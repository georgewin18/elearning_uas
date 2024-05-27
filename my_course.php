<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$nrp = $_SESSION['nrp'];

include 'connect.php';

$sql = "SELECT * FROM userdata WHERE nrp='$nrp';";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$srcfoto = $row['foto'];
if ($srcfoto == '') {
    $srcfoto = "https://cdn.pixabay.com/photo/2018/11/13/21/43/avatar-3814049_640.png";
}

if (isset($_POST['course'])) {
    $_SESSION['id'] = $_POST['id'];
    header("Location: course.php");
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
                        <a href="#" class="nav-link active">My Course</a>
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
        <h2>My Courses</h2>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    <?php
                        include 'connect.php';

                        $sql = "SELECT * FROM courses ORDER BY course_id;";
                        $res = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<form action="" method="post">
                                    <button class="btn btn-link" type="submit" name="course">
                                        <div class="card">
                                            <img src="https://www.signtronix.com/wp-content/uploads/2016/07/Create-Signs-with-Eye-Catching-Colors-to-Promote-Your-Business-7-20.jpg" class="card-img-top" height="137">
                                            <div class="card-body">
                                                <h6>'.$row["course_name"].'<h6>
                                            </div>
                                        </div>
                                    </button>
                                    <input type="hidden" value='.$row["course_id"].' name="id">
                                </form>';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>