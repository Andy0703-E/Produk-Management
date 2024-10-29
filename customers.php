<?php
session_start(); // Memulai session
include "database/koneksi.php"; // Pastikan ini sesuai dengan path file koneksi

// Cek apakah user sudah login
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: users/login.php"); // Arahkan ke halaman login jika belum login
    exit(); // Pastikan tidak ada kode yang dieksekusi setelah header
}

// Fungsi untuk menambahkan pelanggan
if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("INSERT INTO customers (name, email, phone) VALUES (:name, :email, :phone)");
    $query->bindParam(':name', $name);
    $query->bindParam(':email', $email);
    $query->bindParam(':phone', $phone);
    $query->execute();
}

// Fungsi untuk mengupdate pelanggan
if (isset($_POST['update_customer'])) {
    $customer_id = $_POST['customer_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("UPDATE customers SET name=:name, email=:email, phone=:phone WHERE customer_id=:customer_id");
    $query->bindParam(':name', $name);
    $query->bindParam(':email', $email);
    $query->bindParam(':phone', $phone);
    $query->bindParam(':customer_id', $customer_id);
    $query->execute();
}

// Fungsi untuk menghapus pelanggan
if (isset($_GET['delete_id'])) {
    $customer_id = $_GET['delete_id'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("DELETE FROM customers WHERE customer_id=:customer_id");
    $query->bindParam(':customer_id', $customer_id);
    $query->execute();
}

// Mengambil data pelanggan dari database
$query = $conn->prepare("SELECT * FROM customers");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "header.php"; ?>

<div class="container mt-4">

    <!-- Form untuk menambahkan pelanggan -->
    <h2>Add New Customer</h2>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Customer Name" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Customer Email" required>
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Customer Phone" required>
        </div>
        <button type="submit" name="add_customer" class="btn btn-primary">Add Customer</button>
    </form>

    <!-- Daftar pelanggan dalam tabel -->
    <h2 class="mt-4">Customer List</h2>
    <?php if (count($result) > 0): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <a href="?edit_id=<?php echo $row['customer_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete_id=<?php echo $row['customer_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus pelanggan ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada pelanggan tersedia.</p>
    <?php endif; ?>

    <!-- Form untuk mengedit pelanggan -->
    <?php if (isset($_GET['edit_id'])): 
        $edit_id = $_GET['edit_id'];
        $edit_query = $conn->prepare("SELECT * FROM customers WHERE customer_id=:customer_id");
        $edit_query->bindParam(':customer_id', $edit_id);
        $edit_query->execute();
        $edit_customer = $edit_query->fetch(PDO::FETCH_ASSOC);
    ?>
        <h2>Edit Customer</h2>
        <form method="POST">
            <input type="hidden" name="customer_id" value="<?php echo $edit_customer['customer_id']; ?>">
            <div class="mb-3">
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_customer['name']); ?>" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($edit_customer['email']); ?>" required>
            </div>
            <div class="mb-3">
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($edit_customer['phone']); ?>" required>
            </div>
            <button type="submit" name="update_customer" class="btn btn-success">Update Customer</button>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
