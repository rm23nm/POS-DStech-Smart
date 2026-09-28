<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\GateLog;

class GateController extends Controller
{
    /**
     * Endpoint API untuk memvalidasi barcode/RFID dari ESP32
     */
    public function checkAccess(Request $request)
    {
        // Pengecekan Keamanan API
        $secret = $request->header('X-Gate-Secret');
        if ($secret !== 'DSTECH-SECURE-KEY-2026') {
            return response()->json([
                'access' => false,
                'message' => 'Unauthorized Device'
            ], 401);
        }

        // Parameter bisa bernama 'barcode' atau 'rfid' atau 'identifier'
        $identifier = $request->input('identifier');
        $recordOwnerId = $request->input('record_owner_id'); // Bisa dikirim oleh ESP32 atau di-hardcode

        if (empty($identifier)) {
            return response()->json([
                'access' => false,
                'message' => 'Identifier kosong'
            ], 400);
        }

        // 1. Cek di tabel tiket_masuk (Barcode)
        // Kita asumsikan RecordOwnerID bisa diabaikan jika barcode sangat unik,
        // tapi lebih aman jika kita filter.
        $tiketQuery = DB::table('tiket_masuk')->where('BarcodeTiket', $identifier);
        if ($recordOwnerId) {
            $tiketQuery->where('RecordOwnerID', $recordOwnerId);
        }
        $tiket = $tiketQuery->first();

        if ($tiket) {
            if ($tiket->Status == 0) {
                // Tiket Valid & Belum dipakai -> Buka Gate
                // Update status jadi terpakai (1)
                DB::table('tiket_masuk')->where('id', $tiket->id)->update([
                    'Status' => 1,
                    'WaktuPakai' => Carbon::now()
                ]);

                GateLog::create([
                    'identifier' => $identifier,
                    'access_type' => 'TIKET',
                    'status' => 'GRANTED',
                    'message' => 'Akses Diizinkan',
                    'RecordOwnerID' => $recordOwnerId
                ]);

                return response()->json([
                    'access' => true,
                    'type' => 'TIKET',
                    'message' => 'Akses Diizinkan'
                ]);
            } else {
                GateLog::create([
                    'identifier' => $identifier,
                    'access_type' => 'TIKET',
                    'status' => 'DENIED',
                    'message' => 'Tiket Sudah Terpakai',
                    'RecordOwnerID' => $recordOwnerId
                ]);

                // Tiket sudah pernah terpakai
                return response()->json([
                    'access' => false,
                    'type' => 'TIKET',
                    'message' => 'Tiket Sudah Terpakai'
                ]);
            }
        }

        // 2. Jika tidak ada di tiket, cek di tabel Pelanggan (Member / RFID)
        $memberQuery = DB::table('pelanggan')->where('RFID_UID', $identifier);
        if ($recordOwnerId) {
            $memberQuery->where('RecordOwnerID', $recordOwnerId);
        }
        $member = $memberQuery->first();

        if ($member) {
            // Cek status membership
            if ($member->isPaidMembership == 1) {
                $now = Carbon::now()->startOfDay();
                $validUntil = Carbon::parse($member->ValidUntil)->startOfDay();

                if ($validUntil->greaterThanOrEqualTo($now)) {
                    GateLog::create([
                        'identifier' => $identifier,
                        'access_type' => 'MEMBER',
                        'status' => 'GRANTED',
                        'message' => 'Akses Diizinkan',
                        'RecordOwnerID' => $recordOwnerId
                    ]);

                    // Member aktif dan belum expired -> Buka Gate
                    return response()->json([
                        'access' => true,
                        'type' => 'MEMBER',
                        'message' => 'Akses Diizinkan',
                        'nama' => $member->NamaPelanggan
                    ]);
                } else {
                    GateLog::create([
                        'identifier' => $identifier,
                        'access_type' => 'MEMBER',
                        'status' => 'DENIED',
                        'message' => 'Membership Expired',
                        'RecordOwnerID' => $recordOwnerId
                    ]);

                    // Member Expired
                    return response()->json([
                        'access' => false,
                        'type' => 'MEMBER',
                        'message' => 'Membership Expired'
                    ]);
                }
            } else {
                GateLog::create([
                    'identifier' => $identifier,
                    'access_type' => 'MEMBER',
                    'status' => 'DENIED',
                    'message' => 'Bukan Paid Membership',
                    'RecordOwnerID' => $recordOwnerId
                ]);

                return response()->json([
                    'access' => false,
                    'type' => 'MEMBER',
                    'message' => 'Bukan Paid Membership'
                ]);
            }
        }

        // 3. Jika tidak ada di keduanya
        GateLog::create([
            'identifier' => $identifier,
            'access_type' => 'UNKNOWN',
            'status' => 'DENIED',
            'message' => 'Tiket/Kartu Tidak Dikenal',
            'RecordOwnerID' => $recordOwnerId
        ]);

        return response()->json([
            'access' => false,
            'message' => 'Tiket/Kartu Tidak Dikenal'
        ], 404);
    }

    /**
     * Halaman Dasbor Riwayat Gate
     */
    public function indexLogs()
    {
        $logs = GateLog::orderBy('created_at', 'desc')->paginate(50);
        return view('Gate.logs', compact('logs'));
    }

    /**
     * Endpoint API khusus untuk koneksi LAN (HTTP Lokal).
     * Tidak memerlukan HTTPS karena sudah berada di jaringan tertutup.
     * Keamanan dijaga via X-Gate-Secret header.
     * Logika validasi identik dengan checkAccess().
     */
    public function checkAccessLAN(Request $request)
    {
        // Periksa apakah request dari jaringan lokal (opsional, sebagai lapisan keamanan tambahan)
        $clientIp = $request->ip();
        $isLocal = (
            str_starts_with($clientIp, '192.168.') ||
            str_starts_with($clientIp, '10.') ||
            str_starts_with($clientIp, '172.') ||
            $clientIp === '127.0.0.1'
        );

        if (!$isLocal) {
            return response()->json([
                'access' => false,
                'message' => 'Endpoint ini hanya dapat diakses dari jaringan lokal'
            ], 403);
        }

        // Teruskan ke logika checkAccess yang sama
        return $this->checkAccess($request);
    }
}

