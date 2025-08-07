<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_admin = $_GET['id_admin'];
    mysqli_query($conn, "DELETE FROM tbl_admin WHERE id_admin='$id_admin' AND level='Admin'");
    header("location:admin.php");
    exit;
}

include 'header.php';
// Ambil nilai pencarian jika ada
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query data admin dengan level 'admin'
$sql = "SELECT * FROM tbl_admin WHERE level = 'Admin'";
if (!empty($search)) {
    $sql .= " AND (nama_lengkap LIKE '%$search%' OR username LIKE '%$search%')";
}
$sql .= " ORDER BY id_admin";
$data = mysqli_query($conn, $sql);
?>

<div class="container py-4">
    <div class="card border-0 shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="m-0"><i class="fas fa-user me-2"></i>Data Admin</h5>
            <a href="admin-simpan.php" class="btn btn-light btn-sm rounded-pill">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        </div>

        <div class="card-body">
            <!-- Form Pencarian -->
            <form method="GET" class="mb-3">
                <div class="input-group w-50">
                    <span class="input-group-text bg-primary text-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama admin..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="admin.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            <!-- Tabel Data Admin -->
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($data) > 0): ?>
                            <?php $no = 1;
                            while ($row = mysqli_fetch_assoc($data)) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['level']) ?></td>
                                    <td>
                                        <a href="edit_admin.php?id=<?= $row['id_admin'] ?>" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-2 px-3 mx-auto mb-1" style="width: max-content;">
                                            <i class="fas fa-edit"></i> <span>Ubah</span>
                                        </a>
                                        <a href="admin.php?aksi=hapus&id_admin=<?= $row['id_admin'] ?>" class="btn btn-outline-danger btn-sm rounded-pill d-flex align-items-center gap-2 px-3 mx-auto" style="width: max-content;" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash-alt"></i> <span>Hapus</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-danger text-center">Data tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>