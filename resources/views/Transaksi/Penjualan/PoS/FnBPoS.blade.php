<!DOCTYPE html>
<!--
Template Name: Kundol Admin - Bootstrap 4 HTML Admin Dashboard Theme
Author: Themes-coder
Website: https://themes-coder.com/
Contact: sales@themes-coder.com
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
	
	<meta charset="utf-8" />
	<title>Admin | Dashboard</title>
	<meta name="description" content="Updates and statistics" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!--end::Fonts-->

	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="{{ asset('css/style.css?v=1.0')}}" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->

	<link href="{{ asset('api/pace/pace-theme-flat-top.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('api/mcustomscrollbar/jquery.mCustomScrollbar.css')}}" rel="stylesheet" type="text/css" />
	
	{{-- <link href="http://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" /> --}}
	<link href="{{asset('api/datatable/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link href="{{asset('api/select2/select2.min.css')}}" rel="stylesheet" />

	<link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico')}}" />

	<style type="text/css">
		:root {
			--primary: #0b57d0; /* PT. DSTECH Royal Blue */
			--primary-hover: #0842a0;
			--secondary: #dc2626; /* PT. DSTECH Action Crimson Red */
			--secondary-hover: #b91c1c;
			--accent: #10b981; /* Neon Emerald */
			--accent-hover: #059669;
			--dark: #0f172a;
			--light: #f8fafc;
			--border-color: rgba(11, 87, 208, 0.08);
			--glass-bg: rgba(255, 255, 255, 0.9);
			--glass-border: rgba(255, 255, 255, 0.5);
			--shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.03);
			--shadow-md: 0 15px 35px -10px rgba(11, 87, 208, 0.12);
			--shadow-lg: 0 30px 60px -15px rgba(11, 87, 208, 0.22);
			--premium-gradient: linear-gradient(135deg, #094cb4 0%, #00bcff 100%); /* PT. DSTECH Royal to Cyan Gradient */
		}

		/* Custom Toggle Switch for Table Light */
		.switch-lampu {
			position: relative;
			display: inline-block;
			width: 50px;
			height: 26px;
		}

		.switch-lampu input {
			opacity: 0;
			width: 0;
			height: 0;
		}

		.slider-lampu {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #cbd5e1;
			transition: .3s;
			border-radius: 34px;
		}

		.slider-lampu:before {
			position: absolute;
			content: "";
			height: 18px;
			width: 18px;
			left: 4px;
			bottom: 4px;
			background-color: white;
			transition: .3s;
			border-radius: 50%;
			box-shadow: 0 2px 4px rgba(0,0,0,0.15);
		}

		input:checked + .slider-lampu {
			background-color: #f59e0b; /* Amber/Yellow for lighting */
		}

		input:focus + .slider-lampu {
			box-shadow: 0 0 1px #f59e0b;
		}

		input:checked + .slider-lampu:before {
			transform: translateX(24px);
		}


		body {
			font-family: 'Outfit', sans-serif !important;
			background: radial-gradient(at 0% 0%, rgba(11, 87, 208, 0.12) 0px, transparent 50%),
			            radial-gradient(at 50% 0%, rgba(220, 38, 38, 0.08) 0px, transparent 50%),
			            radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.08) 0px, transparent 50%),
			            linear-gradient(rgba(255, 255, 255, 0.82), rgba(255, 255, 255, 0.82)),
			            url('{{ asset("images/misc/bg-login3.jpg") }}') !important;
			background-size: cover, cover, cover, cover, 100% 100% !important; /* Image stretches 100% 100% to fit screen paper completely as a single piece */
			background-position: top center !important;
			background-repeat: no-repeat !important;
			background-attachment: fixed !important;
			color: #1e293b !important;
			overflow: hidden !important;
			height: 100vh !important;
			position: relative;
		}

		/* Premium Grid Overlay */
		body::before {
			content: '';
			position: absolute;
			top: 0; left: 0; right: 0; bottom: 0;
			background-image: linear-gradient(rgba(11, 87, 208, 0.015) 1px, transparent 1px),
			                  linear-gradient(90deg, rgba(11, 87, 208, 0.015) 1px, transparent 1px);
			background-size: 40px 40px;
			pointer-events: none;
			z-index: -1;
		}

		/* Smooth Page Loader */
		.pace {
			background: var(--primary) !important;
		}

		/* Premium Header - Make solid white glassmorphism to cover the busy background image watermark and make text/logo highly visible */
		.pos-header {
			background: rgba(255, 255, 255, 0.95) !important;
			backdrop-filter: blur(12px) !important;
			-webkit-backdrop-filter: blur(12px) !important;
			border-bottom: 1.5px solid rgba(11, 87, 208, 0.25) !important;
			padding: 0.5rem 1.5rem !important;
			box-shadow: 0 4px 18px rgba(11, 87, 208, 0.08) !important;
			position: sticky;
			top: 0;
			z-index: 1000;
			height: 68px;
		}

		/* Sleek Glass Welcome Greeting Badge on the left */
		.greeting-text {
			margin-left: 95px; /* Safely clears the printed company logo in top-left banner */
			background: rgba(255, 255, 255, 0.92) !important;
			padding: 6px 14px !important;
			border-radius: 12px !important;
			border: 1px solid rgba(11, 87, 208, 0.25) !important;
			box-shadow: 0 4px 12px rgba(11, 87, 208, 0.1);
			backdrop-filter: blur(8px);
			-webkit-backdrop-filter: blur(8px);
			display: inline-flex;
			align-items: center;
			gap: 6px;
			height: 38px;
			transition: all 0.2s ease;
		}
		.greeting-text:hover {
			transform: translateY(-1px);
			box-shadow: 0 6px 16px rgba(11, 87, 208, 0.15);
		}

		/* Digital Neon Clock Glass Card */
		.clock-main {
			background: rgba(255, 255, 255, 0.92) !important;
			border: 1.5px solid rgba(11, 87, 208, 0.3) !important;
			border-radius: 50px !important;
			padding: 0.3rem 1.5rem !important;
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
			backdrop-filter: blur(8px);
			-webkit-backdrop-filter: blur(8px);
			height: 38px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.clock .datetime-content ul {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 2px;
			margin: 0;
			padding: 0;
			list-style: none;
		}

		.clock .datetime-content ul li {
			font-weight: 800;
			font-size: 1.15rem;
			color: var(--primary) !important;
			letter-spacing: 0.5px;
		}

		#Date {
			font-size: 0.75rem;
			font-weight: 600;
			color: #475569;
			text-align: center;
			margin-top: 2px;
			text-transform: uppercase;
			letter-spacing: 1px;
		}

		/* Content Wrap */
		.contentPOS {
			padding: 0.35rem 0 !important;
		}

		.contentPOS .card {
			margin-bottom: 10px !important;
		}

		/* Premium Tactile Cards */
		.card {
			background: rgba(255, 255, 255, 0.85) !important;
			backdrop-filter: blur(20px);
			-webkit-backdrop-filter: blur(20px);
			border-radius: 24px !important;
			border: 1px solid rgba(255, 255, 255, 0.6) !important;
			box-shadow: var(--shadow-md) !important;
			transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease;
			margin-bottom: 1.5rem !important;
		}

		.card:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg) !important;
			border-color: rgba(79, 70, 229, 0.25) !important;
		}

		.card-header {
			background: transparent !important;
			border-bottom: 1.5px solid var(--border-color) !important;
			padding: 1.25rem 1.5rem !important;
		}

		.card-body {
			padding: 1.5rem !important;
		}

		/* Compact Doc Badge */
		.doc-badge-container {
			background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
			border-radius: 14px;
			padding: 0.5rem 1rem;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			border: 1px solid rgba(0,0,0,0.04);
		}

		#_NoTransaksi {
			font-weight: 800 !important;
			font-size: 1.15rem !important;
			color: var(--primary) !important;
			letter-spacing: 0.5px;
		}

		/* Inputs Redesign */
		.form-control, select.form-control {
			border-radius: 14px !important;
			border: 1.5px solid #cbd5e1 !important;
			padding: 0.75rem 1rem !important;
			font-size: 0.95rem !important;
			color: #1e293b !important;
			background-color: #ffffff !important;
			box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.01) !important;
			transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
		}

		.form-control:focus {
			border-color: var(--primary) !important;
			box-shadow: 0 0 0 4px rgba(0, 80, 157, 0.12) !important;
			background-color: #ffffff !important;
		}

		.input-group-text {
			border-radius: 14px !important;
			background-color: #f1f5f9 !important;
			border: 1.5px solid #cbd5e1 !important;
		}

		/* Select2 Premium Override */
		.select2-container--default .select2-selection--single {
			border-radius: 14px !important;
			border: 1.5px solid #cbd5e1 !important;
			height: 40px !important;
			padding: 5px 12px !important;
			background-color: #ffffff !important;
			transition: all 0.2s ease !important;
		}

		.select2-container--default .select2-selection--single:focus,
		.select2-container--default.select2-container--open .select2-selection--single {
			border-color: var(--primary) !important;
			box-shadow: 0 0 0 4px rgba(0, 80, 157, 0.12) !important;
		}

		.select2-selection__arrow {
			height: 38px !important;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			line-height: 28px !important;
			color: #1e293b !important;
			font-weight: 800 !important;
			padding-left: 0 !important;
		}

		/* Advanced Touch NumPad Styles */
		.numpad-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 8px;
		}
		.btn-numpad {
			background: rgba(255, 255, 255, 0.8) !important;
			backdrop-filter: blur(5px);
			border: 1.5px solid #cbd5e1 !important;
			color: #1e293b !important;
			height: 38px !important;
			border-radius: 14px !important;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02), inset 0 2px 0 rgba(255,255,255,0.2) !important;
			transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
			display: flex !important;
			flex-direction: column !important;
			align-items: center !important;
			justify-content: center !important;
			padding: 2px !important;
		}
		.btn-numpad:hover {
			background: #f1f5f9 !important;
			border-color: #94a3b8 !important;
			transform: translateY(-1px);
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
		}
		.btn-numpad:active {
			transform: translateY(1px);
			box-shadow: none !important;
		}
		.numpad-num {
			font-size: 1.3rem !important;
			font-weight: 950 !important;
			line-height: 1;
		}
		.numpad-sub {
			font-size: 0.65rem !important;
			color: #64748b !important;
			font-weight: 800 !important;
			letter-spacing: 0.5px;
			margin-top: 1px;
		}

		/* Compact QWERTY Touch Keypad Styles */
		.btn-qwerty {
			background: rgba(255, 255, 255, 0.95) !important;
			border: 1.5px solid #cbd5e1 !important;
			color: #0f172a !important;
			font-weight: 950 !important;
			font-size: 1.25rem !important;
			height: 38px !important;
			min-width: 36px;
			padding: 0 !important;
			border-radius: 12px !important;
			box-shadow: 0 2px 4px rgba(0,0,0,0.04), inset 0 2px 0 rgba(255,255,255,0.2) !important;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
			flex: 1;
		}
		.btn-qwerty:hover {
			background: #f8fafc !important;
			border-color: #94a3b8 !important;
			transform: translateY(-1px);
		}
		.btn-qwerty:active {
			background: #e2e8f0 !important;
			transform: translateY(1px);
		}
		.btn-qwerty-action {
			background: #f1f5f9 !important;
			border-color: #cbd5e1 !important;
		}
		.btn-numpad-action {
			background: rgba(79, 70, 229, 0.06) !important;
			border: 1.5px solid rgba(79, 70, 229, 0.18) !important;
			color: var(--primary) !important;
			font-weight: 950 !important;
			font-size: 1.1rem !important;
			height: 38px !important;
			border-radius: 14px !important;
			transition: all 0.15s ease !important;
		}
		.btn-numpad-action:hover, .btn-numpad-action.active {
			background: var(--primary) !important;
			color: white !important;
			border-color: var(--primary) !important;
			box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
		}
		.btn-numpad-clear {
			background: rgba(244, 63, 94, 0.06) !important;
			border: 1.5px solid rgba(244, 63, 94, 0.18) !important;
			color: var(--secondary) !important;
			font-weight: 950 !important;
			font-size: 1.2rem !important;
			height: 38px !important;
			border-radius: 14px !important;
			transition: all 0.15s ease !important;
		}
		.btn-numpad-clear:hover {
			background: var(--secondary) !important;
			color: white !important;
			border-color: var(--secondary) !important;
			box-shadow: 0 4px 12px rgba(244, 63, 94, 0.2) !important;
		}
		.btn-numpad-enter {
			background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
			border: none !important;
			color: white !important;
			font-weight: 950 !important;
			font-size: 1.2rem !important;
			height: 38px !important;
			border-radius: 14px !important;
			box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35) !important;
			transition: all 0.15s ease !important;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.btn-numpad-enter:hover {
			box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45) !important;
			transform: translateY(-1px) scale(1.02);
		}

		/* Compact Product Card Grid Catalog Styles */
		.catalog-product-card {
			background: rgba(255, 255, 255, 0.85) !important;
			backdrop-filter: blur(10px);
			border-radius: 12px !important;
			border: 1.5px solid rgba(255, 255, 255, 0.6) !important;
			padding: 0.5rem !important;
			cursor: pointer;
			transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01) !important;
			position: relative;
			overflow: hidden;
			height: 165px;
			display: flex;
			flex-direction: column;
			justify-content: flex-start;
			gap: 4px;
		}
		.catalog-product-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 16px rgba(79, 70, 229, 0.1) !important;
			border-color: rgba(79, 70, 229, 0.3) !important;
			background: #ffffff !important;
		}
		.catalog-product-card:hover img {
			transform: scale(1.06);
		}
		.catalog-product-title {
			font-size: 0.72rem;
			font-weight: 750;
			color: #1e293b;
			line-height: 1.25;
			margin: 0;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			height: 2.5em;
			flex-grow: 1;
		}
		.catalog-product-footer {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-top: auto;
			padding-top: 4px;
		}
		.catalog-product-code {
			font-size: 0.62rem;
			font-weight: 600;
			color: #94a3b8;
		}
		.catalog-product-price {
			font-size: 0.78rem;
			font-weight: 850;
			color: #059669;
		}
		.catalog-product-badge {
			position: relative !important;
			background: rgba(79, 70, 229, 0.08);
			color: var(--primary);
			font-size: 0.55rem;
			font-weight: 750;
			padding: 1px 4px;
			border-radius: 4px;
			white-space: nowrap;
			align-self: flex-start;
		}

		/* Alfamart Style Colorful Grid Shortcuts (Sleek and Compact) */
		.alfamart-shortcut-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
			gap: 8px;
			margin-top: 0.5rem;
		}
		.btn-alfamart-tile {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 0.35rem 0.5rem !important;
			border-radius: 12px !important;
			color: white !important;
			font-weight: 800 !important;
			font-size: 0.75rem !important;
			cursor: pointer;
			transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
			border: none !important;
			box-shadow: 0 2px 6px rgba(0,0,0,0.05);
			height: 55px;
		}
		.btn-alfamart-tile:hover {
			transform: translateY(-2px);
			box-shadow: 0 6px 15px rgba(0,0,0,0.12) !important;
			filter: brightness(1.08);
		}
		.btn-alfamart-tile:active {
			transform: translateY(1px);
			box-shadow: none !important;
		}
		.btn-alfamart-tile kbd {
			background: rgba(255, 255, 255, 0.25);
			border-radius: 4px;
			padding: 1px 6px;
			font-size: 0.68rem;
			font-weight: 900;
			margin-bottom: 2px;
			color: white;
			box-shadow: 0 1px 2px rgba(0,0,0,0.1);
		}
		.tile-f2 { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; }
		.tile-f3 { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important; }
		.tile-f4 { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important; }
		.tile-f5 { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
		.tile-f6 { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; }
		.tile-f7 { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important; }
		.tile-del { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important; }

		/* Barcode Input Glowing Scan line */
		.barcode-wrapper {
			position: relative;
		}

		.barcode-wrapper input {
			padding-left: 45px !important;
			font-weight: 600;
			letter-spacing: 0.5px;
		}

		.barcode-icon {
			position: absolute;
			left: 16px;
			top: 50%;
			transform: translateY(-50%);
			color: #94a3b8;
			z-index: 10;
			pointer-events: none;
			transition: color 0.2s ease;
		}

		.barcode-wrapper input:focus + .barcode-icon {
			color: var(--primary);
			animation: pulseScan 1.5s infinite;
		}

		@keyframes pulseScan {
			0% { opacity: 0.5; }
			50% { opacity: 1; }
			100% { opacity: 0.5; }
		}

		/* DX DataGrid Premium Makeover */
		.dx-datagrid {
			border-radius: 16px !important;
			overflow: hidden !important;
			border: 1px solid var(--border-color) !important;
		}

		.dx-header-row {
			background-color: #f8fafc !important;
			font-weight: 700 !important;
			color: #1e293b !important;
		}

		.dx-row-alt {
			background-color: #f8fafc/50 !important;
		}

		/* Right Column: Premium Digital Receipt Tape Container */
		.receipt-tape {
			background: rgba(255, 255, 255, 0.95) !important;
			backdrop-filter: blur(20px);
			-webkit-backdrop-filter: blur(20px);
			border-radius: 28px !important;
			border: 2px dashed rgba(79, 70, 229, 0.25) !important;
			padding: 1.75rem !important;
			position: relative;
			box-shadow: var(--shadow-lg) !important;
		}

		.receipt-tape::before {
			content: '';
			position: absolute;
			top: -4px; left: 10px; right: 10px;
			height: 8px;
			background-image: radial-gradient(circle, rgba(79, 70, 229, 0.2) 4px, transparent 5px);
			background-size: 12px 8px;
		}

		.receipt-tape::after {
			content: '';
			position: absolute;
			bottom: -4px; left: 10px; right: 10px;
			height: 8px;
			background-image: radial-gradient(circle, rgba(79, 70, 229, 0.2) 4px, transparent 5px);
			background-size: 12px 8px;
		}

		.shop-profile {
			border-bottom: 1.5px dashed rgba(79, 70, 229, 0.15);
			padding-bottom: 1.25rem;
			margin-bottom: 1.25rem;
		}

		.shop-profile .media .bg-primary {
			border-radius: 16px !important;
			font-weight: 900;
			box-shadow: 0 8px 24px rgba(79, 70, 229, 0.25);
			background: var(--premium-gradient) !important;
		}

		.shop-profile .title {
			font-size: 1.15rem;
			color: #0f172a;
			margin-bottom: 4px;
			font-weight: 850;
		}

		.shop-profile p {
			margin-bottom: 2px;
			font-size: 0.8rem;
			color: #475569;
		}

		.right-table th {
			font-size: 0.9rem;
			color: #475569 !important;
			font-weight: 700 !important;
		}

		.TotalText {
			font-family: 'Courier New', Courier, monospace;
			font-weight: 800 !important;
			font-size: 1.35rem !important;
			color: #0f172a !important;
			text-align: right;
			padding: 0 !important;
			background: transparent !important;
			border: none !important;
			box-shadow: none !important;
		}

		/* Glowing LED Total Tagihan Board */
		.premium-total-card {
			background: linear-gradient(135deg, #0f172a 0%, #030712 100%) !important;
			border: 2.5px solid rgba(0, 255, 196, 0.65) !important;
			color: #ffffff;
			border-radius: 24px !important;
			padding: 1.15rem 1.5rem !important;
			position: relative;
			overflow: hidden;
			box-shadow: 0 25px 50px rgba(0, 255, 196, 0.25), inset 0 2px 4px rgba(255, 255, 255, 0.05);
		}

		.premium-total-card::after {
			content: '';
			position: absolute;
			top: -40%; left: -40%;
			width: 180%; height: 180%;
			background: radial-gradient(circle, rgba(0, 255, 196, 0.15) 0%, transparent 60%);
			pointer-events: none;
		}

		#_GrandTotal {
			font-family: 'Courier New', Courier, monospace !important;
			font-size: 3.35rem !important;
			font-weight: 950 !important;
			height: auto !important;
			color: #00ffc4 !important; /* Premium Cyan Neon Glow */
			text-shadow: 0 0 20px rgba(0, 255, 196, 0.95), 0 0 3px rgba(0, 255, 196, 1) !important;
			text-align: right !important;
			border: none !important;
			background: transparent !important;
			padding: 0 !important;
			box-shadow: none !important;
		}

		/* Action Grid Buttons */
		.buttons-cash {
			margin-top: 1.5rem;
			width: 100%;
		}

		.buttons-cash > div {
			display: grid;
			grid-template-columns: 1fr;
			gap: 10px;
			width: 100%;
		}

		.btn-primary {
			background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; /* Green for Bayar */
			border: none !important;
			box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35) !important;
			padding: 0.9rem !important;
			font-size: 1.05rem !important;
		}

		.btn-primary:hover {
			transform: translateY(-2px) scale(1.01);
			box-shadow: 0 12px 30px rgba(16, 185, 129, 0.45) !important;
		}

		.btn-danger {
			background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%) !important; /* Crimson red for cancel */
			border: none !important;
			box-shadow: 0 6px 20px rgba(244, 63, 94, 0.25) !important;
			padding: 0.9rem !important;
		}

		.btn-danger:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 25px rgba(244, 63, 94, 0.35) !important;
		}

		.btn-secondary {
			background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; /* Gold/orange for pending draft */
			border: none !important;
			color: white !important;
			box-shadow: 0 6px 20px rgba(245, 158, 11, 0.2) !important;
			padding: 0.9rem !important;
		}

		.btn-secondary:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3) !important;
		}

		/* Modal styling overrides */
		.modal-content {
			border-radius: 28px !important;
			border: none !important;
			box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
			overflow: hidden;
		}

		.modal-header {
			background: #f8fafc !important;
			border-bottom: 1.5px solid var(--border-color) !important;
			padding: 1.5rem 2rem !important;
		}

		.modal-title {
			font-weight: 800;
			color: #0f172a;
		}

		.modal-body {
			padding: 2rem !important;
		}

		/* Payment Horizontal List styling */
		.horizontal-list {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
			gap: 12px;
			padding: 0;
			list-style: none;
			margin: 0;
		}

		.horizontal-list li {
			border: 2px solid #e2e8f0 !important;
			border-radius: 16px !important;
			padding: 1rem !important;
			text-align: center;
			cursor: pointer;
			background: #ffffff;
			transition: all 0.2s ease;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			gap: 8px;
		}

		.horizontal-list li:hover {
			border-color: var(--primary) !important;
			background: rgba(0, 80, 157, 0.02);
			transform: translateY(-2px);
		}

		.horizontal-list li.active {
			border-color: var(--primary) !important;
			background: rgba(0, 80, 157, 0.05) !important;
			box-shadow: 0 0 0 3px rgba(0, 80, 157, 0.15);
		}

		.horizontal-list li span img {
			max-height: 35px;
			object-fit: contain;
		}

		.horizontal-list li .list-title {
			font-size: 0.8rem;
			font-weight: 700;
			color: #334155;
		}

		/* Custom scrollbar */
		.scrollbar-1::-webkit-scrollbar {
			width: 6px;
			height: 6px;
		}
		.scrollbar-1::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 10px;
		}

	</style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="tc_body" class="header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed">
   <!-- Paste this code after body tag -->
   <!-- s -->
   <!-- pos header -->

   <header class="pos-header">
	   <div class="container-fluid">
		   <div class="row align-items-center" style="height: 52px;">
			   <div class="col-xl-4 col-lg-4 col-md-6 d-flex align-items-center" style="position: relative;">
				   <!-- Logo Perusahaan Client -->
				   <div class="logo-container" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%);">
					   <?php $companyData = json_decode($company, true); ?>
					   @if(!empty($companyData[0]['icon']))
						   <img src="{{ $companyData[0]['icon'] }}" alt="Logo" style="height: 42px; max-width: 90px; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(9, 76, 180, 0.15); background: white; border: 1.5px solid rgba(9, 76, 180, 0.2); padding: 2px;">
					   @else
						   <img src="{{ asset('images/misc/LogoFront.png') }}" alt="Logo" style="height: 42px; max-width: 90px; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(9, 76, 180, 0.15); background: white; border: 1.5px solid rgba(9, 76, 180, 0.2); padding: 2px;">
					   @endif
				   </div>
				   <div class="greeting-text" style="margin-left: 105px !important; background: linear-gradient(135deg, rgba(9, 76, 180, 0.06) 0%, rgba(0, 188, 255, 0.06) 100%) !important; border: 1.5px solid rgba(9, 76, 180, 0.2) !important;">
					<span class="font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #094cb4;">WELCOME,</span>
					<span class="font-weight-bolder text-dark" style="font-size: 0.85rem;">{{ Auth::user()->name }}</span>
				   </div>
			   </div>
			   <div class="col-xl-4 col-lg-5 col-md-6 clock-main">
				<div class="clock">
				  <div class="datetime-content">
					<ul>
						<li id="hours"></li>
						<li id="point1">:</li>
						<li id="min"></li>
						<li id="point">:</li>
						<li id="sec"></li>
					</ul>
				  </div>
				 <div class="datetime-content">
					<div id="Date" class=""></div>
				 </div>
				</div>
			   </div>
			   <div class="col-xl-4 col-lg-3 col-md-12 order-lg-last order-second">
				<div class="topbar justify-content-end align-items-center gap-2">
					<!-- Active Cashier Badge -->
					<div class="topbar-item d-none d-md-flex">
						<span class="badge px-3 py-2 rounded-pill font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 4px; background: rgba(255, 255, 255, 0.92) !important; border: 1px solid rgba(11, 87, 208, 0.25) !important; color: var(--primary) !important; box-shadow: 0 4px 10px rgba(0,0,0,0.05); height: 38px;">
							<span class="spinner-grow spinner-grow-sm text-primary" role="status" style="width: 6px; height: 6px; border-width: 1.5px;"></span>
							Kasir Aktif: FnB Mode
						</span>
					</div>
 
					<!-- Document Number Badge -->
					<div class="topbar-item me-1">
						<div class="doc-badge-container d-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill" style="background: rgba(255, 255, 255, 0.92) !important; border: 1px solid rgba(11, 87, 208, 0.25) !important; display: inline-flex; box-shadow: 0 4px 10px rgba(0,0,0,0.05); height: 38px;">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-file-earmark-text text-primary" viewBox="0 0 16 16">
								<path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
								<path d="M2 2a2 2 0 0 1 2-2h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm7.5 1.5v-2.148L3.852 5.5H5.5a1.5 1.5 0 0 0 1.5-1.5"/>
							</svg>
							<span class="text-muted font-weight-bold" style="font-size: 0.72rem; margin-left: 4px;">No. Dok:</span>
							<div id="_NoTransaksi" class="text-success font-weight-bold" style="font-size: 0.72rem; margin-left: 2px;"></div>
						</div>
					</div>
 
					<div class="topbar-item folder-data">
						<div class="btn btn-icon w-auto h-auto btn-clean d-flex align-items-center py-0 me-3" data-bs-toggle="modal" data-bs-target="#folderpop" style="background: rgba(255, 255, 255, 0.92) !important; border: 1px solid rgba(11, 87, 208, 0.25) !important; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-radius: 12px; height: 38px; padding: 0 12px !important; position: relative;">
							<span class="badge badge-pill badge-primary" id="_draftCount" style="position: absolute; top: -8px; right: -8px; background: var(--secondary) !important;">5</span>
							<span class="symbol symbol-35">
								<span class="symbol-label bg-transparent font-size-h5" style="width: auto; height: auto;">
									<svg width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" fill="var(--primary)" viewBox="0 0 16 16">
										<path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"></path>
									</svg>
								</span>
							</span>
						</div>
					</div>

					<div class="topbar-item folder-data">
						<div id="btOpenCustDisplay" class="btn btn-icon  w-auto h-auto btn-clean d-flex align-items-center py-0 me-3">
							<span class="symbol symbol-35  symbol-light-success">
								<span class="symbol-label font-size-h5 ">
									<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" class="bi bi-pc-display-horizontal" viewBox="0 0 16 16">
									<path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v7A1.5 1.5 0 0 0 1.5 10H6v1H1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5v-1h4.5A1.5 1.5 0 0 0 16 8.5v-7A1.5 1.5 0 0 0 14.5 0zm0 1h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5M12 12.5a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0m2 0a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0M1.5 12h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M1 14.25a.25.25 0 0 1 .25-.25h5.5a.25.25 0 1 1 0 .5h-5.5a.25.25 0 0 1-.25-.25"/>
									</svg>
								</span>
							</span>
						</div>
				 
					</div>
			 
				 <div class="dropdown">
					 <div class="topbar-item" data-bs-toggle="dropdown" data-display="static">
						 <div class="btn btn-icon w-auto h-auto btn-clean d-flex align-items-center py-0">
						 
							 <span class="symbol symbol-35 symbol-light-success">
								 <span class="symbol-label font-size-h5 ">
									 <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										 <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
									 </svg>
								 </span>
							 </span>
						 </div>
					 </div>
 
					 <div class="dropdown-menu dropdown-menu-right" style="min-width: 150px;">
 
 
						 <a href="{{ route('logout') }}" class="dropdown-item">
							 <span class="svg-icon svg-icon-xl svg-icon-primary me-2">
								 <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-power">
									 <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
									 <line x1="12" y1="2" x2="12" y2="12"></line>
								 </svg>
							 </span>
							 Logout
						 </a>
					 </div>
 
				 </div>
				</div>
		 
				</div>
		   </div>
	   </div>
   </header>
   <div class="contentPOS">
	    <div class="container-fluid pt-2">

			<div class="row">
				<!-- COLUMN 1: Product Grid Catalog & Action Buttons Grid (HashMicro + Alfamart) -->
				<div class="col-xl-6 col-lg-6 col-md-12 d-flex flex-column" style="height: calc(100vh - 105px) !important; margin-bottom: 0px !important;">
					<div class="card card-custom gutter-b bg-white border-0 mb-3" style="flex-grow: 1; overflow: hidden; display: flex; flex-direction: column; border-radius: 24px !important;">
						<div class="card-header border-0 py-2 d-flex flex-column gap-2" style="background: rgba(255,255,255,0.95); border-radius: 24px 24px 0 0;">
							<div class="d-flex justify-content-between align-items-center w-100">
								<h4 class="font-weight-bold text-dark mb-0" style="font-size: 1.1rem; letter-spacing: 0.5px;">Order Menu</h4>
								<div class="d-flex align-items-center gap-2">
									<!-- Giant Neon LED Total Tagihan billboard in catalog header -->
									<div class="led-header-total px-3 py-1 rounded-pill" style="background: #0f172a; border: 1.5px solid #00ffc4; box-shadow: 0 4px 15px rgba(0,255,196,0.15); display: flex; align-items: center;">
										<span class="text-muted me-2" style="font-size: 0.65rem; font-weight: 800; color: #94a3b8 !important; letter-spacing: 1px; line-height: 1;">TOTAL:</span>
										<span id="headerGrandTotal" style="font-family: monospace; font-size: 1rem; font-weight: 900; color: #00ffc4; text-shadow: 0 0 10px rgba(0, 255, 196, 0.5); line-height: 1;">Rp 0</span>
									</div>
									<span class="badge bg-light-primary text-primary px-3 py-1 rounded-pill font-weight-bold" style="font-size: 0.75rem;">
										<span id="_totalCatalogItems">0</span> Items Available
									</span>
								</div>
							</div>
							<div class="input-group">
								<span class="input-group-text bg-light border-end-0" style="border-radius: 14px 0 0 14px !important; height: 40px; padding: 0.5rem 0.75rem !important;">
									<i class="fas fa-search text-muted"></i>
								</span>
								<input type="text" class="form-control border-start-0" id="_CatalogSearch" placeholder="Cari nama barang atau barcode..." style="border-radius: 0 14px 14px 0 !important; background-color: #f8fafc !important; height: 40px; padding-left: 0 !important; padding-top: 0.5rem !important; padding-bottom: 0.5rem !important;">
							</div>
							<!-- Scrollable Category Menu -->
							<div class="d-flex gap-2 overflow-auto py-1 scrollbar-1" style="white-space: nowrap;">
								<button class="btn btn-sm btn-primary rounded-pill px-4 cat-pill active" data-category="ALL" style="font-weight: 700; font-size: 0.8rem; background: var(--primary) !important;">All Items</button>
								@foreach($jenisitem as $ko)
									<button class="btn btn-sm btn-outline-secondary rounded-pill px-4 cat-pill" data-category="{{ $ko->KodeJenis }}" style="font-weight: 700; font-size: 0.8rem;">{{ $ko->NamaJenis }}</button>
								@endforeach
							</div>
						</div>
						<div class="card-body p-3 flex-grow-1 overflow-auto scrollbar-1" style="background: rgba(79, 70, 229, 0.015); border-radius: 0 0 24px 24px;">
							<div class="row g-2" id="_productGridContainer">
								<div class="col-12 text-center py-5">
									<div class="spinner-border text-primary" role="status"></div>
									<p class="text-muted mt-2">Memuat Katalog Produk...</p>
								</div>
							</div>
						</div>
					</div>

					<!-- Alfamart style Keyboard Action Helper Button Grid -->
					<div class="card card-custom gutter-b bg-white border-0 p-3 mb-0" style="height: 140px; flex-shrink: 0; border-radius: 24px !important; display: flex; flex-direction: column; justify-content: center; margin-bottom: 0px !important;">
						<div class="d-flex align-items-center justify-content-between mb-2">
							<span class="text-muted font-weight-bold" style="font-size: 0.85rem;">
								<i class="fas fa-keyboard text-primary me-1"></i> Cashier Interactive Keyboard Shortcuts
							</span>
							<span class="badge bg-light text-muted font-weight-normal" style="font-size: 0.7rem;">Click to trigger</span>
						</div>
						<div class="alfamart-shortcut-grid">
							<button type="button" class="btn-alfamart-tile tile-f2" onclick="jQuery(document).trigger(jQuery.Event('keydown', {which: 113}));">
								<kbd>F2</kbd>
								<span>Edit Qty</span>
							</button>
							<button type="button" class="btn-alfamart-tile tile-f3" onclick="jQuery(document).trigger(jQuery.Event('keydown', {which: 114}));">
								<kbd>F3</kbd>
								<span>Diskon (%)</span>
							</button>
							<button type="button" class="btn-alfamart-tile tile-f4" onclick="jQuery(document).trigger(jQuery.Event('keydown', {which: 115}));">
								<kbd>F4</kbd>
								<span>Diskon (Rp)</span>
							</button>
							<button type="button" class="btn-alfamart-tile tile-f5" onclick="jQuery('#btBayar').click();">
								<kbd>F5</kbd>
								<span>Bayar (Pay)</span>
							</button>
							<button type="button" class="btn-alfamart-tile tile-f6" onclick="jQuery('#btDraft').click();">
								<kbd>F6</kbd>
								<span>Simpan Draft</span>
							</button>
							<button type="button" class="btn-alfamart-tile tile-f7" onclick="jQuery('#btshippingcost').click();">
								<kbd>F7</kbd>
								<span>Tambah Jasa</span>
							</button>
							<button type="button" class="btn-alfamart-tile tile-del" onclick="jQuery('#btBatal').click();">
								<kbd>DEL</kbd>
								<span>Batal Transaksi</span>
							</button>
						</div>
					</div>
				</div>

				<!-- COLUMN 2: Business Partner Area & Touch NumPad (Alfamart layout style) -->
				<div class="col-xl-3 col-lg-3 col-md-12 d-flex flex-column" style="height: calc(100vh - 105px) !important; margin-bottom: 0px !important;">
					<!-- F&B Order Info Card (Tipe Order & Nomor Meja) -->
					<div class="card card-custom gutter-b bg-white border-0 mb-3 flex-shrink-0" style="height: auto !important; border-radius: 24px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.02) !important;">
						<div class="card-body p-2 px-3">
							<div class="row g-2">
								<div class="col-6">
									<button type="button" class="btn w-100 py-2 d-flex flex-column align-items-center justify-content-center" id="btTipeOrder" style="height: 52px; border-radius: 16px !important; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important; border: none !important; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.25) !important; transition: all 0.2s ease;">
										<span class="text-uppercase text-white font-weight-bold" style="opacity: 0.8; font-size: 0.58rem; letter-spacing: 0.5px; line-height: 1;">Tipe Order</span>
										<div id="txtTipeOrder" class="w-100">
											<h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.82rem; letter-spacing: 0.2px; line-height: 1.2;">Pilih</h6>
										</div>
									</button>
								</div>
								<div class="col-6">
									<button type="button" class="btn w-100 py-2 d-flex flex-column align-items-center justify-content-center" id="btNomorMeja" style="height: 52px; border-radius: 16px !important; background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; border: none !important; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25) !important; transition: all 0.2s ease;">
										<span class="text-uppercase text-white font-weight-bold" style="opacity: 0.8; font-size: 0.58rem; letter-spacing: 0.5px; line-height: 1;">Nomor Meja</span>
										<div id="txtNomorMeja" class="w-100">
											<h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.82rem; letter-spacing: 0.2px; line-height: 1.2;">Pilih</h6>
										</div>
									</button>
								</div>
							</div>
						</div>
					</div>

					<!-- Tactile Glowing Barcode Scanner Card (Shifted to Column 2 for spacious cart) -->
					<div class="card card-custom gutter-b bg-white border-0 mb-3 flex-shrink-0" style="height: auto !important; border-radius: 24px !important;">
						<div class="card-body p-2 px-3">
							<div class="row g-2">
								<div class="col-12 mb-0">
									<div class="barcode-wrapper">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-qr-code-scan barcode-icon" viewBox="0 0 16 16">
											<path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 10 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5"/>
										</svg>
										<input type="text" class="form-control" id="_Barcode" placeholder="Scan Barcode saja (Fokus)..." style="padding-left: 50px !important; height: 36px;">
									</div>
								</div>
								<div class="col-6">
									<label class="text-muted font-weight-bold mb-0" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.3px; line-height: 1;">Kuantitas (Qty)</label>
									<input type="number" class="form-control border-dark" id="_Qty" value="0" style="height: 28px; padding: 2px 6px !important; font-size: 0.8rem;">
								</div>
								<div class="col-6">
									<label class="text-muted font-weight-bold mb-0" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.3px; line-height: 1;">Diskon (Rp / %)</label>
									<input type="number" class="form-control border-dark" id="_Diskon" value="0" style="height: 28px; padding: 2px 6px !important; font-size: 0.8rem;">
								</div>
							</div>
						</div>
					</div>

					<!-- Hidden Sales Input (Automatically handled via user account role) -->
					<input type="hidden" id="KodeSales" value="{{ Auth::user()->KodeSales ?? '' }}">

					<!-- Business Partner Selector Card (Compact - containing only Pilih Pelanggan) -->
					<div class="card card-custom gutter-b bg-white border-0 mb-3 flex-shrink-0" style="height: auto !important; border-radius: 24px !important;">
						<div class="card-header align-items-center border-0 py-2">
							<div class="card-title mb-0 d-flex align-items-center gap-2">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-people-fill text-primary" viewBox="0 0 16 16">
									<path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
								</svg>
								<h4 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">Business Partner</h4>
							</div>
						</div>
						<div class="card-body pt-0 pb-2">
							<div class="mb-0">
								<fieldset class="form-group mb-0 d-flex align-items-center gap-1">
									<div style="flex: 1; min-width: 0;">
										<input type="text" id="FnBRfidScan" class="form-control mb-2" placeholder="Tap RFID / Ketik No. HP..." autofocus>
										<select class="js-example-basic-single js-states form-control bg-transparent" id="KodePelanggan" name="KodePelanggan" style="width: 100%;" disabled>
											<option value="">-- Pelanggan Umum --</option>
											@foreach($pelanggan as $ko)
												<option value="{{ $ko->KodePelanggan }}">
													{{ $ko->NamaPelanggan }}
												</option>
											@endforeach
										</select>
									</div>
									<button class="btn btn-primary d-flex align-items-center justify-content-center px-3" style="height: 40px; border-radius: 14px !important; background: var(--primary) !important;" id="btSearchCustomer" title="Cari Member">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
											<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
										</svg>
									</button>
									<button class="btn btn-secondary d-flex align-items-center justify-content-center px-3" style="height: 40px; border-radius: 14px !important; background: #e2e8f0 !important; color: #475569 !important; border: 1px solid #cbd5e1 !important;" id="btAddCustomer" title="Tambah Member Baru">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
											<path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
										</svg>
									</button>
								</fieldset>
							</div>
						</div>
					</div>

					<!-- Tactile Dark Touch Keyboard (Alfamart layout style) -->
					<div class="card card-custom gutter-b bg-white border-0 p-2 px-3 mb-0" style="flex-grow: 1; border-radius: 24px !important; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; margin-bottom: 0px !important;">
						<div class="d-flex align-items-center justify-content-between mb-2">
							<h4 class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">Touch Keyboard</h4>
							<div class="btn-group" role="group" style="background: #e2e8f0; border-radius: 10px; padding: 3px;">
								<button type="button" class="btn active" id="btnToggleNum" onclick="toggleKeypadMode('NUM')" style="font-size: 0.95rem !important; font-weight: 900 !important; border-radius: 8px; border: none; transition: all 0.2s; padding: 6px 16px !important;">123</button>
								<button type="button" class="btn" id="btnToggleAlpha" onclick="toggleKeypadMode('ALPHA')" style="font-size: 0.95rem !important; font-weight: 900 !important; border-radius: 8px; border: none; transition: all 0.2s; padding: 6px 16px !important;">ABC</button>
							</div>
						</div>
						
						<!-- Active Input Indicator / Status Banner -->
						<div id="keypadIndicatorContainer" class="d-flex gap-2 mb-2">
							<div class="flex-grow-1">
								<div id="_activeInputIndicator" class="badge bg-light-primary text-primary w-100 py-2 font-weight-bold" style="font-size: 0.85rem; border-radius: 12px; letter-spacing: 0.5px;">
									Kuantitas (Qty)
								</div>
							</div>
						</div>

						<!-- NUMPAD VIEW -->
						<div class="numpad-grid" id="numpadViewContainer">
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('7')">
								<span class="numpad-num">7</span>
								<span class="numpad-sub">PQRS</span>
							</button>
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('8')">
								<span class="numpad-num">8</span>
								<span class="numpad-sub">TUV</span>
							</button>
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('9')">
								<span class="numpad-num">9</span>
								<span class="numpad-sub">WXYZ</span>
							</button>
							<button type="button" class="btn btn-numpad-action" id="_btnNumpadQty" onclick="switchActiveInput('QTY')">Qty</button>
							
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('4')">
								<span class="numpad-num">4</span>
								<span class="numpad-sub">GHI</span>
							</button>
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('5')">
								<span class="numpad-num">5</span>
								<span class="numpad-sub">JKL</span>
							</button>
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('6')">
								<span class="numpad-num">6</span>
								<span class="numpad-sub">MNO</span>
							</button>
							<button type="button" class="btn btn-numpad-action" id="_btnNumpadDiscP" onclick="switchActiveInput('DISC_P')">%</button>
							
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('1')">
								<span class="numpad-num">1</span>
								<span class="numpad-sub">.,-</span>
							</button>
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('2')">
								<span class="numpad-num">2</span>
								<span class="numpad-sub">ABC</span>
							</button>
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('3')">
								<span class="numpad-num">3</span>
								<span class="numpad-sub">DEF</span>
							</button>
							<button type="button" class="btn btn-numpad-action" id="_btnNumpadDiscR" onclick="switchActiveInput('DISC_R')">Rp</button>
							
							<button type="button" class="btn btn-numpad-clear" onclick="pressNumpad('C')">C</button>
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('0')">
								<span class="numpad-num">0</span>
								<span class="numpad-sub">SPACE</span>
							</button>
							<button type="button" class="btn btn-numpad" onclick="pressNumpad('00')">
								<span class="numpad-num">00</span>
								<span class="numpad-sub">00</span>
							</button>
							<button type="button" class="btn btn-numpad-enter" onclick="pressNumpad('ENTER')"><i class="fas fa-check"></i></button>
						</div>

						<!-- ALPHANUMERIC QWERTY VIEW (Initially Hidden) -->
						<div id="qwertyViewContainer" class="d-none flex-grow-1" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
							<div class="qwerty-grid" style="display: grid; grid-template-rows: repeat(4, 1fr); gap: 6px; height: 100%;">
								<!-- Row 1 -->
								<div class="d-flex gap-1 justify-content-center">
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('q')">Q</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('w')">W</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('e')">E</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('r')">R</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('t')">T</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('y')">Y</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('u')">U</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('i')">I</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('o')">O</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('p')">P</button>
								</div>
								<!-- Row 2 -->
								<div class="d-flex gap-1 justify-content-center" style="padding-left: 8px; padding-right: 8px;">
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('a')">A</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('s')">S</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('d')">D</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('f')">F</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('g')">G</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('h')">H</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('j')">J</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('k')">K</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('l')">L</button>
								</div>
								<!-- Row 3 -->
								<div class="d-flex gap-1 justify-content-center">
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('z')">Z</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('x')">X</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('c')">C</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('v')">V</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('b')">B</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('n')">N</button>
									<button type="button" class="btn btn-qwerty" onclick="pressQwerty('m')">M</button>
									<button type="button" class="btn btn-qwerty btn-qwerty-action" onclick="pressQwerty('BACKSPACE')" style="min-width: 48px; background-color: #f1f5f9 !important;"><i class="fas fa-backspace" style="font-size: 0.8rem;"></i></button>
								</div>
								<!-- Row 4 -->
								<div class="d-flex gap-1 justify-content-center">
									<button type="button" class="btn btn-qwerty btn-qwerty-action" onclick="pressQwerty('SPACE')" style="flex-grow: 1; background-color: #f1f5f9 !important; font-weight: 700 !important; font-size: 0.72rem !important; letter-spacing: 0.5px;">SPACE</button>
									<button type="button" class="btn btn-qwerty btn-qwerty-enter" onclick="pressQwerty('ENTER')" style="min-width: 65px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important; color: white !important; font-weight: 900 !important; font-size: 0.75rem !important;">ENTER</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- COLUMN 3: Cart grid workspace, Price tag totals & Checkout widget (HashMicro + Alfamart) -->
				<div class="col-xl-3 col-lg-3 col-md-12 d-flex flex-column" style="height: calc(100vh - 105px) !important; margin-bottom: 0px !important;">
					<div class="receipt-tape p-3" style="height: 100% !important; border-radius: 24px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.04) !important; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; margin-bottom: 0px !important;">
						<!-- F&B Digital Cart Table -->
						<div class="table-datapos px-1 mb-2 table-responsive scrollbar-1" style="flex: 1 1 auto; min-height: 100px; overflow-y: auto;">
							<table id="PosDetail" class="table right-table" style="width:100%; border-collapse: separate; border-spacing: 0 4px; margin-bottom: 0;">
								<thead>
									<tr style="background: rgba(79, 70, 229, 0.05); border-radius: 8px;">
										<th width="8%" class="p-2 text-center text-dark" style="font-weight: 800; font-size: 0.72rem; border-top-left-radius: 8px; border-bottom-left-radius: 8px; border: none !important;">#</th>
										<th width="47%" class="p-2 text-dark" style="font-weight: 800; font-size: 0.72rem; border: none !important;">Menu / Item</th>
										<th width="15%" class="p-2 text-center text-dark" style="font-weight: 800; font-size: 0.72rem; border: none !important;">Qty</th>
										<th width="30%" class="p-2 text-end text-dark" style="font-weight: 800; font-size: 0.72rem; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border: none !important;">Total</th>
									</tr>
								</thead>
								<tbody id="AppendArea"></tbody>
							</table>
						</div>

						<!-- Live Bill Calculation Metrics -->
						<div class="resulttable-pos px-1" style="margin-top: auto !important;">
							<table class="table right-table mb-0">
								<tbody>
									<tr class="d-flex align-items-center justify-content-between" style="padding: 1px 0 !important; margin: 0 !important; line-height: 1.1; border-bottom: 1px dashed rgba(0,0,0,0.06);">
										<th class="border-0 mb-0 p-0" style="font-size: 0.95rem !important; font-weight: 800 !important; color: #475569 !important; letter-spacing: 0.2px;">Total Items</th>
										<td class="border-0 justify-content-end d-flex p-0">
											<input type="text" name="_TotalItem" id="_TotalItem" value="0" class="TotalText" readonly style="font-size: 1.05rem !important; height: 20px !important; padding: 0 !important; width: 100px; text-align: right; border: none !important; background: transparent !important; font-weight: 900 !important; color: #0b57d0 !important; box-shadow: none !important; outline: none !important; pointer-events: none;">
										</td>
									</tr>
									
									<tr class="d-flex align-items-center justify-content-between" style="padding: 1px 0 !important; margin: 0 !important; line-height: 1.1; border-bottom: 1px dashed rgba(0,0,0,0.06);">
										<th class="border-0 mb-0 p-0" style="font-size: 0.95rem !important; font-weight: 800 !important; color: #475569 !important; letter-spacing: 0.2px;">Subtotal</th>
										<td class="border-0 justify-content-end d-flex p-0">
											<input type="text" name="_SubTotal" id="_SubTotal" value="0" class="TotalText" readonly style="font-size: 1.05rem !important; height: 20px !important; padding: 0 !important; width: 150px; text-align: right; border: none !important; background: transparent !important; font-weight: 900 !important; color: #0b57d0 !important; box-shadow: none !important; outline: none !important; pointer-events: none;">
										</td>
									</tr>
									
									<tr class="d-flex align-items-center justify-content-between" style="padding: 1px 0 !important; margin: 0 !important; line-height: 1.1; border-bottom: 1px dashed rgba(0,0,0,0.06);">
										<th class="border-0 mb-0 p-0" style="font-size: 0.95rem !important; font-weight: 800 !important; color: #475569 !important; letter-spacing: 0.2px;">Discount</th>
										<td class="border-0 justify-content-end d-flex p-0">
											<input type="text" name="_TotalDiskon" id="_TotalDiskon" value="0" class="TotalText" readonly style="font-size: 1.05rem !important; height: 20px !important; padding: 0 !important; width: 150px; text-align: right; border: none !important; background: transparent !important; font-weight: 900 !important; color: #0b57d0 !important; box-shadow: none !important; outline: none !important; pointer-events: none;">
										</td>
									</tr>

									<tr class="d-flex align-items-center justify-content-between" style="padding: 2px 0 !important; margin: 0 !important; line-height: 1.1; border-bottom: 1px dashed rgba(0,0,0,0.06);">
										<th class="border-0 d-flex align-items-center gap-1.5 p-0" style="font-size: 0.95rem !important; font-weight: 800 !important; color: #475569 !important; letter-spacing: 0.2px;">
											Voucher
										</th>
										<td class="border-0 justify-content-end d-flex align-items-center p-0">
											<div class="input-group" style="width: 150px; height: 26px;">
												<input type="text" name="_VoucherCode" id="_VoucherCode" placeholder="KODE" class="form-control text-uppercase" style="font-size: 0.75rem !important; height: 26px !important; padding: 2px 6px !important; border: 1.2px solid rgba(11, 87, 208, 0.3) !important; border-radius: 6px 0 0 6px !important; font-weight: 700 !important; background: rgba(11, 87, 208, 0.02) !important;">
												<button class="btn btn-primary d-flex align-items-center justify-content-center px-2" type="button" id="btnApplyVoucher" style="height: 26px !important; border-radius: 0 6px 6px 0 !important; background: var(--primary) !important; font-size: 0.7rem; font-weight: 800; border: none;">
													Cek
												</button>
											</div>
										</td>
									</tr>

									<tr class="d-flex align-items-center justify-content-between" style="padding: 1px 0 !important; margin: 0 !important; line-height: 1.1; border-bottom: 1px dashed rgba(0,0,0,0.06);">
										<th class="border-0 mb-0 p-0" style="font-size: 0.95rem !important; font-weight: 800 !important; color: #475569 !important; letter-spacing: 0.2px;">Diskon Voucher</th>
										<td class="border-0 justify-content-end d-flex p-0">
											<input type="text" name="_VoucherDiscount" id="_VoucherDiscount" value="0" class="TotalText" readonly style="font-size: 1.05rem !important; height: 20px !important; padding: 0 !important; width: 150px; text-align: right; border: none !important; background: transparent !important; font-weight: 900 !important; color: #ef4444 !important; box-shadow: none !important; outline: none !important; pointer-events: none;">
										</td>
									</tr>
									
									<tr class="d-flex align-items-center justify-content-between" style="padding: 1px 0 !important; margin: 0 !important; line-height: 1.1; border-bottom: 1px dashed rgba(0,0,0,0.06);">
										<th class="border-0 mb-0 p-0" style="font-size: 0.95rem !important; font-weight: 800 !important; color: #475569 !important; letter-spacing: 0.2px;">Tax</th>
										<td class="border-0 justify-content-end d-flex p-0">
											<input type="text" name="_TotalTax" id="_TotalTax" value="0" class="TotalText" readonly style="font-size: 1.05rem !important; height: 20px !important; padding: 0 !important; width: 150px; text-align: right; border: none !important; background: transparent !important; font-weight: 900 !important; color: #0b57d0 !important; box-shadow: none !important; outline: none !important; pointer-events: none;">
										</td>
									</tr>
									
									<tr class="d-flex align-items-center justify-content-between" style="padding: 1px 0 !important; margin: 0 !important; line-height: 1.1;">
										<th class="border-0 d-flex align-items-center gap-1.5 p-0" style="font-size: 0.95rem !important; font-weight: 800 !important; color: #475569 !important; letter-spacing: 0.2px;">
											Services
											<span class="badge bg-light-primary text-primary rounded-circle cursor-pointer" id="btshippingcost" style="width: 18px; height: 18px; display: inline-flex; justify-content: center; align-items: center; padding: 0; border: 1px solid rgba(11, 87, 208, 0.3);">
												<i class="fas fa-plus" style="font-size: 8px;"></i>
											</span>
										</th>
										<td class="border-0 justify-content-end d-flex align-items-center p-0">
											<input type="text" name="_TotalServices" id="_TotalServices" value="0" class="form-control TotalText" style="font-size: 1.05rem !important; height: 26px !important; padding: 2px 6px !important; width: 110px; text-align: right; font-weight: 900 !important; border: 1.2px solid rgba(11, 87, 208, 0.3) !important; border-radius: 6px !important; color: #0b57d0 !important; background: rgba(11, 87, 208, 0.02) !important;">
											<a href="#" id="btResetServices" class="text-danger font-weight-bold ms-2" style="font-size: 0.85rem; text-decoration: none;">
												<i class="fas fa-undo"></i>
											</a>
										</td>
									</tr>

									<!-- Massive LED Neon Glowing Total Tagihan Panel (Simplified half-height horizontal bar) - Placed directly below Services -->
									<tr class="item-price">
										<th colspan="2" class="border-0 px-0">
											<div class="premium-total-card mt-1" style="padding: 0.35rem 0.85rem !important; border-radius: 16px !important; border: 1.5px solid rgba(0, 255, 196, 0.65) !important; background: linear-gradient(135deg, #0f172a 0%, #030712 100%) !important;">
												<div class="d-flex justify-content-between align-items-center w-100">
													<span class="text-white font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.8px; opacity: 0.8;">TOTAL TAGIHAN</span>
													<input type="text" name="_GrandTotal" id="_GrandTotal" value="0" class="form-control TotalText" style="font-family: 'Courier New', Courier, monospace !important; font-size: 1.85rem !important; font-weight: 950 !important; width: 65%; text-align: right; background: transparent; border: none; padding: 0 !important; color: #00ffc4 !important; text-shadow: 0 0 10px rgba(0, 255, 196, 0.8); pointer-events: none;" readonly>
												</div>
											</div>
										</th>
									</tr>
								</tbody>
							</table>
						</div>

						<!-- Alfamart Checkout Action Row -->
						<div class="d-flex justify-content-start align-items-center flex-column buttons-cash px-1" style="margin-top: 6px !important;">
							<div class="w-100"> 
								<!-- F5 Pay Button takes full width but slightly more compact height: 44px -->
								<button type="button" class="btn btn-primary white w-100 d-flex align-items-center justify-content-center gap-2 mb-2" id="btBayar" style="height: 45px; font-size: 0.95rem; font-weight: 850; border-radius: 14px !important; background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; border:none; box-shadow: 0 4px 12px rgba(16,185,129,0.25) !important;">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
										<path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9A1.5 1.5 0 0 1 1.5 3H2V1.78a1.5 1.5 0 0 1 1.864-1.454zM5.585 1.862A.5.5 0 0 0 5.176 2V3h4.648V2a.5.5 0 0 0-.41-.497zM2 4.5v9a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5"/>
									</svg>
									Bayar Sekarang (F5)
								</button>
								
								<!-- F6 Draft and DEL Batal side by side to save 50px vertical height! -->
								<div class="row g-2">
									<div class="col-6">
										<button type="button" class="btn btn-secondary white w-100 d-flex align-items-center justify-content-center gap-1.5" id="btDraft" style="height: 38px; font-size: 0.8rem; font-weight: 800; border-radius: 12px !important; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important; border:none; box-shadow: 0 4px 10px rgba(59,130,246,0.2) !important; padding: 4px !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
											<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-folder-symlink" viewBox="0 0 16 16">
												<path d="m11.798 8.271-3.182 1.97-.27.166a.77.77 0 0 1-.36.089c-.212 0-.42-.08-.55-.238A.73.73 0 0 1 7.3 9.8V7.9H1.5C.672 7.9 0 7.228 0 6.4V2.5c0-.828.672-1.5 1.5-1.5h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H10v-1.1h3.174a.9.9 0 0 0 .895-.818l.637-7a.9.9 0 0 0-.895-.981H9.828a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.174 2H1.5a.4.4 0 0 0-.4.4v3.9a.4.4 0 0 0 .4.4H7.3V4.9c0-.182.068-.359.18-.497.13-.158.338-.238.55-.238c.13 0 .254.03.36.088l.27.167 3.182 1.97a.71.71 0 0 1 .37.62.71.71 0 0 1-.369.621z"/>
											</svg>
											Draft (F6)
										</button>
									</div>
									<div class="col-6">
										<button type="button" class="btn btn-danger white w-100 d-flex align-items-center justify-content-center gap-1.5" id="btBatal" style="height: 38px; font-size: 0.8rem; font-weight: 800; border-radius: 12px !important; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%) !important; border:none; box-shadow: 0 4px 10px rgba(239,68,68,0.2) !important; padding: 4px !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
											<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
												<path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
											</svg>
											Batal (DEL)
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>

   <div class="modal fade text-left" id="payment-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel11">Payment</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table right-table">
				<tbody>
				  <tr class="d-flex align-items-center justify-content-between">
					<th class="border-0 px-0 font-size-lg mb-0 font-size-bold text-primary">
						<h1>Total Transaksi</h1>
					</th>
					<td class="border-0 justify-content-end d-flex text-primary font-size-lg font-size-bold px-0 font-size-lg mb-0 font-size-bold text-primary">
						<input type="hidden" name="_TotalTagihan" id="_TotalTagihan">
						<h1 id="_TotalTagihanFormated">Rp. </h1>
					</td>
				  </tr>

				  <tr class="d-flex align-items-center justify-content-between" id="rowPaymentVoucher" style="display: none !important;">
					<th class="border-0 px-0 font-size-lg mb-0 font-size-bold text-danger">
						<h1 id="lblPaymentVoucher">Voucher</h1>
					</th>
					<td class="border-0 justify-content-end d-flex text-danger font-size-lg font-size-bold px-0 font-size-lg mb-0 font-size-bold text-danger">
						<h1 id="valPaymentVoucher">- Rp. 0</h1>
					</td>
				  </tr>

				  <tr class="d-flex align-items-center justify-content-between" id="rowTukarPoin" style="display: none !important;">
					<th class="border-0 px-0 font-size-lg mb-0 font-size-bold text-success">
						<h1 id="lblTukarPoin">Poin <button class="btn btn-sm btn-outline-success ml-2" type="button" id="btnTukarPoin">Tukar Poin</button></h1>
					</th>
					<td class="border-0 justify-content-end d-flex text-success font-size-lg font-size-bold px-0 font-size-lg mb-0 font-size-bold text-success">
                        <input type="hidden" name="_NilaiTukarPoin" id="_NilaiTukarPoin" value="0">
                        <input type="hidden" name="_PoinDitukar" id="_PoinDitukar" value="0">
						<h1 id="valTukarPoin">- Rp. 0</h1>
					</td>
				  </tr>

				  <tr class="d-flex align-items-center justify-content-between">
					<th class="border-0 px-0 font-size-lg mb-0 font-size-bold text-primary">
						<h1>Pembulatan</h1>
					</th>
					<td class="border-0 justify-content-end d-flex text-primary font-size-lg font-size-bold px-0 font-size-lg mb-0 font-size-bold text-primary">
						<input type="hidden" name="_Pembulatan" id="_Pembulatan">
						<h1 id="_PembulatanFormated">Rp. </h1>
					</td>
				  </tr>

				  <tr class="d-flex align-items-center justify-content-between">
					<th class="border-0 px-0 font-size-lg mb-0 font-size-bold text-primary">
						<h1>Total Bayar</h1>
					</th>
					<td class="border-0 justify-content-end d-flex text-primary font-size-lg font-size-bold px-0 font-size-lg mb-0 font-size-bold text-primary">
						<input type="hidden" name="_TotalNetBayar" id="_TotalNetBayar">
						<h1 id="_TotalNetBayarFormated">Rp. </h1>
					</td>
				  </tr>
				</tbody>
			</table>	  
				<div class="form-group row">
					<div class="col-md-12">
						<div class="col-lg-12">
							<div class="card card-custom gutter-b bg-white border-0">
								<div class="card-header align-items-center  border-0">
									<div class="card-title mb-0">
										<h3 class="card-label text-body font-weight-bold mb-0">Pilih Metode Pembayaran
										</h3>
									</div>
								</div>

								<div class="card-body px-0">
									<div class="scroll-container list-group scrollbar-1">
										<ul class="horizontal-list">

											@foreach($metodepembayaran as $ko)
												<li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between py-2" StsPyment={{$ko->Active}} id={{ $ko->id }} CaraVerifikasi={{$ko->MetodeVerifikasi}} TipePembayaran={{$ko->TipePembayaran}}>
													<div class="list-left d-flex align-items-center">
														<span class="d-flex align-items-center justify-content-center rounded svg-icon w-45px h-45px bg-light-dark text-white me-2">
															<img src="{{ $ko->Image }}" class="bi bi-lightning-fill" width="80%" onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmkgYmktY2FzaCIgdmlld0JveD0iMCAwIDE2IDE2Ij4gPHBhdGggZD0iTTggMTBhMiAyIDAgMTAwLTQgMiAyIDAgMDAwIDR6Ii8+IDxwYXRoIGQ9Ik0wIDRhMiAyIDAgMDExMiAwaDFhMiAyIDAgMDExMiAwdjFIMHYtMXptMTUtMmEyIDIgMCAwMTAgNGgxYTIgMiAwIDAxMCA0djFoLTE1di0xaDFhMiAyIDAgMDEwLTRIMHYtMWgxNXYxeiIvPiA8L3N2Zz4=';">
														</span>
													  <div class="list-content">
														<span class="list-title text-body">{{ $ko->NamaMetodePembayaran}}</span>
													  </div>
													</div>
												</li>
											@endforeach
								        </ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-12">
						<label  class="text-body">Jumlah Bayar</label>
						<fieldset class="form-label-group ">
							<input type="text" name="JumlahBayar" id="JumlahBayar" class="form-control CenterText" size="300" style="height: : 50px; font-size: 50px">
						</fieldset>
					</div>
					<div class="col-md-12">
						<label  class="text-body">Nomor Refrensi</label>
						<fieldset class="form-label-group ">
							<input type="text" name="NomorRefrensiPembayaran" id="NomorRefrensiPembayaran" class="form-control CenterText">
						</fieldset>
					</div>
				</div>
				<div class="form-group row justify-content-end mb-0">
					<div class="col-md-6  text-end">
						<button class="btn btn-primary" id="btSimpanPembayaran">Submit</button>
					</div>
				</div>
		  </div>
		</div>
	</div>	  	  
	</div>
   <div class="modal fade text-left" id="folderpop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel14" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg " role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel14">Draft Orders</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body pos-ordermain">
				<div id="_draftOrderList">
			  		
				</div>
		  </div>
		</div>
	</div>	  	  
</div>	

<div class="modal fade text-left" id="shippingcost" tabindex="1" role="dialog" aria-labelledby="shippingcost" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel1444">Tambah Biaya Tambhan</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="form-group row">
				<div class="col-md-12">
					<label  class="text-body">Item Jasa</label>
					<fieldset class="form-group mb-12">
						<select class="arabic-select select-down Select2-Selector" id="KodeItemJasa" name="KodeItemJasa" tabindex="-1">
							<option value="">Pilih Jasa</option>
							@foreach($itemServices as $ko)
								<option value="{{ $ko->KodeItem }}">
	                                {{ $ko->NamaItem }}
	                            </option>
							@endforeach
						</select>
					</fieldset>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					<label  class="text-body">Jumlah</label>
					<fieldset class="form-group mb-3">
						<input type="text" class="form-control" name="JumlahJasa" id="JumlahJasa" value="0">
					</fieldset>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					<label  class="text-body">Keterangan</label>
					<fieldset class="form-group mb-3">
						<input type="text" class="form-control" name="KeteranganJasa" id="KeteranganJasa">
					</fieldset>
				</div>
			</div>
			<div class="form-group row justify-content-end mb-0">
				<div class="col-md-6  text-end">
					<button id="btLookupBiaya" class="btn btn-primary">Update Order</button>
				</div>
			</div>
		  </div>
		</div>
	</div>	  	  
</div>


<div class="modal fade text-left" id="LookupItem" tabindex="-1" role="dialog" aria-labelledby="LookupItem" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel1444">Add Shipping Cost</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="col-md-12">
				<div class="dx-viewport demo-container">
                	<div id="data-grid-demo">
                  		<div id="gridLookupItem"></div>
                	</div>
              	</div>
			</div>
			<hr>
			<div class="form-group row justify-content-end mb-0">
				<div class="col-md-6  text-end">
					<button type="button" class="btn btn-primary" id="btPilihLookupData">Pilih Data</button>
				</div>
			</div>
		  </div>
		</div>
	</div>	  	  
</div>

<div class="modal fade text-left" id="LookupCustomer" tabindex="-1" role="dialog" aria-labelledby="LookupCustomer" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel1444">Cari Member / Pelanggan</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="col-md-12">
				<div class="dx-viewport demo-container">
                	<div id="data-grid-demo">
                  		<div id="gridLookupCustomer"></div>
                	</div>
              	</div>
			</div>
			<hr>
			<div class="form-group row justify-content-end mb-0">
				<div class="col-md-6  text-end">
					<button type="button" class="btn btn-primary" id="btPilihCustomer">Pilih Data</button>
				</div>
			</div>
		  </div>
		</div>
	</div>	  	  
</div>


<div class="modal fade text-left" id="LookupAddCustomer" tabindex="-1" role="dialog" aria-labelledby="LookupAddCustomer" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel1444">Tambah Member / Pelanggan</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-12">
            			<label  class="text-body">Kode Pelanggan</label>
            			<fieldset class="form-group mb-3">
            				<input type="text" class="form-control" id="ModalKodePelanggan" name="ModalKodePelanggan" placeholder="<AUTO>" readonly="" >
            			</fieldset>
            			
            		</div>
            		
            		<div class="col-md-12">
            			<label  class="text-body">Nama Pelanggan</label>
            			<fieldset class="form-group mb-3">
            				<input type="text" class="form-control" id="ModalNamaPelanggan" name="ModalNamaPelanggan" placeholder="Masukan Nama Pelanggan" required="">
            			</fieldset>
            			
            		</div>

            		<div class="col-md-6">
            			<label  class="text-body">Grup Pelanggan</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalKodeGrupPelanggan" id="ModalKodeGrupPelanggan" class="js-example-basic-single js-states form-control bg-transparent" name="state" required="">
								@php $gruppelanggan = \App\Models\GrupPelanggan::where('RecordOwnerID', \Auth::user()->RecordOwnerID)->get(); @endphp
								<option value="">Pilih Kelompok Pelanggan</option>
								@foreach($gruppelanggan as $ko)
									<option value="{{ $ko->KodeGrup }}">
                                        {{ $ko->NamaGrup }}
                                    </option>
								@endforeach
								
							</select>
            			</fieldset>
            			
            		</div>

            		<div class="col-md-6">
            			<label  class="text-body">Limit Piutang</label>
            			<fieldset class="form-group mb-3">
            				<input type="number" class="form-control" id="ModalLimitPiutang" name="ModalLimitPiutang" placeholder="Masukan Limit Piutang" value="0">
            			</fieldset>
            			
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Provinsi</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalProvID" id="ModalProvID" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="-1">Pilih Provinsi</option>
								@foreach($provinsi as $ko)
									<option value="{{ $ko->prov_id }}">
                                        {{ $ko->prov_name }}
                                    </option>
								@endforeach
								
							</select>
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Kota</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalKotaID" id="ModalKotaID" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="-1">Pilih Kota</option>
							</select>
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Kecamatan</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalKecID" id="ModalKecID" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="-1">Pilih Kecamatan</option>
							</select>
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Kelurahan</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalKelID" id="ModalKelID" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="-1">Pilih Kelurahan</option>
							</select>
            			</fieldset>
            		</div>

            		<div class="col-md-12">
            			<label  class="text-body">Alamat</label>
            			<fieldset class="form-group mb-12">
            				<textarea class="form-control" id="ModalAlamat" name="ModalAlamat" rows="3" placeholder="Masukan Alamat"></textarea>
            			</fieldset>
            		</div>

            		<div class="col-md-6">
            			<label  class="text-body">Email</label>
            			<fieldset class="form-group mb-3">
            				<input type="mail" class="form-control" id="ModalEmail" name="ModalEmail" placeholder="Masukan Email" >
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">NoTlp</label>
            			<fieldset class="form-group mb-3">
            				<input type="number" class="form-control" id="ModalNoTlp1" name="ModalNoTlp1" placeholder="621325058258" required="">
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Kontak Lain</label>
            			<fieldset class="form-group mb-3">
            				<input type="number" class="form-control" id="ModalNoTlp2" name="ModalNoTlp2" placeholder="621325058258" >
            			</fieldset>
            		</div>

            		<div class="col-md-12">
            			<label  class="text-body">Keterangan Lain</label>
            			<fieldset class="form-group mb-12">
            				<textarea class="form-control" id="ModalKeterangan" name="ModalKeterangan" rows="3" placeholder="Masukan Keterangan"></textarea>
            			</fieldset>
            		</div>

            		<div class="col-md-12">
            			<label  class="text-body">Status</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalStatus" id="ModalStatus" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
            			</fieldset>
            			
            		</div>
				</div>
			</div>
			<hr>
			<div class="form-group row justify-content-end mb-0">
				<div class="col-md-6  text-end">
					<button type="button" class="btn btn-primary" id="btSaveAddCustomer">Simpan Data</button>
				</div>
			</div>
		  </div>
		</div>
	</div>	  	  
</div>

<div class="modal fade text-left" id="LookupTipeOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
		<div class="modal-content" style="border-radius: 24px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border: none !important;">
		  <div class="modal-header border-0 py-3 px-4">
			<h3 class="modal-title font-weight-bold text-dark" id="myModalLabel11" style="font-size: 1.2rem;"><i class="fas fa-utensils text-primary me-2"></i>Tipe Order</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body px-4 pb-4 pt-2">
				<div class="form-group row">
					<div class="col-md-12">
						<div class="card card-custom gutter-b bg-white border-0 mb-0 shadow-none">
							<div class="card-header align-items-center border-0 p-0 mb-3">
								<div class="card-title mb-0">
									<h5 class="card-label text-muted font-weight-bold mb-0" style="font-size: 0.9rem;">Pilih Tipe Layanan / Order</h5>
								</div>
							</div>

							<div class="card-body px-0 py-1">
								<div class="scroll-container list-group scrollbar-1">
									<ul class="horizontal-list tipeorder d-flex flex-wrap gap-3 p-0" style="list-style: none;">
										@foreach($tipeorder as $ko)
											<li class="list-group-item list-group-item-action border d-flex align-items-center justify-content-center p-3 text-center" style="width: calc(33.333% - 11px); border-radius: 18px; cursor: pointer; transition: all 0.2s ease; flex-direction: column; gap: 8px;" id="{{ $ko->id }}" NamaJenisOrder="{{ $ko->NamaJenisOrder }}" DineIn="{{ $ko->DineIn }}">
												<span class="d-flex align-items-center justify-content-center rounded-circle w-50px h-50px bg-light text-primary">
													<img src="{{ $ko->Icon }}" class="bi bi-lightning-fill" width="60%">
												</span>
												<span class="list-title text-dark font-weight-bold" style="font-size: 0.88rem;">{{ $ko->NamaJenisOrder }}</span>
											</li>
										@endforeach
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr class="my-3 opacity-10">
				<div class="form-group row justify-content-end mb-0">
					<div class="col-md-6 text-end">
						<button class="btn btn-primary px-5 py-2" id="btPilihTipeOrder" style="border-radius: 12px !important; font-weight: 750;">Submit</button>
					</div>
				</div>
		  	</div>
		</div>
	</div>	  	  
</div>

<div class="modal fade text-left" id="LookupNomorMeja" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
		<div class="modal-content" style="border-radius: 24px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border: none !important;">
		  <div class="modal-header border-0 py-3 px-4">
			<h3 class="modal-title font-weight-bold text-dark" id="myModalLabel11" style="font-size: 1.2rem;"><i class="fas fa-table text-primary me-2"></i>Nomor Meja</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body px-4 pb-4 pt-2">
				<div class="form-group row">
					<div class="col-md-12">
						<div class="card card-custom gutter-b bg-white border-0 mb-0 shadow-none">
							<div class="card-header align-items-center border-0 p-0 mb-3">
								<div class="card-title mb-0">
									<h5 class="card-label text-muted font-weight-bold mb-0" style="font-size: 0.9rem;">Pilih Nomor Meja Makan Pelanggan</h5>
								</div>
							</div>

							<div class="card-body px-0 py-1">
								<div class="scroll-container list-group scrollbar-1" style="max-height: 380px; overflow-y: auto;">
									<ul class="horizontal-list-meja nomormeja d-flex flex-wrap gap-3 p-0" style="list-style: none;">
										@foreach($meja as $ko)
											@php
												$isNyala = intval($ko->LampuStatus ?? 0) == 1;
												$isKonek = !empty($ko->LampuControllerID) && intval($ko->LampuControllerID) > 0;
												
												if ($isNyala) {
													$iconBgColor = 'rgba(239, 68, 68, 0.15)';
													$textColor = '#ef4444';
													$statusLabel = 'Menyala';
												} elseif ($isKonek) {
													$iconBgColor = 'rgba(16, 185, 129, 0.15)';
													$textColor = '#10b981';
													$statusLabel = 'Konek (Mati)';
												} else {
													$iconBgColor = 'rgba(100, 116, 139, 0.15)';
													$textColor = '#64748b';
													$statusLabel = 'Tidak Konek';
												}
											@endphp
											<li class="list-group-item list-group-item-action border d-flex align-items-center justify-content-center p-3 text-center" style="width: calc(25% - 12px); border-radius: 18px; cursor: pointer; transition: all 0.2s ease; flex-direction: column; gap: 8px;" id="{{ $ko->KodeMeja }}" NamaMeja="{{ $ko->NamaMeja }}">
												<span class="d-flex align-items-center justify-content-center rounded-circle w-50px h-50px" style="background: {{ $iconBgColor }}; color: {{ $textColor }}; transition: all 0.2s ease;">
													<img src="{{ url('images/default/dining-table.png') }}" class="bi bi-lightning-fill" width="60%" style="filter: drop-shadow(0px 1px 2px rgba(0,0,0,0.1));">
												</span>
												<div class="d-flex flex-column align-items-center">
													<span class="list-title text-dark font-weight-bold" style="font-size: 0.88rem;">{{ $ko->NamaMeja }}</span>
													<span class="mt-1 px-2 py-0.5 status-badge" style="font-size: 0.65rem; font-weight: 700; color: {{ $textColor }}; border-radius: 6px; background: {{ $iconBgColor }}; text-transform: uppercase; letter-spacing: 0.2px;">
														{{ $statusLabel }}
													</span>
													@if($isNyala && !empty($ko->LampuID))
														<button type="button" class="btn btn-sm btn-danger rounded-pill mt-2 py-0 px-3" style="font-size: 0.6rem; letter-spacing: 0.5px;" onclick="turnOffTableLight(event, this, {{ $ko->LampuID }})"><i class="fas fa-power-off me-1"></i>Matikan</button>
													@endif
												</div>
											</li>
										@endforeach


									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between" style="border-top: 1.5px solid rgba(11, 87, 208, 0.08) !important;">
					<div class="d-flex align-items-center gap-3">
						<div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
							<i class="fas fa-lightbulb" style="font-size: 1.2rem;"></i>
						</div>
						<div>
							<h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.9rem;">Nyalakan Lampu Meja?</h6>
							<small class="text-muted" style="font-size: 0.78rem;">Aktifkan untuk menerangi/menghias meja dimalam hari</small>
						</div>
					</div>
					<div>
						<label class="switch-lampu" style="margin-bottom: 0;">
							<input type="checkbox" id="chkNyalakanLampuMeja">
							<span class="slider-lampu"></span>
						</label>
					</div>
				</div>

				<hr class="my-3 opacity-10">
				<div class="form-group row justify-content-end mb-0">
					<div class="col-md-6 text-end">

						<button class="btn btn-primary px-5 py-2" id="btPilihNomorMeja" style="border-radius: 12px !important; font-weight: 750;">Submit</button>
					</div>
				</div>
		  	</div>
		</div>
	</div>	  	  
</div>

<div class="modal fade text-left" id="LookupMenuVariant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
		<div class="modal-content" style="border-radius: 24px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border: none !important;">
		  <div class="modal-header border-0 py-3 px-4">
			<h3 class="modal-title font-weight-bold text-dark" id="myModalLabel11" style="font-size: 1.2rem;"><i class="fas fa-cookie-bite text-primary me-2"></i>Variant & Addon Menu</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body px-4 pb-4 pt-1">
				<div class="form-group row">
					<div class="col-md-12">
						<div id="lsvVariantMenu"></div>
						<div class="row">
							<div class="col-md-12 col-12">
								<div class="card card-custom gutter-b bg-white border-0 shadow-none mb-0">
									<div class="card-header align-items-center border-0 p-0 mb-2">
										<div class="card-title mb-0">
											<h4 class="card-label mb-0 font-weight-bold text-dark" style="font-size: 1rem;"><i class="fas fa-plus-circle text-success me-2"></i>Addon Menu</h4>
										</div>
									</div>
									<div class="card-body p-0">
										<div class="col-md-12">
											<div id="gridLookupAddon" style="max-height: 250px;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		  	</div>
			<div class="modal-footer border-0 px-4 pb-4 pt-1">
				<button type="button" class="btn btn-primary px-5 py-2" id="btPilihVariant" style="border-radius: 12px !important; font-weight: 750;">
					<span class="">Pilih Variant</span>
				</button>
			</div> 	
		</div>
	</div>	  	  
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/plugin.bundle.min.js')}}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
<!-- <script src="http://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script> -->
<!-- <script src="{{ asset('js/sweetalert.js')}}"></script> -->
<!-- <script src="{{ asset('js/sweetalert1.js')}}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('api/jqueryvalidate/jquery.validate.min.js')}}"></script>
<script src="{{asset('api/mcustomscrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('api/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('js/script.bundle.js')}}"></script>
<link href="{{ asset('devexpress/dx.light.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('devexpress/dx.all.js')}}"></script>
<script src="{{asset('api/select2/select2.min.js')}}"></script>
@if (env('MIDTRANS_IS_PRODUCTION') == 'false')
<script src="{{ env('MIDTRANS_DEV_URL') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script src="{{ env('MIDTRANS_PROD_URL') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif


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
<script>
    $('#FnBRfidScan').on('keypress', function(e) {
        if (e.which == 13) {
            var searchVal = $(this).val().trim().toLowerCase();
            if (searchVal) {
                var found = _Pelanggan.find(function(p) {
                    return (p.RFID_UID && p.RFID_UID.toLowerCase() == searchVal) ||
                           (p.Keterangan && p.Keterangan.toLowerCase() == searchVal) ||
                           (p.KodePelanggan && p.KodePelanggan.toLowerCase() == searchVal) ||
                           (p.NoIdentitas && p.NoIdentitas.toLowerCase() == searchVal) ||
                           (p.NoTlp1 && p.NoTlp1.toLowerCase() == searchVal);
                });
                if (found) {
                    $('#KodePelanggan').val(found.KodePelanggan).trigger('change');
                    Swal.fire('Berhasil', 'Member ' + found.NamaPelanggan + ' ditemukan.', 'success');
                } else {
                    Swal.fire('Gagal', 'Member tidak ditemukan.', 'error');
                }
                $(this).val('');
            }
        }
    });
</script>
</body>
<!--end::Body-->
</html>
<script type="text/javascript">
	var _LastInputed = '';
	var _VoucherDiscountPercent = 0;
	var _VoucherMaximalDiscount = 0;
	var _VoucherAppliedCode = "";
	var _TipeDiskon = '';
	var _ServicesData = [];
	var _DiskonGrupCustomer = 0;
	var _DiskonMemberPersen = 0;
	var _SisaGratisOngkir = 0;
	var _PoinLoyalti = 0;
	var _TerminPelanggan = '';

	var _Tanggal = '';
	var _Jam = '';
	var _Company = [];
	var _Printer = [];
	var _Pelanggan = [];
	var _KodeMetodePembayaran = -1;
	var _MetodeVerifikasiPembayaran = '';
	var _TipePembayaran = '';
	let customerDisplayWindow;

	// Tactile Cashier Hybrid Controller State
	var _AllProducts = [];
	var _ActiveNumpadField = 'QTY'; // QTY, DISC_P, DISC_R
	var _ActiveCategory = 'ALL';
	
	// F&B Order Global State
	var _idJenisOrder = -1;
	var _NamaJenisOrder = "";
	var _DineIn = "";
	var _KodeMeja = -1;
	var _NamaMeja = "";
	var _NyalakanLampu = 0;


	var _oVariantMenu = <?php echo json_encode($variantmenu) ?>;
	var _oMenuAddon = <?php echo json_encode($menuaddon) ?>;
	var _oTipeOrder = <?php echo json_encode($tipeorder) ?>;
	var _oMeja = <?php echo json_encode($meja) ?>;
	var _oItemMenu = <?php echo json_encode($itemmenu) ?>;
	var _oSelectedVariant = null;

	function clickCatalogProduct(kodeItem) {
		// Reset selected variant
		_oSelectedVariant = null;
		
		// Find in variants
		let filteredVariantDetail = _oVariantMenu.filter(function(variant) {
			return variant.Father == kodeItem;
		});

		if (filteredVariantDetail.length > 0) {
			console.log('Masuk Tambah Variant + addon')
			console.log(filteredVariantDetail)
			var _Header = [];

			for (let index = 0; index < filteredVariantDetail.length; index++) {
				var oData = {
					'id' : filteredVariantDetail[index]['VariantGrupID'],
					'NamaGrup' : filteredVariantDetail[index]['NamaGrup']
				}

				if (_Header.length > 0) {
					let headerExist = _Header.filter(function(temp) {
						return temp.id === filteredVariantDetail[index]['VariantGrupID'];
					});
					if (headerExist.length == 0) {
						_Header.push(oData);	
					}
				}
				else{
					_Header.push(oData);
				}
			}
			
			var _xHTML = "<input type='hidden' value ='"+kodeItem+"' id='VariantFatherItemCode'>";
			for (let i = 0; i < _Header.length; i++) {
				_xHTML += '<div class="row mb-3">';
					_xHTML += '<div class="col-md-12 col-12">';
						_xHTML += '<div class="card card-custom gutter-b bg-white border-0 shadow-none mb-0">';
							_xHTML += '<div class="card-header align-items-center border-0 p-0 mb-2">';
								_xHTML += '<div class="card-title mb-0">';
									_xHTML += '<h5 class="card-label mb-0 font-weight-bold text-dark" style="font-size: 0.95rem;">'+_Header[i]['NamaGrup']+'</h5>'
								_xHTML += '</div>';
							_xHTML += '</div>';

							_xHTML += '<div class="card-body p-0">';
								_xHTML += '<div class="col-md-12">';
									let ItemDetail = filteredVariantDetail.filter(function(variant) {
										return variant.Father === kodeItem && variant.VariantGrupID === _Header[i]['id'];
									});
									_xHTML += '<div class="scroll-container list-group scrollbar-1">';
										_xHTML += '<ul class="horizontal-list-variant VariantData d-flex flex-wrap gap-2 p-0" style="list-style: none;">';
											for (let iDetail = 0; iDetail < ItemDetail.length; iDetail++) {
												_xHTML += '<li class="list-group-item list-group-item-action border d-flex align-items-center p-2 text-center" style="width: calc(33.333% - 6px); border-radius: 12px; cursor: pointer; transition: all 0.2s ease; flex-direction: column; gap: 4px;" id='+'variant'+ItemDetail[iDetail]['VariantID']+' namavariant="'+ItemDetail[iDetail]['NamaVariant']+'" extraprice ="'+ItemDetail[iDetail]['ExtraPrice']+'" VariantGrupID = "'+ItemDetail[iDetail]['VariantGrupID']+'" VariantID = "'+ItemDetail[iDetail]['VariantID']+'">';
													_xHTML += '<span class="d-flex align-items-center justify-content-center rounded-circle w-35px h-35px bg-light text-primary">';
														_xHTML += '<img src="https://cdn-icons-png.freepik.com/256/6178/6178920.png?semt=ais_hybrid" class="bi bi-lightning-fill" width="60%">';
													_xHTML += '</span>';
													_xHTML += '<div class="list-content text-center">';
														_xHTML += '<span class="list-title text-dark font-weight-bold" style="font-size: 0.8rem;">'+ItemDetail[iDetail]['NamaVariant']+'</span><br>';
														_xHTML += '<span class="list-title text-danger font-weight-bold" style="font-size: 0.75rem;">+ Rp. '+ parseFloat(ItemDetail[iDetail]['ExtraPrice']).toLocaleString('id-ID')+'</span>';
													_xHTML += '</div>';
												_xHTML += '</li>';
											}
										_xHTML += '</ul>';
									_xHTML += '</div>';
								_xHTML += '</div>';
							_xHTML += '</div>';

						_xHTML += '</div>';
					_xHTML += '</div>';
				_xHTML += '</div>';
			}
			
			jQuery('#lsvVariantMenu').empty();
			jQuery("#lsvVariantMenu").append(_xHTML);

			// Filter addons for this specific father item
			let filteredAddons = _oMenuAddon.filter(function(addon) {
				return addon.Father == kodeItem;
			});

			var ColumnData = [
				{
					dataField: "NamaAddon",
					caption: "Nama Addon",
					allowSorting: true,
					allowEditing : false
				},
				{
					dataField: "HargaAddon",
					caption: "Extra Cost",
					allowSorting: true,
					allowEditing : false,
					dataType: "number",
					format: {
						type: "fixedPoint",
						precision: 0
					}
				},
			];
			BindLookupServices("gridLookupAddon", "id", filteredAddons, ColumnData,"multiple");

			jQuery('#LookupMenuVariant').modal({backdrop: 'static', keyboard: false})
			jQuery('#LookupMenuVariant').modal('show');

			// Add dynamic variant click listeners
			jQuery('.horizontal-list-variant.VariantData li').click(function() {
				jQuery('.horizontal-list-variant.VariantData li').removeClass('active').css('border-color', '#cbd5e1').css('background', '#ffffff');
				jQuery(this).addClass('active').css('border-color', '#4f46e5').css('background', 'rgba(79, 70, 229, 0.05)');
				
				_oSelectedVariant = {
					id: jQuery(this).attr('id'),
					NamaVariant: jQuery(this).attr('namavariant'),
					ExtraPrice: parseFloat(jQuery(this).attr('extraprice')) || 0,
					VariantGrupID: jQuery(this).attr('VariantGrupID'),
					VariantID: jQuery(this).attr('VariantID')
				};
			});
		} else {
			// No variants, add directly to cart!
			AddNewRow(kodeItem);
			CalculateTotal();
		}
	}

	function AddNewRow(KodeItem) {
		var RandomID = generateRandomText(10);
        var newRow = document.createElement('tr');
        newRow.className = RandomID;
        newRow.id = "InputSectionData"

		var tbody = document.querySelectorAll('#InputSectionData');
        var index = 0;

		if (tbody.length > 0) {
			index = tbody.length + 1;
		}

		// Check if Exists
		var existingRow = Array.from(document.querySelectorAll('input[id="txtKodeItem"]')).find(function(input) {
			return input.value === KodeItem;
		});

		if (existingRow) {
			var row = existingRow.closest('tr');
			var qtyText = row.querySelector('input[id="txtQty"]');
			qtyText.value = parseInt(qtyText.value) + 1;
			updateTotal(row);
			return;
		}

		// Filter Item
		let filteredItem = _oItemMenu.filter(function(item) {
            return item.KodeItem == KodeItem;
        });

		if (filteredItem.length == 0) return;

		var nomorCol = document.createElement('td');
		nomorCol.className = 'p-2 text-center text-muted align-middle';
		nomorCol.style.fontSize = '0.75rem';
		nomorCol.style.border = 'none';

        var ItemCol = document.createElement('td');
		ItemCol.className = 'p-2 align-middle';
		ItemCol.style.border = 'none';

        var QtyCol = document.createElement('td');
		QtyCol.className = 'p-2 align-middle text-center';
		QtyCol.style.border = 'none';

		var TotalCol = document.createElement('td');
		TotalCol.className = 'p-2 align-middle text-end font-weight-bold text-dark';
		TotalCol.style.fontSize = '0.8rem';
		TotalCol.style.border = 'none';

		var NamaItemText = document.createElement('input');
		var KodeItemText = document.createElement('input');
		var QtyText = document.createElement('input');
		var HargaText = document.createElement('input');
		var TotalText = document.createElement('input');
		var DiskonText = document.createElement('input');
		
		// Item
		NamaItemText.type  = 'text';
		NamaItemText.id = "txtNamaItem";
        NamaItemText.name = 'DetailParameter['+index+'][NamaItem]';
        NamaItemText.className = 'form-control form-control-sm bg-transparent border-0 p-0 text-dark font-weight-bold';
		NamaItemText.style.fontSize = '0.8rem';
        NamaItemText.required = true;
        NamaItemText.value = filteredItem[0]["NamaItem"];
        NamaItemText.readOnly = true;
		NamaItemText.title = filteredItem[0]["NamaItem"];
        ItemCol.appendChild(NamaItemText);

        KodeItemText.type = "hidden";
		KodeItemText.id = "txtKodeItem";
        KodeItemText.name = 'DetailParameter['+index+'][KodeItem]';
        KodeItemText.value = KodeItem;
        ItemCol.appendChild(KodeItemText);

		// Qty text & delete combo in styling
        QtyText.type  = 'number';
		QtyText.id = "txtQty";
        QtyText.name = 'DetailParameter['+index+'][Qty]';
        QtyText.className = 'form-control form-control-sm text-center bg-light border-0';
		QtyText.style.borderRadius = '8px';
		QtyText.style.width = '55px';
		QtyText.style.height = '28px';
		QtyText.style.fontSize = '0.78rem';
		QtyText.style.fontWeight = '700';
        QtyText.value = 1;
        QtyText.required = true;
        QtyText.addEventListener('input', function() {
			updateTotal(newRow);
			CalculateTotal();
        });
        QtyCol.appendChild(QtyText);

		// Hidden price & discounts
        HargaText.type  = 'hidden';
		HargaText.id = "txtHarga";
        HargaText.name = 'DetailParameter['+index+'][HargaJual]';
        HargaText.value = filteredItem[0]["HargaJual"];
        ItemCol.appendChild(HargaText);

		DiskonText.type  = 'hidden';
		DiskonText.id = "txtDiskon";
        DiskonText.name = 'DetailParameter['+index+'][Diskon]';
        DiskonText.value = 0;
        ItemCol.appendChild(DiskonText);

		// Total Text
		TotalText.type  = 'text';
		TotalText.id = "txtTotal";
        TotalText.name = 'DetailParameter['+index+'][Total]';
        TotalText.className = 'form-control form-control-sm bg-transparent border-0 p-0 text-end font-weight-bold text-dark';
		TotalText.style.fontSize = '0.8rem';
        TotalText.value = filteredItem[0]["HargaJual"];
        TotalText.required = true;
		TotalText.readOnly = true;
        TotalCol.appendChild(TotalText);

		newRow.appendChild(nomorCol);
        newRow.appendChild(ItemCol);
        newRow.appendChild(QtyCol);
		newRow.appendChild(TotalCol);
        document.getElementById('AppendArea').appendChild(newRow);

		// Auto select newly added row
		$('#AppendArea tr#InputSectionData').removeClass('selected-row');
		newRow.classList.add('selected-row');

		FirstRowHandling();
	}
	
	function AddAddonVariantMenu(KodeItem, oDraftData = []) {
		if (oDraftData.length > 0) {
			for (let index = 0; index < oDraftData.length; index++) {
				var newRow = document.createElement('tr');
				newRow.className = KodeItem + ' variant-subrow';
				newRow.id = "VariantSectionData";
				newRow.style.background = 'rgba(79, 70, 229, 0.02)';

				var tbody = document.querySelectorAll('#VariantSectionData');
				var indexInternal = tbody.length + 1;

				var nomorCol = document.createElement('td');
				nomorCol.style.border = 'none';

				var NamaVariant = document.createElement('td');
				NamaVariant.className = 'p-1 ps-4 align-middle text-muted';
				NamaVariant.style.fontSize = '0.74rem';
				NamaVariant.style.border = 'none';

				var ExtraQty = document.createElement('td');
				ExtraQty.className = 'p-1 text-center align-middle text-muted';
				ExtraQty.style.fontSize = '0.74rem';
				ExtraQty.style.border = 'none';

				var ExtraPrice = document.createElement('td');
				ExtraPrice.className = 'p-1 text-end align-middle text-muted font-weight-bold';
				ExtraPrice.style.fontSize = '0.74rem';
				ExtraPrice.style.border = 'none';

				var GrupVariantID = document.createElement('input');
				var VariantID = document.createElement('input');
				var txtNamaVariant = document.createElement("input");
				var txtExtraPrice = document.createElement("input");
				var txtExtraQty = document.createElement("input");
				var txtKodeItem = document.createElement("input");

				GrupVariantID.type  = 'hidden';
				GrupVariantID.id = "txtGrupVariantID";
				GrupVariantID.name = 'VariantParameter['+indexInternal+'][GrupVariantID]';
				GrupVariantID.value = oDraftData[index]["VariantGrupID"]
				NamaVariant.appendChild(GrupVariantID);

				VariantID.type  = 'hidden';
				VariantID.id = "txtVariantID";
				VariantID.name = 'VariantParameter['+indexInternal+'][VariantID]';
				VariantID.value = oDraftData[index]["VariantID"];
				NamaVariant.appendChild(VariantID);

				txtKodeItem.type  = 'hidden';
				txtKodeItem.id = "txtKodeItem";
				txtKodeItem.name = 'VariantParameter['+indexInternal+'][KodeItem]';
				txtKodeItem.value = KodeItem
				NamaVariant.appendChild(txtKodeItem);

				txtNamaVariant.type  = 'text';
				txtNamaVariant.id = "txtNamaItem";
				txtNamaVariant.name = 'VariantParameter['+indexInternal+'][NamaVariant]';
				txtNamaVariant.className = 'bg-transparent border-0 p-0 text-muted w-100';
				txtNamaVariant.style.fontSize = '0.74rem';
				txtNamaVariant.style.fontStyle = 'italic';
				txtNamaVariant.value = '└─ ' + oDraftData[index]["NamaVariant"];
				txtNamaVariant.readOnly = true;
				NamaVariant.appendChild(txtNamaVariant);

				txtExtraPrice.type  = 'hidden';
				txtExtraPrice.id = "txtExtraPrice";
				txtExtraPrice.name = 'VariantParameter['+indexInternal+'][ExtraPrice]';
				txtExtraPrice.value = oDraftData[index]["ExtraPrice"]
				ExtraPrice.appendChild(txtExtraPrice);
				
				var formattedPrice = document.createTextNode(parseFloat(oDraftData[index]["ExtraPrice"]).toLocaleString('id-ID'));
				ExtraPrice.appendChild(formattedPrice);

				txtExtraQty.type  = 'hidden';
				txtExtraQty.id = "txtExtraQty";
				txtExtraQty.name = 'VariantParameter['+indexInternal+'][ExtraQty]';
				txtExtraQty.value = 1
				ExtraQty.appendChild(txtExtraQty);
				ExtraQty.appendChild(document.createTextNode('1'));

				newRow.appendChild(nomorCol);
				newRow.appendChild(NamaVariant);
				newRow.appendChild(ExtraQty);
				newRow.appendChild(ExtraPrice);
				document.getElementById('AppendArea').appendChild(newRow);	
			}
		}
		else if (_oSelectedVariant) {
			var newRow = document.createElement('tr');
			newRow.className = KodeItem + ' variant-subrow';
			newRow.id = "VariantSectionData";
			newRow.style.background = 'rgba(79, 70, 229, 0.02)';

			var tbody = document.querySelectorAll('#VariantSectionData');
			var index = tbody.length + 1;

			var nomorCol = document.createElement('td');
			nomorCol.style.border = 'none';

			var NamaVariant = document.createElement('td');
			NamaVariant.className = 'p-1 ps-4 align-middle text-muted';
			NamaVariant.style.fontSize = '0.74rem';
			NamaVariant.style.border = 'none';

			var ExtraQty = document.createElement('td');
			ExtraQty.className = 'p-1 text-center align-middle text-muted';
			ExtraQty.style.fontSize = '0.74rem';
			ExtraQty.style.border = 'none';

			var ExtraPrice = document.createElement('td');
			ExtraPrice.className = 'p-1 text-end align-middle text-muted font-weight-bold';
			ExtraPrice.style.fontSize = '0.74rem';
			ExtraPrice.style.border = 'none';

			var GrupVariantID = document.createElement('input');
			var VariantID = document.createElement('input');
			var txtNamaVariant = document.createElement("input");
			var txtExtraPrice = document.createElement("input");
			var txtExtraQty = document.createElement("input");
			var txtKodeItem = document.createElement("input");

			GrupVariantID.type  = 'hidden';
			GrupVariantID.id = "txtGrupVariantID";
			GrupVariantID.name = 'VariantParameter['+index+'][GrupVariantID]';
			GrupVariantID.value = _oSelectedVariant["VariantGrupID"]
			NamaVariant.appendChild(GrupVariantID);

			VariantID.type  = 'hidden';
			VariantID.id = "txtVariantID";
			VariantID.name = 'VariantParameter['+index+'][VariantID]';
			VariantID.value = _oSelectedVariant["VariantID"];
			NamaVariant.appendChild(VariantID);

			txtKodeItem.type  = 'hidden';
			txtKodeItem.id = "txtKodeItem";
			txtKodeItem.name = 'VariantParameter['+index+'][KodeItem]';
			txtKodeItem.value = KodeItem
			NamaVariant.appendChild(txtKodeItem);

			txtNamaVariant.type  = 'text';
			txtNamaVariant.id = "txtNamaItem";
			txtNamaVariant.name = 'VariantParameter['+index+'][NamaVariant]';
			txtNamaVariant.className = 'bg-transparent border-0 p-0 text-muted w-100';
			txtNamaVariant.style.fontSize = '0.74rem';
			txtNamaVariant.style.fontStyle = 'italic';
			txtNamaVariant.value = '└─ ' + _oSelectedVariant["NamaVariant"];
			txtNamaVariant.readOnly = true;
			NamaVariant.appendChild(txtNamaVariant);

			txtExtraPrice.type  = 'hidden';
			txtExtraPrice.id = "txtExtraPrice";
			txtExtraPrice.name = 'VariantParameter['+index+'][ExtraPrice]';
			txtExtraPrice.value = _oSelectedVariant["ExtraPrice"]
			ExtraPrice.appendChild(txtExtraPrice);
			
			var formattedPrice = document.createTextNode(parseFloat(_oSelectedVariant["ExtraPrice"]).toLocaleString('id-ID'));
			ExtraPrice.appendChild(formattedPrice);

			txtExtraQty.type  = 'hidden';
			txtExtraQty.id = "txtExtraQty";
			txtExtraQty.name = 'VariantParameter['+index+'][ExtraQty]';
			txtExtraQty.value = 1
			ExtraQty.appendChild(txtExtraQty);
			ExtraQty.appendChild(document.createTextNode('1'));

			newRow.appendChild(nomorCol);
			newRow.appendChild(NamaVariant);
			newRow.appendChild(ExtraQty);
			newRow.appendChild(ExtraPrice);
			document.getElementById('AppendArea').appendChild(newRow);
		}
	}

	function AddAddonMenu(KodeItem, oData, oDraftData = []) {
		if (oDraftData.length > 0) {
			for (let index = 0; index < oDraftData.length; index++) {
				var newRow = document.createElement('tr');
				newRow.className = KodeItem + ' addon-subrow';
				newRow.id = "AddonSectionData";
				newRow.style.background = 'rgba(79, 70, 229, 0.01)';

				var tbody = document.querySelectorAll('#AddonSectionData');
				var AddAddonMenu = tbody.length + 1;

				var nomorCol = document.createElement('td');
				nomorCol.style.border = 'none';

				var NamaVariant = document.createElement('td');
				NamaVariant.className = 'p-1 ps-4 align-middle text-muted';
				NamaVariant.style.fontSize = '0.74rem';
				NamaVariant.style.border = 'none';

				var ExtraQty = document.createElement('td');
				ExtraQty.className = 'p-1 text-center align-middle text-muted';
				ExtraQty.style.fontSize = '0.74rem';
				ExtraQty.style.border = 'none';

				var ExtraPrice = document.createElement('td');
				ExtraPrice.className = 'p-1 text-end align-middle text-muted font-weight-bold';
				ExtraPrice.style.fontSize = '0.74rem';
				ExtraPrice.style.border = 'none';

				var MenuAddonID = document.createElement('input');
				var txtNamaVariant = document.createElement("input");
				var txtExtraPrice = document.createElement("input");
				var txtExtraQty = document.createElement("input");
				var txtKodeItem = document.createElement("input");

				MenuAddonID.type  = 'hidden';
				MenuAddonID.id = "txtMenuAddonID";
				MenuAddonID.name = 'AddonParameter['+AddAddonMenu+'][MenuAddonID]';
				MenuAddonID.value = oDraftData[index]["AddonMenuID"]
				NamaVariant.appendChild(MenuAddonID);

				txtKodeItem.type  = 'hidden';
				txtKodeItem.id = "txtKodeItem";
				txtKodeItem.name = 'AddonParameter['+AddAddonMenu+'][KodeItem]';
				txtKodeItem.value = KodeItem;
				NamaVariant.appendChild(txtKodeItem);

				txtNamaVariant.type  = 'text';
				txtNamaVariant.id = "txtNamaItem";
				txtNamaVariant.name = 'AddonParameter['+AddAddonMenu+'][NamaAddon]';
				txtNamaVariant.className = 'bg-transparent border-0 p-0 text-muted w-100';
				txtNamaVariant.style.fontSize = '0.74rem';
				txtNamaVariant.style.fontStyle = 'italic';
				txtNamaVariant.value = '├─ ' + oDraftData[index]["NamaAddon"];
				txtNamaVariant.readOnly = true;
				NamaVariant.appendChild(txtNamaVariant);

				txtExtraPrice.type  = 'hidden';
				txtExtraPrice.id = "txtExtraPrice";
				txtExtraPrice.name = 'AddonParameter['+AddAddonMenu+'][HargaAddon]';
				txtExtraPrice.value = oDraftData[index]["HargaAddon"]
				ExtraPrice.appendChild(txtExtraPrice);
				
				var formattedPrice = document.createTextNode(parseFloat(oDraftData[index]["HargaAddon"]).toLocaleString('id-ID'));
				ExtraPrice.appendChild(formattedPrice);

				txtExtraQty.type  = 'hidden';
				txtExtraQty.id = "txtExtraQty";
				txtExtraQty.name = 'AddonParameter['+AddAddonMenu+'][Qty]';
				txtExtraQty.value = oDraftData[index]["ExtraQty"]
				ExtraQty.appendChild(txtExtraQty);
				ExtraQty.appendChild(document.createTextNode('1'));

				newRow.appendChild(nomorCol);
				newRow.appendChild(NamaVariant);
				newRow.appendChild(ExtraQty);
				newRow.appendChild(ExtraPrice);
				document.getElementById('AppendArea').appendChild(newRow);	
			}
		}
		else {
			var newRow = document.createElement('tr');
			newRow.className = KodeItem + ' addon-subrow';
			newRow.id = "AddonSectionData";
			newRow.style.background = 'rgba(79, 70, 229, 0.01)';

			var tbody = document.querySelectorAll('#AddonSectionData');
			var index = tbody.length + 1;

			var nomorCol = document.createElement('td');
			nomorCol.style.border = 'none';

			var NamaVariant = document.createElement('td');
			NamaVariant.className = 'p-1 ps-4 align-middle text-muted';
			NamaVariant.style.fontSize = '0.74rem';
			NamaVariant.style.border = 'none';

			var ExtraQty = document.createElement('td');
			ExtraQty.className = 'p-1 text-center align-middle text-muted';
			ExtraQty.style.fontSize = '0.74rem';
			ExtraQty.style.border = 'none';

			var ExtraPrice = document.createElement('td');
			ExtraPrice.className = 'p-1 text-end align-middle text-muted font-weight-bold';
			ExtraPrice.style.fontSize = '0.74rem';
			ExtraPrice.style.border = 'none';

			var MenuAddonID = document.createElement('input');
			var txtNamaVariant = document.createElement("input");
			var txtExtraPrice = document.createElement("input");
			var txtExtraQty = document.createElement("input");
			var txtKodeItem = document.createElement("input");

			MenuAddonID.type  = 'hidden';
			MenuAddonID.id = "txtMenuAddonID";
			MenuAddonID.name = 'AddonParameter['+index+'][MenuAddonID]';
			MenuAddonID.value = oData["AddonMenuID"]
			NamaVariant.appendChild(MenuAddonID);

			txtKodeItem.type  = 'hidden';
			txtKodeItem.id = "txtKodeItem";
			txtKodeItem.name = 'AddonParameter['+index+'][KodeItem]';
			txtKodeItem.value = KodeItem;
			NamaVariant.appendChild(txtKodeItem);

			txtNamaVariant.type  = 'text';
			txtNamaVariant.id = "txtNamaItem";
			txtNamaVariant.name = 'AddonParameter['+index+'][NamaAddon]';
			txtNamaVariant.className = 'bg-transparent border-0 p-0 text-muted w-100';
			txtNamaVariant.style.fontSize = '0.74rem';
			txtNamaVariant.style.fontStyle = 'italic';
			txtNamaVariant.value = '├─ ' + oData["NamaAddon"];
			txtNamaVariant.readOnly = true;
			NamaVariant.appendChild(txtNamaVariant);

			txtExtraPrice.type  = 'hidden';
			txtExtraPrice.id = "txtExtraPrice";
			txtExtraPrice.name = 'AddonParameter['+index+'][HargaAddon]';
			txtExtraPrice.value = oData["HargaAddon"]
			ExtraPrice.appendChild(txtExtraPrice);
			
			var formattedPrice = document.createTextNode(parseFloat(oData["HargaAddon"]).toLocaleString('id-ID'));
			ExtraPrice.appendChild(formattedPrice);

			txtExtraQty.type  = 'hidden';
			txtExtraQty.id = "txtExtraQty";
			txtExtraQty.name = 'AddonParameter['+index+'][Qty]';
			txtExtraQty.value = 1
			ExtraQty.appendChild(txtExtraQty);
			ExtraQty.appendChild(document.createTextNode('1'));

			newRow.appendChild(nomorCol);
			newRow.appendChild(NamaVariant);
			newRow.appendChild(ExtraQty);
			newRow.appendChild(ExtraPrice);
			document.getElementById('AppendArea').appendChild(newRow);
		}
	}

	function CalculateRowTotal(qty, harga, diskon) {
		return (qty * harga) - ((diskon / 100) * (qty * harga));
	}

	function updateTotal(row) {
		var qty = row.querySelector('input[id="txtQty"]').value;
		var harga = row.querySelector('input[id="txtHarga"]').value;
		var diskon = row.querySelector('input[id="txtDiskon"]').value;
		var totalText = row.querySelector('input[id="txtTotal"]');
		totalText.value = CalculateRowTotal(qty, harga, diskon);
	}

	function AsignRowNumber() {
        var tbody = document.querySelectorAll('#InputSectionData');
        tbody.forEach(function(row, index) {
            var firstCell = row.querySelector('td:first-child');
            if (firstCell) {
                firstCell.textContent = index + 1;
            }
        });
    }

	function generateRandomText(length) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let randomText = '';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            randomText += characters[randomIndex];
        }
        return randomText;
    }

	function FirstRowHandling() {
		var tbody = document.querySelectorAll('#InputSectionData');
		if (tbody.length == 1) {
			var emptyMessage = document.querySelector('td.dataTables_empty');
			if (emptyMessage) {
				emptyMessage.remove();
			}
		}
		else if (tbody.length == 0) {
			var newEmptyMessage = document.createElement('tr');
			var emptyCell = document.createElement('td');
			emptyCell.colSpan = 4;
			emptyCell.className = 'dataTables_empty p-4 text-center text-muted';
			emptyCell.textContent = 'Keranjang kosong';
			newEmptyMessage.appendChild(emptyCell);
			document.getElementById('AppendArea').appendChild(newEmptyMessage);
		}
		AsignRowNumber();
	}

	var _oVariantMenu = <?php echo json_encode($variantmenu) ?>;

	function loadCatalogProducts() {
		var activeCat = _ActiveCategory === 'ALL' ? '' : _ActiveCategory;
		$.ajax({
			type: 'post',
			url: "{{route('menu-ViewJson')}}",
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			data: {
				'KodeJenis' : activeCat,
			},
			dataType: 'json',
			success: function(response) {
				if(response && response.data) {
					_AllProducts = response.data;
					$('#_totalCatalogItems').text(_AllProducts.length);
					renderCatalog(_AllProducts);
				}
			},
			error: function() {
				$('#_productGridContainer').html('<div class="col-12 text-center text-danger py-4"><i class="fas fa-exclamation-triangle"></i> Gagal memuat produk.</div>');
			}
		});
	}

	function renderCatalog(products) {
		var container = $('#_productGridContainer');
		container.empty();
		if(products.length === 0) {
			container.html('<div class="col-12 text-center text-muted py-5">Tidak ada produk yang cocok.</div>');
			return;
		}
		
		products.forEach(function(item) {
			var priceFormatted = parseFloat(item.HargaJual || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
			
			var imgUrl = item.Gambar ? (item.Gambar.startsWith('http://') || item.Gambar.startsWith('https://') || item.Gambar.startsWith('data:') ? item.Gambar : `{{ asset('assets/img/item') }}/${item.Gambar}`) : `https://placehold.co/150x100/e2e8f0/475569?text=${encodeURIComponent(item.NamaItem)}`;

			var cardHtml = `
				<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mb-2 px-1">
					<div class="catalog-product-card" onclick="clickCatalogProduct('${item.KodeItem}')" style="padding: 5px !important; height: 165px; display: flex; flex-direction: column; justify-content: flex-start; gap: 4px;">
						<div class="catalog-product-img-wrapper" style="height: 82px; width: 100%; border-radius: 8px; overflow: hidden; background: #f8fafc; position: relative; flex-shrink: 0;">
							<img src="${imgUrl}" onerror="this.src='https://placehold.co/150x100/e2e8f0/475569?text=Product'" style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
							<span class="catalog-product-badge" style="position: absolute; right: 5px; top: 5px; background: rgba(79, 70, 229, 0.9); color: white; padding: 1px 4px; font-size: 0.52rem; border-radius: 4px; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">${item.NamaSatuan || 'PCS'}</span>
						</div>
						<div class="catalog-product-details-wrapper" style="display: flex; flex-direction: column; gap: 0.5px; padding: 2px 1px 0 1px; overflow: hidden; justify-content: flex-start;">
							<div class="catalog-product-title" title="${item.NamaItem}" style="font-size: 0.7rem; font-weight: 750; color: #1e293b; line-height: 1.2; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; max-height: 2.4em; min-height: 1.2em; margin-bottom: 0px;">${item.NamaItem}</div>
							<div class="catalog-product-info-footer" style="display: flex; flex-direction: column; line-height: 1; margin-top: 0px;">
								<span class="catalog-product-code" style="font-size: 0.58rem; color: #94a3b8; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1;">${item.KodeItem}</span>
								<span class="catalog-product-price" style="font-size: 0.74rem; font-weight: 850; color: #059669; line-height: 1.1; margin-top: 1px;">${priceFormatted}</span>
							</div>
						</div>
					</div>
				</div>
			`;
			container.append(cardHtml);
		});
	}

	function filterCatalog() {
		var searchQuery = $('#_CatalogSearch').val().toLowerCase();
		var filtered = _AllProducts.filter(function(item) {
			var matchesCategory = true;
			if (_ActiveCategory !== 'ALL') {
				matchesCategory = (item.KodeJenisItem === _ActiveCategory);
			}
			
			var matchesSearch = true;
			if (searchQuery) {
				var name = (item.NamaItem || '').toLowerCase();
				var code = (item.KodeItem || '').toLowerCase();
				var barcode = (item.Barcode || '').toLowerCase();
				matchesSearch = (name.indexOf(searchQuery) !== -1 || code.indexOf(searchQuery) !== -1 || barcode.indexOf(searchQuery) !== -1);
			}
			
			return matchesCategory && matchesSearch;
		});
		renderCatalog(filtered);
	}

	function addProductToCartDirectly(kodeItem) {
		clickCatalogProduct(kodeItem);
	}

	function switchActiveInput(field) {
		_ActiveNumpadField = field;
		$('.btn-numpad-action').removeClass('active');
		
		if (field === 'QTY') {
			$('#_btnNumpadQty').addClass('active');
			$('#_activeInputIndicator').text('Kuantitas (Qty)').removeClass('bg-light-danger bg-light-success bg-light-info text-danger text-success text-info').addClass('bg-light-primary text-primary');
			$('#_TipeDiskon').text('');
			$('#_Qty').focus().select();
		} else if (field === 'DISC_P') {
			$('#_btnNumpadDiscP').addClass('active');
			$('#_activeInputIndicator').text('Diskon Persen (%)').removeClass('bg-light-primary bg-light-danger bg-light-success text-primary text-danger text-success').addClass('bg-light-info text-info');
			$('#_TipeDiskon').text('(%)');
			$('#_Diskon').focus().select();
		} else if (field === 'DISC_R') {
			$('#_btnNumpadDiscR').addClass('active');
			$('#_activeInputIndicator').text('Diskon Rupiah (Rp)').removeClass('bg-light-primary bg-light-info bg-light-success text-primary text-info text-success').addClass('bg-light-danger text-danger');
			$('#_TipeDiskon').text('(Rp)');
			$('#_Diskon').focus().select();
		}
	}

	function pressNumpad(key) {
		var activeRow = $('#AppendArea tr#InputSectionData.selected-row');
		if (activeRow.length === 0) {
			// If no row is selected, focus search
			$('#_Barcode').focus();
			return;
		}

		var inputEl;
		if (_ActiveNumpadField === 'QTY') {
			inputEl = activeRow.find('input[id="txtQty"]');
		} else {
			inputEl = activeRow.find('input[id="txtDiskon"]');
		}

		if (inputEl.length === 0) return;

		var currentVal = inputEl.val() || '0';

		if (key === 'C') {
			inputEl.val('0');
		} else if (key === 'ENTER') {
			$('#_Barcode').focus();
			switchActiveInput('QTY');
		} else {
			if (currentVal === '0') {
				inputEl.val(key);
			} else {
				inputEl.val(currentVal + key);
			}
		}
		
		inputEl.trigger('change').trigger('input');
		
		if (_ActiveNumpadField === 'QTY') {
			$('#_Qty').val(inputEl.val());
		} else {
			$('#_Diskon').val(inputEl.val());
		}

		updateTotal(activeRow[0]);
		CalculateTotal();
	}


	document.addEventListener('DOMContentLoaded', () => {
	    const listItems = document.querySelectorAll('.horizontal-list li');
	    // console.log(listItems);
	    listItems.forEach(item => {
	        item.addEventListener('click', () => {
	            // Remove active class from all items
	            listItems.forEach(i => i.classList.remove('active'));

	            // Add active class to the clicked item
	            var Sts = $(item).attr('StsPyment') || $(item).attr('stspyment');
				_MetodeVerifikasiPembayaran = $(item).attr('CaraVerifikasi') || $(item).attr('caraverifikasi');
				_TipePembayaran = $(item).attr('TipePembayaran') || $(item).attr('tipepembayaran');
				
	            if (Sts =='Y') {
	            	item.classList.add('active');
	            	_KodeMetodePembayaran = item.id;
					if (_TipePembayaran == "NON") {
						let netVal = parseFloat(jQuery('#_TotalNetBayar').attr("originalvalue") || 0);
						formatCurrency($('#JumlahBayar'), netVal);
					}
					else{
						formatCurrency($('#JumlahBayar'), 0);
					}
	            	$('#JumlahBayar').focus();
					SetEnableCommand();
	            }
	        });
	    });

	    SetEnableCommand();
	});
	jQuery(function () {
		jQuery(document).ready(function() {

			$('#_Barcode').focus();

			// Cart row selection listener
			jQuery(document).on('click', '#AppendArea tr#InputSectionData', function() {
				jQuery('#AppendArea tr#InputSectionData').removeClass('selected-row');
				jQuery(this).addClass('selected-row');
				
				// Sync UI inputs with row data
				var rowQty = jQuery(this).find('input[id="txtQty"]').val();
				var rowDiskon = jQuery(this).find('input[id="txtDiskon"]').val();
				jQuery('#_Qty').val(rowQty || 0);
				jQuery('#_Diskon').val(rowDiskon || 0);
			});
			
			// Bi-directional sync from UI inputs to active row
			$('#_Qty').on('input', function() {
				var activeRow = $('#AppendArea tr#InputSectionData.selected-row');
				if (activeRow.length > 0) {
					activeRow.find('input[id="txtQty"]').val($(this).val() || 0);
					updateTotal(activeRow[0]);
					CalculateTotal();
				}
			});
			
			$('#_Diskon').on('input', function() {
				var activeRow = $('#AppendArea tr#InputSectionData.selected-row');
				if (activeRow.length > 0) {
					activeRow.find('input[id="txtDiskon"]').val($(this).val() || 0);
					updateTotal(activeRow[0]);
					CalculateTotal();
				}
			});

			// F&B Modal triggers: Open Tipe Order modal when button is clicked
			jQuery(document).on('click', '#btTipeOrder', function() {
				jQuery('#LookupTipeOrder').modal({backdrop: 'static', keyboard: false});
				jQuery('#LookupTipeOrder').modal('show');
			});

			// F&B Modal triggers: Open Nomor Meja modal when button is clicked
			jQuery(document).on('click', '#btNomorMeja', function() {
				jQuery('#chkNyalakanLampuMeja').prop('checked', _NyalakanLampu == 1);
				jQuery('#LookupNomorMeja').modal({backdrop: 'static', keyboard: false});
				jQuery('#LookupNomorMeja').modal('show');
			});

			// F&B Modal triggers and click handlers (inside Tipe Order modal)
			jQuery('.horizontal-list.tipeorder li').click(function() {
				jQuery('.horizontal-list.tipeorder li').removeClass('active').css('border-color', '#cbd5e1').css('background', '#ffffff');
				jQuery(this).addClass('active').css('border-color', '#4f46e5').css('background', 'rgba(79, 70, 229, 0.05)');
				
				_idJenisOrder = jQuery(this).attr('id');
				_NamaJenisOrder = jQuery(this).attr('NamaJenisOrder');
				_DineIn = jQuery(this).attr('DineIn');
			});

			jQuery('.horizontal-list-meja.nomormeja li').click(function() {
				jQuery('.horizontal-list-meja.nomormeja li').removeClass('active').css('border-color', '#cbd5e1').css('background', '#ffffff');
				jQuery(this).addClass('active').css('border-color', '#4f46e5').css('background', 'rgba(79, 70, 229, 0.05)');
				
				_KodeMeja = jQuery(this).attr('id');
				_NamaMeja = jQuery(this).attr('NamaMeja');
			});

			jQuery('#btPilihTipeOrder').click(function () {
				jQuery('#LookupTipeOrder').modal('hide');
				
				var xHTML = "";
				if (_idJenisOrder > -1) {
					xHTML += '<h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.82rem;">' + _NamaJenisOrder + '</h6>';
					
					if (_DineIn == 'Y') {
						jQuery('#chkNyalakanLampuMeja').prop('checked', _NyalakanLampu == 1);
						jQuery('#LookupNomorMeja').modal({backdrop: 'static', keyboard: false});
						jQuery('#LookupNomorMeja').modal('show');
					}
				}
				jQuery('#txtTipeOrder').html(xHTML || '<h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.82rem;">Pilih</h6>');
				SetEnableCommand();
			});

			jQuery('#btPilihNomorMeja').click(function () {
				jQuery('#LookupNomorMeja').modal('hide');

				_NyalakanLampu = jQuery('#chkNyalakanLampuMeja').is(':checked') ? 1 : 0;

				var xHTML = "";
				if (_KodeMeja && _NamaMeja) {
					xHTML = '<h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.82rem;">' + _NamaMeja + '</h6>';
				}
				jQuery('#txtNomorMeja').html(xHTML || '<h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.82rem;">Pilih</h6>');
				SetEnableCommand();
			});


			jQuery('#btPilihVariant').click(function () {
				var KodeItem = jQuery('#VariantFatherItemCode').val();
				jQuery('#LookupMenuVariant').modal('hide');
				AddNewRow(KodeItem);
				AddAddonVariantMenu(KodeItem);
				
				var dataGridInstance = jQuery('#gridLookupAddon').dxDataGrid('instance');
				var selectedRows = dataGridInstance.getSelectedRowsData();

				if (selectedRows.length > 0) {
					for (let index = 0; index < selectedRows.length; index++) {
						AddAddonMenu(KodeItem, selectedRows[index]);
					}
				}
				dataGridInstance.deselectAll();
				CalculateTotal();
			});

			$('#btnApplyVoucher').click(function() {
				var code = $('#_VoucherCode').val().trim().toUpperCase();
				if (!code) {
					Swal.fire({
						icon: "warning",
						title: "Perhatian",
						text: "Silakan masukkan kode voucher terlebih dahulu.",
					});
					return;
				}

				var kodePartner = _Company[0]['KodePartner'];
				var encodedId = btoa(kodePartner);

				$.ajax({
					type: 'GET',
					url: `/booking/${encodedId}/get-DiscountVoucher`,
					data: {
						code: code,
						kodePartner: kodePartner
					},
					success: function(response) {
						if (response.success) {
							// Validate expiry date
							var today = new Date();
							var startDate = new Date(response.startDate);
							var endDate = new Date(response.endDate);
							today.setHours(0, 0, 0, 0);
							startDate.setHours(0, 0, 0, 0);
							endDate.setHours(23, 59, 59, 999);

							if (today < startDate) {
								Swal.fire({
									icon: "error",
									title: "Voucher Belum Aktif",
									text: `Voucher ini baru bisa digunakan mulai tanggal ${response.startDate}.`,
								});
								return;
							}

							if (today > endDate) {
								Swal.fire({
									icon: "error",
									title: "Voucher Kedaluwarsa",
									text: "Voucher ini sudah tidak berlaku lagi.",
								});
								return;
							}

							if (response.discountQuota <= 0) {
								Swal.fire({
									icon: "error",
									title: "Kuota Habis",
									text: "Voucher ini sudah kehabisan kuota penggunaan.",
								});
								return;
							}

							// Voucher is valid!
							_VoucherDiscountPercent = parseFloat(response.discountPercent) || 0;
							_VoucherMaximalDiscount = parseFloat(response.maximalDiscount) || 0;
							_VoucherAppliedCode = code;

							Swal.fire({
								icon: "success",
								title: "Voucher Berhasil Digunakan!",
								text: `Diskon sebesar ${response.discountPercent}% berhasil diterapkan.`,
							});

							CalculateTotal();
						} else {
							Swal.fire({
								icon: "error",
								title: "Gagal",
								text: response.message || "Voucher tidak valid.",
							});
						}
					},
					error: function(xhr) {
						var msg = "Voucher tidak valid atau tidak ditemukan.";
						if (xhr.responseJSON && xhr.responseJSON.message) {
							msg = xhr.responseJSON.message;
						}
						Swal.fire({
							icon: "error",
							title: "Gagal",
							text: msg,
						});
					}
				});
			});

			// Advanced global barcode scanner redirector
			var barcodePressedKeys = [];
			
			jQuery(document).on('keydown', function(e) {
				// Do not intercept if a modal is open (like payment popup, shipping cost, etc.)
				if (jQuery('.modal.show').length > 0) return;
				
				// Ignore control keys, function keys, shift, alt, etc.
				if (e.ctrlKey || e.altKey || e.metaKey || e.key === 'Shift' || e.key === 'Control' || e.key === 'Alt') {
					return;
				}
				
				// If already focused on barcode, let it type naturally
				if (document.activeElement.id === '_Barcode') {
					return;
				}
				
				// Track key and timestamp
				var timeStamp = new Date().getTime();
				barcodePressedKeys.push({
					key: e.key,
					time: timeStamp
				});
				
				// Keep buffer clean: only keys within the last 300ms
				barcodePressedKeys = barcodePressedKeys.filter(function(item) {
					return (timeStamp - item.time) < 300;
				});
				
				// Detect hardware barcode scanner (very rapid key sequences)
				if (barcodePressedKeys.length >= 3) {
					// Check if they are just repeating a single held-down key (e.g. 'aaaa')
					var allIdentical = true;
					for (var i = 1; i < barcodePressedKeys.length; i++) {
						if (barcodePressedKeys[i].key !== barcodePressedKeys[0].key) {
							allIdentical = false;
							break;
						}
					}
					
					if (!allIdentical) {
						var totalInterval = 0;
						for (var i = 1; i < barcodePressedKeys.length; i++) {
							totalInterval += (barcodePressedKeys[i].time - barcodePressedKeys[i - 1].time);
						}
						var avgInterval = totalInterval / (barcodePressedKeys.length - 1);
						
						if (avgInterval < 45) { // Average key interval under 45ms indicates hardware scanner
							var barcodeInput = jQuery('#_Barcode');
							if (barcodeInput.length) {
								var currentActive = document.activeElement;
								var currentActiveId = currentActive.id;
								
								// Shift focus to barcode input
								barcodeInput.focus();
								
								// Extract scanned characters from key history
								var scannedString = barcodePressedKeys.map(function(item) {
									return item.key.length === 1 ? item.key : '';
								}).join('');
								
								// Clean up accidentally modified inputs
								if (currentActiveId === '_Qty') {
									var val = jQuery(currentActive).val() || '';
									var cleanVal = val;
									for (var k = 0; k < scannedString.length; k++) {
										cleanVal = cleanVal.replace(scannedString[k], '');
									}
									jQuery(currentActive).val(cleanVal || '0');
								} else if (currentActiveId === '_Diskon') {
									var val = jQuery(currentActive).val() || '';
									var cleanVal = val;
									for (var k = 0; k < scannedString.length; k++) {
										cleanVal = cleanVal.replace(scannedString[k], '');
									}
									jQuery(currentActive).val(cleanVal || '0');
								} else if (currentActiveId === '_CatalogSearch') {
									var val = jQuery(currentActive).val() || '';
									var cleanVal = val;
									for (var k = 0; k < scannedString.length; k++) {
										cleanVal = cleanVal.replace(scannedString[k], '');
									}
									jQuery(currentActive).val(cleanVal);
								}
								
								// Append scanned string to barcode input
								barcodeInput.val(barcodeInput.val() + scannedString);
								
								// Clear buffer to prevent double triggers
								barcodePressedKeys = [];
								e.preventDefault();
							}
						}
					}
				}
				
				// Standard redirect: if not focused on any text input, redirect single keypress instantly
				var activeTag = document.activeElement.tagName.toLowerCase();
				var activeType = document.activeElement.type ? document.activeElement.type.toLowerCase() : '';
				var isTextFocused = (activeTag === 'input' && (activeType === 'text' || activeType === 'number' || activeType === 'search')) || activeTag === 'textarea' || activeTag === 'select';
				
				if (!isTextFocused) {
					var barcodeInput = jQuery('#_Barcode');
					if (barcodeInput.length) {
						barcodeInput.focus();
					}
				}
			});

			bindGrid([]);

			var xdata = <?php echo $itemServices ?>;
			// console.log(xdata);

			jQuery('.Select2-Selector').select2({
				dropdownParent: $('#shippingcost')
			});

			jQuery('.js-example-basic-single').select2({
				dropdownParent: $('#LookupAddCustomer')
			});

			jQuery('#KodePelanggan').select2({ width: '100%' });

			var now = new Date();
	    	var day = ("0" + now.getDate()).slice(-2);
	    	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	    	var hours = now.getHours().toString().padStart(2, '0');
			var minutes = now.getMinutes().toString().padStart(2, '0');
			var seconds = now.getSeconds().toString().padStart(2, '0');

	    	var firstDay = now.getFullYear()+"-"+month+"-01";
	    	var NowDay = now.getFullYear()+"-"+month+"-"+day;

	    	_Tanggal = NowDay;
	    	_Jam = hours+":"+minutes+":"+seconds;

	    	_Company = <?php echo $company ?>;
	    	_Pelanggan = <?php echo $pelanggan ?>;
			_Printer = <?php echo $printer ?>;

			let url = new URL("{{ url('') }}");
	    	LoadDraftOrderList();
	    	bindGridLookupCustomer(_Pelanggan);

	    	jQuery('#_NoTransaksi').text("<OTOMATIS>");

			updateCustomerDisplay({data: [], Total: 0, Discount: 0, VoucherDiscount: 0, Net: 0, Tax: 0});

			// Initialize Cashier Hybrid Interactive Features
			switchActiveInput('QTY');
			loadCatalogProducts();

			// Bind Category Pills Click
			$(document).on('click', '.cat-pill', function() {
				$('.cat-pill').removeClass('active btn-primary').addClass('btn-outline-secondary').css('background', 'transparent');
				$(this).addClass('active btn-primary').removeClass('btn-outline-secondary').css('background', 'var(--primary)');
				_ActiveCategory = $(this).attr('data-category');
				filterCatalog();
			});

			// Bind Search Catalog Query
			$('#_CatalogSearch').on('input', function() {
				filterCatalog();
			});
		});

		window.UpdateCurrentTime = function UpdateCurrentTime() {
			var now = new Date();
			var day = ("0" + now.getDate()).slice(-2);
			var month = ("0" + (now.getMonth() + 1)).slice(-2);
			var hours = now.getHours().toString().padStart(2, '0');
			var minutes = now.getMinutes().toString().padStart(2, '0');
			var seconds = now.getSeconds().toString().padStart(2, '0');

			var NowDay = now.getFullYear() + "-" + month + "-" + day;
			_Tanggal = NowDay;
			_Jam = hours + ":" + minutes + ":" + seconds;
		}



		$('#_Barcode').on("keypress", function(e) {
			// console.log(e)
	        if (e.keyCode == 13) {
	            $.ajax({
		            async:false,
		            type: 'post',
		            url: "{{route('itemmaster-ViewJson')}}",
		            headers: {
		                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
		            },
		            data: {
		                'KodeJenis' : '',
		                'Merk' 		: '',
		                'TipeItem' 	: '',
		                'Active' : 'Y',
				'KategoriPOS' : 'FNB',
		                'Scan'		: jQuery('#_Barcode').val(),
		                'TipeItemIN' : '1,3,5'
		            },
		            dataType: 'json',
		            success: function(response) {
		            	if (false) { // Disabled shipping/product lookup modal since catalog is already visible on the side
		            		bindGridLookup(response.data);
		            		jQuery('#LookupItem').modal({backdrop: 'static', keyboard: false})
		            		jQuery('#LookupItem').modal('show');
		            	}
		            	else{
		            		var barcodeVal = jQuery('#_Barcode').val().trim().toLowerCase();
		            		var exactMatches = response.data.filter(function(item) {
		            			return (item.Barcode || '').toLowerCase() === barcodeVal || (item.KodeItem || '').toLowerCase() === barcodeVal;
		            		});

		            		if (exactMatches.length > 0) {
								clickCatalogProduct(exactMatches[0]['KodeItem']);
		            		}
		            		else{
		            			Swal.fire({
			                      icon: "error",
			                      title: "Error",
			                      text: "Data Tidak ditemukan",
			                    }).then((result) => {
								  $('#_Barcode').val("")
								  $('#_Barcode').focus()
								});	
		            		}
		            	}
		            }
		        });

				$('#_Barcode').val("")
				$('#_Barcode').focus()
				CalculateTotal();
	        }
		});

		// Global Document keyboard event listener for cashier interactive shortcuts (F1-F7, DEL)
		$(document).on("keydown", function(e) {
			var key = e.which || e.keyCode;

			// Intercept F1 - F7 and DEL (46)
			if (key === 112 || key === 113 || key === 114 || key === 115 || key === 116 || key === 117 || key === 118 || key === 46) {
				e.preventDefault(); // Prevent standard browser behaviors (help, search, reload, address bar focus)

				if (key === 112) { // F1: Focus Barcode Scanner
					$('#_Barcode').focus().select();
				}
				else if (key === 113) { // F2: Edit Qty
					if (_LastInputed != "") {
						$('#_Qty').focus().select();
					}
				}
				else if (key === 114) { // F3: Diskon %
					if (_LastInputed != "") {
						$('#_Diskon').focus().select();
						$('#_TipeDiskon').text(" (%)");
						_TipeDiskon = "%";
					}
				}
				else if (key === 115) { // F4: Diskon Rp
					if (_LastInputed != "") {
						$('#_Diskon').focus().select();
						$('#_TipeDiskon').text(" (Rp)");
						_TipeDiskon = "Rp";
					}
				}
				else if (key === 116) { // F5: Bayar Sekarang
					$('#btBayar').click();
				}
				else if (key === 117) { // F6: Simpan Draft
					$('#btDraft').click();
				}
				else if (key === 118) { // F7: Tambah Jasa
					$('#btshippingcost').click();
				}
				else if (key === 46) { // DEL: Batal Transaksi
					$('#btBatal').click();
				}
			}
		});



		$('#btPilihLookupData').click(function () {
			var dataGridInstance = jQuery('#gridLookupItem').dxDataGrid('instance');
			var selectedRows = dataGridInstance.getSelectedRowsData();

			if (selectedRows.length > 0) {
				jQuery('#LookupItem').modal('hide');
				$('#_Barcode').val(selectedRows[0]['KodeItem']);
				$('#_Barcode').focus();

				var e = $.Event('keypress');
				e.keyCode = 13;
				$('#_Barcode').trigger(e);
			}

		});

		$('#btBatal').click(function () {
			location.reload()
		});

		$('#btshippingcost').click(function () {
			// bindGridLookupServices(_ServicesData);
			$('#KodeItemJasa').val('').trigger('change');
			$('#JumlahJasa').val('0');
			$('#KeteranganJasa').val('');
			jQuery('#shippingcost').modal({backdrop: 'static', keyboard: false})
		    jQuery('#shippingcost').modal('show');
		})

		$('#btLookupBiaya').click(function () {
			jQuery('#shippingcost').modal('hide');

			var item ={
				'KodeItem'  : $('#KodeItemJasa').val(),
				'Jumlah'	: $('#JumlahJasa').attr("originalvalue"),
				'Keterangan': $('#KeteranganJasa').val()
			}

			_ServicesData.push(item);

			CalculateTotal();
		});

		$('#JumlahJasa').focusout(function(){
			formatCurrency($("#JumlahJasa"), $("#JumlahJasa").val());
		});

		$('#JumlahBayar').focusout(function(){
			formatCurrency($("#JumlahBayar"), $("#JumlahBayar").val());
			SetEnableCommand();
		});

		$('#JumlahBayar').on('input', function(){
			let rawVal = $(this).val();
			let cleanAmount = rawVal.replace(/Rp\.?\s*/i, '');
			let commaCount = (cleanAmount.match(/,/g) || []).length;
			let dotCount = (cleanAmount.match(/\./g) || []).length;
			
			if (commaCount > 0 && dotCount > 0) {
				if (cleanAmount.indexOf(',') < cleanAmount.indexOf('.')) {
					cleanAmount = cleanAmount.replace(/,/g, '');
				} else {
					cleanAmount = cleanAmount.replace(/\./g, '').replace(/,/g, '.');
				}
			} else if (commaCount > 0) {
				let parts = cleanAmount.split(',');
				if (parts.length === 2 && parts[1].length <= 2) {
					cleanAmount = cleanAmount.replace(/,/g, '.');
				} else {
					cleanAmount = cleanAmount.replace(/,/g, '');
				}
			} else if (dotCount > 0) {
				let parts = cleanAmount.split('.');
				if (parts.length === 2 && parts[1].length <= 2) {
					// Standar desimal
				} else {
					cleanAmount = cleanAmount.replace(/\./g, '');
				}
			}
			let parsedAmount = parseFloat(cleanAmount);
			if (isNaN(parsedAmount)) parsedAmount = 0;
			$(this).attr("originalvalue", parsedAmount);
			SetEnableCommand();
		});

		jQuery('#KodePelanggan').change(function () {
			$.ajax({
	            async:false,
	            type: 'post',
	            url: "{{route('pelanggan-viewJson')}}",
	            headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
	            data: {
	                'KodePelanggan' : $('#KodePelanggan').val(),
	                'GrupPelanggan' : ''
	            },
	            dataType: 'json',
	            success: function(response) {
	            	if (response.data.length > 0) {
	            		_DiskonGrupCustomer = response.data[0]['DiskonPersen'];
	            		_TerminPelanggan = response.data[0]['DiskonPersen'];

                        _DiskonMemberPersen = parseFloat(response.data[0]['DiskonMemberPersen'] || 0);
                        _DiskonGrupCustomer = parseFloat(_DiskonGrupCustomer || 0) + _DiskonMemberPersen;
                        _SisaGratisOngkir = parseFloat(response.data[0]['SisaGratisOngkir'] || 0);
                        _PoinLoyalti = parseFloat(response.data[0]['PoinLoyalti'] || 0);

						var rows = jQuery('#AppendArea tr#InputSectionData');
						if (rows.length > 0) {
							rows.each(function() {
								var rowDiskon = jQuery(this).find('input[id="txtDiskon"]');
								rowDiskon.val(_DiskonGrupCustomer);
								updateTotal(this);
							});
							CalculateTotal();
						}
	            	}
	            }
	        });
		});

		$('#btDraft').click(function () {
			SaveData('T',$('#btDraft'),'Simpan Sementara');
		});

		$('#btBayar').click(function () {
			// payment-popup
			jQuery('#payment-popup').modal({backdrop: 'static', keyboard: false})
		    jQuery('#payment-popup').modal('show');

		    var TotalPenjualan = parseFloat($('#_GrandTotal').attr('originalvalue') || 0);
		    $('#_TotalTagihan').val(TotalPenjualan);
		    $('#_TotalTagihanFormated').text($('#_GrandTotal').val())

		    // Voucher details in Payment Modal
		    var voucherDiscount = parseFloat($('#_VoucherDiscount').attr('originalvalue') || 0);
		    if (voucherDiscount > 0) {
		    	$('#lblPaymentVoucher').text('Voucher: ' + _VoucherAppliedCode);
		    	$('#valPaymentVoucher').text('- ' + $('#_VoucherDiscount').val());
		    	$('#rowPaymentVoucher').attr('style', 'display: flex !important;');
		    } else {
		    	$('#rowPaymentVoucher').attr('style', 'display: none !important;');
		    }

			// Poin Loyalti
			if (_PoinLoyalti > 0 && _Company.length > 0 && (_Company[0]['NilaiTukarPoin'] || 0) > 0) {
				$('#rowTukarPoin').attr('style', 'display: flex !important;');
				$('#btnTukarPoin').show();
				$('#lblTukarPoin').html('Poin (' + _PoinLoyalti + ') <button class="btn btn-sm btn-outline-success ml-2" type="button" id="btnTukarPoin">Tukar</button>');
			} else {
				$('#rowTukarPoin').attr('style', 'display: none !important;');
			}

			// Pembulatan
			var TotalPembulatan = Math.ceil(TotalPenjualan);
			var NilaiPembulatan = TotalPembulatan - TotalPenjualan;
			// console.log(NilaiPembulatan)
			// formatCurrency($('#_TotalServices'), _tempTotalServices);
			// $('#_Pembulatan').val();
			formatCurrency($('#_Pembulatan'), NilaiPembulatan)
		    $('#_PembulatanFormated').text($('#_Pembulatan').val())

			// Total Penjualan
			// $('#_TotalNetBayar').val();
			formatCurrency($('#_TotalNetBayar'), TotalPembulatan)
		    $('#_TotalNetBayarFormated').text($('#_TotalNetBayar').val())
		});

		$('#payment-popup').on('shown.bs.modal', function () {
			var current = JSON.parse(localStorage.getItem('PoSData') || '{}');
			current.isCheckout = true;
			updateCustomerDisplay(current);
		});

		$('#payment-popup').on('hidden.bs.modal', function () {
			var current = JSON.parse(localStorage.getItem('PoSData') || '{}');
			current.isCheckout = false;
			updateCustomerDisplay(current);
		});

		$('#btnTukarPoin').click(function () {
			Swal.fire({
				title: 'Tukar Poin Loyalti',
				text: "Anda memiliki " + _PoinLoyalti + " poin. Berapa poin yang ingin ditukar? (Tiap 1 Poin = Rp. " + parseFloat(_Company[0]['NilaiTukarPoin'] || 0).toLocaleString('id-ID') + ")",
				icon: 'question',
				input: 'number',
				inputAttributes: {
					min: 1,
					max: _PoinLoyalti,
					step: 1
				},
				showCancelButton: true,
				confirmButtonText: 'Tukar',
				cancelButtonText: 'Batal',
				inputValidator: (value) => {
					if (!value || parseInt(value) <= 0) {
						return 'Masukkan jumlah poin yang valid!'
					}
					if (parseInt(value) > _PoinLoyalti) {
						return 'Poin tidak mencukupi!'
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					var poinTukar = parseInt(result.value);
					if (poinTukar > 0 && poinTukar <= _PoinLoyalti) {
						var nilaiTukar = poinTukar * parseFloat(_Company[0]['NilaiTukarPoin'] || 0);
						$('#_NilaiTukarPoin').val(nilaiTukar);
						$('#_PoinDitukar').val(poinTukar);
						$('#valTukarPoin').text('- Rp. ' + nilaiTukar.toLocaleString('id-ID'));
						$('#btnTukarPoin').hide();
						
						// Recalculate total bayar
						var TotalPenjualan = parseFloat($('#_GrandTotal').attr('originalvalue') || 0);
						var TotalPembulatan = Math.ceil(TotalPenjualan);
						var finalBayar = TotalPembulatan - nilaiTukar;
						if (finalBayar < 0) finalBayar = 0;
						formatCurrency($('#_TotalNetBayar'), finalBayar);
						$('#_TotalNetBayarFormated').text($('#_TotalNetBayar').val());
					}
				}
			});
		});

		$('#btSimpanPembayaran').click(function () {
			// PaymentGateWay();

			if (_MetodeVerifikasiPembayaran == "AUTO") {
				PaymentGateWay('C',$('#btSimpanPembayaran'),'Submit');
			}
			else{
				SaveData('C',$('#btSimpanPembayaran'),'Submit');
			}
		});

		$('#btSearchCustomer').click(function () {
			jQuery('#LookupCustomer').modal({backdrop: 'static', keyboard: false})
			jQuery('#LookupCustomer').modal('show');
		});

		$('#btPilihCustomer').click(function () {
			var dataGridInstance = jQuery('#gridLookupCustomer').dxDataGrid('instance');
			var selectedRows = dataGridInstance.getSelectedRowsData();

			// console.log(selectedRows);
			if (selectedRows.length > 0) {
				jQuery('#LookupCustomer').modal('hide');
				jQuery('#KodePelanggan').val(selectedRows[0]['KodePelanggan']).trigger('change');
			}
		});

		jQuery('#btAddCustomer').click(function () {
			jQuery('#LookupAddCustomer').modal({backdrop: 'static', keyboard: false})
			jQuery('#LookupAddCustomer').modal('show');
		})

		jQuery('#ModalProvID').change(function () {
			$.ajax({
                async   : false,
                type    : "post",
                url     : "{{route('demografipelanggan')}}",
                data    : {
                            'Table' : 'dem_kota',
                            'Field' : 'prov_id',
                            'Value' : jQuery('#ModalProvID').val(),
                            '_token': '{{ csrf_token() }}',
                        },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.data.length > 0) {
                    	$('#ModalKotaID').empty();
                    	var newOption = $('<option>', {
			            	value: -1,
			            	text: "Pilih Kota"
			          	});
			          	$('#ModalKotaID').append(newOption); 
			          	$.each(response.data,function (k,v) {
				            var newOption = $('<option>', {
				            	value: v.city_id,
				            	text: v.city_name
				        	});

				        	$('#ModalKotaID').append(newOption);
				        });
                    }
                }
            });
		});


		jQuery('#ModalKotaID').change(function () {
			// console.log('Test masuk')
			$.ajax({
                async   : false,
                type    : "post",
                url     : "{{route('demografipelanggan')}}",
                data    : {
                            'Table' : 'dem_kecamatan',
                            'Field' : 'kota_id',
                            'Value' : $('#ModalKotaID').val(),
                            '_token': '{{ csrf_token() }}',
                        },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.data.length > 0) {
                    	$('#ModalKecID').empty();
                    	var newOption = $('<option>', {
			            	value: -1,
			            	text: "Pilih Kecamatan"
			          	});
			          	$('#ModalKecID').append(newOption); 
			          	$.each(response.data,function (k,v) {
				            var newOption = $('<option>', {
				            	value: v.dis_id,
				            	text: v.dis_name
				        	});

				        	$('#ModalKecID').append(newOption);
				        });
                    }
                }
            });
		});


		jQuery('#ModalKecID').change(function () {
			// console.log('Test masuk')
			$.ajax({
                async   : false,
                type    : "post",
                url     : "{{route('demografipelanggan')}}",
                data    : {
                            'Table' : 'dem_kelurahan',
                            'Field' : 'kec_id',
                            'Value' : $('#ModalKecID').val(),
                            '_token': '{{ csrf_token() }}',
                        },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.data.length > 0) {
                    	$('#ModalKelID').empty();
                    	var newOption = $('<option>', {
			            	value: -1,
			            	text: "Pilih Kelurahan"
			          	});
			          	$('#ModalKelID').append(newOption); 
			          	$.each(response.data,function (k,v) {
				            var newOption = $('<option>', {
				            	value: v.subdis_id,
				            	text: v.subdis_name
				        	});

				        	$('#ModalKelID').append(newOption);
				        });
                    }
                }
            });
		});

		jQuery('#btSaveAddCustomer').click(function () {
			$.ajax({
                async   : false,
                type    : "post",
                url     : "{{route('pelanggan-storeJson')}}",
                headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
                data    : {
                	'NamaPelanggan' : jQuery('#ModalNamaPelanggan').val(),
                	'KodeGrupPelanggan' : jQuery('#ModalKodeGrupPelanggan').val(),
                	'LimitPiutang' : jQuery('#ModalLimitPiutang').val(),
                	'ProvID' : jQuery('#ModalProvID').val(),
                	'KotaID' : jQuery('#ModalKotaID').val(),
                	'KelID' : jQuery('#ModalKelID').val(),
                	'KecID' : jQuery('#ModalKecID').val(),
                	'Email' : jQuery('#ModalEmail').val(),
                	'NoTlp1' : jQuery('#ModalNoTlp1').val(),
                	'NoTlp2' : jQuery('#ModalNoTlp2').val(),
                	'Alamat' : jQuery('#ModalAlamat').val(),
                	'Keterangan' : jQuery('#ModalKeterangan').val(),
                	'Status' : jQuery('#ModalStatus').val()
                },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.success == true) {
                    	jQuery('#LookupAddCustomer').modal('hide');
                    	var newOption = $('<option>', {
			            	value: response.LastTRX,
			            	text: jQuery('#ModalNamaPelanggan').val()
			          	});
			          	jQuery('#KodePelanggan').append(newOption);
			          	jQuery('#KodePelanggan').val(response.LastTRX).trigger('change');
                    }
                    else{
                    	Swal.fire({
	                      icon: "error",
	                      title: "Opps...",
	                      text: response.message,
	                    });
                    }
                }
            });
		});

		$('#btOpenCustDisplay').click(function(){
			openCustomerDisplay();
		});
	});

	function LoadDraftOrderList() {
		$.ajax({
			async:false,
			url: "{{route('fpenjualan-readheader')}}",
			type: 'POST',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
            	'TglAwal':'1999-01-01',
            	'TglAkhir' : _Tanggal,
            	'KodePelanggan' : '',
            	'Status' : 'T'
           	},
            success: function(response) {
            	// console.log(response);
            	jQuery('#_draftCount').text(response.data.length);

            	if (response.data.length > 0) {
            		jQuery('#_draftOrderList').empty();
            		var xHTML = '<div class="row">';
            		$.each(response.data,function (k,v) {
            			var xNoTransaksi = "'"+v.NoTransaksi+"'";
            			xHTML += '<div class="col-lg-4">';
            			xHTML += '	<div class="pos-order">';
            			xHTML += '		<center><h4 class="pos-order-title" >'+v.NoTransaksi+'</h4></center>';
            			xHTML += '			<div class="orderdetail-pos">';
            			xHTML += '				<p><strong>Customer Name</strong> '+v.NamaPelanggan+'</p>';
            			xHTML += '				<p><strong>Payment Status</strong> Pending</p>';
            			xHTML += '				<p><strong>Total Item</strong> '+v.TotalItems+' Items</p>';
            			xHTML += '				<p><strong>Total Transaksi</strong> '+new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(v.TotalHutang).replace("Rp", "Rp. ").trim()+'</p>';
            			xHTML += '			</div>';
            			xHTML += '			<div class="d-flex justify-content-end">';
            			xHTML += '				<a class="confirm-delete ms-3" title="Edit" onClick = "editDraft('+xNoTransaksi+')"><i class="fas fa-edit"></i></a>';
            			xHTML += '				<a class="confirm-delete ms-3" title="Delete" onClick = "deleteDraft('+xNoTransaksi+')"><i class="fas fa-trash-alt"></i></a>';
            			xHTML += '			</div>';
            			xHTML += '	</div>';
            			xHTML += '</div>';
            			
            		});

            		xHTML += '</div>';

            		// console.log(xHTML);

            			jQuery('#_draftOrderList').html(xHTML);
            	}
            }
		});
	}

	function PrintStruk(NoTransaksi) {

		if(!_Company[0]["NamaPosPrinter"] || _Company[0]["NamaPosPrinter"] == "" || _Company[0]["NamaPosPrinter"] == "-1" || !_Printer || _Printer.length === 0){
			Swal.fire({
				icon: "error",
				title: "Opps...",
				text: "Printer Belum ditentukan, Silahkan melakukan setting di menu Master -> Pengaturan Toko -> Perusahaan",
			});
			return;
			return; // Hentikan eksekusi selanjutnya
		}

		if(_Company[0]["LebarKertas"] == ""){
			Swal.fire({
				icon: "error",
				title: "Opps...",
				text: "Lebar Kertas Belum ditentukan, Silahkan melakukan setting di menu Master -> Pengaturan Toko -> Perusahaan",
			});
			return;
			return; // Hentikan eksekusi
		}

		if(_Printer["PrinterInterface"] == "Bluetooth"){
			$.ajax({
				async:false,
				url: "{{route('print-retail')}}",
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
				},
				data: {'NoTransaksi':NoTransaksi},
				success: function(response) {
					if (response.success == true) {
						Swal.fire({
						icon: "success",
						title: "Sukses",
						text: "Data Penjualan Berhasil Disimpan",
						}).then((result) => {
							location.reload();
						});
					}
					else{
						Swal.fire({
							icon: "error",
							title: "Opps...",
							text: response.message,
						});
					}
				}
			});
		}
		else if(_Printer["PrinterInterface"] == "USB"){
			// var link = "fpenjualan/printthermal/"+cellInfo.data.NoTransaksi;
			let url = "{{ url('') }}";
            // url.searchParams.append('NoTransaksi', NoTransaksi);
			url += "/fpenjualan/printthermal/"+NoTransaksi;
			// console.log(url);
			// // window.location.href = url.toString();
			window.open(url, "_blank");
			location.reload();
		}
		else {
			let url = "{{ url('') }}";
			url += "/fpenjualan/printthermal/"+NoTransaksi;
			window.open(url, "_blank");
			location.reload();
		}
	}

	function GetItemInfo(KodeItem) {
		var oReturnData = {};

		$.ajax({
            async:false,
            type: 'post',
            url: "{{route('itemmaster-ViewJson')}}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
                'KodeJenis' : '',
			    'Merk' 		: '',
			    'TipeItem' 	: '',
				'Active' : 'Y',
				'KategoriPOS' : 'FNB',
				'Scan'		: KodeItem,
				'TipeItemIN' : '1,3,5'
            },
            dataType: 'json',
            success: function(response) {
            	if (response.data.length > 0) {
            		oReturnData = response.data;
            	}
            }
        });

        return oReturnData;
	}

	function CalculateDiskon(KodeItem, Qty) {
		var DiskReturn = {};

		$.ajax({
            async:false,
            type: 'post',
            url: "{{route('fpenjualan-getDiskon')}}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
                'KodeItem' : KodeItem,
                'Qty' 		: Qty
            },
            dataType: 'json',
            success: function(response) {
            	DiskReturn ={
            		Diskon : response.Diskon,
            		DiskonType : response.TipeDiskon
            	}
            }
        });

		return DiskReturn;
	}

	

	function bindGridLookupServices(data) {
		var dataGridInstance = jQuery("#gridLookupServices").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "NoUrut",
			showBorders: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true,
                pageSize: 30
            },
            editing: {
                mode: "row",
                allowUpdating: true,
                allowDeleting: true,
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            columns: [
            	{
                    dataField: "NoUrut",
                    caption: "#",
                    allowEditing:false,
                    allowSorting: false 
                },
                {
                    dataField: "KodeItem",
                    caption: "Jasa",
                    lookup: {
					    dataSource: <?php echo $itemServices ?>,
					    valueExpr: 'KodeItem',
					    displayExpr: 'NamaItem',
				    },
				    allowSorting: false,
				    allowEditing:true
                },
                {
                    dataField: "Jumlah",
                    caption: "Jumlah",
                    allowEditing:true,
                    format: { type: 'fixedPoint', precision: 2 },
                    allowSorting: false 
                },
                {
                    dataField: "Keterangan",
                    caption: "Keterangan",
                    allowEditing:true,
                    allowSorting: false 
                },
            ],
            onContentReady: function(e) {
	            var rowData = dataGridInstance.option("dataSource");
	            if (rowData.length == 1) {
	            	dataGridInstance.editRow(0)	
	            }
	        },
	        onCellClick:function (e) {
	            var rowData = dataGridInstance.option("dataSource");
	            var columnIndex = e.columnIndex;
	            // console.log(e)
	        	if (columnIndex >= 1 && columnIndex <= 5) {
	                dataGridInstance.editRow(e.rowIndex)	
	            }
	            dataGridInstance.option("focusedColumnIndex", columnIndex);	
	        },
		}).dxDataGrid('instance');

		var allRowsData  = dataGridInstance.option("dataSource");
    	var newData = { NoUrut: allRowsData.length + 1,KodeItem:"", Jumlah: 0, Keterangan:'' }
    	dataGridInstance.option("dataSource", [...dataGridInstance.option("dataSource"), newData]);
    	dataGridInstance.refresh();

    	dataGridInstance.on('rowUpdated', function(e) {
    		// console.log(e)
    		CalculateTotal();
    	});

    	dataGridInstance.on('editorPreparing',function (e) {
    		if (e.parentType === "dataRow" && e.dataField === "KodeItem") {
    			e.editorOptions.onFocusOut = (x) => {
    				var rowIndex = dataGridInstance.getRowIndexByKey(e.row.key);

    				dataGridInstance.cellValue(rowIndex, "Jumlah", 0);
		            dataGridInstance.cellValue(rowIndex, "Keterangan", '');
		            // dataGridInstance.cellValue(rowIndex, "Qty", 1);

		            dataGridInstance.refresh();

		            dataGridInstance.saveEditData();

		            var allRowsData  = dataGridInstance.option("dataSource");
                    var newData = { NoUrut: allRowsData.length + 1,KodeItem:"", Jumlah: 0, Keterangan:'' }
    				dataGridInstance.option("dataSource", [...dataGridInstance.option("dataSource"), newData]);
    				dataGridInstance.refresh();
    			}
    		}
    		else if (e.parentType === "dataRow" && e.dataField === "Jumlah") {
		    	e.editorOptions.onFocusOut = (x) => {
		    		dataGridInstance.saveEditData();
		    		CalculateTotal();
		    	}
		    }
		    else if (e.parentType === "dataRow" && e.dataField === "Keterangan") {
		    	e.editorOptions.onFocusOut = (x) => {
		    		dataGridInstance.saveEditData();
		    	}
		    }
    	})
	}

	function bindGridLookup(data) {
		// gridLookupItem
		var dataGridInstance = jQuery("#gridLookupItem").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "KodeItem",
			showBorders: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true,
                pageSize: 30
            },
            editing: {
                mode: "row",
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            selection: {
                mode: "single" // Enable single selection mode
            },
            searchPanel: {
	            visible: true,
	            width: 240,
	            placeholder: "Search..."
	        },
            columns: [
            	{
                    dataField: "KodeItem",
                    caption: "Kode Item",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "Barcode",
                    caption: "Barcode",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "NamaItem",
                    caption: "Nama Item",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "Stock",
                    caption: "Stock",
                    allowSorting: true,
                    allowEditing : false,
                    format: { type: 'fixedPoint', precision: 2 },
                },
                {
                    dataField: "Satuan",
                    caption: "Sat",
                    allowSorting: true,
                    allowEditing : false
                },
            ]
		}).dxDataGrid('instance');
	}
	function bindGrid(data) {
		return;
	}

	function bindGridLookupCustomer(data) {
		// gridLookupItem
		var dataGridInstance = jQuery("#gridLookupCustomer").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "KodePelanggan",
			showBorders: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true,
                pageSize: 30
            },
            editing: {
                mode: "row",
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            selection: {
                mode: "single" // Enable single selection mode
            },
            searchPanel: {
	            visible: true,
	            width: 240,
	            placeholder: "Search..."
	        },
            columns: [
            	{
                    dataField: "KodePelanggan",
                    caption: "Kode Pelanggan",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "NamaPelanggan",
                    caption: "Nama Pelanggan",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "NoTlpConcat",
                    caption: "No. HP",
                    allowSorting: true,
                    allowEditing : false
                },
            ]
		}).dxDataGrid('instance');
	}
	
	function SaveData(Status, ButonObject, ButtonDefaultText) {
		if (_idJenisOrder == -1) {
			Swal.fire({
				icon: "error",
				title: "Opps...",
				text: "Tipe Order Belum Dipilih!",
			});
			return;
		}

		if (_DineIn == 'Y' && (_KodeMeja == "" || _KodeMeja == -1 || !_KodeMeja)) {
			Swal.fire({
				icon: "error",
				title: "Opps...",
				text: "Nomor Meja Belum Dipilih!",
			});
			return;
		}

		UpdateCurrentTime();
		ButonObject.text('Tunggu Sebentar.....');
  		ButonObject.attr('disabled',true);

  		var NoTransaksi = "";
  		if (jQuery('#_NoTransaksi').text() != "<OTOMATIS>") {
  			NoTransaksi = jQuery('#_NoTransaksi').text();
  		}
  		
  		var oDetail = [];
		var oVariant = [];

		var rows = document.querySelectorAll('#AppendArea tr#InputSectionData');
		var rowsVariant = document.querySelectorAll('#AppendArea tr#VariantSectionData');
		var rowsAddon = document.querySelectorAll('#AppendArea tr#AddonSectionData');

		var NoUrut = 0;
		rows.forEach(function(row) {
			var totalInput = row.querySelector('input[id="txtTotal"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');
			var RowQty = row.querySelector('input[id="txtQty"]');
			var RowHarga = row.querySelector('input[id="txtHarga"]');
			var RowDiskon = row.querySelector('input[id="txtDiskon"]');

			if (totalInput) {
				var oDisk = parseFloat(RowQty.value) * parseFloat(RowHarga.value) * (parseFloat(RowDiskon.value)/ 100);
				
				var oItem = {
  					'NoUrut' : NoUrut,
					'KodeItem' : RowKodeItem.value,
					'Qty' : RowQty.value,
					'QtyKonversi' : RowQty.value,
					'Satuan' : '',
					'Harga' : RowHarga.value,
					'Discount' : oDisk,
					'HargaNet' : (RowQty.value * RowHarga.value) - oDisk,
					'BaseReff' : 'POS',
					'BaseLine' : -1,
					'KodeGudang' : _Company[0]['GudangPoS'],
					'LineStatus': Status,
					'VatPercent' : 0,
					'HargaPokokPenjualan' : 0,
  				}
  				
  				oDetail.push(oItem)
				NoUrut+=1
			}
		});

		var VariantUrut = 0;
		var AddonUrut = 0;

		rowsVariant.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			var RowVariantGrupID = row.querySelector('input[id="txtGrupVariantID"]');
			var RowVariantID = row.querySelector('input[id="txtVariantID"]');
			var RowNamaItem = row.querySelector('input[id="txtNamaItem"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');

			var oItem = {
				'NoUrut'			: VariantUrut,
				'VariantGrupID'		: RowVariantGrupID.value,
				'VariantID'			: RowVariantID.value,
				'AddonMenuID'		: -1,
				'NamaGroupVariant'	: '',
				'NamaVariant'		: RowNamaItem.value.replace('└─ ', ''),
				'ExtraQty'			: RowQty.value,
				'ExtraPrice'		: RowExtraPrice.value,
				'KodeItem'			: RowKodeItem.value
			}

			oVariant.push(oItem);
			VariantUrut += 1;
		});

		rowsAddon.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			var RowMenuAddonID = row.querySelector('input[id="txtMenuAddonID"]');
			var RowNamaItem = row.querySelector('input[id="txtNamaItem"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');

			var oItem = {
				'NoUrut'			: AddonUrut,
				'VariantGrupID'		: -1,
				'VariantID'			: -1,
				'AddonMenuID'		: RowMenuAddonID.value,
				'NamaGroupVariant'	: '',
				'NamaVariant'		: RowNamaItem.value.replace('├─ ', ''),
				'ExtraQty'			: RowQty.value,
				'ExtraPrice'		: RowExtraPrice.value,
				'KodeItem'			: RowKodeItem.value
			}

			oVariant.push(oItem);
			AddonUrut += 1;
		});

  		if (_ServicesData.length > 0) {
            var remainingGratisOngkir = _SisaGratisOngkir;
  			for (var i = 0; i < _ServicesData.length; i++) {
                var currentSvcBiaya = parseFloat(_ServicesData[i]['Jumlah']);
                if (remainingGratisOngkir > 0 && currentSvcBiaya > 0) {
                    if (remainingGratisOngkir >= currentSvcBiaya) {
                        remainingGratisOngkir -= currentSvcBiaya;
                        currentSvcBiaya = 0;
                    } else {
                        currentSvcBiaya -= remainingGratisOngkir;
                        remainingGratisOngkir = 0;
                    }
                }

  				var oItem = {
  					'NoUrut' : oDetail.length + 1,
					'KodeItem' : _ServicesData[i]['KodeItem'],
					'Qty' : 1,
					'Satuan' : '',
					'Harga' : currentSvcBiaya,
					'Discount' : 0,
					'HargaNet' : currentSvcBiaya,
					'BaseReff' : '',
					'BaseLine' : -1,
					'KodeGudang' : 'UMM',
					'LineStatus': Status,
  				}
  				
  				oDetail.push(oItem)
  			}
  		}

  		var oData = {
			'NoTransaksi' : NoTransaksi,
			'TglTransaksi' : _Tanggal + " " + _Jam,
			'TglJatuhTempo' : _Tanggal,
			'NoReff' : 'POS',
			'KodeSales' : '',
			'KodePelanggan' : jQuery('#KodePelanggan').val() ? jQuery('#KodePelanggan').val() : 'CASH',
			'KodeTermin' : _Company[0]['TerminBayarPoS'],
			'Termin' : 0,
			'TotalTransaksi' : jQuery('#_SubTotal').attr("originalvalue"),
			'Potongan' : parseFloat(jQuery('#_TotalDiskon').attr("originalvalue") || 0) + parseFloat(jQuery('#_VoucherDiscount').attr("originalvalue") || 0) + parseFloat($('#_NilaiTukarPoin').val() || 0),
			'PoinDitukar' : $('#_PoinDitukar').val() || 0,
			'Pajak' : 0,
			'Pembulatan' : (Status == 'T' ? 0 : jQuery('#_Pembulatan').attr("originalvalue")),
			'TotalPembelian' : (Status == 'T' ? (parseFloat(jQuery('#_SubTotal').attr("originalvalue") || 0) - parseFloat(jQuery('#_TotalDiskon').attr("originalvalue") || 0) - parseFloat(jQuery('#_VoucherDiscount').attr("originalvalue") || 0)) : jQuery('#_TotalNetBayar').attr("originalvalue")),
			'TotalRetur' : 0,
			'TotalPembayaran' : (Status) == 'T' ? 0 : jQuery('#JumlahBayar').attr("originalvalue"),
			'Status' : Status,
			'Keterangan' : _VoucherAppliedCode ? 'Voucher: ' + _VoucherAppliedCode : '',
			'MetodeBayar' : _KodeMetodePembayaran,
			'ReffPembayaran' : $('#NomorRefrensiPembayaran').val(),
			'JenisOrder' : _idJenisOrder,
			'KodeMeja' : _KodeMeja,
			'NyalakanLampu' : _NyalakanLampu,
			'Detail' : oDetail,
			'Variant' : oVariant
		}


		$.ajax({
			async:false,
			url: (NoTransaksi) == "" ? "{{route('fpenjualan-retailPosFnB')}}" : "{{route('fpenjualan-editJsonPosFnB')}}",
			type: 'POST',
			contentType: 'application/json',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify(oData),
            success: function(response) {
            	if (response.success == true) {
            		if(Status == 'T'){
            			Swal.fire({
	                      icon: "success",
	                      title: "Sukses",
	                      text: "Data Berhasil disimpan",
	                    }).then((result) => {
						  location.reload();
						});
            		}else{
            			let valToFormat = parseFloat(response.Kembalian); if (isNaN(valToFormat)) valToFormat = 0; let formattedAmount = "Rp. " + parseFloat(valToFormat).toLocaleString("id-ID", {minimumFractionDigits: 0, maximumFractionDigits: 0});
	            		Swal.fire({
						  title: "KEMBALIAN "+formattedAmount,
						  text: "Cetak Struk ?",
						  icon: "warning",
						  showCancelButton: true,
						  confirmButtonColor: "#3085d6",
						  cancelButtonColor: "#d33",
						  confirmButtonText: "Cetak",
						  cancelButtonText: "Tidak Cetak"
						}).then((result) => {
						  if (result.isConfirmed) {
						    PrintStruk(response.LastTRX);
						  }
						  else{
						  	location.reload();
						  }
						});
            		}
            	}
            	else{
            		Swal.fire({
                      icon: "error",
                      title: "Opps...",
                      text: response.message,
                    })
                    ButonObject.text(ButtonDefaultText);
  					ButonObject.attr('disabled',false);
            	}
            }
		});

		ButonObject.text(ButtonDefaultText);
  		ButonObject.attr('disabled',false);
	}

	function PaymentGateWay(Status, ButonObject, ButtonDefaultText) {
		UpdateCurrentTime();
		var NoTransaksi = "";
  		if (jQuery('#_NoTransaksi').text() != "<OTOMATIS>") {
  			NoTransaksi = jQuery('#_NoTransaksi').text();
  		}
  		
  		var oDetail = [];
		var oVariant = [];

		var rows = document.querySelectorAll('#AppendArea tr#InputSectionData');
		var rowsVariant = document.querySelectorAll('#AppendArea tr#VariantSectionData');
		var rowsAddon = document.querySelectorAll('#AppendArea tr#AddonSectionData');

		var NoUrut = 0;
		rows.forEach(function(row) {
			var totalInput = row.querySelector('input[id="txtTotal"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');
			var RowQty = row.querySelector('input[id="txtQty"]');
			var RowHarga = row.querySelector('input[id="txtHarga"]');
			var RowDiskon = row.querySelector('input[id="txtDiskon"]');

			if (totalInput) {
				var oDisk = parseFloat(RowQty.value) * parseFloat(RowHarga.value) * (parseFloat(RowDiskon.value)/ 100);
				
				var oItem = {
  					'NoUrut' : NoUrut,
					'KodeItem' : RowKodeItem.value,
					'Qty' : RowQty.value,
					'QtyKonversi' : RowQty.value,
					'Satuan' : '',
					'Harga' : RowHarga.value,
					'Discount' : oDisk,
					'HargaNet' : (RowQty.value * RowHarga.value) - oDisk,
					'BaseReff' : 'POS',
					'BaseLine' : -1,
					'KodeGudang' : _Company[0]['GudangPoS'],
					'LineStatus': Status,
					'VatPercent' : 0,
					'HargaPokokPenjualan' : 0,
  				}
  				
  				oDetail.push(oItem)
				NoUrut+=1
			}
		});

		var VariantUrut = 0;
		var AddonUrut = 0;

		rowsVariant.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			var RowVariantGrupID = row.querySelector('input[id="txtGrupVariantID"]');
			var RowVariantID = row.querySelector('input[id="txtVariantID"]');
			var RowNamaItem = row.querySelector('input[id="txtNamaItem"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');

			var oItem = {
				'NoUrut'			: VariantUrut,
				'VariantGrupID'		: RowVariantGrupID.value,
				'VariantID'			: RowVariantID.value,
				'AddonMenuID'		: -1,
				'NamaGroupVariant'	: '',
				'NamaVariant'		: RowNamaItem.value.replace('└─ ', ''),
				'ExtraQty'			: RowQty.value,
				'ExtraPrice'		: RowExtraPrice.value,
				'KodeItem'			: RowKodeItem.value
			}

			oVariant.push(oItem);
			VariantUrut += 1;
		});

		rowsAddon.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			var RowMenuAddonID = row.querySelector('input[id="txtMenuAddonID"]');
			var RowNamaItem = row.querySelector('input[id="txtNamaItem"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');

			var oItem = {
				'NoUrut'			: AddonUrut,
				'VariantGrupID'		: -1,
				'VariantID'			: -1,
				'AddonMenuID'		: RowMenuAddonID.value,
				'NamaGroupVariant'	: '',
				'NamaVariant'		: RowNamaItem.value.replace('├─ ', ''),
				'ExtraQty'			: RowQty.value,
				'ExtraPrice'		: RowExtraPrice.value,
				'KodeItem'			: RowKodeItem.value
			}

			oVariant.push(oItem);
			AddonUrut += 1;
		});

  		if (_ServicesData.length > 0) {
  			for (var i = 0; i < _ServicesData.length; i++) {
  				var oItem = {
  					'NoUrut' : oDetail.length + 1,
					'KodeItem' : _ServicesData[i]['KodeItem'],
					'Qty' : 1,
					'Satuan' : '',
					'Harga' : _ServicesData[i]['Jumlah'],
					'Discount' : 0,
					'HargaNet' : _ServicesData[i]['Jumlah'],
					'BaseReff' : '',
					'BaseLine' : -1,
					'KodeGudang' : 'UMM',
					'LineStatus': Status,
  				}
  				
  				oDetail.push(oItem)
  			}
  		}

  		var oData = {
			'NoTransaksi' : NoTransaksi,
			'TglTransaksi' : _Tanggal + " " + _Jam,
			'TglJatuhTempo' : _Tanggal,
			'NoReff' : 'POS',
			'KodeSales' : jQuery('#KodeSales').val(),
			'KodePelanggan' : jQuery('#KodePelanggan').val() ? jQuery('#KodePelanggan').val() : 'CASH',
			'KodeTermin' : _Company[0]['TerminBayarPoS'],
			'Termin' : 0,
			'TotalTransaksi' : jQuery('#_SubTotal').attr("originalvalue"),
			'Potongan' : jQuery('#_TotalDiskon').attr("originalvalue"),
			'Pajak' : 0,
			'Pembulatan' : jQuery('#_Pembulatan').attr("originalvalue"),
			'TotalPembelian' : jQuery('#_TotalNetBayar').attr("originalvalue"),
			'TotalRetur' : 0,
			'TotalPembayaran' : (Status) == 'T' ? 0 : jQuery('#Bayar').val(),
			'Status' : Status,
			'Keterangan' : '',
			'MetodeBayar' : _KodeMetodePembayaran,
			'ReffPembayaran' : $('#NomorRefrensiPembayaran').val(),
			'Detail' : oDetail,
			'Variant' : oVariant
		}
		
		fetch( "{{route('pembayaranpenjualan-createpayment')}}", {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			body: JSON.stringify(oData)
		})
		.then(response => response.json())
		.then(data => {
			if (data.provider == 'xendit' && data.qr_string) {
                Swal.fire({
                    title: 'Scan QRIS',
                    html: '<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(data.qr_string) + '" /><br><br><p>Tunggu hingga Pelanggan berhasil membayar.</p>',
                    showCancelButton: true,
                    confirmButtonText: 'Selesai & Tutup Transaksi',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#NomorRefrensiPembayaran').val(data.order_id);
                        SaveData(Status, ButonObject, ButtonDefaultText);
                    } else {
                        Swal.fire('Dibatalkan', 'Transaksi dibatalkan', 'error');
                    }
                });
            } else if (data.snap_token) {
                snap.pay(data.snap_token, {
					onSuccess: function(result){
						console.log(result);
						if(result.transaction_status == "cancel"){
							Swal.fire({
								icon: "error",
								title: "Opps...",
								text: "Pembayaran Dibatalkan",
							})
						}
						else{
							$('#NomorRefrensiPembayaran').val(result.order_id)
							SaveData(Status, ButonObject, ButtonDefaultText)
						}
					},
					onPending: function(result){
						console.log(result);
					},
					onError: function(result){
						Swal.fire({
							icon: "error",
							title: "Opps...",
							text: result,
						})
					},
					onClose: function(){
						console.log('customer closed the popup without finishing the payment');
					}
				});
			} else {
				Swal.fire({
					icon: "error",
					title: "Opps...",
					text: data.error,
				})
			}
		})
		.catch(error => console.error('Error:', error));
	}

	function formatCurrency(input, amount) {
		let cleanAmount = amount;
		if (typeof cleanAmount === 'string') {
			cleanAmount = cleanAmount.replace(/Rp\.?\s*/i, '');
			
			let commaCount = (cleanAmount.match(/,/g) || []).length;
			let dotCount = (cleanAmount.match(/\./g) || []).length;
			
			if (commaCount > 0 && dotCount > 0) {
				if (cleanAmount.indexOf(',') < cleanAmount.indexOf('.')) {
					cleanAmount = cleanAmount.replace(/,/g, '');
				} else {
					cleanAmount = cleanAmount.replace(/\./g, '').replace(/,/g, '.');
				}
			} else if (commaCount > 0) {
				let parts = cleanAmount.split(',');
				if (parts.length === 2 && parts[1].length <= 2) {
					cleanAmount = cleanAmount.replace(/,/g, '.');
				} else {
					cleanAmount = cleanAmount.replace(/,/g, '');
				}
			} else if (dotCount > 0) {
				let parts = cleanAmount.split('.');
				if (parts.length === 2 && parts[1].length <= 2) {
					// Standar desimal
				} else {
					cleanAmount = cleanAmount.replace(/\./g, '');
				}
			}
		}
		
		let parsedAmount = parseFloat(cleanAmount);
		if (isNaN(parsedAmount)) parsedAmount = 0;
		
		input.attr("originalvalue", parsedAmount);
		
		let valToFormat = parsedAmount; if (isNaN(valToFormat)) valToFormat = 0; let formattedAmount = "Rp. " + parseFloat(valToFormat).toLocaleString("id-ID", {minimumFractionDigits: 0, maximumFractionDigits: 0});

		input.val(formattedAmount);
	}

	function CalculateTotal() {
		var rows = document.querySelectorAll('#AppendArea tr'); // Select all rows within the AppendArea
		var grandTotal = 0;

		var rowsItem = document.querySelectorAll('#AppendArea tr#InputSectionData');
		var rowsVariant = document.querySelectorAll('#AppendArea tr#VariantSectionData');
		var rowsAddon = document.querySelectorAll('#AppendArea tr#AddonSectionData');

		var _tempTotalItem = 0;
		var _tempSubTotal = 0;
		var _tempTotalDiskon = 0;
		var _tempTotalTax = 0;
		var _tempTotalServices = 0;
		var _tempGrandTotal = 0;

		rowsItem.forEach(function(row) {
			var totalInput = row.querySelector('input[id="txtTotal"]');
			var RowQty = row.querySelector('input[id="txtQty"]');
			var RowHarga = row.querySelector('input[id="txtHarga"]');
			var RowDiskon = row.querySelector('input[id="txtDiskon"]');

			if (totalInput) {
				var qtyVal = parseFloat(RowQty.value) || 0;
				var hargaVal = parseFloat(RowHarga.value) || 0;
				var diskonPercent = parseFloat(RowDiskon.value) || 0;

				_tempTotalItem += qtyVal;
				_tempSubTotal += parseFloat(totalInput.value) || 0;
				_tempTotalDiskon += qtyVal * hargaVal * (diskonPercent / 100);
			}
		});

		rowsVariant.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			_tempSubTotal += (RowQty.value * RowExtraPrice.value);
		});

		rowsAddon.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			_tempSubTotal += (RowQty.value * RowExtraPrice.value);
		});

	    // Jasa
	    var originalTotalServices = 0;
	    for (var i = 0; i < _ServicesData.length; i++) {
	    	originalTotalServices += parseFloat(_ServicesData[i]['Jumlah']);
	    }

	    _tempTotalServices = originalTotalServices;
	    if (_SisaGratisOngkir > 0 && _tempTotalServices > 0) {
	        if (_SisaGratisOngkir >= _tempTotalServices) {
	            _tempTotalServices = 0;
	        } else {
	            _tempTotalServices -= _SisaGratisOngkir;
	        }
	    }

	    // Calculate Voucher Discount
	    var netSubtotal = _tempSubTotal - _tempTotalDiskon;
	    var _tempVoucherDiscount = 0;
	    if (_VoucherDiscountPercent > 0) {
	    	_tempVoucherDiscount = netSubtotal * (_VoucherDiscountPercent / 100);
	    	if (_VoucherMaximalDiscount > 0 && _tempVoucherDiscount > _VoucherMaximalDiscount) {
	    		_tempVoucherDiscount = _VoucherMaximalDiscount;
	    	}
	    }

	    // Update UI elements
	    $('#_TotalItem').text(_tempTotalItem);
	    formatCurrency($('#_SubTotal'), _tempSubTotal);
	    formatCurrency($('#_TotalDiskon'), _tempTotalDiskon);
	    formatCurrency($('#_VoucherDiscount'), _tempVoucherDiscount);
	    formatCurrency($('#_TotalServices'), _tempTotalServices);
		formatCurrency($('#_TotalTax'), _tempTotalTax);

		var grandTotalVal = _tempSubTotal + _tempTotalServices - _tempTotalDiskon - _tempVoucherDiscount + _tempTotalTax;
	    formatCurrency($('#_GrandTotal'), grandTotalVal);

		// Format dynamic header grand total text
		var formattedHeaderTotal = parseFloat(grandTotalVal).toLocaleString('id-ID', {
			style: 'currency',
			currency: 'IDR',
			minimumFractionDigits: 0
		});
		$('#headerGrandTotal').text(formattedHeaderTotal);

		SetEnableCommand();

		// Real-time synchronization to Customer Display (localStorage 'PoSData')
		var allRowsData = [];
		rowsItem.forEach(function(row) {
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]').value;
			var RowNamaItem = row.querySelector('input[id="txtNamaItem"]').value;
			var RowQty = parseFloat(row.querySelector('input[id="txtQty"]').value) || 0;
			var RowHarga = parseFloat(row.querySelector('input[id="txtHarga"]').value) || 0;
			var RowDiskon = parseFloat(row.querySelector('input[id="txtDiskon"]').value) || 0;
			var RowTotal = parseFloat(row.querySelector('input[id="txtTotal"]').value) || 0;

			// Get all variants and addons for this specific item code
			var namaTambahan = [];
			var rowsVariantThis = document.querySelectorAll('#AppendArea tr#VariantSectionData[class~="' + RowKodeItem + '"]');
			var rowsAddonThis = document.querySelectorAll('#AppendArea tr#AddonSectionData[class~="' + RowKodeItem + '"]');
			
			rowsVariantThis.forEach(function(r) {
				var vName = r.querySelector('input[id="txtNamaItem"]').value;
				namaTambahan.push(vName);
			});
			rowsAddonThis.forEach(function(r) {
				var aName = r.querySelector('input[id="txtNamaItem"]').value;
				namaTambahan.push(aName);
			});

			var fullNamaItem = RowNamaItem;
			if (namaTambahan.length > 0) {
				fullNamaItem += ' (' + namaTambahan.join(', ') + ')';
			}

			allRowsData.push({
				KodeItem: RowKodeItem,
				NamaItem: fullNamaItem,
				Qty: RowQty,
				Harga: RowHarga,
				Discount: RowQty * RowHarga * (RowDiskon / 100),
				Total: RowTotal
			});
		});

		var displayObject = {
			data: allRowsData,
			Total: _tempSubTotal + _tempTotalServices,
			Discount: _tempTotalDiskon,
			VoucherDiscount: _tempVoucherDiscount,
			Net: grandTotalVal,
			Tax: _tempTotalTax
		};
		updateCustomerDisplay(displayObject);
		
  		$('#_Barcode').val('');
  		$('#_Barcode').focus();
	}

	function SetEnableCommand() {
    	var ErrorCount = 0;

		if (_idJenisOrder == -1) {
			ErrorCount +=1;
		}

		if (_DineIn == 'Y' && (_KodeMeja == "" || _KodeMeja == -1 || !_KodeMeja)) {
			ErrorCount +=1;
		}

		if ($('#KodePelanggan').val() == "") {
			// ErrorCount +=1; // Allow CASH / walk-in
		}

    	if (ErrorCount >0) {
    		$('#btSimpanPembayaran').attr('disabled',true);
    	}
    	else{
    		$('#btSimpanPembayaran').attr('disabled',false);
    	}
    }
    function editDraft(NoTransaksi) {
    	jQuery('#_NoTransaksi').text(NoTransaksi);
    	jQuery('#AppendArea').empty();

    	// Load Header
    	$.ajax({
			async:false,
			url: "{{route('fpenjualan-findheader')}}",
			type: 'POST',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
            	'NoTransaksi':NoTransaksi
           	},
            success: function(response) {
            	if (response.data.length > 0) {
            		jQuery('#KodePelanggan').val(response.data[0]['KodePelanggan']).trigger('change');
            		jQuery('#KodeSales').val(response.data[0]['KodeSales']).trigger('change');
            	}
            }
		});

		// Load Detail
		$.ajax({
			async:false,
			url: "{{route('fpenjualan-readdetail')}}",
			type: 'POST',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
            	'NoTransaksi':NoTransaksi
           	},
            success: function(response) {
            	$.each(response.data, function(k, v) {
					var newRow = `
					<tr id="InputSectionData" class="align-middle" style="border-bottom: 1px solid #f1f5f9;">
						<td style="width: 45%; padding: 12px 8px;">
							<input type="text" id="txtNamaItem" name="txtNamaItem[]" class="form-control form-control-sm border-0 bg-transparent fw-medium text-slate-800 p-0" value="${v.NamaItem}" readonly style="box-shadow: none;">
							<input type="hidden" id="txtKodeItem" name="txtKodeItem[]" value="${v.KodeItem}">
						</td>
						<td style="width: 15%; padding: 12px 8px;" class="text-center">
							<div class="input-group input-group-sm justify-content-center" style="width: 90px; margin: 0 auto; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden;">
								<button type="button" class="btn btn-sm btn-light border-0 px-2" onclick="adjustQtyDirect(this, -1)" style="background: #f8fafc;"><i class="fa fa-minus text-slate-500" style="font-size: 0.75rem;"></i></button>
								<input type="text" id="txtQty" name="txtQty[]" class="form-control form-control-sm text-center border-0 p-0 fw-semibold text-slate-800" value="${v.Qty}" onchange="updateTotal(this.parentNode.parentNode.parentNode)" style="font-size: 0.85rem; box-shadow: none; min-width: 30px;">
								<button type="button" class="btn btn-sm btn-light border-0 px-2" onclick="adjustQtyDirect(this, 1)" style="background: #f8fafc;"><i class="fa fa-plus text-slate-500" style="font-size: 0.75rem;"></i></button>
							</div>
						</td>
						<td style="width: 20%; padding: 12px 8px;" class="text-end">
							<input type="text" id="txtHarga" name="txtHarga[]" class="form-control form-control-sm border-0 bg-transparent text-end p-0 text-slate-600" value="${v.Harga}" readonly style="box-shadow: none;">
						</td>
						<td style="width: 15%; padding: 12px 8px;" class="text-end">
							<input type="text" id="txtTotal" name="txtTotal[]" class="form-control form-control-sm border-0 bg-transparent text-end p-0 fw-semibold text-slate-800" value="${v.Harga * v.Qty}" readonly style="box-shadow: none;">
							<input type="hidden" id="txtDiskon" name="txtDiskon[]" value="0">
						</td>
						<td style="width: 5%; padding: 12px 8px;" class="text-center">
							<button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" onclick="deleteRow(this)" style="box-shadow: none;"><i class="fa fa-trash-alt"></i></button>
						</td>
					</tr>`;
					jQuery('#AppendArea').append(newRow);
            	});
            	CalculateTotal();
            	jQuery('#folderpop').modal('hide');
            }
		});
    }

    function editDataTransaksi(NoTransaksi, Status) {
    	$.ajax({
			async:false,
			url: "{{route('fpenjualan-editStatus')}}",
			type: 'POST',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
            	'NoTransaksi':NoTransaksi,
            	'Status' : Status
           	},
            success: function(response) {
            	if (response.success == true) {
            		Swal.fire({
                      icon: "success",
                      title: "Horray..",
                      text: "Data Berhasil Dihapus",
                    }).then((result) => {
					  location.reload();
					});
            	}
            	else{
            		Swal.fire({
                      icon: "error",
                      title: "wooopss..",
                      text: response.message,
                    });
            	}
            }
		});
    }

    function deleteDraft(NoTransaksi) {
    	jQuery('#_NoTransaksi').text(NoTransaksi)
    	// editDraft(NoTransaksi);
    	Swal.fire({
		  title: "Hapus Data Draff Penjualan",
		  text: "Hapus Draft penjualan ini ?",
		  icon: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#3085d6",
		  cancelButtonColor: "#d33",
		  confirmButtonText: "Hapus",
		  cancelButtonText: "Jangan Hapus"
		}).then((result) => {
		  if (result.isConfirmed) {
		    editDataTransaksi(NoTransaksi, 'D')
		  }
		  else{
		  	location.reload();
		  }
		});
    }

	var _custDisplayWindow = null;
	const posChannel = new BroadcastChannel('pos_display_channel');

	function updateCustomerDisplay(displayObject) {
		localStorage.setItem('PoSData', JSON.stringify(displayObject));
		try {
			posChannel.postMessage({ type: 'updateDisplay', data: displayObject });
		} catch (e) {
			console.error("BroadcastChannel error:", e);
		}
		if (window._custDisplayWindow && !window._custDisplayWindow.closed) {
			try {
				window._custDisplayWindow.postMessage({ type: 'updateDisplay', data: displayObject }, '*');
			} catch (e) {
				console.error("postMessage error:", e);
			}
		}
	}

	function openCustomerDisplay() {
		// Use Laravel's url() helper to generate the URL
		const url = "{{ url('/fpenjualan/custdisplay') }}";
		window._custDisplayWindow = window.open(url, '_blank', 'width=1390,height=800,,scrollbars=no,toolbar=no,status=no,menubar=no');
		setTimeout(() => {
			var current = JSON.parse(localStorage.getItem('PoSData') || '{}');
			updateCustomerDisplay(current);
		}, 1000);
	}

	// Toggle between Numeric Keypad and QWERTY Alpha Keypad
	function toggleKeypadMode(mode) {
		if (mode === 'NUM') {
			$('#btnToggleNum').addClass('active');
			$('#btnToggleAlpha').removeClass('active');
			$('#numpadViewContainer').removeClass('d-none');
			$('#qwertyViewContainer').addClass('d-none');
			$('#keypadIndicatorContainer').removeClass('d-none');
		} else {
			$('#btnToggleAlpha').addClass('active');
			$('#btnToggleNum').removeClass('active');
			$('#qwertyViewContainer').removeClass('d-none');
			$('#numpadViewContainer').addClass('d-none');
			$('#keypadIndicatorContainer').addClass('d-none');
			$('#_Barcode').focus();
		}
	}

	// Alphanumeric QWERTY key presses
	function pressQwerty(char) {
		var targetInput = $('#_Barcode');
		
		if ($('#_CatalogSearch').is(':focus')) {
			targetInput = $('#_CatalogSearch');
		}

		var currentVal = targetInput.val() || '';

		if (char === 'BACKSPACE') {
			targetInput.val(currentVal.substring(0, currentVal.length - 1));
			targetInput.trigger('input');
			targetInput.trigger('keyup');
		} else if (char === 'SPACE') {
			targetInput.val(currentVal + ' ');
			targetInput.trigger('input');
			targetInput.trigger('keyup');
		} else if (char === 'ENTER') {
			var e = $.Event('keypress');
			e.keyCode = 13;
			targetInput.trigger(e);
		} else {
			targetInput.val(currentVal + char.toUpperCase());
			targetInput.trigger('input');
			targetInput.trigger('keyup');
		}
	}

	function turnOffTableLight(e, btnElement, lampuId) {
		e.stopPropagation(); // Mencegah klik item meja memicu pemilihan meja
		
		Swal.fire({
			title: 'Matikan Lampu?',
			text: "Lampu meja ini akan dimatikan sekarang.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#ef4444',
			cancelButtonColor: '#64748b',
			confirmButtonText: 'Ya, Matikan!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				// Memanggil endpoint power-off secara AJAX background
				jQuery.ajax({
					url: "{{ url('titiklampu/power-off') }}/" + lampuId,
					type: 'GET',
					success: function() {
						// Update elemen UI di kasir POS secara instan agar tidak perlu refresh halaman
						let listItem = jQuery(btnElement).closest('li');
						// Ubah badge status menjadi Hijau (Konek)
						listItem.find('.rounded-circle').css({'background': 'rgba(16, 185, 129, 0.15)', 'color': '#10b981'});
						listItem.find('span.status-badge').css({'background': 'rgba(16, 185, 129, 0.15)', 'color': '#10b981'}).text('Konek (Mati)');
						// Hapus tombol Matikan
						jQuery(btnElement).remove();
						
						Swal.fire({
							title: 'Berhasil!',
							text: 'Lampu meja berhasil dimatikan secara manual.',
							icon: 'success',
							timer: 2000,
							showConfirmButton: false
						});
					},
					error: function() {
						Swal.fire('Gagal!', 'Terjadi kesalahan saat mematikan lampu meja.', 'error');
					}
				});
			}
		});
	}
</script>