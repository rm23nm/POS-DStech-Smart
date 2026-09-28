@extends('parts.header')
	
@section('content')
<style>
    /* Basic styling to prevent CSS conflicts */
    .ck-editor__editable {
        white-space: normal !important;
    }
</style>
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="{{route('subs')}}"></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Input Produk Berlangganan</li>
			</ol>
		</nav>
	</div>
</div>
<!--end::Subheader-->
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 px-4">
				<div class="row">
					<div class="col-lg-12 col-xl-12 px-4">
						<div class="card card-custom gutter-b bg-transparent shadow-none border-0" >
							<div class="card-header align-items-center  border-bottom-dark px-0">
								<div class="card-title mb-0">
									<h3 class="card-label mb-0 font-weight-bold text-body">

                                        @if (count($subscriptionheader) > 0 && !request()->query('copy'))
                                        Edit Produk Berlangganan
                                    		<input type="hidden" name="formtype" id="formtype" value="edit">
	                                	@else
                                            @if(request()->query('copy'))
                                            Duplikat Produk Berlangganan
                                            @else
                                            Tambah Produk Berlangganan
                                            @endif
	                                    	<input type="hidden" name="formtype" id="formtype" value="add">
	                                	@endif
									</h3>
								</div>
							</div>
						
						</div>


					</div>
				</div>

				<div class="row">
					<div class="col-12  px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-body" >
								<div class="form-group row">
                                    <div class="col-md-3">
                                        <label  class="text-body">Kode Produk Berlangganan</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="text" class="form-control" id="NoTransaksi" name="NoTransaksi" placeholder="Masukan Kode Produk Berlangganan" value="{{ count($subscriptionheader) > 0 && !request()->query('copy') ? $subscriptionheader[0]['NoTransaksi'] : '' }}" {{ count($subscriptionheader) > 0 && !request()->query('copy') ? 'readonly' : '' }}>
                                        </fieldset>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label  class="text-body">Nama Produk Berlangganan</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="text" class="form-control" id="NamaSubscription" name="NamaSubscription" placeholder="Masukan Nama Produk Berlangganan" value="{{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['NamaSubscription'] . (request()->query('copy') ? ' (Copy)' : '') : '' }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        
                                        <label  class="text-body">Jenis Usaha</label>
                                        <fieldset class="form-group mb-3">
                                            <select required id="JenisUsaha" name="JenisUsaha" class="js-example-basic-single form-control text-dark border-0 p-0 h-20px font-size-h5">
                                                <option value=""  {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['JenisUsaha'] == "" ? 'selected' : '' : '' }}>Pilih Jenis Usaha</option>
                                                <option value="Retail" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['JenisUsaha'] == "Retail" ? 'selected' : '' : '' }} >Retail</option>
                                                <option value="FnB" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['JenisUsaha'] == "FnB" ? 'selected' : '' : '' }}>Food and Beverage</option>
                                                <option value="Hiburan" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['JenisUsaha'] == "Hiburan" ? 'selected' : '' : '' }}>Hiburan</option>
                                                <option value="Apotek" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['JenisUsaha'] == "Apotek" ? 'selected' : '' : '' }}>Apotek / Klinik</option>
                                                <option value="TiketGate" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['JenisUsaha'] == "TiketGate" ? 'selected' : '' : '' }}>Tiket & Smart Gate</option>
                                                <option value="BengkelDealer" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['JenisUsaha'] == "BengkelDealer" ? 'selected' : '' : '' }}>Bengkel dan Dealer</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <label  class="text-body">Tanggal Berlaku</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="date" class="form-control" id="Tanggal" name="Tanggal" placeholder="Masukan Kode Produk Berlangganan" value="{{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['Tanggal'] : '' }}">
                                        </fieldset>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label  class="text-body">Deskripsi Produk</label>
                                        <fieldset class="form-group mb-3">
                                            <textarea id="DeskripsiSubscription" name="DeskripsiSubscription" class="bg-transparent text-dark">
                                            {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['DeskripsiSubscription'] : '' }}
                                            </textarea>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-6">
                                        <label  class="text-body">Harga</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="number" class="form-control" id="Harga" name="Harga" placeholder="Masukan Harga" value="{{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['Harga'] : '' }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-md-6">
                                        <label  class="text-body">Lama Berlangganan (Bulan)</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="number" class="form-control" id="LamaSubsription" name="LamaSubsription" placeholder="Masukan Lama Berlangganan" value="{{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['LamaSubsription'] : '' }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-md-12">
                                        <fieldset class="form-group mb-3">
                                            <input type="checkbox" class="checkbox-input" id="AllowAccounting" name="AllowAccounting" placeholder="Masukan Harga" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['AllowAccounting'] == 1 ? 'checked' : '' : '' }}>
                                            <label  class="text-body" for="AllowAccounting">Integrasi Akutansi</label>
                                        </fieldset>

                                        <fieldset class="form-group mb-3">
                                            <input type="checkbox" class="checkbox-input" id="AllowPesananMeja" name="AllowPesananMeja" placeholder="Masukan Harga" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['AllowPesananMeja'] == 1 ? 'checked' : '' : '' }}>
                                            <label  class="text-body" for="AllowPesananMeja">Integrasi Pesanan Di Meja (Khusus FnB)</label>
                                        </fieldset>
                                        <fieldset class="form-group mb-3">
                                            <input type="checkbox" class="checkbox-input" id="AllowPaymentGateway" name="AllowPaymentGateway" placeholder="Masukan Harga" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['AllowPaymentGateway'] == 1 ? 'checked' : '' : '' }}>
                                            <label  class="text-body" for="AllowPaymentGateway">Integrasi QRIS</label>
                                        </fieldset>
                                        <fieldset class="form-group mb-3">
                                            <input type="checkbox" class="checkbox-input" id="AllowKatalogOnline" name="AllowKatalogOnline" placeholder="Masukan Harga" {{ count($subscriptionheader) > 0 ? $subscriptionheader[0]['AllowKatalogOnline'] == 1 ? 'checked' : '' : '' }}>
                                            <label  class="text-body" for="AllowKatalogOnline">Integrasi Katalog Online</label>
                                        </fieldset>
                                        <fieldset class="form-group mb-3">
                                            <input type="checkbox" class="checkbox-input" id="AllowMonitorAntrean" name="AllowMonitorAntrean" placeholder="Masukan Harga" {{ count($subscriptionheader) > 0 ? (isset($subscriptionheader[0]['AllowMonitorAntrean']) && $subscriptionheader[0]['AllowMonitorAntrean'] == 1) ? 'checked' : '' : '' }}>
                                            <label  class="text-body" for="AllowMonitorAntrean">Integrasi Monitor Antrean / Kitchen (NEW)</label>
                                        </fieldset>
                                    </div>

                                    <hr>
                                    
                                    <center><h2>Akses Menu Berlangganan</h2></center>
                                    <p class="text-center text-muted mb-4">Pilih dan centang menu yang ingin dimasukkan ke dalam paket berlangganan ini. Anda bisa menggunakan tombol <strong>"Pilih Semua"</strong> untuk mempermudah pemilihan fitur per jenis usaha!</p>

                                     @php
                                        $groupedPermissions = [
                                            'backoffice' => [
                                                'title' => '📦 KASIR, INVENTORI & BACK-OFFICE',
                                                'color' => '#0072ff',
                                                'items' => []
                                            ],
                                            'fnb' => [
                                                'title' => '🍽️ MANAJEMEN RESTORAN (F&B)',
                                                'color' => '#d01818',
                                                'items' => []
                                            ],
                                            'bengkel' => [
                                                'title' => '🔧 MANAJEMEN BENGKEL & DEALER',
                                                'color' => '#e67e22',
                                                'items' => []
                                            ],
                                            'hiburan' => [
                                                'title' => '🎱 BOOKING, IOT & HIBURAN',
                                                'color' => '#2ecc71',
                                                'items' => []
                                            ],
                                            'display' => [
                                                'title' => '📺 LAYAR ANTREAN & KDS',
                                                'color' => '#9b59b6',
                                                'items' => []
                                            ],
                                            'keuangan' => [
                                                'title' => '📊 KEUANGAN & AKUNTANSI',
                                                'color' => '#f39c12',
                                                'items' => []
                                            ],
                                            'umum' => [
                                                'title' => '⚙️ UMUM & PENGATURAN',
                                                'color' => '#6c757d',
                                                'items' => []
                                            ]
                                        ];

                                        // Function to classify Level 2 permission items
                                        $classifyLevel2 = function($item) {
                                            $id = intval($item['MenuID']);

                                            // Backoffice (Kasir, Inventori, dll)
                                            $backofficeIds = [2, 6, 20, 25, 31, 35, 50, 53, 70];
                                            // Keuangan
                                            $keuanganIds = [12, 40, 41, 56, 85, 86];
                                            // FnB
                                            $fnbIds = [74];
                                            // Bengkel
                                            $bengkelIds = [129, 131, 132, 134];
                                            // Hiburan & IoT
                                            $hiburanIds = [88, 91, 121, 125];
                                            // Display / KDS
                                            $displayIds = [113, 115, 116, 117, 118, 119, 127, 142, 143, 144];

                                            if (in_array($id, $backofficeIds)) {
                                                return 'backoffice';
                                            }
                                            if (in_array($id, $keuanganIds)) {
                                                return 'keuangan';
                                            }
                                            if (in_array($id, $fnbIds)) {
                                                return 'fnb';
                                            }
                                            if (in_array($id, $bengkelIds)) {
                                                return 'bengkel';
                                            }
                                            if (in_array($id, $hiburanIds)) {
                                                return 'hiburan';
                                            }
                                            if (in_array($id, $displayIds)) {
                                                return 'display';
                                            }
                                            return 'umum';
                                        };

                                        // Filter and group Level 2 permissions
                                        foreach ($permissionrole as $lv1) {
                                            if (intval($lv1['MenuID']) == 100) {
                                                continue; // Hide SuperAdmin menu (ID 100)
                                            }
                                            foreach ($lv1['submenu'] as $lv2) {
                                                $category = $classifyLevel2($lv2);
                                                
                                                // Split Booking, List Booking, Vouchers, and Self Service POS from Penjualan (ID 25) into Hiburan
                                                if ($lv2['MenuID'] == 25) {
                                                    $backofficeSubmenu = [];
                                                    $hiburanSubmenu = [];
                                                    
                                                    foreach ($lv2['submenu'] as $lv3) {
                                                        $lv3Id = intval($lv3['MenuID']);
                                                        if (in_array($lv3Id, [95, 96, 97, 98])) {
                                                            $hiburanSubmenu[] = $lv3;
                                                        } else {
                                                            $backofficeSubmenu[] = $lv3;
                                                        }
                                                    }
                                                    
                                                    if (!empty($backofficeSubmenu)) {
                                                        $backofficeLv2 = $lv2;
                                                        $backofficeLv2['submenu'] = $backofficeSubmenu;
                                                        $groupedPermissions['backoffice']['items'][] = $backofficeLv2;
                                                    }
                                                    
                                                    if (!empty($hiburanSubmenu)) {
                                                        $hiburanLv2 = $lv2;
                                                        $hiburanLv2['PermissionName'] = 'Booking & POS Self Service';
                                                        $hiburanLv2['submenu'] = $hiburanSubmenu;
                                                        $groupedPermissions['hiburan']['items'][] = $hiburanLv2;
                                                    }
                                                } else {
                                                    // Add badges to display items for easier identification
                                                    if ($category == 'display') {
                                                        $lv2Id = intval($lv2['MenuID']);
                                                        if (in_array($lv2Id, [113, 116, 117, 118])) {
                                                            $lv2['PermissionName'] .= ' <span class="badge bg-danger text-white ms-2" style="font-size:0.6rem; padding: 2px 6px;">FnB</span>';
                                                        } elseif (in_array($lv2Id, [142, 143, 144])) {
                                                            $lv2['PermissionName'] .= ' <span class="badge bg-info text-white ms-2" style="font-size:0.6rem; padding: 2px 6px;">Apotek</span>';
                                                        } elseif (in_array($lv2Id, [115])) {
                                                            $lv2['PermissionName'] .= ' <span class="badge bg-success text-white ms-2" style="font-size:0.6rem; padding: 2px 6px;">Hiburan</span>';
                                                        } elseif ($lv2Id == 127) {
                                                            $lv2['PermissionName'] .= ' <span class="badge bg-warning text-dark ms-2" style="font-size:0.6rem; padding: 2px 6px;">Bengkel</span>';
                                                        } else {
                                                            $lv2['PermissionName'] .= ' <span class="badge bg-primary text-white ms-2" style="font-size:0.6rem; padding: 2px 6px;">Umum</span>';
                                                        }
                                                    }
                                                    $groupedPermissions[$category]['items'][] = $lv2;
                                                }
                                            }
                                        }
                                    @endphp

                                    <div class="row" id="subscription-feature-cards">
                                        @foreach ($groupedPermissions as $gKey => $group)
                                            <div class="col-md-6 mb-4 group-card-{{ $gKey }}">
                                                <div class="card shadow-sm border" style="border-radius: 12px; overflow: hidden; border-top: 4px solid {{ $group['color'] }} !important;">
                                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 px-4">
                                                        <h5 class="card-title mb-0 font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $group['title'] }}</h5>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-select-all font-weight-bold" data-target="group_{{ $gKey }}" style="font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">Pilih Semua</button>
                                                    </div>
                                                    <div class="card-body bg-white py-3 px-4" style="max-height: 400px; overflow-y: auto;">
                                                        <div class="dd group_{{ $gKey }}" id="nestable_{{ $gKey }}">
                                                            <ol class="dd-list">
                                                                @foreach ($group['items'] as $lv2)
                                                                    <li class="dd-item" data-id="{{ $lv2['MenuID'] }}">
                                                                        <div class="dd-handle font-weight-bold">{!! $lv2['PermissionName'] !!}</div>
                                                                        <div class="inner-content">
                                                                            <div class="custom-control switch custom-switch-info custom-control-inline form-check form-switch me-0">
                                                                                <input type="checkbox" class="form-check-input group-chk-{{ $gKey }}" id="chk{{ str_replace(' ', '', $lv2['MenuID']) }}" {{ $lv2['Selected'] != "" ? 'checked' : '' }} value="{{ $lv2['MenuID'] }}">
                                                                                <label class="form-check-label me-1" for="chk{{ str_replace(' ', '', $lv2['MenuID']) }}"></label>
                                                                            </div>
                                                                        </div>

                                                                        @if (count($lv2['submenu']) > 0)
                                                                            <ol class="dd-list">
                                                                                @foreach ($lv2['submenu'] as $lv3)
                                                                                    <li class="dd-item" data-id="{{ $lv3['MenuID'] }}">
                                                                                        <div class="dd-handle text-muted" style="font-size: 0.85rem;">{{ $lv3['PermissionName'] }}</div>
                                                                                        <div class="inner-content">
                                                                                            <div class="custom-control switch custom-switch-info custom-control-inline form-check form-switch me-0">
                                                                                                <input type="checkbox" class="form-check-input group-chk-{{ $gKey }}" id="chk{{ str_replace(' ', '', $lv3['MenuID']) }}" {{ $lv3['Selected'] != "" ? 'checked' : '' }} value="{{ $lv3['MenuID'] }}">
                                                                                                <label class="form-check-label me-1" for="chk{{ str_replace(' ', '', $lv3['MenuID']) }}"></label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-12">
                                        <button id="btSave" name="btSave" type="button" class="btn btn-success text-white font-weight-bold me-1 mb-1">Simpan</button>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/jquery.nestable.js')}}"></script>
<script type="text/javascript">
	$(function () {
        let DeskripsiSubscriptionInstance;
		$(document).ready(function () {
            ClassicEditor.create(document.querySelector('#DeskripsiSubscription')).then( editor => {DeskripsiSubscriptionInstance = editor})
			.catch( error => {
			    console.error( error );
			});
            jQuery('.dd').nestable({
                collapsedClass:'dd-collapsed',
            }).nestable('collapseAll');

            // Select All / Deselect All for each category group
            jQuery('.btn-select-all').click(function () {
                var targetClass = jQuery(this).data('target');
                var checkboxes = jQuery('.' + targetClass + ' input[type="checkbox"]');
                
                var hasUnchecked = false;
                checkboxes.each(function() {
                    if (!jQuery(this).prop('checked')) {
                        hasUnchecked = true;
                    }
                });
                
                if (hasUnchecked) {
                    checkboxes.prop('checked', true);
                    jQuery(this).text('Batal Pilih');
                    jQuery(this).removeClass('btn-outline-secondary').addClass('btn-danger text-white');
                } else {
                    checkboxes.prop('checked', false);
                    jQuery(this).text('Pilih Semua');
                    jQuery(this).removeClass('btn-danger text-white').addClass('btn-outline-secondary');
                }
            });

            // Dynamic Filtering based on Jenis Usaha
            function filterMenuByUsaha() {
                var jenis = jQuery('#JenisUsaha').val();
                
                // Hide specific categories first
                jQuery('.group-card-backoffice, .group-card-fnb, .group-card-bengkel, .group-card-hiburan, .group-card-display').hide();
                // Umum & Keuangan always show
                jQuery('.group-card-keuangan, .group-card-umum').show(); 

                if (jenis == 'Retail' || jenis == 'Apotek') {
                    jQuery('.group-card-backoffice, .group-card-display').fadeIn();
                } else if (jenis == 'BengkelDealer') {
                    jQuery('.group-card-backoffice, .group-card-bengkel, .group-card-display').fadeIn();
                } else if (jenis == 'FnB') {
                    // FnB: Kasir/Backoffice + Restoran + Booking/IoT + KDS
                    jQuery('.group-card-backoffice, .group-card-fnb, .group-card-hiburan, .group-card-display').fadeIn();
                } else if (jenis == 'Hiburan' || jenis == 'TiketGate') {
                    // Hiburan/Gate: Kasir + Hiburan/IoT + Display
                    jQuery('.group-card-backoffice, .group-card-hiburan, .group-card-display').fadeIn();
                } else {
                    // Show all if empty
                    jQuery('.group-card-backoffice, .group-card-fnb, .group-card-hiburan, .group-card-display').show();
                }
            }

            jQuery('#JenisUsaha').change(function() {
                filterMenuByUsaha();
            });

            // Run once on load
            filterMenuByUsaha();
		});

        // Save
        jQuery('#btSave').click(function () {
            jQuery('#btSave').text('Tunggu Sebentar.....');
            jQuery('#btSave').attr('disabled',true);

            var oDetail = [];
            var dataPermission = <?php echo json_encode($permission); ?>;
            
            // Map permissions by ID for resolving hierarchy
            var permMap = {};
            for (var i = 0; i < dataPermission.length; i++) {
                permMap[dataPermission[i].id] = dataPermission[i];
            }

            var selectedIds = new Set();

            // 1. Collect explicitly checked permissions
            for (var i = 0; i < dataPermission.length; i++) {
                var permId = dataPermission[i].id;
                var checkbox = jQuery("#chk" + permId + ":checked").val();
                if (typeof checkbox !== 'undefined' && checkbox !== '') {
                    selectedIds.add(parseInt(checkbox));
                }
            }

            // 2. Auto-resolve parent hierarchy (MenuInduk)
            var idsToProcess = Array.from(selectedIds);
            while (idsToProcess.length > 0) {
                var currentId = idsToProcess.pop();
                var perm = permMap[currentId];
                if (perm && perm.MenuInduk && perm.MenuInduk > 0) {
                    var parentId = parseInt(perm.MenuInduk);
                    if (!selectedIds.has(parentId)) {
                        selectedIds.add(parentId);
                        idsToProcess.push(parentId);
                    }
                }
            }

            // 3. Build details payload
            selectedIds.forEach(function(id) {
                oDetail.push({
                    'PermissionID': id
                });
            });

            var oData = {
				'NoTransaksi' : jQuery('#NoTransaksi').val(),
                'Tanggal' : jQuery('#Tanggal').val(),
                'NamaSubscription' : jQuery('#NamaSubscription').val(),
                'DeskripsiSubscription' : DeskripsiSubscriptionInstance.getData(),
                'Harga' : jQuery('#Harga').val(),
                'LamaSubsription' : jQuery('#LamaSubsription').val(),
                'AllowAccounting' : jQuery('#AllowAccounting').prop('checked') ? 1 : 0,
                'AllowPesananMeja' : jQuery('#AllowPesananMeja').prop('checked') ? 1 : 0,
                'AllowPaymentGateway' : jQuery('#AllowPaymentGateway').prop('checked') ? 1 : 0,
                'AllowKatalogOnline' : jQuery('#AllowKatalogOnline').prop('checked') ? 1 : 0,
                'AllowMonitorAntrean' : jQuery('#AllowMonitorAntrean').prop('checked') ? 1 : 0,
                'JenisUsaha' : jQuery('#JenisUsaha').val(),
				'Detail' : oDetail
			};

            // console.log(oData);
            var formtype = jQuery('#formtype').val();

            if (formtype == 'add') {
				$.ajax({
					url: "{{route('subs-storeJson')}}",
					type: 'POST',
					contentType: 'application/json',
					headers: {
		                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
		            },
		            data: JSON.stringify(oData),
		            success: function(response) {
		            	if (response.success == true) {
		            		Swal.fire({
		                        html: "Data berhasil disimpan!",
		                        icon: "success",
		                        title: "Horray...",
		                        // text: "Data berhasil disimpan! <br> " + response.Kembalian,
		                    }).then((result)=>{
		                        jQuery('#btSave').text('Save');
		                        jQuery('#btSave').attr('disabled',false);
		                        // location.reload();
		                        window.location.href = '{{url("subs")}}';
		                    });
		            	}
		            	else{
		            		Swal.fire({
		                      icon: "error",
		                      title: "Opps...",
		                      text: response.message,
		                    })
		                    jQuery('#btSave').text('Save');
		                    jQuery('#btSave').attr('disabled',false);
		            	}
		            }
				})
			}
			else{
				$.ajax({
					url: "{{route('subs-editJson')}}",
					type: 'POST',
					contentType: 'application/json',
					headers: {
		                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
		            },
		            data: JSON.stringify(oData),
		            success: function(response) {
		            	if (response.success == true) {
		            		Swal.fire({
		                        html: "Data berhasil disimpan!",
		                        icon: "success",
		                        title: "Horray...",
		                        // text: "Data berhasil disimpan! <br> " + response.Kembalian,
		                    }).then((result)=>{
		                        jQuery('#btSave').text('Save');
		                        jQuery('#btSave').attr('disabled',false);
		                        // location.reload();
		                        window.location.href = '{{url("subs")}}';
		                    });
		            	}
		            	else{
		            		Swal.fire({
		                      icon: "error",
		                      title: "Opps...",
		                      text: response.message,
		                    })
		                    jQuery('#btSave').text('Save');
		                    jQuery('#btSave').attr('disabled',false);
		            	}
		            }
				})
			}
        });
	});
</script>
@endpush