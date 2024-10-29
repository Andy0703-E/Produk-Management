<?php
session_start(); // Memulai session
include "database/koneksi.php"; // Pastikan ini sesuai dengan path file koneksi

// Cek apakah user sudah login
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: users/login.php"); // Arahkan ke halaman login jika belum login
    exit(); // Pastikan tidak ada kode yang dieksekusi setelah header
}

// Fungsi untuk menambahkan kategori
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("INSERT INTO categories (category_name) VALUES (:name)");
    $query->bindParam(':name', $name);
    $query->execute();
}

// Fungsi untuk mengupdate kategori
if (isset($_POST['update_category'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("UPDATE categories SET category_name=:name WHERE category_id=:category_id");
    $query->bindParam(':name', $name);
    $query->bindParam(':category_id', $category_id);
    $query->execute();
}

// Fungsi untuk menghapus kategori
if (isset($_GET['delete_id'])) {
    $category_id = $_GET['delete_id'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("DELETE FROM categories WHERE category_id=:category_id");
    $query->bindParam(':category_id', $category_id);
    $query->execute();
}

// Mengambil data kategori dari database
$query = $conn->prepare("SELECT * FROM categories");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "header.php"; ?>

<div class="container mt-4">

    <!-- Form untuk menambahkan kategori -->
    <h2>Add New Category</h2>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Category Name" required>
        </div>
        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
    </form>

    <!-- Daftar kategori dalam tabel -->
    <h2 class="mt-4">Category List</h2>
    <?php if (count($result) > 0): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td>
                            <a href="?edit_id=<?php echo $row['category_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete_id=<?php echo $row['category_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus kategori ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada kategori tersedia.</p>
    <?php endif; ?>

    <!-- Form untuk mengedit kategori -->
    <?php if (isset($_GET['edit_id'])): 
        $edit_id = $_GET['edit_id'];
        $edit_query = $conn->prepare("SELECT * FROM categories WHERE category_id=:category_id");
        $edit_query->bindParam(':category_id', $edit_id);
        $edit_query->execute();
        $edit_category = $edit_query->fetch(PDO::FETCH_ASSOC);
    ?>
        <h2>Edit Category</h2>
        <form method="POST">
            <input type="hidden" name="category_id" value="<?php echo $edit_category['category_id']; ?>">
            <div class="mb-3">
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_category['category_name']); ?>" required>
            </div>
            <button type="submit" name="update_category" class="btn btn-success">Update Category</button>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
