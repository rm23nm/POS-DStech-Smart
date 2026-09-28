<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;

use App\Models\TableOrderHeader;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\TitikLampu;
use App\Models\Paket;
use App\Models\BookingOnline;
use App\Models\Pelanggan;
use App\Models\DocumentNumbering;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelangganExport;
use App\Models\User;
use App\Models\DiscountVoucher;
use App\Models\MetodePembayaran;

use Illuminate\Support\Facades\Mail;
use App\Mail\KonfirmasiPembayaranMail;
use App\Mail\SendMail;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\UserRole;

class BookingOnlineController extends Controller
{
    public function indexRev2($id){
        $idE = base64_decode($id); // ⬅️ decode di sini
        $company = Company::where('KodePartner','=',$idE)->first();
        $gallery = Company::select('ImageGallery1', 'ImageGallery2', 'ImageGallery3','ImageGallery4','ImageGallery5','ImageGallery6','ImageGallery7','ImageGallery8','ImageGallery9','ImageGallery10','ImageGallery11','ImageGallery12')
                    ->where('KodePartner', $idE)
                    ->get();
        $paketTransaksi = Paket::where('RecordOwnerID','=',$idE)
                            ->where(DB::RAW("COALESCE(BisaDipesan, 'N')"), 'Y')->get();
        $user= User::where('RecordOwnerID','=',$idE)->first();

        // Fetch FnB Categories and initial products for online booking
        $fnbCategories = DB::table('jenisitem')
                            ->where('RecordOwnerID', $idE)
                            ->get();

        $titikLampu = TitikLampu::selectRaw("titiklampu.*, tkelompoklampu.NamaKelompok")
                    ->leftjoin('tkelompoklampu', function ($value)  {
                        $value->on('titiklampu.KelompokLampu','=','tkelompoklampu.KodeKelompok')
                        ->on('titiklampu.RecordOwnerID','=','tkelompoklampu.RecordOwnerID');
                    })
                    ->where('titiklampu.BisaDipesan', 1)
                    ->where('titiklampu.RecordOwnerID', $idE)
                    ->get();
        Log::debug("indexRev2: idE=$idE, titikLampu count=" . count($titikLampu));
        $today = Carbon::today()->toDateString();


        $userdata = UserRole::selectRaw("users.*")
                ->leftJoin('users', function ($value){
                    $value->on('userrole.userid','=','users.id')
                    ->on('userrole.RecordOwnerID','=','users.RecordOwnerID');
                })
                ->leftJoin('roles', function ($value){
                    $value->on('roles.id','=','userrole.roleid')
                    ->on('roles.RecordOwnerID','=','userrole.RecordOwnerID');
                })
                ->where('roles.RoleName', 'SuperAdmin')
                ->where('userrole.RecordOwnerID', $idE)
                ->first();

        $midtransdata = MetodePembayaran::where('RecordOwnerID','=',$idE)
                            ->where('MetodeVerifikasi','=','AUTO')->first();
        $midtransclientkey = "";
        $MetodePembayaranAutoID = -1;
        // dd($midtransdata->ClientKey);
        if ($midtransdata) {
            $midtransclientkey = $midtransdata->ServerKey;
            $MetodePembayaranAutoID = $midtransdata->id;
        }

        $galleryImages = [];
        $videoDisplay = [];

        $galleryImages[] = [
            'slot' => -1,
            'url' => $company->BannerBooking
        ];

        // Loop dari 1 sampai 12
        
        for ($i = 1; $i <= 12; $i++) {
            $field = "ImageGallery{$i}";

            if (!empty($company->$field)) {
                $galleryImages[] = [
                    'slot' => $i,
                    'url' => $company->$field
                ];
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            $field = "VideoCustomerDisplay{$i}";
            if (!empty($company->$field)) {
                $videoDisplay[] = $company->$field;
            }
            
        }
        

        $groupedLampu = $titikLampu->groupBy('NamaKelompok');

        switch ($company->DefaultLandingPages) {
            case 'bo1':
                $Tahun = Carbon::now()->year;
                return view('Transaksi.Penjualan.PoS.BookingOnline', compact('company', 'titikLampu','gallery','paketTransaksi','user', 'midtransclientkey', 'today', 'groupedLampu', 'userdata', 'Tahun', 'fnbCategories'));
                break;
            case 'bo2':
                $hargaMinimal = $paketTransaksi->min('HargaNormal');
                $Tahun = Carbon::now()->year;
                return view("Transaksi.Penjualan.PoS.BookingOnline_2", compact('company', 'titikLampu', 'gallery', 'paketTransaksi', 'user', 'midtransclientkey', 'today', 'groupedLampu', 'videoDisplay', 'hargaMinimal', 'galleryImages', 'userdata', 'Tahun', 'fnbCategories'));
                break;
            default:
                return redirect()->action([BookingOnlineController::class, 'index'], ['id' => $id]);
                break;
        }
    }

    public function getFnBItems(Request $request)
    {
        $RecordOwnerID = $request->input('RecordOwnerID');
        $KodeKelompok = $request->input('KodeKelompok');

        $query = DB::table('itemmaster')
            ->where('RecordOwnerID', $RecordOwnerID)
            ->where('Active', 'Y')
            ->whereIn('TypeItem', [1, 2]); // Food & Drink

        if ($KodeKelompok && $KodeKelompok != 'all') {
            $query->where('KodeJenisItem', $KodeKelompok);
        }

        $items = $query->get();

        return response()->json($items);
    }

    public function getjadwalMeja(Request $request){
        $mejaData = [];
        $RecordOwnerID = $request->input('RecordOwnerID');
        $PaketID = $request->input('PaketID');
        $tanggalBooking = $request->input('TglBooking');

        $company = Company::where('KodePartner','=', $RecordOwnerID)->first();
        if (!$company) return response()->json([]);

        $jamMulai = Carbon::createFromFormat('H:i:s', $company->JamAwalBooking);
        $jamSelesai = Carbon::createFromFormat('H:i:s', $company->JamAkhirBooking);
        $now = Carbon::now();

        if ($jamSelesai->lessThan($jamMulai)) {
            $jamSelesai->addDay();
        }

        $paketTransaksi = Paket::where('RecordOwnerID','=',$RecordOwnerID)
                            ->where(DB::RAW("COALESCE(BisaDipesan, 'N')"), 'Y')
                            ->where('id', $PaketID)->first();

        if (!$paketTransaksi) return response()->json([]);

        // Ambil semua TitikLampu
        $semuaTitik = TitikLampu::selectRaw("titiklampu.*, tkelompoklampu.NamaKelompok, COALESCE(titiklampu.Deskripsi,'') AS 'Desc'")
                        ->where('BisaDipesan', 1)
                        ->leftJoin('tkelompoklampu', function ($value){
                            $value->on('tkelompoklampu.KodeKelompok','=','titiklampu.KelompokLampu')
                            ->on('tkelompoklampu.RecordOwnerID','=','titiklampu.RecordOwnerID');
                        })
                        ->where('titiklampu.RecordOwnerID', '=', $RecordOwnerID)->get();

        // Ambil semua Booking & Order untuk hari tersebut (Sekali query)
        $allBookings = BookingOnline::where('RecordOwnerID', $RecordOwnerID)
            ->where('StatusTransaksi', 0)
            ->where('TglBooking', $tanggalBooking)
            ->get();

        $allOrders = TableOrderHeader::where('RecordOwnerID', $RecordOwnerID)
            ->whereIn('DocumentStatus', ['D', 'O'])
            ->get();

        foreach ($semuaTitik as $titik) {
            $start = $jamMulai->copy();
            $jadwal = [];
            
            $tableBookings = $allBookings->where('mejaID', $titik->id);
            $tableOrders = $allOrders->where('tableid', $titik->id);

            while ($start < $jamSelesai) {
                $next = $start->copy()->addHour();
                $jamString = $start->format('H:i') . ' - ' . $next->format('H:i');
                
                $daysOffset = $start->copy()->startOfDay()->diffInDays($jamMulai->copy()->startOfDay());
                $fullStart = Carbon::parse($tanggalBooking)->addDays($daysOffset)->setTimeFrom($start);
                
                $daysOffsetNext = $next->copy()->startOfDay()->diffInDays($jamMulai->copy()->startOfDay());
                $fullEnd = Carbon::parse($tanggalBooking)->addDays($daysOffsetNext)->setTimeFrom($next);

                $fullStartStr = $fullStart->format('Y-m-d H:i:s');
                $fullEndStr = $fullEnd->format('Y-m-d H:i:s');

                // Cek Booking (In-memory)
                $adaBooking = $tableBookings->filter(function($q) use ($fullStartStr, $fullEndStr, $tanggalBooking) {
                    $bStart = $tanggalBooking . ' ' . $q->JamMulai;
                    $bEnd = $tanggalBooking . ' ' . $q->JamSelesai;
                    return ($bStart < $fullEndStr && $bEnd > $fullStartStr);
                })->isNotEmpty();

                // Cek Order (In-memory)
                $adaOrder = $tableOrders->filter(function($q) use ($fullStartStr, $fullEndStr, $tanggalBooking) {
                    $oStart = $q->JamMulai;
                    $oEnd = $q->JamSelesai ?? '9999-12-31 23:59:59';

                    if ($q->Status == 1) {
                        $overlap = ($oStart <= $fullEndStr && $oEnd >= $fullEndStr);
                        if ($q->JenisPaket == 'PAYPERUSE') {
                            return $overlap && ($q->TglTransaksi == $tanggalBooking);
                        }
                        return $overlap;
                    } else {
                        return ($oStart < $fullEndStr && $oEnd > $fullStartStr);
                    }
                })->isNotEmpty();

                $slotSudahLewat = $fullEnd->lessThan($now);
                $status = ($adaBooking || $adaOrder || $slotSudahLewat) ? 'booked' : 'available';

                $jadwal[] = [
                    'jam' => $jamString,
                    'harga' => $paketTransaksi->HargaNormal,
                    'status' => $status,
                    'jammulai' => $start->format('H:i'),
                    'jamselesai' => $next->format('H:i')
                ];

                $start = $next;
            }

            $mejaData[] = [
                'id' => $titik->id,
                'nama' => $titik->NamaTitikLampu,
                'deskripsi' => ($titik->Desc == "" ? 'Meja standar dengan suasana santai' : $titik->Desc) ,
                'KelompokMeja' => $titik->NamaKelompok,
                'KelompokMejaSlug' => \Illuminate\Support\Str::slug($titik->NamaKelompok),
                'fitur' => [],
                'jadwal' => $jadwal
            ];
        }

        return response()->json($mejaData);
    }   
    public function index($id)
    {
        // dd($id);
        $idE = base64_decode($id); // ⬅️ decode di sini
        $company = Company::where('KodePartner','=',$idE)->first();
        $titikLampu = TitikLampu::where('BisaDipesan', 1)
        ->where('RecordOwnerID', $idE)
        ->get();
        $gallery = Company::select('ImageGallery1', 'ImageGallery2', 'ImageGallery3','ImageGallery4','ImageGallery5','ImageGallery6','ImageGallery7','ImageGallery8','ImageGallery9','ImageGallery10','ImageGallery11','ImageGallery12')
        ->where('KodePartner', $idE)
        ->get();
        $paketTransaksi = Paket::where('RecordOwnerID','=',$idE)
                            ->where(DB::RAW("COALESCE(BisaDipesan, 'N')"), 'Y')->get();
        $user= User::where('RecordOwnerID','=',$idE)->first();

        $midtransdata = MetodePembayaran::where('RecordOwnerID','=',$idE)
                            ->where('MetodeVerifikasi','=','AUTO')->first();
        $midtransclientkey = "";
        $MetodePembayaranAutoID = -1;
        // dd($midtransdata->ClientKey);
        if ($midtransdata) {
            $midtransclientkey = $midtransdata->ServerKey;
            $MetodePembayaranAutoID = $midtransdata->id;
        }
        $today = date('Y-m-d');

        //dd($company);

        // dd($paketTransaksi);

        return view('Transaksi.Penjualan.PoS.BookingOnline', compact('company', 'titikLampu','gallery','paketTransaksi','user', 'midtransclientkey', 'today'));
    }

    public function getData()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman booking internal.');
        }
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        $company = Company::where('KodePartner', '=', $RecordOwnerID)->first();
        $titikLampu = TitikLampu::selectRaw("titiklampu.*, tkelompoklampu.NamaKelompok")
            ->leftjoin('tkelompoklampu', function ($value)  {
                $value->on('titiklampu.KelompokLampu','=','tkelompoklampu.KodeKelompok')
                ->on('titiklampu.RecordOwnerID','=','tkelompoklampu.RecordOwnerID');
            })
            ->where('titiklampu.BisaDipesan', 1)
            ->where('titiklampu.RecordOwnerID', $RecordOwnerID)
            ->get();
        $gallery = Company::select('ImageGallery1', 'ImageGallery2', 'ImageGallery3', 'ImageGallery4', 'ImageGallery5', 'ImageGallery6', 'ImageGallery7', 'ImageGallery8', 'ImageGallery9', 'ImageGallery10', 'ImageGallery11', 'ImageGallery12')
            ->where('KodePartner', $RecordOwnerID)
            ->get();
        $paketTransaksi = Paket::where('RecordOwnerID', '=', $RecordOwnerID)->get();
        $user = User::where('RecordOwnerID', '=', $RecordOwnerID)->first();

        $midtransdata = MetodePembayaran::where('RecordOwnerID', '=', $RecordOwnerID)
            ->where('MetodeVerifikasi', '=', 'AUTO')->first();
        $midtransclientkey = "";
        if ($midtransdata) {
            $midtransclientkey = $midtransdata->ServerKey;
        }
        $today = date('Y-m-d');
        $groupedLampu = $titikLampu->groupBy('NamaKelompok');
        $userdata = Auth::user();
        $Tahun = \Carbon\Carbon::now()->year;
        $fnbCategories = \Illuminate\Support\Facades\DB::table('jenisitem')
                            ->where('RecordOwnerID', $RecordOwnerID)
                            ->get();

        return view('Transaksi.Penjualan.PoS.BookingOnline', compact('company', 'titikLampu', 'gallery', 'paketTransaksi', 'user', 'midtransclientkey', 'today', 'groupedLampu', 'userdata', 'Tahun', 'fnbCategories'));
    }

public function createMidTransTransaction(Request $request)
{
    $jsonData = $request->json()->all();

    $TotalPembelian = $jsonData['TotalPembelian'];
    $oCompany = Company::where('KodePartner','=',$jsonData['kodePartner'])->first();

    $midtransdata = MetodePembayaran::where('RecordOwnerID','=',$jsonData['kodePartner'])
                            ->where('MetodeVerifikasi','=','AUTO')->first();
    $midtransclientkey = "";
    $MetodePembayaranAutoID = -1;
    // dd($midtransdata->ClientKey);
    if ($midtransdata) {
        $midtransclientkey = $midtransdata->ServerKey;
        $MetodePembayaranAutoID = $midtransdata->id;
    }

    if ($midtransclientkey == "") {
        return response()->json(['error' => 'Pembayaran Belum bisa dilakukan, Silahkan Hubungi Administrator']);
    }
    
    
    Config::$serverKey = $midtransclientkey;
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = config('midtrans.is_sanitized');
    Config::$is3ds = config('midtrans.is_3ds');

    // Data transaksi yang akan dikirimkan ke Midtrans
    $transaction_details = [
        'order_id' => uniqid(),
        'gross_amount' => floatval($TotalPembelian), // Jumlah total transaksi
    ];

    $customer_details = [
        'first_name' => $oCompany->NamaPartner,
    ];

    $transaction = [
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
    ];

    try {
        $snapToken = Snap::getSnapToken($transaction);
        return response()->json(['snap_token' => $snapToken]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
}


function SimpanPembayaranJson(Request $request) {
    $data = array('success' => false, 'message' => '', 'data' => array(), 'Kembalian' => "");

    $jsonData = $request->json()->all();
    //dd($jsonData);
    $isFinished = false;
    DB::beginTransaction(); // Mulai transaksi
    try {
        $NoTransaksi = "";
        $prefix = date('ymd'); // Format YYMMDD
        $lastNoTrx = BookingOnline::where(DB::raw('LEFT(NoTransaksi,6)'),'=',$prefix)->count() + 1;
        $NoTransaksi = $prefix . str_pad($lastNoTrx, 4, '0', STR_PAD_LEFT);

        // CEK KETERSEDIAAN LAGI (Avoid Race Condition)
        $fullStart = Carbon::parse($jsonData['TglBooking'] . ' ' . $jsonData['JamMulai']);
        $fullEnd = Carbon::parse($jsonData['TglBooking'] . ' ' . $jsonData['JamSelesai']);
        $roid = $jsonData['kodePartner'];
        $mejaID = $jsonData['mejaID'];

        // Cek Booking Lain (Online)
        $clashBooking = BookingOnline::where('RecordOwnerID', $roid)
            ->where('mejaID', $mejaID)
            ->where('StatusTransaksi', 0)
            ->where(function ($q) use ($fullStart, $fullEnd) {
                $q->where(DB::raw("CONCAT(CAST(TglBooking AS DATE), ' ', JamMulai)"), '<', $fullEnd->format('Y-m-d H:i:s'))
                  ->where(DB::raw("CONCAT(CAST(TglBooking AS DATE), ' ', JamSelesai)"), '>', $fullStart->format('Y-m-d H:i:s'));
            })->exists();

        // Cek Order Langsung (Dashboard/Billing)
        $clashOrder = TableOrderHeader::where('RecordOwnerID', $roid)
            ->where('tableid', $mejaID)
            ->whereIn('DocumentStatus', ['O', 'D'])
            ->where(function ($q) use ($fullStart, $fullEnd) {
                $q->where('JamMulai', '<', $fullEnd->format('Y-m-d H:i:s'))
                  ->where(function($q2) use ($fullStart) {
                      $q2->where('JamSelesai', '>', $fullStart->format('Y-m-d H:i:s'))
                         ->orWhereNull('JamSelesai');
                  });
            })->exists();

        if ($clashBooking || $clashOrder) {
            throw new \Exception('Maaf, slot waktu ini baru saja dipesan oleh orang lain atau sudah terisi. Silakan pilih waktu atau meja lain.');
        }
        
        // Cek apakah email sudah ada di tabel pelanggan
        $existingPelanggan = Pelanggan::where('Email', $jsonData['Email'])->first();

        if ($existingPelanggan) {
            $KodePelanggan = $existingPelanggan->KodePelanggan;
        } else {
            $numberingData = new DocumentNumbering();
            $KodePelanggan = $numberingData->GetNewDoc("PLG", "pelanggan", "KodePelanggan");
        }


        $model = new BookingOnline();
        $model->NoTransaksi = $NoTransaksi;
        $model->TglBooking = $jsonData['TglBooking'];
        $model->JamMulai = $jsonData['JamMulai'];
        $model->JamSelesai = $jsonData['JamSelesai'];
        $model->mejaID = $jsonData['mejaID'];
        $model->paketid = $jsonData['paketid'];
        $model->KodeSales = $jsonData['KodeSales'];
        $model->KodePelanggan =  $KodePelanggan;
        $model->StatusTransaksi = $jsonData['StatusTransaksi'];
        $model->Keterangan = $jsonData['Keterangan'];
        $model->ExtraRequest = $jsonData['ExtraRequest'];
        $model->TotalTransaksi = $jsonData['TotalTransaksi'];
        $model->TotalTax = $jsonData['TotalTax'];
        $model->TotalDiskon = $jsonData['TotalDiskon'];
        $model->TotalLainLain = $jsonData['TotalLainLain'];
        $model->NetTotal = $jsonData['NetTotal'];
        $model->RecordOwnerID = $jsonData['kodePartner'];

        if (!$model->save()) {
            throw new \Exception('Penambahan Data Pembayaran Gagal');
        }

         // Jika pelanggan baru, tambahkan ke tabel pelanggan
         if (!$existingPelanggan) {
            $pelanggan = new Pelanggan();
            $pelanggan->KodePelanggan = $KodePelanggan;
            $pelanggan->NamaPelanggan = $jsonData['NamaPelanggan'];
            $pelanggan->KodeGrupPelanggan = "1001";
            $pelanggan->Email = $jsonData['Email'];
            $pelanggan->NoTlp1 = $jsonData['NoTlp1'];
            $pelanggan->Status = 1;
            $pelanggan->RecordOwnerID = $jsonData['kodePartner'];

            if (!$pelanggan->save()) {
                throw new \Exception('Penambahan Data Pelanggan Gagal');
            }
        }

        // SAVE FnB ITEMS
        if (isset($jsonData['fnbCart']) && count($jsonData['fnbCart']) > 0) {
            // Check if table exists, create if not
            DB::statement("CREATE TABLE IF NOT EXISTS bookingtablefnb (
                NoTransaksi VARCHAR(50),
                ItemMasterID VARCHAR(50),
                Qty DECIMAL(18,2),
                Harga DECIMAL(18,2),
                Diskon DECIMAL(18,2),
                LineTotal DECIMAL(18,2),
                RecordOwnerID VARCHAR(50)
            )");

            foreach ($jsonData['fnbCart'] as $fnbItem) {
                DB::table('bookingtablefnb')->insert([
                    'NoTransaksi' => $NoTransaksi,
                    'ItemMasterID' => $fnbItem['KodeItem'],
                    'Qty' => $fnbItem['qty'],
                    'Harga' => $fnbItem['harga'],
                    'Diskon' => 0,
                    'LineTotal' => $fnbItem['harga'] * $fnbItem['qty'],
                    'RecordOwnerID' => $roid
                ]);
            }
        }

        // Jika semuanya berhasil, commit transaksi
        DB::commit();

        $data['success'] = true;
        $data['message'] = 'Data berhasil disimpan';
        $data['data'] = [
            'NoTransaksi' => $NoTransaksi,
            'TglBooking' => $jsonData['TglBooking'],
            'JamMulai' => $jsonData['JamMulai'],
            'JamSelesai' => $jsonData['JamSelesai']
        ];
        $isFinished = true;
        //return response()->json($data);

    } catch (\Exception $e) {
        DB::rollBack(); 
        $data['success'] = false;
        $data['message'] = $e->getMessage();
        
    }
    
    if($isFinished){
        try {
            // Send Email
            $booking = [
                'Email' => $jsonData['Email'],
                'NoTransaksi' => $NoTransaksi,
                'TglBooking' => $jsonData['TglBooking'],
                'JamMulai' => $jsonData['JamMulai'],
                'JamSelesai' => $jsonData['JamSelesai']
            ];

    
        
            $emailPelanggan = Pelanggan::where('KodePelanggan', $KodePelanggan)->first();
            //dd($data);

            if ($emailPelanggan) {
                $oCompany = Company::where('KodePartner','=',$jsonData['kodePartner'])->first();
                $fnbItems = DB::table('bookingtablefnb')
                    ->join('itemmaster', 'bookingtablefnb.ItemMasterID', '=', 'itemmaster.KodeItem')
                    ->where('bookingtablefnb.NoTransaksi', $NoTransaksi)
                    ->where('bookingtablefnb.RecordOwnerID', $jsonData['kodePartner'])
                    ->select('bookingtablefnb.*', 'itemmaster.NamaItem')
                    ->get();

                Mail::to($emailPelanggan->Email)->send(new KonfirmasiPembayaranMail($booking, $emailPelanggan, $oCompany, $fnbItems));
            }
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }
    }
    
    return response()->json($data);
}

public function getBookingsByDate(Request $request) {
    $tanggal = $request->input('tanggal');
    $idMeja = $request->input('idMeja'); 
    $RecordOwnerID = $request->input('RecordOwnerID');

    //    $bookings = BookingOnline::whereDate('TglBooking', $tanggal)
    //     ->where('mejaID', $idMeja)
    //     ->select('JamMulai', 'JamSelesai')
    //     ->get();
    
    $bookings = DB::table('titiklampu as a')
        ->leftJoin(DB::raw("(
            SELECT 
                x.mejaID,
                CONCAT(CAST(x.TglBooking as date),' ',x.JamMulai) JamMulai,
		        CONCAT(CAST(x.TglBooking AS DATE),' ',x.JamSelesai) JamSelesai,
                x.RecordOwnerID,
                'JAM' as JenisPaket
            FROM bookingtableonline x
            WHERE x.StatusTransaksi = 0
            AND x.RecordOwnerID = '".$RecordOwnerID."'
            
            UNION ALL
            
            SELECT 
                y.tableid as mejaID,
                y.JamMulai,
                COALESCE(y.JamSelesai, NOW()) as JamSelesai,
                y.RecordOwnerID,
                y.JenisPaket
            FROM tableorderheader y
            WHERE y.Status <> 0
            AND y.RecordOwnerID = '".$RecordOwnerID."'
        ) as b"), function($join) {
            $join->on('a.id', '=', 'b.mejaID')
                ->on('a.RecordOwnerID', '=', 'b.RecordOwnerID');
        })
        ->where('a.RecordOwnerID', $RecordOwnerID)
        ->where('a.id', $idMeja)
        ->whereNotNull('b.JamMulai')
        ->where(DB::raw('CAST(b.JamMulai AS DATE)'), '=', $tanggal)
        ->select('a.id', 'b.JamMulai', 'b.JamSelesai', 'a.RecordOwnerID', 'b.JenisPaket')
        ->orderBy('a.id')
        ->get();

    return response()->json($bookings);
}

public function getDiscountVoucher(Request $request)
{
    $voucherCode = $request->query('code');
    $kodePartner = $request->query('kodePartner');
    $voucher = DiscountVoucher::where('VoucherCode', $voucherCode)
    ->where('RecordOwnerID', $kodePartner)->first();

    if (!$voucher) {
        return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan'], 404);
    }

    return response()->json([
        'success' => true,
        'discountPercent' => $voucher->DiscountPercent,
        'maximalDiscount' => $voucher->MaximalDiscount,
        'discountQuota' => $voucher->DiscountQuota,
        'startDate' => $voucher->StartDate,
        'endDate' => $voucher->EndDate,
    ]);
}

public function View(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $listBooking = BookingOnline::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();
    $encodedRecordOwnerID = base64_encode(Auth::user()->RecordOwnerID);
    $BookingURLString = url('booking/').'/'.$encodedRecordOwnerID;
    $meja = DB::table('titiklampu')->where('RecordOwnerID', Auth::user()->RecordOwnerID)->get();
    return view('Transaksi.Penjualan.PoS.ListBookingOnlineV2', compact('listBooking', 'BookingURLString', 'meja'));
}

public function ViewGenerateVoucher(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $listBooking = BookingOnline::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();


    return view('Transaksi.Penjualan.PoS.GenerateVoucher', compact('listBooking'));
}

public function storeVoucher(Request $request)
{
    try{
    // Validasi input
    $request->validate([
        'discountPercent' => 'required|numeric|min:1|max:100',
        'maximalDiscount' => 'required|numeric|min:1000',
        'discountQuota' => 'required|integer|min:1',
        'expiryDate' => 'required|date|after:today',
    ]);

    // Generate kode voucher unik (6 karakter)
    //$voucherCode = strtoupper(Str::random(6));
    
    // Simpan data ke database
    $voucher = new DiscountVoucher();
    $voucher->VoucherCode = $request->voucherCode;
    $voucher->DiscountPercent = $request->discountPercent;
    $voucher->MaximalDiscount = $request->maximalDiscount;
    $voucher->DiscountQuota = $request->discountQuota;
    $voucher->DiscountDescription = $request->description;
    $voucher->StartDate = $request->startDate;
    $voucher->EndDate = $request->expiryDate;
    if (!Auth::check()) {
        throw new \Exception('Unauthorized');
    }
    $voucher->RecordOwnerID = Auth::user()->RecordOwnerID;
    $voucher->save();

    $data['success'] = true;
    $data['message'] = 'Data berhasil disimpan';

} catch (\Exception $e) {
    $data['success'] = false;
    $data['message'] = $e->getMessage();
    
}

    return response()->json($data);

}

public function getListVoucher()
    {
        $vouchers = DiscountVoucher::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($vouchers);
    }

    public function getBookings()
    {
        if (!Auth::check()) {
            return response()->json(['data' => []]);
        }
        $query = BookingOnline::join('pelanggan', 'bookingtableonline.KodePelanggan', '=', 'pelanggan.KodePelanggan')
        ->join('titiklampu', 'bookingtableonline.mejaID', '=', 'titiklampu.id') 
        ->where('bookingtableonline.RecordOwnerID', Auth::user()->RecordOwnerID)
        ->where('bookingtableonline.StatusTransaksi', '0')
        ->orderBy('bookingtableonline.created_at', 'desc')
        ->select(
            'bookingtableonline.*', 
            'pelanggan.NamaPelanggan', 
            'pelanggan.Email', 
            'pelanggan.NoTlp1', 
            'titiklampu.NamaTitikLampu' ,
            DB::raw("CASE 
                    WHEN bookingtableonline.StatusTransaksi = 0 THEN 'WAITING' 
                    WHEN bookingtableonline.StatusTransaksi = 1 THEN 'CHECK IN' 
                    ELSE 'UNKNOWN' 
                 END AS StatusTransaksi")
        );
        

        //dd($query->toSql(), $query->getBindings());

        $bookings = $query->get();

        return response()->json($bookings);
    }

    public function getBookingsList(Request $request){
        $query = BookingOnline::join('pelanggan', 'bookingtableonline.KodePelanggan', '=', 'pelanggan.KodePelanggan')
        ->join('titiklampu', 'bookingtableonline.mejaID', '=', 'titiklampu.id') 
        ->where('bookingtableonline.RecordOwnerID', Auth::user()->RecordOwnerID)
        ->whereBetween(DB::raw('CAST(bookingtableonline.TglBooking AS DATE)'), [$request->input('TglAwal'), $request->input('TglAkhir')])
        ->where('bookingtableonline.StatusTransaksi', '0')
        ->orderBy('bookingtableonline.created_at', 'desc')
        ->select(
            'bookingtableonline.*', 
            'pelanggan.NamaPelanggan', 
            'pelanggan.Email', 
            'pelanggan.NoTlp1', 
            'titiklampu.NamaTitikLampu' ,
            DB::raw("CASE 
                    WHEN bookingtableonline.StatusTransaksi = 0 THEN 'WAITING' 
                    WHEN bookingtableonline.StatusTransaksi = 1 THEN 'CHECK IN' 
                    ELSE 'UNKNOWN' 
                 END AS StatusTransaksi")
        );
        

        //dd($query->toSql(), $query->getBindings());

        $bookings = $query->get();

        return response()->json($bookings);
    }

    public function getBookingDetail($noTransaksi)
{
    $booking = BookingOnline::join('pelanggan', 'bookingtableonline.KodePelanggan', '=', 'pelanggan.KodePelanggan')
        ->join('titiklampu', 'bookingtableonline.mejaID', '=', 'titiklampu.id')
        ->join('pakettransaksi', 'bookingtableonline.paketid', '=', 'pakettransaksi.id')
        ->where('bookingtableonline.NoTransaksi', $noTransaksi)
        ->select(
            'bookingtableonline.*', 
            'pelanggan.NamaPelanggan', 
            'pelanggan.Email', 
            'titiklampu.NamaTitikLampu',
            'pakettransaksi.JenisPaket',
            'pakettransaksi.DurasiPaket',
            DB::raw("CASE 
            WHEN bookingtableonline.StatusTransaksi = 0 THEN 'WAITING' 
            WHEN bookingtableonline.StatusTransaksi = 1 THEN 'CHECK IN' 
            ELSE 'UNKNOWN' 
         END AS StatusTransaksi")
        )
        ->first();

    if ($booking) {
        return response()->json($booking);
    } else {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }
}

public function getMejaByTransaksi($noTransaksi)
{
    $booking = BookingOnline::where('NoTransaksi', $noTransaksi)
    ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
    ->select('mejaID')
    ->first();
    

if (!$booking) {
    return response()->json(['error' => 'Data tidak ditemukan'], 404);
}

return response()->json($booking);

}


public function insertTableOrder(Request $request)
{
    $data = ['success' => false, 'message' => '', 'data' => [], 'Kembalian' => ""];

    $this->validate($request, [
        'JenisPaket' => 'required',
        'paketid' => 'required',
        'tableid' => 'required',
        'KodeSales' => 'required',
        'DurasiPaket' => 'required'
    ]);

    try {
        DB::beginTransaction(); // Mulai transaksi

        $currentDate = Carbon::now();
        $Year = $currentDate->format('Y');
        $Month = $currentDate->format('m');

        $tglBooking = $request->input('TglBooking'); // "2025-03-13 00:00:00"
        $jamMulai = $request->input('JamMulai'); // "10:00:00"

        $startDate = Carbon::parse($tglBooking)->format('Y-m-d') . ' ' . $jamMulai;

        // Insert ke tableorderheader
        $model = new TableOrderHeader();
        $model->NoTransaksi = $request->input('NoTransaksi');
        $model->TglTransaksi = $startDate;
        $model->TglPencatatan = Carbon::now();
        $model->JenisPaket = $request->input('JenisPaket');
        $model->paketid = $request->input('paketid');
        $model->tableid = $request->input('tableid');
        $model->KodeSales = $request->input('KodeSales');
        $model->DurasiPaket = $request->input('DurasiPaket');
        $model->Status = $request->input('Status');
        $model->KodePelanggan = $request->input('KodePelanggan');
        $model->TaxTotal = 0; // Default 0
        $model->GrossTotal = $request->input('GrossTotal');
        $model->DiscTotal = $request->input('DiscTotal');
        $model->NetTotal = $request->input('NetTotal');
        $model->JamMulai = $startDate;
        $model->JamSelesai = Carbon::parse($startDate)->addHours($request->input('DurasiPaket'));
        // if ($request->input('JenisPaket') != 'MENIT') {
        //     $model->JamSelesai = Carbon::parse($startDate)->addHours($request->input('DurasiPaket'));
        // }

        $model->RecordOwnerID = Auth::user()->RecordOwnerID;
        $model->DocumentStatus = 'O'; // Standardize for active table
        $model->kitchen_order_status = 0; // Ensure appears in KDS

        if (!$model->save()) {
            throw new \Exception('Gagal menyimpan data ke tableorderheader.');
        }

        // Update bookingtableonline
        $update = DB::table('bookingtableonline')
            ->where('NoTransaksi', '=', $request->input('NoTransaksi'))
            ->update(['StatusTransaksi' => 1]);

        // MOVE FnB ITEMS TO tableorderfnb
        $fnbBookings = DB::table('bookingtablefnb')
            ->where('NoTransaksi', $request->input('NoTransaksi'))
            ->get();

        foreach ($fnbBookings as $fb) {
            DB::table('tableorderfnb')->insert([
                'NoTransaksi' => $request->input('NoTransaksi'),
                'KodeItem' => $fb->ItemMasterID,
                'Qty' => $fb->Qty,
                'Harga' => $fb->Harga,
                'Diskon' => $fb->Diskon,
                'LineTotal' => $fb->LineTotal,
                'RecordOwnerID' => $fb->RecordOwnerID,
                'isCompleted' => 0,
                'LineStatus' => 'O',
                'ServiceType' => 'DINE_IN',
                'OrderSource' => 'WEB',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if (!$update) {
            throw new \Exception('Gagal memperbarui bookingtableonline.');
        }

        // Update status titiklampu menjadi AKTIF (1)
        DB::table('titiklampu')
            ->where('id', $request->input('tableid'))
            ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
            ->update(['status' => 1]);

        DB::commit(); // Commit transaksi jika semuanya berhasil
        $data['success'] = true;
    } catch (\Throwable $th) {
        DB::rollBack(); // Rollback transaksi jika ada error
        $data['message'] = 'Internal error: ' . $th->getMessage();
    }

    return response()->json($data);
}




    public function rescheduleBooking(Request $request) {
        try {
            $NoTransaksi = $request->input('NoTransaksi');
            $mejaID = $request->input('mejaID');
            $TglBooking = $request->input('TglBooking');
            $JamMulai = $request->input('JamMulai');
            $JamSelesai = $request->input('JamSelesai');
            $roid = Auth::user()->RecordOwnerID;

            $booking = BookingOnline::where('NoTransaksi', $NoTransaksi)->where('RecordOwnerID', $roid)->first();
            if (!$booking) {
                return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan!']);
            }
            
            // Collision detection
            $fullStart = Carbon::parse($TglBooking . ' ' . $JamMulai)->format('Y-m-d H:i:s');
            $fullEnd = Carbon::parse($TglBooking . ' ' . $JamSelesai);
            if ($fullEnd->lt(Carbon::parse($fullStart))) {
                $fullEnd->addDay();
            }
            $fullEndStr = $fullEnd->format('Y-m-d H:i:s');

            // 1. Cek bentrok dengan booking online lain (yang bukan trx ini)
            $clashBooking = BookingOnline::where('RecordOwnerID', $roid)
                ->where('mejaID', $mejaID)
                ->where('NoTransaksi', '!=', $NoTransaksi)
                ->where('StatusTransaksi', 0)
                ->where(function ($q) use ($fullStart, $fullEndStr) {
                    $q->whereRaw("CAST(CONCAT(TglBooking, ' ', JamMulai) AS DATETIME) < ?", [$fullEndStr])
                      ->whereRaw("CAST(CONCAT(TglBooking, ' ', JamSelesai) AS DATETIME) > ?", [$fullStart]);
                })->exists();

            // 2. Cek bentrok dengan tableorderheader (yang bukan trx ini)
            $clashOrder = DB::table('tableorderheader')->where('RecordOwnerID', $roid)
                ->where('tableid', $mejaID)
                ->where('NoTransaksi', '!=', $NoTransaksi)
                ->whereIn('DocumentStatus', ['O', 'D'])
                ->where('JamMulai', '<', $fullEndStr)
                ->where(function ($q) use ($fullStart) {
                    $q->where('JamSelesai', '>', $fullStart)->orWhereNull('JamSelesai');
                })->exists();

            if ($clashBooking || $clashOrder) {
                return response()->json(['success' => false, 'message' => 'Slot waktu / Meja tersebut sudah terisi oleh pelanggan lain!']);
            }

            // Update
            $booking->mejaID = $mejaID;
            $booking->TglBooking = $TglBooking;
            $booking->JamMulai = $JamMulai;
            $booking->JamSelesai = $JamSelesai;
            $booking->save();

            return response()->json(['success' => true, 'message' => 'Reschedule berhasil!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}