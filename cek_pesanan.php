<?php
session_start();
include 'koneksi.php';

$search_result = null;
$search_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = trim($_POST['search_term']);
    $whatsapp_number = '6281347844591';
    if (!empty($search_term)) {
        $sql = "SELECT DISTINCT 
                    p.id_pembelian,
                    p.tanggal_pembelian,
                    p.nama_pembeli,
                    p.no_telepon,
                    ps.nama_menu,
                    ps.jumlah,
                    ps.total_harga,
                    n.id_nota
                FROM pembelian p
                LEFT JOIN pesanan ps ON p.id_pembelian = ps.id_pembelian
                LEFT JOIN nota n ON p.id_pembelian = n.id_pembelian
                WHERE p.id_pembelian = ? 
                OR (LOWER(p.nama_pembeli) = LOWER(?))
                ORDER BY p.tanggal_pembelian DESC, ps.id_pesanan ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $search_term, $search_term);
        $stmt->execute();
        $search_result = $stmt->get_result();

        if ($search_result->num_rows == 0) {
            $search_error = "Tidak ditemukan pesanan: " . htmlspecialchars($search_term);
        }
    } else {
        $search_error = "Mohon memasukkan ID Pembelian atau Nama";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flavour Bites! - Check Order</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            <h2 class="section-title">Cek Pesanan Anda</h2>
            <form method="POST" action="">
                <div class="search-container">
                    <label for="search_term">Masukan ID Pembelian atau Nama</label>
                    <input type="text" id="search_term" name="search_term" placeholder="Search order..." value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>">
                    <button type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>

            <?php if ($search_error): ?>
                <div class="error-message">
                    <?php echo $search_error; ?>
                </div>
            <?php endif; ?>

            <?php if ($search_result && $search_result->num_rows > 0): ?>
                <div class="order-results">
                    <?php
                    $current_order = null;
                    $total_order = 0;

                    while ($row = $search_result->fetch_assoc()):
                        if ($current_order !== $row['id_pembelian']):
                            if ($current_order !== null):
                                echo "<div class='order-total'>Total: Rp " . number_format($total_order, 0, ',', '.') . "</div>";
                                echo "</div>"; // Close order-details
                                echo "<div class='contact-seller'><a href='https://wa.me/$whatsapp_number?text=Hai%20saya%20" . urlencode($current_nama_pembeli) . "%2C%20ingin%20menginformasikan%20pembelian%20dengan%20ID%20" . urlencode($current_id_pembelian) . ".%20Apakah%20Anda%20dapat%20membantu%20saya%20dengan%20pesanan%20tersebut%3F' target='_blank'>Hubungi Penjual</a></div>";
                                echo "</div>"; // Close order-card
                            endif;

                            $current_order = $row['id_pembelian'];
                            $current_nama_pembeli = $row['nama_pembeli'];
                            $current_id_pembelian = $row['id_pembelian'];
                            $total_order = 0;
                    ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <h3>Order ID: <?php echo htmlspecialchars($row['id_pembelian']); ?></h3>
                                    <p>Customer: <?php echo htmlspecialchars($row['nama_pembeli']); ?></p>
                                    <p>Date: <?php echo date('d-m-Y H:i', strtotime($row['tanggal_pembelian'])); ?></p>
                                    <?php if ($row['id_nota']): ?>
                                        <div class="nota-badge">
                                            <i class="fas fa-receipt"></i> Pembayaran Berhasil
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="order-details">
                                <?php
                            endif;
                            if ($row['nama_menu']):
                                $total_order += $row['total_harga'];
                                ?>
                                    <div class="order-item">
                                        <span class="item-name"><?php echo htmlspecialchars($row['nama_menu']); ?></span>
                                        <span class="item-quantity">x<?php echo $row['jumlah']; ?></span>
                                        <span class="item-price">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></span>
                                    </div>
                            <?php
                            endif;
                        endwhile;

                        if ($current_order !== null):
                            echo "<div class='order-total'>Total: Rp " . number_format($total_order, 0, ',', '.') . "</div>";
                            echo "</div>"; // Close order-details
                            echo "<div class='contact-seller'><button onclick=\"window.open('https://wa.me/$whatsapp_number?text=Hai%20saya%20" . urlencode($current_nama_pembeli) . "%2C%20ingin%20menginformasikan%20pembelian%20dengan%20ID%20" . urlencode($current_id_pembelian) . ".%20Apakah%20Anda%20dapat%20membantu%20saya%20dengan%20pesanan%20tersebut%3F', '_blank')\">Hubungi Penjual</button></div>";
                            echo "</div>"; // Close order-card
                        endif;
                            ?>
                                </div>
                            </div>
                        <?php endif; ?>

                </div>
        </div>

        <footer>
            <p>@flavourbites.techno</p>
            <p>© 2024 Flavour Bites. All rights reserved.</p>
        </footer>
    </div>
</body>

</html>