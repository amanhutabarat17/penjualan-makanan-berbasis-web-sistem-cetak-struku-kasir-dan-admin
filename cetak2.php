<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Tersedia</title>
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
    <center><h1>Laporan Barang Tersedia</h1></center>
<div><div>
    <table border="0.2">
        <thead>
            <tr>

                <th>No</th>
                <th>Nama Makanan</th>
                <th>Id Makanan</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'koneksi.php';
            $query = "SELECT * FROM produk";
            $result = mysqli_query($koneksi, $query);
            $no=1;
            while ($row = mysqli_fetch_array($result)) {
                $no++;
                echo "<tr>";
                echo "<td>" . $no . "</td>";
                echo "<td>" . $row['namaBarang'] . "</td>";
                echo "<td>" . $row['idBarang'] . "</td>";
                echo "<td>" . $row['harga'] . "</td>";
                echo "<td>" . $row['deskripsi'] . "</td>";
                echo "<td>" . $row['stok'] . "</td>";
                echo "</tr>";
            }
           
            ?>
        </tbody>
    </table>
    </div>
    <?php
    $result = mysqli_query($koneksi, 'SELECT SUM(stok) AS value_sum FROM produk'); 
    $row = mysqli_fetch_assoc($result); 
    $sum = $row['value_sum'];
    mysqli_close($koneksi);
    ?>
    <div class="total">
                <p><strong>Barang Tersedia:</strong><?=$sum?></p>
            </div></div>
    <script>
        window.print(); // Memanggil fungsi print() untuk mencetak halaman web.
    </script>
</body>

</html>