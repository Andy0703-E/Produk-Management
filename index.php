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

// Mengambil jumlah produk dari database
$product_count_query = "SELECT COUNT(*) AS total FROM products";
$product_count_result = mysqli_query($connect, $product_count_query);
$product_count = mysqli_fetch_assoc($product_count_result)['total'];

// Mengambil jumlah kategori dari database
$category_count_query = "SELECT COUNT(*) AS total FROM categories";
$category_count_result = mysqli_query($connect, $category_count_query);
$category_count = mysqli_fetch_assoc($category_count_result)['total'];

// Mengambil jumlah pelanggan dari database
$customer_count_query = "SELECT COUNT(*) AS total FROM customers";
$customer_count_result = mysqli_query($connect, $customer_count_query);
$customer_count = mysqli_fetch_assoc($customer_count_result)['total'];

// Menutup koneksi
mysqli_close($connect);
?>


<body>
<?php 
include "header.php"
?>
    <div class="container mt-4">
        <h1 class="mb-4">Dashboard</h1>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-header">Total Products</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product_count; ?></h5>
                        <p class="card-text">Jumlah produk yang tersedia.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-header">Total Categories</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $category_count; ?></h5>
                        <p class="card-text">Jumlah kategori produk.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-header">Total Customers</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $customer_count; ?></h5>
                        <p class="card-text">Jumlah pelanggan terdaftar.</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mt-5">Sales Overview</h2>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [120, 150, 170, 200, 230, 300],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
