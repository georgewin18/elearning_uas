<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$level = $_SESSION['level'];
$nrp = $_SESSION['nrp'];

include 'connect.php';

$sql = "SELECT * FROM userdata WHERE nrp='$nrp';";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$srcfoto = $row['foto'];
if ($srcfoto == '') {
    $srcfoto = "https://cdn.pixabay.com/photo/2018/11/13/21/43/avatar-3814049_640.png";
}

$id = $_SESSION['id'];

$sql = "SELECT * FROM courses WHERE course_id='$id';";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

$course = $row["course_name"];
$nip = $row["lecturer_id"];
$jadwal = $row["schedule"];

$sql = "SELECT * FROM userdata WHERE nrp='$nip';";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

$nama_dosen = $row["nama"];

if (isset($_POST['detail_tugas'])) {
    $_SESSION['tugas_id'] = $_POST['tugas_id'];
    header("Location: tugas.php");
}

if (isset($_POST['create_tugas'])) {
    $tid = $_POST['tugas_id'];
    $judul = $_POST['judul_tugas'];
    $detail = $_POST['detail_tugas'];

    $sql = "INSERT INTO tugas(id,judul,detail,course_id)
            VALUES('$tid','$judul','$detail','$id');";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: course.php');
    } else {
        echo "Gagal, Error : " . mysqli_error($conn);
    }
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

    <div class="container mt-2">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?php echo $course; ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th>NIP</th>
                                    <td>: <?php echo $nip; ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Dosen</th>
                                    <td>: <?php echo $nama_dosen; ?></td>
                                </tr>
                                <tr>
                                    <th>Jadwal</th>
                                    <td>: <?php echo $jadwal; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                <?php
                if ($level == "dosen" && $nrp == $nip) { 
                    echo '<div class="mt-2">
                        <button class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#create">Buat Tugas Baru</button>
                    </div>';
                }
                ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Assignments</h4>
                        <hr>
                        <?php
                            $sql = "SELECT * FROM tugas WHERE course_id='$id';";
                            $res = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    echo '<div class="card mt-3">
                                        <div class="card-header">
                                            <h5 class="card-title">'.$row['judul'].'</h5>
                                        </div>
                                        <div class="card-body">
                                            <p>'.$row['detail'].'</p>
                                        </div>';
                                        if ($level != 'dosen') {
                                            echo '<div class="card-footer d-flex justify-content-between align-items-center">';
                                            
                                            $tugas_id = $row['id'];
                                            $sql_badge = "SELECT * FROM store_tugas WHERE nrp_mhs='$nrp' AND tugas_id='$tugas_id';";
                                            $res_badge = mysqli_query($conn, $sql_badge);
                                            if (mysqli_num_rows($res_badge) == 0) {
                                                echo '<span class="badge rounded-pill text-bg-danger">Belum mengumpulkan</span>';
                                            } else {
                                                echo '<span class="badge rounded-pill text-bg-success">Sudah mengumpulkan</span>';
                                            }
                                        } else {
                                            echo '<div class="card-footer d-flex justify-content-end align-items-center">';
                                        }
                                    echo '<form action="" method="post">
                                                <button class="btn btn-sm btn-outline-primary" type="sumbit" name="detail_tugas">Detail</button>
                                                <input type="hidden" value="'.$row['id'].'" name="tugas_id">
                                            </form>
                                        </div>
                                    </div>';
                                } 
                            } else {
                                echo '<div class="text-center mt-3">
                                    <p>Belum ada tugas</p>
                                </div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="create">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Buat Tugas Baru</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                    <input class="form-control mb-2" type="text" name="tugas_id" placeholder="Masukkan ID Tugas ex. T202...">
                        <input class="form-control mb-2" type="text" name="judul_tugas" placeholder="Masukkan Judul">
                        <textarea class="form-control" rows="5" name="detail_tugas" placeholder="Massukkan Detail Tugas"></textarea>
                        <div class="mt-3 gap-2 justify-content-sm-end">
                            <input type="submit" value="Buat Tugas" class="btn btn-primary w-100 " name="create_tugas">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>