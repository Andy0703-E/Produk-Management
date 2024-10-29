<?php
session_start(); // Memulai session

// Cek apakah user sudah login
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: users/login.php"); // Arahkan ke halaman login jika belum login
    exit(); // Pastikan tidak ada kode yang dieksekusi setelah header
}

// Koneksi ke database
$connect = mysqli_connect("localhost", "root", "", "kedai");

// Cek koneksi
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fungsi untuk menambahkan produk
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $price = mysqli_real_escape_string($connect, $_POST['price']);
    $stock = mysqli_real_escape_string($connect, $_POST['stock']);
    
    $query = "INSERT INTO products (name, description, price, stock) VALUES ('$name', '$description', '$price', '$stock')";
    mysqli_query($connect, $query);
}

// Fungsi untuk mengupdate produk
if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $price = mysqli_real_escape_string($connect, $_POST['price']);
    $stock = mysqli_real_escape_string($connect, $_POST['stock']);

    $query = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock' WHERE product_id='$product_id'";
    mysqli_query($connect, $query);
}

// Fungsi untuk menghapus produk
if (isset($_GET['delete_id'])) {
    $product_id = $_GET['delete_id'];
    $query = "DELETE FROM products WHERE product_id='$product_id'";
    mysqli_query($connect, $query);
}

// Mengambil data produk dari database
$query = "SELECT * FROM products";
$result = mysqli_query($connect, $query);
?>

<?php 
include "header.php"
?>

    <div class="container mt-4">

        <!-- Form untuk menambahkan produk -->
        <h2>Add New Product</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            </div>
            <div class="mb-3">
                <textarea name="description" class="form-control" placeholder="Product Description" required></textarea>
            </div>
            <div class="mb-3">
                <input type="number" name="price" class="form-control" placeholder="Price" required>
            </div>
            <div class="mb-3">
                <input type="number" name="stock" class="form-control" placeholder="Stock" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-primary w-100">Add Product</button>
        </form>

        <!-- Daftar produk dalam tabel -->
        <h2 class="mt-4">Product List</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['stock']); ?></td>
                            <td>
                                <a href="?edit_id=<?php echo $row['product_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete_id=<?php echo $row['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus produk ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada produk tersedia.</p>
        <?php endif; ?>

        <!-- Form untuk mengedit produk -->
        <?php if (isset($_GET['edit_id'])): 
            $edit_id = $_GET['edit_id'];
            $edit_query = "SELECT * FROM products WHERE product_id='$edit_id'";
            $edit_result = mysqli_query($connect, $edit_query);
            $edit_product = mysqli_fetch_assoc($edit_result);
        ?>
            <h2>Edit Product</h2>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $edit_product['product_id']; ?>">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_product['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <textarea name="description" class="form-control"><?php echo htmlspecialchars($edit_product['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($edit_product['price']); ?>" required>
                </div>
                <div class="mb-3">
                    <input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($edit_product['stock']); ?>" required>
                </div>
                <button type="submit" name="update_product" class="btn btn-success">Update Product</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Menutup koneksi -->
    <?php mysqli_close($connect); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
