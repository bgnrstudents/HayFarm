<?php
require('config/database.php');
session_start();
$error = '';
$validate = '';

if( isset($_POST['submit']) ){
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($db, $username);
    $email    = stripslashes($_POST['email']);
    $email    = mysqli_real_escape_string($db, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($db, $password);

    if(!empty(trim($username)) && !empty(trim($email)) && !empty(trim($password))){
        if( cek_nama($username,$db) == 0 ){
            $pass  = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO user (username, email, password) VALUES ('$username','$email','$pass')";
            $result = mysqli_query($db, $query);
            if ($result) {
                $_SESSION['username'] = $username;
                header('Location: login.php');
            } else {
                $error = 'Register User Gagal !!';
            }
        } else {
            $error = 'Username sudah terdaftar !!';
        }
    } else {
        $error = 'Data tidak boleh kosong !!';
    }
}

function cek_nama($username, $db){
    $nama  = mysqli_real_escape_string($db, $username);
    $query = "SELECT * FROM user WHERE username = '$nama'";
    if( $result = mysqli_query($db, $query) ) return mysqli_num_rows($result);
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

        .form-group input::placeholder {
            color: #aaa;
        }

        .form-group input:focus {
            border-color: #198754;
            background: #fff;
        }

        .btn-daftar {
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
            margin-top: 8px;
            transition: background 0.2s, transform 0.1s;
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
    </style>
</head>
<body>

    <div class="form-card">
        <h4>Registerasi Akun Baru</h4>
        <p class="subtitle">Buat akun baru untuk melanjutkan</p>

        <?php if($error != ''): ?>
            <div class="alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username">
            </div>

            <div class="form-group">
                <label for="no_telp">No Telp</label>
                <input type="text" id="no_telp" name="no_telp" placeholder="No Telp">
            </div>

            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" id="email" name="email" placeholder="Email">
            </div>

            <div class="form-group">
                <label for="InputPassword">Password</label>
                <input type="password" id="InputPassword" name="password" placeholder="Password">
                <?php if($validate != ''): ?>
                    <p style="color:#c0392b;font-size:0.82rem;margin-top:4px"><?= $validate ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" name="submit" class="btn-daftar">Daftar</button>

        </form>

        <div class="form-footer">
            Sudah punya akun ? <a href="login.php">Login</a>
        </div>
    </div>

</body>
</html>