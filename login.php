<?php
include 'includes/header.php';
?>

<div class="login-container">
    <h1>Login</h1>
    <form method="POST" action="proses_login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>

<?php
include 'includes/footer.php';
?>