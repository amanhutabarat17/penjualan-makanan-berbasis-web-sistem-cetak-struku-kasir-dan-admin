<?php
include 'koneksi.php';

if (isset($_GET['idBarang'])) {
    $id = $_GET['idBarang'];

    $query = "DELETE FROM produk WHERE idBarang='$id'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Data berhasil dihapus');</script>";
        header("Location: admin.php#barang");
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}