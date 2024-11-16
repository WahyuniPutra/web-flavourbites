<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer_name']) && isset($_POST['customer_phone'])) {
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $orders = $_SESSION['orders'];

    // Create the new id_pembelian format
    $id_pembelian = date('md') . date('His');

    // Insert new pembelian
    $sql = "INSERT INTO pembelian (tanggal_pembelian, nama_pembeli, no_telepon, id_pembelian) VALUES (NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $customer_name, $customer_phone, $id_pembelian);
    $stmt->execute();

    foreach ($orders as $order) {
        $id_menu = $order['id_menu'];
        $quantity = $order['quantity'];

        if ($quantity > 0) {
            $sql = "SELECT nama_menu, harga FROM menu WHERE id_menu = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_menu);
            $stmt->execute();
            $result = $stmt->get_result();
            $menu_item = $result->fetch_assoc();

            if ($menu_item) {
                $nama_menu = $menu_item['nama_menu'];
                $harga = $menu_item['harga'];
                $total_harga = $harga * $quantity;

                $sql = "INSERT INTO pesanan (id_pembelian, id_menu, nama_menu, jumlah, total_harga) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iisii", $id_pembelian, $id_menu, $nama_menu, $quantity, $total_harga);
                $stmt->execute();
            }
        }
    }
    echo "<script>
            alert('Terimakasih! Pemesanan anda dengan ID Pembelian: $id_pembelian telah berhasil');
            window.location.href = 'index.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flavour Bites! - Payment Form</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <h1>Flavour Bites!</h1>
        <p>Discover Our Delicious Menu</p>
        <nav>
            <ul>
                <li><a href="index.php">Menu</a></li>
                <li><a href="cek_pesanan.php">Check Order</a></li>
            </ul>
        </nav>
    </header>

    <div class="content-wrapper">
        <div class="form-container">
            <form method="POST" action="">
                <label for="customer_name">Nama:</label>
                <input type="text" id="customer_name" name="customer_name" required>

                <label for="customer_phone">Nomor Telepon:</label>
                <input type="text" id="customer_phone" name="customer_phone" required>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <footer>
        <p>@flavourbites.techno</p>
        <p>© 2024 Flavour Bites. All rights reserved.</p>
    </footer>
</body>

</html>