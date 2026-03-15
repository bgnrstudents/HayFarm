<?php
//menyertakan file program koneksi.php pada register
require('../config/database.php');
//inisialisasi session
session_start();
$error = '';
$validate = '';

//mengecek apakah form registrasi di submit atau tidak
if( isset($_POST['submit']) ){
        // menghilangkan backshlases
        $username = stripslashes($_POST['username']);
        //cara sederhana mengamankan dari sql injection
        $username = mysqli_real_escape_string($db, $username);
        $email    = stripslashes($_POST['email']);
        $email    = mysqli_real_escape_string($db, $email);
        $password = stripslashes($_POST['password']);
        $password = mysqli_real_escape_string($db, $password);
        //cek apakah nilai yang diinputkan pada form ada yang kosong atau tidak
        if(!empty(trim($username)) && !empty(trim($email)) && !empty(trim($password))){
            //memanggil method cek_nama untuk mengecek apakah user sudah terdaftar atau belum
                if( cek_nama($username,$db) == 0 ){
                    //hashing password sebelum disimpan didatabase
                    $pass  = password_hash($password, PASSWORD_DEFAULT);
                    //insert data ke database
                    $query = "INSERT INTO user (username,email, password ) VALUES ('$username','$email','$pass')";
                    $result   = mysqli_query($db, $query);
                    //jika insert data berhasil maka akan diredirect ke halaman index.php serta menyimpan data username ke session
                    if ($result) {
                        $_SESSION['username'] = $username;
                
                        header('Location: login.php');
                    
                    //jika gagal maka akan menampilkan pesan error
                    } else {
                        $error =  'Register User Gagal !!';
                    }
                }else{
                        $error =  'Username sudah terdaftar !!';
                }          
        }else {
            $error =  'Data tidak boleh kosong !!';
        }
    } 
    //fungsi untuk mengecek username apakah sudah terdaftar atau belum
    function cek_nama($username,$db){
        $nama = mysqli_real_escape_string($db, $username);
        $query = "SELECT * FROM user WHERE username = '$nama'";
        if( $result = mysqli_query($db, $query) ) return mysqli_num_rows($result);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
            <!-- justify-content-center untuk mengatur posisi form agar berada di tengah-tengah -->
            <section class="row justify-content-center">
            <section class="col-12 col-sm-6 col-md-4 col-lg-5">
                <form class="form-container" action="register.php" method="POST">
                    <h4 class="text-center font-weight-bold"> Registerasi Akun Baru </h4>
                    <p> Buat akun baru untuk melanjutkan </p>
                    <?php if($error != ''){ ?>
                        <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                    <?php } ?>
                
                    <div class="form-group text-left">
                        <label for="nama">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username">
                    </div>
                    <div class="form-group text-left">
                        <label for="no_telp">No Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Masukkan No Telepon">
                    </div>
                    <div class="form-group text-left">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Masukkan email">
                    </div>
                    <div class="form-group text-left">
                        <label for="InputPassword">Password</label>
                        <input type="password" class="form-control" id="InputPassword" name="password" placeholder="Password">
                        <?php if($validate != '') {?>
                            <p class="text-danger"><?= $validate; ?></p>
                        <?php }?>
                    </div>
                    <button type="submit" name="submit" class="btn btn-block">Daftar</button>
                    <div class="form-footer mt-2">
                        <p> Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
                    </div>
                </form>
            </section>
            </section>
        </section>
    <!-- Bootstrap requirement jQuery pada posisi pertama, kemudian Popper.js, dan  yang terakhit Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>