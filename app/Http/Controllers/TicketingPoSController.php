<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\FakturPenjualanHeader;
use App\Models\FakturPenjualanDetail;
use App\Models\ItemMaster;
use App\Models\DocumentNumbering;
use App\Models\MetodePembayaran;
use App\Services\AccountingService;

class TicketingPoSController extends Controller
{
    public function index(Request $request)
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $company = Company::where('KodePartner', $user->RecordOwnerID)->first();

        // Ambil Data Tiket & Member
        $tickets = DB::table('itemmaster')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('Active', 'Y')
            ->where(function($query) {
                $query->where('KategoriPOS', 'TIKET')
                      ->orWhere('KodeJenisItem', 'TIKET')
                      ->orWhere('KodeJenisItem', '3002')
                      ->orWhere('KodeJenisItem', 'MEMBER')
                      ->orWhere('NamaItem', 'like', '%tiket%')
                      ->orWhere('NamaItem', 'like', '%ticket%')
                      ->orWhere('NamaItem', 'like', '%member%')
                      ->orWhere('NamaItem', 'like', '%langganan%');
            })
            ->get();

        // Ambil Data F&B (exclude tiket)
        $fnbItems = DB::table('itemmaster')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('Active', 'Y')
            ->where(function($query) { $query->where('KategoriPOS', '!=', 'TIKET')->orWhereNull('KategoriPOS'); })
              ->where('KodeJenisItem', '!=', 'TIKET')
              ->where('KodeJenisItem', '!=', '3002')
            ->where('KodeJenisItem', '!=', 'MEMBER')
            ->where('NamaItem', 'not like', '%tiket%')
            ->where('NamaItem', 'not like', '%ticket%')
            ->where('NamaItem', 'not like', '%member%')
            ->where('NamaItem', 'not like', '%langganan%')
            ->get();

        $fnbCategories = DB::table('jenisitem')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->get();

        $pelanggan = DB::table('pelanggan')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->get();

        $metodePembayaran = MetodePembayaran::where('RecordOwnerID', $user->RecordOwnerID)->get();

        $midtransclientkey = config('midtrans.client_key');

                $gruppelanggan = \App\Models\GrupPelanggan::where('RecordOwnerID', \Auth::user()->RecordOwnerID)->get();
        return view('Transaksi.Penjualan.PoS.TicketingPoS', [
            'gruppelanggan' => $gruppelanggan,
            'company' => $company,
            'tickets' => $tickets,
            'fnbItems' => $fnbItems,
            'fnbCategories' => $fnbCategories,
            'pelanggan' => $pelanggan,
            'metodePembayaran' => $metodePembayaran,
            'midtransclientkey' => $midtransclientkey
        ]);
    }

    public function generateTickets(Request $request)
    {
        $noTransaksi = $request->input('NoTransaksi');
        $idUser = Auth::user()->id;
        $user = User::find($idUser);

        if (!$noTransaksi) {
            return response()->json(['success' => false, 'message' => 'NoTransaksi required'], 400);
        }

        $detail = DB::table('fakturpenjualandetail')
            ->where('NoTransaksi', $noTransaksi)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->get();

        $generatedBarcodes = [];

        foreach ($detail as $row) {
            for ($i = 0; $i < $row->Qty; $i++) {
                $barcode = date('ymd') . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
                
                DB::table('tiket_masuk')->insert([
                    'NoTransaksi' => $noTransaksi,
                    'KodeItem' => $row->KodeItem,
                    'BarcodeTiket' => $barcode,
                    'Status' => 0,
                    'RecordOwnerID' => $user->RecordOwnerID,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                $generatedBarcodes[] = [
                    'ItemName' => $row->NamaItem,
                    'Barcode' => $barcode
                ];
            }
        }

        return response()->json([
            'success' => true,
            'barcodes' => $generatedBarcodes
        ]);
    }

    public function storeTicketing(Request $request)
    {
        $jsonData = $request->json()->all();
        DB::beginTransaction();

        $user = Auth::user();
        $oCompany = Company::where('KodePartner', $user->RecordOwnerID)->first();
        $errorCount = 0;
        $message = '';

        try {
            $currentDate = Carbon::now('Asia/Jakarta');
            $Year = $currentDate->format('Y');
            $Month = $currentDate->format('m');

            $numberingData = new DocumentNumbering();
            $NoTransaksi = $numberingData->GetNewDoc("POS", "fakturpenjualanheader", "NoTransaksi");

            $model = new FakturPenjualanHeader;
            $model->Periode = $Year . $Month;
            $model->NoTransaksi = $NoTransaksi;
            $model->Transaksi = 'POS';
            $model->TglTransaksi = $currentDate->toDateString();
            $model->TglJatuhTempo = $currentDate->toDateString();
            $model->NoReff = '';
            $model->KodePelanggan = empty($jsonData['KodePelanggan']) || $jsonData['KodePelanggan'] === 'CASH' ? 'UMUM' : $jsonData['KodePelanggan'];
            $model->KodeTermin = 'CASH';
            $model->Termin = 0;
            $model->TotalTransaksi = $jsonData['Subtotal'];
            $model->Potongan = $jsonData['Potongan'] ?? 0;
            $model->Pajak = $jsonData['PPN'] ?? 0;
            $model->PajakHiburan = $jsonData['PB1'] ?? 0;
            $model->BiayaLayanan = 0;
            $model->TotalPembelian = $jsonData['GrandTotal'];
            $model->TotalRetur = 0;
            $model->TotalPembayaran = $jsonData['NominalBayar'];
            $model->Pembulatan = 0;
            $model->Status = 'C';
            $model->Keterangan = 'POS Ticketing - ' . ($jsonData['MemberName'] ?? 'General');
            $model->MetodeBayar = $jsonData['MetodePembayaranId'];
            $model->ReffPembayaran = '';
            $model->KodeSales = '';
            $model->Posted = 1;
            $model->CreatedBy = $user->name;
            $model->UpdatedBy = "";
            $model->RecordOwnerID = $user->RecordOwnerID;

            $save = $model->save();

            if (!$save) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan header transaksi']);
            }

            $noUrut = 1;
            $generatedBarcodes = [];

            foreach ($jsonData['items'] as $item) {
                if ($item['qty'] <= 0) continue;

                $oItem = ItemMaster::where('RecordOwnerID', $user->RecordOwnerID)
                            ->where('KodeItem', $item['code'])
                            ->first();

                if (!$oItem) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Item ' . $item['name'] . ' tidak ditemukan']);
                }

                $modelDetail = new FakturPenjualanDetail;
                $modelDetail->NoTransaksi = $NoTransaksi;
                $modelDetail->NoUrut = $noUrut++;
                $modelDetail->KodeItem = $item['code'];
                $modelDetail->Qty = $item['qty'];
                $modelDetail->QtyKonversi = $item['qty'];
                $modelDetail->QtyRetur = 0;
                $modelDetail->Satuan = $oItem->Satuan ?? 'PCS';
                $modelDetail->Harga = $item['price'];
                $modelDetail->Discount = 0;
                $modelDetail->BaseReff = '';
                $modelDetail->BaseLine = -1;
                $modelDetail->KodeGudang = $oCompany->GudangPoS ?? 'GDG01';
                
                $HargaGros = $item['qty'] * $item['price'];
                $modelDetail->HargaNet = $HargaGros;
                $modelDetail->LineStatus = 'C';
                $modelDetail->VatPercent = $oCompany->PPN ?? 0;
                $modelDetail->HargaPokokPenjualan = $oItem->HargaPokokPenjualan ?? 0;
                $modelDetail->RecordOwnerID = $user->RecordOwnerID;
                
                // Distribusi proporsional pajak (sederhana)
                $proporsi = $HargaGros / max(1, $jsonData['Subtotal']);
                $modelDetail->Pajak = ($jsonData['PPN'] ?? 0) * $proporsi;
                $modelDetail->PajakHiburan = ($jsonData['PB1'] ?? 0) * $proporsi;
                
                $saveDetail = $modelDetail->save();

                if (!$saveDetail) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Gagal menyimpan detail transaksi']);
                }

                // Jika ini adalah TIKET, generate tiket masuk & kurangi stok
                if ($item['type'] === 'TIKET') {
                    for ($i = 0; $i < $item['qty']; $i++) {
                        $barcode = date('ymd') . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
                        
                        DB::table('tiket_masuk')->insert([
                            'NoTransaksi' => $NoTransaksi,
                            'KodeItem' => $item['code'],
                            'BarcodeTiket' => $barcode,
                            'Status' => 0,
                            'RecordOwnerID' => $user->RecordOwnerID,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);

                        $generatedBarcodes[] = [
                            'ItemName' => $item['name'],
                            'Barcode' => $barcode
                        ];
                    }
                } elseif ($item['type'] === 'MEMBER' && !empty($jsonData['KodePelanggan']) && $jsonData['KodePelanggan'] !== 'CASH') {
                    // Update Masa Aktif Member
                    $pelanggan = DB::table('pelanggan')
                        ->where('RecordOwnerID', $user->RecordOwnerID)
                        ->where('KodePelanggan', $jsonData['KodePelanggan'])
                        ->first();
                        
                    if ($pelanggan) {
                        $package = DB::table('member_packages')
                            ->where('RecordOwnerID', $user->RecordOwnerID)
                            ->where('KodePaket', $item['code'])
                            ->first();

                        if ($package) {
                            $currentValidUntil = $pelanggan->ValidUntil ? Carbon::parse($pelanggan->ValidUntil) : Carbon::now();
                            if ($currentValidUntil->isPast()) {
                                $currentValidUntil = Carbon::now();
                            }
                            $newValidUntil = $currentValidUntil->addDays(($package->ValidDays ?? 30) * $item['qty'])->format('Y-m-d');
                            $maxPlay = ($package->MaxPlay ?? 0) ? (($package->MaxPlay ?? 0) * $item['qty']) : 0;
                            
                            // --- NEW: Insert/Update customer_memberships ---
                            $existingMembership = DB::table('customer_memberships')
                                ->where('RecordOwnerID', $user->RecordOwnerID)
                                ->where('KodePelanggan', $jsonData['KodePelanggan'])
                                ->where('KodePaketMember', $package->KodePaket)
                                ->orderBy('ValidUntil', 'desc')
                                ->first();

                            $currentValidUntil_cm = Carbon::now('Asia/Jakarta');
                            if ($existingMembership && $existingMembership->ValidUntil) {
                                $exDate = Carbon::parse($existingMembership->ValidUntil, 'Asia/Jakarta');
                                if ($exDate->isFuture()) {
                                    $currentValidUntil_cm = $exDate;
                                }
                            }
                            
                            $addedDays = ($package->ValidDays ?? 30) * $item['qty'];
                            $newValidUntil_cm = $currentValidUntil_cm->addDays($addedDays)->format('Y-m-d');
                            $addedMaxPlay = ($package->MaxPlay ?? 0) * $item['qty'];
                            
                            if ($existingMembership && Carbon::parse($existingMembership->ValidUntil, 'Asia/Jakarta')->isFuture()) {
                                DB::table('customer_memberships')
                                    ->where('id', $existingMembership->id)
                                    ->update([
                                        'ValidUntil' => $newValidUntil_cm,
                                        'MaxPlay' => DB::raw("MaxPlay + $addedMaxPlay")
                                    ]);
                            } else {
                                DB::table('customer_memberships')->insert([
                                    'KodePelanggan' => $jsonData['KodePelanggan'],
                                    'KodePaketMember' => $package->KodePaket,
                                    'ValidFrom' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                                    'ValidUntil' => $newValidUntil_cm,
                                    'MaxPlay' => $addedMaxPlay,
                                    'Played' => 0,
                                    'maxTimePerPlay' => $package->maxTimePerPlay ?? 0,
                                    'RecordOwnerID' => $user->RecordOwnerID,
                                    'created_at' => Carbon::now('Asia/Jakarta')
                                ]);
                            }
                            // -------------------------------------------

                            $updateData = [
                                'isPaidMembership' => 1,
                                'ValidUntil' => $newValidUntil,
                                'TglBerlanggananPaketBulanan' => Carbon::now()->format('Y-m-d'),
                                'MemberPrice' => $package->Harga,
                                'Played' => 0,
                                'KodePaketMember' => $item['code']
                            ];

                            if ($maxPlay > 0) {
                                $updateData['MaxPlay'] = DB::raw("COALESCE(MaxPlay, 0) + $maxPlay");
                            }
                            if (($package->maxTimePerPlay ?? 0) > 0) {
                                $updateData['maxTimePerPlay'] = $package->maxTimePerPlay;
                            }

                            DB::table('pelanggan')
                                ->where('RecordOwnerID', $user->RecordOwnerID)
                                ->where('KodePelanggan', $jsonData['KodePelanggan'])
                                ->update($updateData);

                        } else {
                            $itemName = strtolower($item['name']);
                            $monthsToAdd = 1; // Default 1 bulan
                            
                            if (str_contains($itemName, '3 bulan')) {
                                $monthsToAdd = 3;
                            } elseif (str_contains($itemName, '6 bulan')) {
                                $monthsToAdd = 6;
                            } elseif (str_contains($itemName, '1 tahun') || str_contains($itemName, '12 bulan')) {
                                $monthsToAdd = 12;
                            } elseif (str_contains($itemName, '1 bulan')) {
                                $monthsToAdd = 1;
                            }

                            // Kalikan dengan jumlah QTY yang dibeli
                            $monthsToAdd = $monthsToAdd * $item['qty'];

                            $currentValidUntil = $pelanggan->ValidUntil ? Carbon::parse($pelanggan->ValidUntil) : Carbon::now();
                            
                            // Jika sudah kedaluwarsa, mulai dari hari ini. Jika masih aktif, akumulasikan.
                            if ($currentValidUntil->isPast()) {
                                $currentValidUntil = Carbon::now();
                            }
                            
                            $newValidUntil = $currentValidUntil->addMonths($monthsToAdd)->format('Y-m-d');
                            
                            DB::table('pelanggan')
                                ->where('RecordOwnerID', $user->RecordOwnerID)
                                ->where('KodePelanggan', $jsonData['KodePelanggan'])
                                ->update([
                                    'isPaidMembership' => 1,
                                    'ValidUntil' => $newValidUntil,
                                    'KodePaketMember' => $item['code']
                                ]);
                        }
                    }
                }
                
                // Kurangi Stok Item
                if ($oCompany->AllowNegativeInventory == 'N') {
                    if ($oItem->Stock < $item['qty'] && $oItem->TypeItem != 4 && $oItem->TypeItem != 2) {
                        DB::rollBack();
                        return response()->json(['success' => false, 'message' => 'Stok ' . $item['name'] . ' tidak mencukupi']);
                    }
                }
                
                if ($oItem->TypeItem != 4 && $oItem->TypeItem != 2) {
                    $oItem->Stock -= $item['qty'];
                    $oItem->save();
                }
            }

            // POST ACCOUNTING
            if ($oCompany->isPostingAkutansi == 1) {
                $journal = new AccountingService();
                $journal->initialize("OINV", $currentDate->toDateString(), $NoTransaksi, "O", false);

                // 1. Kas/Bank (Debit)
                $metode = MetodePembayaran::where('RecordOwnerID', $user->RecordOwnerID)
                                ->where('id', $jsonData['MetodePembayaranId'])->first();

                if (!$metode || empty($metode->AkunPembayaran)) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Metode Pembayaran tidak memiliki akun akuntansi yang valid']);
                }
                
                $res = $journal->addDetailWithAccount($metode->AkunPembayaran, 1, $jsonData['GrandTotal'], $model->Keterangan);
                if (!$res['success']) { DB::rollBack(); return response()->json($res); }

                // 2. Pendapatan (Kredit)
                // Menggunakan akun pendapatan default perusahaan
                $res = $journal->addDetailFromSetting("InvAcctPendapatanJual", 2, $jsonData['Subtotal'] - ($jsonData['Potongan'] ?? 0), $model->Keterangan);
                if (!$res['success']) { DB::rollBack(); return response()->json($res); }

                // 3. PPN (Kredit)
                if (($jsonData['PPN'] ?? 0) > 0) {
                    $res = $journal->addDetailFromSetting("PjAcctPajakPenjualan", 2, $jsonData['PPN'], $model->Keterangan);
                    if (!$res['success']) { DB::rollBack(); return response()->json($res); }
                }

                // 4. Pajak Hiburan/PB1 (Kredit)
                if (($jsonData['PB1'] ?? 0) > 0) {
                    $res = $journal->addDetailFromSetting("PjAcctPajakHiburan", 2, $jsonData['PB1'], $model->Keterangan);
                    if (!$res['success']) { DB::rollBack(); return response()->json($res); }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'invoiceNo' => $NoTransaksi,
                'kembalian' => max(0, $jsonData['NominalBayar'] - $jsonData['GrandTotal']),
                'barcodes' => $generatedBarcodes
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    public function createMidTransTransaction(Request $request)
    {
        $user = Auth::user();
        $MetodeBayar = $request->MetodePembayaranId;
        $GrandTotal = $request->GrandTotal;

        $GetSetting = DB::table('metodepembayaran')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('id', $MetodeBayar)
            ->first();

        if ($GetSetting) {
            \Midtrans\Config::$serverKey = $GetSetting->ServerKey;
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            $params = array(
                'transaction_details' => array(
                    'order_id' => rand(),
                    'gross_amount' => $GrandTotal,
                ),
                'customer_details' => array(
                    'first_name' => 'Pelanggan',
                    'last_name' => 'POS',
                    'email' => 'pos@pos.com',
                    'phone' => '08111222333',
                ),
            );

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                return response()->json(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        return response()->json(['success' => false, 'message' => 'Metode Pembayaran tidak valid']);
    }

    public function printThermal($NoTransaksi)
    {
        $user = Auth::user();
        
        $header = DB::table('fakturpenjualanheader')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('NoTransaksi', $NoTransaksi)
            ->first();

        if (!$header) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $details = DB::table('fakturpenjualandetail as fd')
            ->join('itemmaster as i', function($join) use ($user) {
                $join->on('fd.KodeItem', '=', 'i.KodeItem')
                     ->where('i.RecordOwnerID', '=', $user->RecordOwnerID);
            })
            ->where('fd.RecordOwnerID', $user->RecordOwnerID)
            ->where('fd.NoTransaksi', $NoTransaksi)
            ->select('fd.*', 'i.NamaItem')
            ->get();

        $tickets = DB::table('tiket_masuk')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('NoTransaksi', $NoTransaksi)
            ->get();

        $company = DB::table('company')
            ->where('KodePartner', $user->RecordOwnerID)
            ->first();

        return view('Transaksi.Penjualan.PoS.PrintThermalTiket', [
            'header' => $header,
            'details' => $details,
            'tickets' => $tickets,
            'company' => $company
        ]);
    }

    public function checkVoucher(Request $request)
    {
        try {
            $idUser = Auth::user()->id;
            $user = User::find($idUser);
            $kodeVoucher = $request->KodeVoucher;
            $subtotal = $request->Subtotal;

            $voucher = DB::table('discountvoucher')
                ->where('VoucherCode', $kodeVoucher)
                ->where('RecordOwnerID', $user->RecordOwnerID)
                ->first();

            if (!$voucher) {
                return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan.']);
            }

            $today = Carbon::today()->format('Y-m-d');
            if ($today < $voucher->StartDate || $today > $voucher->EndDate) {
                return response()->json(['success' => false, 'message' => 'Voucher sudah kadaluarsa atau belum aktif.']);
            }

            if ($voucher->DiscountUsed >= $voucher->DiscountQuota) {
                return response()->json(['success' => false, 'message' => 'Kuota voucher sudah habis.']);
            }

            $discountNominal = $subtotal * ($voucher->DiscountPercent / 100);
            
            // Perbaiki logika jika MaximalDiscount = 0, maka tidak ada batas
            if ($voucher->MaximalDiscount > 0 && $discountNominal > $voucher->MaximalDiscount) {
                $discountNominal = $voucher->MaximalDiscount;
            }

            return response()->json([
                'success' => true,
                'discount' => $discountNominal,
                'message' => 'Voucher berhasil diterapkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function checkInMember(Request $request)
    {
        $user = Auth::user();
        $uid = $request->input('RFID_UID');

        if (!$uid) {
            return response()->json(['success' => false, 'message' => 'Kartu RFID harus discan.']);
        }

        // Cari pelanggan
        $member = DB::table('pelanggan')
                    ->where(function($q) use ($uid) {
                        $q->where('RFID_UID', $uid)
                          ->orWhere('Keterangan', $uid)
                          ->orWhere('KodePelanggan', $uid);
                    })
                    ->where('RecordOwnerID', $user->RecordOwnerID)
                    ->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Kartu Member tidak terdaftar.']);
        }

        // Cari semua paket aktif pelanggan
        $activeMemberships = DB::table('customer_memberships')
            ->join('member_packages', function($join) {
                $join->on('customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')
                     ->on('customer_memberships.RecordOwnerID', '=', 'member_packages.RecordOwnerID');
            })
            ->join('itemmaster', function($join) {
                $join->on('customer_memberships.KodePaketMember', '=', 'itemmaster.KodeItem')
                     ->on('customer_memberships.RecordOwnerID', '=', 'itemmaster.RecordOwnerID');
            })
            ->where('customer_memberships.KodePelanggan', $member->KodePelanggan)
            ->where('customer_memberships.RecordOwnerID', $user->RecordOwnerID)
            ->where('customer_memberships.ValidUntil', '>=', Carbon::now('Asia/Jakarta'))
            ->select('customer_memberships.*', 'member_packages.KategoriPaket', 'itemmaster.NamaItem')
            ->get();

        if ($activeMemberships->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Member ini tidak memiliki paket aktif yang tersedia atau sudah kedaluwarsa.']);
        }

        $validMembership = null;

        foreach ($activeMemberships as $am) {
            // Abaikan paket meja/hiburan jika check-in dilakukan dari Ticketing POS (manual kasir gym/kolam)
            if ($am->KategoriPaket === 'HIBURAN') continue;

            // Cek kuota
            if ($am->MaxPlay == 0 || $am->Played < $am->MaxPlay) {
                $validMembership = $am;
                break;
            }
        }

        if (!$validMembership) {
            return response()->json(['success' => false, 'message' => 'Member memiliki paket aktif, namun kuota kunjungannya sudah habis atau paket bukan untuk Gym/Kolam.']);
        }

        // Potong kuota
        DB::table('customer_memberships')
            ->where('id', $validMembership->id)
            ->update([
                'Played' => DB::raw('Played + 1')
            ]);
            
        // (Opsional) Rekam ke riwayat kunjungan jika diperlukan
        try {
            DB::table('tiket_masuk')->insert([
                'NoTransaksi' => 'CHECKIN-MEMBER-' . time(),
                'KodeItem' => $validMembership->KodePaketMember,
                'BarcodeTiket' => $member->KodePelanggan,
                'Status' => 1,
                'WaktuPakai' => Carbon::now('Asia/Jakarta'),
                'RecordOwnerID' => $user->RecordOwnerID,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);
        } catch (\Exception $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Check-In Berhasil untuk ' . $member->NamaPelanggan . ' menggunakan paket ' . $validMembership->NamaItem . '.',
            'member_name' => $member->NamaPelanggan,
            'package_name' => $validMembership->NamaItem,
            'played' => $validMembership->Played + 1,
            'max_play' => $validMembership->MaxPlay == 0 ? 'Unlimited' : $validMembership->MaxPlay
        ]);
    }
}

