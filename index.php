<?php
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'login') {
        session_start();
        include 'assets/conn/config.php';

        $username = $_POST['username'];
        $password = $_POST['password'];

        $data = mysqli_query($conn, "SELECT * FROM tbl_admin WHERE username='$username' AND password='$password'");
        $cek = mysqli_num_rows($data);

        if ($cek > 0) {
            $a = mysqli_fetch_array($data);
            if ($a['level'] == 'Admin') {
                $_SESSION['username'] = $username;
                header("location:admin/index.php");
            } elseif ($a['level'] == 'Pasien') {
                $_SESSION['username'] = $username;
                header("location:pasien/index.php");
            }
        } else {
            header("location:index.php?pesan=gagal");
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
    <meta name="description" content="">
    <meta name="author" content="">
    <title></title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>



    <div class="container">


        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">

                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">SILAHKAN LOGIN</h1>
                                    </div>

                                    <?php
                                    if (isset($_GET['pesan'])) {
                                        if ($_GET['pesan'] == 'gagal') {
                                            echo "<div class='alert alert-danger'><span class='fas fa-times'></span>&nbsp; Login Gagal !!</div>";
                                        }
                                    }
                                    ?>

                                    <form class="user" action="index?aksi=login" method="post"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password"
                                                class="form-control form-control-user" id="exampleInputPassword"
                                                placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block"> MASUK</button>
                                    </form>
                                    <hr>

                                    <div class="text-center">
                                        <a href="daftar.php">-Daftar Akun-</a>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


    </div>



    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
</body>

</html>