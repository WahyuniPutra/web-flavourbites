<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
        $id_pembelian = $_POST['id_pembelian'];
        $id_admin = 1; // ID admin yang login

        // Fetch order details
        $sql = "SELECT * FROM pesanan WHERE id_pembelian = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("i", $id_pembelian);
        $stmt->execute();
        $result = $stmt->get_result();
        $pesanan = $result->fetch_all(MYSQLI_ASSOC);

        if ($pesanan) {
            try {
                // Mulai transaksi
                $conn->begin_transaction();

                foreach ($pesanan as $order) {
                    // Insert into nota
                    $sql = "INSERT INTO nota (id_pembelian, id_admin, tanggal, jam, nama_menu, total_harga) 
                            VALUES (?, ?, CURDATE(), CURTIME(), ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if (!$stmt) {
                        throw new Exception('Prepare failed: ' . $conn->error);
                    }

                    $stmt->bind_param("iisi", $order['id_pembelian'], $id_admin, $order['nama_menu'], $order['total_harga']);
                    
                    if (!$stmt->execute()) {
                        throw new Exception('Execute failed: ' . $stmt->error);
                    }

                    // Delete from pesanan
                    $sql = "DELETE FROM pesanan WHERE id_pesanan = ?";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        throw new Exception('Prepare delete failed: ' . $conn->error);
                    }
                    
                    $stmt->bind_param("i", $order['id_pesanan']);
                    if (!$stmt->execute()) {
                        throw new Exception('Execute delete failed: ' . $stmt->error);
                    }
                }

                // Commit transaksi jika semua berhasil
                $conn->commit();

                // Redirect to print_nota.php
                header("Location: print_nota.php?id_pembelian=$id_pembelian");
                exit();

            } catch (Exception $e) {
                // Rollback jika terjadi error
                $conn->rollback();
                echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "');</script>";
                error_log('Error: ' . $e->getMessage());
            }
        } else {
            echo "<script>alert('Pesanan tidak ditemukan.');</script>";
        }
    } elseif (isset($_POST['reject'])) {
        $id_pembelian = $_POST['id_pembelian'];

        // Hapus pesanan dari tabel pesanan
        $sql = "DELETE FROM pesanan WHERE id_pembelian = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_pembelian);
            if ($stmt->execute()) {
                echo "<script>alert('Pesanan berhasil ditolak dan dihapus.');</script>";
            } else {
                echo "<script>alert('Gagal menghapus pesanan: " . htmlspecialchars($stmt->error) . "');</script>";
            }
        } else {
            echo "<script>alert('Prepare statement error: " . htmlspecialchars($conn->error) . "');</script>";
        }
    }
}

// Fetch pending orders
$sql = "SELECT * FROM pesanan ORDER BY id_pembelian";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Flavour Bites</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #ff1493;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ff1493;
            color: white;
        }
        .btn {
            padding: 10px 15px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-approve {
            background-color: #28a745;
        }
        .btn-approve:hover {
            background-color: #218838;
        }
        .btn-reject {
            background-color: #dc3545;
        }
        .btn-reject:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Flavour Bites</h1>
    </header>
    <div class="container">
        <h2>Pending Orders</h2>
        <?php
        if ($result->num_rows > 0) {
            $current_id_pembelian = null;
            while($row = $result->fetch_assoc()) {
                if ($current_id_pembelian !== $row['id_pembelian']) {
                    if ($current_id_pembelian !== null) {
                        echo "</tbody></table>
                              <form method='POST' style='text-align:center; margin-bottom:20px;'>
                                  <input type='hidden' name='id_pembelian' value='" . htmlspecialchars($current_id_pembelian) . "'>
                                  <button type='submit' name='approve' class='btn btn-approve'>Approve All</button>
                                  <button type='submit' name='reject' class='btn btn-reject'>Reject All</button>
                              </form>";
                    }
                    $current_id_pembelian = $row['id_pembelian'];
                    echo "<h3>ID Pembelian: " . htmlspecialchars($current_id_pembelian) . "</h3>";
                    echo "<table>
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Nama Menu</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>";
                }
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id_pesanan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nama_menu']) . "</td>";
                echo "<td>" . htmlspecialchars($row['jumlah']) . "</td>";
                echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>
                  <form method='POST' style='text-align:center; margin-bottom:20px;'>
                      <input type='hidden' name='id_pembelian' value='" . htmlspecialchars($current_id_pembelian) . "'>
                      <button type='submit' name='approve' class='btn btn-approve'>Approve All</button>
                      <button type='submit' name='reject' class='btn btn-reject'>Reject All</button>
                  </form>";
        } else {
            echo "<p>No pending orders.</p>";
        }
        ?>
    </div>
</body>
</html>