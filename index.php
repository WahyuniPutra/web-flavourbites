<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orders']) && !empty($_POST['orders'])) {
    // Simpan orders ke dalam session
    $_SESSION['orders'] = $_POST['orders'];
    // Redirect ke form_bayar.php
    header('Location: form_bayar.php');
    exit();

    // Insert new pembelian
    $id_pembelian = date('s') . date('i') . date('H') . date('d') . date('m') . date('Y');
    $sql = "INSERT INTO pembelian (tanggal_pembelian) VALUES (NOW())";
    $conn->query($sql);
    $id_pembelian = $conn->insert_id;

    foreach ($orders as $order) {
        $id_menu = $order['id_menu'];
        $quantity = $order['quantity'];

        if ($quantity > 0) { // Only process items with quantity > 0
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
    Swal.fire({
        title: 'Thank You!',
        text: 'Your order has been placed successfully!',
        icon: 'success',
        confirmButtonColor: '#ff1493'
    }).then(() => {
        window.location.href = 'beranda.php';
    });
</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flavour Bites! - Checkout</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
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
        <div class="menu-container">
            <div>
                <button onclick="toggleCartSummary()" class="toggle-btn"><i class="fas fa-shopping-cart"></i></button>
            </div>  
            <h2 class="section-title">Our Menu</h2>        
            <form class="order-form" method="POST" action="" id="orderForm">
                <div class="menu-grid">
                    <?php
                    $sql = "SELECT id_menu, nama_menu, harga, porsi, foto FROM menu";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='menu-item' data-id='" . $row['id_menu'] . "'>";
                            echo "<div class='badge'>Added to cart!</div>";
                            if ($row["foto"]) {
                                echo $row["foto"];
                            } else {
                                echo "<img src='images/default-menu.jpg' alt='Default menu image'>";
                            }
                            echo "<h3>" . htmlspecialchars($row["nama_menu"]) . "</h3>";
                            echo "<p class='price' data-price='" . htmlspecialchars($row["harga"]) . "'>Rp " . number_format($row["harga"], 0, ',', '.') . "/" . $row["porsi"] . "</p>";
                            echo "<input type='hidden' name='orders[" . $row['id_menu'] . "][id_menu]' value='" . htmlspecialchars($row['id_menu']) . "'>";
                            echo "<div class='quantity-controls'>";
                            echo "<button type='button' class='quantity-btn minus'><i class='fas fa-minus'></i></button>";
                            echo "<span class='quantity-display'>0</span>";
                            echo "<input type='hidden' name='orders[" . $row['id_menu'] . "][quantity]' value='0' class='quantity-input'>";
                            echo "<button type='button' class='quantity-btn plus'><i class='fas fa-plus'></i></button>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    $conn->close();
                    ?>
                </div>

                <div class="cart-summary">
                    <h3>Your Order</h3>
                    <div class="cart-items"></div>
                    <div class="total-price" id="total-price">Total: Rp 0</div>
                    <button type="submit" class="checkout-btn">
                        <i class="fas fa-shopping-cart"></i> Checkout
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>@flavourbites.techno</p>
        <p>© 2024 Flavour Bites. All rights reserved.</p>
    </footer>
</body>
<script>
    function toggleCartSummary() {
        const cartSummary = document.querySelector('.cart-summary');
        
        if (cartSummary.style.opacity === '1') {
            // Sembunyikan cart-summary
            cartSummary.style.opacity = '0';
            if (window.innerWidth <= 768) {
                cartSummary.style.transform = 'translateX(50%) translateY(100%)';
            } else {
                cartSummary.style.transform = 'translateY(100%)';
            }
        } else {
            // Tampilkan cart-summary
            cartSummary.style.opacity = '1';
            if (window.innerWidth <= 768) {
                cartSummary.style.transform = 'translateX(50%) translateY(0)';
            } else {
                cartSummary.style.transform = 'translateY(0)';
            }
        }
    }
</script>
</html>