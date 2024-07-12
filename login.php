<?php
session_start();
if(isset($_SESSION['username'])) {
    
    if($_SESSION['role'] == 'admin') {
        header("Location: admin.php");
        exit;
    } elseif ($_SESSION['role'] == 'kasir') {
        header("Location: index.php");
        exit;
    }
}
require_once "koneksi.php";

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        
        if($_SESSION['role'] == 'admin') {
            header("Location: admin.php"); 
            exit;
        } else {
           
            header("Location: index.php");
            exit;
        }
    } else {
        $error = "Username or password is incorrect";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style/css/style1.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if(isset($error)) { ?>
            <div><?php echo $error; ?></div>
        <?php } ?>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <a href="register.php">Belum Memiliki Akun? Register</a>
    </div>
</body>
</html>
