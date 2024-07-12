<?php
session_start();
include 'koneksi.php';
if(isset($_GET)){
  $idBarang = $_GET['idBarang'];
  $query="select * from produk where idBarang = '$idBarang'";
  $result=mysqli_query($koneksi,$query);
$row=mysqli_fetch_array($result);
$foto=$row['foto'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan'])) {
  // Retrieve data from form
  $namaBarang = $_POST['namaBarang'];
  $harga = (int) $_POST['harga'];
  $deskripsi = $_POST['deskripsi'];
  $stok = (int) $_POST['stok'];
  
  // Check if a file was uploaded
  if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
      $fileName = $_FILES['gambar']['name'];
      $fileSize = $_FILES["gambar"]["size"];
      $error = $_FILES["gambar"]["error"];
      $tmpName = $_FILES["gambar"]["tmp_name"];

      // Validasi dan proses upload gambar
      if ($error == UPLOAD_ERR_OK) {
          if (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['jpg', 'png', 'jpeg'])) {
              echo "<script> alert('Yang Anda upload bukan gambar')</script>";
          } elseif ($fileSize > 10000000) {
              echo "<script> alert('Gambar terlalu besar')</script>";
          } else {
              $fileName = uniqid() . '.' . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
              move_uploaded_file($tmpName, 'images/' . $fileName);
          }
      } else {
          echo "<script> alert('Terjadi kesalahan saat mengupload gambar')</script>";
      }
  } else {
      // Jika tidak ada file yang diupload, gunakan foto yang ada di database
      $fileName = ''; // Atur $fileName menjadi string kosong untuk query update
  }

  // Update query
  $updateQuery = "UPDATE produk SET namaBarang = '$namaBarang', harga = $harga, deskripsi = '$deskripsi', stok = $stok";
  if (!empty($fileName)) {
      $updateQuery .= ", foto = '$fileName'";
  }
  $updateQuery .= " WHERE idBarang = $idBarang";

  if (mysqli_query($koneksi, $updateQuery)) {
      header('Location: admin.php#barang');
      exit;
  } else {
      echo "<script> alert('Gagal melakukan update')</script>";
  }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['batal'])){
    header('Location: admin.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aman.Store - Edit Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f3f4f6;
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
        } .form-group .hahai {
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
        }
    </style>
</head>

<body>
    <nav class="bg-blue-200 border-gray-200 dark:bg-gray-900 dark:border-gray-700">
        <div class="max-w-screen-xl bg-blue-200 flex flex-wrap items-center justify-between mx-auto p-4 rounded">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="gambar/shop.jpg" class="h-9 w-10" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Aman.Store</span>
            </a>
        </div>
    </nav>

    <div class="form-container">
        <h2>Edit Barang</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="namaBarang">Nama Barang:</label>
                <input type="text" id="namaBarang" name="namaBarang" value="<?= $row['namaBarang']?>" required>
            </div>
            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="number" id="harga" name="harga" value="<?=  $row['harga'] ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" required><?= $row['deskripsi']?></textarea>
            </div>
            <div class="form-group">
                <label for="stok">Stok:</label>
                <input type="number" id="stok" name="stok" value="<?=  $row['stok']?>" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                
                    <div class="current-image">
                        <img src="images/<?= $row['foto'] ?>" alt="Current Image">
                    </div>
                <input type="file" id="foto" name="gambar">
                <p class="image-preview" id="imagePreview"></p>
            </div>
            <div class="flex justify-between">
            <div class="form-group">
                <button type="submit" class="submit-button" name="simpan">Simpan Perubahan</button>
            </div><div class="form-group">
                <button name="batal" type="submit" class="hahai">Batal</button>
            </div></div>
            
        </form>
    </div>
</body>

</html>
<?php } ?>