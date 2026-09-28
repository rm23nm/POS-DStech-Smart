@extends('parts.header')
	
@section('content')
<style type="text/css">
	.xContainer{
	  display: flex;
	  flex-wrap: wrap;
	  justify-content: center;
	  align-items: center;
	  vertical-align: middle;
	}
	.image_result{
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  border: 1px solid black;
	  /*flex-grow: 1;*/
	  vertical-align: middle;
	  align-content: center;
	  flex-basis: 200;
	  width: 150px;
	  height: 200px;
	}
	.image_result img {
	  max-width: 100%; /* Fit the image to the container width */
	  height: 100%; /* Maintain the aspect ratio */
	}

	.image_result_sample{
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  border: 1px solid black;
	  /*flex-grow: 1;*/
	  vertical-align: middle;
	  align-content: center;
	  flex-basis: 200;
	  width: 100%;
	  height: 150px;
	}
	.image_result_sample img {
	  max-width: 100%; /* Fit the image to the container width */
	  height: 100%; /* Maintain the aspect ratio */
	}
	.enableFileControl{
		display: inline!important;
	}
	#isPostingAkutansi[readonly] {
		pointer-events: none;
		touch-action: none;
		background-color: #e9ecef;
		color: #495057;
	}
	.disabled {
		pointer-events: none;
		color: gray;
		cursor: default;
		text-decoration: none;
	}
	
  </style>
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="{{route('companysetting')}}">Setting Data Perusahaan</a>
				</li>
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
										Setting Data perusahaan

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
								<form action="{{route('companysetting-edit')}}" method="post" id="formCompanySetting">	
									@csrf
									<div class="row">
										<div class="col-md-3">
											<ul class="nav flex-column nav-pills mb-3" id="v-pills-tab1" role="tablist" aria-orientation="vertical">
												@if ($company[0]['isActive'] == 0)
													<li class="nav-item" >
														<a class="nav-link active" id="general-tab2" data-bs-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link disabled" id="inv-tab" data-bs-toggle="pill" href="#inv" role="tab" aria-controls="inv" aria-selected="false">Inventory</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link disabled" id="printer-tab" data-bs-toggle="pill" href="#printer" role="tab" aria-controls="printer" aria-selected="false">Printer</a>
													</li>
													<li class="nav-item" style="display: none;">
														<a class="nav-link disabled" id="ecatalog-tab" data-bs-toggle="pill" href="#ecatalog" role="tab" aria-controls="ecatalog" aria-selected="false">E-Catalog</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link disabled" id="bulkaction-tab" data-bs-toggle="pill" href="#bulkaction" role="tab" aria-controls="bulkaction" aria-selected="false">Import Data</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link" id="invoice-tab" data-bs-toggle="pill" href="#invoice" role="tab" aria-controls="invoice" aria-selected="false">Tagihan</a>
													</li>
													<li class="nav-item mt-3" >
														<a class="nav-link disabled font-weight-bolder text-dark" style="background:transparent; padding-bottom: 5px; padding-left:15px;" aria-selected="false">Pengaturan Display</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link disabled" id="custdisplay-pos-tab" data-bs-toggle="pill" href="#custdisplay-pos" role="tab" aria-controls="custdisplay-pos" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-computer me-2"></i> Tampilan POS & Kasir</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link disabled" id="custdisplay-antrean-tab" data-bs-toggle="pill" href="#custdisplay-antrean" role="tab" aria-controls="custdisplay-antrean" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-presentation me-2"></i> Customer Display</a>
													</li>
													@if ($company[0]['JenisUsaha'] == "Hiburan" || $company[0]['JenisUsaha'] == "FnB")
													<li class="nav-item" >
														<a class="nav-link disabled" id="custdisplay-ecatalog-tab" data-bs-toggle="pill" href="#custdisplay-ecatalog" role="tab" aria-controls="custdisplay-ecatalog" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-internet me-2"></i> E-Catalog & Booking Hiburan</a>
													</li>
													@endif
													@if (in_array($company[0]['JenisUsaha'], ["Retail", "Bengkel", "Servis"]))
													<li class="nav-item" >
														<a class="nav-link disabled" id="website-bengkel-tab" data-bs-toggle="pill" href="#website-bengkel" role="tab" aria-controls="website-bengkel" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-internet me-2"></i> Website Booking Bengkel</a>
													</li>
													@endif
													<li class="nav-item" >
														<a class="nav-link" id="domain-tab" data-bs-toggle="pill" href="#domain" role="tab" aria-controls="domain" aria-selected="false">Domain & Midtrans</a>
													</li>
													
													@if ($company[0]['JenisUsaha'] == "Hiburan")
													<li class="nav-item" >
														<a class="nav-link disabled" id="hiburan-tab" data-bs-toggle="pill" href="#hiburan" role="tab" aria-controls="hiburan" aria-selected="false">Hiburan</a>
													</li>
													@endif
													@if ($company[0]['JenisUsaha'] == "Hiburan")
													<li class="nav-item" style="display: none;">
														<a class="nav-link" id="booking-tab" data-bs-toggle="pill" href="#booking" role="tab" aria-controls="booking" aria-selected="false">Booking Online</a>
													</li>
													@endif
												@else
													<li class="nav-item" >
														<a class="nav-link active" id="general-tab2" data-bs-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link" id="inv-tab" data-bs-toggle="pill" href="#inv" role="tab" aria-controls="inv" aria-selected="false">Inventory</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link" id="printer-tab" data-bs-toggle="pill" href="#printer" role="tab" aria-controls="printer" aria-selected="false">Printer</a>
													</li>
													<li class="nav-item" style="display: none;">
														<a class="nav-link" id="ecatalog-tab" data-bs-toggle="pill" href="#ecatalog" role="tab" aria-controls="ecatalog" aria-selected="false">E-Catalog</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link" id="bulkaction-tab" data-bs-toggle="pill" href="#bulkaction" role="tab" aria-controls="bulkaction" aria-selected="false">Import Data</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link" id="invoice-tab" data-bs-toggle="pill" href="#invoice" role="tab" aria-controls="invoice" aria-selected="false">Tagihan</a>
													</li>
													<li class="nav-item mt-3" >
														<a class="nav-link disabled font-weight-bolder text-dark" style="background:transparent; padding-bottom: 5px; padding-left:15px;" aria-selected="false">Pengaturan Display</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link" id="custdisplay-pos-tab" data-bs-toggle="pill" href="#custdisplay-pos" role="tab" aria-controls="custdisplay-pos" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-computer me-2"></i> Tampilan POS & Kasir</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link" id="custdisplay-antrean-tab" data-bs-toggle="pill" href="#custdisplay-antrean" role="tab" aria-controls="custdisplay-antrean" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-presentation me-2"></i> Customer Display</a>
													</li>
													@if ($company[0]['JenisUsaha'] == 'Klinik')
													<li class="nav-item" >
														<a class="nav-link" id="custdisplay-klinik-tab" data-bs-toggle="pill" href="#custdisplay-klinik" role="tab" aria-controls="custdisplay-klinik" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-presentation me-2 text-primary"></i> Layar TV Poli Klinik</a>
													</li>
													<li class="nav-item" >
														<a class="nav-link" id="custdisplay-kiosk-tab" data-bs-toggle="pill" href="#custdisplay-kiosk" role="tab" aria-controls="custdisplay-kiosk" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-presentation me-2 text-warning"></i> Layar TV Kiosk Pendaftaran</a>
													</li>
													@endif
													@if ($company[0]['JenisUsaha'] == "Hiburan" || $company[0]['JenisUsaha'] == "FnB")
													<li class="nav-item" >
														<a class="nav-link" id="custdisplay-ecatalog-tab" data-bs-toggle="pill" href="#custdisplay-ecatalog" role="tab" aria-controls="custdisplay-ecatalog" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-internet me-2"></i> E-Catalog & Booking Hiburan</a>
													</li>
													@endif
													@if (in_array($company[0]['JenisUsaha'], ["Retail", "Bengkel", "Servis"]))
													<li class="nav-item" >
														<a class="nav-link" id="website-bengkel-tab" data-bs-toggle="pill" href="#website-bengkel" role="tab" aria-controls="website-bengkel" aria-selected="false" style="padding-left: 25px;"><i class="flaticon-internet me-2"></i> Website Booking Bengkel</a>
													</li>
													@endif
													<li class="nav-item" >
														<a class="nav-link" id="domain-tab" data-bs-toggle="pill" href="#domain" role="tab" aria-controls="domain" aria-selected="false">Domain & Midtrans</a>
													</li>
													@if ($company[0]['JenisUsaha'] == "Hiburan")
													<li class="nav-item" >
														<a class="nav-link" id="hiburan-tab" data-bs-toggle="pill" href="#hiburan" role="tab" aria-controls="hiburan" aria-selected="false">Hiburan</a>
													</li>
													@endif
													@if ($company[0]['JenisUsaha'] == "Hiburan")
													<li class="nav-item" style="display: none;">
														<a class="nav-link" id="booking-tab" data-bs-toggle="pill" href="#booking" role="tab" aria-controls="booking" aria-selected="false">Booking Online</a>
													</li>
													@endif
												@endif
											</ul>
										</div>
										<div class="col-md-9">
											<div class="tab-content" id="v-pills-tabContent1">
												<div class="tab-pane fade show active" id="general" role="tabpanel" >
													<div class="form-group row">

														<div class="col-md-12"> 
															<fieldset class="form-group mb-3">
																<textarea id = "image_base64" name = "image_base64" style="display: none;">{{ count($company) > 0 ? $company[0]['icon'] : '' }}</textarea>
																
																<input type="file" id="Attachment" name="Attachment" accept=".jpg" class="btn btn-warning" style="display: none;"/>
																<div class="xContainer">
																	<div id="image_result" class="image_result">
																		@if (count($company) > 0)
																			<img src=" {{$company[0]['icon']}} ">
																		@else
																			<img src="https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg">
																		@endif
																	</div>
																</div>
															</fieldset>
															
														</div>

														<div class="col-md-3">
					                            			<label  class="text-body">Kode Perusahaan</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="text" class="form-control" id="KodePartner" name="KodePartner" placeholder="Masukan Kode KelompokRekening" value="{{ count($company) > 0 ? $company[0]['KodePartner'] : '' }}"  readonly="" >
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-9">
					                            			<label  class="text-body">Nama Perusahaan</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="text" class="form-control" id="NamaPartner" name="NamaPartner" placeholder="Masukan Nama Perusahaan" value="{{ count($company) > 0 ? $company[0]['NamaPartner'] : '' }}"  >
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-12">
					                            			<label  class="text-body">Jenis Usaha</label>
					                            			<fieldset class="form-group mb-3">
					                            				<select name="JenisUsaha" id="JenisUsaha" class="js-example-basic-single js-states form-control bg-transparent">
					                            					<option value="" {{ count($company) > 0 ? $company[0]['JenisUsaha'] == '' ? 'selected' : '' : '' }}>Pilih Jenis Usaha</option>
					                            					<option value="Retail" {{ count($company) > 0 ? $company[0]['JenisUsaha'] == 'Retail' ? 'selected' : '' : '' }}>Retail</option>
					                            					<option value="FnB" {{ count($company) > 0 ? $company[0]['JenisUsaha'] == 'FnB' ? 'selected' : '' : '' }}>Food and Beverage</option>
					                            					<option value="Hiburan" {{ count($company) > 0 ? $company[0]['JenisUsaha'] == 'Hiburan' ? 'selected' : '' : '' }}>Hiburan</option>
					                            				</select>
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-12">
					                            			<label  class="text-body">Alamat</label>
					                            			<fieldset class="form-group mb-12">
					                            				<textarea class="form-control" id="AlamatTagihan" name="AlamatTagihan" rows="3" placeholder="Masukan Alamat">{{ count($company) > 0 ? $company[0]['AlamatTagihan'] : '' }}</textarea>
					                            			</fieldset>
					                            		</div>

					                            		<div class="col-md-4">
					                            			<label  class="text-body">Telepon</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="text" class="form-control" id="NoTlp" name="NoTlp" placeholder="Masukan Nomor Telephone" value="{{ count($company) > 0 ? $company[0]['NoTlp'] : '' }}"  >
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-4">
					                            			<label  class="text-body">No. HP</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="text" class="form-control" id="NoHP" name="NoHP" placeholder="Masukan Nomor Handphone" value="{{ count($company) > 0 ? $company[0]['NoHP'] : '' }}"  >
					                            			</fieldset>
					                            			
					                            		</div>

														<div class="col-md-4">
					                            			<label  class="text-body">Email</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="text" class="form-control" id="Email" name="Email" placeholder="Masukan Nomor Email" value="{{ count($company) > 0 ? ($company[0]['Email'] == '' ? ($userdata ? $userdata->email : Auth::user()->email) : $company[0]['Email']) : '' }}"  >
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-6">
					                            			<label  class="text-body">Tgl. Mulai Berlangganan</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="date" class="form-control" id="StartSubs" name="StartSubs" placeholder="Masukan Nomor Handphone" value="{{ count($company) > 0 ? $company[0]['StartSubs'] : '' }}" readonly="" >
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-6">
					                            			<label  class="text-body">Tgl. Selesai Berlangganan</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="date" class="form-control" id="EndSubs" name="EndSubs" placeholder="Masukan Nomor Handphone" value="{{ count($company) > 0 ? $company[0]['EndSubs'] : '' }}" readonly="" >
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-6">
					                            			<label  class="text-body">NPWP</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="text" class="form-control" id="NPWP" name="NPWP" placeholder="Masukan Nomor NPWP" value="{{ count($company) > 0 ? $company[0]['NPWP'] : '' }}">
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-6">
					                            			<label  class="text-body">Tanggal PKP</label>
					                            			<?php echo $company[0]['TglPKP']; ?>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="date" class="form-control" id="TglPKP" name="TglPKP" placeholder="Masukan Nomor Handphone" value="{{ count($company) > 0 ? $company[0]['TglPKP'] : '2000-01-01' }}" >
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-6">
					                            			<label  class="text-body">PPN</label>
					                            			<fieldset class="form-group mb-3">
					                            				<input type="number" class="form-control" id="PPN" name="PPN" placeholder="Masukan Nomor Handphone" value="{{ count($company) > 0 ? $company[0]['PPN'] : 0 }}" >
					                            			</fieldset>
					                            			<label for="isHargaJualIncludePPN">Harga Jual Sudah Termasuk PPN</label>
					                            			<input type="checkbox" class="checkbox-input" id="isHargaJualIncludePPN" {{ count($company) > 0 ? $company[0]['isHargaJualIncludePPN'] == 1 ? 'checked' : '' : '' }}>
					                            			
					                            		</div>

					                            		<div class="col-md-6">
					                            			<label  class="text-body">Posting Akutansi ?</label>
					                            			<fieldset class="form-group mb-3">
					                            				<select name="isPostingAkutansi" id="isPostingAkutansi" class="js-states form-control bg-transparent" readonly>
					                            					<option value="0" {{ count($company) > 0 ? $company[0]['isPostingAkutansi'] == 0 ? 'selected' : '' : '' }} >Tidak</option>
					                            					<option value="1" {{ count($company) > 0 ? $company[0]['isPostingAkutansi'] == 1 ? 'selected' : '' : '' }}>Ya</option>
					                            				</select>
					                            			</fieldset>
					                            			
					                            		</div>
													</div>
												</div>

												<div class="tab-pane fade " id="inv" role="tabpanel" aria-labelledby="inv-tab">
													<div class="form-group row">
														<div class="col-md-12">
					                            			<label  class="text-body">Default Gudang Penjualan PoS</label>
					                            			<fieldset class="form-group mb-3">
					                            				<select name="GudangPoS" id="GudangPoS" class="js-example-basic-single js-states form-control bg-transparent">
																	<option value="">Pilih Gudang</option>
																	@foreach($gudang as $ko)
																		<option value="{{ $ko->KodeGudang }}" {{ $ko->KodeGudang == (count($company) > 0 ? $company[0]['GudangPoS'] : '') ? 'selected' : '' }}>
								                                            {{ $ko->NamaGudang }}
								                                        </option>
																	@endforeach
																</select>
					                            			</fieldset>
					                            		</div>

					                            		<div class="col-md-12">
					                            			<label  class="text-body">Default Termin PoS</label>
					                            			<fieldset class="form-group mb-3">
					                            				<select name="TerminBayarPoS" id="TerminBayarPoS" class="js-example-basic-single js-states form-control bg-transparent">
																	<option value="">Pilih Termin</option>
																	@foreach($temin as $ko)
																		<option value="{{ $ko->id }}" {{ $ko->id == (count($company) > 0 ? $company[0]['TerminBayarPoS'] : '') ? 'selected' : '' }}>
								                                            {{ $ko->NamaTermin }}
								                                        </option>
																	@endforeach
																</select>
					                            			</fieldset>
					                            		</div>

					                            		<div class="col-md-12">
					                            			<label  class="text-body">Izinkan Inventory Menjadi Negative (-) ?</label>
					                            			<fieldset class="form-group mb-3">
					                            				<select name="AllowNegativeInventory" id="AllowNegativeInventory" class="js-example-basic-single js-states form-control bg-transparent">
																	<option value="N" {{ count($company) > 0 ? $company[0]['AllowNegativeInventory'] == 'N' ? 'selected' : '' : '' }}>TIDAK</option>
																	<option value="Y" {{ count($company) > 0 ? $company[0]['AllowNegativeInventory'] == 'Y' ? 'selected' : '' : '' }}>YA</option>
																</select>
					                            			</fieldset>
					                            		</div>

													</div>
												</div>

												<div class="tab-pane fade " id="printer" role="tabpanel" aria-labelledby="printer-tab">
													<div class="form-group row">
														<div class="col-md-4">
					                            			<label  class="text-body">Printer Register</label>
					                            			<fieldset class="form-group mb-4">
					                            				<select name="NamaPosPrinter" id="NamaPosPrinter" class="js-example-basic-single js-states form-control bg-transparent">
					                            					<option value="-1">Pilih Printer</option>
																	@foreach($printer as $ko)
																		<option {{ count($company) > 0 ? $company[0]['NamaPosPrinter'] == $ko->DeviceAddress ? "selected" : '' :""}} value="{{ $ko->DeviceAddress }}">
					                                                        {{ $ko->NamaPrinter. ' > '. $ko->PrinterInterface }}
					                                                    </option>
																	@endforeach
					                            				</select>
					                            			</fieldset>
					                            			
					                            		</div>

					                            		<div class="col-md-3">
					                            			<label  class="text-body">Lebar Kertas</label>
					                            			<fieldset class="form-group mb-3">
					                            				<select name="LebarKertas" id="LebarKertas" class="js-example-basic-single js-states form-control bg-transparent">
					                            					<option value="48">48</option>
					                            					<option value="58">58</option>
					                            					<option value="80">80</option>
					                            				</select>
					                            			</fieldset>
					                            		</div>
														<!--
					                            		<div class="col-md-3">
					                            			<fieldset class="form-group mb-3">
					                            				<button type="button" class="btn btn-warning">Test Print</button>
					                            			</fieldset>
					                            		</div>
					                            		<div class="col-md-3">
					                            			<fieldset class="form-group mb-3">
					                            				<button type="button" class="btn btn-warning" id="testPrintUSB">Test Print Usb</button>
					                            			</fieldset>
					                            		</div> -->

					                            		<!-- <a href="{{ url('companysetting/testprint') }}">Test Print</a> -->
					                            		<div class="col-md-12">
					                            			<label  class="text-body">Keterangan Footer</label>
					                            			<fieldset class="form-group mb-12">
					                            				<textarea class="form-control" id="FooterNota" name="FooterNota" rows="3" placeholder="Masukan Alamat">{{ count($company) > 0 ? $company[0]['FooterNota'] : '' }}</textarea>
					                            			</fieldset>
					                            		</div>

														<div class="col-md-4">
					                            			<label  class="text-body">Format Faktur</label>
					                            			<fieldset class="form-group mb-3">
					                            				<select name="DefaultSlip" id="DefaultSlip" class="js-example-basic-single js-states form-control bg-transparent">
					                            					<option value="slip1" {{ count($company) > 0 ? $company[0]['DefaultSlip'] == "slip1"? "selected" : '' :""}} >Slip 1</option>
					                            					<option value="slip2" {{ count($company) > 0 ? $company[0]['DefaultSlip'] == "slip2"? "selected" : '' :""}} >Slip 2</option>
					                            					<option value="slip3" {{ count($company) > 0 ? $company[0]['DefaultSlip'] == "slip3"? "selected" : '' :""}} >Slip 3</option>
																	<option value="slip4" {{ count($company) > 0 ? $company[0]['DefaultSlip'] == "slip4"? "selected" : '' :""}} >Slip 4</option>
																	<option value="slip5" {{ count($company) > 0 ? $company[0]['DefaultSlip'] == "slip5"? "selected" : '' :""}} >Slip 5</option>
					                            				</select>
					                            		</div>

														<div class="col-md-8">
															<label  class="text-body">Preview</label>
															<fieldset class="form-group mb-3">
																<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" id="PreviewImageSlip" width="100%">
															</fieldset>
														</div>

													</div>
												</div>

												<div class="tab-pane fade " id="bulkaction" role="tabpanel" aria-labelledby="printer-tab">
													<div class="form-group row">
														<div class="col-md-10">
					                            			<label  class="text-body">Daftar Barang <a href="{{ asset('sample/BulkItemMaster.xlsx')}}">Download Sample</a></label>
					                            			<fieldset class="form-group mb-4">
					                            				<input type="file" class="form-control enableFileControl" id="BulkItemMaster" name="BulkItemMaster" accept=".xls, .xlsx" />
					                            			</fieldset>
					                            			
					                            		</div>
														<div class="col-md-2">
															<label  class="text-body">.</label>
															<fieldset class="form-group mb-4">
																<button type="button" class="btn btn-success text-white font-weight-bold me-1 mb-1" id="btInportItem">Proses</button>
															</fieldset>
														</div>

														<div class="col-md-10">
					                            			<label  class="text-body">Harga Jual <a href="{{ asset('sample/BulkHargaJual.xlsx')}}">Download Sample</a></label>
					                            			<fieldset class="form-group mb-4">
					                            				<input type="file" class="form-control enableFileControl" id="BulkHargaJual" name="BulkHargaJual" accept=".xls, .xlsx" />
					                            			</fieldset>
					                            			
					                            		</div>
														<div class="col-md-2">
															<label  class="text-body">.</label>
															<fieldset class="form-group mb-4">
																<button type="button" class="btn btn-success text-white font-weight-bold me-1 mb-1" id="btInportHargaJual">Proses</button>
															</fieldset>
														</div>

														<div class="col-md-10">
					                            			<label  class="text-body">Pelanggan <a href="{{ asset('sample/BulkPelanggan.xlsx')}}">Download Sample</a></label>
					                            			<fieldset class="form-group mb-4">
					                            				<input type="file" class="form-control enableFileControl" id="BulkPelanggan" name="BulkPelanggan" accept=".xls, .xlsx" />
					                            			</fieldset>
					                            			
					                            		</div>
														<div class="col-md-2">
															<label  class="text-body">.</label>
															<fieldset class="form-group mb-4">
																<button type="button" class="btn btn-success text-white font-weight-bold me-1 mb-1" id="btInportPelanggan">Proses</button>
															</fieldset>
														</div>

														<div class="col-md-10">
					                            			<label  class="text-body">Supplier <a href="{{ asset('sample/BulkSupplier.xlsx')}}">Download Sample</a></label>
					                            			<fieldset class="form-group mb-4">
					                            				<input type="file" class="form-control enableFileControl" id="BulkSupplier" name="BulkSupplier" accept=".xls, .xlsx" />
					                            			</fieldset>
					                            			
					                            		</div>
														<div class="col-md-2">
															<label  class="text-body">.</label>
															<fieldset class="form-group mb-4">
																<button type="button" class="btn btn-success text-white font-weight-bold me-1 mb-1" id="btInportSupplier">Proses</button>
															</fieldset>
														</div>
													</div>
												</div>

												<div class="tab-pane fade " id="invoice" role="tabpanel" aria-labelledby="printer-tab">
													<div class="row">
														<div class="col-md-3">
															<label  class="text-body">Tanggal Awal</label>
															<input type="date" name="TglAwal" id="TglAwal" class="form-control">
														</div>
														<div class="col-md-3">
															<label  class="text-body">Tanggal Akhir</label>
															<input type="date" name="TglAkhir" id="TglAkhir" class="form-control">
														</div>
													</div>
													<hr>
													<div class="form-group row">
														<div class="col-md-12">
					                            			<div class="table-responsive" id="printableTable">
																<table id="invoiceTable" class="display" style="width:100%">
																	<thead>
																		<tr>
																			<th>Nomor Invoice</th>
																			<th>Tgl Invoice</th>
																			<th>Nama Paket</th>
																			<th>Total</th>
																			<th>Tgl Jatuh Tempo</th>
																			<th>Status</th>
																			<th>Action</th>
																		</tr>
																	</thead>
																</table>
															</div>

					                            		</div>
													</div>
												</div>
												<div class="tab-pane fade " id="domain" role="tabpanel" aria-labelledby="domain-tab">
													<div class="row">
														<div class="col-md-12">
															<h4 class="mb-4">Pengaturan Domain Kustom</h4>
															<div class="alert alert-light-primary py-2 px-3 mb-4">
																<small><i class="fas fa-info-circle me-1"></i> Gunakan domain kustom (contoh: <b>order.kantin.com</b>) untuk memberikan pengalaman brand sendiri kepada pelanggan.</small>
															</div>
														</div>
														
														<div class="col-md-6">
															<label class="text-body fw-bold">Domain Toko FnB (Store)</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="CustomDomain" placeholder="contoh: order.gorfit.com" value="{{ count($company) > 0 ? $company[0]['CustomDomain'] : '' }}">
															</fieldset>
														</div>

														<div class="col-md-6">
															<label class="text-body fw-bold">Domain Booking Online</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="CustomDomainBooking" placeholder="contoh: booking.gorfit.com" value="{{ count($company) > 0 ? $company[0]['CustomDomainBooking'] : '' }}">
															</fieldset>
														</div>

														<div class="col-md-6">
															<label class="text-body fw-bold">Domain Monitor Antrean (Queue)</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="CustomDomainQueue" placeholder="contoh: antrean.gorfit.com" value="{{ count($company) > 0 ? $company[0]['CustomDomainQueue'] : '' }}">
															</fieldset>
														</div>

														<div class="col-md-6">
															<label class="text-body fw-bold">Domain Kitchen Display (KDS)</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="CustomDomainKDS" placeholder="contoh: dapur.gorfit.com" value="{{ count($company) > 0 ? $company[0]['CustomDomainKDS'] : '' }}">
															</fieldset>
														</div>

														<div class="col-md-12 mt-4">
															<hr>
															<h4 class="mb-4">Integrasi Pembayaran Midtrans</h4>
															<div class="alert alert-light-warning py-2 px-3 mb-4">
																<small><i class="fas fa-key me-1"></i> Masukkan Server Key & Client Key dari Dashboard Midtrans Anda untuk mengaktifkan pembayaran otomatis.</small>
															</div>
														</div>

														<div class="col-md-6">
															<label class="text-body fw-bold">Midtrans Client Key</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="MidtransClientKey" placeholder="SB-Mid-client-..." value="{{ count($company) > 0 ? $company[0]['MidtransClientKey'] : '' }}">
															</fieldset>
														</div>

														<div class="col-md-6">
															<label class="text-body fw-bold">Midtrans Server Key</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="MidtransServerKey" placeholder="SB-Mid-server-..." value="{{ count($company) > 0 ? $company[0]['MidtransServerKey'] : '' }}">
															</fieldset>
														</div>

														{{-- ===== SECTION: NOTIFIKASI EXPIRED WA ===== --}}
														<div class="col-md-12 mt-4">
															<hr>
															<h4 class="mb-3"><i class="fab fa-whatsapp text-success mr-2"></i> Pengaturan Notifikasi WA Barang Expired</h4>
															<div class="alert alert-light-success py-2 px-3 mb-4">
																<small><i class="fas fa-bell me-1"></i> Sistem akan mengirimkan peringatan otomatis setiap hari pukul <b>08:00 pagi</b> ke nomor WA yang didaftarkan di bawah ini, jika ada barang yang mendekati atau sudah melewati tanggal kedaluwarsa.</small>
															</div>
														</div>

														<div class="col-md-3">
															<label class="text-body fw-bold">Hari Peringatan Expired</label>
															<fieldset class="form-group mb-3">
																<div class="input-group">
																	<input type="number" class="form-control" name="ExpiredAlertDays" placeholder="90" min="1" max="365" value="{{ count($company) > 0 ? ($company[0]['ExpiredAlertDays'] ?? 90) : 90 }}">
																	<div class="input-group-append"><span class="input-group-text">hari</span></div>
																</div>
																<small class="text-muted">Peringatan warna kuning akan muncul untuk barang yang akan expired dalam jumlah hari ini.</small>
															</fieldset>
														</div>

														<div class="col-md-9">
															<label class="text-body fw-bold">Nomor WA Tujuan Notifikasi</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="ExpiredAlertWA" placeholder="628123456789,628987654321" value="{{ count($company) > 0 ? ($company[0]['ExpiredAlertWA'] ?? '') : '' }}">
																<small class="text-muted">Masukkan nomor WA dalam format internasional (62xxx). Untuk lebih dari satu nomor, pisahkan dengan koma. Contoh: <code>6281234567,6287654321</code></small>
															</fieldset>
														</div>

														<div class="col-md-12 mt-2">
															<hr>
															<h5 class="mb-3"><i class="fas fa-key text-warning mr-2"></i> Kredensial Smartpro (Akun Client)</h5>
															<div class="alert alert-light-warning py-2 px-3 mb-4">
																<small><i class="fas fa-info-circle me-1"></i> Masukkan API Key dan Nomor Pengirim dari akun <b>Smartpro</b> Anda sendiri. Jika dikosongkan, sistem akan menggunakan akun Smartpro master. Login ke <a href="https://smartpro.dstechsmart.com" target="_blank">smartpro.dstechsmart.com</a> untuk mendapatkan API Key.</small>
															</div>
														</div>

														<div class="col-md-6">
															<label class="text-body fw-bold">Smartpro API Key</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="SmartproApiKey" placeholder="Masukkan API Key dari akun Smartpro Anda" value="{{ count($company) > 0 ? ($company[0]['SmartproApiKey'] ?? '') : '' }}">
															</fieldset>
														</div>

														<div class="col-md-6">
															<label class="text-body fw-bold">Nomor WA Pengirim (Device Smartpro)</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" name="SmartproSender" placeholder="628xxx (nomor WA yang terhubung di Smartpro)" value="{{ count($company) > 0 ? ($company[0]['SmartproSender'] ?? '') : '' }}">
															</fieldset>
														</div>
														
								{{-- ===== GATE API KEY SECTION ===== --}}
								<div class="card card-custom gutter-b mt-6 border border-light-danger bg-white shadow-sm rounded">
									<div class="card-header h-auto py-3 bg-light-danger border-0 rounded-top">
										<div class="card-title m-0">
											<h3 class="card-label text-danger font-weight-bolder m-0" style="font-size: 1.25rem;">
												<i class="fas fa-door-open me-2 text-danger"></i> Gate Agent — API Key
											</h3>
										</div>
									</div>
									<div class="card-body py-4">
										<div class="row">
											<div class="col-12 mb-3">
												<div class="alert alert-light-info border border-info rounded">
													<i class="fas fa-info-circle text-info me-2"></i>
													<strong>Apa ini?</strong> API Key ini digunakan oleh <strong>Gate Agent</strong> yang terinstall di PC Client (lokasi usaha) untuk sinkronisasi data tiket &amp; kartu member dari server ini. Berikan key ini kepada teknisi saat instalasi Gate Agent.
												</div>
											</div>
											<div class="col-md-8">
												<label class="text-body fw-bold">Gate Agent API Key</label>
												<fieldset class="form-group mb-3">
													<div class="input-group">
														<input type="text" class="form-control font-monospace" id="GateApiKeyInput" name="GateApiKey"
															placeholder="Klik Generate untuk membuat API Key baru"
															value="{{ count($company) > 0 ? ($company[0]['GateApiKey'] ?? '') : '' }}"
															readonly>
														<button type="button" class="btn btn-light-primary px-4" onclick="copyGateApiKey()" title="Salin Key">
															<i class="fas fa-copy"></i> Salin
														</button>
													</div>
													<small class="text-muted">API Key bersifat rahasia. Jangan berikan kepada pihak yang tidak berkepentingan.</small>
												</fieldset>
											</div>
											<div class="col-md-4 d-flex align-items-center">
												<button type="button" class="btn btn-danger w-100" onclick="generateGateApiKey()">
													<i class="fas fa-key me-2"></i> Generate API Key Baru
												</button>
											</div>
										</div>
									</div>
								</div>
								{{-- ===== END GATE API KEY SECTION ===== --}}
														{{-- ===== END SECTION ===== --}}

													</div>
												</div>

												<div class="tab-pane fade" id="custdisplay-pos" role="tabpanel" aria-labelledby="custdisplay-pos-tab">
													<div class="row">
														
														<!-- CARD 1: DISPLAY POS & KASIR -->
														<div class="card card-custom gutter-b mb-6 border border-light-primary bg-white shadow-sm rounded">
															<div class="card-header h-auto py-3 bg-light-primary border-0 rounded-top">
																<div class="card-title m-0">
																	<h3 class="card-label text-primary font-weight-bolder m-0" style="font-size: 1.25rem;">
																		<i class="flaticon-computer me-2 text-primary"></i> 1. Tampilan POS & Kasir
																	</h3>
																</div>
															</div>
															<div class="card-body py-4">
																<div class="row">
																	<div class="col-md-4 mb-3 d-none">
																		<label class="text-body fw-bold">Template Tampilan POS Retail</label>
																		<fieldset class="form-group mb-3">
																			<select name="PosTemplate" id="PosTemplate" class="js-example-basic-single js-states form-control bg-transparent">
																				<option value="NormalPoS" {{ count($company) > 0 ? $company[0]['PosTemplate'] == "NormalPoS" ? "selected" : '' :""}} >Standard POS (Default)</option>
																				<option value="NormalPoS_Premium" {{ count($company) > 0 ? $company[0]['PosTemplate'] == "NormalPoS_Premium" ? "selected" : '' :""}} >Premium POS (Modern Glow)</option>
																			</select>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-4 mb-3 d-none">
																		<label class="text-body fw-bold">Type Background POS</label>
																		<fieldset class="form-group mb-3">
																			<select name="TypeBackgraund" id="TypeBackgraund" class="js-example-basic-single js-states form-control bg-transparent">
																				<option value="Color" {{ count($company) > 0 && $company[0]['TypeBackgraund'] == 'Color' ? 'selected' : '' }}>Color</option>
																				<option value="Image" {{ count($company) > 0 && $company[0]['TypeBackgraund'] == 'Image' ? 'selected' : '' }}>Image</option>
																			</select>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-4 mb-3 d-none">
																		<label class="text-body fw-bold">Background POS</label>
																		<fieldset class="form-group mb-3" id="backgroundInput">
																			<!-- Dinamis oleh JS -->
																		</fieldset>
																	</div>
																	
																	<div class="col-md-6 mb-3">
																		<label class="text-body fw-bold">Tipe Layar Antrean/Proses (KDS)</label>
																		<fieldset class="form-group mb-3">
																			<select name="TypeKitchenBackgraund" id="TypeKitchenBackgraund" class="js-example-basic-single js-states form-control bg-transparent">
																				<option value="Color" {{ count($company) > 0 && $company[0]['TypeKitchenBackgraund'] == 'Color' ? 'selected' : '' }}>Color</option>
																				<option value="Image" {{ count($company) > 0 && $company[0]['TypeKitchenBackgraund'] == 'Image' ? 'selected' : '' }}>Image</option>
																			</select>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-6 mb-3">
																		<label class="text-body fw-bold">Background Layar (KDS)</label>
																		<fieldset class="form-group mb-3" id="kitchenBackgroundInput">
																			<!-- Dinamis oleh JS -->
																		</fieldset>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="tab-pane fade" id="custdisplay-antrean" role="tabpanel" aria-labelledby="custdisplay-antrean-tab">
													<div class="row">
														<!-- CARD 2: CUSTOMER DISPLAY -->
														<div class="card card-custom gutter-b mb-6 border border-light-success bg-white shadow-sm rounded">
															<div class="card-header h-auto py-3 bg-light-success border-0 rounded-top">
																<div class="card-title m-0">
																	<h3 class="card-label text-success font-weight-bolder m-0" style="font-size: 1.25rem;">
																		<i class="flaticon-presentation me-2 text-success"></i> 2. Customer Display (Layar Antrean & Promo)
																	</h3>
																</div>
															</div>
															<div class="card-body py-4">
																<div class="row">
																	<div class="col-sm-12 mb-3">
																		<label class="text-body fw-bold">Promo tampil customer display</label>
																		<fieldset class="form-group mb-3">
																			<div id="PromoDsiplay">
																				{!! count($company) > 0 ? $company[0]['PromoDsiplay'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Running Text</label>
																		<fieldset class="form-group mb-3">
																			<input type="text" class="form-control" id="RunningText" name="RunningText" placeholder="Masukan Running Text" value="{{ count($company) > 0 ? $company[0]['RunningText'] : 0 }}" >
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Model Tampilan Customer Display</label>
																		<fieldset class="form-group mb-3">
																			<select name="CustDisplayDesignSetting" id="CustDisplayDesignSetting" class="js-example-basic-single js-states form-control bg-transparent">
																				<option value="default" {{ (count($company) > 0 && $company[0]['CustDisplayDesignSetting'] == "default") ? "selected" : "" }}>Model Standar Premium (Biru-Merah Neon)</option>
																				<option value="model_neon_red" {{ (count($company) > 0 && $company[0]['CustDisplayDesignSetting'] == "model_neon_red") ? "selected" : "" }}>Model Neon Merah (Full Red Theme & Neonbox)</option>
																				<option value="model_neon_blue" {{ (count($company) > 0 && $company[0]['CustDisplayDesignSetting'] == "model_neon_blue") ? "selected" : "" }}>Model Neon Biru (Full Blue Theme & Neonbox)</option>
																				<option value="model_neon_split" {{ (count($company) > 0 && $company[0]['CustDisplayDesignSetting'] == "model_neon_split") ? "selected" : "" }}>Model Neon Split (Merah-Biru Kombinasi Dinamis)</option>
																			</select>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold mb-2">Gambar Slide Promo Customer Display</label>
																		<div class="row">
																			@for ($i = 1; $i <= 5; $i++)
																			<div class="col-sm-3 mb-3">
																				<fieldset class="form-group mb-0">
																					<textarea id="ImageCustDisplay{{$i}}Base64" name="ImageCustDisplay{{$i}}Base64" style="display: none;">{{ count($company) > 0 ? $company[0]['ImageCustDisplay'.$i] : '' }}</textarea>
																					<input type="file" id="fileImageCustDisplay{{$i}}" name="fileImageCustDisplay{{$i}}" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
																					<div class="xContainer border rounded p-1 text-center bg-light">
																						<div id="ImageCustDisplay{{$i}}" class="image_result_sample" style="height: 120px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
																							@if (!empty($company[0]['ImageCustDisplay'.$i]))
																								<img src="{{$company[0]['ImageCustDisplay'.$i]}}" style="max-height: 100%; max-width: 100%;">
																							@else
																								<img src="https://png.pngtree.com/png-vector/20221125/ourmid/pngtree-no-image-available-icon-flatvector-illustration-blank-avatar-modern-vector-png-image_40962406.jpg" style="max-height: 100%; max-width: 100%;">
																							@endif
																						</div>
																						<span class="text-muted d-block mt-1 font-size-xs">Slide #{{$i}}</span>
																					</div>
																				</fieldset>
																			</div>
																			@endfor
																		</div>
																	</div>
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Video Customer Display (Youtube ID / Drive)</label>
																		<div class="row">
																			@for ($v = 1; $v <= 5; $v++)
																			<div class="col-md-6 mb-3">
																				<fieldset class="form-group mb-2">
																					<label class="font-size-sm text-muted">Link/ID Video {{$v}}</label>
																					<input type="text" class="form-control" id="VideoCustomerDisplay{{$v}}" name="VideoCustomerDisplay{{$v}}" placeholder="Contoh: IcJCYEZMpwA" value="{{ count($company) > 0 ? $company[0]['VideoCustomerDisplay'.$v] : '' }}" >
																				</fieldset>
																			</div>
																			@endfor
																		</div>
																	</div>

																	</div>
																</div>
															</div>
														</div>
													</div>


												@if ($company[0]['JenisUsaha'] == "Klinik")
												<div class="tab-pane fade" id="custdisplay-klinik" role="tabpanel" aria-labelledby="custdisplay-klinik-tab">
													<div class="row">
														<!-- CARD: CUSTOMER DISPLAY KLINIK -->
														<div class="card card-custom gutter-b border border-light-primary bg-white shadow-sm rounded w-100">
															<div class="card-header h-auto py-3 bg-light-primary border-0 rounded-top">
																<div class="card-title m-0">
																	<h3 class="card-label text-primary font-weight-bolder m-0" style="font-size: 1.25rem;">
																		<i class="flaticon-presentation me-2 text-primary"></i> Layar TV Antrean Poli Klinik
																	</h3>
																</div>
															</div>
															<div class="card-body py-4">
																<div class="row">
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Video TV Poli Klinik (Youtube URL / mp4 bebas iklan)</label>
																		<p class="text-muted text-sm">Jika menggunakan URL video berakhiran .mp4, video akan otomatis diputar berulang tanpa iklan.</p>
																		<div class="row">
																			@for ($v = 1; $v <= 5; $v++)
																			<div class="col-md-6 mb-3">
																				<fieldset class="form-group mb-2">
																					<label class="font-size-sm text-muted">Video Klinik {{$v}} (Tampil di Semua Poli)</label>
																					<input type="text" class="form-control" id="VideoKlinikDisplay{{ $v > 1 ? $v : '' }}" name="VideoKlinikDisplay{{ $v > 1 ? $v : '' }}" placeholder="Contoh: https://www.youtube.com/embed/... atau link .mp4" value="{{ count($company) > 0 ? $company[0]['VideoKlinikDisplay' . ($v > 1 ? $v : '')] : '' }}" >
																				</fieldset>
																			</div>
																			@endfor
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
                                            <div class="tab-pane fade" id="custdisplay-kiosk" role="tabpanel" aria-labelledby="custdisplay-kiosk-tab">
										<div class="row">
											<div class="col-md-12">
												<h4 class="mb-4"><i class="flaticon-presentation me-2 text-warning"></i> Layar TV Kiosk Pendaftaran</h4>
												
												<label class="text-body fw-bold">Background Kiosk Antrean Pendaftaran</label>
												<fieldset class="form-group mb-5">
													<textarea id="KioskBackgroundBase64" name="KioskBackgroundBase64" style="display: none;">{{ count($company) > 0 ? $company[0]['KioskBackground'] : '' }}</textarea>
													<input type="file" id="fileKioskBackground" name="fileKioskBackground" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
													<div class="xContainer">
														<div id="KioskBackgroundPreview" class="image_result_sample" style="cursor:pointer;" title="Klik untuk mengubah gambar">
															@if (count($company) > 0 && $company[0]['KioskBackground'] != '')
																<img src="{{$company[0]['KioskBackground']}}" style="max-height: 200px;">
															@else
																<img src="https://png.pngtree.com/png-vector/20221125/ourmid/pngtree-no-image-available-icon-flatvector-illustration-blank-avatar-modern-vector-png-image_40962406.jpg" style="max-height: 200px;">
															@endif
														</div>
													</div>
													<small class="text-muted mt-2 d-block">Klik gambar di atas untuk mengunggah background baru.</small>
												</fieldset>

												<label class="text-body fw-bold">Manajemen Loket Fisik</label>
												<fieldset class="form-group mb-5">
													<a href="{{ route('klinik-loket.index') }}" class="btn btn-outline-primary"><i class="fas fa-list"></i> Atur Daftar Loket Fisik Kiosk</a>
													<p class="text-muted mt-2">Gunakan tombol di atas untuk menambah, mengubah, atau menghapus Loket (misal: LOKET UMUM, LOKET 1). Loket ini akan ditampilkan di kotak bagian bawah layar TV Kiosk.</p>
												</fieldset>
											</div>
										</div>


											</div>
												@endif
												@if ($company[0]['JenisUsaha'] == "Hiburan" || $company[0]['JenisUsaha'] == "FnB")
												<div class="tab-pane fade" id="custdisplay-ecatalog" role="tabpanel" aria-labelledby="custdisplay-ecatalog-tab">
													<div class="row">
														<!-- CARD 3: WEBSITE UTAMA & BOOKING ONLINE -->
														<div class="card card-custom gutter-b mb-6 border border-light-info bg-white shadow-sm rounded">
															<div class="card-header h-auto py-3 bg-light-info border-0 rounded-top">
																<div class="card-title m-0">
																	<h3 class="card-label text-info font-weight-bolder m-0" style="font-size: 1.25rem;">
																		<i class="flaticon-internet me-2 text-info"></i> 3. Website E-Catalog & Booking Online
																	</h3>
																</div>
															</div>
															<div class="card-body py-4">
																
																<!-- Accordion Banners E-Catalog -->
																<h5 class="text-dark font-weight-bold mb-4 mt-2">Banners & Promosi Utama Website</h5>
																<div class="accordion mb-4" id="accordionBanners">
																	@for ($b = 1; $b <= 3; $b++)
																	<div class="accordion-item border mb-2 rounded">
																		<h2 class="accordion-header" id="headingBanner{{$b}}">
																			<button class="accordion-button {{ $b > 1 ? 'collapsed' : '' }} py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBanner{{$b}}" aria-expanded="{{ $b == 1 ? 'true' : 'false' }}" aria-controls="collapseBanner{{$b}}">
																				Banner Promosi #{{$b}}
																			</button>
																		</h2>
																		<div id="collapseBanner{{$b}}" class="accordion-collapse collapse {{ $b == 1 ? 'show' : '' }}" aria-labelledby="headingBanner{{$b}}" data-bs-parent="#accordionBanners">
																			<div class="accordion-body">
																				<div class="row">
																					<div class="col-sm-12 mb-3">
																						<label class="text-body fw-bold">Banner Header</label>
																						<fieldset class="form-group mb-2">
																							<div id="BannerHeader{{$b}}">
																								{!! count($company) > 0 ? $company[0]['BannerHeader'.$b] : '' !!}
																							</div>
																						</fieldset>
																					</div>
																					<div class="col-sm-12 mb-3">
																						<label class="text-body fw-bold">Banner Text (Deskripsi)</label>
																						<fieldset class="form-group mb-2">
																							<div id="BannerText{{$b}}">
																								{!! count($company) > 0 ? $company[0]['BannerText'.$b] : '' !!}
																							</div>
																						</fieldset>
																					</div>
																					<div class="col-sm-12 mb-3">
																						<label class="text-body fw-bold">Gambar Banner</label>
																						<fieldset class="form-group mb-2">
																							<textarea id="Banner{{$b}}Base64" name="Banner{{$b}}Base64" style="display: none;">{{ count($company) > 0 ? $company[0]['Banner'.$b] : '' }}</textarea>
																							<input type="file" id="fileBanner{{$b}}" name="fileBanner{{$b}}" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
																							<div class="xContainer border rounded p-1 text-center bg-light">
																								<div id="Banner{{$b}}" class="image_result_sample" style="max-height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
																									@if (count($company) > 0 && !empty($company[0]['Banner'.$b]))
																										<img src="{{$company[0]['Banner'.$b]}}" style="max-height: 100%; max-width: 100%;">
																									@else
																										<img src="https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg" style="max-height: 100%; max-width: 100%;">
																									@endif
																								</div>
																							</div>
																						</fieldset>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	@endfor
																</div>
																
																<hr class="my-4">
																
																<!-- Booking Online Info -->
																<h5 class="text-dark font-weight-bold mb-4">Pengaturan Landing Page & Booking Online</h5>
																<div class="row">
																	<div class="col-md-6 mb-3">
																		<label class="text-body fw-bold">Template Booking Online</label>
																		<fieldset class="form-group mb-3">
																			<select name="DefaultLandingPages" id="DefaultLandingPages" class="js-example-basic-single js-states form-control bg-transparent">
																				<option value="bo1" {{count($company) > 0 ? $company[0]['DefaultLandingPages'] == "bo1"? "selected" : '' :""}} >Template 1</option>
																				<option value="bo2" {{count($company) > 0 ? $company[0]['DefaultLandingPages'] == "bo2"? "selected" : '' :""}} >Template 2</option>
																			</select>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-6 mb-3">
																		<label class="text-body fw-bold">Default Thema Warna Website</label>
																		<fieldset class="form-group mb-3">
																			<input type="color" class="form-control" id="DefaultLandingPageColor" name="DefaultLandingPageColor" value="{{ count($company) > 0 ? $company[0]['DefaultLandingPageColor'] : '' }}" >
																		</fieldset>
																	</div>

																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Preview Template Booking</label>
																		<fieldset class="form-group mb-3 text-center">
																			<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" id="PreviewImageTemplate" class="border rounded shadow-xs" style="max-width: 350px; width: 100%;">
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Tampilkan Link URL Di Struk Transaksi</label>
																		<fieldset class="form-group mb-3">
																			<select name="ShowLinkInReciept" id="ShowLinkInReciept" class="js-example-basic-single js-states form-control bg-transparent">
																				<option value="0" {{ count($company) > 0 ? $company[0]['ShowLinkInReciept'] == '0' ? 'selected' : '' : '' }}>Tidak</option>
																				<option value="1" {{ count($company) > 0 ? $company[0]['ShowLinkInReciept'] == '1' ? 'selected' : '' : '' }}>Ya</option>
																			</select>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-4">
																		<label class="text-body fw-bold">Upload Image Banner Booking</label>
																		<fieldset class="form-group mb-3">
																			<textarea id="BannerBookingBase64" name="BannerBookingBase64" style="display: none;">{{ count($company) > 0 ? $company[0]['BannerBooking'] : '' }}</textarea>
																			<input type="file" id="fileBannerBooking" name="fileBannerBooking" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
																			<div class="xContainer border rounded p-1 text-center bg-light">
																				<div id="BannerBooking" class="image_result_sample" style="max-height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
																					@if (count($company) > 0 && !empty($company[0]['BannerBooking']))
																						<img src="{{$company[0]['BannerBooking']}}" style="max-height: 100%; max-width: 100%;">
																					@else
																						<img src="https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg" style="max-height: 100%; max-width: 100%;">
																					@endif
																				</div>
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Headline Banner Booking</label>
																		<fieldset class="form-group mb-3">
																			<div id="HeadlineBanner">
																				{!! count($company) > 0 ? $company[0]['HeadlineBanner'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-4">
																		<label class="text-body fw-bold">Sub Headline Banner Booking</label>
																		<fieldset class="form-group mb-3">
																			<div id="SubHeadlineBanner">
																				{!! count($company) > 0 ? $company[0]['SubHeadlineBanner'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-6 mb-3">
																		<label class="text-body fw-bold">Jam Awal Booking</label>
																		<fieldset class="form-group mb-3">
																			<input type="text" class="form-control" id="JamAwalBooking" name="JamAwalBooking" step="60" value="{{ count($company) > 0 && $company[0]['JamAwalBooking'] ? date('H:i', strtotime($company[0]['JamAwalBooking'])) : '' }}">
																		</fieldset>
																	</div>
																	
																	<div class="col-md-6 mb-3">
																		<label class="text-body fw-bold">Jam Akhir Booking</label>
																		<fieldset class="form-group mb-3">
																			<input type="text" class="form-control" id="JamAkhirBooking" name="JamAkhirBooking" step="60" value="{{ count($company) > 0 && $company[0]['JamAkhirBooking'] ? date('H:i', strtotime($company[0]['JamAkhirBooking'])) : '' }}">
																		</fieldset>
																	</div>
																</div>
																
																<hr class="my-4">
																
																<!-- Syarat & Ketentuan -->
																<h5 class="text-dark font-weight-bold mb-4">Syarat & Ketentuan (Term & Conditions)</h5>
																<div class="row">
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Term & Condition Umum</label>
																		<fieldset class="form-group mb-3">
																			<div id="TermAndCondition">
																				{!! count($company) > 0 ? $company[0]['TermAndCondition'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">About Us (Tentang Kami)</label>
																		<fieldset class="form-group mb-3">
																			<div id="AboutUs">
																				{!! count($company) > 0 ? $company[0]['AboutUs'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-4">
																		<label class="text-body fw-bold">Term & Condition Booking Online</label>
																		<fieldset class="form-group mb-3">
																			<div id="TermAndConditionBookingOnline">
																				{!! count($company) > 0 ? $company[0]['TermAndConditionBookingOnline'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																</div>
																
																<hr class="my-4">
																
																<!-- Meja yang bisa dipesan -->
																<h5 class="text-dark font-weight-bold mb-3">Daftar Meja Yang Dapat Dipesan Online</h5>
																<div class="row mb-4">
																	<div class="col-md-12">
																		<div class="table-responsive border rounded shadow-xs bg-light">
																			<table id="bookingTable" class="table table-bordered table-striped m-0">
																				<thead class="bg-primary text-white">
																					<tr>
																						<th style='display:none;'>No</th>
																						<th>Meja</th>
																						<th>Bisa Di Booking ?</th>
																					</tr>
																				</thead>
																				<tbody id="tableBookingList">
																					<!-- Data will load dynamically from database -->
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
																<hr class="my-4">
																
																<!-- Image Gallery 1-12 -->
																<h5 class="text-dark font-weight-bold mb-4">Galeri Foto Website (Maks. 12 Foto)</h5>
																<div class="row">
																	@for ($g = 1; $g <= 12; $g++)
																	<div class="col-sm-3 mb-4">
																		<fieldset class="form-group mb-0">
																			<textarea id="ImageGallery{{$g}}Base64" name="ImageGallery{{$g}}Base64" style="display: none;">{{ count($company) > 0 ? $company[0]['ImageGallery'.$g] : '' }}</textarea>
																			<input type="file" id="fileImageGallery{{$g}}" name="fileImageGallery{{$g}}" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
																			<div class="xContainer border rounded p-1 text-center bg-light">
																				<div id="ImageGallery{{$g}}" class="image_result_sample" style="height: 110px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
																					@if (!empty($company[0]['ImageGallery'.$g]))
																						<img src="{{$company[0]['ImageGallery'.$g]}}" style="max-height: 100%; max-width: 100%;">
																					@else
																						<img src="https://png.pngtree.com/png-vector/20221125/ourmid/pngtree-no-image-available-icon-flatvector-illustration-blank-avatar-modern-vector-png-image_40962406.jpg" style="max-height: 100%; max-width: 100%;">
																					@endif
																				</div>
																				<span class="text-muted d-block mt-1 font-size-xs">Galeri #{{$g}}</span>
																			</div>
																		</fieldset>
																	</div>
																	@endfor
																</div>
																
																<hr class="my-4">
																
																<!-- Tautan Cepat -->
																<h5 class="text-dark font-weight-bold mb-3">Tautan Cepat Website & Booking</h5>
																<div class="row">
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Link URL Halaman Website Utama</label>
																		<div class="d-flex align-items-center">
																			@if ($company[0]['JenisUsaha'] == "Retail")
																			<a href="{{ url('cat/').'/'.$company[0]['KodePartner'].'#home-basic' }}" target="_blank" class="btn btn-primary font-weight-bold me-3">
																				<i class="flaticon-link me-1"></i> Buka Website
																			</a>
																			@else
																			<a href="{{ url('fnb-store/').'/'.$company[0]['KodePartner'] }}" target="_blank" class="btn btn-primary font-weight-bold me-3">
																				<i class="flaticon-link me-1"></i> Buka Website
																			</a>
																			@endif
																			
																			<a href="{{ $BookingURLString ?? '#' }}" target="_blank" class="btn btn-outline-primary font-weight-bold">
																				<i class="flaticon-link me-1"></i> Buka Link Booking
																			</a>
																		</div>
																	</div>
																</div>
																
															</div>
														</div>
														
													</div>
												</div>
												@endif

												@if (in_array($company[0]['JenisUsaha'], ["Retail", "Bengkel", "Servis"]))
												<div class="tab-pane fade" id="website-bengkel" role="tabpanel" aria-labelledby="website-bengkel-tab">
													<div class="row">
														<!-- CARD 4: WEBSITE BENGKEL -->
														<div class="card card-custom gutter-b mb-6 border border-light-info bg-white shadow-sm rounded">
															<div class="card-header h-auto py-3 bg-light-info border-0 rounded-top">
																<div class="card-title m-0">
																	<h3 class="card-label text-info font-weight-bolder m-0" style="font-size: 1.25rem;">
																		<i class="flaticon-internet me-2 text-info"></i> Website Booking Bengkel
																	</h3>
																</div>
															</div>
															<div class="card-body py-4">
																
																<!-- Accordion Banners Bengkel -->
																<h5 class="text-dark font-weight-bold mb-4 mt-2">Banners & Promosi Utama Website Bengkel</h5>
																<div class="accordion mb-4" id="accordionBannersBengkel">
																	@for ($b = 1; $b <= 3; $b++)
																	<div class="accordion-item border mb-2 rounded">
																		<h2 class="accordion-header" id="headingBannerBengkel{{$b}}">
																			<button class="accordion-button {{ $b > 1 ? 'collapsed' : '' }} py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBannerBengkel{{$b}}" aria-expanded="{{ $b == 1 ? 'true' : 'false' }}" aria-controls="collapseBannerBengkel{{$b}}">
																				Banner Promosi #{{$b}}
																			</button>
																		</h2>
																		<div id="collapseBannerBengkel{{$b}}" class="accordion-collapse collapse {{ $b == 1 ? 'show' : '' }}" aria-labelledby="headingBannerBengkel{{$b}}" data-bs-parent="#accordionBannersBengkel">
																			<div class="accordion-body">
																				<div class="row">
																					<div class="col-sm-12 mb-3">
																						<label class="text-body fw-bold">Banner Header</label>
																						<fieldset class="form-group mb-2">
																							<div id="BannerHeader{{$b}}">
																								{!! count($company) > 0 ? $company[0]['BannerHeader'.$b] : '' !!}
																							</div>
																						</fieldset>
																					</div>
																					<div class="col-sm-12 mb-3">
																						<label class="text-body fw-bold">Banner Text (Deskripsi)</label>
																						<fieldset class="form-group mb-2">
																							<div id="BannerText{{$b}}">
																								{!! count($company) > 0 ? $company[0]['BannerText'.$b] : '' !!}
																							</div>
																						</fieldset>
																					</div>
																					<div class="col-sm-12 mb-3">
																						<label class="text-body fw-bold">Gambar Banner</label>
																						<fieldset class="form-group mb-2">
																							<textarea id="Banner{{$b}}Base64" name="Banner{{$b}}Base64" style="display: none;">{{ count($company) > 0 ? $company[0]['Banner'.$b] : '' }}</textarea>
																							<input type="file" id="fileBanner{{$b}}" name="fileBanner{{$b}}" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
																							<div class="xContainer border rounded p-1 text-center bg-light">
																								<div id="Banner{{$b}}_Preview" class="image_result_sample" style="max-height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;" onclick="document.getElementById('fileBanner{{$b}}').click();">
																									@if (count($company) > 0 && !empty($company[0]['Banner'.$b]))
																										<img src="{{$company[0]['Banner'.$b]}}" style="max-height: 100%; max-width: 100%;">
																									@else
																										<img src="https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg" style="max-height: 100%; max-width: 100%;">
																									@endif
																								</div>
																							</div>
																						</fieldset>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	@endfor
																</div>
																
																<hr class="my-4">
																
																<!-- Booking Online Info -->
																<h5 class="text-dark font-weight-bold mb-4">Pengaturan Landing Page & Booking Bengkel</h5>
																<div class="row">
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Warna Tema Website Bengkel</label>
																		<fieldset class="form-group mb-3">
																			<input type="color" class="form-control" id="DefaultLandingPageColor" name="DefaultLandingPageColor" value="{{ count($company) > 0 ? $company[0]['DefaultLandingPageColor'] : '' }}" >
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-4">
																		<label class="text-body fw-bold">Upload Image Banner Hero Bengkel</label>
																		<fieldset class="form-group mb-3">
																			<textarea id="BannerBookingBase64" name="BannerBookingBase64" style="display: none;">{{ count($company) > 0 ? $company[0]['BannerBooking'] : '' }}</textarea>
																			<input type="file" id="fileBannerBooking" name="fileBannerBooking" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
																			<div class="xContainer border rounded p-1 text-center bg-light">
																				<div id="BannerBooking_Preview" class="image_result_sample" style="max-height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center;" onclick="document.getElementById('fileBannerBooking').click();">
																					@if (count($company) > 0 && !empty($company[0]['BannerBooking']))
																						<img src="{{$company[0]['BannerBooking']}}" style="max-height: 100%; max-width: 100%;">
																					@else
																						<img src="https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg" style="max-height: 100%; max-width: 100%;">
																					@endif
																				</div>
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">Headline Banner (Judul Utama)</label>
																		<fieldset class="form-group mb-3">
																			<div id="HeadlineBanner">
																				{!! count($company) > 0 ? $company[0]['HeadlineBanner'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-4">
																		<label class="text-body fw-bold">Sub Headline Banner (Teks Bawah Judul)</label>
																		<fieldset class="form-group mb-3">
																			<div id="SubHeadlineBanner">
																				{!! count($company) > 0 ? $company[0]['SubHeadlineBanner'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																	
																</div>
																
																<hr class="my-4">
																
																<!-- Syarat & Ketentuan -->
																<h5 class="text-dark font-weight-bold mb-4">Deskripsi Bengkel & Informasi Tambahan</h5>
																<div class="row">
																	
																	<div class="col-md-12 mb-3">
																		<label class="text-body fw-bold">About Us (Tentang Bengkel Kami)</label>
																		<fieldset class="form-group mb-3">
																			<div id="AboutUs">
																				{!! count($company) > 0 ? $company[0]['AboutUs'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="col-md-12 mb-4">
																		<label class="text-body fw-bold">Syarat & Ketentuan Booking Servis</label>
																		<fieldset class="form-group mb-3">
																			<div id="TermAndConditionBookingOnline">
																				{!! count($company) > 0 ? $company[0]['TermAndConditionBookingOnline'] : '' !!}
																			</div>
																		</fieldset>
																	</div>
																</div>
																
																<hr class="my-4">
																
																<!-- Tautan Cepat -->
																<h5 class="text-dark font-weight-bold mb-3">Tautan Cepat Website Booking Bengkel</h5>
																<div class="row">
																	<div class="col-md-12 mb-3">
																		<div class="d-flex align-items-center">
																			<a href="{{ count($company) > 0 ? url('booking-bengkel/'.$company[0]['KodePartner']) : '#' }}" target="_blank" class="btn btn-outline-primary font-weight-bold">
																				<i class="flaticon-link me-1"></i> Buka Website Booking Bengkel
																			</a>
																		</div>
																	</div>
																</div>
																
															</div>
														</div>
														
													</div>
												</div>
												@endif



												<div class="tab-pane fade " id="hiburan" role="tabpanel" aria-labelledby="hiburan-tab">
													<div class="form-group row">
														<div class="col-md-12">
					                            			<label  class="text-body">Pajak Hiburan (%)</label>
					                            			<fieldset class="form-group mb-4">
					                            				<input type="number" class="form-control" step="0.01" id="PajakHiburan" name="PajakHiburan" placeholder="Masukan Pajak Hiburan" value="{{ count($company) > 0 ? $company[0]['PajakHiburan'] : "0" }}" >
					                            			</fieldset>
					                            			
					                            		</div>

														<div class="col-md-12">
					                            			<label  class="text-body">Warning Waktu Hampir Habis</label>
					                            			<fieldset class="form-group mb-4">
					                            				<input type="number" class="form-control" step="1" id="WarningTimer" name="WarningTimer" placeholder="Masukan Warning Hampir Habis" value="{{ count($company) > 0 ? $company[0]['WarningTimer'] : "0" }}" >
					                            			</fieldset>
					                            			
					                            		</div>

														<div class="col-md-12">
					                            			<label  class="text-body">Item Jasa Sewa</label>
					                            			<fieldset class="form-group mb-4">
					                            				<select name="ItemHiburan" id="ItemHiburan" class="js-example-basic-single js-states form-control bg-transparent">
					                            					<option value="" {{ count($company) > 0 ? $company[0]['ItemHiburan'] == ""? "selected" : '' :""}} >Pilih Item</option>
																	@foreach($itemjasa as $js)
																		<option value="{{ $js->KodeItem }}" {{ $js->KodeItem == (count($company) > 0 ? $company[0]['ItemHiburan'] : '') ? 'selected' : '' }}>
								                                            {{ $js->NamaItem }}
								                                        </option>
																	@endforeach
					                            				</select>

																<small>Setting ini digunakan untuk generate faktur penjualan</small>
					                            			</fieldset>
					                            			
					                            		</div>

														<div class="col-md-12">
															<label class="text-body">Queue System Link</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" id="BookingURL" name="BookingURL"
																	value="{{ $QueueURLString }}" readonly>
															</fieldset>
														</div>



														<div class="col-md-12">
															<label class="text-body">Running Text Self Services</label>
															<fieldset class="form-group mb-3">
																<input type="text" class="form-control" id="RunningTextSelfServices" name="RunningTextSelfServices" value="{{ count($company) > 0 ? $company[0]['RunningTextSelfServices'] : "" }}">
															</fieldset>
														</div>

														<div class="col-md-4">
															<label  class="text-body">Queue Design Setting</label>
															<fieldset class="form-group mb-3">
																<select name="QueueDesignSetting" id="QueueDesignSetting" class="js-example-basic-single js-states form-control bg-transparent">
																	<option value="QueueManagement" {{ count($company) > 0 ? $company[0]['QueueDesignSetting'] == "QueueManagement"? "selected" : '' :""}} >Versi 1</option>
																	<option value="QueueManagement_v2" {{ count($company) > 0 ? $company[0]['QueueDesignSetting'] == "QueueManagement_v2"? "selected" : '' :""}} >Versi 2</option>
																	<option value="QueueManagement_v3" {{ count($company) > 0 ? $company[0]['QueueDesignSetting'] == "QueueManagement_v3"? "selected" : '' :""}} >Versi 3</option>
																</select>
															</fieldset>
														</div>

														<div class="col-md-8">
															<label  class="text-body">Preview</label>
															<fieldset class="form-group mb-3">
																<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" id="PreviewQueueDesign" width="100%">
															</fieldset>
														</div>

														<div class="col-md-12">
															<div class="form-group mb-3">
																<label class="checkbox checkbox-outline checkbox-success">
																	<input type="checkbox" id="showBayarDiMeja" name="showBayarDiMeja" {{ (count($company) > 0 && substr($company[0]['ShowMetodePembayaran'] ?? '00', 0, 1) == '1') ? 'checked' : '' }}>
																	<span></span>Tampilkan Metode Pembayaran <i><b>Bayar dikasir</b></i> di pemesanan meja
																</label>
															</div>
															<div class="form-group mb-3">
																<label class="checkbox checkbox-outline checkbox-success">
																	<input type="checkbox" id="showLangsungBayar" name="showLangsungBayar" {{ (count($company) > 0 && substr($company[0]['ShowMetodePembayaran'] ?? '00', 1, 1) == '1') ? 'checked' : '' }}>
																	<span></span>Tampilkan Metode Pembayaran <i><b>Langsung Bayar</b></i> di pemesanan meja
																</label>
															</div>
														</div>

													</div>
												</div>


											</div>
										</div>
									</div>
</div>
									<div class="form-group row mt-4">
	                            		<div class="col-md-12">
	                            			<button type="submit" class="btn btn-success text-white font-weight-bold me-1 mb-1"><i class="fas fa-save"></i> Simpan Pengaturan</button>
	                            		</div>

									</div>
								</form>
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

{{-- @if (env('MIDTRANS_IS_PRODUCTION') == 'false')
<script src="{{ env('MIDTRANS_DEV_URL') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script src="{{ env('MIDTRANS_PROD_URL') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif --}}
<script src="{{ env('MIDTRANS_DEV_URL') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
var _URL = window.URL || window.webkitURL;
var _URLePub = window.URL || window.webkitURL;
var oCompany;
	$(function () {
		let quill_BannerHeader1, quill_BannerHeader2, quill_BannerHeader3;
		let quill_BannerText1, quill_BannerText2, quill_BannerText3;
		let quill_PromoDsiplay, quill_HeadlineBanner, quill_SubHeadlineBanner;
		let quill_TermAndCondition, quill_AboutUs, quill_TermAndConditionBookingOnline;

		if(document.getElementById('BannerHeader1')) quill_BannerHeader1 = new Quill('#BannerHeader1', { theme: 'snow' });
		if(document.getElementById('BannerHeader2')) quill_BannerHeader2 = new Quill('#BannerHeader2', { theme: 'snow' });
		if(document.getElementById('BannerHeader3')) quill_BannerHeader3 = new Quill('#BannerHeader3', { theme: 'snow' });

		if(document.getElementById('BannerText1')) quill_BannerText1 = new Quill('#BannerText1', { theme: 'snow' });
		if(document.getElementById('BannerText2')) quill_BannerText2 = new Quill('#BannerText2', { theme: 'snow' });
		if(document.getElementById('BannerText3')) quill_BannerText3 = new Quill('#BannerText3', { theme: 'snow' });

		if(document.getElementById('PromoDsiplay')) quill_PromoDsiplay = new Quill('#PromoDsiplay', { theme: 'snow' });

		if(document.getElementById('HeadlineBanner')) quill_HeadlineBanner = new Quill('#HeadlineBanner', { theme: 'snow' });
		if(document.getElementById('SubHeadlineBanner')) quill_SubHeadlineBanner = new Quill('#SubHeadlineBanner', { theme: 'snow' });

		if(document.getElementById('TermAndCondition')) quill_TermAndCondition = new Quill('#TermAndCondition', { theme: 'snow' });
		if(document.getElementById('AboutUs')) quill_AboutUs = new Quill('#AboutUs', { theme: 'snow' });

		if(document.getElementById('TermAndConditionBookingOnline')) quill_TermAndConditionBookingOnline = new Quill('#TermAndConditionBookingOnline', {
			theme:'snow'
		});
		jQuery(document).ready(function () {

			// 
			flatpickr("#JamAwalBooking", {
				enableTime: true,
				noCalendar: true,
				dateFormat: "H:i",  // 24-hour format: H = hour (00-23), i = minutes
				time_24hr: true
			});

			flatpickr("#JamAkhirBooking", {
				enableTime: true,
				noCalendar: true,
				dateFormat: "H:i",  // 24-hour format: H = hour (00-23), i = minutes
				time_24hr: true
			});
			var now = new Date();
			var day = ("0" + now.getDate()).slice(-2);
			var month = ("0" + (now.getMonth() + 1)).slice(-2);
			var firstDay = now.getFullYear()+"-"+month+"-01";
			var NowDay = now.getFullYear()+"-"+month+"-"+day;

			jQuery('#TglAwal').val(firstDay);
			jQuery('#TglAkhir').val(NowDay);

			var slip = "{{ count($company) > 0 ? $company[0]['DefaultSlip'] : 'slip1' }}";
			var template = "{{ count($company) > 0 ? $company[0]['DefaultLandingPages'] : 'bo1' }}";
			jQuery('#LevelHarga').select2();
			jQuery('#DefaultSlip').val(slip).trigger('change');
			jQuery('#DefaultLandingPages').val(template).trigger('change');

			var queueDesign = "{{ count($company) > 0 ? $company[0]['QueueDesignSetting'] : 'QueueManagement' }}";
			jQuery('#QueueDesignSetting').val(queueDesign).trigger('change');

			jQuery('#QueueDesignSetting').change(function() {
				var design = jQuery(this).val();
				// Replace with actual image paths when available
				var imageUrl = ""; 
				if(design === "QueueManagement") imageUrl = "https://pos.dstechsmart.com/images/Screenshot%202026-01-01%20225023.png"; 
				else if(design === "QueueManagement_v2") imageUrl = "https://pos.dstechsmart.com/images/Screenshot%202026-01-01%20225004.png";
				else if(design === "QueueManagement_v3") imageUrl = "https://pos.dstechsmart.com/images/Screenshot%202026-01-01%20224854.png";
				
				jQuery('#PreviewQueueDesign').attr('src', imageUrl);
			});
			jQuery('#QueueDesignSetting').trigger('change');

			oCompany = <?php echo $company ?>;
			console.log(oCompany)
			jQuery('#isPostingAkutansi').val(oCompany[0]['AllowAccounting']);

			if (oCompany[0]['AllowKatalogOnline'] == 0) {
				jQuery('#ecatalog-tab').hide();
			}

			// Generate Table
			// invoiceTable
			jQuery('#invoiceTable').DataTable({
				"ajax": {
					"url": "{{route('invpengguna-viewpercom')}}",
					"type": "POST",
					"contentType": "application/json",
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					},
					"data": function(d) {
						d.TglAwal = jQuery('#TglAwal').val();
						d.TglAkhir = jQuery('#TglAkhir').val();
						d.Status = "";
						return JSON.stringify(d);
					},
					"dataSrc": ""
				},
				"columns": [
					{ "data": "NoTransaksi" },
					{ 
						"data": "TglTransaksi",
						"render": function(data, type, row) {
							if (!data) return '';
							let date = new Date(data);
							let day = String(date.getDate()).padStart(2, '0');
							let month = String(date.getMonth() + 1).padStart(2, '0');
							let year = date.getFullYear();
							return `${day}-${month}-${year}`;
						}
					},
					{ "data": "NamaSubscription" },
					{ 
						"data": "TotalTagihan",
						"render": function(data, type, row) {
							return parseFloat(data).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
						}
					},
					{ 
						"data": "TglJatuhTempo",
						"render": function(data, type, row) {
							if (!data) return '';
							let date = new Date(data);
							let day = String(date.getDate()).padStart(2, '0');
							let month = String(date.getMonth() + 1).padStart(2, '0');
							let year = date.getFullYear();
							return `${day}-${month}-${year}`;
						}
					},
					{ "data": "StatusPembayaran" },
					{
						"data": null,
						"orderable": false,
						"render": function(data, type, row) {
							let disabled = row.StatusPembayaran == "LUNAS" ? "disabled" : "";
							return `<button type="button" class="btn btn-warning btn-bayar" data-id="${row.NoTransaksi}" data-TotalBayar="${row.TotalTagihan}" ${disabled}>Bayar</button>`;
						}
					}
				]
			});

			// var initialType = jQuery('#TypeBackgraund').val();
			updateBackgroundInput(oCompany[0]['TypeBackgraund']);
			updateKitchenBackgroundInput(oCompany[0]['TypeKitchenBackgraund']);

		});

		jQuery('form').submit(function(e) {

			e.preventDefault(); // Prevent default form submission

			var form = $(this);
			var formData = form.serializeArray();
			var actionUrl = form.attr('action');
			var submitButton = form.find("button[type='submit']");
			submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

			var BannerHeader1 = quill_BannerHeader1 ? quill_BannerHeader1.root.innerHTML : '';
			var BannerHeader2 = quill_BannerHeader2 ? quill_BannerHeader2.root.innerHTML : '';
			var BannerHeader3 = quill_BannerHeader3 ? quill_BannerHeader3.root.innerHTML : '';
			var BannerText1 = quill_BannerText1 ? quill_BannerText1.root.innerHTML : '';
			var BannerText2 = quill_BannerText2 ? quill_BannerText2.root.innerHTML : '';
			var BannerText3 = quill_BannerText3 ? quill_BannerText3.root.innerHTML : '';
			var PromoDsiplay = quill_PromoDsiplay ? quill_PromoDsiplay.root.innerHTML : '';
			var HeadlineBanner = quill_HeadlineBanner ? quill_HeadlineBanner.root.innerHTML : '';
			var SubHeadlineBanner = quill_SubHeadlineBanner ? quill_SubHeadlineBanner.root.innerHTML : '';
			var TermAndCondition = quill_TermAndCondition ? quill_TermAndCondition.root.innerHTML : '';
			var AboutUs = quill_AboutUs ? quill_AboutUs.root.innerHTML : '';
			var TermAndConditionBookingOnline = quill_TermAndConditionBookingOnline ? quill_TermAndConditionBookingOnline.root.innerHTML : '';
			

			formData.push({ name: "BannerHeader1", value: BannerHeader1 });
			formData.push({ name: "BannerHeader2", value: BannerHeader2 });
			formData.push({ name: "BannerHeader3", value: BannerHeader3 });
			formData.push({ name: "BannerText1", value: BannerText1 });
			formData.push({ name: "BannerText2", value: BannerText2 });
			formData.push({ name: "BannerText3", value: BannerText3 });
			formData.push({ name: "PromoDsiplay", value: PromoDsiplay });
			formData.push({ name: "HeadlineBanner", value: HeadlineBanner });
			formData.push({ name: "SubHeadlineBanner", value: SubHeadlineBanner });
			formData.push({ name: "TermAndCondition", value: TermAndCondition });
			formData.push({ name: "AboutUs", value: AboutUs });
			formData.push({ name: "TermAndConditionBookingOnline", value:TermAndConditionBookingOnline});

			$.ajax({
				url: actionUrl,
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(response) {
					if(response.success == true){
						swal.fire({
							title: 'Success',
							text: response.message,
							icon: 'success',
							confirmButtonText: 'OK'
						}).then(function() {
							window.location.href = "{{ route('companysetting') }}";
						});
					} else {
						swal.fire({
							title: 'Error',
							text: response.message,
							icon: 'error',
							confirmButtonText: 'OK'
						}).then(function() {
							submitButton.prop('disabled', false).html('Save');
						});
					}
				},
			});
		});

		jQuery('#invoiceTable').on('click', '.btn-bayar', function() {
			var NoTransaksi = jQuery(this).data('id');
			var TotalPembayaran = jQuery(this).data('totalbayar');
			// console.log(TotalPembayaran)
			PaymentGateWay(jQuery(".btn-bayar"), "Bayar", NoTransaksi, TotalPembayaran)
		});
		jQuery('#isPostingAkutansi').on('mousedown', function(event) {
			if (jQuery(this).attr('readonly')) {
				event.preventDefault();
			}
		});

		// Function to update the background input field
		function updateBackgroundInput(type) {
			var backgroundInput = jQuery('#backgroundInput');
			backgroundInput.empty(); // Clear current input

			if (type === 'Image') {
				var imageInputHTML = `
					<fieldset class="form-group mb-3">
						<textarea id="BackgraundBase64" name="BackgraundBase64" style="display: none;">{{ count($company) > 0 ? $company[0]['Backgraund'] : '' }}</textarea>
						<input type="file" id="fileBackgraund" name="fileBackgraund" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
						<div class="xContainer">
							<div id="BackgraundPreview" class="image_result_sample">
								@if (count($company) > 0 && $company[0]['Backgraund'] != '')
									<img src="{{$company[0]['Backgraund']}}">
								@else
									<img src="https://png.pngtree.com/png-vector/20221125/ourmid/pngtree-no-image-available-icon-flatvector-illustration-blank-avatar-modern-vector-png-image_40962406.jpg">
								@endif
							</div>
						</div>
					</fieldset>
				`;
				backgroundInput.append(imageInputHTML);

				jQuery('#BackgraundPreview').click(function(){
					$('#fileBackgraund').click();
				});

				jQuery("#fileBackgraund").change(function(){
					var file = $(this)[0].files[0];
					var img = new Image();
					img.src = _URL.createObjectURL(file);
					img.onload = function () {
						// You can add width/height validation if needed
					}
					readURL(this, "BackgraundPreview");
					encodeImagetoBase64(this, "BackgraundBase64");
				});

			} else { // Color
				var input = jQuery('<input>').attr({
					type: 'color',
					class: 'form-control',
					name: 'Backgraund',
					id: 'Backgraund'
				});

				console.log(oCompany);
				
				if (oCompany && oCompany.length > 0 && oCompany[0]['TypeBackgraund'] === 'Color') {
					input.val(oCompany[0]['Backgraund']);
				}
				else {
					input.val('#ffffff'); // default color
				}
				backgroundInput.append(input);
			}
		}

		// Event listener for when the selection changes
		jQuery('#TypeBackgraund').on('change', function(){
			updateBackgroundInput(jQuery(this).val());
		});

		function updateKitchenBackgroundInput(type) {
			var backgroundInput = jQuery('#kitchenBackgroundInput');
			backgroundInput.empty(); // Clear current input

			if (type === 'Image') {
				var imageInputHTML = `
					<fieldset class="form-group mb-3">
						<textarea id="KitchenBackgraundBase64" name="KitchenBackgraundBase64" style="display: none;">{{ count($company) > 0 ? $company[0]['KitchenBackgraund'] : '' }}</textarea>
						<input type="file" id="fileKitchenBackgraund" name="fileKitchenBackgraund" accept=".jpg, .png" class="btn btn-warning" style="display: none;"/>
						<div class="xContainer">
							<div id="KitchenBackgraundPreview" class="image_result_sample">
								@if (count($company) > 0 && $company[0]['KitchenBackgraund'] != '' && $company[0]['TypeKitchenBackgraund'] == 'Image')
									<img src="{{$company[0]['KitchenBackgraund']}}">
								@else
									<img src="https://png.pngtree.com/png-vector/20221125/ourmid/pngtree-no-image-available-icon-flatvector-illustration-blank-avatar-modern-vector-png-image_40962406.jpg">
								@endif
							</div>
						</div>
					</fieldset>
				`;
				backgroundInput.append(imageInputHTML);

				jQuery('#KitchenBackgraundPreview').click(function(){
					$('#fileKitchenBackgraund').click();
				});

				jQuery("#fileKitchenBackgraund").change(function(){
					var file = $(this)[0].files[0];
					var img = new Image();
					img.src = _URL.createObjectURL(file);
					img.onload = function () {
						// You can add width/height validation if needed
					}
					readURL(this, "KitchenBackgraundPreview");
					encodeImagetoBase64(this, "KitchenBackgraundBase64");
				});

			} else { // Color
				var input = jQuery('<input>').attr({
					type: 'color',
					class: 'form-control',
					name: 'KitchenBackgraund',
					id: 'KitchenBackgraund'
				});

				if (oCompany && oCompany.length > 0 && oCompany[0]['TypeKitchenBackgraund'] === 'Color') {
					input.val(oCompany[0]['KitchenBackgraund']);
				}
				else {
					input.val('#ffffff'); // default color
				}
				backgroundInput.append(input);
			}
		}

		jQuery('#TypeKitchenBackgraund').on('change', function(){
			updateKitchenBackgroundInput(jQuery(this).val());
		});

		jQuery('#btTestPrint').click(function () {
			// alert('asd')
			$.ajax({
	            async:false,
	            type: 'post',
	            url: "{{route('print-test')}}",
	            headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
	            data: {
	                'NoTransaksi' : 'asdasda'
	            },
	            dataType: 'json',
	            success: function(response) {
	                // bindGridDetail(response.data)
	                console.log('response');
	            }
	        })
		});

		jQuery('#testPrintUSB').click(function () {
			// window.print();
			let url = new URL("{{ route('print-testusb48') }}");
            // url.searchParams.append('Orientasi', jQuery('#Orientasi').val());
			var currentUrl  = window.location.href = url.toString();
		});

		jQuery('#DefaultSlip').change(function () {
			var BaseURL = "{{ asset('') }}";
			var url = BaseURL+"images/slip/"+jQuery('#DefaultSlip').val()+".png";
			console.log();
			// var URL = "{{ asset("+FileName+")}}";
			jQuery("#PreviewImageSlip").attr("src", url);
		});

		jQuery('#DefaultLandingPages').change(function () {
			var BaseURL = "{{ asset('') }}";
			var url = BaseURL+"images/bo/"+jQuery('#DefaultLandingPages').val()+".png";
			console.log();
			// var URL = "{{ asset("+FileName+")}}";
			jQuery("#PreviewImageTemplate").attr("src", url);
		});


		jQuery('#image_result').click(function(){
			$('#Attachment').click();
		});

		jQuery('#BannerBooking').click(function(){
			$('#fileBannerBooking').click();
		});

		jQuery('#Banner1').click(function(){
			$('#fileBanner1').click();
		});

		jQuery('#Banner2').click(function(){
			$('#fileBanner2').click();
		});

		jQuery('#Banner3').click(function(){
			$('#fileBanner3').click();
		});

		jQuery('#ImageCustDisplay1').click(function(){
			$('#fileImageCustDisplay1').click();
		});
		jQuery('#ImageCustDisplay2').click(function(){
			$('#fileImageCustDisplay2').click();
		});
		jQuery('#ImageCustDisplay3').click(function(){
			$('#fileImageCustDisplay3').click();
		});
		jQuery('#ImageCustDisplay4').click(function(){
			$('#fileImageCustDisplay4').click();
		});

		jQuery('#ImageCustDisplay5').click(function(){
			$('#fileImageCustDisplay5').click();
		});

		jQuery('#ImageGallery1').click(function(){
			$('#fileImageGallery1').click();
		});
		jQuery('#ImageGallery2').click(function(){
			$('#fileImageGallery2').click();
		});
		jQuery('#ImageGallery3').click(function(){
			$('#fileImageGallery3').click();
		});
		jQuery('#ImageGallery4').click(function(){
			$('#fileImageGallery4').click();
		});

		jQuery('#ImageGallery5').click(function(){
			$('#fileImageGallery5').click();
		});
		jQuery('#ImageGallery6').click(function(){
			$('#fileImageGallery6').click();
		});
		jQuery('#ImageGallery7').click(function(){
			$('#fileImageGallery7').click();
		});
		jQuery('#ImageGallery8').click(function(){
			$('#fileImageGallery8').click();
		});
		jQuery('#ImageGallery9').click(function(){
			$('#fileImageGallery9').click();
		});
		jQuery('#ImageGallery10').click(function(){
			$('#fileImageGallery10').click();
		});
		jQuery('#ImageGallery11').click(function(){
			$('#fileImageGallery11').click();
		});
		jQuery('#ImageGallery12').click(function(){
			$('#fileImageGallery12').click();
		});

		jQuery("#fileBannerBooking").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "BannerBooking");
			encodeImagetoBase64(this, "BannerBookingBase64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileBanner1").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "Banner1");
			encodeImagetoBase64(this, "Banner1Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileBanner2").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "Banner2");
			encodeImagetoBase64(this, "Banner2Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileBanner3").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "Banner3");
			encodeImagetoBase64(this, "Banner3Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageCustDisplay1").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageCustDisplay1");
			encodeImagetoBase64(this, "ImageCustDisplay1Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageCustDisplay2").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageCustDisplay2");
			encodeImagetoBase64(this, "ImageCustDisplay2Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageCustDisplay3").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageCustDisplay3");
			encodeImagetoBase64(this, "ImageCustDisplay3Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageCustDisplay4").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageCustDisplay4");
			encodeImagetoBase64(this, "ImageCustDisplay4Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageCustDisplay5").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageCustDisplay5");
			encodeImagetoBase64(this, "ImageCustDisplay5Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery1").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery1");
			encodeImagetoBase64(this, "ImageGallery1Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery2").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery2");
			encodeImagetoBase64(this, "ImageGallery2Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery3").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery3");
			encodeImagetoBase64(this, "ImageGallery3Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery4").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery4");
			encodeImagetoBase64(this, "ImageGallery4Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});


		jQuery("#fileImageGallery5").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery5");
			encodeImagetoBase64(this, "ImageGallery5Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery6").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery6");
			encodeImagetoBase64(this, "ImageGallery6Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery7").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery7");
			encodeImagetoBase64(this, "ImageGallery7Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery8").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery8");
			encodeImagetoBase64(this, "ImageGallery8Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery9").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery9");
			encodeImagetoBase64(this, "ImageGallery9Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery10").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery10");
			encodeImagetoBase64(this, "ImageGallery10Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery11").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery11");
			encodeImagetoBase64(this, "ImageGallery11Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery("#fileImageGallery12").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this, "ImageGallery12");
			encodeImagetoBase64(this, "ImageGallery12Base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});



		$("#Attachment").change(function(){
			var file = $(this)[0].files[0];
			img = new Image();
			img.src = _URL.createObjectURL(file);
			var imgwidth = 0;
			var imgheight = 0;
			img.onload = function () {
				imgwidth = this.width;
				imgheight = this.height;
				$('#width').val(imgwidth);
				$('#height').val(imgheight);
			}
			readURL(this,"image_result");
			encodeImagetoBase64(this,"image_base64");
			// alert("Current width=" + imgwidth + ", " + "Original height=" + imgheight);
		});

		jQuery('#btInportItem').click(function () {
			var formData = new FormData();
			formData.append('BulkItemMaster', jQuery('#BulkItemMaster')[0].files[0]);

			$.ajax({
	            async:false,
	            type: 'post',
	            url: "{{route('companysetting-importitem')}}",
	            headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
	            data: formData,
	            processData: false,
                contentType: false,
	            success: function(response) {
	                // bindGridDetail(response.data)
	                // console.log(response);
					if (response.success) {
						Swal.fire({
							icon: "success",
							title: 'Horay',
							text: 'Data Berhasil Disimpan',
							// footer: '<a href>Why do I have this issue?</a>'
						}).then((result)=>{
							location.reload();
						});
					}
					else{
						Swal.fire({
							icon: "error",
							title: 'Error',
							text: response.message,
							// footer: '<a href>Why do I have this issue?</a>'
						});
					}
	            }
	        });
		});


		jQuery('#btInportHargaJual').click(function () {
			var formData = new FormData();
			formData.append('BulkHargaJual', jQuery('#BulkHargaJual')[0].files[0]);

			$.ajax({
	            async:false,
	            type: 'post',
	            url: "{{route('companysetting-importharga')}}",
	            headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
	            data: formData,
	            processData: false,
                contentType: false,
	            success: function(response) {
	                // bindGridDetail(response.data)
	                // console.log(response);
					if (response.success) {
						Swal.fire({
							icon: "success",
							title: 'Horay',
							text: 'Data Berhasil Disimpan',
							// footer: '<a href>Why do I have this issue?</a>'
						}).then((result)=>{
							location.reload();
						});
					}
					else{
						Swal.fire({
							icon: "error",
							title: 'Error',
							text: response.message,
							// footer: '<a href>Why do I have this issue?</a>'
						});
					}
	            }
	        });
		});

		jQuery('#btInportPelanggan').click(function () {
			var formData = new FormData();
			formData.append('BulkPelanggan', jQuery('#BulkPelanggan')[0].files[0]);

			$.ajax({
	            async:false,
	            type: 'post',
	            url: "{{route('companysetting-importpelanggan')}}",
	            headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
	            data: formData,
	            processData: false,
                contentType: false,
	            success: function(response) {
	                // bindGridDetail(response.data)
	                // console.log(response);
					if (response.success) {
						Swal.fire({
							icon: "success",
							title: 'Horay',
							text: 'Data Berhasil Disimpan',
							// footer: '<a href>Why do I have this issue?</a>'
						}).then((result)=>{
							location.reload();
						});
					}
					else{
						Swal.fire({
							icon: "error",
							title: 'Error',
							text: response.message,
							// footer: '<a href>Why do I have this issue?</a>'
						});
					}
	            }
	        });
		});

		jQuery('#btInportSupplier').click(function () {
			var formData = new FormData();
			formData.append('BulkSupplier', jQuery('#BulkSupplier')[0].files[0]);

			$.ajax({
	            async:false,
	            type: 'post',
	            url: "{{route('companysetting-importsupplier')}}",
	            headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
	            data: formData,
	            processData: false,
                contentType: false,
	            success: function(response) {
	                // bindGridDetail(response.data)
	                // console.log(response);
					if (response.success) {
						Swal.fire({
							icon: "success",
							title: 'Horay',
							text: 'Data Berhasil Disimpan',
							// footer: '<a href>Why do I have this issue?</a>'
						}).then((result)=>{
							location.reload();
						});
					}
					else{
						Swal.fire({
							icon: "error",
							title: 'Error',
							text: response.message,
							// footer: '<a href>Why do I have this issue?</a>'
						});
					}
	            }
	        });
		});

		function readURL(input, outputElement) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				
				reader.onload = function (e) {
				// console.log(e.target.result);
				$('#'+outputElement).html("<img src ='"+e.target.result+"'> ");
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		function encodeImagetoBase64(element, textOutput) {
			$('#'+textOutput).val('');
			var file = element.files[0];
			if (!file) return;

			var reader = new FileReader();
			reader.onload = function(event) {
				var img = new Image();
				img.onload = function() {
					// Set maximum dimensions for high-quality customer display slides
					var max_width = 1200;
					var max_height = 1200;
					var width = img.width;
					var height = img.height;

					// Calculate new dimensions while maintaining aspect ratio
					if (width > max_width || height > max_height) {
						if (width > height) {
							height = Math.round((height * max_width) / width);
							width = max_width;
						} else {
							width = Math.round((width * max_height) / height);
							height = max_height;
						}
					}

					// Create HTML5 Canvas to perform client-side downscaling and compression
					var canvas = document.createElement('canvas');
					canvas.width = width;
					canvas.height = height;
					var ctx = canvas.getContext('2d');
					
					// Draw image with smooth scaling
					ctx.drawImage(img, 0, 0, width, height);

					// Compress to high-efficiency JPEG (0.75 quality reduces size by ~90% with zero visible quality loss)
					var compressedBase64 = canvas.toDataURL('image/jpeg', 0.75);
					
					// Write compressed base64 directly to form field
					$('#'+textOutput).val(compressedBase64);
				};
				img.src = event.target.result;
			};
			reader.readAsDataURL(file);
		}

		function PaymentGateWay(ButtonObject, ButtonDefaultText, NoTransaksi, TotalPembelian) {
			ButtonObject.text('Tunggu Sebentar.....');
			ButtonObject.attr('disabled',true);

			var oData = {
				'NoTransaksi' : NoTransaksi,
				'TotalPembelian' : TotalPembelian,
			}

			fetch( "{{route('invpengguna-create-gateway')}}", {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
				body: JSON.stringify(oData)
			})
			.then(response => response.json())
			.then(data => {
                if (data.provider === 'xendit' && data.invoice_url) {
                    window.location.href = data.invoice_url;
                    return;
                }
				if (data.snap_token) {
					snap.pay(data.snap_token, {
						onSuccess: function(result){
							// console.log(result);
							if(result.transaction_status == "cancel"){
								ButtonObject.text('Bayar');
  								ButtonObject.attr('disabled',false);

								Swal.fire({
									icon: "error",
									title: "Opps...",
									text: "Pembayaran Dibatalkan",
								});
							}
							else{
								// order_id
								// $('#NomorRefrensiPembayaran').val(result.order_id)

								// Metode Pembayaran :

								var PaymentMethodgateWay = result.payment_type;
								if (result.va_numbers) {
									PaymentMethodgateWay += "_"+result.payment_type+"#"+result.va_numbers[0]["bank"]+"#"+result.va_numbers[0]["va_number"]
								}
								console.log(result);
								var xData = {
									"BaseReff" : NoTransaksi,
									"MetodePembayaran" : PaymentMethodgateWay,
									"NoReff" : result.order_id,
									"Keterangan" : result.transaction_id,
									"TotalBayar" : TotalPembelian
								}
								// SaveData(Status, ButonObject, ButtonDefaultText)
								$.ajax({
									async:false,
									type: 'post',
									url: "{{route('invpengguna-pay-gateway')}}",
									headers: {
										'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
									},
									data: JSON.stringify(xData),
									processData: false,
									contentType: false,
									success: function(response) {
										// bindGridDetail(response.data)
										// console.log(response);
										if (response.success) {
											Swal.fire({
												icon: "success",
												title: 'Horay',
												text: 'Data Berhasil Disimpan',
												// footer: '<a href>Why do I have this issue?</a>'
											}).then((result)=>{
												location.reload();
											});
										}
										else{
											ButtonObject.text('Bayar');
  											ButtonObject.attr('disabled',false);
											Swal.fire({
												icon: "error",
												title: 'Error',
												text: response.message,
												// footer: '<a href>Why do I have this issue?</a>'
											});
										}
									}
								});
							}
							// Proses pembayaran sukses
						},
						onPending: function(result){
							// console.log(result);
							ButtonObject.text('Bayar');
  							ButtonObject.attr('disabled',false);
							console.log('customer closed the popup without finishing the payment - Pending');
							// Pembayaran tertunda
						},
						onError: function(result){
							// console.log(result);
							ButtonObject.text('Bayar');
  							ButtonObject.attr('disabled',false);
							Swal.fire({
								icon: "error",
								title: "Opps...",
								text: result,
							})
							// Pembayaran gagal
						},
						onClose: function(){
							ButtonObject.text('Bayar');
  							ButtonObject.attr('disabled',false);
							console.log('customer closed the popup without finishing the payment');
						}
					});
				} else {
					ButtonObject.text('Bayar');
  					ButtonObject.attr('disabled',false);
					// alert('Error: ' + data.error);
					Swal.fire({
						icon: "error",
						title: "Opps...",
						text: data.error,
					})
				}
			})
			.catch(error => console.error('Error:', error));
			}
	})

	document.addEventListener("DOMContentLoaded", function () {
    let tableBody = document.getElementById("tableBookingList");
    if (!tableBody) {
        return;
    }
    console.log('Fetching meja list...');
    fetch('/get-meja')
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Meja data received:', data);
            tableBody.innerHTML = "";

            data.forEach((meja) => {
                let checked = meja.BisaDipesan == 1 ? "checked" : "";
                let row = `<tr>
                    <td style = 'display:none;'>${meja.id}</td>
                    <td>${meja.NamaTitikLampu}</td>
                    <td>
                        <input type="checkbox" class="meja-checkbox" data-id="${meja.id}" ${checked} />
                    </td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error fetching meja:', error));
});

document.addEventListener("change", function (event) {
    if (event.target.classList.contains('meja-checkbox')) {
        let mejaId = event.target.getAttribute('data-id');
        let bisaDipesan = event.target.checked ? 1 : 0;

        let xData = {
            "id": mejaId,
            "BisaDipesan": bisaDipesan,
        };

        $.ajax({
            type: 'POST',
            url: "/titiklampu/updateStatusMeja", 
            headers: {
										'X-CSRF-TOKEN': '{{ csrf_token() }}' 
									},
            data: JSON.stringify(xData),
            contentType: "application/json", 
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: 'Horay',
                        text: 'Data Meja Berhasil Diupdate',
                    }).then(() => {
                        //location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: 'Error',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memperbarui data.',
                });
            }
        });
    }
});


jQuery('#KioskBackgroundPreview').click(function(){
    $('#fileKioskBackground').click();
});

jQuery("#fileKioskBackground").change(function(){
    var file = $(this)[0].files[0];
    if(file){
        var img = new Image();
        img.src = _URL.createObjectURL(file);
        readURL(this, "KioskBackgroundPreview");
        encodeImagetoBase64(this, "KioskBackgroundBase64");
    }
});
    // ===== Gate API Key Functions (window scope agar bisa dipanggil dari onclick) =====
    window.generateGateApiKey = function() {
        Swal.fire({
            title: "Generate API Key Baru?",
            html: "<p>API Key lama akan diganti. Gate Agent yang sudah terinstall harus diupdate dengan key baru ini.</p>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Generate!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                const array = new Uint8Array(16);
                window.crypto.getRandomValues(array);
                const apiKey = Array.from(array).map(b => b.toString(16).padStart(2, "0")).join("").toUpperCase();
                document.getElementById("GateApiKeyInput").value = apiKey;
                Swal.fire({ icon: "success", title: "API Key Baru Dibuat!", html: "<p>Jangan lupa klik <strong>Simpan</strong> untuk menyimpan API Key ini.</p>", timer: 3000 });
            }
        });
    };

    window.copyGateApiKey = function() {
        const input = document.getElementById("GateApiKeyInput");
        const val = input.value;
        if (!val) {
            Swal.fire("Kosong!", "Belum ada API Key. Silakan Generate terlebih dahulu.", "warning");
            return;
        }
        navigator.clipboard.writeText(val).then(() => {
            Swal.fire({ icon: "success", title: "Disalin!", text: "API Key berhasil disalin ke clipboard.", timer: 2000, showConfirmButton: false });
        });
    };
    // ===== End Gate API Key Functions =====

</script>
@endpush
