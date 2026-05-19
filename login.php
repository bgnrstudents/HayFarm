<?php
//inisialisasi session
session_start();
$redirect_from_get = $_GET['redirect'] ?? '';
if (!empty($redirect_from_get)) {
    $_SESSION['redirect_after_login'] = $redirect_from_get;
}

require_once 'config/database.php';
require_once 'process/auth/Auth.php';

$db = (new Database())->getConnection();
$auth = new Auth($db);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($auth->login($email, $password)) {
        $role = $_SESSION['role'];

        $redirect_url = null;
        if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
            $redirect_url = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
        }

        $is_safe_redirect = false;
        if ($redirect_url) {
            $normalized_url = ltrim($redirect_url, '/');
            if (
                strpos($normalized_url, 'index.php?page=user/') === 0 ||
                strpos($normalized_url, 'pages/user/') === 0
            ) {
                $is_safe_redirect = true;
                $redirect_url = $normalized_url;
            }
        }

        if ($role === 'admin') {
            header('Location: pages/admin/dashboard.php');
        } elseif ($role === 'manager') {
            header('Location: pages/manager/index.php');
        } else {
            if ($is_safe_redirect && $redirect_url) {
                header('Location: ' . $redirect_url);
            } else {
                header('Location: index.php');
            }
        }
        exit();
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
    <title>Masuk | HayFarm</title>
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
            padding: 20px;
        }

        /* ── Card ── */
        .form-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 48px;
            width: 100%;
            max-width: 480px;
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

        /* ── Form Groups ── */
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
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #196c33;
            box-shadow: 0 0 0 3px rgba(25, 108, 51, 0.1);
        }

        .form-group input.error {
            border-color: #f87171 !important;
            background: #fff5f5 !important;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1) !important;
        }

        /* ── Password Wrapper ── */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            padding-right: 50px;
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

        /* ── Inline Error ── */
        .error-message-inline {
            color: #dc2626;
            font-size: 0.82rem;
            margin-top: 4px;
            min-height: 16px;
            font-family: 'Nunito', sans-serif;
        }

        /* ── Alert Error ── */
        .error-alert {
            background: linear-gradient(135deg, #fff5f5 0%, #fee2e2 100%);
            border: 1px solid #fca5a5;
            border-left: 4px solid #ef4444;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
            animation: slideInAlert 0.3s ease-out;
        }

        .error-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
            color: #b91c1c;
        }

        .error-content { flex: 1; }

        .error-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #b91c1c;
            margin-bottom: 4px;
        }

        .error-text {
            font-size: 0.88rem;
            color: #374151;
            line-height: 1.4;
        }

        @keyframes slideInAlert {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Submit Button ── */
        .btn-submit {
            width: 100%;
            height: 48px;
            border-radius: 10px;
            background: #196c33;
            border: none;
            outline: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Nunito', sans-serif;
            color: #fff;
            margin-top: 8px;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-submit:hover { background: #145a2a; }
        .btn-submit:active { transform: scale(0.99); }

        /* ── Footer ── */
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

        .form-footer a:hover { text-decoration: underline; }
    </style>
</head>

<body>
    <div class="form-card">
        <h4>Masuk ke Akun</h4>
        <p class="subtitle">Silahkan masukkan email dan password untuk melanjutkan</p>

        <?php if (!empty($error)): ?>
            <div class="error-alert">
                <div class="error-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#b91c1c" stroke-width="2"/>
                        <path d="M12 8V12" stroke="#b91c1c" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="12" cy="16" r="1" fill="#b91c1c"/>
                    </svg>
                </div>
                <div class="error-content">
                    <div class="error-title">Login Gagal</div>
                    <div class="error-text"><?= htmlspecialchars($error) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" id="loginForm">

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                    placeholder="alamatemailanda@gmail.com"
                    autocomplete="username" required>
                <div class="error-message-inline" id="emailError"></div>
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="loginPassword" name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password" required>
                    <span class="toggle-password" onclick="togglePassword('loginPassword', this)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke="#666" stroke-width="2"/>
                        </svg>
                    </span>
                </div>
                <div class="error-message-inline" id="passwordError"></div>
            </div>

            <!-- BUTTON -->
            <button type="submit" name="submit" class="btn-submit">Masuk</button>

        </form>

        <!-- FOOTER -->
        <div class="form-footer">
            Belum mempunyai akun? <a href="register.php">Buat Akun</a>
        </div>
    </div>

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

        function validateEmail(email) {
            if (!email) return 'Email tidak boleh kosong';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return 'Format email tidak valid';
            return '';
        }

        function validatePassword(password) {
            if (!password) return 'Password tidak boleh kosong';
            return '';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const emailInput    = document.getElementById('email');
            const passwordInput = document.getElementById('loginPassword');
            const form          = document.getElementById('loginForm');

            emailInput.addEventListener('input', function () {
                showError('email', 'emailError', validateEmail(this.value.trim()));
            });

            passwordInput.addEventListener('input', function () {
                showError('loginPassword', 'passwordError', validatePassword(this.value));
            });

            form.addEventListener('submit', function (e) {
                const emailErr    = validateEmail(emailInput.value.trim());
                const passwordErr = validatePassword(passwordInput.value);

                showError('email', 'emailError', emailErr);
                showError('loginPassword', 'passwordError', passwordErr);

                if (emailErr || passwordErr) {
                    e.preventDefault();
                    document.querySelector('.error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    </script>
</body>

</html>