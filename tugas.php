<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$level = $_SESSION['level'];
$nrp = $_SESSION['nrp'];
$message = '';

include 'connect.php';

$tid = $_SESSION['tugas_id'];
$nilai_siswa = "-";

$sql = "SELECT * FROM tugas WHERE id='$tid';";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

$title = $row['judul'];
$desc = $row['detail'];

$sql = "SELECT * FROM userdata WHERE nrp='$nrp';";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$srcfoto = $row['foto'];
if ($srcfoto == '') {
    $srcfoto = "https://cdn.pixabay.com/photo/2018/11/13/21/43/avatar-3814049_640.png";
}

$sql_badge = "SELECT * FROM store_tugas WHERE nrp_mhs='$nrp' AND tugas_id='$tid';";
$res_badge = mysqli_query($conn, $sql_badge);
if (mysqli_num_rows($res_badge) == 1) {
    $row = mysqli_fetch_assoc($res_badge);
    $filename = $row['filename'];
    $path = $row['path'];
    $nilai_siswa = $row['nilai'];
    if ($nilai_siswa == NULL) {
        $nilai_siswa = "-";
    }
}

if (isset($_POST['upload'])) {
    $filename = $_FILES["file"]["name"];
    $tempname = $_FILES["file"]["tmp_name"];
    $folder = "tugas/" . $filename;

    $sql = "INSERT INTO store_tugas(nrp_mhs,filename, path,tugas_id)
            VALUES ('$nrp','$filename','$folder','$tid');";
    mysqli_query($conn, $sql);

    if (!move_uploaded_file($tempname, $folder)) {
        $message = "Gagal upload file!";
    } else {
        header('Location: tugas.php');
    }
}

if (isset($_POST['beri_nilai'])) {
    $nilai = $_POST['nilai'];
    $nrp_mhs = $_POST['nrp_mhs'];

    $sql = "UPDATE store_tugas SET nilai='$nilai'
            WHERE nrp_mhs='$nrp_mhs' AND tugas_id='$tid';";

    if (mysqli_query($conn, $sql)) {
        header('Location: tugas.php');
    } else {
        echo "Gagal, Error : " . mysqli_error($conn);
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
                        <a href="dashboard.php" class="nav-link">Dashboard</a>
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
                            <li><a href="profile.php" class="dropdown-item">Profile</a></li>
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

    <div class="container-fluid justify-content-center mt-2 w-75">
        <div class="row">
            <div class="col-lg-7">
                <div class="card mt-2 mb-3">
                    <div class="card-header">
                        <h2 class="card-title"><?php echo $title; ?></h2>
                    </div>
                    <div class="card-body p-2 mt-2">
                        <h6>Detail :</h6>
                        <p><?php echo $desc; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card mt-2 mb-3">
                    <?php
                    if ($level != 'dosen') {
                        echo '<div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Tugas Anda</h4>';
                                if (mysqli_num_rows($res_badge) == 0) {
                                    echo '<span class="badge rounded-pill text-bg-danger">Belum mengumpulkan</span>';
                                } else {
                                    echo '<span class="badge rounded-pill text-bg-success">Sudah mengumpulkan</span>';
                                }
                        echo '</div>
                            Lampiran :
                            <div class="card mb-3">
                                <div class="card-body d-block justify-content-center align-items-center">';
                                    if (mysqli_num_rows($res_badge) == 0) {
                                        echo '<p>
                                            Belum Upload file   
                                        </p>';
                                    } else {
                                        echo '<div class="row g-0">
                                            <div class="col-xxl-8 mb-2 text-truncate">'.$filename.'</div>              
                                            <div class="col-xxl-4 d-flex flex-row-reverse">
                                                <a class="btn btn-primary" href="download.php?id='.$path.'">Download</a>
                                            </div>
                                        </div>';
                                    }
                        echo '</div>
                            </div>
                            Nilai:
                            <p>'.$nilai_siswa.'</p>
                            <button class="btn btn-primary btn-lg w-100 mt-2" type="button" data-bs-toggle="modal" data-bs-target="#upload">Upload</button>
                        </div>';
                    } else {
                        echo '<div class="card-body">
                                <div class="text-center">
                                    <h4>Tugas Siswa</h4>
                                </div>
                                <table class="table table-hover">
                                    <thead>
                                        <th>NRP</th>
                                        <th>File</th>
                                        <th>Nilai</th>
                                    </thead>
                                    <tbody>';
                                        include 'connect.php';

                                        $sql = "SELECT * FROM store_tugas WHERE tugas_id='$tid' ORDER BY nrp_mhs;";
                                        $res = mysqli_query($conn, $sql);

                                        if (mysqli_num_rows($res) > 0) {
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                $nilai_siswa = $row['nilai'];
                                                if ($nilai_siswa == NULL) {
                                                    $nilai_siswa = "-";
                                                }

                                                echo '<tr>';
                                                echo '<td>' . $row["nrp_mhs"] . '</td>';
                                                echo '<td>
                                                        <a class="btn btn-primary btn-sm" href="download.php?id=' . $row['path'] . '">Download</a>
                                                    </td>';
                                                echo '<td width="40%">
                                                        <form method="post" action="" class="d-flex justify-content-between">
                                                            <input type="text" value="'.$nilai_siswa.'" class="form-control-sm w-25" name="nilai">
                                                            <input type="hidden" value="'.$row['nrp_mhs'].'" name="nrp_mhs">
                                                            <button class="btn btn-primary btn-sm align-self-start" type="submit" name="beri_nilai">Beri Nilai</button>
                                                        </form>
                                                    </td>
                                                </tr>';
                                            }
                                        }
                        echo '</tbody>
                                </table>
                            </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="upload">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload File</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="text-center">
                            <input class="form-control" type="file" name="file">
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
                            <div class="mt-3 gap-2 justify-content-sm-end">
                                <input type="submit" value="Upload" class="btn btn-primary w-100 " name="upload">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>