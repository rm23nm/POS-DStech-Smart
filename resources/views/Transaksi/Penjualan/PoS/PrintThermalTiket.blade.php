<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tiket - {{ $header->NoTransaksi }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }
        .ticket {
            width: 58mm; /* atau sesuaikan dengan 80mm */
            margin: 0 auto;
            padding: 10px;
            text-align: center;
            page-break-after: always;
            box-sizing: border-box;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .company-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 5px;
        }
        .company-contact {
            font-size: 11px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            margin: 10px 0;
            padding: 5px 0;
        }
        .ticket-name {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .barcode-container {
            margin: 10px 0;
        }
        .barcode {
            width: 100%;
            height: auto;
        }
        .info {
            font-size: 10px;
            text-align: left;
            margin-top: 10px;
        }
        .footer {
            font-size: 10px;
            margin-top: 15px;
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        @media print {
            body {
                width: 100%;
            }
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    @if(count($tickets) == 0)
        <div class="ticket">
            @if(!empty($company->icon))
                <img src="{{ str_starts_with($company->icon, 'http') ? $company->icon : asset('storage/' . $company->icon) }}" alt="Logo" class="company-logo">
            @endif
            <div class="company-name">{{ $company->NamaPartner ?? 'Perusahaan' }}</div>
            <div class="company-contact">
                {{ $company->AlamatTagihan ?? '' }}<br>
                Telp: {{ $company->NoHP ?? $company->NoTlp ?? '-' }}
            </div>
            
            <div class="title">Struk Pembelian</div>
            
            <div class="info" style="margin-bottom: 10px;">
                <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
                    <tr>
                        <td>No:</td>
                        <td style="text-align: right;">{{ $header->NoTransaksi }}</td>
                    </tr>
                    <tr>
                        <td>Tgl:</td>
                        <td style="text-align: right;">{{ \Carbon\Carbon::parse($header->TglTransaksi)->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Oleh:</td>
                        <td style="text-align: right;">{{ Auth::user()->name }}</td>
                    </tr>
                    @if($header->KodePelanggan && $header->KodePelanggan != 'CASH')
                    <tr>
                        <td>Pelanggan:</td>
                        <td style="text-align: right;">{{ $header->NamaPelanggan ?? $header->KodePelanggan }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <div style="border-top: 1px dashed #000; margin-bottom: 5px;"></div>

            <table style="width: 100%; font-size: 11px; text-align: left; border-collapse: collapse;">
                @foreach($details as $detail)
                <tr>
                    <td colspan="3">{{ $detail->NamaItem }}</td>
                </tr>
                <tr>
                    <td>{{ $detail->Qty }}x</td>
                    <td style="text-align: right;">{{ number_format($detail->Harga ?? 0) }}</td>
                    <td style="text-align: right;">{{ number_format(($detail->Qty ?? 0) * ($detail->Harga ?? 0)) }}</td>
                </tr>
                @endforeach
            </table>

            <div style="border-top: 1px dashed #000; margin-top: 5px; margin-bottom: 5px;"></div>

            <table style="width: 100%; font-size: 11px; font-weight: bold; text-align: left; border-collapse: collapse;">
                <tr>
                    <td>Subtotal:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->TotalTransaksi ?? 0) }}</td>
                </tr>
                @if(isset($header->Potongan) && $header->Potongan > 0)
                <tr>
                    <td>Diskon:</td>
                    <td style="text-align: right;">- Rp {{ number_format($header->Potongan ?? 0) }}</td>
                </tr>
                @endif
                @if(isset($header->Pajak) && $header->Pajak > 0)
                <tr>
                    <td>Pajak:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->Pajak ?? 0) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding-top: 5px; font-size: 12px;">Total:</td>
                    <td style="text-align: right; padding-top: 5px; font-size: 12px;">Rp {{ number_format($header->TotalPembelian ?? 0) }}</td>
                </tr>
                <tr>
                    <td>Bayar:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->TotalPembayaran ?? 0) }}</td>
                </tr>
                <tr>
                    <td>Kembali:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->Kembalian ?? 0) }}</td>
                </tr>
            </table>

            <div class="footer">
                Terima kasih atas pembelian Anda!<br>
                Harap simpan struk ini sebagai bukti pembayaran.
            </div>
        </div>
    @else
        @php $ticketNumber = 1; @endphp
        @foreach($details as $detail)
            @php
                // Get tickets specifically for this KodeItem
                $detailTickets = collect($tickets)->where('KodeItem', $detail->KodeItem)->values();
            @endphp
            @if($detailTickets->count() > 0)
                @foreach($detailTickets as $ticket)
                <div class="ticket">
                    @if(!empty($company->icon))
                        <img src="{{ str_starts_with($company->icon, 'http') ? $company->icon : asset('storage/' . $company->icon) }}" alt="Logo" class="company-logo">
                    @endif
                    <div class="company-name">{{ $company->NamaPartner ?? 'Perusahaan' }}</div>
                    <div class="company-contact">
                        {{ $company->AlamatTagihan ?? '' }}<br>
                        Telp: {{ $company->NoHP ?? $company->NoTlp ?? '-' }}
                    </div>
                    
                    <div class="title">TIKET MASUK</div>
                    
                    <div class="ticket-name">{{ $detail->NamaItem }}</div>

                    <div class="barcode-container">
                        <svg class="barcode-svg" jsbarcode-value="{{ $ticket->BarcodeTiket }}" jsbarcode-displayvalue="true" jsbarcode-height="40" jsbarcode-width="1.5" jsbarcode-margin="0" style="width: 100%; height: auto;"></svg>
                    </div>

                    <div class="info">
                        No. Trx : {{ $header->NoTransaksi }}-{{ $ticketNumber }}<br>
                        Tgl     : {{ date('d-m-Y H:i', strtotime($header->TglTransaksi)) }}<br>
                        Harga   : Rp {{ number_format($detail->Harga ?? 0, 0, ',', '.') }}
                    </div>

                    <div class="footer">
                        Terima kasih atas kunjungan Anda.<br>
                        Tiket hanya berlaku 1 kali scan.
                    </div>
                </div>
                @php $ticketNumber++; @endphp
                @endforeach
            @endif
        @endforeach
    @endif

    <script>
        // Inisialisasi semua elemen yang memiliki class barcode-svg
        JsBarcode(".barcode-svg").init();

        // Otomatis trigger dialog print saat halaman dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // Beri jeda 0.5 detik agar barcode selesai di-render
        }
    </script>

<script>
    var _globalBarcodeScannerBuffer = "";
    var _globalBarcodeScannerTimer = null;
    
    $(document).on("keypress", function(e) {
        if (e.target.id === "_Barcode") return; // Ignore if already focused on barcode
        
        if (e.key && e.key.length === 1 && !e.ctrlKey && !e.altKey) {
            _globalBarcodeScannerBuffer += e.key;
            
            if (_globalBarcodeScannerTimer) clearTimeout(_globalBarcodeScannerTimer);
            
            _globalBarcodeScannerTimer = setTimeout(function() {
                _globalBarcodeScannerBuffer = "";
            }, 60); // Scanner types very fast
            
        } else if (e.key === "Enter" || e.keyCode === 13) {
            if (_globalBarcodeScannerBuffer.length >= 3) {
                // It's a scanner!
                e.preventDefault();
                $('#_Barcode').val(_globalBarcodeScannerBuffer);
                _globalBarcodeScannerBuffer = "";
                $('#_Barcode').focus();
                
                var eEnter = $.Event('keypress');
                eEnter.which = 13;
                eEnter.keyCode = 13;
                $('#_Barcode').trigger(eEnter);
            } else {
                _globalBarcodeScannerBuffer = "";
            }
        }
    });
</script>
</body>
</html>
