<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, height=device-height, viewport-fit=cover">
    
    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    
    <title>CSC RO VIII - Integrated Management Information System</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --accent-color: #0077b6;
            --accent-hover: #005a87;
            --accent-light: rgba(0, 119, 182, 0.1);
            --accent-ultra-light: rgba(0, 119, 182, 0.05);
            --gradient-primary: linear-gradient(135deg, #0077b6 0%, #005a87 100%);
            --gradient-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-accent: linear-gradient(135deg, #0077b6 0%, #00a8e8 50%, #0077b6 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-light: 0 8px 32px rgba(0, 119, 182, 0.15);
            --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.1);
            --shadow-floating: 0 25px 70px rgba(0, 119, 182, 0.2);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-radius: 20px;
            --border-radius-large: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #0077b6 75%, #005a87 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating particles background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(0, 119, 182, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 10%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(90deg); }
            50% { transform: translateY(-40px) rotate(180deg); }
            75% { transform: translateY(-20px) rotate(270deg); }
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 1;
            opacity: 0.3;
        }

        .container-fluid {
            position: relative;
            z-index: 2;
        }

        /* Enhanced Login Card */
        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-large);
            box-shadow: var(--shadow-heavy);
            padding: 3rem;
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-accent);
            background-size: 200% 200%;
            animation: gradientMove 3s ease infinite;
            border-radius: var(--border-radius-large) var(--border-radius-large) 0 0;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-card::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: var(--gradient-accent);
            border-radius: var(--border-radius-large);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-floating);
        }

        .login-card:hover::after {
            opacity: 0.1;
        }

        /* Dark mode styles */
        .login-card-dark {
            background: rgba(15, 23, 42, 0.95);
            color: white;
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .login-card-dark::before {
            background: var(--gradient-secondary);
        }

        .login-card-dark::after {
            background: var(--gradient-secondary);
        }

        /* Logo container with enhanced animations */
        .logo-container {
            position: relative;
            margin-bottom: 2.5rem;
        }

        .logo-container img {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 8px 20px rgba(0, 119, 182, 0.2));
        }

        .logo-container img:hover {
            transform: scale(1.08) rotate(2deg);
            filter: drop-shadow(0 12px 30px rgba(0, 119, 182, 0.3));
        }

        #toggleTriggerImg {
            cursor: pointer;
            position: relative;
        }

        #toggleTriggerImg::before {
            content: 'Click to toggle options';
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--accent-color);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        #toggleTriggerImg:hover::before {
            opacity: 1;
            bottom: -25px;
        }

        /* Enhanced modern heading */
        .modern-heading {
            font-weight: 700;
            font-size: 1.25rem;
            line-height: 1.4;
            margin-bottom: 2rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 200%;
            animation: textGradient 4s ease infinite;
            text-align: center;
            position: relative;
        }

        @keyframes textGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .modern-heading-dark {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 200%;
            animation: textGradient 4s ease infinite;
        }

        /* Enhanced badges */
        .modern-badge {
            background: var(--gradient-accent);
            background-size: 200% 200%;
            animation: badgeGradient 3s ease infinite;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: var(--shadow-light);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        @keyframes badgeGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .modern-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .modern-badge:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 119, 182, 0.35);
        }

        .modern-badge:hover::before {
            left: 100%;
        }

        .modern-badge-dark {
            background: var(--gradient-secondary);
            background-size: 200% 200%;
            animation: badgeGradient 3s ease infinite;
        }

        /* Enhanced input groups */
        .modern-input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .modern-input-group .input-group {
            position: relative;
            overflow: hidden;
            border-radius: var(--border-radius);
        }

        .modern-input-group .input-group-text {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            padding: 1rem 1.2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .modern-input-group .form-control {
            border: 2px solid #e2e8f0;
            border-left: none;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            position: relative;
        }

        .modern-input-group .form-control::placeholder {
            color: var(--text-secondary);
            font-weight: 400;
        }

        .modern-input-group .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.3rem rgba(0, 119, 182, 0.2);
            transform: translateY(-2px);
            background: white;
        }

        .modern-input-group .form-control:focus ~ .input-group-text,
        .modern-input-group:focus-within .input-group-text {
            border-color: var(--accent-color);
            background: var(--accent-light);
            transform: translateY(-2px);
        }

        /* Dark mode input styles */
        .modern-input-group-dark .input-group-text {
            background: linear-gradient(135deg, rgba(51, 65, 85, 0.9) 0%, rgba(30, 41, 59, 0.9) 100%);
            border-color: rgba(148, 163, 184, 0.3);
            color: white;
        }

        .modern-input-group-dark .form-control {
            background: linear-gradient(135deg, rgba(51, 65, 85, 0.9) 0%, rgba(30, 41, 59, 0.9) 100%);
            border-color: rgba(148, 163, 184, 0.3);
            color: white;
        }

        .modern-input-group-dark .form-control::placeholder {
            color: rgba(203, 213, 225, 0.6);
        }

        .modern-input-group-dark .form-control:focus {
            background: rgba(51, 65, 85, 0.95);
            border-color: #8b5cf6;
            box-shadow: 0 0 0 0.3rem rgba(139, 92, 246, 0.2);
        }

        .modern-input-group-dark .form-floating > label {
            color: rgba(203, 213, 225, 0.8);
        }

        .modern-input-group-dark .form-floating > .form-control:focus ~ label,
        .modern-input-group-dark .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: rgba(203, 213, 225, 0.95) !important;
        }

        .modern-input-group-dark .form-floating > .form-control:focus ~ label {
            color: #a78bfa !important;
        }

        /* Enhanced password toggle */
        .password-toggle {
            position: absolute;
            right: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
            padding: 0.5rem;
            border-radius: 50%;
        }

        .password-toggle:hover {
            color: var(--accent-color);
            background: var(--accent-ultra-light);
            transform: translateY(-50%) scale(1.2);
        }

        /* Enhanced CAPTCHA section */
        .captcha-section {
            background: linear-gradient(135deg, var(--accent-ultra-light) 0%, rgba(0, 119, 182, 0.08) 100%);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 119, 182, 0.15);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .captcha-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 119, 182, 0.05), transparent);
            transition: left 2s;
        }

        .captcha-section:hover::before {
            left: 100%;
        }

        .captcha-section-dark {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(139, 92, 246, 0.15) 100%);
            border-color: rgba(139, 92, 246, 0.2);
        }

        .captcha-display {
            background: var(--gradient-accent);
            background-size: 200% 200%;
            animation: captchaGradient 4s ease infinite;
            color: white;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: 3px;
            box-shadow: var(--shadow-light);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        @keyframes captchaGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .captcha-display:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(0, 119, 182, 0.4);
        }

        .captcha-display-dark {
            background: var(--gradient-secondary);
            background-size: 200% 200%;
            animation: captchaGradient 4s ease infinite;
        }

        .captcha-refresh {
            cursor: pointer;
            font-size: 1.3rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .captcha-refresh:hover {
            transform: rotate(360deg) scale(1.2);
            background: rgba(255, 255, 255, 0.2);
        }

        /* Enhanced modern button */
        .modern-btn {
            background: var(--gradient-accent);
            background-size: 200% 200%;
            animation: buttonGradient 3s ease infinite;
            border: none;
            border-radius: var(--border-radius);
            padding: 1.2rem 2.5rem;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: var(--shadow-light);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        @keyframes buttonGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .modern-btn:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0, 119, 182, 0.4);
            background: var(--gradient-accent);
        }

        .modern-btn:hover::before {
            left: 100%;
        }

        .modern-btn:active {
            transform: translateY(-2px) scale(1.02);
        }

        .modern-btn-dark {
            background: var(--gradient-secondary);
            background-size: 200% 200%;
            animation: buttonGradient 3s ease infinite;
        }

        .modern-btn-dark:hover {
            background: var(--gradient-secondary);
            box-shadow: 0 20px 50px rgba(139, 92, 246, 0.4);
        }

        /* Enhanced toggle container */
        #toggleContainer {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1.5rem;
            transform: translateY(-10px);
        }

        #toggleContainer.show {
            opacity: 1;
            max-height: 80px;
            transform: translateY(0);
        }

        .modern-switch {
            background: linear-gradient(135deg, var(--accent-ultra-light) 0%, rgba(0, 119, 182, 0.1) 100%);
            border: 1px solid rgba(0, 119, 182, 0.2);
            border-radius: 15px;
            padding: 1.2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .modern-switch::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 119, 182, 0.05), transparent);
            transition: left 1.5s;
        }

        .modern-switch:hover::before {
            left: 100%;
        }

        .modern-switch-dark {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(139, 92, 246, 0.15) 100%);
            border-color: rgba(139, 92, 246, 0.2);
        }

        .form-check-input {
            width: 3rem;
            height: 1.5rem;
            border-radius: 2rem;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 119, 182, 0.25);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 119, 182, 0.25);
        }

        /* Enhanced forgot password link */
        .forgot-link {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            padding: 0.5rem 0;
        }

        .forgot-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0.25rem;
            left: 0;
            background: var(--gradient-accent);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2px;
        }

        .forgot-link:hover {
            color: var(--accent-hover);
            transform: translateX(4px);
        }

        .forgot-link:hover::after {
            width: 100%;
        }

        .forgot-link-dark {
            color: #a78bfa;
        }

        .forgot-link-dark:hover {
            color: #8b5cf6;
        }

        /* Enhanced animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-card {
            animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Staggered animation for form elements */
        .modern-input-group:nth-child(1) { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.1s both; }
        .modern-input-group:nth-child(2) { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.2s both; }
        .captcha-section { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.3s both; }
        .modern-btn { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.4s both; }

        /* Loading state */
        .loading .modern-btn {
            pointer-events: none;
            opacity: 0.8;
        }

        .spinner-border-sm {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive enhancements */
        @media (max-width: 768px) {
            .login-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: var(--border-radius);
            }
            
            .modern-heading {
                font-size: 1.1rem;
                line-height: 1.3;
            }
            
            .modern-badge {
                font-size: 0.7rem;
                padding: 0.6rem 1.2rem;
            }
            
            .form-control, .input-group-text {
                padding: 0.875rem 1rem;
                font-size: 0.95rem;
            }
            
            .modern-btn {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem 1rem;
            }
            
            .captcha-section {
                padding: 1rem;
            }
            
            .captcha-display {
                font-size: 1rem;
                letter-spacing: 2px;
            }
        }

        /* Custom SweetAlert2 styles */
        .swal2-popup {
            border-radius: var(--border-radius) !important;
            box-shadow: var(--shadow-heavy) !important;
        }

        .swal2-popup-idle {
            border-left: 4px solid var(--accent-color) !important;
        }

        .swal2-popup-idle .swal2-icon.swal2-info {
            border-color: var(--accent-color) !important;
            color: var(--accent-color) !important;
        }

        .swal2-confirm {
            background: var(--gradient-accent) !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 0.75rem 1.5rem !important;
            font-family: 'Poppins', sans-serif !important;
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Focus indicators */
        .modern-btn:focus,
        .form-control:focus,
        .form-check-input:focus {
            outline: 2px solid var(--accent-color);
            outline-offset: 2px;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .login-card {
                border: 2px solid var(--accent-color);
            }
            
            .modern-btn {
                border: 2px solid white;
            }
        }
    </style>
</head>
<body class="my-login-page overflow-hidden">
    <img class="wave d-none d-md-block" src="login_img/wave.png">

    <div class="container-fluid">
        <div class="row min-vh-100">
            
            <!-- Left Image Section (Hidden on mobile) -->
            <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
                <img src="login_img/bg.png" class="img-fluid" alt="CSC RO VIII Background" style="max-height: 450px; object-fit: contain;">
            </div>

            <!-- Login Form Section -->
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center px-3">
                <div class="login-card w-100" style="max-width: 500px;" id="loginCard">
                    <form class="w-100">
                        <input type="hidden" id="loginMode" name="login_mode" value="admin">
                        
                        <div class="text-center logo-container">
                            <img src="login_img/csclogo.png" class="img-fluid mb-3" style="height: 80px;" alt="CSC Logo">
                            <img src="login_img/CSC-IMIS.png" id="toggleTriggerImg" class="img-fluid mb-4" style="height: 80px; cursor: pointer;" alt="CSC IMIS Logo">
                            
                            <h5 id="welcomeText" class="modern-heading">
                                Welcome to CSC RO VIII - Integrated Management Information System
                            </h5>
                            
                            <div class="mb-4">
                                <span id="loginModeBadge" class="modern-badge">
                                    LOGIN AS CSC RO VIII EMPLOYEE
                                </span>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="modern-input-group" id="usernameGroup">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person-circle text-secondary"></i>
                                </span>
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control" id="username" placeholder="Username" required autofocus>
                                    <label for="username">Username</label>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="modern-input-group" id="passwordGroup">
                            <div class="input-group position-relative">
                                <span class="input-group-text">
                                    <i class="bi bi-shield-lock text-secondary"></i>
                                </span>
                                <div class="form-floating flex-grow-1">
                                    <input type="password" class="form-control pe-5" id="password" placeholder="Password" required autocomplete="current-password">
                                    <label for="password">Password</label>
                                    <i class="bi bi-eye-slash password-toggle" id="togglePassword" title="Toggle password visibility"></i>
                                </div>
                            </div>
                        </div>

                        <!-- CAPTCHA -->
                        <div class="captcha-section" id="captchaSection">
                            <div class="text-center mb-3">
                                <span class="modern-badge" id="captchaBadge">
                                    <i class="bi bi-shield-check me-2"></i>ENTER CAPTCHA TO PROCEED
                                </span>
                            </div>
                            
                            <div class="d-flex gap-3 align-items-start">
                                <!-- CAPTCHA Input -->
                                <div class="flex-grow-1">
                                    <div class="modern-input-group mb-0" id="captchaInputGroup">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-123 text-secondary"></i>
                                            </span>
                                            <input type="number" class="form-control" id="captcha" placeholder="Enter CAPTCHA" required maxlength="4">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- CAPTCHA Display -->
                                <div class="captcha-display d-flex align-items-center justify-content-between" style="min-width: 130px; height: 58px;" id="generatedCaptcha">
                                    <span id="captchaValue" class="flex-grow-1 text-center">1234</span>
                                    <i class="bi bi-arrow-clockwise captcha-refresh ms-2" id="refreshCaptcha" title="Refresh CAPTCHA"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="modern-btn w-100 text-white mb-3" id="loginBtn">
                            <span id="btnText">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Access System
                            </span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </button>

                        <!-- Toggle Container -->
                        <div id="toggleContainer">
                            <div class="modern-switch d-flex justify-content-between align-items-center" id="toggleSwitch">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-gear-fill me-2 text-secondary"></i>
                                    <label class="form-check-label mb-0" for="toggleModeBtn" style="font-size: 0.9rem; font-weight: 500;">
                                        Switch to Superadmin
                                    </label>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="toggleModeBtn">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // -------------------------
            // Enhanced Password Toggle
            // -------------------------
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');

            if (togglePasswordBtn && passwordField) {
                togglePasswordBtn.addEventListener('click', function () {
                    const isPassword = passwordField.type === 'password';
                    passwordField.type = isPassword ? 'text' : 'password';
                    this.classList.toggle('bi-eye-slash');
                    this.classList.toggle('bi-eye');
                    
                    // Add subtle animation feedback
                    this.style.transform = 'translateY(-50%) scale(0.8)';
                    setTimeout(() => {
                        this.style.transform = 'translateY(-50%) scale(1)';
                    }, 150);
                });
            }

            // -----------------------------
            // Enhanced Toggle Login Mode
            // -----------------------------
            $('#toggleModeBtn').on('change', function () {
                const isSuperadmin = $(this).is(':checked');
                const $card = $('#loginCard');
                const $badge = $('#loginModeBadge');
                const $captchaBadge = $('#captchaBadge');
                const $modeInput = $('#loginMode');
                const $toggleLabel = $('label[for="toggleModeBtn"]');
                const $heading = $('#welcomeText');
                const $toggleImg = $('#toggleTriggerImg');
                const $generatedCaptcha = $('#generatedCaptcha');
                const $captchaSection = $('#captchaSection');
                const $toggleSwitch = $('#toggleSwitch');
                const $loginBtn = $('#loginBtn');
                const $inputGroups = $('.modern-input-group');

                // Add transition effect
                $card.css('transform', 'scale(0.98)');
                
                setTimeout(() => {
                    if (isSuperadmin) {
                        // Switch to dark mode (superadmin)
                        $card.addClass('login-card-dark');
                        $badge.addClass('modern-badge-dark').html('<i class="bi bi-person-fill-gear me-2"></i>LOGIN AS CSC RO VIII SUPERADMIN');
                        $captchaBadge.addClass('modern-badge-dark');
                        $heading.addClass('modern-heading-dark');
                        $toggleImg.attr('src', 'login_img/CSC-IMIS-dark.png');
                        $generatedCaptcha.addClass('captcha-display-dark');
                        $captchaSection.addClass('captcha-section-dark');
                        $toggleSwitch.addClass('modern-switch-dark');
                        $loginBtn.addClass('modern-btn-dark');
                        $inputGroups.addClass('modern-input-group-dark');
                        
                        $modeInput.val('superadmin');
                        $toggleLabel.text('Switch Back to Employee');
                        
                    } else {
                        // Switch back to light mode (employee)
                        $card.removeClass('login-card-dark');
                        $badge.removeClass('modern-badge-dark').html('<i class="bi bi-person-circle me-2"></i>LOGIN AS CSC RO VIII EMPLOYEE');
                        $captchaBadge.removeClass('modern-badge-dark');
                        $heading.removeClass('modern-heading-dark');
                        $toggleImg.attr('src', 'login_img/CSC-IMIS.png');
                        $generatedCaptcha.removeClass('captcha-display-dark');
                        $captchaSection.removeClass('captcha-section-dark');
                        $toggleSwitch.removeClass('modern-switch-dark');
                        $loginBtn.removeClass('modern-btn-dark');
                        $inputGroups.removeClass('modern-input-group-dark');
                        
                        $modeInput.val('admin');
                        $toggleLabel.text('Switch to Superadmin');
                    }
                    
                    $card.css('transform', 'scale(1)');
                }, 200);
            });

            // ------------------------
            // Enhanced Toggle Container
            // ------------------------
            const toggleImg = document.getElementById("toggleTriggerImg");
            const toggleContainer = document.getElementById("toggleContainer");

            if (toggleImg && toggleContainer) {
                toggleImg.addEventListener("click", function () {
                    toggleContainer.classList.toggle("show");
                    
                    // Add click animation
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            }

            // -----------------------------
            // Enhanced CAPTCHA Generation
            // -----------------------------
            function generateCaptcha() {
                const captcha = Math.floor(1000 + Math.random() * 9000);
                const captchaElement = document.getElementById('captchaValue');
                
                // Add generation animation
                captchaElement.style.transform = 'scale(0.8)';
                captchaElement.style.opacity = '0.5';
                
                setTimeout(() => {
                    captchaElement.textContent = captcha;
                    captchaElement.style.transform = 'scale(1)';
                    captchaElement.style.opacity = '1';
                }, 200);
            }

            // Enhanced refresh CAPTCHA with animation
            document.getElementById('refreshCaptcha').addEventListener('click', function() {
                this.style.animation = 'none';
                setTimeout(() => {
                    this.style.animation = '';
                    generateCaptcha();
                }, 10);
            });

            // -----------------------------
            // Enhanced Form Submission
            // -----------------------------
            $('form').on('submit', function(e) {
                e.preventDefault();
                
                const $btn = $('#loginBtn');
                const $btnText = $('#btnText');
                const $btnSpinner = $('#btnSpinner');
                
                // Add loading state
                $btn.addClass('loading');
                $btnText.addClass('d-none');
                $btnSpinner.removeClass('d-none');
                $btn.prop('disabled', true);
                
                // Simulate authentication (replace with actual auth logic)
                setTimeout(() => {
                    $btn.removeClass('loading');
                    $btnText.removeClass('d-none');
                    $btnSpinner.addClass('d-none');
                    $btn.prop('disabled', false);
                    
                    // Add success animation or redirect
                    // This is where you'd integrate with your auth.js
                }, 2000);
            });

            // -----------------------------
            // Enhanced Input Focus Effects
            // -----------------------------
            $('.form-control').on('focus', function() {
                $(this).closest('.modern-input-group').addClass('focused');
            }).on('blur', function() {
                $(this).closest('.modern-input-group').removeClass('focused');
            });

            // -----------------------------
            // Enhanced Logout Alerts
            // -----------------------------
            const urlParams = new URLSearchParams(window.location.search);
            const logoutReason = urlParams.get('logout');
            const usernameInput = document.getElementById('username');

            const clearLogoutParam = () => {
                const url = new URL(window.location);
                url.searchParams.delete('logout');
                window.history.replaceState({}, document.title, url.toString());
            };

            const focusUsername = () => {
                setTimeout(() => {
                    usernameInput?.focus();
                }, 300);
            };

            if (logoutReason === 'idle') {
                Swal.fire({
                    title: 'Session Expired',
                    html: '<div style="font-family: Poppins, sans-serif;"><i class="bi bi-clock-history" style="font-size: 2rem; color: #0077b6; margin-bottom: 1rem;"></i><br>You have been automatically logged out due to inactivity.<br><br><strong>Please log in again to continue accessing the system.</strong></div>',
                    icon: 'info',
                    confirmButtonText: '<i class="bi bi-box-arrow-in-right"></i> Continue to Login',
                    allowOutsideClick: false,
                    customClass: {
                        popup: 'swal2-popup-idle',
                        confirmButton: 'swal2-confirm'
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInUp animate__faster'
                    }
                }).then(() => {
                    clearLogoutParam();
                    focusUsername();
                });

            } else if (logoutReason === 'manual') {
                Swal.fire({
                    title: 'Logged Out Successfully',
                    html: '<div style="font-family: Poppins, sans-serif;"><i class="bi bi-check-circle" style="font-size: 2rem; color: #28a745; margin-bottom: 1rem;"></i><br><strong>You have been successfully logged out.</strong><br>Thank you for using CSC RO VIII IMIS.</div>',
                    icon: 'success',
                    confirmButtonText: '<i class="bi bi-box-arrow-in-right"></i> New Login',
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        confirmButton: 'swal2-confirm'
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInUp animate__faster'
                    }
                }).then(() => {
                    clearLogoutParam();
                    focusUsername();
                });

            } else {
                focusUsername();
            }

            // Generate initial CAPTCHA with animation
            setTimeout(generateCaptcha, 500);

            // -----------------------------
            // Enhanced Accessibility
            // -----------------------------
            
            // Keyboard navigation for custom elements
            $('.captcha-refresh').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).click();
                }
            });

            $('#toggleTriggerImg').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).click();
                }
            });

            // Add proper ARIA labels
            $('#toggleTriggerImg').attr('aria-label', 'Click to show login options');
            $('#refreshCaptcha').attr('aria-label', 'Refresh CAPTCHA code');
            $('#togglePassword').attr('aria-label', 'Toggle password visibility');

            // -----------------------------
            // Performance Optimizations
            // -----------------------------
            
            // Debounced resize handler
            let resizeTimeout;
            $(window).on('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    // Handle responsive adjustments if needed
                }, 250);
            });

            // Preload images for smoother transitions
            const imagePreloader = new Image();
            imagePreloader.src = 'login_img/CSC-IMIS-dark.png';
        });
    </script>
</body>
</html>