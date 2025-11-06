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
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Sistem Pakar</title>

    <!-- FontAwesome & SB Admin 2 -->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/login.css" rel="stylesheet">
</head>

<body>
<div class="container login-container d-flex justify-content-center align-items-center">
    <div class="card w-75 shadow-lg">
        <div class="row g-0">
            <!-- Kolom kiri: form login -->
            <div class="col-lg-6 p-5 d-flex flex-column justify-content-center">
                <div class="text-center mb-4">
                    <h1 class="h4 mb-2">Hallo Selamat Datang </h1>
                    <p class="mb-4">Disistem pakar diagnosa penyakit pencernaan anak-anak UPTD Puskesmas Kresek</p>
                </div>

                <?php
                if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') {
                    echo "<div class='alert'><i class='fas fa-times'></i> Login gagal! Username atau password salah.</div>";
                }
                ?>

                <form class="user" action="index.php?aksi=login" method="post">
                    <div class="form-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
                    </div>
                    <div class="form-group mb-4">
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block shadow-sm">Masuk</button>
                </form>

                <hr>
                <div class="text-center">
                    <a href="daftar.php" class="link-register">Belum punya akun? Daftar di sini</a>
                </div>
            </div>

            <!-- Kolom kanan: gambar -->
            <div class="col-lg-6 login-image"></div>
        </div>
    </div>
</div>

<!-- Script -->
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>
