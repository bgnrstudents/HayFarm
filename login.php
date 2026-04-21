<?php
require('config/database.php');
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
<!-- <html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport"$dbtent="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet"></style>
</head>
<body> -->
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
        <!-- <script src="script.js"></script> -->
    <!-- Bootstrap requirement jQuery pada posisi pertama, kemudian Popper.js, dan  yang terakhit Bootstrap JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html> -->