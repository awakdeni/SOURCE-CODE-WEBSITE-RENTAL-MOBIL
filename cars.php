<?php
include 'includes/header.php';
include 'includes/db.php';

$cars = $pdo->query('SELECT * FROM cars WHERE status = "available"')->fetchAll();
?>

<section>
    <br>
    <div class="car-list">
        <?php foreach ($cars as $car): ?>
        <div class="car-item">
            <img src="<?= $car['image'] ?>" alt="<?= $car['name'] ?>">
            <h3><?= $car['name'] ?></h3>
            <p><?= $car['description'] ?></p>
            <p>Harga: Rp <?= number_format($car['price_per_day'], 0, ',', '.') ?> / hari</p>
            <a href="booking.php?car_id=<?= $car['id'] ?>">Pesan Sekarang</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
include 'includes/footer.php';
?>