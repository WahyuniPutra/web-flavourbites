<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orders']) && !empty($_POST['orders'])) {
    $orders = $_POST['orders'];
    
    // Insert new pembelian
    $sql = "INSERT INTO pembelian (tanggal_pembelian) VALUES (NOW())";
    $conn->query($sql);
    $id_pembelian = $conn->insert_id;
    
    foreach ($orders as $order) {
        $id_menu = $order['id_menu'];
        $quantity = $order['quantity'];
        
        if ($quantity > 0) {  // Only process items with quantity > 0
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.3.0/sweetalert2.all.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff0f5;
        }
        header {
            background-color: #ff69b4;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        nav {
            background-color: #ff1493;
            overflow: hidden;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        nav a:hover {
            background-color: #ff69b4;
            color: white;
        }
        .menu-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .menu-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .menu-item:hover {
            transform: translateY(-5px);
        }
        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .menu-item h3 {
            color: #ff1493;
            margin: 10px 0;
        }
        .menu-item .price {
            color: #ff69b4;
            font-size: 1.2em;
            font-weight: bold;
            margin: 10px 0;
        }
        .order-form {
            margin-top: 10px;
        }
        .order-form input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-right: 10px;
        }
        .order-form button {
            background-color: #ff1493;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .order-form button:hover {
            background-color: #ff69b4;
        }
        .total-price {
            text-align: center;
            margin-top: 20px;
            font-size: 1.5em;
            color: #ff1493;
        }
        footer {
            background-color: #ff1493;
            color: white;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .content-wrapper {
            margin-bottom: 70px; /* Add space for fixed footer */
        }
        h2.section-title {
            text-align: center;
            color: #ff1493;
            margin: 40px 0 20px;
            font-size: 2em;
        }
        .menu-item {
            position: relative;
            overflow: hidden;
        }
        
        .quantity-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
            gap: 10px;
        }
        
        .quantity-btn {
            background-color: #ff1493;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .quantity-btn:hover {
            background-color: #ff69b4;
            transform: scale(1.1);
        }
        
        .quantity-display {
            font-size: 1.2em;
            color: #ff1493;
            font-weight: bold;
            min-width: 40px;
            text-align: center;
        }
        
        .cart-summary {
            position: fixed;
            bottom: 70px;
            right: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 300px;
            z-index: 1000;
            transform: translateY(100%);
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s;
        }
        
        .cart-summary.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            border-bottom: 1px solid #ffd1dc;
        }
        
        .checkout-btn {
            background-color: #ff1493;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 1.1em;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.3s;
            width: 100%;
            margin-top: 15px;
        }
        
        .checkout-btn:hover {
            background-color: #ff69b4;
            transform: scale(1.05);
        }
        
        .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff1493;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            opacity: 0;
            transform: scale(0);
            transition: opacity 0.3s, transform 0.3s;
        }
        
        .badge.show {
            opacity: 1;
            transform: scale(1);
        }
        
        @keyframes addToCart {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .cart-animation {
            animation: addToCart 0.5s ease;
        }
    </style>
</head>
<body>
<header>
        <h1>Flavour Bites!</h1>
        <p>Discover Our Delicious Menu</p>
    </header>
    
    <div class="content-wrapper">
        <div class="menu-container">
            <h2 class="section-title">Our Menu</h2>
            <form class="order-form" method="POST" action="" id="orderForm">
                <div class="menu-grid">
                    <?php
                    $sql = "SELECT id_menu, nama_menu, harga, foto FROM menu";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='menu-item' data-id='" . $row['id_menu'] . "'>";
                            echo "<div class='badge'>Added to cart!</div>";
                            if ($row["foto"]) {
                                echo "<img src='" . htmlspecialchars($row["foto"]) . "' alt='" . htmlspecialchars($row["nama_menu"]) . "'>";
                            } else {
                                echo "<img src='images/default-menu.jpg' alt='Default menu image'>";
                            }
                            echo "<h3>" . htmlspecialchars($row["nama_menu"]) . "</h3>";
                            echo "<p class='price' data-price='" . htmlspecialchars($row["harga"]) . "'>Rp " . number_format($row["harga"], 0, ',', '.') . "</p>";
                            echo "<input type='hidden' name='orders[" . $row['id_menu'] . "][id_menu]' value='" . htmlspecialchars($row["id_menu"]) . "'>";
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

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuItems = document.querySelectorAll('.menu-item');
        const cartSummary = document.querySelector('.cart-summary');
        let timeout;

        function updateCartSummary() {
            const cartItems = document.querySelector('.cart-items');
            cartItems.innerHTML = '';
            let total = 0;
            let hasItems = false;

            menuItems.forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-input').value);
                if (quantity > 0) {
                    hasItems = true;
                    const name = item.querySelector('h3').textContent;
                    const price = parseInt(item.querySelector('.price').dataset.price);
                    const itemTotal = price * quantity;
                    total += itemTotal;

                    cartItems.innerHTML += `
                        <div class="cart-item">
                            <span>${name} x ${quantity}</span>
                            <span>Rp ${itemTotal.toLocaleString('id-ID')}</span>
                        </div>
                    `;
                }
            });

            document.getElementById('total-price').innerText = 
                `Total: Rp ${total.toLocaleString('id-ID')}`;

            if (hasItems) {
                cartSummary.classList.add('show');
            } else {
                cartSummary.classList.remove('show');
            }
        }

        function showAddedBadge(item) {
            const badge = item.querySelector('.badge');
            badge.classList.add('show');
            setTimeout(() => badge.classList.remove('show'), 1500);
        }

        menuItems.forEach(item => {
            const minusBtn = item.querySelector('.minus');
            const plusBtn = item.querySelector('.plus');
            const quantityDisplay = item.querySelector('.quantity-display');
            const quantityInput = item.querySelector('.quantity-input');

            minusBtn.addEventListener('click', () => {
                let quantity = parseInt(quantityDisplay.textContent);
                if (quantity > 0) {
                    quantity--;
                    quantityDisplay.textContent = quantity;
                    quantityInput.value = quantity;
                    updateCartSummary();
                }
            });

            plusBtn.addEventListener('click', () => {
                let quantity = parseInt(quantityDisplay.textContent);
                quantity++;
                quantityDisplay.textContent = quantity;
                quantityInput.value = quantity;
                item.classList.add('cart-animation');
                showAddedBadge(item);
                setTimeout(() => item.classList.remove('cart-animation'), 500);
                updateCartSummary();
            });
        });

        document.getElementById('orderForm').addEventListener('submit', (e) => {
            let hasItems = false;
            menuItems.forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-input').value);
                if (quantity > 0) hasItems = true;
            });

            if (!hasItems) {
                e.preventDefault();
                Swal.fire({
                    title: 'Cart Empty',
                    text: 'Please add some items to your cart before checking out.',
                    icon: 'warning',
                    confirmButtonColor: '#ff1493'
                });
            }
        });
    });
    </script>

    <footer>
        <p>@flavourbites.techno</p>
        <p>© 2024 Flavour Bites. All rights reserved.</p>
    </footer>
</body>
</html>