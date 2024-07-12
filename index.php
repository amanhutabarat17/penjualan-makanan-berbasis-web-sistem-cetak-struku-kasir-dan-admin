<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'kasir') {
    header("Location: login.php");
    exit;
  }
  $username = $_SESSION['username'];
$query1 = "select id from users where username='$username'";
$result1 = mysqli_query($koneksi, $query1);
while ($row = mysqli_fetch_array($result1)) {
    $idKasir=$row['id'];
// Initialize variables
$namaBarang = '';
$harga = 0;
$idBarang = 0;

// Initialize cart if not set
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
    $_SESSION['lastid'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambahKeranjang'])) {
    $namaBarang = $_POST['namaBarang'];
    $harga = $_POST['harga'];
    $idBarang = $_POST['idBarang'];
    $stok = $_POST['stok'];

    
    if ($stok > 0) {
        $found = false;
        foreach ($_SESSION['keranjang'] as $key => $item) {
            if ($item['idBarang'] == $idBarang) {
                $_SESSION['keranjang'][$key]['jumlah']++; // Memperbarui jumlah langsung
                $found = true;
                break;
            }
        }
        
        // Tambahkan item baru ke keranjang jika belum ada
        if (!$found) {
            $_SESSION['keranjang'][] = [
                'id' => $_SESSION['lastid'],
                'namaBarang' => $namaBarang,
                'harga' => $harga,
                'idBarang' => $idBarang,
                'jumlah' => 1
            ];
            $_SESSION['lastid']++;
        }

        // Kurangi stok produk yang dibeli
        $query_update = "UPDATE produk SET stok = stok - 1 WHERE idBarang = $idBarang";
        mysqli_query($koneksi, $query_update);
    }
}

// $total=0;
// $total=0;
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan'])) {
//      foreach ($_SESSION['keranjang'] as $item){
//    $nama=$item['namaBarang'];
//    $total+=$item['harga'];
// } 

// }

$totalHarga2 = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $totalHarga2 += $item['harga'] * $item['jumlah']; 
}


if (isset($_POST['kurang'])) {
    $idHapus = $_POST['idHapus'];
    $idBarang = $_POST['idBarang'];

    foreach ($_SESSION['keranjang'] as $key => $item) {
        if ($item['id'] == $idHapus) {
            // Tambahkan kembali stok produk yang dikurangi
            $query_update = "UPDATE produk SET stok = stok + 1 WHERE idBarang = $idBarang";
            mysqli_query($koneksi, $query_update);

            // Kurangi jumlah item dalam keranjang
            $_SESSION['keranjang'][$key]['jumlah']--;
            foreach ($_SESSION['keranjang'] as  $item) {
            if ($item ['jumlah'] <= 0) {
                unset($_SESSION['keranjang'][$key]);
            }
        }
            break;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpankan'])) {
    $namaPembeli = mysqli_real_escape_string($koneksi, $_POST['namaPembeli']);
    $bayar = (int) $_POST['bayartah'];
    $kembalian = $bayar - $_SESSION['totalHarga']; 

    $now = date('Y-m-d H:i:s');
    $tgl_bln_thn = date('d M Y', strtotime($now));
    
    // Insert into pelanggan table
    $insert_pelanggan = "INSERT INTO pelanggan (namaPelanggan, totalHarga, jumlahBayar, kembalian,tanggal) 
                        VALUES ('$namaPembeli', {$_SESSION['totalHarga']}, $bayar, $kembalian,'$tgl_bln_thn')";
    if (mysqli_query($koneksi, $insert_pelanggan)) {
        $idPelanggan = mysqli_insert_id($koneksi);

        // Array untuk menyimpan jumlah barang yang akan dimasukkan ke dalam penjualan
        $itemsToInsert = [];

        // Proses setiap item dalam keranjang
        foreach ($_SESSION['keranjang'] as $item) {
            $idBarang = (int) $item['idBarang'];
            $jumlahBarang = (int) $item['jumlah'];

            // Tambahkan item ke $itemsToInsert
            $itemsToInsert[$idBarang] = isset($itemsToInsert[$idBarang]) ? $itemsToInsert[$idBarang] + $jumlahBarang : $jumlahBarang;
        }

        // Insert into penjualan table for each unique item in $itemsToInsert
        foreach ($itemsToInsert as $idBarang => $jumlahBarang) {
            // Insert into penjualan for each unique item
            $insert_penjualan = "INSERT INTO penjualan (idPelanggan, idBarang, id, jumlahBarang) 
                                VALUES ($idPelanggan, $idBarang, $idKasir, $jumlahBarang)";
            mysqli_query($koneksi, $insert_penjualan);

            // Update stok barang jika perlu (opsional)
            // $query_update = "UPDATE produk SET stok = stok - $jumlahBarang WHERE idBarang = $idBarang";
            // mysqli_query($koneksi, $query_update);
        }

        // Clear session setelah transaksi berhasil
        $_SESSION['keranjang'] = [];
        $_SESSION['lastid'] = 0;
        $_SESSION['totalHarga'] = 0;

        // Redirect atau tampilkan pesan sukses
            echo "<script> alert('Berhasil');
                 window.location.href = 'index.php';
                 </script>";
          
    } else {
        // Handle error
        echo "Error: " . mysqli_error($koneksi);
    }
}
if (isset($_POST['bayarkan'])) {
    $totalHarga = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $totalHarga += $item['harga'] * $item['jumlah']; // Calculate total price for each item
}
    $_SESSION['totalHarga'] = $totalHarga;
}
$totalHa=0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hitung'])) {
    $bayar = (int) $_POST['bayartah'];

    foreach ($_SESSION['keranjang'] as $item) {
        $totalHa += $item['harga'];
    }

    $kembalian = $bayar - $totalHa;

    // Set a session variable to store the calculation result
    
}
// Display modal on form submission
$showModal = isset($_POST['bayarkan'])|| isset($_POST['hitung']);

// Query to get product data
$result = mysqli_query($koneksi, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aman.Store</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .main-content {
            display: flex;
        }

        .product-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            height: auto;
            flex: 1;
            /* // overflow-y: auto; */
        }

        .product-item {
            height: 100%;
        }

        .cart-container {
            background-color: #FEF3C7;
            /* bg-yellow-200 */
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-left: 5px;

        }
        body{
            background-color: #393646;
        } .navi,nav{
          background-color: #1c1917;
        }
    </style>
</head>

<body>

<nav class="nav  text-white border-gray-200 dark:bg-gray-900 dark:border-gray-700 p-4">
    <div class="navi max-w-screen-xl flex items-center justify-between mx-auto">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="gambar/shop.jpg" class="h-9 w-10" alt="Flowbite Logo" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Aman.Store</span>
        </a>
        <div class="flex justify-end gap-2" >
            <p class="text-lg">Selamat Datang <?= $username ?>, Anda login sebagai Kasir</p>
            <div><a href="logout.php" class="bg-yellow-200 rounded px-4 py-2 text-black">LOGOUT</a></div>
 </div>
    </div>
</nav>

    <!-- Content -->
    <div class="main-content p-4 mt-2 mx-auto pl-0 max-w-screen-xl flex flex-col md:flex-row gap-4">
        <div class="product-container w-[400px] rounded h-[600px]">
            <?php while ($row = mysqli_fetch_array($result)): ?>
                <div class="product-item flex flex-col justify-center items-center w-full h-80 rounded-lg bg-gray-300 py-1">
                    <div class="flex h-40 w-full justify-center items-center p-1">
                    <img src="images/<?php echo $row['foto']; ?>" style="max-width: 100%; max-height: 100%;border-radius: 10px;" alt="<?php echo $row['namaBarang']; ?>">
                    </div>
                    <h2 class="text-2xl mb-3 font-bold"><?= $row['namaBarang'] ?></h2>
                    <div class="h-32 mb-2">
                        <p class="text-sm"><?= $row['deskripsi'] ?></p>
                    </div>
                    <div><p class="text-sm" style="color:red">Stok: <?= $row['stok'] ?></p></div>
                    <div>
                   <strong><p class="text-sm">Rp<?= number_format($row['harga'], 0, ',', '.') ?>,00</p></strong> 
                    </div>
                    
                    <form method="POST" action="#barang">
                        <input type="hidden" name="namaBarang" value="<?= $row['namaBarang'] ?>">
                        <input type="hidden" name="harga" value="<?= $row['harga'] ?>">
                        <input type="hidden" name="idBarang" value="<?= $row['idBarang'] ?>">
                        <input type="hidden" name="stok" value="<?= $row['stok'] ?>">
                        <button type="submit" name="tambahKeranjang"
                            class="block bg-green-300  hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center border-black">Tambah
                            ke Keranjang</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="flex w-1/6">

            <div class="cart-container fixed bg-green-300 text-center">
                <div class="flex justify-between items-center mb-4">
                    <img src="gambar/keranjang1.png" alt="" style="height: 80px;">
                    <form method="POST" action="">
                        <button type="submit" name="bayarkan"
                            class="text-2xl font-bold bg-blue-400 px-4 py-2 rounded">Bayar</button>
                    </form>
                </div>

                <?php if (!empty($_SESSION['keranjang'])): ?>
                    <?php foreach ($_SESSION['keranjang'] as $item): ?>
                        <div class="flex justify-between items-center mb-1">
                            <div><?= $item['namaBarang'] ?> - <?= $item['harga'] ?> X <?= $item['jumlah'] ?></div>
                            <form method="POST" action="">
                                <input type="hidden" name="namaBarang" value="<?= $item['namaBarang'] ?>">
                                <input type="hidden" name="idHapus" value="<?= $item['id'] ?>">
                                <input type="hidden" name="idBarang" value="<?= $item['idBarang'] ?>">
                                <button type="submit" name="kurang"
                                    class="bg-yellow-200 text-red-500 hover:bg-yellow-300 focus:ring-4 focus:outline-none focus:ring-red-300 font-small rounded-lg px-2 py-1">-</button>
                            </form>
                        </div>
                    <?php endforeach; ?>

                    <form method="POST" action="">
                        <button type="submit" name="hitungTotal"
                            class="block bg-blue-500 text-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Hitung
                            Total</button>
                    </form>
                    <?php if (isset($_POST['hitungTotal'])): ?>
                        <p class="mt-3">Total Harga: Rp<?= number_format($totalHarga2 , 0, ',', '.') ?>,00</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Keranjang kosong.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($showModal): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h2 class="text-2xl font-bold mb-4">Keranjang Belanja</h2>
            <form method="POST" action="">
                <table class="w-full mb-4">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Nama Barang</th>
                            <th class="border px-4 py-2">Harga</th>
                            <th class="border px-4 py-2">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['keranjang'] as $item): ?>
                            <tr>
                                <td class="border px-4 py-2"><?= $item['namaBarang'] ?></td>
                                <td class="border px-4 py-2"><?= $item['harga'] ?></td>
                                <td class="border px-4 py-2"><?= $item['jumlah'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="border px-4 py-2 font-bold">Total Harga</td>
                            <td class="border px-4 py-2 font-bold">Rp<?= number_format(isset($_SESSION['totalHarga']) ? $_SESSION['totalHarga'] : '', 0, ',', '.') ?>,00</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="flex justify-start items-center gap-3 mb-4">
                    <label for="namaPembeli">Nama Pembeli</label>
                    <input type="text" name="namaPembeli" value="<?= isset($_POST['namaPembeli']) ? $_POST['namaPembeli'] : '' ?>" id="namaPembeli" class="px-4 py-2 rounded-lg w-40">
                </div>
                <div class="flex justify-start items-center gap-3 mb-4">
                    <label for="bayartah">Jumlah Bayar</label>
                    <input type="text" name="bayartah" id="bayartah" class="px-4 py-2 rounded-lg w-40" value="<?= isset($_POST['bayartah']) ? $_POST['bayartah'] : '' ?>">
                    <button type="submit" name="hitung" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Hitung</button>
                </div>
                <div class="flex justify-start items-center gap-3 mb-4">
                    <label for="kembalian">Kembalian</label>
                    <input type="text" name="kembalian" id="kembalian" readonly value="<?= isset($kembalian) ? $kembalian : '' ?>" class="px-4 py-2 rounded-lg w-40">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="window.location.href='index.php';" class="bg-blue-500 text-white px-4 py-2 rounded-lg mr-2">Batal</button>
                    <button type="submit" name="simpankan" onclick="window.open('cetak_struk.php', '_blank')"<?php mysqli_close($koneksi); ?> class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
</body>

</html>
<?php } ?>