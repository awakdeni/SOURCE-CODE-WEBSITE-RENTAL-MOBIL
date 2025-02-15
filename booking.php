<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $bank = $_POST['bank'];
    
    // Hitung total harga
    $stmt = $pdo->prepare('SELECT price_per_day FROM cars WHERE id = ?');
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();
    $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
    $total_price = $car['price_per_day'] * $days;
    
    // Simpan pemesanan
    $stmt = $pdo->prepare('INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price, status) VALUES (?, ?, ?, ?, ?, "pending")');
    $stmt->execute([$_SESSION['user']['id'], $car_id, $start_date, $end_date, $total_price]);
    
    $booking_id = $pdo->lastInsertId();
    
    // Simpan data pembayaran
    if (!empty($_FILES['proof']['name'])) {
        $target_dir = "uploads/";
        $proof_image = $target_dir . basename($_FILES["proof"]["name"]);
        move_uploaded_file($_FILES["proof"]["tmp_name"], $proof_image);
        
        $stmt = $pdo->prepare("INSERT INTO payments (booking_id, user_id, bank_name, proof_image, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$booking_id, $_SESSION['user']['id'], $bank, $proof_image]);
    }

    header('Location: bookings.php');
    exit();
}

$car_id = $_GET['car_id'];
$stmt = $pdo->prepare('SELECT * FROM cars WHERE id = ?');
$stmt->execute([$car_id]);
$car = $stmt->fetch();

// Ambil daftar bank dari database
$bankStmt = $pdo->query('SELECT bank_name, account_number, account_holder FROM banks');
$banks = $bankStmt->fetchAll();

include 'includes/header.php';
?>
<section>
    <h1>Booking Mobil: <?= $car['name'] ?></h1>
    <form method="POST" enctype="multipart/form-data" oninput="calculatePrice()">
        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
        <label for="start_date">Tanggal Mulai:</label>
        <input type="date" name="start_date" id="start_date" required>
        <label for="end_date">Tanggal Selesai:</label>
        <input type="date" name="end_date" id="end_date" required>
        
        <label for="total_price">Total Harga:</label>
        <input type="text" id="total_price" readonly>
        
        <label for="bank">Pilih Bank:</label>
        <select name="bank" required>
            <?php foreach ($banks as $bank): ?>
                <option value="<?= $bank['bank_name'] ?>">
                    <?= $bank['bank_name'] ?> - <?= $bank['account_number'] ?> (a/n <?= $bank['account_holder'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        
        <label for="proof">Upload Bukti Transfer:</label>
        <input type="file" name="proof" accept="image/*" required>
        
        <button type="submit">Pesan & Bayar</button>
    </form>
</section>
<script>
function calculatePrice() {
    let startDate = document.getElementById('start_date').value;
    let endDate = document.getElementById('end_date').value;
    let pricePerDay = <?= $car['price_per_day'] ?>;

    if (startDate && endDate) {
        let start = new Date(startDate);
        let end = new Date(endDate);
        let days = (end - start) / (1000 * 60 * 60 * 24);
        if (days > 0) {
            document.getElementById('total_price').value = 'Rp ' + (pricePerDay * days).toLocaleString();
        } else {
            document.getElementById('total_price').value = '';
        }
    }
}
</script>
<?php include 'includes/footer.php'; ?>
