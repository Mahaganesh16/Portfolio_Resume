<?php
session_start();
if(isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Shreetech Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
    <style>
        :root {
            --primary: #FD0155;
            --primary-dark: #d00146;
            --dark: #0f172a;
            --glass: rgba(255, 255, 255, 0.95);
        }
        body {
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            overflow: hidden;
        }
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            z-index: 10;
        }
        .login-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            padding: 50px 40px;
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transform: translateY(0);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6);
        }
        .logo-box {
            width: 80px;
            height: 80px;
            background: var(--primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 20px rgba(253, 1, 85, 0.3);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 10px 20px rgba(253, 1, 85, 0.3); }
            50% { transform: scale(1.05); box-shadow: 0 15px 30px rgba(253, 1, 85, 0.5); }
            100% { transform: scale(1); box-shadow: 0 10px 20px rgba(253, 1, 85, 0.3); }
        }
        .logo-box i {
            font-size: 35px;
            color: white;
        }
        h2 {
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        p.subtitle {
            color: #64748b;
            margin-bottom: 35px;
            font-size: 15px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
            color: #475569;
        }
        .input-group {
            position: relative;
        }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: 0.3s;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e2e8f0;
            background: #f8fafc;
            font-weight: 500;
            transition: all 0.3s;
            height: 50px;
        }
        .form-control:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(253, 1, 85, 0.1);
        }
        .form-control:focus + i {
            color: var(--primary);
        }
        .btn-login {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            font-weight: 700;
            font-size: 16px;
            margin-top: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-login:hover {
            background: var(--primary-dark);
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(253, 1, 85, 0.2);
        }
        .error-msg {
            background: #fff1f2;
            color: #e11d48;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 600;
            border-left: 4px solid #e11d48;
        }
        .bg-circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }
        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary), #ff4d8d);
            opacity: 0.1;
            filter: blur(60px);
        }
        .circle-1 { width: 400px; height: 400px; top: -100px; right: -100px; }
        .circle-2 { width: 300px; height: 300px; bottom: -50px; left: -50px; }
    </style>
</head>
<body>
    <div class="bg-circles">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-box">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2>Welcome Back</h2>
            <p class="subtitle">Secure admin access to your portfolio</p>

            <?php if(isset($_GET['error'])): ?>
                <div class="error-msg">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> Invalid credentials. Please try again.
                </div>
            <?php endif; ?>

            <form action="login_action" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" placeholder="Enter username" required autofocus>
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>
                <button type="submit" class="btn-login">
                    <span>Login to Dashboard</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
