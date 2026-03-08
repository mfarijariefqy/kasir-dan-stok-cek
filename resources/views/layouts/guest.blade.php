<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Cerita Coffee') }}</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1A0F07 0%, #3E2723 50%, #6F4E37 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Animated background dots */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(111,78,55,0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,193,7,0.1) 0%, transparent 40%),
                radial-gradient(circle at 60% 80%, rgba(62,39,35,0.5) 0%, transparent 50%);
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        /* Logo/Brand */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
            color: #fff;
            animation: fadeInDown 0.5s ease-out;
        }
        .brand-header .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #FF8F00, #FFC107);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #3E2723;
            margin-bottom: 14px;
            box-shadow: 0 8px 25px rgba(255,143,0,0.4);
        }
        .brand-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .brand-header p {
            font-size: 0.83rem;
            color: rgba(255,255,255,0.55);
            margin-top: 4px;
        }

        /* Card */
        .login-card {
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            padding: 36px 36px 32px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
            animation: fadeInUp 0.5s ease-out;
        }

        .login-card h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #3E2723;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 7px;
        }

        .form-group .input-wrapper {
            position: relative;
        }

        .form-group .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #A1887F;
            font-size: 0.9rem;
        }

        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="text"] {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid #E0D9D0;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #333;
            background: #FAFAF8;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: #6F4E37;
            box-shadow: 0 0 0 3px rgba(111,78,55,0.12);
            background: #fff;
        }

        .form-group .error-msg {
            color: #C62828;
            font-size: 0.78rem;
            margin-top: 5px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #6F4E37;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 0.83rem;
            color: #666;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #6F4E37, #A1887F);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            letter-spacing: 0.3px;
            box-shadow: 0 5px 18px rgba(111,78,55,0.4);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #3E2723, #6F4E37);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(111,78,55,0.5);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            font-size: 0.82rem;
            color: #A1887F;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #6F4E37;
        }

        .session-status {
            background: #E8F5E9;
            color: #2E7D32;
            border-left: 4px solid #43A047;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.83rem;
            margin-bottom: 18px;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .copyright {
            text-align: center;
            margin-top: 20px;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Brand Header -->
        <div class="brand-header">
            <div class="brand-icon">
                <i class="fas fa-mug-hot"></i>
            </div>
            <h1>Cerita Coffee</h1>
            <p>Sistem Kasir & Manajemen Stok</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <h2><i class="fas fa-sign-in-alt mr-2" style="color:#6F4E37;"></i>Masuk ke Sistem</h2>
            {{ $slot }}
        </div>

        <div class="copyright">
            &copy; {{ date('Y') }} Cerita Coffee &bull; All rights reserved
        </div>
    </div>
</body>
</html>
