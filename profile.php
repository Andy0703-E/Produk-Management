<?php
session_start(); // Memulai session
include "database/koneksi.php"; // Pastikan ini sesuai dengan path file koneksi

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    echo "Pengguna tidak ditemukan.";
    exit();
}

// Ambil username dari session
$username = $_SESSION['username'];

// Mengambil data pengguna berdasarkan username
$query = $conn->prepare("SELECT * FROM users WHERE username = :username");
$query->bindParam(':username', $username);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit();
}
?>

<?php include "header.php"; ?>

<div class="container mt-4">
    <h2>Active User</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
