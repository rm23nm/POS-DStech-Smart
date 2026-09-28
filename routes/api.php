<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrintingRecieptController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\FlutterWebAppsController;
use App\Http\Controllers\FakturPenjualanController;
use App\Http\Controllers\TableOrderController;
use App\Http\Controllers\MasterControllerController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BookingOnlineController;
use App\Http\Controllers\DiscountVoucherController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\GenerateDocumentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[LoginController::class,'API_login']);
Route::post('logout',[LoginController::class,'logout'])->middleware('auth:sanctum');
Route::post('test',[PrintingRecieptController::class,'GetData']);

Route::post('printerlist',[PrinterController::class,'Read']);
Route::post('printerstore',[PrinterController::class,'store']);
Route::post('printeredit',[PrinterController::class,'edit']);
Route::post('/payment/midtrans/notify', [WebhookController::class, 'handleNotification']);
Route::post('/payment/xendit/notify', [WebhookController::class, 'handleXenditNotification']);

// Xendit SaaS Payment Webhook
Route::post('/saas/payment/xendit/notify', [\App\Http\Controllers\XenditSaaSWebhookController::class, 'handle']);


Route::post('printerdelete',[PrinterController::class,'delete']);
Route::post('testnotif',[PrinterController::class,'TestNotif']);


Route::post('initWebMenu',[FlutterWebAppsController::class,'InitProgram']);
Route::post('saveFromTable',[FlutterWebAppsController::class,'TableServices']);

Route::post('getMenu',[FlutterWebAppsController::class,'GetMenuByKelompok']);
Route::post('getAddon',[FlutterWebAppsController::class,'getVariantAddonData']);
Route::post('getTable',[TableOrderController::class,'ReadTableAPI']);

Route::post('checkCommand',[MasterControllerController::class,'CheckCommand']);
Route::post('releaseCommand',[MasterControllerController::class,'DeviceCommand']);

Route::post('/sendreminder', [CompanyController::class, 'CheckSubscriptionStatus'])->name('sendreminder');

Route::post('/getjadwal', [BookingOnlineController::class, 'getjadwalMeja'])->name('booking-getjadwal');

Route::post('/discountvoucher/cekdiscount', [DiscountVoucherController::class, 'checkVoucher'])->name('discountvoucher-cekdiscount');

Route::post('/pelanggan/viewJson', [PelangganController::class, 'ReadPelangganJson'])->name('pelanggan-viewJson');

Route::post('/tools/generate-document', [GenerateDocumentController::class, 'generate'])->name('tools-generate-document');

// Webhook endpoint for centralizing subscription reporting
Route::post('/superadmin/sync-subscription', [\App\Http\Controllers\DstechGlobalSyncController::class, 'syncSubscription']);
// API for Tripod Gate ESP32
Route::post('/gate/scan', [\App\Http\Controllers\GateApiController::class, 'scan'])->name('gate-scan');


// API untuk Gate ESP32 (Koneksi WiFi / HTTPS dari luar jaringan)
Route::post('/gate/check', [App\Http\Controllers\GateController::class, 'checkAccess']);

// API untuk Gate ESP32 (Koneksi LAN / HTTP lokal - dalam jaringan yang sama)
Route::post('/gate/lan', [App\Http\Controllers\GateController::class, 'checkAccessLAN']);

// API untuk Gate Agent (PC Client) - Sinkronisasi data tiket & member ke lokal
Route::post('/gate/sync-data',  [App\Http\Controllers\GateAgentController::class, 'syncData']);
// API untuk Gate Agent (PC Client) - Update tiket yang sudah dipakai di gate lokal ke cloud
Route::post('/gate/use-ticket', [App\Http\Controllers\GateAgentController::class, 'useTicket']);

// API untuk Pengecekan Lisensi Offline dari Aplikasi Desktop/Mobile
Route::post('/check-license', [App\Http\Controllers\LicenseController::class, 'checkLicense']);
