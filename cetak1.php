<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        /* CSS untuk desain tabel */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }.print {
            text-align: left;
        }
    </style>
</head>

<body>
    <center><h1>Laporan Penjualan</h1></center>
<div><div>
    <table border="0.2">
        <thead>
            <tr>
                <th>Nama Makanan</th>
                <th>Nama Pelanggan</th>
                <th>Kasir</th>
                <th>Jumlah Barang</th>
                <th>Total Belanja</th>
                <th>Jumlah Bayar</th>
                <th>Kembalian</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'koneksi.php';

            $query = "SELECT 
                        idpenjualan,namapelanggan,
            GROUP_CONCAT(namaBarang SEPARATOR ', ') as namaMakanan,
            username as kasir,
            SUM(jumlahBarang) as jumlahBarang,
            SUM(totalharga) as totalBelanja,
            SUM(jumlahbayar) as totalBayar,
            SUM(kembalian) as totalKembalian
          FROM penjualan 
          INNER JOIN users ON penjualan.id = users.id 
          INNER JOIN produk ON penjualan.idBarang = produk.idBarang 
          INNER JOIN pelanggan ON penjualan.idPelanggan = pelanggan.idPelanggan 
          GROUP BY namapelanggan";
            $result = mysqli_query($koneksi, $query);

            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['idpenjualan'] . "</td>";
                echo "<td>" . $row['namaMakanan'] . "</td>";
                echo "<td>" . $row['namapelanggan'] . "</td>";
                echo "<td>" . $row['kasir'] . "</td>";
                echo "<td>" . $row['jumlahBarang'] . "</td>";
                echo "<td>" . $row['totalBelanja'] . "</td>";
                echo "<td>" . $row['totalBayar'] . "</td>";
                echo "<td>" . $row['totalKembalian'] . "</td>";
                echo "</tr>";
            }
           
            ?>
        </tbody>
    </table>
    </div>
    <?php
    $result = mysqli_query($koneksi, 'SELECT SUM(totalharga) AS value_sum FROM pelanggan'); 
    $row = mysqli_fetch_assoc($result); 
    $sum = $row['value_sum'];
    mysqli_close($koneksi);
    ?>
    <div class="total">
                <p><strong>Total Pemasukan:</strong> Rp <?= number_format($sum, 0, ',', '.') ?>,00</p>
            </div></div>
    <script>
        window.print();
    </script>
</body>

</html>