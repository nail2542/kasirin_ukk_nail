<?php
@ob_start();
session_start();
require 'config.php';

if (isset($_POST['proses'])) {
    $user = strip_tags($_POST['user']);
    $pass = strip_tags($_POST['pass']);

    $sql = 'SELECT member.*, login.user, login.pass
            FROM member 
            INNER JOIN login ON member.id_member = login.id_member
            WHERE user = ? AND pass = md5(?)';
    $row = $config->prepare($sql);
    $row->execute(array($user, $pass));
    $jum = $row->rowCount();

    if ($jum > 0) {
        $hasil = $row->fetch();
        $_SESSION['admin'] = $hasil;
        echo '<script>alert("Login Sukses");window.location="index.php";</script>';
    } else {
        echo '<script>alert("Login Gagal");history.go(-1);</script>';
    }
}

if (isset($_POST['register'])) {
    $reg_user = strip_tags($_POST['reg_user']);
    $reg_pass = strip_tags($_POST['reg_pass']);

    // Cek apakah username sudah terdaftar
    $check = $config->prepare('SELECT * FROM login WHERE user = ?');
    $check->execute(array($reg_user));
    $jum = $check->rowCount();

    if ($jum > 0) {
        echo '<script>alert("Username sudah ada, gunakan username lain.");history.go(-1);</script>';
    } else {
        // Hash password dengan md5 (untuk keamanan lebih baik bisa diganti dengan password_hash)
        $hashed_pass = md5($reg_pass);
        $insert = $config->prepare('INSERT INTO login (user, pass) VALUES (?, ?)');
        $insert->execute(array($reg_user, $hashed_pass));

        if ($insert) {
            echo '<script>alert("Registrasi berhasil! Silakan login.");window.location="index.php";</script>';
        } else {
            echo '<script>alert("Registrasi gagal, coba lagi.");history.go(-1);</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - UKK Kasir</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="sb-admin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 mt-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="h4 text-gray-900 mb-4"><b>Point Of Sales</b></h4>
                            </div>

                            <!-- Form Login -->
                            <form class="form-login" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="user" placeholder="User ID" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="pass" placeholder="Password" required>
                                </div>
                                <button class="btn btn-primary btn-block" name="proses" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
                            </form>
                            
                            <hr>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="sb-admin/vendor/jquery/jquery.min.js"></script>
    <script src="sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="sb-admin/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="sb-admin/js/sb-admin-2.min.js"></script>
</body>

</html>
