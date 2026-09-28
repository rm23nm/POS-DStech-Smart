<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Billing POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/style.css?v=1.0')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('api/pace/pace-theme-flat-top.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('api/select2/select2.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Midtrans Snap -->
    @if(env('MIDTRANS_IS_PRODUCTION', false))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', '') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', '') }}"></script>
    @endif

    <style>
        /* ========== LAYOUT ========== */
        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #f4f6fb;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        /* Header POS */
        .pos-header {
            background: #1a1a2e;
            color: #eee;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            height: 68px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }
        .pos-header .brand {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #00e5ff;
        }
        .pos-header .user-info {
            font-size: 0.9rem;
            color: #cfd8dc;
        }
        .pos-header .header-actions a {
            color: #cfd8dc;
            text-decoration: none;
            font-size: 0.85rem;
            margin-left: 16px;
        }
        .pos-header .header-actions a:hover { color: #fff; }

        /* Clock */
        .clock-display {
            font-size: 1.8rem;
            font-weight: 700;
            color: #00e5ff;
            letter-spacing: 4px;
            text-shadow: 0 0 10px rgba(0,229,255,0.5);
        }

        /* Main wrapper */
        .pos-main {
            margin-top: 68px; /* header height */
            display: flex;
            height: calc(100vh - 68px);
            overflow: hidden;
        }

        /* ========== LEFT PANEL ========== */
        .left-panel {
            flex: 0 0 70%;
            width: 70%;
            overflow-y: auto;
            padding: 24px;
            @if(count($company) > 0 && $company[0]->TypeBackgraund == 'Color' && !empty($company[0]->Backgraund))
                background-color: {{ $company[0]->Backgraund }};
            @elseif(count($company) > 0 && $company[0]->TypeBackgraund == 'Image' && !empty($company[0]->Backgraund))
                background: linear-gradient(rgba(244, 246, 251, 0.3), rgba(244, 246, 251, 0.3)), url('{{ $company[0]->Backgraund }}') no-repeat center center;
                background-size: 100% 100%;
            @else
                background: #f4f6fb;
            @endif
        }

        /* Kelompok section - Transparent Card */
        .kelompok-section {
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .kelompok-title {
            display: inline-block;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 8px 24px 8px 16px;
            border-radius: 4px 20px 20px 4px;
            margin-bottom: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        }

        /* Titik lampu grid */
        .titik-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* Each titik lampu box */
        .titik-box {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            border: 3px solid transparent;
            transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
            box-shadow: 0 3px 7px rgba(0,0,0,0.22);
            position: relative;
            user-select: none;
            padding: 4px;
        }
        .titik-nama {
            font-size: 0.72rem;
            font-weight: 700;
            text-align: center;
            line-height: 1.2;
            word-break: break-word;
            max-width: 100%;
            padding: 0 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .titik-box:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .titik-box.active-selected {
            border-color: #fff;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.7), 0 4px 12px rgba(0,0,0,0.35);
            transform: scale(1.12);
        }

        /* Status Colors */
        .status-0  { background: linear-gradient(135deg, #2e7d32, #43a047); } /* Hijau - Kosong */
        .status-1  { background: linear-gradient(135deg, #b71c1c, #e53935); } /* Merah - Aktif */
        .status-2  { background: linear-gradient(135deg, #e65100, #fb8c00); } /* Kuning/Orange */
        .status-99 { background: linear-gradient(135deg, #fbc02d, #ffeb3b); color: #000 !important; } /* Kuning - Mau Habis */
        .status-n1 { background: linear-gradient(135deg, #f57f17, #fdd835); filter: brightness(0.9); } /* Kuning keemasan - Checkout */

        .titik-label {
            font-size: 0.6rem;
            opacity: 0.85;
            margin-top: 2px;
        }

        /* ========== RIGHT PANEL ========== */
        .right-panel {
            flex: 0 0 30%;
            width: 30%;
            background: #fff;
            border-left: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 16px rgba(0,0,0,0.08);
            overflow-y: auto;
        }

        /* Placeholder state */
        .right-placeholder {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #bdbdbd;
            padding: 32px;
            text-align: center;
        }
        .right-placeholder i { font-size: 4rem; margin-bottom: 16px; }
        .right-placeholder p { font-size: 1rem; }

        /* Detail content */
        .detail-content { display: none; flex-direction: column; flex: 1; }

        /* Titik name banner */
        .detail-name-banner {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: #fff;
            padding: 14px 16px;
            font-size: 1.2rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1px;
        }
        .detail-status-badge {
            display: block;
            width: 100%;
            padding: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            text-align: center;
            color: #fff;
            border: none;
            cursor: default;
        }

        /* Image */
        .detail-img-wrap {
            width: 100%;
            height: 250px;
            overflow: hidden;
            background: #e9ecef;
            border-bottom: 1px solid #eee;
        }
        .detail-img-wrap img {
            width: 100%;
            height: 100%;
            /* background-size: 100% 100%; */
            object-fit: cover;
        }

        /* Info list */
        .detail-info-list {
            list-style: none;
            margin: 0;
            padding: 0 0 0 0;
            border-bottom: 1px solid #eeee;
        }
        .detail-info-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            border-bottom: 1px dotted #e0e0e0;
            font-size: 0.9rem;
        }
        .detail-info-list li .info-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #37474f;
        }
        .detail-info-list li .info-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.8rem;
        }
        .detail-info-list li .info-value {
            font-weight: 600;
            color: #263238;
            font-size: 0.85rem;
            text-align: right;
            max-width: 55%;
            word-break: break-word;
        }

        /* Action buttons area */
        .detail-actions {
            padding: 12px 12px 8px;
        }
        .detail-actions .btn-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-bottom: 6px;
        }
        .detail-actions .btn-row-2 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }
        .detail-actions .btn-action {
            padding: 10px 4px;
            font-size: 0.78rem;
            font-weight: 700;
            border: none;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            transition: opacity 0.2s, transform 0.15s;
        }
        .detail-actions .btn-action i { font-size: 1.1rem; }
        .detail-actions .btn-action:hover:not(:disabled) {
            opacity: 0.88;
            transform: translateY(-1px);
        }
        .detail-actions .btn-action:disabled {
            opacity: 0.38;
            cursor: not-allowed;
        }
        .btn-pilih-paket { background: linear-gradient(135deg, #2e7d32, #43a047); }
        .btn-checkout     { background: linear-gradient(135deg, #b71c1c, #e53935); }
        .btn-detail-view  { background: linear-gradient(135deg, #e65100, #fb8c00); }
        .btn-tambah-makan { background: linear-gradient(135deg, #1565c0, #1976d2); }
        .btn-tambah-jam   { background: linear-gradient(135deg, #6a1b9a, #8e24aa); }
        .btn-tambah-layanan { background: linear-gradient(135deg, #00695c, #00897b); }

        /* Legend */
        .legend {
            display: flex;
            gap: 20px;
            padding: 12px 16px 8px;
            flex-wrap: wrap;
            font-size: 1rem;
            font-weight: 600;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #37474f;
        }
        .legend-dot {
            width: 22px; height: 22px;
            border-radius: 5px;
            display: inline-block;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #9e9e9e;
            font-size: 1rem;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 10px; display: block; }

        /* Scrollbar styling */
        .left-panel::-webkit-scrollbar,
        .right-panel::-webkit-scrollbar { width: 6px; }
        .left-panel::-webkit-scrollbar-track,
        .right-panel::-webkit-scrollbar-track { background: #f1f1f1; }
        .left-panel::-webkit-scrollbar-thumb,
        .right-panel::-webkit-scrollbar-thumb { background: #bdbdbd; border-radius: 3px; }

        /* Auto Refresh Setting */
        .refresh-config {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 5px 12px;
            color: #ffffff;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
        }
        .refresh-config select {
            background: #1a1a2e;
            color: #ffffff;
            border: 1px solid #00e5ff;
            border-radius: 4px;
            outline: none;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            padding: 2px 6px;
            transition: all 0.2s;
        }
        .refresh-config select:focus {
            box-shadow: 0 0 8px rgba(0,229,255,0.6);
            border-color: #fff;
        }
        .refresh-config select option {
            background: #1a1a2e;
            color: #fff;
        }

        /* Modal Order Detail */
        .modal-pos {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
        }
        .modal-pos-content {
            background-color: #fff;
            margin: 5% auto;
            width: 90%; max-width: 800px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: flex; flex-direction: column;
            max-height: 85vh; overflow: hidden;
            animation: modalSlideDown 0.3s ease-out;
        }
        @keyframes modalSlideDown {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-pos-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex; justify-content: space-between; align-items: center;
            background: #1a237e; color: #fff;
        }
        .modal-pos-header h2 { margin: 0; font-size: 1.3rem; }
        .modal-pos-body { padding: 20px; overflow-y: auto; }
        .modal-pos-footer {
            padding: 12px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex; justify-content: flex-end; gap: 10px;
            background: #f8f9fa;
        }
        .modal-close { color: #fff; font-size: 24px; font-weight: bold; cursor: pointer; }
        
        .detail-info-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 12px; margin-bottom: 20px;
        }
        .detail-item { display: flex; flex-direction: column; }
        .detail-label { font-size: 0.75rem; color: #757575; text-transform: uppercase; margin-bottom: 2px; }
        .detail-value { font-size: 0.95rem; font-weight: 600; color: #212121; }
        
        .section-title {
            font-size: 1rem; font-weight: 700; color: #1a237e;
            margin: 15px 0 10px 0; padding-bottom: 5px;
            border-bottom: 2px solid #e8eaf6;
            display: flex; justify-content: space-between; align-items: center;
        }
        .fnb-table { width: 100%; border-collapse: collapse; }
        .fnb-table th { text-align: left; padding: 10px; background: #f5f5f5; color: #616161; font-size: 0.8rem; }
        .fnb-table td { padding: 10px; border-bottom: 1px solid #eee; font-size: 0.85rem; }
        
        .payment-summary-box {
            background: #e8f5e9; border: 1px solid #c8e6c9;
            border-radius: 8px; padding: 15px; margin-top: 15px;
        }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 0.9rem; }
        .summary-row.total {
            font-weight: 700; font-size: 1rem;
            border-top: 1px solid #a5d6a7; padding-top: 5px;
            margin-top: 5px; color: #2e7d32;
        }
        .btn-modal-action {
            padding: 8px 18px; border: none; border-radius: 4px;
            font-weight: 600; cursor: pointer; font-size: 0.9rem;
        }
        .btn-modal-close { background: #e0e0e0; color: #424242; }
        .btn-modal-pay { background: #43a047; color: #fff; }

        /* TreeView Styles for FnB Grouping */
        .fnb-group-header {
            background-color: #f8f9fa !important;
            cursor: pointer;
            font-weight: 700;
            color: #1a237e;
            border-bottom: 2px solid #e8eaf6 !important;
        }
        .fnb-group-header:hover {
            background-color: #ede7f6 !important;
        }
        .fnb-group-header i {
            margin-right: 8px;
            transition: transform 0.2s;
            width: 14px;
            text-align: center;
        }
        .fnb-group-header.expanded i {
            transform: rotate(90deg);
        }
        .fnb-details-row {
            background-color: #ffffff;
        }
        .fnb-details-row td {
            padding-left: 35px !important;
            border-bottom: 1px solid #f1f1f1 !important;
            font-size: 0.82rem !important;
            color: #455a64;
        }
        /* ========== RECEIPT PREVIEW MODAL (THERMAL) ========== */
        .receipt-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 9000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }
        .receipt-overlay.open { display: flex; }

        .receipt-container {
            background: #fff;
            width: 400px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            overflow: hidden;
            animation: receiptPop 0.3s ease-out;
        }
        @keyframes receiptPop {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .receipt-paper {
            background: #fff;
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            font-family: 'Courier New', Courier, monospace;
            color: #000;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
        }
        .receipt-logo {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .receipt-address {
            font-size: 0.85rem;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .receipt-divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .receipt-info {
            font-size: 0.85rem;
            margin-bottom: 10px;
        }
        .receipt-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .receipt-table {
            width: 100%;
            font-size: 0.85rem;
            border-collapse: collapse;
        }
        .receipt-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .receipt-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .receipt-totals {
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .receipt-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .receipt-grand-total {
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px double #000;
        }

        .receipt-footer {
            text-align: center;
            font-size: 0.8rem;
            margin-top: 20px;
            font-style: italic;
        }

        .receipt-actions {
            background: #f1f3f4;
            padding: 15px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn-receipt {
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-receipt-print { background: #1a237e; color: #fff; }
        .btn-receipt-email { background: #e91e63; color: #fff; }
        .btn-receipt-wa { background: #25d366; color: #fff; }
        .btn-receipt:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-reprint {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #1a237e;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 0.75rem;
            margin-left: 10px;
            transition: all 0.2s ease;
            padding: 0;
            vertical-align: middle;
        }
        .btn-reprint:hover {
            background-color: #3949ab;
            transform: scale(1.1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
        .btn-reprint i {
            pointer-events: none;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                background: #fff;
                visibility: hidden; /* Hide all by default */
            }
            /* Show only the receipt */
            #modalReceiptPreview, #modalReceiptPreview * {
                visibility: visible !important;
            }
            #modalReceiptPreview {
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .receipt-container {
                box-shadow: none !important;
                border: none !important;
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
            }
            
            .receipt-paper {
                padding: 5mm !important;
                width: 80mm !important;
                overflow: visible !important;
                box-sizing: border-box !important;
            }
            
            /* Ensure text is black for printing */
            #modalReceiptPreview * {
                color: #000 !important;
                background: transparent !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            .receipt-actions { display: none !important; }
        }

        /* FnB Selection Styling for Main POS */
        .fnb-selection-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 10px; margin-top: 10px; max-height: 350px; overflow-y: auto; padding: 10px; background: #f8f9fa; border-radius: 12px; border: 1px solid #e0e0e0; }
        .fnb-card { background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid #eee; cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .fnb-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #1a237e; }
        .fnb-card-img { width: 100%; height: 90px; object-fit: cover; background: #eee; }
        .fnb-card-body { padding: 8px; flex: 1; display: flex; flex-direction: column; }
        .fnb-card-name { font-weight: 700; font-size: 0.75rem; color: #263238; line-height: 1.2; margin-bottom: 4px; height: 2.4em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        .fnb-card-price { font-weight: 800; color: #2e7d32; font-size: 0.8rem; }
        .fnb-card-stock { font-size: 0.65rem; color: #78909c; margin-top: auto; }

        /* FnB Selection Styling - List Style (Harmonized with Self-Service) */
        .fnb-selection-area { border: 1.5px solid #e0e0e0; border-radius: 12px; overflow: hidden; margin-top: 15px; background: #fff; }
        .fnb-header { background: #f5f5f5; padding: 12px 16px; font-weight: 700; color: #1a237e; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; }
        .fnb-list-container { height: 300px; max-height: 400px; overflow-y: auto; padding: 10px; background: #fff; border: 1px solid #eee; border-radius: 8px; margin: 10px; }
        .fnb-item { display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f1f1f1; gap: 12px; transition: background 0.2s; min-height: 80px; }
        .fnb-item:hover { background: #f8f9ff; }
        .fnb-item:last-child { border-bottom: none; }
        .fnb-item-img { width: 55px; height: 55px; border-radius: 10px; object-fit: cover; background: #eee; border: 1px solid #e0e0e0; }
        .fnb-item-info { flex: 1; min-width: 0; }
        .fnb-item-name { font-weight: 700; font-size: 0.95rem; color: #1a237e; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .fnb-item-price { font-size: 0.85rem; color: #e65100; font-weight: 700; }
        .fnb-item-stock { font-size: 0.7rem; color: #78909c; font-weight: 600; margin-top: 1px; }
        .fnb-qty-ctrl { display: flex; align-items: center; gap: 10px; background: #f5f5f5; padding: 4px 8px; border-radius: 20px; border: 1.5px solid #e0e0e0; }
        .fnb-qty-btn { width: 28px; height: 28px; border-radius: 50%; border: none; background: #fff; color: #1a237e; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 700; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .fnb-qty-btn:hover { background: #1a237e; color: #fff; transform: scale(1.1); }
        .fnb-qty-btn:active { transform: scale(0.9); }
        .fnb-qty-val { width: 25px; text-align: center; font-weight: 800; font-size: 1rem; color: #1a237e; }
    </style>

</head>

<body>
    <!-- ===== HEADER ===== -->
    <header class="pos-header">
        <div class="brand d-flex align-items-center gap-2">
            @if(!empty($company[0]['icon']))
                <img src="{{ $company[0]['icon'] }}" alt="Logo" style="height: 40px; width: auto; border-radius: 4px; background: white; padding: 2px;">
            @else
                <img src="{{ asset('images/misc/LogoFront.png') }}" alt="Logo" style="height: 40px; width: auto;">
            @endif
            <span>{{ $company[0]['NamaPartner'] }}</span>
        </div>
        <div class="clock-display" id="posHeaderClock">--:--:--</div>
        <div class="d-flex align-items-center gap-3 header-actions">
            <a href="{{ route('ticketing-pos') }}" class="btn btn-sm btn-info text-white" style="background:#0ea5e9; border:none; padding:4px 10px; border-radius:4px;"><i class="fas fa-swimmer"></i> Ticketing / Kolam</a>
            <div class="refresh-config">
                <i class="fas fa-sync-alt"></i>
                <span>Auto Refresh:</span>
                <select id="autoRefreshInterval" onchange="onRefreshIntervalChange()">
                    <option value="0">OFF</option>
                    <option value="10000" selected>10s</option>
                    <option value="30000">30s</option>
                    <option value="60000">1m</option>
                    <option value="120000">2m</option>
                </select>
            </div>
            <span class="user-info"><i class="fas fa-user-circle"></i> {{ Auth::user()->name }}</span>
            <a href="javascript:void(0)" onclick="openCustomerDisplay()"><i class="fas fa-desktop"></i> Customer Display</a>
            <a href="javascript:void(0)" onclick="openJualFnbModal()" style="background: linear-gradient(135deg,#e65100,#ff8f00); color:#fff; padding:5px 12px; border-radius:6px; font-weight:600;"><i class="fas fa-utensils"></i> Jual FnB</a>
            <a href="{{ route('bookinglist') }}" target="_blank"><i class="fas fa-calendar-alt"></i> Booking</a>
            <a href="{{ route('logout') }}">
                <i class="fas fa-power-off"></i> Logout
            </a>
        </div>
    </header>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

    <!-- ===== MAIN SPLIT LAYOUT ===== -->
    <div class="pos-main">

        <!-- ===== LEFT PANEL : Titik Lampu Grid ===== -->
        <div class="left-panel">
            <!-- Legend -->
            <div class="legend mb-1">
                <span class="legend-item"><span class="legend-dot" style="background:#43a047;"></span> Kosong</span>
                <span class="legend-item"><span class="legend-dot" style="background:#e53935;"></span> Aktif</span>
                <span class="legend-item"><span class="legend-dot" style="background:#fb8c00;"></span> Hampir Habis / Checkout</span>
            </div>

            @if (count($kelompoklampu) > 0)
                @foreach ($kelompoklampu as $tl)
                    @php
                        // Filter titik lampu for this kelompok
                        $itemInGroup = $titiklampu->filter(fn($item) => $item->KelompokLampu == $tl->KodeKelompok)->sortBy('DigitalInput');
                    @endphp
                    @if ($itemInGroup->count() > 0)
                        <div class="kelompok-section">
                            <div class="kelompok-title"><i class="fas fa-layer-group me-1"></i> {{ $tl->NamaKelompok }}</div>
                            <div class="titik-grid">
                                @foreach ($itemInGroup as $item)
                                    @php
                                        $statusClass = 'status-0';
                                        if ($item->Status == 1)  $statusClass = 'status-1';
                                        elseif ($item->Status == 99) $statusClass = 'status-99';
                                        elseif ($item->Status == -1) $statusClass = 'status-n1';
                                        elseif ($item->Status == 2)  $statusClass = 'status-2';

                                        $statusLabel = 'KOSONG';
                                        if ($item->Status == 1)  $statusLabel = 'AKTIF';
                                        elseif ($item->Status == 99) $statusLabel = 'HABIS';
                                        elseif ($item->Status == -1) $statusLabel = 'CHECKOUT';
                                        elseif ($item->Status == 2)  $statusLabel = 'KUNING';
                                    @endphp
                                    <div class="titik-box {{ $statusClass }}"
                                         data-id="{{ $item->id }}"
                                         data-namatitiklampu="{{ $item->NamaTitikLampu }}"
                                         data-notransaksi="{{ $item->NoTransaksi }}"
                                         data-jenispaket="{{ $item->JenisPaket }}"
                                         data-status="{{ $item->Status }}"
                                         data-namapaket="{{ $item->NamaPaket ?? '' }}"
                                         data-jammulai="{{ $item->JamMulai ? \Carbon\Carbon::parse($item->JamMulai)->format('d/m/Y H:i') : '-' }}"
                                         data-jamselesai="{{ $item->JamSelesai ? \Carbon\Carbon::parse($item->JamSelesai)->format('d/m/Y H:i') : '-' }}"
                                         data-gambar="{{ $item->Gambar ?? '' }}"
                                         data-statuslabel="{{ $statusLabel }}"
                                         data-totalPembayaran="{{ $item->TotalPembayaran ?? 0 }}"
                                         data-rawjammulai="{{ $item->JamMulai ?? '' }}"
                                         data-rawjamselesai="{{ $item->JamSelesai ?? '' }}"
                                         data-namakelompok="{{ $tl->NamaKelompok }}"
                                         data-kelompoklampu="{{ $item->KelompokLampu ?? '' }}"
                                         onclick="selectTitikLampu(this)"
                                         title="{{ $item->NamaTitikLampu }}"
                                         role="button">
                                        <div class="titik-nama">{{ $item->NamaTitikLampu }}</div>
                                        @if($item->Status != 0 && ($item->TotalPembayaran ?? 0) > 0)
                                            <div class="paid-badge" title="Sudah Ada Pembayaran">
                                                PAID
                                            </div>
                                        @endif
                                        <div class="table-timer">--:--:--</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-lightbulb"></i>
                    Belum Ada Data Titik Lampu
                </div>
            @endif
        </div>

        <!-- ===== RIGHT PANEL : Detail ===== -->
        <div class="right-panel" id="rightPanel">

            <!-- Placeholder (no selection) -->
            <div class="right-placeholder" id="rightPlaceholder">
                <i class="fas fa-hand-pointer"></i>
                <p>Klik Titik Lampu<br>untuk melihat detail</p>
            </div>

            <!-- Detail content (shown when selected) -->
            <div class="detail-content" id="detailContent">
                <!-- Name banner -->
                <div class="detail-name-banner" id="detailNamaTitikLampu">--</div>

                <!-- Status badge -->
                <div class="detail-status-badge" id="detailStatusBadge" style="background:#9e9e9e;">--</div>

                <!-- Image -->
                <div class="detail-img-wrap">
                    <img id="detailGambar" src="https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg" alt="Titik Lampu" style="opacity: 0.5;" />
                </div>

                <!-- Info list -->
                <ul class="detail-info-list">
                    <li>
                        <div class="info-label">
                            <span class="info-icon bg-success"><i class="fas fa-box"></i></span>
                            Paket
                        </div>
                        <span class="info-value" id="detailPaket">-</span>
                    </li>
                    <li>
                        <div class="info-label">
                            <span class="info-icon bg-primary"><i class="fas fa-play"></i></span>
                            Jam Mulai
                        </div>
                        <span class="info-value" id="detailJamMulai">-</span>
                    </li>
                    <li>
                        <div class="info-label">
                            <span class="info-icon bg-warning"><i class="fas fa-stop"></i></span>
                            Jam Selesai
                        </div>
                        <span class="info-value" id="detailJamSelesai">-</span>
                    </li>
                </ul>

                <!-- Action buttons -->
                <div class="detail-actions">
                    <!-- Row 1: Pilih Paket, Checkout, Detail -->
                    <div class="btn-row mb-2">
                        <button class="btn-action btn-pilih-paket" id="btnPilihPaket" onclick="onPilihPaket()" title="Pilih Paket">
                            <i class="fas fa-play"></i>
                            <span>Paket</span>
                        </button>
                        <button class="btn-action btn-checkout" id="btnCheckOut" onclick="onCheckOut()" title="Checkout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Checkout</span>
                        </button>
                        <button class="btn-action btn-detail-view" id="btnDetail" onclick="onDetail()" title="Detail">
                            <i class="fas fa-info-circle"></i>
                            <span>Detail</span>
                        </button>
                    </div>
                    <!-- Row 2: Tambah Makan, Tambah Durasi, Tambah Layanan -->
                    <div class="btn-row-2">
                        <button class="btn-action btn-tambah-makan" id="btnTambahMakan" onclick="onTambahMakan()" title="Tambah Makan">
                            <i class="fas fa-utensils"></i>
                            <span>+ Makan</span>
                        </button>
                        <button class="btn-action btn-tambah-jam" id="btnTambahJam" onclick="onTambahJam()" title="Tambah Durasi">
                            <i class="fas fa-clock"></i>
                            <span>+ Durasi</span>
                        </button>
                        <button class="btn-action btn-tambah-layanan" id="btnTambahLayanan" onclick="onTambahLayanan()" title="Tambah Layanan">
                            <i class="fas fa-concierge-bell"></i>
                            <span>+ Layanan</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('devexpress/jquery.min.js') }}"></script>
    <script src="{{ asset('api/select2/select2.min.js') }}"></script>

    <script>
    // ===== DATA from PHP =====
    // All titik lampu data in JSON for JS use
    var titiklampuData = @json($titiklampu);

    // Currently selected titik lampu
    var selectedTitik = null;
    var refreshTimer = null;

    // ===== CLOCK =====
    function updateClock() {
        var _nowLocal = new Date();
        var h = String(_nowLocal.getHours()).padStart(2, '0');
        var m = String(_nowLocal.getMinutes()).padStart(2, '0');
        var s = String(_nowLocal.getSeconds()).padStart(2, '0');
        document.getElementById('posHeaderClock').textContent = h + ':' + m + ':' + s;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ===== AUTO REFRESH =====
    function onRefreshIntervalChange() {
        console.log("Interval changed, restarting auto refresh...");
        var interval = parseInt(document.getElementById('autoRefreshInterval').value);
        stopAutoRefresh();
        if (interval > 0) {
            startAutoRefresh(interval);
        }
    }

    function startAutoRefresh(ms) {
        refreshTimer = setInterval(refreshTableStatuses, ms);
        console.log("Auto refresh started: " + ms + "ms");
    }

    function stopAutoRefresh() {
        if (refreshTimer) {
            clearInterval(refreshTimer);
            refreshTimer = null;
            console.log("Auto refresh stopped.");
        }
    }

    let isRefreshing = false;
    function refreshTableStatuses() {
        if (isRefreshing) return;
        isRefreshing = true;
        console.log("Auto-refresh: Fetching latest statuses from server...");

        fetch('/billing/get-table-statuses')
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
                return res.json();
            })
            .then(res => {
                if (res.success) {
                    console.log("Auto-refresh: Data received successfully (" + res.data.length + " tables)");
                    updateUIWithLatestData(res.data);
                } else {
                    console.error("Auto-refresh: Server returned error:", res.message);
                }
            })
            .catch(err => {
                console.error("Auto-refresh: Fetch failed:", err);
            })
            .finally(() => {
                isRefreshing = false;
            });
    }
    
    // Initial start - tunggu sampai DOM siap baru jalankan auto-refresh
    document.addEventListener('DOMContentLoaded', function() {
        onRefreshIntervalChange();
        // Langsung ambil data awal saat halaman pertama dibuka
        refreshTableStatuses();
    });

    // Timer Heartbeat - hitung mundur setiap detik
    setInterval(updateTableTimers, 1000);

    function updateTableTimers() {
        document.querySelectorAll('.titik-box').forEach(el => {
            const status = parseInt(el.dataset.status || 0);
            const rawMulai = el.dataset.rawjammulai;
            const rawSelesai = el.dataset.rawjamselesai;
            const timerEl = el.querySelector('.table-timer');
            if (!timerEl) return;

            if (status === 0) {
                if (rawMulai && rawMulai !== "") {
                    timerEl.textContent = "Book: " + (el.dataset.jammulai || "");
                    timerEl.style.color = "#1565c0"; // Blueish for booking
                } else {
                    timerEl.textContent = "";
                }
                return;
            }

            var _nowLocal = new Date();
            let diffMs = 0;
            let label = "";

            if (rawSelesai && rawSelesai.trim() !== "" && rawSelesai !== "null") {
                // Countdown Logic
                const end = new Date(rawSelesai.replace(' ', 'T'));
                diffMs = end - _nowLocal;
                if (diffMs < 0) {
                    label = "TIME UP";
                    timerEl.style.color = "#d32f2f";
                    // If time is up, force checkout status color
                    el.classList.remove('status-1', 'status-99');
                    el.classList.add('status-n1');
                } else {
                    label = formatDuration(diffMs);
                    timerEl.style.color = "inherit";
                    // Warning: 10 minutes or less
                    if (diffMs <= 10 * 60 * 1000) {
                        // Only change to warning if it's currently active (status-1)
                        // and not already at checkout (status-n1)
                        if (!el.classList.contains('status-n1')) {
                            el.classList.remove('status-1');
                            el.classList.add('status-99');
                        }
                    }
                    // No revert here to avoid fighting with server clock sync. 
                    // Reverts are handled by updateUIWithLatestData when fetching from server.
                }
            } else if (rawMulai && rawMulai.trim() !== "" && rawMulai !== "null") {
                // Count-up Logic
                const start = new Date(rawMulai.replace(' ', 'T'));
                diffMs = _nowLocal - start;
                label = formatDuration(diffMs);
                timerEl.style.color = "inherit";
            }

            timerEl.textContent = label;
        });
    }

    function formatDuration(ms) {
        let absMs = Math.abs(ms);
        let totalSecs = Math.floor(absMs / 1000);
        let h = Math.floor(totalSecs / 3600);
        let m = Math.floor((totalSecs % 3600) / 60);
        let s = totalSecs % 60;
        return [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
    }

    function updateUIWithLatestData(data) {
        data.forEach(item => {
            var el = document.querySelector('.titik-box[data-id="' + item.id + '"]');
            if (el) {
                // Update data attributes
                el.dataset.notransaksi = item.NoTransaksi || '';
                el.dataset.jenispaket = item.JenisPaket || '';
                el.dataset.status = item.Status || 0;
                el.dataset.namapaket = item.NamaPaket || '';
                el.dataset.jammulai = item.JamMulaiParsed || '-';
                el.dataset.jamselesai = item.JamSelesaiParsed || '-';
                el.dataset.statuslabel = item.StatusMeja || 'KOSONG';
                el.dataset.totalPembayaran = item.TotalPembayaran || 0;
                el.dataset.nettotal = item.NetTotal || 0;
                el.dataset.rawjammulai = item.JamMulai || '';
                el.dataset.rawjamselesai = item.JamSelesai || '';
                el.dataset.namakelompok = item.NamaKelompok || '';
                el.dataset.kelompoklampu = item.KelompokLampu || '';

                // Update classes
                el.className = 'titik-box';
                if (el.dataset.id == (selectedTitik ? selectedTitik.id : null)) {
                    el.classList.add('active-selected');
                }
                
                var s = parseInt(item.Status);
                if (s === 0) el.classList.add('status-0');
                else if (s === 1) el.classList.add('status-1');
                else if (s === 2) el.classList.add('status-2');
                else if (s === 99) el.classList.add('status-99');
                else if (s === -1) el.classList.add('status-n1');

                // Update Paid Badge
                var totalPay = parseFloat(item.TotalPembayaran || 0);
                var status = parseInt(item.Status || 0);
                var paidBadge = el.querySelector('.paid-badge');

                // Explicitly remove badge if status is 0 (Vacant/Booking)
                if (status === 0) {
                    if (paidBadge) paidBadge.remove();
                } else if (status !== 0 && totalPay > 0) {
                    // Show badge only for Active/Checkout if paid
                    if (!paidBadge) {
                        paidBadge = document.createElement('div');
                        paidBadge.className = 'paid-badge';
                        paidBadge.title = 'Sudah Ada Pembayaran';
                        paidBadge.innerHTML = 'PAID';
                        el.appendChild(paidBadge);
                    }
                } else {
                    if (paidBadge) paidBadge.remove();
                }

                // Ensure Timer exists
                if (!el.querySelector('.table-timer')) {
                    var timerDiv = document.createElement('div');
                    timerDiv.className = 'table-timer';
                    timerDiv.textContent = '--:--:--';
                    el.appendChild(timerDiv);
                }
            }
        });

        // If something is selected, refresh the right panel
        if (selectedTitik) {
            var updated = data.find(x => x.id == selectedTitik.id);
            if (updated) {
                // Mock a selection to re-trigger renderRightPanel with new data
                var mockEl = document.querySelector('.titik-box[data-id="' + updated.id + '"]');
                if (mockEl) {
                    var newData = {
                        id:             mockEl.dataset.id,
                        namatitiklampu: mockEl.dataset.namatitiklampu,
                        notransaksi:    mockEl.dataset.notransaksi,
                        jenispaket:     mockEl.dataset.jenispaket,
                        status:         parseInt(mockEl.dataset.status),
                        namapaket:      mockEl.dataset.namapaket,
                        jammulai:       mockEl.dataset.jammulai,
                        jamselesai:     mockEl.dataset.jamselesai,
                        rawjammulai:    mockEl.dataset.rawjammulai,
                        rawjamselesai:  mockEl.dataset.rawjamselesai,
                        namakelompok:   mockEl.dataset.namakelompok,
                        kelompoklampu:  mockEl.dataset.kelompoklampu || '',
                        gambar:         mockEl.dataset.gambar,
                        statuslabel:    mockEl.dataset.statuslabel,
                        totalPembayaran: parseFloat(mockEl.dataset.totalPembayaran || 0),
                        nettotal:        parseFloat(mockEl.dataset.nettotal || 0)
                    };
                    selectedTitik = newData;
                    renderRightPanel(newData);
                }
            }
        }
    }

    // ===== SELECT TITIK LAMPU =====
    function selectTitikLampu(el) {
        // Deselect previous
        document.querySelectorAll('.titik-box.active-selected').forEach(function(b) {
            b.classList.remove('active-selected');
        });

        // Select this one
        el.classList.add('active-selected');

        var data = {
            id:             el.dataset.id,
            namatitiklampu: el.dataset.namatitiklampu,
            notransaksi:    el.dataset.notransaksi,
            jenispaket:     el.dataset.jenispaket,
            status:         parseInt(el.dataset.status),
            namapaket:      el.dataset.namapaket,
            jammulai:       el.dataset.jammulai,
            jamselesai:     el.dataset.jamselesai,
            rawjammulai:    el.dataset.rawjammulai,
            rawjamselesai:  el.dataset.rawjamselesai,
            namakelompok:   el.dataset.namakelompok,
            kelompoklampu:  el.dataset.kelompoklampu || '',
            gambar:         el.dataset.gambar,
            statuslabel:    el.dataset.statuslabel,
            totalPembayaran: parseFloat(el.dataset.totalPembayaran || 0)
        };

        selectedTitik = data;
        renderRightPanel(data);

        // Jika meja kosong (Status 0), langsung buka modal Pilih Paket (Menu)
        if (data.status === 0) {
            onPilihPaket();
        }
    }

    function isAnyModalOpen() {
        return $('#modalPilihPaket').hasClass('open') || 
               $('#modalTambahMakanan').hasClass('open') || 
               $('#modalTambahDurasi').hasClass('open') || 
               $('#modalDetailOrder').hasClass('open');
    }

    function renderRightPanel(data) {
        // Hide placeholder, show detail
        document.getElementById('rightPlaceholder').style.display = 'none';
        document.getElementById('detailContent').style.display = 'flex';

        // Name
        document.getElementById('detailNamaTitikLampu').textContent = data.namatitiklampu;

        // Status badge
        var badge = document.getElementById('detailStatusBadge');
        badge.textContent = data.statuslabel;
        var badgeColors = {
            0: '#43a047', 1: '#e53935', 2: '#fb8c00', 99: '#fb8c00', '-1': '#fdd835'
        };
        badge.style.background = badgeColors[data.status] || '#9e9e9e';
        badge.style.color = data.status == -1 ? '#333' : '#fff';

        // Image
        var imgEl = document.getElementById('detailGambar');
        if (data.gambar && data.gambar.trim() !== '') {
            imgEl.src = data.gambar;
            imgEl.style.opacity = '1';
        } else {
            imgEl.src = 'https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg';
            imgEl.style.opacity = '0.5';
        }

        // Paket
        document.getElementById('detailPaket').textContent = (data.namapaket && data.namapaket.trim() !== '') ? data.namapaket : '-';

        // Jam Mulai
        document.getElementById('detailJamMulai').textContent = data.jammulai || '-';

        // Jam Selesai
        document.getElementById('detailJamSelesai').textContent = data.jamselesai || '-';

        // Enable/Disable buttons based on status
        // status: 0 = Kosong, 1 = Aktif, 99 = Hampir Habis, -1 = Checkout
        var s = data.status;
        var noTrx = data.notransaksi && data.notransaksi.trim() !== '';
        var jenis = data.jenispaket || '';

        // Pilih Paket: enabled only when status = 0 (kosong)
        setBtn('btnPilihPaket', s === 0);

        // Checkout: enabled when status = 1 or 99
        setBtn('btnCheckOut', (s === 1 || s === 99));

        // Detail: enabled when there's a transaction AND table is NOT vacant (status 0)
        setBtn('btnDetail', noTrx && s !== 0);

        // Tambah Makan: enabled when status = 1 or 99 (and has transaction)
        setBtn('btnTambahMakan', (s === 1 || s === 99) && noTrx);

        // Tambah Durasi: enabled when status = 1 or 99 AND jenis supports it
        var jenisJam = ['JAM', 'MENIT' ,'JAMREALTIME', 'DAILY', 'MONTHLY', 'YEARLY'];
        setBtn('btnTambahJam', (s === 1 || s === 99) && noTrx && jenisJam.includes(jenis));

        // Tambah Layanan: enabled when status = 1 or 99
        setBtn('btnTambahLayanan', (s === 1 || s === 99) && noTrx);

        // Sync with Customer Display (full detail if available)
        // Skip sync if a modal is open, to avoid overwriting unsaved modal state
        if (!isAnyModalOpen()) {
            syncCustomerDisplayFromSelected(data);
        }
    }

    function fetchAndSyncCustomerDisplay(noTransaksi) {
        if (!noTransaksi) return;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        $.ajax({
            url: '{{ route("billing-get-order-detail") }}',
            method: 'POST',
            data: {
                _token: token,
                NoTransaksi: noTransaksi
            },
            success: function(response) {
                if (response.success) {
                    syncDisplayFromResponse(response);
                }
            }
        });
    }

    function syncDisplayFromResponse(response) {
        const h = response.header;
        const fnb = response.fnb;
        
        let fnbTotal = 0;
        fnb.forEach(item => {
            const sub = parseFloat(item.Harga || 0) * parseFloat(item.Qty || 0);
            fnbTotal += sub;
        });

        const syncData = {
            data: [],
            Total: (parseFloat(h.TotalPaket || 0)) + fnbTotal,
            Discount: parseFloat(h.TotalDiskon || 0),
            Tax: (parseFloat(h.TotalPajak || 0)) + (parseFloat(h.TotalPajakHiburan || 0)) + (parseFloat(h.TotalLayanan || 0)),
            Net: (parseFloat(h.GrandTotal || 0)) + fnbTotal
        };

        if (h.NamaPaket) {
            syncData.data.push({ NamaItem: h.NamaPaket, Qty: 1, Harga: h.HargaPaket });
        }

        fnb.forEach(item => {
            syncData.data.push({ NamaItem: item.NamaItem || item.KodeItem, Qty: item.Qty, Harga: item.Harga });
        });

        syncCustomerDisplay(syncData);
    }

    let custDisplayWindow = null;
    function openCustomerDisplay() {
        if (custDisplayWindow && !custDisplayWindow.closed) {
            custDisplayWindow.focus();
        } else {
            const url = "{{ route('fpenjualan-custdisplay-new') }}";
            custDisplayWindow = window.open(url, 'CustomerDisplay', 'width=1280,height=720');
        }
    }

    function syncCustomerDisplay(data) {
        localStorage.setItem('PoSData', JSON.stringify(data));
    }

    function syncCustomerGreeting(name) {
        if (!name) return;
        localStorage.setItem('PoSGreeting', JSON.stringify({ name: name, timestamp: Date.now() }));
    }



    function isCustDisplayOpen() {
        return (custDisplayWindow && !custDisplayWindow.closed);
    }

    function syncCustomerDisplayFromSelected(data) {
        // If there's a transaction, fetch full details for accurate item list and totals
        if (data.notransaksi && typeof data.notransaksi === 'string' && data.notransaksi.trim() !== '') {
            fetchAndSyncCustomerDisplay(data.notransaksi);
            return;
        }

        // Fast initial sync for empty/new tables
        const syncData = {
            data: data.namapaket ? [{ NamaItem: data.namapaket, Qty: 1, Harga: 0 }] : [],
            Total: 0,
            Discount: 0,
            Tax: 0,
            Net: 0
        };
        syncCustomerDisplay(syncData);
    }

    function setBtn(id, enabled) {
        var btn = document.getElementById(id);
        if (!btn) return;
        btn.disabled = !enabled;
    }

    // ===== ACTION HANDLERS =====
    function onPilihPaket() {
        if (!selectedTitik) return;
        // Populate modal header
        document.getElementById('modalPaketTitikNama').textContent = selectedTitik.namatitiklampu;
        // Reset form fields
        const _nowLocal = new Date();
        const year = _nowLocal.getFullYear();
        const month = String(_nowLocal.getMonth() + 1).padStart(2, '0');
        const day = String(_nowLocal.getDate()).padStart(2, '0');
        document.getElementById('ppTglTransaksi').value = `${year}-${month}-${day}`;
        
        var selJenis = document.getElementById('ppJenisPaket');
        selJenis.value = 'JAM'; // Default to JAM for faster workflow
        onJenisPaketChange('JAM'); 

        // Durasi & Harga reset
        document.getElementById('ppHargaNormal').value = '';
        document.getElementById('ppDurasi').value = '1';
        document.getElementById('ppMemberSearch').value = '';
        // document.getElementById('ppKodeSales').value = '';

        // Reset FnB Cart
        ppFnbCart = [];
        updatePpFnbListQty();

        // Auto-select first available packet if filtered
        const selPaket = $('#ppPaketId');
        if (selPaket.find('option[value!=""]').length > 0) {
            const firstVal = selPaket.find('option[value!=""]').first().val();
            selPaket.val(firstVal).trigger('change');
        }

        // Reset Jam Mulai ke Waktu Sekarang (24H Format)
        var now = new Date();
        const elmJamMulai = document.getElementById('ppJamMulai');
        elmJamMulai.value = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
        elmJamMulai.readOnly = false;
        elmJamMulai.style.backgroundColor = '';

        // Reset validasi saat modal kebuka
        validateForm();
        
        // Show modal
        document.getElementById('modalPilihPaket').classList.add('open');
    }

    function closePilihPaketModal() {
        document.getElementById('modalPilihPaket').classList.remove('open');
    }

    // Close on overlay click
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('modalPilihPaket').addEventListener('click', function(e) {
            if (e.target === this) closePilihPaketModal();
        });
        // Set today's date
        var todayInput = document.getElementById('ppTglTransaksi');
        if (todayInput) todayInput.valueAsDate = new Date();
    });

    function onCheckOut() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Peringatan", "Pilih meja yang sedang aktif terlebih dahulu", "warning");
            return;
        }

        swal({
            title: "Konfirmasi Checkout",
            text: "Apakah Anda yakin ingin melakukan checkout untuk " + selectedTitik.namatitiklampu + "?",
            type: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Checkout!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.value) {
                // Tampilkan loading
                swal({
                    title: "Memproses...",
                    text: "Sedang melakukan checkout",
                    type: "info",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/billing/process-checkout',
                    method: 'POST',
                    data: {
                        _token: token,
                        NoTransaksi: selectedTitik.notransaksi
                    },
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: "Berhasil",
                                text: response.message,
                                type: "success"
                            }).then(() => {
                                // Refresh status meja
                                refreshTableStatuses();
                                // Kosongkan panel kanan
                                selectedTitik = null;
                                $('.titik-box').removeClass('selected');
                                $('#billing-detail-container').html('<div class="empty-state">Pilih meja untuk melihat detail</div>');
                            });
                        } else {
                            swal("Gagal", response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        swal("Error", "Terjadi kesalahan sistem", "error");
                    }
                });
            }
        });
    }

    function onDetail() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Peringatan", "Pilih meja yang sedang aktif terlebih dahulu", "warning");
            return;
        }

        // Show loading state
        swal({
            title: "Memuat...",
            text: "Mengambil detail order",
            type: "info",
            showConfirmButton: false,
            allowOutsideClick: false
        });

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: '{{ route("billing-get-order-detail") }}',
            method: 'POST',
            data: {
                _token: token,
                NoTransaksi: selectedTitik.notransaksi
            },
            success: function(response) {
                swal.close();
                if (response.success) {
                    syncDisplayFromResponse(response);
                    populateDetailModal(response);
                    $('#modalDetailOrder').fadeIn();
                } else {
                    swal("Gagal", response.message, "error");
                }
            },
            error: function(xhr) {
                swal("Error", "Gagal mengambil data dari server", "error");
            }
        });
    }

    function closeDetailModal() {
        $('#modalDetailOrder').fadeOut();
    }

    function populateDetailModal(res) {
        const h = res.header;
        const p = res.payment;
        
        // Store JamSelesai for checkout logic
        window._mdJamSelesai = h.JamSelesai || null;
        window._mdHeaderStatus = h.Status;

        const _nowLocal = new Date();
        const year = _nowLocal.getFullYear();
        const month = String(_nowLocal.getMonth() + 1).padStart(2, '0');
        const day = String(_nowLocal.getDate()).padStart(2, '0');
        const localDate = `${year}-${month}-${day}`;
        $('#ppTglTransaksi').val(localDate);
        $('#mdNamaPelanggan').text(h.NamaPelanggan || 'Umum');
        $('#mdJamMulai').text(h.JamMulai || '-');

        // Show effective JamSelesai with LIVE badge if calculated on the fly
        if (h.IsLiveDuration) {
            const liveTime = h.JamSelesai ? h.JamSelesai.substring(0, 16).replace('T', ' ') : '-';
            $('#mdJamSelesai').html(liveTime + ' <span style="background:#e53935;color:#fff;font-size:0.72rem;padding:1px 6px;border-radius:20px;font-weight:700;vertical-align:middle;">LIVE</span>');
        } else {
            $('#mdJamSelesai').text(h.JamSelesai ? h.JamSelesai.substring(0, 16).replace('T', ' ') : '-');
        }

        let unit = 'Menit';
        let displayDurasi = h.DurasiMenit;
        const jp = h.JenisPaket;

        if (['JAM', 'JAMREALTIME', 'PAKETMEMBER'].includes(jp)) {
            unit = 'Jam';
            displayDurasi = Math.ceil(h.DurasiMenit / 60);
        } else if (jp === 'DAILY') {
            unit = 'Hari';
            displayDurasi = Math.ceil(h.DurasiMenit / 1440);
        } else if (jp === 'MONTHLY') {
            unit = 'Bulan';
            displayDurasi = Math.ceil(h.DurasiMenit / (1440 * 30));
        } else if (jp === 'YEARLY') {
            unit = 'Tahun';
            displayDurasi = Math.ceil(h.DurasiMenit / (1440 * 365));
        } else if (jp === 'PAYPERUSE') {
            unit = '';
        }

        const durasiLabel = h.IsLiveDuration
            ? h.NamaPaket + ' / ' + displayDurasi + ' ' + unit + ' <em style="color:#e65100;font-size:0.82rem;">(berjalan)</em>'
            : h.NamaPaket + ' / ' + displayDurasi + ' ' + unit;
        $('#mdPaketDurasi').html(durasiLabel);
        $('#mdHargaSatuan').text(formatRp(h.HargaPaket));
        
        
        $('#mdDiskon').text('- ' + formatRp(h.TotalDiskon));
        $('#mdPajak').text(formatRp(h.TotalPajak));
        $('#mdPajakHiburan').text(formatRp(h.TotalPajakHiburan));
        $('#mdLayanan').text(formatRp(h.TotalLayanan));

        // Group FnB by NoTransaksi
        const groupedFnb = {};
        res.fnb.forEach(item => {
            if (!groupedFnb[item.NoTransaksi]) {
                groupedFnb[item.NoTransaksi] = {
                    TglTransaksi: item.TglTransaksi,
                    items: []
                };
            }
            groupedFnb[item.NoTransaksi].items.push(item);
        });

        // FnB List with Grouping (Treeview)
        let fnbHtml = '';
        let fnbTotal = 0;
        
        Object.keys(groupedFnb).forEach(noTrans => {
            const group = groupedFnb[noTrans];
            const tgl = group.TglTransaksi ? group.TglTransaksi.substring(0, 10) : '-';
            
            let subtotalGroup = 0;
            let groupItemsHtml = '';
            let groupStatus = 'C'; // Default to Paid, we'll check if any item is 'O' or null

            // Group Items (Treeview Children)
            group.items.forEach(item => {
                const totalItem = parseFloat(item.Harga || 0) * parseFloat(item.Qty || 0);
                subtotalGroup += totalItem;

                // jika LineStatus = 'C' maka transaksi itu terbayar, jika 'O' atau null belum terbayar
                // Jika ada satu saja yang bukan 'C', anggap grup belum terbayar (atau check first item)
                if (item.LineStatus !== 'C') {
                    groupStatus = 'O';
                }

                groupItemsHtml += `
                <tr class="fnb-details-row fnb-group-${noTrans}">
                    <td>${item.NamaItem || item.KodeItem}</td>
                    <td>${item.Qty}</td>
                    <td>${formatRp(item.Harga)}</td>
                    <td style="text-align:right;">${formatRp(totalItem)}</td>
                </tr>`;
            });

            fnbTotal += subtotalGroup;

            const statusLabel = groupStatus === 'C' ? 'TERBAYAR' : 'BELUM TERBAYAR';
            const statusColor = groupStatus === 'C' ? '#2e7d32' : '#c62828';
            const statusBg = groupStatus === 'C' ? '#e8f5e9' : '#ffebee';

            const reprintBtn = groupStatus === 'C' ? `<button class="btn-reprint" onclick="event.stopPropagation(); showReceiptPreview('${noTrans}')" title="Reprint Struk"><i class="fas fa-print"></i></button>` : '';

            // Header Group (Treeview Parent)
            fnbHtml += `
            <tr class="fnb-group-header expanded" onclick="toggleFnbGroup('${noTrans}', this)">
                <td colspan="3">
                    <i class="fas fa-chevron-right"></i> 
                    ${noTrans} 
                    <span style="font-weight:400; font-size:0.75rem; color:#666; margin-left:10px;">(${tgl})</span>
                    <span style="margin-left:10px; font-size:0.7rem; padding:2px 8px; border-radius:12px; background:${statusBg}; color:${statusColor}; border:1px solid ${statusColor}; font-weight:700;">
                        ${statusLabel}
                    </span>
                    ${reprintBtn}
                </td>
                <td style="text-align:right;">${formatRp(subtotalGroup)}</td>
            </tr>`;
            
            fnbHtml += groupItemsHtml;
        });

        $('#mdFnBList').html(fnbHtml || '<tr><td colspan="4" style="text-align:center; color:#999;">Tidak ada pesanan FnB</td></tr>');

        $('#fnbCountBadge').text(res.fnb.length + ' Item');
        $('#mdTotalMakanan').text(formatRp(fnbTotal));

        // Final Grand Total = Paket + FnB
        const grandTotalAll = (h.GrandTotal || 0);
        $('#mdTotalPaket').text(formatRp(h.TotalPaket - fnbTotal));
        
        // Add Reprint button for main packet if paid
        console.log(h);
        if (h.PacketInvoiceNo) {
            $('#mdTotalPaket').append(`<button class="btn-reprint" onclick="showReceiptPreview('${h.NoTransaksi}')" title="Reprint Struk Paket"><i class="fas fa-print"></i></button>`);
        }
        
        $('#mdGrandTotal').text(formatRp(grandTotalAll));

        // Payment Info
        window._mdOutstanding = p.Outstanding || 0;

        // Jika order masih aktif berjalan (JamSelesai null ATAU live durasi), sembunyikan pembayaran
        let isStillRunning = !h.JamSelesai || h.IsLiveDuration;
        
        // Aturan khusus MENITREALTIME: Sembunyikan payment kecuali sudah checkout (Status -1)
        if (h.JenisPaket === 'MENITREALTIME') {
            isStillRunning = (h.Status != -1);
        }

        console.log(p.NeedsPayment);
        console.log(isStillRunning);

        if (p.NeedsPayment && !isStillRunning) {
            $('#mdSumTagihan').text(formatRp(p.TotalTagihanAktual));
            $('#mdSumTerbayar').text(formatRp(p.TotalTerbayar));
            $('#mdSumOutstanding').text(formatRp(p.Outstanding));
            // Pre-fill nominal to outstanding
            document.getElementById('mdNominalBayar').value = formatRupiahVal(p.Outstanding);
            $('#mdPaymentSection').show();
            $('#mdBtnCheckOut').show().prop('disabled', false);
            onDetailMetodeChange(); // Trigger admin fee calc on open
        } else {
            console.log('disable')
            $('#mdPaymentSection').hide();
            $('#mdBtnCheckOut').show().prop('disabled', true);
        }

        // Sync with Customer Display (full detail)
        const syncData = {
            data: [],
            Total: h.TotalPaket,
            Discount: h.TotalDiskon,
            Tax: h.TotalPajak + h.TotalPajakHiburan + h.TotalLayanan,
            Net: grandTotalAll
        };
        // Add Paket if exists
        if (h.NamaPaket) {
            syncData.data.push({ NamaItem: h.NamaPaket, Qty: 1, Harga: h.HargaPaket });
        }
        // Add FnB items
        res.fnb.forEach(item => {
            syncData.data.push({ NamaItem: item.NamaItem || item.KodeItem, Qty: item.Qty, Harga: item.Harga });
        });
        syncCustomerDisplay(syncData);
    }

    function toggleFnbGroup(noTrans, elm) {
        const rows = document.querySelectorAll('.fnb-group-' + noTrans);
        const isExpanded = elm.classList.contains('expanded');
        
        if (isExpanded) {
            rows.forEach(r => r.style.display = 'none');
            elm.classList.remove('expanded');
        } else {
            rows.forEach(r => r.style.display = 'table-row');
            elm.classList.add('expanded');
        }
    }

    function showReceiptPreview(noFaktur) {
        if (!noFaktur) return;

        // Show loading
        swal({
            title: "Memuat Struk...",
            text: "Mohon tunggu sebentar",
            type: "info",
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: '/billing/get-faktur-detail',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                NoTransaksi: noFaktur
            },
            success: function(res) {
                swal.close();
                if (res.success) {
                    populateReceipt(res);
                    $('#modalReceiptPreview').addClass('open');
                    
                    // Otomatis cetak setelah modal terbuka
                    setTimeout(() => {
                        window.print();
                    }, 500);
                } else {
                    swal("Gagal", res.message, "error");
                }
            },
            error: function() {
                swal.close();
                swal("Error", "Gagal mengambil data struk", "error");
            }
        });
    }

    function populateReceipt(res) {
        const h = res.header;
        const d = res.details;
        const c = res.company;

        // Company
        $('#rcptCompanyName').text(c ? c.NamaPartner : "D'BILLIARD");
        $('#rcptCompanyAddress').text(c ? c.Alamat : "");
        $('#rcptCompanyPhone').text(c ? "Telp: " + c.NoTlp : "");

        // Header Info
        $('#rcptNoFaktur').text("#" + h.NoTransaksi);
        
        // Date formatting
        const dateObj = new Date(h.TglTransaksi);
        const dateStr = dateObj.toLocaleDateString('id-ID') + ' ' + dateObj.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        $('#rcptDate').text(dateStr);
        
        $('#rcptTable').text(h.NamaTitikLampu || '-');
        $('#rcptCustomer').text(h.NamaPelanggan || 'Umum');
        $('#rcptCustomerEmail').text(h.Email || '');

        // Items
        let itemsHtml = '';
        d.forEach(item => {
            itemsHtml += `
                <tr>
                    <td>
                        <div style="font-weight:600;">${item.NamaItem || item.Keterangan || item.KodeItem}</div>
                        <div style="font-size:0.75rem;">${formatRp(item.Harga)}</div>
                    </td>
                    <td style="text-align:center;">${item.Qty}</td>
                    <td style="text-align:right;">${formatRp(item.HargaNet)}</td>
                </tr>
            `;
        });
        $('#rcptItems').html(itemsHtml);

        // Totals
        $('#rcptSubtotal').text(formatRp(h.TotalTransaksi));
        
        if (parseFloat(h.Potongan || 0) > 0) {
            $('#rcptRowDiskon').show();
            $('#rcptDiskon').text("- " + formatRp(h.Potongan));
        } else {
            $('#rcptRowDiskon').hide();
        }

        $('#rcptPajak').text(formatRp(h.Pajak));

        if (parseFloat(h.PajakHiburan || 0) > 0) {
            $('#rcptRowPajakHiburan').show();
            $('#rcptPajakHiburan').text(formatRp(h.PajakHiburan));
        } else {
            $('#rcptRowPajakHiburan').hide();
        }

        $('#rcptLayanan').text(formatRp(h.BiayaLayanan));
        $('#rcptGrandTotal').text(formatRp(h.TotalPembelian));

        // Payment
        $('#rcptMetode').text(h.NamaMetodePembayaran || h.MetodeBayar || '-');
        $('#rcptBayar').text(formatRp(h.Bayar || h.TotalPembelian));
        $('#rcptKembali').text(formatRp(Math.max(0, h.Kembali || 0)));
    }

    function closeReceiptModal() {
        $('#modalReceiptPreview').removeClass('open');
        // Smooth refresh status without reloading page
        refreshTableStatuses();
    }

    function shareReceiptWhatsApp() {
        const noFaktur = $('#rcptNoFaktur').text().replace('#', '').trim();
        const total = $('#rcptGrandTotal').text().trim();
        const date = $('#rcptDate').text().trim();
        const company = $('#rcptCompanyName').text().trim();
        const customer = $('#rcptCustomer').text().trim();
        
        const text = `Halo, berikut adalah struk digital Anda:\n\n*${company}*\nNo: ${noFaktur}\nTanggal: ${date}\nPelanggan: ${customer}\n*Total: ${total}*\n\nTerima kasih atas kunjungan Anda!`;
        const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
        window.open(url, '_blank');
    }

    function sendReceiptEmail() {
        const noFaktur = $('#rcptNoFaktur').text().replace('#', '').trim();
        const defaultEmail = $('#rcptCustomerEmail').text().trim();
        
        swal({
            title: 'Kirim Struk via Email',
            text: 'Masukkan alamat email penerima:',
            input: 'email',
            inputValue: defaultEmail,
            inputPlaceholder: 'email@example.com',
            showCancelButton: true,
            confirmButtonColor: '#1a237e',
            confirmButtonText: 'Kirim Sekarang',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alamat email harus diisi!'
                }
            }
        }).then((result) => {
            if (result.value) {
                // Show loading
                swal({
                    title: "Sedang Mengirim...",
                    text: "Mohon tunggu sebentar",
                    type: "info",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                $.ajax({
                    url: '{{ route("billing-send-receipt-email") }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        NoTransaksi: noFaktur,
                        Email: result.value
                    },
                    success: function(res) {
                        if (res.success) {
                            swal("Berhasil", res.message, "success");
                        } else {
                            swal("Gagal", res.message, "error");
                        }
                    },
                    error: function() {
                        swal("Error", "Gagal menghubungi server.", "error");
                    }
                });
            }
        });
    }

    function printReceipt() {
        const receiptContent = document.getElementById('receiptContent').innerHTML;
        const companyName = document.getElementById('rcptCompanyName').innerText;
        const noFaktur = document.getElementById('rcptNoFaktur').innerText;
        
        const printWindow = window.open('', '_blank', 'width=400,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Receipt - ${noFaktur}</title>
                    <style>
                        body {
                            font-family: 'Courier New', Courier, monospace;
                            margin: 0;
                            padding: 20px;
                            width: 80mm;
                            background: white;
                        }
                        .receipt-header { text-align: center; margin-bottom: 15px; }
                        .receipt-logo { font-size: 1.4rem; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
                        .receipt-address { font-size: 0.85rem; line-height: 1.2; margin-bottom: 2px; }
                        .receipt-divider { border-top: 1px dashed #000; margin: 10px 0; }
                        .receipt-info { font-size: 0.85rem; margin-bottom: 10px; }
                        .receipt-info-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
                        .receipt-table { width: 100%; font-size: 0.85rem; border-collapse: collapse; }
                        .receipt-table th { text-align: left; border-bottom: 1px dashed #000; padding-bottom: 5px; }
                        .receipt-table td { padding: 4px 0; vertical-align: top; }
                        .receipt-totals { margin-top: 10px; font-size: 0.9rem; }
                        .receipt-total-row { display: flex; justify-content: space-between; margin-bottom: 3px; }
                        .receipt-grand-total { font-size: 1.1rem; font-weight: 700; margin-top: 5px; padding-top: 5px; border-top: 1px double #000; }
                        .receipt-footer { text-align: center; font-size: 0.8rem; margin-top: 20px; font-style: italic; }
                        @media print {
                            body { padding: 0; margin: 0; width: 80mm; }
                        }
                    </style>
                </head>
                <body>
                    <div class="receipt-paper">
                        ${receiptContent}
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            // window.close(); // Optional: close window after print
                        };
                    <\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
    }



    function onDetailMetodeChange() {
        const sel = document.getElementById('mdMetodePembayaran');
        if (!sel) return;
        const opt = sel.options[sel.selectedIndex];
        const percent = parseFloat(opt.dataset.percent || 0);
        const rupiah  = parseFloat(opt.dataset.rupiah  || 0);
        const tipePembayaran = opt.getAttribute('data-tipe') || '';
        const outstanding = window._mdOutstanding || 0;

        let adminFee = 0;
        if (percent > 0) {
            adminFee = outstanding * (percent / 100);
        } else if (rupiah > 0) {
            adminFee = rupiah;
        }

        if (adminFee > 0) {
            $('#mdAdminFeeValue').text(formatRp(Math.round(adminFee)));
            $('#mdAdminFeeRow').show();
        } else {
            $('#mdAdminFeeRow').hide();
        }
        window._mdAdminFee = Math.round(adminFee);
        window._mdTipePembayaran = tipePembayaran;
        onDetailNominalChange(false);
    }

    function onDetailNominalChange(isFromInput = false) {
        const outstanding = window._mdOutstanding || 0;
        const adminFee = window._mdAdminFee || 0;
        const totalHarus = Math.round(outstanding + adminFee);
        const tipePembayaran = window._mdTipePembayaran || '';

        const nominalInp = document.getElementById('mdNominalBayar');
        if (tipePembayaran === 'NON TUNAI' || tipePembayaran === 'NONTUNAI') {
            nominalInp.value = new Intl.NumberFormat('id-ID').format(totalHarus);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f3f6f9';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) {
                nominalInp.value = new Intl.NumberFormat('id-ID').format(totalHarus);
            }
        }

        const nominal = parseFormattedRp(nominalInp.value || '0');
        const kembalian = nominal - totalHarus;
        $('#mdKembalian').text(formatRp(Math.max(0, kembalian)));
        if (kembalian < 0) {
            $('#mdKembalian').css('color', '#c62828').text('Kurang: ' + formatRp(Math.abs(kembalian)));
        } else {
            $('#mdKembalian').css('color', '#2e7d32');
        }
    }

    function formatRupiahVal(val) {
        // Returns formatted string for input field (no 'Rp' prefix)
        return new Intl.NumberFormat('id-ID').format(Math.round(val || 0));
    }

    function onCheckOutFromDetail() {
        closeDetailModal();
        setTimeout(() => {
            onCheckOut();
        }, 300);
    }

    function onBayarFromDetail() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Perhatian", "Transaksi tidak valid.", "warning");
            return;
        }

        const nominal = parseFormattedRp(document.getElementById('mdNominalBayar').value || '0');
        const outstanding = window._mdOutstanding || 0;
        const adminFee = window._mdAdminFee || 0;
        const totalHarus = Math.round(outstanding + adminFee);

        if (nominal < totalHarus) {
            swal("Perhatian", "Nominal bayar kurang dari total yang harus dibayar.", "warning");
            return;
        }

        const selMp = document.getElementById('mdMetodePembayaran');
        const mpId = selMp ? selMp.value : '';
        const mpNama = selMp ? selMp.options[selMp.selectedIndex].dataset.nama : '';

        // Determine if we need to ask about checkout
        const jamSelesaiStr = window._mdJamSelesai;
        var _nowLocal = new Date();
        let jamSelesaiDt = jamSelesaiStr ? new Date(jamSelesaiStr) : null;
        const isExpiredUnpaid = jamSelesaiDt && jamSelesaiDt < _nowLocal && window._mdHeaderStatus == -1;
        const shouldAskCheckout = !jamSelesaiDt || jamSelesaiDt > _nowLocal;

        function doSubmit(doCheckout) {
            const btn = document.getElementById('mdBtnCheckOut');
            const oldHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            fetch('{{ route("billing-pay-order-detail") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    NoTransaksi: selectedTitik.notransaksi,
                    MetodePembayaranId: mpId,
                    NominalBayar: nominal,
                    AdminFee: adminFee,
                    DoCheckout: doCheckout ? 1 : 0
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    if (res.snap_token) {
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menunggu Pembayaran...';
                        window.snap.pay(res.snap_token, {
                            onSuccess: function (result) {
                                fetch('{{ route("billing-midtrans-success") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: res.NoTransaksi,
                                        payment_type: 'PAY_DETAIL',
                                        DoCheckout: doCheckout ? 1 : 0
                                    })
                                })
                                .then(r => r.json())
                                .then(r => {
                                    closeDetailModal();
                                    showReceiptPreview(res.NoTransaksi);
                                });
                            },
                            onPending: function (result) {
                                swal("Info", "Pembayaran tertunda. Selesaikan pembayaran Anda.", "info").then(() => refreshTableStatuses());
                            },
                            onError: function (result) {
                                btn.disabled = false;
                                btn.innerHTML = oldHtml;
                                swal("Gagal", "Pembayaran gagal.", "error");
                            },
                            onClose: function () {
                                btn.disabled = false;
                                btn.innerHTML = oldHtml;
                                fetch('{{ route("billing-midtrans-cancel") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: res.NoTransaksi,
                                        payment_type: 'PAY_DETAIL'
                                    })
                                }).then(() => {
                                    swal("Batal", "Pembayaran dibatalkan.", "warning");
                                });
                            }
                        });
                    } else {
                        console.log("Manual Payment")
                        btn.disabled = false;
                        btn.innerHTML = oldHtml;
                        closeDetailModal();
                        showReceiptPreview(selectedTitik.notransaksi);
                    }
                } else {
                    btn.disabled = false;
                    btn.innerHTML = oldHtml;
                    swal("Gagal", res.message || "Terjadi kesalahan.", "error");
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = oldHtml;
                console.error(err);
                swal("Error", "Gagal menghubungi server.", "error");
            });
        }

        // Step 1: Konfirmasi pembayaran
        swal({
            title: "Konfirmasi Pembayaran",
            text: "Metode: " + mpNama + "\nNominal: " + formatRp(nominal),
            type: "question",
            showCancelButton: true,
            confirmButtonColor: "#1a237e",
            confirmButtonText: "Ya, Bayar",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (!result.value) return;

            if (shouldAskCheckout) {
                // Step 2: Tanya apakah mau checkout sekalian
                swal({
                    title: "Checkout Sekarang?",
                    text: "Order masih aktif. Apakah ingin menutup order sekalian setelah bayar?",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#2e7d32",
                    confirmButtonText: "Ya, Checkout Sekalian",
                    cancelButtonText: "Tidak, Bayar Saja"
                }).then((r2) => {
                    doSubmit(r2.value === true);
                });
            } else {
                // Order sudah expired — langsung proses, backend yang auto-checkout
                doSubmit(false);
            }
        });
    }

    let fnbCart = [];

    function onTambahMakan() {
        if (!selectedTitik) return;
        if (!selectedTitik.notransaksi) {
            swal("Perhatian", "Meja ini belum memiliki transaksi aktif.", "warning");
            return;
        }

        $('#mdFnbTitikNama').text(selectedTitik.namatitiklampu);
        fnbCart = [];
        updateFnbListQty();
        $('#fnbSearchInput').val('');
        
        // Reset payment radio
        $('input[name="FnbOpsiBayar"][value="NANTI"]').prop('checked', true);
        toggleFnbPaymentSection();

        $('#modalTambahMakanan').addClass('open');
    }

    function closeTambahMakananModal() {
        $('#modalTambahMakanan').removeClass('open');
    }

    function filterFnbGrid(query, gridId) {
        let q = query.toLowerCase();
        $(`#${gridId} > div`).each(function() {
            let name = $(this).data('name') || "";
            $(this).toggle(name.includes(q));
        });
    }

    // Legacy search functions removed. Using filterFnbGrid instead.

    function updateFnbListQty() {
        $('.fnb-qty-val').text(0);
        fnbCart.forEach(item => {
            $(`.fnb-qty-val[data-kode="${item.KodeItem}"]`).text(item.Qty);
        });
        
        let total = fnbCart.reduce((sum, i) => sum + (i.Qty * i.Harga), 0);
        $('#fnbTotalItem').text(formatRp(total));
        $('#fnbCalcSubtotal').text(formatRp(total));

        // Sync with Customer Display
        const syncData = {
            data: fnbCart.map(item => ({ NamaItem: item.NamaItem, Qty: item.Qty, Harga: item.Harga })),
            Total: total,
            Discount: 0,
            Tax: 0,
            Net: total
        };
        if (typeof syncCustomerDisplay === 'function') syncCustomerDisplay(syncData);
    }

    function addFnbToCart(item, delta = 1) {
        let existing = fnbCart.find(c => c.KodeItem === item.KodeItem);
        if (existing) {
            existing.Qty += delta;
            if (existing.Qty < 1) {
                fnbCart = fnbCart.filter(c => c.KodeItem !== item.KodeItem);
            }
        } else if (delta > 0) {
            fnbCart.push({
                KodeItem: item.KodeItem,
                NamaItem: item.NamaItem,
                Harga: item.HargaJual,
                Qty: delta
            });
        }
        
        updateFnbListQty();
        calculateFnbTotal();
    }

    function toggleFnbPaymentSection() {
        let opsi = $('input[name="FnbOpsiBayar"]:checked').val();
        if (opsi === 'LANGSUNG') {
            $('#fnbPaymentSection').slideDown(200);
            calculateFnbTotal();
        } else {
            $('#fnbPaymentSection').slideUp(200);
        }
    }

    function calculateFnbTotal(isFromInput = false) {
        let totalPesanan = fnbCart.reduce((sum, item) => sum + (item.Qty * item.Harga), 0);
        let ppnPersen = parseFloat($('#fnbCalcPpnPersen').text()) || 0;
        let servicePersen = parseFloat($('#fnbCalcServicePersen').text()) || 0;

        let ppnRp = totalPesanan * (ppnPersen / 100);
        let serviceRp = totalPesanan * (servicePersen / 100);

        let $mp = $('#fnbMetodePembayaran option:selected');
        let adminPercent = parseFloat($mp.data('percent')) || 0;
        let adminRupiah = parseFloat($mp.data('rupiah')) || 0;
        let tipePembayaran = $mp.data('tipe') || '';

        let subtotalWithTax = totalPesanan + ppnRp + serviceRp;
        let adminFee = 0;
        if (adminPercent > 0) adminFee = subtotalWithTax * (adminPercent / 100);
        else if (adminRupiah > 0) adminFee = adminRupiah;

        let grandTotal = Math.round(subtotalWithTax + adminFee);

        $('#fnbCalcSubtotal').text(formatRp(Math.round(totalPesanan)));
        $('#fnbCalcPpnRp').text(formatRp(Math.round(ppnRp)));
        $('#fnbCalcServiceRp').text(formatRp(Math.round(serviceRp)));
        $('#fnbCalcAdminRp').text(formatRp(Math.round(adminFee)));
        $('#rowFnbAdmin').toggle(adminFee > 0);
        $('#fnbCalcGrandTotal').text(formatRp(grandTotal));

        const nominalInp = document.getElementById('fnbNominalBayar');
        if (tipePembayaran === 'NON TUNAI' || tipePembayaran === 'NONTUNAI') {
            nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f3f6f9';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) {
                nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
            }
        }

        let nominal = parseFormattedRp(nominalInp.value || '0');
        let kembalian = nominal - grandTotal;
        $('#fnbKembalian').val(formatRp(Math.max(0, kembalian)));

        if (nominal < grandTotal && $('input[name="FnbOpsiBayar"]:checked').val() === 'LANGSUNG') {
            $('#fnbKembalian').val('Kurang: ' + formatRp(Math.abs(kembalian))).css('color', 'red');
        } else {
            $('#fnbKembalian').css('color', 'green');
        }

    }

    /* Logic for FnB Ordering inside Pilih Paket Modal */
    let ppFnbCart = [];

    // Legacy search functions removed. Using filterFnbGrid instead.

    function updatePpFnbListQty() {
        $('.pp-fnb-qty-val').text(0);
        ppFnbCart.forEach(item => {
            $(`.pp-fnb-qty-val[data-kode="${item.KodeItem}"]`).text(item.Qty);
        });
        
        let fnbTotal = ppFnbCart.reduce((acc, item) => acc + (item.Qty * item.Harga), 0);
        $('#ppSumFnb').text(formatRp(fnbTotal));
    }

    function addPpFnbToCart(item, delta = 1) {
        let existing = ppFnbCart.find(c => c.KodeItem === item.KodeItem);
        if (existing) {
            existing.Qty += delta;
            if (existing.Qty < 1) {
                ppFnbCart = ppFnbCart.filter(c => c.KodeItem !== item.KodeItem);
            }
        } else if (delta > 0) {
            ppFnbCart.push({
                KodeItem: item.KodeItem,
                NamaItem: item.NamaItem,
                Harga: item.HargaJual,
                Qty: delta
            });
        }
        
        updatePpFnbListQty();
        calculateTotal();
    }




    function syncSnapToken(token) {
        if (!token) return;
        localStorage.setItem('PoSSnapToken', JSON.stringify({ token: token, timestamp: Date.now() }));
    }

    function submitFnbOrder() {
        if (fnbCart.length === 0) {
            swal("Error", "Silahkan pilih item terlebih dahulu.", "error");
            return;
        }

        let payload = {
            NoTransaksi: selectedTitik.notransaksi,
            items: [...fnbCart],
            OpsiBayar: $('input[name="FnbOpsiBayar"]:checked').val(),
            MetodePembayaran: $('#fnbMetodePembayaran').val(),
            NominalBayar: parseFormattedRp($('#fnbNominalBayar').val() || '0'),
            ServiceType: $('input[name="fnbServiceType"]:checked').val()
        };

        if (payload.OpsiBayar === 'LANGSUNG') {
            let gt = parseFormattedRp($('#fnbCalcGrandTotal').text());
            if (payload.NominalBayar < gt) {
                swal("Error", "Nominal bayar kurang dari Grand Total.", "error");
                return;
            }
        }

        swal({
            title: "Konfirmasi",
            text: "Simpan pesanan FnB ini?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Simpan",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (!result.value) return;

            const $btn = $('#btnConfirmFnb');
            const oldHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            console.log("SUBMITTING FNB ORDER - Payload:", payload);
            console.log("FNB CART STATUS:", fnbCart);

            fetch("/billing/store-fnb", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    if (res.snap_token) {
                        $btn.html('<i class="fas fa-spinner fa-spin"></i> Menunggu Bayar...');
                        
                        const handlers = {
                            onSuccess: function (result) {
                                fetch('/billing/midtrans-success', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: res.NoTransaksi,
                                        payment_type: 'ADD_FNB',
                                        NominalBayar: payload.NominalBayar
                                    })
                                })
                                .then(r => r.json())
                                .then(r => {
                                    closeTambahMakananModal();
                                    showReceiptPreview(res.NoTransaksi);
                                });
                            },
                            onPending: function (result) {
                                swal("Info", "Selesaikan pembayaran.", "info").then(() => refreshTableStatuses());
                            },
                            onError: function (result) {
                                $btn.prop('disabled', false).html(oldHtml);
                                swal("Error", "Pembayaran gagal.", "error");
                            },
                            onClose: function () {
                                $btn.prop('disabled', false).html(oldHtml);
                                fetch('/billing/midtrans-cancel', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: res.NoTransaksi,
                                        payment_type: 'ADD_FNB'
                                    })
                                }).then(() => {
                                    swal("Batal", "Pembayaran dibatalkan.", "warning");
                                });
                            }
                        };

                        // Show Snap directly on billing
                        window.snap.pay(res.snap_token, handlers);
                    } else {
                        $btn.prop('disabled', false).html(oldHtml);
                        closeTambahMakananModal();
                        if (res.NoTransaksi) {
                            showReceiptPreview(res.NoTransaksi);
                        } else {
                            refreshTableStatuses();
                        }
                    }
                } else {
                    $btn.prop('disabled', false).html(oldHtml);
                    swal("Gagal", res.message, "error");
                }
            })
            .catch(err => {
                $btn.prop('disabled', false).html(oldHtml);
                swal("Error", "Terjadi kesalahan sistem.", "error");
            });
        });
    }

    function onTambahJam() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Peringatan", "Pilih meja yang sedang aktif terlebih dahulu", "warning");
            return;
        }

        document.getElementById('mdDurasiTitikNama').textContent = selectedTitik.namatitiklampu;
        
        // Populate packets with same unit
        const selTdPaket = document.getElementById('tdPaketId');
        selTdPaket.innerHTML = '';
        
        const currentJenis = selectedTitik.jenispaket;
        const category = selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";

        dataPaketAll.forEach(p => {
            if (p.JenisPaket === currentJenis) {
                // Filter berdasarkan kategori meja
                if (category === "" || p.NamaPaket.toUpperCase().includes(category)) {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.text = p.NamaPaket;
                    opt.dataset.harga = p.HargaNormal || 0;
                    opt.dataset.durasi = p.DurasiPaket || 1;
                    selTdPaket.appendChild(opt);
                }
            }
        });

        if (selTdPaket.options.length === 0) {
            swal("Info", "Tidak ada paket tambahan yang tersedia untuk jenis paket ini.", "info");
            return;
        }

        // Set Unit Label
        let unit = 'Jam';
        if (currentJenis.includes('MENIT')) unit = 'Menit';
        else if (currentJenis === 'DAILY') unit = 'Hari';
        else if (currentJenis === 'MONTHLY') unit = 'Bulan';
        else if (currentJenis === 'YEARLY') unit = 'Tahun';
        document.getElementById('tdUnitLabel').textContent = unit;

        // Reset fields
        document.getElementById('tdDurasi').value = 1;
        document.querySelector('input[name="TdOpsiBayar"][value="NANTI"]').checked = true;
        toggleTdPaymentSection();
        
        calculateTambahDurasi();

        document.getElementById('modalTambahDurasi').classList.add('open');
    }

    function closeTambahDurasiModal() {
        document.getElementById('modalTambahDurasi').classList.remove('open');
    }

    function toggleTdPaymentSection() {
        const opsi = document.querySelector('input[name="TdOpsiBayar"]:checked').value;
        const section = document.getElementById('tdPaymentSection');
        section.style.display = (opsi === 'LANGSUNG') ? 'block' : 'none';
        calculateTambahDurasi();
    }

    function adjTambahDurasi(val) {
        const inp = document.getElementById('tdDurasi');
        let v = parseInt(inp.value) || 1;
        v += val;
        if (v < 1) v = 1;
        inp.value = v;
        calculateTambahDurasi();
    }

    function onTambahDurasiPaketChange() {
        calculateTambahDurasi();
    }

    function calculateTambahDurasi(isFromInput = false) {
        const sel = document.getElementById('tdPaketId');
        if (!sel.selectedOptions[0]) return;

        const opt = sel.selectedOptions[0];
        const harga = parseFloat(opt.dataset.harga) || 0;
        const durasi = parseInt(document.getElementById('tdDurasi').value) || 1;

        const subtotal = harga * durasi;
        
        const ppnPersen = parseFloat(document.getElementById('tdCalcPpnPersen').textContent) || 0;
        const servicePersen = parseFloat(document.getElementById('tdCalcServicePersen').textContent) || 0;

        const ppnRp = Math.round(subtotal * (ppnPersen / 100));
        const serviceRp = Math.round(subtotal * (servicePersen / 100));

        // Admin Fee from Payment Method
        let adminRp = 0;
        const mpSel = document.getElementById('tdMetodePembayaran');
        let tipePembayaran = '';
        if (mpSel && mpSel.selectedOptions[0]) {
            const mpOpt = mpSel.selectedOptions[0];
            tipePembayaran = mpOpt.getAttribute('data-tipe');
            const pAdmin = parseFloat(mpOpt.dataset.percent) || 0;
            const rAdmin = parseFloat(mpOpt.dataset.rupiah) || 0;
            adminRp = Math.round(subtotal * (pAdmin / 100)) + rAdmin;
        }

        const isLangsung = document.querySelector('input[name="TdOpsiBayar"]:checked').value === 'LANGSUNG';
        
        document.getElementById('tdCalcSubtotal').textContent = formatRupiahVal(subtotal);
        document.getElementById('tdCalcPpnRp').textContent = formatRupiahVal(ppnRp);
        document.getElementById('tdCalcServiceRp').textContent = formatRupiahVal(serviceRp);
        
        if (adminRp > 0) {
            document.getElementById('rowTdAdmin').style.display = 'flex';
            document.getElementById('tdCalcAdminRp').textContent = formatRupiahVal(adminRp);
        } else {
            document.getElementById('rowTdAdmin').style.display = 'none';
        }

        const grandTotal = subtotal + ppnRp + serviceRp + (isLangsung ? adminRp : 0);
        document.getElementById('tdCalcGrandTotal').textContent = formatRupiahVal(grandTotal);

        if (isLangsung) {
            const nominalInp = document.getElementById('tdNominalBayar');
            
            if (tipePembayaran === 'NON TUNAI' || tipePembayaran === 'NONTUNAI') {
                nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
                nominalInp.readOnly = true;
                nominalInp.style.backgroundColor = '#f3f6f9';
            } else {
                nominalInp.readOnly = false;
                nominalInp.style.backgroundColor = '';
                if (!isFromInput) {
                    nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
                }
            }

            const bayar = parseFormattedRp(nominalInp.value);
            const kembali = bayar - grandTotal;
            document.getElementById('tdKembalian').value = formatRupiahVal(kembali);
        }

        // Sync with Customer Display
        const syncData = {
            data: [{ NamaItem: "Tambah Durasi: " + sel.options[sel.selectedIndex].text, Qty: durasi, Harga: harga }],
            Total: subtotal,
            Discount: 0,
            Tax: ppnRp + serviceRp + (isLangsung ? adminRp : 0),
            Net: grandTotal
        };
        syncCustomerDisplay(syncData);
    }

    function submitTambahDurasi() {
        if (!selectedTitik || !selectedTitik.notransaksi) return;

        const paketId = document.getElementById('tdPaketId').value;
        const durasi = document.getElementById('tdDurasi').value;
        const opsiBayar = document.querySelector('input[name="TdOpsiBayar"]:checked').value;
        const mpId = document.getElementById('tdMetodePembayaran').value;
        const nominalBayar = parseFormattedRp(document.getElementById('tdNominalBayar').value);

        if (!paketId) {
            swal("Peringatan", "Pilih paket terlebih dahulu", "warning");
            return;
        }

        if (opsiBayar === 'LANGSUNG') {
            const grandTotalStr = document.getElementById('tdCalcGrandTotal').textContent;
            const grandTotal = parseFormattedRp(grandTotalStr);
            if (nominalBayar < grandTotal) {
                swal("Peringatan", "Nominal bayar tidak boleh kurang dari total tagihan", "warning");
                return;
            }
        }

        const $btn = $('#btnConfirmTambahDurasi');
        const oldHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("billing-store-tambah-durasi") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                NoTransaksi: selectedTitik.notransaksi,
                PaketId: paketId,
                Durasi: durasi,
                OpsiBayar: opsiBayar,
                MetodePembayaran: mpId,
                NominalBayar: nominalBayar
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (res.snap_token) {
                    $btn.html('<i class="fas fa-spinner fa-spin"></i> Menunggu Bayar...');
                    
                    const handlers = {
                        onSuccess: function (result) {
                            fetch('{{ route("billing-midtrans-success") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({ 
                                    NoTransaksi: res.NoTransaksi,
                                    payment_type: 'ADD_DURATION',
                                    DurasiBaru: res.DurasiBaru,
                                    PaketId: res.PaketId,
                                    NominalBayar: nominalBayar
                                })
                            })
                            .then(r => r.json())
                            .then(r => {
                                closeTambahDurasiModal();
                                showReceiptPreview(res.NoTransaksi);
                            });
                        },
                        onPending: function (result) {
                            swal("Info", "Selesaikan pembayaran.", "info").then(() => refreshTableStatuses());
                        },
                        onError: function (result) {
                            $btn.prop('disabled', false).html(oldHtml);
                            swal("Error", "Pembayaran gagal.", "error");
                        },
                        onClose: function () {
                            $btn.prop('disabled', false).html(oldHtml);
                            fetch('{{ route("billing-midtrans-cancel") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({ 
                                    NoTransaksi: res.NoTransaksi,
                                    payment_type: 'ADD_DURATION'
                                })
                            }).then(() => {
                                swal("Batal", "Pembayaran dibatalkan.", "warning");
                            });
                        }
                    };

                    // Decide where to show Snap
                    const $mp = $('#tdMetodePembayaran option:selected');
                    if (isCustDisplayOpen() && $mp.data('verifikasi') === 'AUTO') {
                        syncSnapToken(res.snap_token, handlers);
                    } else {
                        window.snap.pay(res.snap_token, handlers);
                    }
                } else {
                    $btn.prop('disabled', false).html(oldHtml);
                    closeTambahDurasiModal();
                    if (res.NoTransaksi) {
                        showReceiptPreview(res.NoTransaksi);
                    } else {
                        closePilihPaketModal();
                        refreshTableStatuses();
                    }
                }
            } else {
                $btn.prop('disabled', false).html(oldHtml);
                swal("Gagal", res.message, "error");
            }
        })
        .catch(err => {
            $btn.prop('disabled', false).html(oldHtml);
            swal("Error", "Terjadi kesalahan sistem.", "error");
        });
    }

    function onTambahLayanan() {
        if (!selectedTitik) return;
        
        // Cek apakah meja punya JamSelesai yang valid (bukan null/kosong)
        let jamMulaiBaru = '';
        let tglMulaiBaru = new Date();

        if (selectedTitik.jamselesai && selectedTitik.jamselesai !== '-' && selectedTitik.jamselesai !== '') {
            // Kita ambil dari data mentah rawjamselesai ("YYYY-MM-DD HH:mm:ss")
            const js = selectedTitik.rawjamselesai;
            console.log(selectedTitik);
            if (js) {
                const dt = new Date(js);
                dt.setMinutes(dt.getMinutes() + 10); // Tambah 10 menit dari waktu selesai sebelumnya
                jamMulaiBaru = String(dt.getHours()).padStart(2, '0') + ':' + String(dt.getMinutes()).padStart(2, '0');
                tglMulaiBaru = dt;
            }
        }

        if (!jamMulaiBaru) {
            swal("Peringatan", "Layanan saat ini tidak memiliki batas waktu selesai yang pasti (misal: Realtime). + Layanan secara berurutan tidak dapat dilakukan.", "warning");
            return;
        }

        // Jalankan reset normal modal pilih paket
        document.getElementById('modalPaketTitikNama').textContent = selectedTitik.namatitiklampu;
        document.getElementById('ppTglTransaksi').valueAsDate = tglMulaiBaru; // Set ke tanggal selesai paket sebelumnya
        
        var selJenis = document.getElementById('ppJenisPaket');
        selJenis.value = '';
        onJenisPaketChange(''); 

        document.getElementById('ppPaketId').innerHTML = '<option value="">-- Pilih Paket --</option>';
        document.getElementById('ppHargaNormal').value = '';
        document.getElementById('ppDurasi').value = '1';
        document.getElementById('ppMemberSearch').value = '';
        // document.getElementById('ppKodeSales').value = '';

        // Tembak Jam Mulai dan KUNCI
        const elmJamMulai = document.getElementById('ppJamMulai');
        elmJamMulai.value = jamMulaiBaru;
        elmJamMulai.readOnly = true;
        elmJamMulai.style.backgroundColor = '#e0e0e0';

        validateForm();
        document.getElementById('modalPilihPaket').classList.add('open');
    }
    </script>

    <!-- ===== MODAL PILIH PAKET ===== -->
    <div id="modalPilihPaket" class="pp-overlay">
        <div class="pp-modal">

            <!-- Header -->
            <div class="pp-header">
                <div>
                    <div class="pp-header-label"><i class="fas fa-play-circle"></i> Pilih Paket</div>
                    <div class="pp-header-titik" id="modalPaketTitikNama">--</div>
                </div>
                <button class="pp-close" onclick="closePilihPaketModal()">&times;</button>
            </div>

            <!-- Body -->
            <div class="pp-body">
                <form id="frmPilihPaketNew" autocomplete="off">

                    <!-- Row: Tanggal -->
                    <div class="pp-row">
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-calendar-alt"></i> Tanggal Transaksi</label>
                            <input type="date" class="pp-input" id="ppTglTransaksi" name="TglTransaksi">
                        </div>
                    </div>

                    <!-- Row: Jenis Paket + Paket -->
                    <div class="pp-row pp-row-2">
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-tags"></i> Jenis Paket</label>
                            <select class="pp-input" id="ppJenisPaket" name="JenisPaket" onchange="onJenisPaketChange(this.value)">
                                <option value="">-- Pilih Jenis --</option>
                                @if(isset($jenisLangganan) && count($jenisLangganan) > 0)
                                    @foreach($jenisLangganan as $jl)
                                        @php
                                            $val = is_array($jl) ? $jl['Kode'] : $jl;
                                            $lbl = is_array($jl) ? $jl['Nama'] : $jl;
                                        @endphp
                                        <option value="{{ $val }}">{{ $lbl }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-box"></i> Paket</label>
                            <select class="pp-input" id="ppPaketId" name="paketid">
                                <option value="">-- Pilih Paket --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row: Harga Normal + Durasi -->
                    <div class="pp-row pp-row-2">
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-money-bill-wave"></i> Harga</label>
                            <input type="text" class="pp-input" id="ppHargaNormal" name="HargaNormal" readonly style="background-color: #f5f5f5; cursor: not-allowed;" placeholder="Otomatis">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-hourglass-half"></i> Durasi</label>
                            <div class="pp-durasi-wrap">
                                <button type="button" class="pp-dur-btn" id="btnMinDurasi" onclick="changeDurasi(-1)">&#8722;</button>
                                <input type="number" class="pp-input pp-dur-input" id="ppDurasi" name="DurasiPaket" min="1" value="1">
                                <button type="button" class="pp-dur-btn" id="btnPlusDurasi" onclick="changeDurasi(1)">&#43;</button>
                            </div>
                        </div>
                    </div>

                    <!-- Row: Hari/Tanggal (for slot fetch) & Slot Container -->
                    <div class="pp-row" id="ppRowSlot" style="display:none;">
                        <div class="pp-field" style="margin-bottom: 10px;">
                            <label class="pp-label"><i class="fas fa-th-large"></i> Slot Tersedia</label>
                            <div id="ppSlotContainer" class="slot-container">
                                <div class="slot-loading">Memuat slot...</div>
                            </div>
                            <small id="ppSlotHelper" style="color: #546e7a; margin-top: 5px;">Pilih 1 atau lebih slot berurutan.</small>
                        </div>
                    </div>

                    <!-- Row: Jam Mulai (auto) + Jam Selesai (calculated) -->
                    <div class="pp-row pp-row-2" id="ppRowJamMulai">
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-play"></i> Jam Mulai</label>
                            <input type="text" class="pp-input" id="ppJamMulai" name="JamMulai" placeholder="HH:mm">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-stop"></i> Jam Selesai (Est.)</label>
                            <input type="text" class="pp-input" id="ppJamSelesai" readonly placeholder="-">
                        </div>
                    </div>

                    <!-- Row: Member -->
                    <div class="pp-row">
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-user"></i> Member (opsional)</label>
                            <input type="text" class="pp-input" id="ppMemberSearch" placeholder="Cari nama, ID, atau No. HP...">
                            <select class="pp-input mt-1" id="ppKodePelanggan" name="KodePelanggan" style="margin-top:6px;">
                                <option value="">-- Tidak ada / Umum --</option>
                                @foreach($pelanggan as $plg)
                                    <option value="{{ $plg->KodePelanggan }}">{{ $plg->NamaPelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(isset($company[0]) && $company[0]->JenisUsaha == 'Apotek')
                    <!-- Row: Apotek -->
                    <div class="pp-row">
                        <div class="pp-field">
                            <label class="pp-label" style="color: #d32f2f;"><i class="fas fa-stethoscope"></i> Dokter (Opsional)</label>
                            <input type="text" class="pp-input" id="ppNamaDokter" placeholder="Nama Dokter">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label" style="color: #d32f2f;"><i class="fas fa-user-injured"></i> Pasien (Opsional)</label>
                            <input type="text" class="pp-input" id="ppNamaPasien" placeholder="Nama Pasien">
                        </div>
                    </div>
                    <div class="pp-row">
                        <div class="pp-field">
                            <label class="pp-label" style="color: #d32f2f;"><i class="fas fa-file-medical"></i> No Resep</label>
                            <input type="text" class="pp-input" id="ppNoResep" placeholder="Boleh dikosongkan">
                        </div>
                    </div>
                    @endif


                    <!-- Row: Sales Guard -->
                    <div class="pp-row">
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-user-shield"></i> Service Guard</label>
                            <select class="pp-input" id="ppKodeSales" name="KodeSales" onchange="validateForm()" {{ (Auth::user()->KodeSales != '') ? 'disabled' : '' }}>
                                <option value="">-- Pilih Guard --</option>
                                @foreach($sales as $sls)
                                    <option value="{{ $sls->KodeSales }}" {{ $sls->KodeSales == Auth::user()->KodeSales ? 'selected' : '' }}>{{ $sls->NamaSales }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                    <!-- Row: FnB Ordering (Harmonized with Self-Service) -->
                    <div class="fnb-selection-area" style="margin-top: 15px;">
                        <div class="fnb-header">
                            <span style="font-size: 0.9rem;"><i class="fas fa-utensils"></i> PESAN MAKANAN & MINUMAN (OPSIONAL)</span>
                            <span id="ppFnbTotalCount" class="badge" style="background: #1a237e; color: #fff; font-size: 0.75rem; border-radius: 10px; padding: 4px 10px;">0 Item</span>
                        </div>
                        <div style="padding: 10px; border-bottom: 1px solid #eee; background: #fff;">
                            <input type="text" class="pp-input" id="ppFnbSearchInput" placeholder="Cari makanan/minuman..." onkeyup="filterFnbGrid(this.value, 'ppFnbMenuList')" style="border-radius: 20px; padding-left: 15px; height: 38px;">
                        </div>
                        
                        <div class="fnb-list-container" id="ppFnbMenuList">
                            @foreach($itemmaster as $item)
                                <div class="fnb-item" data-name="{{ strtolower($item->NamaItem) }}">
                                    <img src="{{ $item->Gambar ? (str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTUwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg==' }}" 
                                         class="fnb-item-img" 
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTUwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg=='">
                                    <div class="fnb-item-info">
                                        <div class="fnb-item-name">{{ $item->NamaItem }}</div>
                                        <div class="fnb-item-price">Rp {{ number_format($item->HargaJual) }}</div>
                                        <div class="fnb-item-stock">Stok: {{ $item->Stock }}</div>
                                    </div>
                                    <div class="fnb-qty-ctrl">
                                        <button type="button" class="fnb-qty-btn" onclick='addPpFnbToCart(@json($item), -1)'>-</button>
                                        <span class="fnb-qty-val pp-fnb-qty-val" data-kode="{{ $item->KodeItem }}">0</span>
                                        <button type="button" class="fnb-qty-btn" onclick='addPpFnbToCart(@json($item), 1)'>+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Row: Service Type (Dine In / Take Away) -->
                    <div class="pp-row" style="margin-top: 15px; background: #fffde7; padding: 10px; border-radius: 8px; border: 1px solid #fff9c4;">
                        <div class="pp-field">
                            <label class="pp-label" style="color: #f57f17;"><i class="fas fa-hand-holding-heart"></i> Tipe Layanan</label>
                            <div style="display: flex; gap: 20px; margin-top: 8px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; color: #1a237e;">
                                    <input type="radio" name="ppServiceType" value="DINE_IN" checked> Makan di Tempat
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; color: #e65100;">
                                    <input type="radio" name="ppServiceType" value="TAKE_AWAY"> Bawa Pulang
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Row: Opsi Bayar -->
                    <div class="pp-row" style="margin-top: 20px; border-top: 1px dashed #cfd8dc; padding-top: 15px;">
                        <div class="pp-field">
                            <label class="pp-label"><i class="fas fa-cash-register"></i> Pembayaran</label>
                            <div style="display: flex; gap: 15px; margin-top: 5px;">
                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                    <input type="radio" name="OpsiBayar" value="NANTI" checked onchange="toggleBayarLangsung()"> Bayar Nanti
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                    <input type="radio" name="OpsiBayar" value="LANGSUNG" onchange="toggleBayarLangsung()"> Bayar Langsung
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Bayar Langsung Container -->
                    <div id="ppDetailBayar" style="display: none; background: #f1f8e9; border: 1px solid #c5e1a5; border-radius: 8px; padding: 15px; margin-top: 15px;">
                        
                        <div class="detail-calc-row">
                            <span>Subtotal Paket (x<span id="calcQty">1</span>):</span>
                            <span id="calcSubtotal">Rp 0</span>
                        </div>
                        
                        <div class="detail-calc-row">
                            <span>Subtotal FnB:</span>
                            <span id="ppSumFnb">Rp 0</span>
                        </div>
                        
                        <div class="detail-calc-row">
                            <span>Diskon Member (<span id="calcDiskonPersen">0</span>%):</span>
                            <span id="calcDiskonRp" style="color: #e53935;">- Rp 0</span>
                        </div>
                        
                        <div class="pp-row mt-1" style="margin-bottom: 8px;">
                            <div class="pp-field">
                                <label class="pp-label" style="font-size: 0.75rem;">Kode Voucher</label>
                                <input type="text" class="pp-input" id="ppKodeVoucher" placeholder="Masukkan voucher..." oninput="onVoucherInput()" style="padding: 6px 10px;">
                                <div id="ppVoucherStatus" style="font-size: 0.75rem; margin-top: 4px;"></div>
                            </div>
                        </div>
                        
                        <div class="detail-calc-row">
                            <span>Voucher / Potongan Lain:</span>
                            <span id="calcVoucherRp" style="color: #e53935;">- Rp 0</span>
                        </div>

                        <div class="detail-calc-row">
                            <span>PPN (<span id="calcPpnPersen">0</span>%):</span>
                            <span id="calcPpnRp">Rp 0</span>
                        </div>
                        <div class="detail-calc-row">
                            <span>Pajak Hiburan (<span id="calcPb1Persen">0</span>%):</span>
                            <span id="calcPb1Rp">Rp 0</span>
                        </div>
                        <div class="detail-calc-row" id="rowBiayaAdmin" style="display: none;">
                            <span>Biaya Admin:</span>
                            <span id="calcAdminRp">Rp 0</span>
                        </div>
                        
                        <hr style="border-top: 1px dashed #aed581; border-bottom: none; margin: 10px 0;">

                        <div class="detail-calc-row" style="font-size: 1.1rem; font-weight: 700;">
                            <span>GRAND TOTAL:</span>
                            <span id="calcGrandTotal" style="color: #2e7d32;">Rp 0</span>
                        </div>

                        <div class="pp-row" style="margin-top: 15px;">
                            <div class="pp-field">
                                <label class="pp-label">Metode Pembayaran</label>
                                <select class="pp-input" id="ppMetodePembayaran" name="MetodePembayaran" onchange="calculateTotal()">
                                    @foreach($metodepembayaran as $mp)
                                        <option value="{{ $mp->id }}" 
                                            data-tipe="{{ $mp->TipePembayaran }}"
                                            data-percent="{{ $mp->BiayaAdminPercent ?? 0 }}"
                                            data-rupiah="{{ $mp->BiayaAdminRupiah ?? 0 }}"
                                            data-verifikasi="{{ $mp->MetodeVerifikasi }}">
                                            {{ $mp->NamaMetodePembayaran }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pp-row pp-row-2" style="margin-top: 10px;">
                            <div class="pp-field">
                                <label class="pp-label">Nominal Bayar</label>
                                <input type="text" class="pp-input" id="ppNominalBayar" oninput="formatRupiahInput(this); calculateTotal(true)" value="0">
                            </div>
                            <div class="pp-field">
                                <label class="pp-label">Kembalian</label>
                                <input type="text" class="pp-input" id="ppKembalian" readonly value="Rp 0">
                            </div>
                        </div>
                        
                    </div>

                </form>
            </div>

            <!-- Footer -->
            <div class="pp-footer">
                <button type="button" class="pp-btn-cancel" onclick="closePilihPaketModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="pp-btn-confirm" id="ppBtnConfirm" onclick="onKonfirmasiPaket()">
                    <i class="fas fa-check"></i> Konfirmasi & Mulai
                </button>
            </div>

        </div>
    </div>

    <!-- ===== MODAL TAMBAH MAKANAN ===== -->
    <div id="modalTambahMakanan" class="pp-overlay">
        <div class="pp-modal" style="max-width: 800px;">
            <!-- Header -->
            <div class="pp-header">
                <div>
                    <div class="pp-header-label"><i class="fas fa-utensils"></i> Tambah Makanan & Minuman</div>
                    <div class="pp-header-titik" id="mdFnbTitikNama">--</div>
                </div>
                <button class="pp-close" onclick="closeTambahMakananModal()">&times;</button>
            </div>

            <!-- Body -->
            <div class="pp-body">

                <!-- Row: FnB Ordering (Harmonized with Self-Service) -->
                <div class="fnb-selection-area">
                    <div class="fnb-header">
                        <span style="font-size: 0.9rem;"><i class="fas fa-utensils"></i> PILIH MAKANAN & MINUMAN</span>
                        <span id="fnbTotalCount" class="badge" style="background: #1a237e; color: #fff; font-size: 0.75rem; border-radius: 10px; padding: 4px 10px;">0 Item</span>
                    </div>
                    <div style="padding: 10px; border-bottom: 1px solid #eee; background: #fff;">
                        <input type="text" class="pp-input" id="fnbSearchInput" placeholder="Ketik nama makanan atau minuman..." onkeyup="filterFnbGrid(this.value, 'fnbMenuList')" style="border-radius: 20px; padding-left: 15px; height: 38px;">
                    </div>
                    
                    <div class="fnb-list-container" id="fnbMenuList" style="max-height: 400px;">
                        @foreach($itemmaster as $item)
                            @if(in_array($item->TypeItem, [1,2,3,5]))
                            <div class="fnb-item" data-name="{{ strtolower($item->NamaItem) }}">
                                <img src="{{ $item->Gambar ? (str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTUwIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMjAiIGZpbGw9IiNhYWEiIGR5PSIuM2VtIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiPkZvb2Q8L3RleHQ+PC9zdmc+' }}" 
                                     class="fnb-item-img" 
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTUwIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMjAiIGZpbGw9IiNhYWEiIGR5PSIuM2VtIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiPkZvb2Q8L3RleHQ+PC9zdmc+'">
                                <div class="fnb-item-info">
                                    <div class="fnb-item-name">{{ $item->NamaItem }}</div>
                                    <div class="fnb-item-price">Rp {{ number_format($item->HargaJual) }}</div>
                                    <div class="fnb-item-stock">Stok: {{ $item->Stock }}</div>
                                </div>
                                <div class="fnb-qty-ctrl">
                                    <button type="button" class="fnb-qty-btn" onclick='addFnbToCart(@json($item), -1)'>-</button>
                                    <span class="fnb-qty-val fnb-qty-val" data-kode="{{ $item->KodeItem }}">0</span>
                                    <button type="button" class="fnb-qty-btn" onclick='addFnbToCart(@json($item), 1)'>+</button>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Row: Service Type (Dine In / Take Away) -->
                <div class="pp-row" style="margin-top: 15px; background: #fffde7; padding: 10px; border-radius: 8px; border: 1px solid #fff9c4;">
                    <div class="pp-field">
                        <label class="pp-label" style="color: #f57f17;"><i class="fas fa-hand-holding-heart"></i> Tipe Layanan</label>
                        <div style="display: flex; gap: 20px; margin-top: 8px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; color: #1a237e;">
                                <input type="radio" name="fnbServiceType" value="DINE_IN" checked> Makan di Tempat
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700; color: #e65100;">
                                <input type="radio" name="fnbServiceType" value="TAKE_AWAY"> Bawa Pulang
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pp-row" style="margin-top:15px; background: #f8f9fa; padding: 12px; border-radius: 10px; border: 1px solid #e0e0e0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 800; font-size: 1.1rem; color: #1a237e;">
                        <span>TOTAL PESANAN:</span>
                        <span id="fnbTotalItem">Rp 0</span>
                    </div>
                </div>

                <!-- Opsi Bayar Row -->
                <div class="pp-row" style="margin-top: 20px; border-top: 1px dashed #cfd8dc; padding-top: 15px;">
                    <div class="pp-field">
                        <label class="pp-label"><i class="fas fa-cash-register"></i> Pembayaran</label>
                        <div style="display: flex; gap: 15px; margin-top: 5px;">
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                <input type="radio" name="FnbOpsiBayar" value="NANTI" checked onchange="toggleFnbPaymentSection()"> Bayar Nanti
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                <input type="radio" name="FnbOpsiBayar" value="LANGSUNG" onchange="toggleFnbPaymentSection()"> Bayar Langsung
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Section Details -->
                <div id="fnbPaymentSection" style="display: none; background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px; padding: 15px; margin-top: 15px;">
                    <div class="detail-calc-row">
                        <span>Total Pesanan:</span>
                        <span id="fnbCalcSubtotal">Rp 0</span>
                    </div>
                    <div class="detail-calc-row">
                        <span>PPN (<span id="fnbCalcPpnPersen">{{ $company[0]->PPN ?? 0 }}</span>%):</span>
                        <span id="fnbCalcPpnRp">Rp 0</span>
                    </div>
                    <div class="detail-calc-row">
                        <span>Biaya Layanan (<span id="fnbCalcServicePersen">{{ $company[0]->ServiceCharge ?? 0 }}</span>%):</span>
                        <span id="fnbCalcServiceRp">Rp 0</span>
                    </div>
                    <div class="detail-calc-row" id="rowFnbAdmin" style="display: none;">
                        <span>Biaya Admin:</span>
                        <span id="fnbCalcAdminRp">Rp 0</span>
                    </div>
                    <hr style="border-top: 1px dashed #90caf9; border-bottom: none; margin: 10px 0;">
                    <div class="detail-calc-row" style="font-size: 1.1rem; font-weight: 700;">
                        <span>GRAND TOTAL:</span>
                        <span id="fnbCalcGrandTotal" style="color: #1565c0;">Rp 0</span>
                    </div>

                    <div class="pp-row" style="margin-top: 15px;">
                        <div class="pp-field">
                            <label class="pp-label">Metode Pembayaran</label>
                            <select class="pp-input" id="fnbMetodePembayaran" onchange="calculateFnbTotal()">
                                @foreach($metodepembayaran as $mp)
                                    <option value="{{ $mp->id }}" 
                                        data-percent="{{ $mp->BiayaAdminPercent ?? 0 }}"
                                        data-rupiah="{{ $mp->BiayaAdminRupiah ?? 0 }}"
                                        data-verifikasi="{{ $mp->MetodeVerifikasi }}">
                                        {{ $mp->NamaMetodePembayaran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pp-row pp-row-2" style="margin-top: 10px;">
                        <div class="pp-field">
                            <label class="pp-label">Nominal Bayar</label>
                            <input type="text" class="pp-input" id="fnbNominalBayar" oninput="formatRupiahInput(this); calculateFnbTotal(true)" value="0">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Kembalian</label>
                            <input type="text" class="pp-input" id="fnbKembalian" readonly value="Rp 0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closeTambahMakananModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button class="pp-btn-confirm" id="btnConfirmFnb" onclick="submitFnbOrder()">
                    <i class="fas fa-save"></i> Simpan Pesanan
                </button>
            </div>
        </div>
    </div>

    <!-- ===== MODAL TAMBAH DURASI ===== -->
    <div id="modalTambahDurasi" class="pp-overlay">
        <div class="pp-modal" style="max-width: 500px;">
            <div class="pp-header">
                <div>
                    <div class="pp-header-label"><i class="fas fa-clock"></i> Tambah Durasi</div>
                    <div class="pp-header-titik" id="mdDurasiTitikNama">--</div>
                </div>
                <button class="pp-close" onclick="closeTambahDurasiModal()">&times;</button>
            </div>

            <div class="pp-body">
                <div class="pp-row">
                    <div class="pp-field">
                        <label class="pp-label">Pilih Paket Ekstensi</label>
                        <select class="pp-input" id="tdPaketId" onchange="onTambahDurasiPaketChange()">
                            <!-- Populated via JS -->
                        </select>
                    </div>
                </div>

                <div class="pp-row pp-row-2" style="margin-top: 10px;">
                    <div class="pp-field">
                        <label class="pp-label">Durasi Tambahan</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <button type="button" class="btn-qty" onclick="adjTambahDurasi(-1)">-</button>
                            <input type="number" id="tdDurasi" class="pp-input" style="text-align:center; flex:1;" value="1" min="1" onchange="calculateTambahDurasi()">
                            <button type="button" class="btn-qty" onclick="adjTambahDurasi(1)">+</button>
                            <span id="tdUnitLabel" style="font-weight:600; color:#444; width:50px;">Jam</span>
                        </div>
                    </div>
                </div>

                <div class="pp-row" style="margin-top: 20px; border-top: 1px dashed #cfd8dc; padding-top: 15px;">
                    <div class="pp-field">
                        <label class="pp-label"><i class="fas fa-cash-register"></i> Pembayaran</label>
                        <div style="display: flex; gap: 15px; margin-top: 5px;">
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                <input type="radio" name="TdOpsiBayar" value="NANTI" checked onchange="toggleTdPaymentSection()"> Bayar Nanti
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                <input type="radio" name="TdOpsiBayar" value="LANGSUNG" onchange="toggleTdPaymentSection()"> Bayar Langsung
                            </label>
                        </div>
                    </div>
                </div>

                <div id="tdPaymentSection" style="display: none; background: #f1f8e9; border: 1px solid #c5e1a5; border-radius: 8px; padding: 15px; margin-top: 15px;">
                    <div class="detail-calc-row">
                        <span>Tambahan Biaya:</span>
                        <span id="tdCalcSubtotal">Rp 0</span>
                    </div>
                    <div class="detail-calc-row">
                        <span>PPN (<span id="tdCalcPpnPersen">{{ $company[0]->PPN ?? 0 }}</span>%):</span>
                        <span id="tdCalcPpnRp">Rp 0</span>
                    </div>
                    <div class="detail-calc-row">
                        <span>Biaya Layanan (<span id="tdCalcServicePersen">{{ $company[0]->ServiceCharge ?? 0 }}</span>%):</span>
                        <span id="tdCalcServiceRp">Rp 0</span>
                    </div>
                    <div class="detail-calc-row" id="rowTdAdmin" style="display: none;">
                        <span>Biaya Admin:</span>
                        <span id="tdCalcAdminRp">Rp 0</span>
                    </div>
                    <hr style="border-top: 1px dashed #9ccc65; border-bottom: none; margin: 10px 0;">
                    <div class="detail-calc-row" style="font-size: 1.1rem; font-weight: 700;">
                        <span>GRAND TOTAL:</span>
                        <span id="tdCalcGrandTotal" style="color: #388e3c;">Rp 0</span>
                    </div>

                    <div class="pp-row" style="margin-top: 15px;">
                        <div class="pp-field">
                            <label class="pp-label">Metode Pembayaran</label>
                            <select class="pp-input" id="tdMetodePembayaran" onchange="calculateTambahDurasi()">
                                @foreach($metodepembayaran as $mp)
                                    <option value="{{ $mp->id }}" 
                                        data-percent="{{ $mp->BiayaAdminPercent ?? 0 }}"
                                        data-rupiah="{{ $mp->BiayaAdminRupiah ?? 0 }}"
                                        data-verifikasi="{{ $mp->MetodeVerifikasi }}">
                                        {{ $mp->NamaMetodePembayaran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pp-row pp-row-2" style="margin-top: 10px;">
                        <div class="pp-field">
                            <label class="pp-label">Nominal Bayar</label>
                            <input type="text" class="pp-input" id="tdNominalBayar" oninput="formatRupiahInput(this); calculateTambahDurasi(true)" value="0">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Kembalian</label>
                            <input type="text" class="pp-input" id="tdKembalian" readonly value="Rp 0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closeTambahDurasiModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button class="pp-btn-confirm" id="btnConfirmTambahDurasi" onclick="submitTambahDurasi()">
                    <i class="fas fa-save"></i> Perpanjang Durasi
                </button>
            </div>
        </div>
    </div>

    <!-- ===== MODAL RECEIPT PREVIEW ===== -->
    <div id="modalReceiptPreview" class="receipt-overlay">
        <div class="receipt-container">
            <div class="receipt-paper" id="receiptContent">
                <div class="receipt-header">
                    <div class="receipt-logo" id="rcptCompanyName">D'BILLIARD</div>
                    <div class="receipt-address" id="rcptCompanyAddress">Alamat Perusahaan</div>
                    <div class="receipt-address" id="rcptCompanyPhone">Telp: 08123456789</div>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-info">
                    <div class="receipt-info-row">
                        <span>No: <strong id="rcptNoFaktur">#12345</strong></span>
                        <span id="rcptDate">27/03/2026 15:30</span>
                    </div>
                    <div class="receipt-info-row">
                        <span>Kasir: <span id="rcptCashier">{{ Auth::user()->name }}</span></span>
                        <span>Meja: <span id="rcptTable">-</span></span>
                    </div>
                    <div class="receipt-info-row">
                        <span>Pelanggan:</span>
                        <span id="rcptCustomer">Umum</span>
                        <span id="rcptCustomerEmail" style="display:none;"></span>
                    </div>
                </div>

                <div class="receipt-divider"></div>

                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Total</th>
                        </tr>
                    </thead>
                    <tbody id="rcptItems">
                        <!-- Items populate here -->
                    </tbody>
                </table>

                <div class="receipt-divider"></div>

                <div class="receipt-totals">
                    <div class="receipt-total-row">
                        <span>Subtotal:</span>
                        <span id="rcptSubtotal">Rp 0</span>
                    </div>
                    <div class="receipt-total-row" id="rcptRowDiskon" style="display:none;">
                        <span>Diskon:</span>
                        <span id="rcptDiskon">-Rp 0</span>
                    </div>
                    <div class="receipt-total-row">
                        <span>Pajak:</span>
                        <span id="rcptPajak">Rp 0</span>
                    </div>
                    <div class="receipt-total-row" id="rcptRowPajakHiburan" style="display:none;">
                        <span>Pajak Hiburan:</span>
                        <span id="rcptPajakHiburan">Rp 0</span>
                    </div>
                    <div class="receipt-total-row">
                        <span>Layanan / Admin:</span>
                        <span id="rcptLayanan">Rp 0</span>
                    </div>
                    <div class="receipt-total-row receipt-grand-total">
                        <span>GRAND TOTAL:</span>
                        <span id="rcptGrandTotal">Rp 0</span>
                    </div>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-info">
                    <div class="receipt-info-row">
                        <span>Metode: <span id="rcptMetode">Tunai</span></span>
                    </div>
                    <div class="receipt-info-row">
                        <span>Bayar:</span>
                        <span id="rcptBayar">Rp 0</span>
                    </div>
                    <div class="receipt-info-row" style="font-weight:700;">
                        <span>Kembali:</span>
                        <span id="rcptKembali">Rp 0</span>
                    </div>
                </div>

                <div class="receipt-footer">
                    Terima kasih atas kunjungan Anda!<br>
                    Silahkan datang kembali.
                </div>
            </div>

            <div class="receipt-actions">
                <button class="btn-receipt btn-receipt-print" onclick="printReceipt()"><i class="fas fa-print"></i> Cetak</button>
                <button class="btn-receipt btn-receipt-email" onclick="sendReceiptEmail()"><i class="fas fa-envelope"></i> Email</button>
                <button class="btn-receipt btn-receipt-wa" onclick="shareReceiptWhatsApp()"><i class="fab fa-whatsapp"></i> WhatsApp</button>
                <button class="btn-receipt btn-receipt-close" onclick="closeReceiptModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL ORDER -->


    <div id="modalDetailOrder" class="modal-pos">
        <div class="modal-pos-content">
            <div class="modal-pos-header">
                <h2 id="mdTitle">Detail Order</h2>
                <span class="modal-close" onclick="closeDetailModal()">&times;</span>
            </div>
            <div class="modal-pos-body">
                <div class="detail-info-grid">
                    <div class="detail-item">
                        <span class="detail-label">No. Transaksi</span>
                        <span class="detail-value" id="mdNoTransaksi">-</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nama Pelanggan</span>
                        <span class="detail-value" id="mdNamaPelanggan">-</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Jam Mulai</span>
                        <span class="detail-value" id="mdJamMulai">-</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Jam Selesai</span>
                        <span class="detail-value" id="mdJamSelesai">-</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Paket / Durasi</span>
                        <span class="detail-value" id="mdPaketDurasi">-</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Harga Per Durasi</span>
                        <span class="detail-value" id="mdHargaSatuan">-</span>
                    </div>
                </div>

                <div class="summary-details" style="display:grid; grid-template-columns:repeat(2, 1fr); gap:12px; background:#f5faff; padding:15px; border-radius:8px; border:1px solid #d1e4ff;">
                    <div class="summary-row"><span>Total Paket</span> <span id="mdTotalPaket">Rp 0</span></div>
                    <div class="summary-row"><span>Diskon</span> <span id="mdDiskon" style="color:#e53935;">Rp 0</span></div>
                    <div class="summary-row"><span>Pajak (PPN)</span> <span id="mdPajak">Rp 0</span></div>
                    <div class="summary-row"><span>Pajak Hiburan</span> <span id="mdPajakHiburan">Rp 0</span></div>
                    <div class="summary-row"><span>Biaya Layanan</span> <span id="mdLayanan">Rp 0</span></div>
                    <div class="summary-row"><span>Total Makanan &amp; Minuman</span> <span id="mdTotalMakanan">Rp 0</span></div>
                    <div class="summary-row" style="font-weight:700; border-top:1px solid #bddbff; padding-top:8px; grid-column: span 2; font-size:1.1rem;">
                        <span>Grand Total (Paket + FnB)</span> <span id="mdGrandTotal">Rp 0</span>
                    </div>
                </div>

                <div class="section-title">
                    <span>Daftar Pesanan FnB</span>
                    <span id="fnbCountBadge" style="background:#1a237e; color:#fff; font-size:0.75rem; padding:2px 8px; border-radius:10px;">0 Item</span>
                </div>
                <div style="max-height: 200px; overflow-y: auto;">
                    <table class="fnb-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th style="text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody id="mdFnBList">
                            <!-- JS populated -->
                        </tbody>
                    </table>
                </div>

                <div id="mdPaymentSection" style="display: none; background:#fff8e1; border:1px solid #ffe082; border-radius:10px; padding:16px; margin-top:12px;">
                    <div style="font-weight:700; font-size:0.95rem; color:#e65100; margin-bottom:10px;"><i class="fas fa-exclamation-circle"></i> Ada Sisa Tagihan</div>

                    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:8px; margin-bottom:12px;">
                        <div><span style="font-size:0.82rem; color:#555;">Total Tagihan</span><br><strong id="mdSumTagihan" style="font-size:1rem;">Rp 0</strong></div>
                        <div><span style="font-size:0.82rem; color:#555;">Sudah Terbayar</span><br><strong id="mdSumTerbayar" style="color:#2e7d32; font-size:1rem;">Rp 0</strong></div>
                        <div style="grid-column:span 2; background:#fff3cd; border-radius:6px; padding:8px 10px;">
                            <span style="font-size:0.82rem; color:#555;">Sisa Tagihan</span><br>
                            <strong id="mdSumOutstanding" style="color:#c62828; font-size:1.15rem;">Rp 0</strong>
                        </div>
                    </div>

                    <hr style="border:none; border-top:1px dashed #ffc107; margin:10px 0;">
                    <div style="font-weight:600; font-size:0.88rem; color:#555; margin-bottom:8px;">Pembayaran Sisa</div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                        <div style="grid-column:span 2;">
                            <label style="font-size:0.82rem; color:#666; display:block; margin-bottom:3px;">Metode Pembayaran</label>
                            <select id="mdMetodePembayaran" class="pp-input" style="width:100%;" onchange="onDetailMetodeChange()">
                                @foreach($metodepembayaran as $mp)
                                    <option value="{{ $mp->id }}"
                                        data-nama="{{ $mp->NamaMetodePembayaran }}"
                                        data-percent="{{ $mp->BiayaAdminPercent ?? 0 }}"
                                        data-rupiah="{{ $mp->BiayaAdminRupiah ?? 0 }}">
                                        {{ $mp->NamaMetodePembayaran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="mdAdminFeeRow" style="grid-column:span 2; display:none; background:#fce4ec; border-radius:6px; padding:8px 10px;">
                            <span style="font-size:0.82rem; color:#555;">Biaya Admin / Layanan</span><br>
                            <strong id="mdAdminFeeValue" style="color:#c62828;">Rp 0</strong>
                        </div>

                        <div style="grid-column:span 2;">
                            <label style="font-size:0.82rem; color:#666; display:block; margin-bottom:3px;">Nominal Bayar</label>
                            <input type="text" id="mdNominalBayar" class="pp-input" style="width:100%; font-size:1rem; font-weight:600;" placeholder="0" oninput="formatRupiahInput(this); onDetailNominalChange(true)" value="0">
                        </div>

                        <div style="grid-column:span 2; background:#e8f5e9; border-radius:6px; padding:8px 10px;">
                            <span style="font-size:0.82rem; color:#555;">Kembalian</span><br>
                            <strong id="mdKembalian" style="color:#2e7d32; font-size:1.1rem;">Rp 0</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-pos-footer">
                <button class="btn-modal-action btn-modal-close" onclick="closeDetailModal()">Tutup</button>
                <button id="mdBtnCheckOut" class="btn-modal-action btn-modal-pay" onclick="onBayarFromDetail()" style="display: none;"><i class="fas fa-money-bill-wave"></i> Bayar</button>
            </div>
        </div>
    </div>

    <style>
    /* ===== MODAL PILIH PAKET ===== */
    .pp-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 5000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }
    .pp-overlay.open { display: flex; }

    .pp-modal {
        background: #fff;
        border-radius: 16px;
        width: 95%;
        max-width: 600px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        animation: ppSlideIn 0.25s ease;
    }
    @keyframes ppSlideIn {
        from { opacity: 0; transform: translateY(-30px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0)   scale(1); }
    }

    /* Header */
    .pp-header {
        background: linear-gradient(135deg, #1a237e, #3949ab);
        color: #fff;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .pp-header-label {
        font-size: 0.82rem;
        opacity: 0.8;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .pp-header-titik {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .pp-close {
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
        font-size: 1.6rem;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .pp-close:hover { background: rgba(255,255,255,0.3); }

    /* Body */
    .pp-body {
        padding: 20px 24px;
        overflow-y: auto;
        flex: 1;
    }
    .pp-row { margin-bottom: 14px; }
    .pp-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .pp-field { display: flex; flex-direction: column; }
    .pp-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #546e7a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .pp-label i { margin-right: 4px; }
    .pp-input {
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.95rem;
        color: #263238;
        outline: none;
        transition: border-color 0.2s;
        width: 100%;
        background: #fafafa;
    }
    .pp-input:focus { border-color: #3949ab; background: #fff; }
    .titik-box.status-2 { background: #fb8c00; color: #fff; }
    .titik-box.active-selected {
        outline: 3px solid #2196f3;
        outline-offset: 2px;
        transform: scale(1.05);
        z-index: 10;
    }

    /* Paid Badge inside Titik Box */
    .titik-box {
        position: relative;
    }
    .paid-badge {
        position: absolute;
        top: -10px;
        right: -8px;
        background: #4caf50;
        color: white;
        border-radius: 4px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        border: 1.5px solid white;
        z-index: 5;
        text-transform: uppercase;
        line-height: 1;
    }

    /* Timer inside Titik Box */
    .table-timer {
        position: absolute;
        bottom: 0px;
        left: 0;
        right: 0;
        font-size: 12px;
        font-weight: 800;
        text-align: center;
        background: rgba(0,0,0,0.15);
        padding: 3px 0;
        pointer-events: none;
        color: #333;
    }
    .status-0 .table-timer { display: none; }
    /* Durasi stepper */
    .pp-durasi-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .pp-dur-btn {
        width: 36px;
        height: 36px;
        border: 1.5px solid #3949ab;
        border-radius: 8px;
        background: #fff;
        color: #3949ab;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        transition: background 0.15s;
        flex-shrink: 0;
    }
    .pp-dur-btn:hover { background: #3949ab; color: #fff; }
    .pp-dur-input { text-align: center; flex: 1; }

    /* Footer */
    .pp-footer {
        padding: 14px 24px;
        background: #f8f9fb;
        border-top: 1px solid #e8eaf6;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    
    /* Calc Detail Row */
    .detail-calc-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: #455a64;
        margin-bottom: 6px;
    }
    .pp-btn-cancel {
        padding: 10px 22px;
        border-radius: 8px;
        border: 1.5px solid #b0bec5;
        background: #fff;
        color: #546e7a;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.15s;
    }
    .pp-btn-cancel:hover { background: #eceff1; }
    .pp-btn-confirm {
        padding: 10px 26px;
        border-radius: 8px;
        border: none;
        background: linear-gradient(135deg, #2e7d32, #43a047);
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: opacity 0.15s, transform 0.12s;
        box-shadow: 0 4px 12px rgba(46,125,50,0.3);
    }
        .pp-btn-confirm:hover { opacity: 0.88; transform: translateY(-1px); }
    .pp-btn-confirm:disabled { background: #b0bec5; cursor: not-allowed; box-shadow: none; opacity: 0.7; }

    /* Slots */
    .slot-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        background: #f8f9fb;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        max-height: 180px;
        overflow-y: auto;
    }
    .slot-box {
        padding: 6px 12px;
        border: 1.5px solid #b0bec5;
        border-radius: 6px;
        background: #fff;
        color: #37474f;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }
    .slot-box:hover:not(.booked) {
        border-color: #3949ab;
        color: #3949ab;
    }
    .slot-box.selected {
        background: #3949ab;
        color: #fff;
        border-color: #3949ab;
    }
    .slot-box.booked {
        background: #e0e0e0;
        color: #9e9e9e;
        border-color: #d5d5d5;
        cursor: not-allowed;
    }
    .slot-loading { font-size: 0.85rem; color: #78909c; width: 100%; text-align: center; font-style: italic; }
    .mt-1 { margin-top: 6px; }

    /* Fix SweetAlert being behind modal (ULTRA-HIGH) */
    .swal2-container, 
    .sweet-overlay, 
    .swal-overlay,
    .swal-container { 
        z-index: 999999 !important; 
    }
    .swal2-popup, 
    .sweet-alert, 
    .swal-modal,
    .swal-popup { 
        z-index: 1000000 !important; 
    }
    </style>

    <script>
    // ===== DATA INJECTIONS =====
    var dataPaketAll = {!! json_encode($paket) !!};
    var dataPelangganAll = {!! json_encode($pelanggan) !!}; // Data Member Injected
    var dataCustomerMemberships = {!! json_encode($customerMemberships ?? []) !!}; // Active Memberships
    var dataGrupPelanggan = {!! json_encode($gruppelanggan) !!}; // Member Group
    var confCompany = {!! json_encode(count($company) > 0 ? $company[0] : null) !!};

    var selectedSlots = []; // menampung jam slot yang di click
    var rawSlots = [];      // var nyimpan data asli slot dari json
    var activeHargaPaket = 0;

    // ===== MODAL PILIH PAKET LOGIC (UI Only) =====
    function onJenisPaketChange(jenis) {
        var sel = document.getElementById('ppPaketId');
        sel.innerHTML = '<option value="">-- Pilih Paket --</option>';
        document.getElementById('ppHargaNormal').value = '';
        
        var rowSlot = document.getElementById('ppRowSlot');
        var rowJam = document.getElementById('ppRowJamMulai');
        selectedSlots = [];

        if (!jenis) {
            rowSlot.style.display = 'none';
            rowJam.style.display = 'grid';
            return;
        }

        var btnMin = document.getElementById('btnMinDurasi');
        var btnPlus = document.getElementById('btnPlusDurasi');
        var inputDurasi = document.getElementById('ppDurasi');

        if (jenis === 'MENITREALTIME' || jenis === 'PAYPERUSE') {
            inputDurasi.disabled = true;
            btnMin.disabled = true;
            btnPlus.disabled = true;
            inputDurasi.style.backgroundColor = '#f3f6f9';
            btnMin.style.backgroundColor = '#f3f6f9';
            btnPlus.style.backgroundColor = '#f3f6f9';
            inputDurasi.value = '1';
        } else {
            // Always enable duration input, relying on package default (DurasiPaket)
            inputDurasi.disabled = false;
            btnMin.disabled = false;
            btnPlus.disabled = false;
            inputDurasi.style.backgroundColor = '';
            btnMin.style.backgroundColor = '';
            btnPlus.style.backgroundColor = '';
        }

        if (jenis === 'JAM' || jenis === 'PAKETMEMBER') {
            rowSlot.style.display = 'block';
            rowJam.style.display = 'none'; // Sembunyikan Jam Mulai krn pakai slot
            fetchSlots();
        } else {
            rowSlot.style.display = 'none';
            rowJam.style.display = 'grid';
        }

        var cat = selectedTitik && selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";
        
        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value.trim();
            var kelLampu = selectedTitik ? (selectedTitik.KelompokLampu || selectedTitik.kelompoklampu || "").toString().trim() : "";
            
            console.log("=== DEBUG PAKET MEMBER ===");
            console.log("memberId: ", memberId);
            console.log("kelLampu (from selectedTitik): ", kelLampu, typeof kelLampu);
            console.log("selectedTitik: ", selectedTitik);
            console.log("dataCustomerMemberships: ", dataCustomerMemberships);
            
            var validMemberships = [];
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(memberData.maxTimePerPlay) || 1;
                }
                validMemberships = dataCustomerMemberships.filter(function(m) {
                    var mId = (m.KodePelanggan || "").toString().trim();
                    var mKl = (m.KelompokLampu || "").toString().trim();
                    return mId == memberId && (!mKl || mKl == kelLampu);
                });
                console.log("validMemberships after filter: ", validMemberships);
            }

            validMemberships.forEach(function(m) {
                var opt = document.createElement('option');
                opt.value = m.KodePaketMember; // Gunakan string ID PaketMember 
                opt.text = (m.NamaPaket || m.KodePaketMember) + " (Berlaku s/d " + m.ValidUntil + ")";
                opt.dataset.harga = 0;
                opt.dataset.durasi = m.maxTimePerPlay || 1;
                sel.appendChild(opt);
            });
            
            // Set Unit Label
            document.getElementById('tdUnitLabel').textContent = 'Jam';
            
            if (sel.options.length === 1) { // Hanya "-- Pilih Paket --"
                swal("Info", "Member belum dipilih atau tidak memiliki paket aktif untuk meja ini.", "info");
                return;
            }
        } else {
            dataPaketAll.forEach(function(p) {
                if (p.JenisPaket === jenis) {
                    // Filter berdasarkan kategori meja
                    if (cat === "" || p.NamaPaket.toUpperCase().includes(cat)) {
                        var opt = document.createElement('option');
                        opt.value = p.id;
                        opt.text = p.NamaPaket;
                        opt.dataset.harga = p.HargaNormal || 0;
                        opt.dataset.durasi = p.DurasiPaket || 1; // Inject package base durasi
                        sel.appendChild(opt);
                    }
                }
            });
            
            if (sel.options.length === 1) {
                swal("Info", "Tidak ada paket tambahan yang tersedia untuk jenis paket ini.", "info");
                return;
            }
        }
        
        updateJamSelesai();
        validateForm();
    }

    document.getElementById('ppTglTransaksi').addEventListener('change', function() {
        var jenis = document.getElementById('ppJenisPaket').value;
        if (jenis === 'JAM' || jenis === 'PAKETMEMBER') {
            fetchSlots();
        }
    });

    function fetchSlots() {
        var tgl = document.getElementById('ppTglTransaksi').value;
        var table_id = selectedTitik ? selectedTitik.id : null;
        var container = document.getElementById('ppSlotContainer');
        
        if (!tgl || !table_id) return;
        
        container.innerHTML = '<div class="slot-loading">Memuat slot...</div>';
        selectedSlots = [];
        
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/billing/getAvailableSlots', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ tanggal: tgl, table_id: table_id })
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                renderSlots(res.data);
            } else {
                container.innerHTML = '<div class="slot-loading">Gagal memuat slot: '+res.message+'</div>';
            }
        })
        .catch(err => {
            container.innerHTML = '<div class="slot-loading">Terdapat kesalahan sistem.</div>';
        });
    }

    function renderSlots(slotsData) {
        rawSlots = slotsData;
        var container = document.getElementById('ppSlotContainer');
        container.innerHTML = '';
        
        if (slotsData.length === 0) {
            container.innerHTML = '<div class="slot-loading">Tidak ada slot tersedia di hari tersebut.</div>';
            return;
        }

        var jenis = document.getElementById('ppJenisPaket').value;
        var isMemberValid = true;
        var memberError = '';

        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (!memberId) {
                isMemberValid = false;
                memberError = 'Pilih Member terlebih dahulu untuk melihat ketersediaan slot.';
            } else {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (!memberData) {
                    isMemberValid = false;
                    memberError = 'Member tidak ditemukan (Harap Refresh).';
                } else if (memberData.isPaidMembership != 1) {
                    isMemberValid = false;
                    memberError = 'Member tidak memiliki Membership Aktif (Nilai saat ini: ' + memberData.isPaidMembership + '). Harap Refresh halaman jika baru membayar.';
                }
            }
        }

        if (!isMemberValid) {
            var errorDiv = document.createElement('div');
            errorDiv.style.width = '100%';
            errorDiv.style.textAlign = 'center';
            errorDiv.style.marginBottom = '10px';
            errorDiv.innerHTML = '<span style="color:#e53935; font-size: 0.85rem;"><i class="fas fa-ban"></i> ' + memberError + '</span>';
            container.appendChild(errorDiv);
        }

        slotsData.forEach((s, idx) => {
            // Force block slot if member is invalid for PAKETMEMBER
            var isSlotBooked = s.booked || !isMemberValid;

            var d = document.createElement('div');
            d.className = 'slot-box' + (isSlotBooked ? ' booked' : '');
            d.textContent = s.time;
            d.dataset.idx = idx;
            
            if (!isSlotBooked) {
                d.addEventListener('click', function() {
                    toggleSlot(this, idx, s);
                });
            }
            container.appendChild(d);
        });
    }

    function toggleSlot(el, idx, slot) {
        if (el.classList.contains('selected')) {
            // Remove
            var pos = selectedSlots.findIndex(x => x.idx === idx);
            if (pos !== -1) selectedSlots.splice(pos, 1);
            el.classList.remove('selected');
        } else {
            // Add
            selectedSlots.push({ idx: idx, slot: slot });
            el.classList.add('selected');
        }
        
        // Sort
        selectedSlots.sort((a,b) => a.idx - b.idx);
        validateSlotContinuity();
        
        // Update Durasi (asumsi tiap slot = 1 jam)
        if (selectedSlots.length > 0) {
            document.getElementById('ppDurasi').value = selectedSlots.length;
            // Kita override JamMulai & Selesai value logic asli agar dikirim benar saat submit
            document.getElementById('ppJamMulai').value = selectedSlots[0].slot.time;
        } else {
            document.getElementById('ppDurasi').value = 1;
        }
        updateJamSelesai();

        // Auto-select next slots for member package IF they just selected the first slot
        var jenis = document.getElementById('ppJenisPaket').value;
        if (jenis === 'PAKETMEMBER' && el.classList.contains('selected') && selectedSlots.length === 1) {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    var maxTime = parseInt(memberData.maxTimePerPlay) || 1;
                    if (maxTime > 1) {
                        var nextSlotsNeeded = maxTime - 1;
                        var allSlotDivs = document.querySelectorAll('.slot-box:not(.booked)');
                        var startIndex = -1;
                        for(var i=0; i<allSlotDivs.length; i++) {
                            if (allSlotDivs[i].dataset.idx == idx) {
                                startIndex = i; break;
                            }
                        }
                        if (startIndex !== -1) {
                            for(var i=1; i<=nextSlotsNeeded; i++) {
                                var nextDiv = allSlotDivs[startIndex + i];
                                if (nextDiv && parseInt(nextDiv.dataset.idx) === parseInt(idx) + i) {
                                    nextDiv.click(); // Programmatically click the next slot
                                } else {
                                    break; // Stop if not consecutive or booked
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function validateSlotContinuity() {
        var helper = document.getElementById('ppSlotHelper');
        var jenis = document.getElementById('ppJenisPaket').value;
        var reqSlots = 0;

        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    reqSlots = parseInt(memberData.maxTimePerPlay) || 0;
                }
            }
        }

        var isConsecutive = true;
        if (selectedSlots.length > 1) {
            for(var i=1; i<selectedSlots.length; i++) {
                if (selectedSlots[i].idx !== selectedSlots[i-1].idx + 1) {
                    isConsecutive = false; break;
                }
            }
        }

        if (!isConsecutive) {
            helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-exclamation-circle"></i> Slot harus berurutan!</span>';
            document.getElementById('ppBtnConfirm').disabled = true;
            return;
        }

        if (jenis === 'PAKETMEMBER' && reqSlots > 0 && selectedSlots.length !== reqSlots) {
            helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-exclamation-circle"></i> Wajib '+reqSlots+' Jam berurutan (Cari jam kosong yang cukup).</span>';
            document.getElementById('ppBtnConfirm').disabled = true;
            return;
        }

        if (selectedSlots.length > 0) {
            helper.innerHTML = '<span style="color:#43a047;"><i class="fas fa-check-circle"></i> Slot valid ('+selectedSlots.length+' Jam).</span>';
            validateForm();
        } else {
            if (jenis === 'PAKETMEMBER' && reqSlots > 0) {
                helper.innerHTML = 'Pilih slot kosong yang cukup untuk '+reqSlots+' Jam.';
            } else {
                helper.innerHTML = 'Pilih 1 atau lebih slot berurutan.';
            }
            validateForm();
        }
    }

    $('#ppPaketId').on('change', function() {
        var selectedOpt = this.options[this.selectedIndex];
        var inputHarga = document.getElementById('ppHargaNormal');
        var inputDurasi = document.getElementById('ppDurasi');
        
        if (selectedOpt && selectedOpt.value !== "") {
            var hrg = parseFloat(selectedOpt.dataset.harga) || 0;
            var defaultDur = parseInt(selectedOpt.dataset.durasi) || 1;
            
            // Format to Rupiah standard
            inputHarga.value = formatRp(hrg);
            
            // Set Base & Minimum Durasi depending on package's preset
            inputDurasi.min = defaultDur;
            inputDurasi.value = defaultDur;
            window.activePaketDurasi = defaultDur; // Global tracker to manage increments
        } else {
            inputHarga.value = '';
            inputDurasi.value = '1';
            inputDurasi.min = '1';
            window.activePaketDurasi = 1;
        }
        updateJamSelesai();
        calculateTotal(); // Ensure totals are updated
        validateForm();
    });

    function changeDurasi(delta) {
        var inp = document.getElementById('ppDurasi');
        var val = parseInt(inp.value) || 1;
        var step = window.activePaketDurasi || 1;
        
        // Cek target increment, apakah kelipatan aktif durasi atau 1 (kalo realtime dll)
        if (!step) step = 1;

        // Tambah/Kurang berdasarkan default step paket
        var newVal = val + (delta * step);

        // Jangan pernah di bawah batas minimal (step itu sendiri)
        val = Math.max(step, newVal);
        inp.value = val;
        updateJamSelesai();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var durEl = document.getElementById('ppDurasi');
        if (durEl) durEl.addEventListener('input', updateJamSelesai);
        var jamEl = document.getElementById('ppJamMulai');
        if (jamEl) {
            // Set now
            var now = new Date();
            jamEl.value = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
            jamEl.addEventListener('change', updateJamSelesai);
            jamEl.addEventListener('input', updateJamSelesai); // Real-time calc as they type
        }
    });

    document.getElementById('ppKodePelanggan').addEventListener('change', function() {
        var memberId = this.value;
        if (memberId) {
            var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
            if (memberData) {
                syncCustomerGreeting(memberData.NamaPelanggan);
            }
        }

        var jenis = document.getElementById('ppJenisPaket').value;
        if(jenis === 'PAKETMEMBER') {
            var mId = document.getElementById('ppKodePelanggan').value;
            if (mId) {
                var mData = dataPelangganAll.find(m => m.KodePelanggan == mId);
                if (mData && mData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(mData.maxTimePerPlay) || 1;
                }
            }

            // Panggil fungsi ini untuk merefresh ulang dropdown paket berlangganan
            onJenisPaketChange('PAKETMEMBER');
            
            updateJamSelesai();
            
            // Re-render slots to reflect new member status (lock/unlock)
            if (rawSlots && rawSlots.length > 0) {
                // Clear selections
                selectedSlots = [];
                // document.getElementById('ppDurasi').value = 1;
                renderSlots(rawSlots);
            }
        }
        validateForm();
    });

    function updateJamSelesai() {
        var jenisPaket = document.getElementById('ppJenisPaket').value;
        var jamMulai = document.getElementById('ppJamMulai').value;
        var durasiInp = document.getElementById('ppDurasi');
        var durasi = parseInt(durasiInp.value) || 0;
        var helper = document.getElementById('ppSlotHelper');
        var confirmBtn = document.getElementById('ppBtnConfirm');
        var inputJamSelesai = document.getElementById('ppJamSelesai');

        if (!jamMulai || !durasi) { inputJamSelesai.value = ''; return; }
        
        // Cek rule PAKETMEMBER
        if (jenisPaket === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (!memberId) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Harap pilih Member untuk PAKETMEMBER!</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            }
            
            var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
            if (!memberData) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member tidak ditemukan (Harap Refresh).</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            } else if (memberData.isPaidMembership != 1) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member belum aktif (Nilai: ' + memberData.isPaidMembership + '). Harap Refresh!</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            }

            // Batasi durasi dengan maxTimePerPlay 
            // Anggap maxTimePerPlay adalah dalam jumlah SLOT atau JAM
            var maxTime = parseInt(memberData.maxTimePerPlay) || 0;
            if (durasi > maxTime) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-exclamation"></i> Melebihi batas Max Time ('+maxTime+' Slot)!</span>';
                confirmBtn.disabled = true;
                return;
            }
        }

        var parts = jamMulai.split(':');
        var d = new Date();
        // Pakai tanggal yang diinput atau hari ini
        var tglInp = document.getElementById('ppTglTransaksi').value;
        if (tglInp) {
            var tParts = tglInp.split('-');
            d = new Date(tParts[0], tParts[1]-1, tParts[2]);
        }
        
        d.setHours(parseInt(parts[0]), parseInt(parts[1]), 0);

        // Logic Increment:
        switch(jenisPaket) {
            case 'MENIT':
                d.setMinutes(d.getMinutes() + durasi);
                break;
            case 'JAM':
            case 'JAMREALTIME':
            case 'PAKETMEMBER':
                d.setMinutes(d.getMinutes() + (durasi * 60) - 1); // dikurangi 1 menit seperti logic awal
                break;
            case 'DAILY':
                d.setDate(d.getDate() + durasi);
                break;
            case 'MONTHLY':
                d.setMonth(d.getMonth() + durasi);
                break;
            case 'YEARLY':
                d.setFullYear(d.getFullYear() + durasi);
                break;
            case 'MENITREALTIME':
            case 'PAYPERUSE':
                // Abaikan durasi, jam selesai tidak di set / dikosongkan
                inputJamSelesai.type = 'text';
                inputJamSelesai.value = '-';
                calculateTotal(); // Trigger ulang kalkulasi
                return; 
            default:
                d.setMinutes(d.getMinutes() + (durasi * 60) - 1); // Default as Jam
                break;
        }

        // Tampilkan hasil format HH:mm ke input JAM (utk jam dan menit)
        if (['DAILY', 'MONTHLY', 'YEARLY'].includes(jenisPaket)) {
            var datePart = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
            var timePart = String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
            inputJamSelesai.value = datePart + ' ' + timePart;
        } else {
            inputJamSelesai.value = String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
        }

        // Trigger ulang kalkulasi jika Opsi Bayar Langsung terbuka
        calculateTotal();
    }

    // ==== BAYAR LANGSUNG LOGIC ====
    function toggleBayarLangsung() {
        var isLangsung = document.querySelector('input[name="OpsiBayar"]:checked').value === 'LANGSUNG';
        document.getElementById('ppDetailBayar').style.display = isLangsung ? 'block' : 'none';
        if(isLangsung) {
            calculateTotal();
        } else {
            validateForm(); // Re-validate if switched back to Bayar Nanti
        }
    }

    function formatRp(value) {
        if (value === null || value === undefined) return 'Rp 0';
        return 'Rp ' + parseFloat(value).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function parseFormattedRp(rpString) {
        if (!rpString) return 0;
        // Ambil bagian utuh sebelum koma desimal (,00) agar tidak dirubah jadi angka ribuan
        var wholePart = rpString.split(',')[0];
        return parseFloat(wholePart.replace(/[^0-9-]/g, '')) || 0;
    }

    function formatRupiahInput(elm) {
        var num = elm.value.replace(/[^0-9]/g, '');
        if(num === '') { elm.value = ''; return; }
        elm.value = new Intl.NumberFormat('id-ID').format(Number(num));
    }

    function toRupiah(num) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num || 0);
    }

    function calculateTotal(isFromInput = false) {
        // 1. Base Data (Paket)
        var durasi = parseInt(document.getElementById('ppDurasi').value) || 1;
        var baseDurasi = window.activePaketDurasi || 1; 
        var hargaInp = document.getElementById('ppHargaNormal').value;
        var harga = parseFormattedRp(hargaInp);
        
        var subtotalPaket = (durasi / baseDurasi) * harga;
        
        // Debug check if result is NaN or 0 unexpectedly
        if (isNaN(subtotalPaket)) subtotalPaket = 0;
        
        $('#calcSubtotal').text(formatRp(subtotalPaket));
        $('#calcQty').text(durasi);

        // 2. FnB Data
        var fnbTotal = ppFnbCart.reduce((acc, item) => acc + (item.Qty * item.Harga), 0);
        $('#ppSumFnb').text(formatRp(fnbTotal));
        
        var subtotalCombined = subtotalPaket + fnbTotal;

        // 3. Discounts
        var discPer = 0;
        var custId = document.getElementById('ppKodePelanggan').value;
        if(custId) {
            var cust = dataPelangganAll.find(m => m.KodePelanggan == custId);
            if(cust && cust.GroupID) {
                var grp = dataGrupPelanggan.find(g => g.id == cust.GroupID);
                if(grp && grp.DiskonPersen) discPer = parseFloat(grp.DiskonPersen);
            }
        }
        var diskonRp = subtotalPaket * (discPer / 100);
        $('#calcDiskonPersen').text(discPer);
        $('#calcDiskonRp').text('- ' + formatRp(diskonRp));

        var voucherRp = window.activeVoucherRp || 0; 
        $('#calcVoucherRp').text('- ' + formatRp(voucherRp));

        // 4. DPP & Taxes
        var ppnPer = confCompany ? parseFloat(confCompany.PPN) || 0 : 0;
        var pb1Per = confCompany ? parseFloat(confCompany.PajakHiburan) || 0 : 0;
        
        var dpp = subtotalCombined - diskonRp - voucherRp;
        if (dpp < 0) dpp = 0;

        var ppnRp = dpp * (ppnPer / 100);
        var pb1Rp = dpp * (pb1Per / 100);
        
        $('#calcPpnPersen').text(ppnPer);
        $('#calcPpnRp').text(formatRp(ppnRp));
        $('#calcPb1Persen').text(pb1Per);
        $('#calcPb1Rp').text(formatRp(pb1Rp));

        var grandTotal = dpp + ppnRp + pb1Rp;

        // 5. Admin Fee (based on Payment Method)
        var nominalInp = document.getElementById('ppNominalBayar');
        var selMp = document.getElementById('ppMetodePembayaran');
        var tipePembayaran = '';
        var adminPercent = 0;
        var adminRupiah = 0;

        if (selMp && selMp.options.length > 0 && selMp.selectedIndex >= 0) {
            var opt = selMp.options[selMp.selectedIndex];
            tipePembayaran = opt.getAttribute('data-tipe');
            adminPercent = parseFloat(opt.getAttribute('data-percent')) || 0;
            adminRupiah = parseFloat(opt.getAttribute('data-rupiah')) || 0;
        }

        var adminFeeRp = 0;
        if (adminPercent > 0) {
            adminFeeRp = (adminPercent / 100) * grandTotal;
        } else if (adminRupiah > 0) {
            adminFeeRp = adminRupiah;
        }

        if (adminFeeRp > 0) {
            $('#rowBiayaAdmin').show();
            $('#calcAdminRp').text(formatRp(adminFeeRp));
        } else {
            $('#rowBiayaAdmin').hide();
        }

        grandTotal += adminFeeRp;
        $('#calcGrandTotal').text(formatRp(grandTotal));

        // 6. Payment Logic
        var isNonTunai = tipePembayaran && tipePembayaran.toUpperCase().indexOf('NON') !== -1;
        if (isNonTunai) {
            nominalInp.value = formatRupiahVal(grandTotal);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f5f5f5';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) {
                nominalInp.value = formatRupiahVal(grandTotal);
            }
        }

        // 7. Change (Kembalian)
        let pay = parseFormattedRp(nominalInp.value || '0');
        let change = pay - grandTotal;
        let kembalianEl = document.getElementById('ppKembalian');
        if (change < 0) {
            $('#ppKembalian').text('Kurang: ' + formatRp(Math.abs(change))).css('color', '#e53935');
        } else {
            $('#ppKembalian').text(formatRp(change)).css('color', '#2e7d32');
        }

        // 8. Sync Customer Display
        const syncData = {
            data: [{ NamaItem: "Paket (" + durasi + ")", Qty: 1, Harga: subtotalPaket }],
            Total: subtotalCombined,
            Discount: diskonRp + voucherRp,
            Tax: ppnRp + pb1Rp + adminFeeRp,
            Net: grandTotal
        };
        ppFnbCart.forEach(item => {
            syncData.data.push({ NamaItem: item.NamaItem, Qty: item.Qty, Harga: item.Harga });
        });
        syncCustomerDisplay(syncData);
        validateForm();
    }


    function validateForm() {
        var btn = document.getElementById('ppBtnConfirm');
        var jenisPaket = document.getElementById('ppJenisPaket').value;
        var paketId = document.getElementById('ppPaketId').value;
        var kodePelanggan = document.getElementById('ppKodePelanggan').value;
        var kodeSales = document.getElementById('ppKodeSales').value;

        btn.disabled = true; // Auto-disable by default

        if (!jenisPaket) return;
        if (jenisPaket !== 'PAKETMEMBER' && !paketId) return;

        if (jenisPaket === 'JAM' || jenisPaket === 'PAKETMEMBER') {
            if (selectedSlots.length === 0) return;
            for(var i=1; i<selectedSlots.length; i++) {
                if (selectedSlots[i].idx !== selectedSlots[i-1].idx + 1) return; // Not consecutive
            }
        }

        if (jenisPaket === 'PAKETMEMBER') {
            var memberData = dataPelangganAll.find(m => m.KodePelanggan == kodePelanggan);
            if (!memberData || memberData.isPaidMembership != 1) return;
            var validMemberships = dataCustomerMemberships.filter(function(m) {
                return m.KodePelanggan == kodePelanggan && (!m.KelompokLampu || m.KelompokLampu == selectedTitik.kelompoklampu);
            });
            if (validMemberships.length === 0) return;
            var durasi = parseInt(document.getElementById('ppDurasi').value) || 0;
            var maxTime = parseInt(validMemberships[0].maxTimePerPlay) || parseInt(memberData.maxTimePerPlay) || 0;
            if (durasi !== maxTime) return;
        }

        var isLangsung = document.querySelector('input[name="OpsiBayar"]:checked').value === 'LANGSUNG';
        if (isLangsung) {
            var grandTotalText = document.getElementById('calcGrandTotal').textContent;
            var grandTotal = parseFormattedRp(grandTotalText);
            var nominalBayar = parseFormattedRp(document.getElementById('ppNominalBayar').value);
            if (nominalBayar < grandTotal) return;
        }

        btn.disabled = false; // Checks passed
    }

    var voucherTypingTimer;
    window.activeVoucherRp = 0;

    function onVoucherInput() {
        clearTimeout(voucherTypingTimer);
        var kode = document.getElementById('ppKodeVoucher').value.trim();
        var statusEl = document.getElementById('ppVoucherStatus');
        
        if (!kode) {
            statusEl.innerHTML = '';
            window.activeVoucherRp = 0;
            calculateTotal();
            return;
        }

        statusEl.innerHTML = '<span style="color:#78909c;">Mengecek voucher...</span>';
        
        voucherTypingTimer = setTimeout(function() {
            var durasi = parseInt(document.getElementById('ppDurasi').value) || 1;
            var hargaInp = document.getElementById('ppHargaNormal').value;
            var harga = parseFormattedRp(hargaInp);
            var subtotal = harga * durasi;

            // Kurangi diskon member dulu sebagai basis check voucher
            var discPer = 0;
            var custId = document.getElementById('ppKodePelanggan').value;
            if(custId) {
                var cust = dataPelangganAll.find(m => m.KodePelanggan == custId);
                if(cust && cust.GroupID) {
                    var grp = dataGrupPelanggan.find(g => g.id == cust.GroupID);
                    if(grp && grp.DiskonPersen) discPer = parseFloat(grp.DiskonPersen);
                }
            }
            subtotal = subtotal - (subtotal * (discPer / 100));
            
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/billing/checkvoucher', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ voucher_code: kode, subtotal: subtotal })
            })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    statusEl.innerHTML = '<span style="color:#43a047;"><i class="fas fa-check"></i> ' + res.message + '</span>';
                    window.activeVoucherRp = res.discountRp || 0;
                } else {
                    statusEl.innerHTML = '<span style="color:#e53935;"><i class="fas fa-times"></i> ' + res.message + '</span>';
                    window.activeVoucherRp = 0;
                }
                calculateTotal(); // update grand total
            })
            .catch(err => {
                statusEl.innerHTML = '<span style="color:#e53935;"><i class="fas fa-exclamation-triangle"></i> Gagal memeriksa voucher.</span>';
                window.activeVoucherRp = 0;
                calculateTotal();
            });
        }, 800); // 800ms debounce
    }

    function onKonfirmasiPaket() {
        if (!selectedTitik) {
            swal("Perhatian", "Silakan pilih titik lampu terlebih dahulu.", "warning");
            return;
        }

        const payload = {
            tableid: selectedTitik.id,
            TglTransaksi: document.getElementById('ppTglTransaksi').value,
            JenisPaket: document.getElementById('ppJenisPaket').value,
            paketid: document.getElementById('ppPaketId').value,
            DurasiPaket: document.getElementById('ppDurasi').value,
            JamMulai: document.getElementById('ppJamMulai').value,
            JamSelesai: document.getElementById('ppJamSelesai').value,
            KodePelanggan: document.getElementById('ppKodePelanggan').value,
            KodeSales: document.getElementById('ppKodeSales').value,
            OpsiBayar: document.querySelector('input[name="OpsiBayar"]:checked').value,
            MetodePembayaran: document.getElementById('ppMetodePembayaran').value,
            NominalBayar: parseFormattedRp(document.getElementById('ppNominalBayar').value),
            KodeVoucher: document.getElementById('ppKodeVoucher').value.trim(),
            ServiceType: document.querySelector('input[name="ppServiceType"]:checked').value,
            NamaDokter: document.getElementById('ppNamaDokter') ? document.getElementById('ppNamaDokter').value : '',
            NamaPasien: document.getElementById('ppNamaPasien') ? document.getElementById('ppNamaPasien').value : '',
            NoResep: document.getElementById('ppNoResep') ? document.getElementById('ppNoResep').value : '',
            fnbItems: ppFnbCart
        };

        // Basic validation
        if (payload.JenisPaket !== 'PAKETMEMBER' && !payload.paketid) {
            swal("Perhatian", "Silakan pilih paket.", "warning");
            return;
        }

        swal({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menyimpan paket ini untuk " + selectedTitik.namatitiklampu + "?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#1a237e",
            confirmButtonText: "Ya, Simpan",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.value) {
                const btn = document.getElementById('ppBtnConfirm');
                const oldHtml = btn.innerHTML;

                // Show loading on button
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch('/billing/store-paket', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => {
                    if (!res.ok) throw new Error('Server error: ' + res.status);
                    return res.json();
                })
                .then(res => {
                    console.log(res);
                    if (res.success) {
                        
                        // Cek apakah ada snap_token untuk pembayaran Midtrans
                        if (res.snap_token) {
                            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menunggu Pembayaran...';
                            
                            const handlers = {
                                onSuccess: function (result) {
                                    // Panggil backend untuk finalize payment
                                    fetch('{{ route("billing-midtrans-success") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': token
                                        },
                                        body: JSON.stringify({ 
                                            NoTransaksi: res.NoTransaksi,
                                            payment_type: 'POS'
                                        })
                                    })
                                    .then(r => r.json())
                                    .then(r => {
                                        showReceiptPreview(res.NoTransaksi);
                                    })
                                    .catch(err => {
                                        swal("Perhatian", "Pembayaran berhasil, tapi sinkronisasi gagal. Harap lapor admin.", "warning").then(() => {
                                            btn.disabled = false;
                                            btn.innerHTML = oldHtml;
                                            closePilihPaketModal();
                                            refreshTableStatuses();
                                        });
                                    });
                                },
                                onPending: function (result) {
                                    swal({
                                        title: "Pembayaran Tertunda",
                                        text: "Selesaikan instruksi pembayaran Anda.",
                                        type: "info"
                                    }).then(() => {
                                        btn.disabled = false;
                                        btn.innerHTML = oldHtml;
                                        closePilihPaketModal();
                                        refreshTableStatuses();
                                    });
                                },
                                onError: function (result) {
                                    btn.disabled = false;
                                    btn.innerHTML = oldHtml;
                                    swal("Gagal", "Pembayaran gagal diproses.", "error");
                                },
                                onClose: function () {
                                    btn.disabled = false;
                                    btn.innerHTML = oldHtml;
                                    
                                    fetch('{{ route("billing-midtrans-cancel") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': token
                                        },
                                        body: JSON.stringify({ 
                                            NoTransaksi: res.NoTransaksi,
                                            payment_type: 'POS'
                                        })
                                    })
                                    .then(() => {
                                        swal("Perhatian", "Anda menutup halaman pembayaran sebelum selesai. Transaksi dibatalkan.", "warning").then(() => {
                                            btn.disabled = false;
                                            btn.innerHTML = oldHtml;
                                            closePilihPaketModal();
                                            refreshTableStatuses();
                                        });
                                    });
                                }
                            };

                            // Show Snap directly on billing
                            window.snap.pay(res.snap_token, handlers);
                        } else {
                            // Flow normal (Cash / Piutang / Metode Manual)
                            var isLangsung = document.querySelector('input[name="OpsiBayar"]:checked').value === 'LANGSUNG';

                             if (isLangsung) {
                                btn.disabled = false;
                                btn.innerHTML = oldHtml;
                                closePilihPaketModal();
                                showReceiptPreview(res.NoTransaksi);   
                            }
                            else{
                                swal("Perhatian", "Order berhasil dibuat", "success").then(() => {
                                    btn.disabled = false;
                                    btn.innerHTML = oldHtml;
                                    closePilihPaketModal();
                                    refreshTableStatuses();
                                });
                            }
                        }
                    } else {
                        // Restore button
                        btn.disabled = false;
                        btn.innerHTML = oldHtml;
                        swal("Gagal", res.message || "Terjadi kesalahan saat menyimpan.", "error");
                    }
                })
                .catch(err => {
                    // Restore button
                    btn.disabled = false;
                    btn.innerHTML = oldHtml;
                    console.error(err);
                    swal("Error", "Gagal menghubungi server.", "error");
                });
            }
        });
    }
    </script>
    <!-- ===== MODAL JUAL FnB STANDALONE ===== -->
    <div id="modalJualFnb" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9000; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:16px; width:98%; max-width:1300px; height:92vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden;">
            <!-- Header -->
            <div style="background:linear-gradient(135deg,#e65100,#ff8f00); color:#fff; padding:16px 24px; display:flex; justify-content:space-between; align-items:center;">
                <h2 style="margin:0; font-size:1.2rem;"><i class="fas fa-utensils"></i> Jual FnB Langsung</h2>
                <button onclick="closeJualFnbModal()" style="background:none; border:none; color:#fff; font-size:1.5rem; cursor:pointer; line-height:1;">&times;</button>
            </div>
            <!-- Body -->
            <div style="flex:1; overflow:hidden; padding:20px; display:flex; gap:20px;">
                <!-- Left: Item Search & Grid (Product Area) -->
                <div style="flex:1; min-width:0; display:flex; flex-direction:column; border-right:1px solid #f0f0f0; padding-right:10px;">
                    <div style="margin-bottom:12px;">
                        <label style="font-size:0.8rem; color:#555; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Pilih Item / Produk</label>
                        <div style="position:relative; margin-top:4px;">
                            <input type="text" id="jualFnbSearchInput" placeholder="Cari Nama Menu atau Barcode..." onkeyup="filterFnbGrid(this.value, 'jualFnbMenuGrid')"
                                style="width:100%; padding:12px 15px; border:2px solid #ffcc80; border-radius:10px; font-size:1rem; box-sizing:border-box; margin-bottom:15px; outline:none; transition:0.3s;"
                                onfocus="this.style.borderColor='#e65100'">
                        </div>
                    </div>
                    
                    <div style="padding:15px; display:grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap:15px; overflow-y:auto;" id="fnbQuickGrid">
                        @foreach($itemmaster as $item)
                            @php $isHabis = ($item->Stock ?? 0) <= 0; @endphp
                            <div class="fnb-card" 
                                 onclick="{{ $isHabis ? "swal('Stok Habis', 'Menu tidak tersedia.', 'warning')" : 'addJualFnbToCart(' . json_encode($item) . ')' }}" 
                                 style="background:#fff; border:1px solid #eee; border-radius:12px; padding:12px; cursor:pointer; transition:all 0.2s; display:flex; flex-direction:column; align-items:center; text-align:center; box-shadow:0 2px 6px rgba(0,0,0,0.06); position:relative; {{ $isHabis ? 'opacity:0.6; filter:grayscale(0.9);' : '' }}">
                                
                                @if($isHabis)
                                    <div style="position:absolute; top:40%; left:50%; transform:translate(-50%,-50%) rotate(-15deg); background:#d32f2f; color:#fff; padding:4px 8px; border-radius:4px; font-weight:800; font-size:0.75rem; z-index:10; border:1px solid #fff; white-space:nowrap; box-shadow:0 2px 8px rgba(0,0,0,0.3);">STOK HABIS</div>
                                @endif

                                <img src="{{ $item->Gambar ? (str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg==' }}" style="width:100px; height:100px; object-fit:cover; border-radius:10px; margin-bottom:10px;">
                                <div style="font-weight:700; font-size:0.9rem; color:#333; height:40px; overflow:hidden; line-height:1.2;">{{ $item->NamaItem }}</div>
                                <div style="font-weight:800; color:#e65100; margin-top:5px; font-size:1rem;">Rp {{ number_format($item->HargaJual) }}</div>
                                <div style="font-size:0.75rem; color:{{ $isHabis ? '#d32f2f' : '#888' }}; margin-top:2px;">Stok: {{ number_format($item->Stock ?? 0) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Cart & Summary & Payment -->
                <div style="width:450px; flex-shrink:0; display:flex; flex-direction:column; gap:16px;">
                    <!-- Cart Table (Rincian) -->
                    <div style="flex:1; border:1px solid #e0e0e0; border-radius:12px; overflow:hidden; display:flex; flex-direction:column; background:#fff;">
                        <div style="padding:10px 15px; background:#f5f7fa; border-bottom:1px solid #eee; font-weight:700; color:#444;">Rincian Pesanan</div>
                        <div style="flex:1; overflow-y:auto;">
                            <table style="width:100%; border-collapse:collapse;">
                                <thead style="background:#fafafa; position:sticky; top:0; z-index:10;">
                                    <tr>
                                        <th style="padding:10px 12px; text-align:left; font-size:0.75rem; color:#888; text-transform:uppercase;">Item</th>
                                        <th style="padding:10px 8px; text-align:center; font-size:0.75rem; color:#888; text-transform:uppercase; width:80px;">Qty</th>
                                        <th style="padding:10px 8px; text-align:right; font-size:0.75rem; color:#888; text-transform:uppercase;">Total</th>
                                        <th style="padding:10px 8px; width:36px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="jualFnbCartItems">
                                    <tr><td colspan="4" style="text-align:center; padding:40px 20px; color:#90a4ae;">Belum ada item terpilih.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary & Payment Details -->
                    <div style="background:#fff; border:1.5px solid #e8eaf6; border-radius:12px; padding:16px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            <!-- Summary -->
                            <div>
                                <div style="font-weight:700; color:#1a237e; margin-bottom:8px; font-size:0.9rem;">Ringkasan</div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:0.8rem; color:#666;"><span>Subtotal</span><span id="jualFnbSubtotal">Rp 0</span></div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:0.8rem; color:#666;"><span>PPN</span><span id="jualFnbPpn">Rp 0</span></div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:0.8rem; color:#666;"><span>Layanan</span><span id="jualFnbLayanan">Rp 0</span></div>
                                <div id="jualFnbAdminRow" style="display:none; justify-content:space-between; margin-bottom:4px; font-size:0.8rem; color:#666;"><span>Admin</span><span id="jualFnbAdminFee">Rp 0</span></div>
                                <div style="display:flex; justify-content:space-between; font-size:1.1rem; font-weight:800; color:#e65100; margin-top:8px; border-top:1px dashed #ccc; padding-top:8px;">
                                    <span>TOTAL</span><span id="jualFnbGrandTotal">Rp 0</span>
                                </div>
                            </div>

                            <!-- Customer & Payment -->
                            <div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                    <label style="font-size:0.75rem; color:#555; font-weight:700;">PELANGGAN</label>
                                    <div style="font-size:0.7rem;">
                                        <input type="checkbox" id="jualFnbIsNewCustomer" onchange="toggleJualFnbNewCustomer()">
                                        <label for="jualFnbIsNewCustomer" style="color:#e65100; font-weight:700; cursor:pointer;">BARU?</label>
                                    </div>
                                </div>
                                <div id="jualFnbExistingCustomerRow" style="margin-bottom:8px;">
                                    <select id="jualFnbPelanggan" class="js-select2" style="width:100%;">
                                        <option value="">-- Pelanggan Umum --</option>
                                        @foreach($pelanggan as $p)
                                            <option value="{{ $p->KodePelanggan }}">{{ $p->NamaPelanggan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="jualFnbNewCustomerRow" style="display:none; margin-bottom:8px;">
                                    <input type="text" id="jualFnbNewNama" placeholder="Nama *" style="width:100%; padding:6px; margin-bottom:4px; border:1px solid #ddd; border-radius:4px; font-size:0.8rem;">
                                    <input type="text" id="jualFnbNewTlp" placeholder="WA *" style="width:100%; padding:6px; border:1px solid #ddd; border-radius:4px; font-size:0.8rem;">
                                </div>

                                <label style="font-size:0.75rem; color:#555; font-weight:700; display:block; margin-bottom:2px;">VOUCHER / DISKON</label>
                                <div style="display:flex; gap:5px; margin-bottom:8px;">
                                    <input type="text" id="jualFnbVoucher" placeholder="Kode Voucher" style="flex:1; padding:6px; border:1px solid #ccc; border-radius:6px; font-size:0.85rem; text-transform:uppercase;">
                                    <button onclick="applyJualFnbVoucher()" style="background:#1a237e; color:#fff; border:none; border-radius:6px; padding:0 12px; font-size:0.75rem; font-weight:700;">CEK</button>
                                </div>

                                <label style="font-size:0.75rem; color:#555; font-weight:700; display:block; margin-bottom:2px;">METODE BAYAR</label>
                                <select id="jualFnbMetode" onchange="calculateJualFnbTotal()" style="width:100%; padding:6px; border:1px solid #ccc; border-radius:6px; font-size:0.85rem; margin-bottom:8px;">
                                    @foreach($metodepembayaran as $mp)
                                        <option value="{{ $mp->id }}" data-percent="{{ $mp->AdminFeePercent ?? 0 }}" data-rupiah="{{ $mp->AdminFeeRupiah ?? 0 }}" data-tipe="{{ $mp->TipePembayaran ?? '' }}" data-nama="{{ $mp->NamaMetodePembayaran }}">{{ $mp->NamaMetodePembayaran }}</option>
                                    @endforeach
                                </select>

                                <label style="font-size:0.75rem; color:#555; font-weight:700; display:block; margin-bottom:2px;">NOMINAL BAYAR</label>
                                <input type="text" id="jualFnbNominal" placeholder="0" oninput="onJualFnbNominalChange()" style="width:100%; padding:8px; border:1.5px solid #2e7d32; border-radius:6px; font-size:1rem; font-weight:700; text-align:right;">
                                
                                <div style="display:flex; justify-content:space-between; font-size:0.8rem; font-weight:700; margin-top:8px;">
                                    <span style="color:#555;">KEMBALI:</span>
                                    <span id="jualFnbKembalian" style="color:#2e7d32;">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button id="jualFnbBtnSubmit" onclick="submitJualFnb()" style="width:100%; background:linear-gradient(135deg,#2e7d32,#43a047); color:#fff; border:none; border-radius:10px; padding:14px; font-size:1.1rem; font-weight:800; cursor:pointer; margin-top:15px; box-shadow:0 4px 10px rgba(46,125,50,0.2);">
                            <i class="fas fa-print"></i> PROSES TRANSAKSI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // ===== JUAL FnB STANDALONE =====
    let jualFnbCart = [];

    function openJualFnbModal() {
        jualFnbCart = [];
        updateJualFnbCartTable();
        $('#jualFnbSearchInput').val('');
        $('#jualFnbSearchResults').hide();
        calculateJualFnbTotal();
        document.getElementById('modalJualFnb').style.display = 'flex';
    }

    function closeJualFnbModal() {
        document.getElementById('modalJualFnb').style.display = 'none';
    }

    function toggleJualFnbNewCustomer() {
        const isNew = $('#jualFnbIsNewCustomer').is(':checked');
        if (isNew) {
            $('#jualFnbExistingCustomerRow').hide();
            $('#jualFnbNewCustomerRow').show();
        } else {
            $('#jualFnbExistingCustomerRow').show();
            $('#jualFnbNewCustomerRow').hide();
        }
    }

    // Close on backdrop click
    document.getElementById('modalJualFnb').addEventListener('click', function(e) {
        if (e.target === this) closeJualFnbModal();
    });

    function searchJualFnbItems(query) {
        let $results = $('#jualFnbSearchResults');
        if (query.length < 2) { $results.hide(); return; }
        $.ajax({
            url: "{{ route('itemmaster-GetStockPerWhs') }}",
            method: 'POST',
            data: { 
                Scan: query, 
                Active: 'Y', 
                TipeItemIN: '1,2,3,5',
                KodeGudang: "{{ $company[0]->GudangPoS ?? 'GDG01' }}"
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if (res.data && res.data.length > 0) {
                    let html = res.data.map(item => `
                        <div onclick='addJualFnbToCart(${JSON.stringify(item).replace(/"/g, "&quot;")})'
                            style="padding:10px 12px; cursor:pointer; border-bottom:1px solid #f0f0f0; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-weight:600; font-size:0.88rem;">${item.NamaItem}</div>
                                <div style="font-size:0.75rem; color:#999;">${item.KodeItem}</div>
                            </div>
                            <div style="font-weight:700; color:#2e7d32; font-size:0.88rem;">${formatRp(item.HargaJual)}</div>
                        </div>`).join('');
                    $results.html(html).show();
                } else {
                    $results.html('<div style="padding:10px; color:#999; font-size:0.88rem;">Tidak ditemukan.</div>').show();
                }
            }
        });
    }

    function addJualFnbToCart(item) {
        let existing = jualFnbCart.find(c => c.KodeItem === item.KodeItem);
        if (existing) { existing.Qty += 1; }
        else { jualFnbCart.push({ KodeItem: item.KodeItem, NamaItem: item.NamaItem, Harga: item.HargaJual, Satuan: item.Satuan || 'PCS', Qty: 1 }); }
        $('#jualFnbSearchInput').val('');
        $('#jualFnbSearchResults').hide();
        updateJualFnbCartTable();
    }

    function changeJualFnbQty(index, delta) {
        jualFnbCart[index].Qty = Math.max(1, jualFnbCart[index].Qty + delta);
        updateJualFnbCartTable();
    }

    function setJualFnbQty(index, val) {
        let v = parseInt(val);
        if (v < 1 || isNaN(v)) v = 1;
        jualFnbCart[index].Qty = v;
        updateJualFnbCartTable();
    }

    function removeJualFnbItem(index) {
        jualFnbCart.splice(index, 1);
        updateJualFnbCartTable();
    }

    function updateJualFnbCartTable() {
        let $body = $('#jualFnbCartItems');
        if (jualFnbCart.length === 0) {
            $body.html('<tr><td colspan="4" style="text-align:center; padding:40px 20px; color:#90a4ae;">Belum ada item terpilih.</td></tr>');
        } else {
            let html = jualFnbCart.map((item, i) => {
                let sub = item.Qty * item.Harga;
                return `<tr>
                    <td style="padding:10px 12px; font-size:0.85rem;">
                        <div style="font-weight:600;">${item.NamaItem}</div>
                        <div style="font-size:0.75rem; color:#888;">@ ${formatRp(item.Harga)}</div>
                    </td>
                    <td style="padding:8px;">
                        <div style="display:flex; align-items:center; gap:2px; justify-content:center;">
                            <button type="button" class="pp-dur-btn" style="padding:2px 6px; font-size:0.7rem;" onclick="changeJualFnbQty(${i}, -1)">−</button>
                            <input type="number" class="pp-input" style="width:36px; text-align:center; padding:2px; font-size:0.85rem; font-weight:700; border:none; background:transparent;" value="${item.Qty}" onchange="setJualFnbQty(${i}, this.value)">
                            <button type="button" class="pp-dur-btn" style="padding:2px 6px; font-size:0.7rem;" onclick="changeJualFnbQty(${i}, 1)">+</button>
                        </div>
                    </td>
                    <td style="padding:8px; text-align:right; font-size:0.85rem; font-weight:700; color:#e65100;">${formatRp(sub)}</td>
                    <td style="padding:8px; text-align:center;"><button type="button" onclick="removeJualFnbItem(${i})" style="background:none; border:none; color:#e53935; cursor:pointer; font-size:0.8rem;"><i class="fas fa-trash"></i></button></td>
                </tr>`;
            }).join('');
            $body.html(html);
        }
        calculateJualFnbTotal();
    }

    function calculateJualFnbTotal(isFromInput = false) {
        let subtotal = jualFnbCart.reduce((s, i) => s + i.Qty * i.Harga, 0);
        let ppnPersen = parseFloat($('#jualFnbPpnPersen').text()) || 0;
        let servicePersen = parseFloat($('#jualFnbServicePersen').text()) || 0;

        let ppnRp = subtotal * (ppnPersen / 100);
        let serviceRp = subtotal * (servicePersen / 100);

        let $opt = $('#jualFnbMetode option:selected');
        let adminPercent = parseFloat($opt.data('percent')) || 0;
        let adminRupiah = parseFloat($opt.data('rupiah')) || 0;
        let tipe = $opt.data('tipe') || '';

        let subtotalWithTax = subtotal + ppnRp + serviceRp;
        let adminFee = adminPercent > 0 ? subtotalWithTax * (adminPercent / 100) : (adminRupiah > 0 ? adminRupiah : 0);
        let grandTotal = Math.round(subtotalWithTax + adminFee);

        $('#jualFnbSubtotal').text(formatRp(Math.round(subtotal)));
        $('#jualFnbPpn').text(formatRp(Math.round(ppnRp)));
        $('#jualFnbLayanan').text(formatRp(Math.round(serviceRp)));
        $('#jualFnbGrandTotal').text(formatRp(grandTotal));

        if (adminFee > 0) {
            $('#jualFnbAdminFee').text(formatRp(Math.round(adminFee)));
            $('#jualFnbAdminRow').css('display', 'flex');
        } else {
            $('#jualFnbAdminRow').hide();
        }

        const nominalInp = document.getElementById('jualFnbNominal');
        if (tipe === 'NON TUNAI' || tipe === 'NONTUNAI') {
            nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f3f6f9';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
        }

        let nominal = parseFormattedRp(nominalInp.value || '0');
        let kembalian = nominal - grandTotal;
        if (kembalian < 0) {
            $('#jualFnbKembalian').text('Kurang: ' + formatRp(Math.abs(kembalian))).css('color', '#c62828');
        } else {
            $('#jualFnbKembalian').text(formatRp(kembalian)).css('color', '#2e7d32');
        }
    }

    function onJualFnbNominalChange() {
        calculateJualFnbTotal(true);
    }

    function submitJualFnb() {
        if (jualFnbCart.length === 0) {
            swal("Perhatian", "Tidak ada item di keranjang.", "warning");
            return;
        }

        let grandTotalText = $('#jualFnbGrandTotal').text();
        let grandTotal = parseFormattedRp(grandTotalText);
        let nominal = parseFormattedRp($('#jualFnbNominal').val() || '0');

        if (nominal < grandTotal) {
            swal("Perhatian", "Nominal bayar kurang dari Grand Total.", "warning");
            return;
        }

        swal({
            title: "Konfirmasi Pembayaran",
            text: "Proses pembayaran FnB sebesar " + formatRp(grandTotal) + "?",
            type: "question",
            showCancelButton: true,
            confirmButtonColor: "#e65100",
            confirmButtonText: "Ya, Bayar",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (!result.value) return;

            const $btn = $('#jualFnbBtnSubmit');
            const oldHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            fetch('{{ route("billing-jual-fnb-standalone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    items: jualFnbCart,
                    MetodePembayaranId: $('#jualFnbMetode').val(),
                    NominalBayar: nominal,
                    isNewCustomer: $('#jualFnbIsNewCustomer').is(':checked'),
                    KodePelanggan: $('#jualFnbPelanggan').val(),
                    NamaPelanggan: $('#jualFnbNewNama').val(),
                    NoTlp1: $('#jualFnbNewTlp').val(),
                    Email: $('#jualFnbNewEmail').val()
                })
            })
            .then(r => r.json())
            .then(r => {
                if (r.success) {
                    if (r.snap_token) {
                        $btn.html('<i class="fas fa-spinner fa-spin"></i> Menunggu Pembayaran...');
                        window.snap.pay(r.snap_token, {
                            onSuccess: function (result) {
                                fetch('{{ route("billing-midtrans-success") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: r.invoiceNo,
                                        payment_type: 'JUAL_FNB'
                                    })
                                })
                                .then(res => res.json())
                                .then(res => {
                                    if (res.success) {
                                        swal({ title: "Berhasil!", text: "Penjualan berhasil disimpan.\nNo Faktur: " + res.invoiceNo, type: "success", timer: 2000, showConfirmButton: true })
                                            .then(() => {
                                                closeJualFnbModal();
                                                showReceiptPreview(res.invoiceNo);
                                            });
                                    } else {
                                        swal("Gagal", res.message || "Gagal finalisasi data.", "error");
                                    }
                                });
                            },
                            onPending: function (result) {
                                swal("Info", "Selesaikan pembayaran Anda.", "info").then(() => closeJualFnbModal());
                            },
                            onError: function (result) {
                                $btn.prop('disabled', false).html(oldHtml);
                                swal("Gagal", "Pembayaran gagal.", "error");
                            },
                            onClose: function () {
                                $btn.prop('disabled', false).html(oldHtml);
                                swal("Batal", "Pembayaran dibatalkan.", "warning");
                            }
                        });
                    } else {
                        $btn.prop('disabled', false).html(oldHtml);
                        let msg = 'No Faktur: ' + r.invoiceNo;
                        if (r.kembalian > 0) msg += '\nKembalian: ' + formatRp(r.kembalian);
                        swal({ title: "Berhasil!", text: msg, type: "success", timer: 2000, showConfirmButton: true })
                            .then(() => {
                                closeJualFnbModal();
                                showReceiptPreview(r.invoiceNo);
                            });
                    }
                } else {
                    $btn.prop('disabled', false).html(oldHtml);
                    swal("Gagal", r.message || "Terjadi kesalahan.", "error");
                }
            })
            .catch(err => {
                $btn.prop('disabled', false).html(oldHtml);
                swal("Error", "Gagal menghubungi server.", "error");
            });
        });
    }

    $(document).ready(function() {
        $('.js-select2').select2({
            dropdownParent: $('#modalJualFnb')
        });
    });
    </script>

    <script src="{{ asset('js/sweetalert.js') }}"></script>

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
