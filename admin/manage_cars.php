<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../includes/db.php';

// Tambah mobil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price_per_day = $_POST['price_per_day'];
    $image = $_POST['image']; // Upload gambar sebenarnya

    $stmt = $pdo->prepare('INSERT INTO cars (name, description, price_per_day, image) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $description, $price_per_day, $image]);
}

// Hapus mobil
if (isset($_GET['delete'])) {
    $car_id = $_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM cars WHERE id = ?');
    $stmt->execute([$car_id]);
    header('Location: manage_cars.php');
    exit();
}

// Ambil daftar mobil
$cars = $pdo->query('SELECT * FROM cars')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="index.php">Admin Panel</a>
            </div>
            <nav class="navbar">
                <ul class="nav-links">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="manage_cars.php">Kelola Mobil</a></li>
                    <li><a href="manage_orders.php">Kelola Pemesanan</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <h2>Kelola Mobil</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Nama Mobil" required>
            <textarea name="description" placeholder="Deskripsi" required></textarea>
            <input type="number" name="price_per_day" placeholder="Harga per Hari" required>
            <input type="text" name="image" placeholder="URL Gambar" required>
            <button type="submit" name="add_car">Tambah Mobil</button>
        </form>

        <h3>Daftar Mobil</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?= $car['id'] ?></td>
                    <td><?= $car['name'] ?></td>
                    <td><?= $car['description'] ?></td>
                    <td>Rp <?= number_format($car['price_per_day'], 0, ',', '.') ?></td>
                    <td><img src="<?= $car['image'] ?>" alt="<?= $car['name'] ?>" width="100"></td>
                    <td>
                        <a href="manage_cars.php?delete=<?= $car['id'] ?>" onclick="return confirm('Apakah Anda yakin?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>