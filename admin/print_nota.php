<?php
// print_nota.php
session_start();
include '../koneksi.php';

if (!isset($_GET['id_pembelian'])) {
    die('No order ID specified');
}

$id_pembelian = $_GET['id_pembelian'];

// Fetch nota details
$sql = "SELECT * FROM nota WHERE id_pembelian = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $id_pembelian);
$stmt->execute();
$result = $stmt->get_result();
$notas = $result->fetch_all(MYSQLI_ASSOC);

if (empty($notas)) {
    die('No nota found for this order ID');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian - Flavour Bites</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .nota-container {
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .header p {
            font-size: 12px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="nota-container">
        <div class="header">
            <h1>Flavour Bites</h1>
            <p>Technopreneurship Exhibition 4.0</p>
            <p>@flavourbites.techno</p>
            <p>===============================</p>
            <p>Nota :<?php echo htmlspecialchars($id_pembelian); ?></p>
            <p>Tanggal: <?php echo htmlspecialchars($notas[0]['tanggal']); ?></p>
            <p>Jam: <?php echo htmlspecialchars($notas[0]['jam']); ?></p>
            <p>===============================</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th style="text-align: right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($notas as $nota) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($nota['nama_menu']) . "</td>";
                    echo "<td style='text-align: right;'>Rp " . number_format($nota['total_harga'], 0, ',', '.') . "</td>";
                    echo "</tr>";
                    $total += $nota['total_harga'];
                }
                ?>
            </tbody>
        </table>

        <div class="total">
            <p>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></p>
        </div>

        <div class="footer">
            <p>===============================</p>
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Silahkan datang kembali</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.location.href = 'index.php';
            }
        }
    </script>
</body>
</html>