<?php
require_once 'config/database.php';
require_once 'process/auth/Auth.php';
session_start();

$db = (new Database())->getConnection();
$auth = new Auth($db);
$error = '';

if (isset($_POST['submit'])) {
    $username = preg_replace('/\s+/', ' ', trim((string) ($_POST['username'] ?? ''))) ?? '';
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = trim((string) ($_POST['password'] ?? ''));

    if ($auth->register($username, $email, $password)) {
        $_SESSION['username'] = $username;
        header('Location: login.php');
        exit;
    } else {
        $error = $auth->getLastError();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | HayFarm</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: url('public/images/bg_login.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 48px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
        }

        .form-card h4 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 6px;
        }

        .form-card .subtitle {
            font-size: 0.92rem;
            color: #666;
            text-align: center;
            margin-bottom: 28px;
        }

        .alert-danger {
            background: #fde8e8;
            color: #c0392b;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.88rem;
            margin-bottom: 18px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            height: 48px;
            background: #f1f9f4;
            border: 1.5px solid transparent;
            border-radius: 10px;
            padding: 0 16px;
            font-size: 0.95rem;
            font-family: 'Nunito', sans-serif;
            color: #333;
            transition: border-color 0.2s;
            outline: none;
        }

        .form-group input.error {
            border-color: #dc3545;
            background: #fff5f5;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('public/images/bg_login.png') no-repeat center center fixed;
            background-size: cover;
        }

        section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
            color: #fff;
        }

        .form-box {
            position: relative;
            width: 630px;
            height: 450px;
            background-color: rgb(255, 255, 255);
            border: 2px solid rgba(0, 0, 0, 0.42);
            border-radius: 20px;
            backdrop-filter: blur(15px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h4 {
            font-family: 'Nunito', sans-serif;
            font-size: 1.5rem;
            color: black;
            text-align: center;
            margin-bottom: 2%;
            margin-top: 5%;
            text-shadow: 9%;
        }

        p {
            font-family: 'Nunito', sans-serif;
            font-size: 0.9rem;
            color: black;
            text-align: center;
            margin-bottom: 8%;
            text-shadow: 9%;
        }

        .form-container {
            background: rgb(255, 255, 255);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 1030px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
            margin: 0 auto;
        }

        .form-box {
            position: relative;
            margin: 20px 0;
            width: 630px;
            border-bottom: 2px solid #fff;
        }

        .form-box label {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            transition: all .5s ease-in-out;
            color: #fafafa;
            font-size: 1rem;
            pointer-events: none;
        }

        .form-control {
            position: relative;
            /* margin: 5px; */
            width: 500px;
            height: 46px;
            background: rgba(241, 249, 244, 1);
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Nunito', sans-serif;
            font-weight: semibold;
        }

        .form-control.error {
            border: 2px solid #dc3545;
            background: #fff5f5;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.82rem;
            margin-top: 4px;
            min-height: 16px;
        }

        .form-group label {
            color: #000000;
            font-weight: semibold;
            font-family: 'Nunito', sans-serif;
            justify-content: left;
            align-items: start;
        }

        input:focus~label,
        input:valid~label {
            top: -5px;
        }

        .inputbox input {
            width: 350px;
            height: 60px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1rem;
            padding: 0 35px 0 5px;
            color: #fff;
        }

        button {
            width: 100%;
            height: 46px;
            border-radius: 8px;
            background: rgba(25, 108, 51, 1);
            border: none;
            outline: none;
            cursor: pointer;
            font-size: 20px;
            color: #fff;
        }

        .form-footer {
            font-size: .9rem;
            color: #009b48;
            text-align: center;
            margin: 25px 0 10px;
        }

        .form-footer p a {
            text-decoration: none;
            color: #009b48;
            font-weight: 600;
        }

        .form-footer p a:hover {
            text-decoration: underline;
        }


        .btn-daftar:hover {
            background: #145a2a;
        }

        .btn-daftar:active {
            transform: scale(0.99);
        }

        .form-footer {
            text-align: center;
            margin-top: 18px;
            font-size: 0.9rem;
            color: #555;
        }

        .form-footer a {
            color: #196c33;
            font-weight: 700;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            padding-right: 50px;
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
            color: #666;
            user-select: none;
        }

        .toggle-password:hover {
            background: rgba(0, 0, 0, 0.05);
            color: #333;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
        }

        /* ✅ ALERT ERROR - Tema Hay Farm */
        .error-alert {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #86efac;
            border-left: 4px solid #22c55e;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
            animation: slideInError 0.3s ease-out;
        }

        .error-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
            color: #166534;
        }

        .error-content {
            flex: 1;
        }

        .error-title {
            font-family: 'Nunito', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #166534;
            margin-bottom: 4px;
        }

        .error-message {
            font-family: 'Nunito', sans-serif;
            font-size: 0.88rem;
            color: #374151;
            line-height: 1.4;
        }

        @keyframes slideInError {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ✅ INPUT ERROR STATE */
        .form-control.error,
        .form-group input.error {
            border-color: #f87171 !important;
            background: #fff5f5 !important;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1) !important;
        }

        .error-message-inline {
            color: #dc2626;
            font-size: 0.82rem;
            margin-top: 4px;
            min-height: 16px;
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>

    <div class="form-card">
        <h4>Registerasi Akun Baru</h4>
        <p class="subtitle">Buat akun baru untuk melanjutkan</p>

        <?php if (!empty($error)): ?>
            <div class="error-alert">
                <div class="error-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#166534" stroke-width="2" />
                        <path d="M12 8V12" stroke="#166534" stroke-width="2" stroke-linecap="round" />
                        <circle cx="12" cy="16" r="1" fill="#166534" />
                    </svg>
                </div>
                <div class="error-content">
                    <div class="error-title">Registrasi Gagal</div>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" id="registerForm">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" maxlength="50" required>
                <div class="error-message" id="usernameError"></div>
            </div>

            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <div class="error-message" id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="InputPassword">Password</label>

                <div class="password-wrapper">
                    <input type="password"
                        id="InputPassword"
                        name="password"
                        placeholder="Password"
                        required>

                    <span class="toggle-password" onclick="togglePassword('InputPassword', this)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="3" stroke="#666" stroke-width="2" />
                        </svg>
                    </span>
                </div>
                <div class="error-message" id="passwordError"></div>
            </div>

            <button type="submit" name="submit" class="btn-daftar" id="submitBtn">Daftar</button>

        </form>

        <div class="form-footer">
            Sudah punya akun ? <a href="login.php">Login</a>
        </div>
    </div>

</body>
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);

        if (input.type === "password") {
            input.type = "text";
            icon.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a14.9 14.9 0 0 1 5.9-6.8L2 2l20 20-2.06-2.06zM9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a14.9 14.9 0 0 1-1.55 3.64M15 12a3 3 0 0 1-6 0 3 3 0 0 1 6 0z" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="2" y1="2" x2="22" y2="22" stroke="#666" stroke-width="2" stroke-linecap="round"/>
            </svg>`;
        } else {
            input.type = "password";
            icon.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="12" r="3" stroke="#666" stroke-width="2"/>
            </svg>`;
        }
    }

    // Real-time validation functions
    function validateUsername(username) {
        username = username.replace(/\s+/g, ' ').trim();
        if (username.length < 3) {
            return 'Username minimal 3 karakter';
        }
        if (username.length > 50) {
            return 'Username maksimal 50 karakter';
        }
        if (!/^[a-zA-Z0-9_ ]+$/.test(username)) {
            return 'Username hanya boleh huruf, angka, spasi, dan underscore';
        }
        return '';
    }

    function validateEmail(email) {
        if (!email) {
            return 'Email tidak boleh kosong';
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return 'Format email tidak valid';
        }
        return '';
    }

    function validatePassword(password) {
        if (password.length < 6) {
            return 'Password minimal 6 karakter';
        }
        if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
            return 'Password harus mengandung huruf besar, kecil, dan angka';
        }
        return '';
    }

    function showError(inputId, errorId, message) {
        const input = document.getElementById(inputId);
        const errorDiv = document.getElementById(errorId);

        if (message) {
            input.classList.add('error');
            errorDiv.textContent = message;
        } else {
            input.classList.remove('error');
            errorDiv.textContent = '';
        }
    }

    // Add event listeners for real-time validation
    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('InputPassword');
        const form = document.getElementById('registerForm');

        usernameInput.addEventListener('input', function() {
            this.value = this.value.replace(/\s+/g, ' ');
            const error = validateUsername(this.value);
            showError('username', 'usernameError', error);
        });

        emailInput.addEventListener('input', function() {
            const error = validateEmail(this.value.trim());
            showError('email', 'emailError', error);
        });

        passwordInput.addEventListener('input', function() {
            const error = validatePassword(this.value);
            showError('InputPassword', 'passwordError', error);
        });

        // Form submission validation
        form.addEventListener('submit', function(e) {
            usernameInput.value = usernameInput.value.replace(/\s+/g, ' ').trim();
            const usernameError = validateUsername(usernameInput.value);
            const emailError = validateEmail(emailInput.value.trim());
            const passwordError = validatePassword(passwordInput.value);

            showError('username', 'usernameError', usernameError);
            showError('email', 'emailError', emailError);
            showError('InputPassword', 'passwordError', passwordError);

            if (usernameError || emailError || passwordError) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

</html>
