<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * GateAgentController
 * Endpoint cloud khusus untuk Gate Agent yang terinstall di PC Client.
 * Menyediakan data tiket & member untuk sinkronisasi lokal,
 * dan menerima update status tiket yang sudah dipakai di gate.
 */
class GateAgentController extends Controller
{
    /**
     * Validasi API Key client
     */
    private function validateApiKey(Request $request): ?array
    {
        $data          = $request->json()->all();
        $recordOwnerId = $data['record_owner_id'] ?? null;
        $apiKey        = $data['api_key']          ?? null;

        if (!$recordOwnerId || !$apiKey) {
            return null;
        }

        // Cek di tabel company apakah API key cocok dengan client
        $company = DB::table('company')
            ->where('KodePartner', $recordOwnerId)
            ->where('GateApiKey', $apiKey)
            ->first();

        return $company ? (array)$company : null;
    }

    /**
     * POST /api/gate/sync-data
     * Mengirim data tiket & member RFID ke Gate Agent di PC Client
     */
    public function syncData(Request $request)
    {
        $company = $this->validateApiKey($request);
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'API Key tidak valid'], 401);
        }

        $recordOwnerId = $company['KodePartner'];

        // Ambil tiket yang belum kadaluarsa / masih relevan (buat dalam 7 hari terakhir)
        $tikets = DB::table('tiket_masuk')
            ->where('RecordOwnerID', $recordOwnerId)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select('id', 'NoTransaksi', 'KodeItem', 'BarcodeTiket', 'Status', 'WaktuPakai')
            ->get()
            ->toArray();

        // Ambil semua member dengan RFID terdaftar
        $members = DB::table('pelanggan')
            ->where('RecordOwnerID', $recordOwnerId)
            ->whereNotNull('RFID_UID')
            ->where('RFID_UID', '!=', '')
            ->select('KodePelanggan', 'NamaPelanggan', 'RFID_UID', 'isPaidMembership', 'ValidUntil')
            ->get()
            ->toArray();

        return response()->json([
            'success' => true,
            'tikets'  => $tikets,
            'members' => $members,
            'synced_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * POST /api/gate/use-ticket
     * Menerima notifikasi dari Gate Agent bahwa tiket sudah dipakai di gate lokal
     * Lalu update status di database cloud
     */
    public function useTicket(Request $request)
    {
        $data          = $request->json()->all();
        $recordOwnerId = $data['record_owner_id'] ?? null;
        $apiKey        = $data['api_key']          ?? null;
        $barcode       = $data['barcode']           ?? null;

        if (!$recordOwnerId || !$apiKey || !$barcode) {
            return response()->json(['success' => false, 'message' => 'Parameter tidak lengkap'], 400);
        }

        // Validasi API Key
        $company = DB::table('company')
            ->where('KodePartner', $recordOwnerId)
            ->where('GateApiKey', $apiKey)
            ->first();

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'API Key tidak valid'], 401);
        }

        // Update tiket di cloud
        $updated = DB::table('tiket_masuk')
            ->where('RecordOwnerID', $recordOwnerId)
            ->where('BarcodeTiket', $barcode)
            ->where('Status', 0)
            ->update([
                'Status'     => 1,
                'WaktuPakai' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        if ($updated > 0) {
            return response()->json(['success' => true, 'message' => 'Tiket berhasil ditandai terpakai']);
        }

        return response()->json(['success' => false, 'message' => 'Tiket tidak ditemukan atau sudah terpakai']);
    }
}
