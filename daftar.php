<?php
include 'assets/conn/config.php';
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'daftar') {

        $nama_lengkap = $_POST['nama_lengkap'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $umur = $_POST['umur'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Insert into tbl_admin only once
        // This query is already executed above, so it is removed to avoid duplicate entries.

        mysqli_query($conn, "INSERT INTO tbl_admin (nama_lengkap, username, password, level)
        VALUES('$nama_lengkap','$username','$password','Pasien')");

        $id_admin = mysqli_insert_id($conn); // Get the last inserted id_admin

        mysqli_query($conn, "INSERT INTO tbl_pasien (id_admin, nama_lengkap, jenis_kelamin, umur)
        VALUES ('$id_admin', '$nama_lengkap', '$jenis_kelamin', '$umur')");
        header("location:index.php?pesan=berhasil");
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
                                        <h1 class="h4 text-gray-900 mb-4">-SILAHKAN DAFTAR AKUN-</h1>
                                    </div>

                                    <?php
                                    if (isset($_GET['pesan'])) {
                                        if ($_GET['pesan'] == 'gagal') {
                                            echo "<div class='alert alert-danger'>
                                            <span class='fas fa-times'>
                                            </span>&nbsp; Login Gagal !!</div>";
                                        } elseif ($_GET['pesan'] == 'berhasil') {
                                            echo "<div class='alert alert-primary'>
                                            <span class='fas fa-check'>
                                            </span>&nbsp; Akun Berhasil Terdaftar!!</div>";
                                        }
                                    }
                                    ?>
                                    <form class="user" action="daftar.php?aksi=daftar" method="post"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <input type="text" name="nama_lengkap"
                                                class="form-control"
                                                placeholder="Nama Lengkap">
                                        </div>

                                        <div class="form-group">
                                            <select id="jenisKelamin" name="jenis_kelamin" class="form-control">
                                                <option selected disabled>Pilih Jenis Kelamin Anda</option>
                                                <option value="Laki-Laki">Laki-Laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input type="number" name="umur"
                                                class="form-control"
                                                placeholder="Umur" min="1" max="100">
                                        </div>

                                        <div class="form-group">
                                            <input type="text" name="username"
                                                class="form-control"
                                                placeholder="Username">
                                        </div>

                                        <div class="form-group">
                                            <input type="text" name="password"
                                                class="form-control"
                                                placeholder="Password">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">-DAFTAR-</button>
                                    </form>
                                    <br>

                                    <div class="text-center">
                                        <a href="index.php">-Login Akun-</a>
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