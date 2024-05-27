<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

include 'connect.php';

$nrp = $_SESSION['nrp'];
$message = '';

if (!empty($nrp)) {
    $sql = "SELECT * FROM userdata WHERE nrp='$nrp';";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    $nrp = $row['nrp'];
    $nama = $row['nama'];
    $j_kelamin = $row['jenis_kelamin'];
    $agama = $row['agama'];
    $tempat_lahir = $row['tempat_lahir'];
    $tanggal_lahir = $row['tanggal_lahir'];
    $no_telp = $row['no_telp'];
    $email = $row['email'];
    $alamat = $row['alamat'];
    $srcfoto = $row['foto'];

    if ($srcfoto == '') {
        $srcfoto = "https://cdn.pixabay.com/photo/2018/11/13/21/43/avatar-3814049_640.png";
    }
}

if (isset($_POST['upload'])) {
    $filename = $_FILES["foto"]["name"];
    $tempname = $_FILES["foto"]["tmp_name"];
    $folder = "images/" . $filename;

    $sql = "UPDATE userdata SET foto='$folder'
            WHERE nrp='$nrp'";
    mysqli_query($conn, $sql);

    if (!move_uploaded_file($tempname, $folder)) {
        $message = "Gagal upload foto!";
    } else {
        header('Location: profile.php');
    }
}

if (isset($_POST['delFoto'])) {
    $sql = "UPDATE userdata SET foto='' WHERE nrp='$nrp'";
    mysqli_query($conn, $sql);

    header('Location: profile.php');
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d0e96cede2.js" crossorigin="anonymous"></script>
    <title>E-Learning</title>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand">E-Learning</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="my_course.php" class="nav-link">My Course</a>
                    </li>
                </ul>
                <ul class="navbar-nav d-flex me-2">
                    <li>
                        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offCanvas">
                            <i class="fa-solid fa-bell" style="color: #ffffff;"></i>
                        </button>
                    </li>
                    <li class="nav-item dropstart">
                        <a href="" class="nav-link" id="dropdownMenu" role="button" data-bs-toggle="dropdown">
                            <img src=<?php echo "'$srcfoto'" ?> class="rounded-circle" width="28">
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="dropdown-item">Profile</a></li>
                            <li><a href="" class="dropdown-item">Grades</a></li>
                            <li><div class="dropdown-divider"></div></li>
                            <li><a href="logout.php" class="dropdown-item">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end" tabindex="1" id="offCanvas">
        <div class="offcanvas-header">
            <h5>Notifications</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <p>It's still empty here...</p>
        </div>
    </div>

    <div class="container-fluid mt-3">
        <div class="text-center">
            <h2>Profile</h2>
            <hr>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-6 mb-3">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>NRP</th>
                            <td><?php echo $nrp; ?></td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td><?php echo $nama; ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><?php echo $j_kelamin; ?></td>
                        </tr>
                        <tr>
                            <th>Agama</th>
                            <td><?php echo $agama; ?></td>
                        </tr>
                        <tr>
                            <th>Tempat Lahir</th>
                            <td><?php echo $tempat_lahir; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td><?php echo $tanggal_lahir; ?></td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td><?php echo $no_telp; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $email; ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?php echo $alamat; ?></td>
                        </tr>
                    </tbody>
                </table>
                <form action="edit_data.php" method="post">
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary btn-lg">Edit Data</button>
                    <input type="hidden" value=<?php echo "$nrp" ?> name="edit_nrp">
                    <input type="hidden" value="0" name="back_val">
                </form>
            </div>
            <div class="col-md-4 mb-3">
                <img src=<?php echo "'$srcfoto'" ?> class="img-thumbnail mx-auto d-block" width="320">
                <div class="text-center mt-3">
                    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="modal" data-bs-target="#uploadModal">Ubah Foto</button>
                    <form action="" method="post">
                        <input class="btn btn-danger" type="submit" name="delFoto" value="Hapus Foto">
                        <input type="hidden" name="enrp" value=<?php echo "'$nrp'" ?>>
                    </form>
                </div>
            </div>
        </div> 
    </div>
    <div class="modal" id="uploadModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload Foto</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="text-center">
                            <input class="form-control" type="file" name="foto">
                            <input type="hidden" name="enrp" value=<?php echo "'$nrp'" ?>>
                            <?php
                            if ($message != "") {
                                echo '<p align="center">
                                    <strong>
                                        <font color="#FF0000">
                                            '.$message.'
                                        </font>
                                    </strong>
                                </p>';
                            }
                            ?>
                            <div class="mt-3 d-grid gap-2 d-sm-flex justify-content-sm-end">
                                <input type="submit" value="Upload" class="btn btn-primary" name="upload">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>