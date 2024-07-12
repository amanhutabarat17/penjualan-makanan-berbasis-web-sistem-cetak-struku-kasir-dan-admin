<?php
include 'koneksi.php';
$result = mysqli_query($koneksi, 'SELECT SUM(totalharga) AS value_sum FROM pelanggan'); 
$row = mysqli_fetch_assoc($result); 
$sum = $row['value_sum'];

$result1 = mysqli_query($koneksi, 'SELECT SUM(stok) AS value_sum FROM produk'); 
$row1 = mysqli_fetch_assoc($result1); 
$sum1 = $row1['value_sum'];

$result2 = mysqli_query($koneksi, 'SELECT SUM(jumlahBarang) AS value_sum FROM penjualan'); 
$row2 = mysqli_fetch_assoc($result2); 
$sum3 = $row2['value_sum'];
if (isset($_POST['submit'])) {
  $namaBarang = $_POST['namaBarang'];
  $harga = $_POST['harga'];
  $deskripsi = $_POST['deskripsi'];
  $stok = $_POST['stok'];
  $fileName = $_FILES['gambar']['name'];
  $fileSize = $_FILES["gambar"]["size"];
  $error = $_FILES["gambar"]["error"];
  $tmpName = $_FILES["gambar"]["tmp_name"];

  // Validasi dan proses upload gambar
  if ($error == 4) {
    echo "<script> alert('Masukkan Gambar')</script>";
  } elseif (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['jpg', 'png', 'jpeg'])) {
    echo "<script> alert('Yang Anda upload bukan gambar')</script>";
  } elseif ($fileSize > 10000000) {
    echo "<script> alert('Gambar terlalu besar')</script>";
  } else {
    $fileName = uniqid() . '.' . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    move_uploaded_file($tmpName, 'images/' . $fileName);

    $query = "INSERT INTO produk (namaBarang, harga, deskripsi, stok, foto) 
              VALUES ('$namaBarang', '$harga', '$deskripsi', '$stok', '$fileName')";
    $result = mysqli_query($koneksi, $query);
    if ($result) {
      echo "<script> alert('Berhasil');
           window.location.href = 'admin.php';
           </script>";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #393646;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-top: 20px;
        }

        .form-container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 18px;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input[type=text],
        .form-group input[type=number],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .form-group .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-group input[type=file] {
            margin-top: 10px;
        }

        .form-group .current-image {
            margin-top: 10px;
        }

        .form-group img {
            max-width: 200px;
            height: auto;
            display: block;
            margin-top: 10px;
        }

        .form-group .image-preview {
            margin-top: 10px;
            max-width: 200px;
            height: auto;
            display: block;
        }

        .form-group .submit-button {
            margin-top: 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        body{
          background-color: #393646;
        }.navi,nav{
          background-color: #1c1917;
        }.contan{
          background-color: #1c1917;
        }
    </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="nav border-gray-200 dark:bg-gray-900 dark:border-gray-700">
    <div class="navi max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <span class="text-white">Selamat Datang, Anda login sebagai Admin</span>
      <button data-collapse-toggle="navbar-dropdown" type="button"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-dropdown" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>
      <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
        <ul
          class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
          <li>
            <a href="#home"
              class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500 dark:bg-blue-600 md:dark:bg-transparent">Home</a>
          </li>
          <li>
            <a href="#barang"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Input
              Makanan</a>
          </li>
          <li>
            <a href="#data"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Data
              Makanan</a>
          </li>
          <li>
            <a href="#laporan"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Laporan
              Penjualan</a>
          </li>
          <li>
            <a href="logout.php"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">LOGOUT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->

  <!-- Statistics Section -->
   <section id="home" class="pr-20 pl-20 ml-20 mr-20">
  <div class="flex justify-center p-4 mt-8 gap-5 bg-burlywood-100 mx-3 rounded ">
    <div class="flex justify-center gap-5">
      <div class="flex items-center justify-center w-1/2 h-60 bg-yellow-200 rounded text-center">
        <span class="text-2xl font-bold text-gray-800 dark:text-gray-300">Jumlah Barang Tersedia <p><?= $sum1 ?></p></span>
      </div>
      <div class="flex items-center justify-center w-1/2 h-60 bg-blue-200 rounded text-center">
        <span class="text-2xl font-bold text-gray-800 dark:text-gray-300">Pendapatan<p>Rp<?= number_format( $sum , 0, ',', '.') ?>,00</p></span>
      </div>
      <div class="flex items-center justify-center w-1/2 h-60 bg-red-200 rounded text-center">
        <span class="text-2xl font-bold text-gray-800 dark:text-gray-300">Jumlah Barang Keluar<p><?= $sum3 ?></p></span>
      </div>
    </div>
  </div>
  </section>
  <!-- End Statistics Section -->

  <!-- Form Section -->
  <section id="barang">
    <div class="contan py-2 px-2 r-2 l-2 ">
  <div class="form-container text-yellow-300">
        <h2>Tambah Makanan</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="namaBarang">Nama Makanan:</label>
                <input type="text" id="namaBarang" name="namaBarang"required>
            </div>
            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="number" id="harga" name="harga" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Keterangan:</label>
                <textarea id="deskripsi" name="deskripsi" required></textarea>
            </div>
            <div class="form-group">
                <label for="stok">Stok:</label>
                <input type="number" id="stok" name="stok" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" id="foto" name="gambar">
                <p class="image-preview" id="imagePreview"></p>
            </div>
        <center><button type="submit" name="submit"
            class="text-black bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            name="submit">Simpan</button></center>
      </form>
    </div>
    </div>
    </section>
  <!-- End Form Section -->

  <!-- Data Barang Section -->
  
  <section id="data" class="mt-8 pr-1 pl-1">
    <div class="overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-gray-800 p-5 border-y-2 ">
      <div class="flex justify-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-300">DATA-DATA MAKANAN</h1>
      </div>
      <div class="mt-4">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border-collapse">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Nama Makanan
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Id Makanan
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Harga
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Deskripsi
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Stok
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Gambar
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Action
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "SELECT * FROM produk";
            $result = mysqli_query($koneksi, $query);
            while ($row = mysqli_fetch_array($result)) {
              ?>
              <tr class="border-b dark:border-gray-600">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                  <?php echo $row['namaBarang']; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  <?php echo $row['idBarang']; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  <?php echo $row['harga']; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  <?php echo $row['deskripsi']; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  <?php echo $row['stok']; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  <img src="images/<?php echo $row['foto']; ?>" alt="Gambar Barang"
                    class="w-24 h-16 object-cover rounded-lg">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  <a href="edit.php?idBarang=<?php echo $row['idBarang']; ?>"
                    class="text-blue-700 hover:text-blue-900 dark:text-blue-500 dark:hover:text-blue-700 mr-2">Edit</a>
                  <a href="hapus.php?idBarang=<?php echo $row['idBarang']; ?>"
                    class="text-yellow-700 hover:text-yellow-900 dark:text-yellow-500 dark:hover:text-yellow-700">Hapus</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="flex justify-end p-1">
        <a type="button" href="cetak2.php" class="bg bg-blue-200 rounded w-20 text-center">cetak</a>
      </div>
      </div>
    </div>
  </section>
  <!-- End Data Barang Section -->

  <!-- Laporan Penjualan Section -->
  <section id="laporan" class="mt-8 mb-5 pr-1 pl-1">
    <div class="overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-gray-800 p-5 border-y-2">
      <div class="flex justify-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-300">LAPORAN PENJUALAN</h1>
      </div>
      <div class="mt-4">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border-collapse">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Id Penjualan
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Nama Makanan
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Nama Pelanggan
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Kasir
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Jumlah Barang
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Total Belanja
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Jumlah Bayar
              </th>
              <th scope="col" class="px-6 py-3 border-b-2 border-gray-200 dark:border-gray-600">
                Kembalian
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
$query = "SELECT 
            idpenjualan,namapelanggan,
            GROUP_CONCAT(namaBarang SEPARATOR ', ') as namaMakanan,
            username as kasir,
            SUM(jumlahBarang) as jumlahBarang,
            totalharga as totalBelanja,
            jumlahbayar as totalBayar,
            kembalian as totalKembalian
          FROM penjualan 
          INNER JOIN users ON penjualan.id = users.id 
          INNER JOIN produk ON penjualan.idBarang = produk.idBarang 
          INNER JOIN pelanggan ON penjualan.idPelanggan = pelanggan.idPelanggan 
          GROUP BY namapelanggan";
$result = mysqli_query($koneksi, $query);

while ($row = mysqli_fetch_array($result)) {
?>
<tr class="border-b dark:border-gray-600">
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
    <?php echo $row['idpenjualan'];?>
  </td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    <?php echo $row['namaMakanan']; ?>
  </td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    <?php echo $row['namapelanggan']; ?>
  </td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    <?php echo $row['kasir']; ?>
  </td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    <?php echo $row['jumlahBarang']; ?>
  </td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    <?php echo $row['totalBelanja']; ?>
  </td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    <?php echo $row['totalBayar']; ?>
  </td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    <?php echo $row['totalKembalian']; ?>
  </td>
</tr>
<?php } ?>

          </tbody>
        </table>
      </div>
      <div class="flex justify-end p-1">
        <a type="button" href="cetak1.php" class="bg bg-blue-200 rounded w-20 text-center">cetak</a>
      </div>
    </div>
  </section>
  <!-- End Laporan Penjualan Section -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>