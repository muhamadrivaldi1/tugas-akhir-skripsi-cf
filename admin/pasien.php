<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $id_pasien = $_GET['id_pasien'];
        mysqli_query($conn, "DELETE FROM tbl_pasien WHERE id_pasien='$id_pasien'");
        header("location:pasien.php");
        exit;
    }
}

include 'header.php';
?>

<div class="container py-4">
    <div class="card border-0 shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="m-0">Data Pasien</h5>
            <a href="pasien-simpan.php" class="btn btn-light btn-sm rounded-pill">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Umur</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = mysqli_query($conn, "SELECT p.*, a.username, a.password 
                            FROM tbl_pasien p
                            LEFT JOIN tbl_admin a ON p.id_admin = a.id_admin
                            ORDER BY p.id_pasien");
                        if (!$data || mysqli_num_rows($data) === 0) {
                            echo '<tr><td colspan="7" class="text-center text-danger">Tidak ada data pasien.</td></tr>';
                        } else {
                            $no = 1;
                            while ($a = mysqli_fetch_array($data)) {
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($a['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($a['jenis_kelamin']) ?></td>
                                    <td><?= htmlspecialchars($a['umur']) ?></td>
                                    <td><?= htmlspecialchars($a['username'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($a['password'] ?? '-') ?></td>
                                    <td>
                                        <a href="pasien-ubah.php?id_pasien=<?= $a['id_pasien'] ?>"
                                            class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-2 px-3">
                                            <i class="fas fa-edit"></i> <span>Ubah</span>
                                        </a>

                                        <a href="pasien.php?id_pasien=<?= $a['id_pasien'] ?>&aksi=hapus"
                                            class="btn btn-outline-danger btn-sm rounded-pill d-flex align-items-center gap-2 px-3"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash-alt"></i> <span>Hapus</span>
                                        </a>

                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>