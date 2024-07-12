<?php
require_once "koneksi.php";

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    $query = "INSERT INTO users (username, password,nama, email, role) VALUES ('$username', '$password','$nama', '$email', 'kasir')";
    if(mysqli_query($koneksi, $query)) {
        echo "Registration successful!";
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="gambar/umi.png" rel="icon">
    <link rel="stylesheet" href="style/css/style1.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="text" name="email" placeholder="Emailx" required>
            <button type="submit" name="register">Register</button>
        </form>
        <a href="login.php">Sudah Memiliki Akun? Login</a>
    </div>
</body>
</html>
