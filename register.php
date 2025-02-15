<?php
include 'includes/header.php';
?>

<div class="register-container">
    <h1>Register</h1>
    <form method="POST" action="proses_register.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

<?php
include 'includes/footer.php';
?>