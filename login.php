<!DOCTYPE html>
<?php
session_start();
$errorMsg = '';

include 'connect.php';
if (isset($_POST['login'])) {
    $usn = $_POST['usn'];
    $pwd = md5($_POST['pwd']);

    $sql = "SELECT nrp, level FROM userdata WHERE usn='$usn' AND pwd='$pwd';";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    if (mysqli_num_rows($res) == 1) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['nrp'] = $row['nrp'];
        $_SESSION['level'] = $row['level'];
        header('Location: dashboard.php');
    } else {
        $errorMsg = "username / password salah!";
    }
}
mysqli_close($conn);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>E-Learning</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5 col-lg-6" style="height: 100vh;">
                <div class="container-sm shadow w-75 d-flex justify-content-center align-items-center" style="height: 100vh;">
                    <div class="text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/id/4/44/Logo_PENS.png" alt="" width="180">
                        <h3 class="mt-5 mb-3">Login</h3>
                        <hr>
                        <form action="" method="post">
                        <input type="text" class="form-control form-control-lg mb-2" placeholder="Masukkan Username" name="usn" required>
                        <input type="password" class="form-control form-control-lg mb-2" placeholder="Masukkan Password" name="pwd" required>
                        <?php
                        if ($errorMsg != '') {
                            echo '<p align="center">
                                <strong>
                                    <font color="#FF0000">
                                        '.$errorMsg.'
                                    </font>
                                </strong>
                            </p>';
                        }
                        ?>
                        <button class="btn btn-lg btn-primary mt-4 mb-4 w-100" type="submit" name="login">Login</button>
                        <p>Belum punya akun? <a href="daftar.php">Daftar sekarang!</a></p>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-lg-6" style="background-image: url(https://shine.co.id/wp-content/uploads/2022/12/Politeknik-Elektronik-Negeri-Surabaya-Surabaya.jpg); background-repeat: no-repeat; background-size: cover;">
            </div>
        </div>
    </div>
</body>
</html>