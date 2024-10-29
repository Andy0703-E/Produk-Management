<?php
session_start(); // Memulai session
include "database/koneksi.php"; // Pastikan ini sesuai dengan path file koneksi

// Cek apakah user sudah login
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: users/login.php"); // Arahkan ke halaman login jika belum login
    exit(); // Pastikan tidak ada kode yang dieksekusi setelah header
}

// Fungsi untuk menambahkan ulasan
if (isset($_POST['add_review'])) {
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("INSERT INTO reviews (customer_id, product_id, rating, comment) VALUES (:customer_id, :product_id, :rating, :comment)");
    $query->bindParam(':customer_id', $customer_id);
    $query->bindParam(':product_id', $product_id);
    $query->bindParam(':rating', $rating);
    $query->bindParam(':comment', $comment);
    $query->execute();
}

// Fungsi untuk mengupdate ulasan
if (isset($_POST['update_review'])) {
    $review_id = $_POST['review_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("UPDATE reviews SET rating=:rating, comment=:comment WHERE review_id=:review_id");
    $query->bindParam(':rating', $rating);
    $query->bindParam(':comment', $comment);
    $query->bindParam(':review_id', $review_id);
    $query->execute();
}

// Fungsi untuk menghapus ulasan
if (isset($_GET['delete_id'])) {
    $review_id = $_GET['delete_id'];

    // Persiapkan dan eksekusi query
    $query = $conn->prepare("DELETE FROM reviews WHERE review_id=:review_id");
    $query->bindParam(':review_id', $review_id);
    $query->execute();
}

// Mengambil data ulasan dari database
$query = $conn->prepare("SELECT * FROM reviews");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "header.php"; ?>

<div class="container mt-4">

    <!-- Form untuk menambahkan ulasan -->
    <h2>Add New Review</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="customer_id">Customer ID</label>
            <input type="text" name="customer_id" class="form-control" placeholder="Customer ID" required>
        </div>
        <div class="mb-3">
            <label for="product_id">Product ID</label>
            <input type="text" name="product_id" class="form-control" placeholder="Product ID" required>
        </div>
        <div class="mb-3">
            <label for="rating">Rating</label>
            <input type="number" name="rating" class="form-control" min="1" max="5" required>
        </div>
        <div class="mb-3">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" name="add_review" class="btn btn-primary">Add Review</button>
    </form>

    <!-- Daftar ulasan dalam tabel -->
    <h2 class="mt-4">Review List</h2>
    <?php if (count($result) > 0): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Product ID</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['customer_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['rating']); ?></td>
                        <td><?php echo htmlspecialchars($row['comment']); ?></td>
                        <td>
                            <a href="?edit_id=<?php echo $row['review_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete_id=<?php echo $row['review_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus ulasan ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada ulasan tersedia.</p>
    <?php endif; ?>

    <!-- Form untuk mengedit ulasan -->
    <?php if (isset($_GET['edit_id'])): 
        $edit_id = $_GET['edit_id'];
        $edit_query = $conn->prepare("SELECT * FROM reviews WHERE review_id=:review_id");
        $edit_query->bindParam(':review_id', $edit_id);
        $edit_query->execute();
        $edit_review = $edit_query->fetch(PDO::FETCH_ASSOC);
    ?>
        <h2>Edit Review</h2>
        <form method="POST">
            <input type="hidden" name="review_id" value="<?php echo $edit_review['review_id']; ?>">
            <div class="mb-3">
                <label for="rating">Rating</label>
                <input type="number" name="rating" class="form-control" value="<?php echo htmlspecialchars($edit_review['rating']); ?>" min="1" max="5" required>
            </div>
            <div class="mb-3">
                <label for="comment">Comment</label>
                <textarea name="comment" class="form-control" rows="3" required><?php echo htmlspecialchars($edit_review['comment']); ?></textarea>
            </div>
            <button type="submit" name="update_review" class="btn btn-success">Update Review</button>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
