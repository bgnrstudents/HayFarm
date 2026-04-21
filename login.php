<?php
require('config/database.php');
session_start();
$error    = '';
$validate = '';

if( isset($_POST['submit']) ){
    $email    = stripslashes($_POST['email']);
    $email    = mysqli_real_escape_string($db, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($db, $password);

    if(!empty(trim($email)) && !empty(trim($password))){
        $query  = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($db, $query);
        $rows   = mysqli_num_rows($result);

        if ($rows != 0) {
            $row  = mysqli_fetch_assoc($result);
            $hash = $row['password'];
            if(password_verify($password, $hash)){
                $_SESSION['username'] = $row['username']; // ← fix: ambil dari $row
                header('Location: ../index.php');
                exit();
            } else {
                $error = 'Email atau password salah !!';
            }
        } else {
            $error = 'Upaya Masuk User Gagal !!';
        }
    } else {
        $error = 'Data tidak boleh kosong !!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HayFarm</title>
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
            background: url('/HAYFARM-1/public/images/bg_login.png') no-repeat center center fixed;
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

        .subtitle {
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

        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .label-row label {
            margin-bottom: 0;
        }

        .label-row a {
            font-size: 0.82rem;
            color: #888;
            text-decoration: none;
        }

        .label-row a:hover {
            color: #196c33;
            text-decoration: underline;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
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

        .form-group input[type="email"]::placeholder,
        .form-group input[type="password"]::placeholder {
            color: #aaa;
        }

        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #198754;
            background: #fff;
        }

        /* Checkbox remember */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #196c33;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 0.88rem;
            color: #555;
            cursor: pointer;
            margin: 0;
        }

        .btn-masuk {
            width: 100%;
            height: 50px;
            background: #196c33;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 700;
            font-family: 'Nunito', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-masuk:hover {
            background: #145a2a;
        }

        .btn-masuk:active {
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
    </style>
</head>
<body>

    <div class="form-card">
        <h4>Masuk ke Akun</h4>
        <p class="subtitle">Silahkan masukkan email dan password untuk melanjutkan</p>

        <?php if($error != ''): ?>
            <div class="alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">

            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" id="email" name="email" placeholder="alamatemailanda@gmail.com" required>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <label for="password">Password</label>
                    <a href="#">Forget Password?</a>
                </div>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                <?php if($validate != ''): ?>
                    <p style="color:#c0392b;font-size:0.82rem;margin-top:4px"><?= $validate ?></p>
                <?php endif; ?>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Password</label>
            </div>

            <button type="submit" name="submit" class="btn-masuk">Masuk</button>

        </form>

        <div class="form-footer">
            Belum mempunyai akun ? <a href="register.php">Buat Akun</a>
        </div>
    </div>

</body>
</html>