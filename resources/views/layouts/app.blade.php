<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - DriveEase Rentals</title>
    
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        /* === VARIABLES === */
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        
        /* === BASE STYLES === */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            padding-top: 20px;
        }
        
        .container {
            max-width: 100%;
        }
        
        /* === NAVBAR === */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 30px;
            padding: 10px 0;
        }
        
        .navbar-brand {
            color: var(--primary) !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-brand i {
            color: var(--secondary);
        }
        
        .nav-link {
            color: var(--primary) !important;
            font-weight: 500;
            padding: 8px 15px !important;
            border-radius: 6px;
            margin: 0 3px;
            transition: all 0.3s;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background-color: var(--secondary);
            color: white !important;
        }
        
        .nav-link i {
            margin-right: 5px;
        }
        
        /* === CARDS === */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background-color: var(--primary);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
            border: none;
        }

        .card-header2 {
            background-color: white;
            color: var(--primary);
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
            border: none;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* === STATS CARDS === */
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }
        
        .stats-icon.primary { background: linear-gradient(135deg, var(--primary), #3a5068); }
        .stats-icon.success { background: linear-gradient(135deg, var(--success), #2ecc71); }
        .stats-icon.warning { background: linear-gradient(135deg, var(--warning), #f1c40f); }
        .stats-icon.danger { background: linear-gradient(135deg, var(--danger), #c0392b); }
        
        .stats-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .stats-label {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }
        
        /* === TABLES === */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: var(--primary);
            padding: 12px 15px;
        }
        
        .table tbody tr {
            transition: background-color 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        /* === BUTTONS === */
        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary), #2980b9);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success), #229954);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning), #d68910);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #b03a2e);
        }
        
        .btn i {
            margin-right: 5px;
        }
        
        /* === BADGES === */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
        }
        
        .badge.bg-success { background-color: var(--success) !important; }
        .badge.bg-warning { background-color: var(--warning) !important; }
        .badge.bg-danger { background-color: var(--danger) !important; }
        .badge.bg-info { background-color: var(--secondary) !important; }
        
        /* === FORMS === */
        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 8px;
        }
        
        /* === ALERTS === */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        /* === PAGINATION === */
        .pagination .page-link {
            border-radius: 6px;
            margin: 0 3px;
            border: 1px solid #dee2e6;
            color: var(--primary);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        /* === FOOTER === */
        .footer {
            background-color: var(--primary);
            color: white;
            padding: 30px 0;
            margin-top: 40px;
            border-radius: 10px 10px 0 0;
        }
        
        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer a:hover {
            color: white;
        }
        
        .footer h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        /* === UTILITY CLASSES === */
        .page-title {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--secondary);
            display: inline-block;
        }
        
        .section-title {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }
        
        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            body {
                padding-top: 10px;
            }
            
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .stats-card {
                margin-bottom: 15px;
            }
            
            .table-responsive {
                font-size: 14px;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>

    <div class="container">
        <!-- Content -->
         
        <main>
            @include('partials.navbar')
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </main>

    </div>


    <!-- Scripts -->
     @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @yield('scripts')
</body>
</html>