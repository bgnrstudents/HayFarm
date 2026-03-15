<?php
require('../config/database.php');
//inisialisasi session
session_start();
$error = '';
$validate = '';
if( isset($_POST['submit']) ){        
        $email = stripslashes($_POST['email']);
        //cara sederhana mengamankan dari sql injection
        $email = mysqli_real_escape_string($db, $email);
         // menghilangkan backshlases
        $password = stripslashes($_POST['password']);
         //cara sederhana mengamankan dari sql injection
        $password = mysqli_real_escape_string($db, $password);
    
        //cek apakah nilai yang diinputkan pada form ada yang kosong atau tidak
        if(!empty(trim($email)) && !empty(trim($password))){
            //select data berdasarkan email dari database
            $query      = "SELECT * FROM user WHERE email = '$email'";
            $result     = mysqli_query($db, $query);
            $rows       = mysqli_num_rows($result);
            if ($rows != 0) {
                $hash   = mysqli_fetch_assoc($result)['password'];
                if(password_verify($password, $hash)){
                    $_SESSION['username'] = $username;
            
                    header('Location: ../index.php');
                    exit();
                }
                            
            //jika gagal maka akan menampilkan pesan error
            } else {
                $error =  'Upaya Masuk User Gagal !!';
            }
            
        }else {
            $error =  'Data tidak boleh kosong !!';
        }
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport"$dbtent="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<!-- nunito -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet"></style>
<!-- costum css -->
<style>
body {
    margin: 0;
    padding: 0;
    background: url('images/bg_login.png') no-repeat center center fixed;
    background-size: cover;
}
section{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100%;
    color: #fff;
}
.form-box{
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
h4{
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
.form-box{
    position: relative;
    margin: 20px 0;
    width: 630px;
    border-bottom: 2px solid #fff;
}
.form-box label{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    transition: all .5s ease-in-out;
    color: #fafafa;
    font-size: 1rem;
    pointer-events: none;
}
.form-control{
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
.form-group label{
    color: #000000;
    font-weight: semibold;
    font-family: 'Nunito', sans-serif;
    justify-content: left;
    align-items: start;
}
input:focus~label, input:valid~label{
    top: -5px;
}
.inputbox input{
    width: 350px;
    height: 60px;
    background: transparent;
    border: none;
    outline: none;
    font-size: 1rem;
    padding: 0 35px 0 5px;
    color: #fff;
}
button{
    width: 318px;
    height: 46px;
    border-radius: 8px;
    background: rgba(25, 108, 51, 1);
    border: none;
    outline: none;
    cursor: pointer;
    font-size: 20px;
    color: #fff;
}
.form-footer{
    font-size: .9rem;
    color: #009b48;
    text-align: center;
    margin: 25px 0 10px;
}
.form-footer p a{
    text-decoration: none;
    color: #009b48;
    font-weight: 600;
}
.form-footer p a:hover{
    text-decoration: underline;
}
</style>
</head>
<body>
        <section class="container-fluid mb-4">
            <section class="row justify-content-center">
            <section class="col-12 col-sm-6 col-md-4">
                <form class="form-container" action="login.php" method="POST">
                    <h4 class="text-center font-weight-bold">Masuk ke Akun</h4>
                    <p>Silahkan masukkan email dan password untuk melanjutkan</p>

                    <?php if($error != ''){ ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php } ?>

                    <!-- EMAIL -->
                    <div class="form-group text-left">
                        <label>Email address:</label>
                        <input type="email" class="form-control"  name="email" placeholder="alamatemailanda@gmail.com" required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group text-left"
                        <div style="display:flex; justify-content:space-between;">
                            <label>Password</label>
                            <a href="#" style="font-size:14px; color: darkgrey;">Forget Password?</a>
                        </div>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
                        <?php if($validate != '') { ?>
                            <p class="text-danger"><?= $validate; ?></p>
                        <?php } ?>
                    </div>

                    <!-- REMEMBER -->
                    <div class="form-group text-left">
                        <input type="checkbox" name="remember">
                        <label style="font-size:14px;"> Remember Password</label>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" name="submit" class="btn btn-block"> Masuk </button>

                    <!-- FOOTER -->
                    <div class="form-footer">
                        <p>Belum mempunyai akun ? <a href="register.php">Buat Akun</a></p>
                    </div>

                </form>
            </section>
            </section>
        </section>
        <script src="script.js"></script>
    <!-- Bootstrap requirement jQuery pada posisi pertama, kemudian Popper.js, dan  yang terakhit Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>