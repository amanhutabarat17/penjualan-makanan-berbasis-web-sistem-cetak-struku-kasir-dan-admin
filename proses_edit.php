<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from form
    $idBarang = $_POST['idBarang'];
    $namaBarang = mysqli_real_escape_string($koneksi, $_POST['namaBarang']);
    $harga = (int) $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok = (int) $_POST['stok'];

    // Initialize variables for image management
    $uploadOk = 1;
    $errorMessage = '';

    // Check if a new image file is uploaded
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "images/";
        $targetFile = $targetDir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check === false) {
            $errorMessage = "File yang diunggah bukan gambar.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["foto"]["size"] > 500000) {
            $errorMessage = "Ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $errorMessage = "Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo $errorMessage;
        } else {
            // if everything is ok, try to upload file
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
                // Delete old image if it exists
                $query_select_old_image = "SELECT foto FROM produk WHERE idBarang = $idBarang";
                $result_old_image = mysqli_query($koneksi, $query_select_old_image);
                $row_old_image = mysqli_fetch_assoc($result_old_image);
                $oldImage = $row_old_image['foto'];

                if (!empty($oldImage)) {
                    $oldImagePath = $targetDir . $oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath); // Delete old image file
                    }
                }

                // Update query with new image
                $updateQuery = "UPDATE produk SET namaBarang = '$namaBarang', harga = $harga, deskripsi = '$deskripsi', stok = $stok, foto = '" . basename($_FILES["foto"]["name"]) . "' WHERE idBarang = $idBarang";
                if (mysqli_query($koneksi, $updateQuery)) {
                    header('Location: index.php');
                    exit;
                } else {
                    echo "Gagal Mengupdate: " . mysqli_error($koneksi);
                }
            } else {
                echo "Terjadi kesalahan saat mengunggah file gambar.";
            }
        }
    } else {
        // Update query without new image
        $updateQuery = "UPDATE produk SET namaBarang = '$namaBarang', harga = $harga, deskripsi = '$deskripsi', stok = $stok WHERE idBarang = $idBarang";
        if (mysqli_query($koneksi, $updateQuery)) {
            header('Location: index.php');
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($koneksi);
        }
    }
}

// Close database connection
mysqli_close($koneksi);
?>
