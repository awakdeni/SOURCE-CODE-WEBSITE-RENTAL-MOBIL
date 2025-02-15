<?php
include 'includes/header.php'; // Include header
?>

<section>
    <h2>Hubungi Kami</h2>
    <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, silakan hubungi kami melalui formulir di bawah ini atau informasi kontak yang tersedia.</p>

    <div class="contact-container">
        <div class="contact-form">
            <h3>Kirim Pesan</h3>
            <form action="proses_contact.php" method="POST">
                <input type="text" name="name" placeholder="Nama Anda" required>
                <input type="email" name="email" placeholder="Email Anda" required>
                <input type="text" name="subject" placeholder="Subjek" required>
                <textarea name="message" placeholder="Pesan Anda" rows="5" required></textarea>
                <button type="submit">Kirim Pesan</button>
            </form>
        </div>

        <div class="contact-info">
            <h3>Informasi Kontak</h3>
            <ul>
                <li><strong>Alamat:</strong> Padang Tujuah, Pasaman Barat</li>
                <li><strong>Email:</strong> info@rentaloto.com</li>
                <li><strong>Telepon:</strong> 0823-8113-0623</li>
                <li><strong>Jam Operasional:</strong> Senin - Jumat, 08:00 - 17:00 WIB</li>
            </ul>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php'; // Include footer
?>