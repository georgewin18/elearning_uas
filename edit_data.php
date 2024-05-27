<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$enrp = $_POST['edit_nrp'];
$isChecked1 = $isChecked2 = '';

include 'connect.php';
if(!empty($enrp)) {
    $sql = "SELECT * FROM userdata WHERE nrp='$enrp';";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    $nrp = $row['nrp'];
    $nama = $row['nama'];
    $j_kelamin = $row['jenis_kelamin'];
    if ($j_kelamin == 'Laki-laki') {
        $isChecked1 = 'checked';
    } else {
        $isChecked2 = 'checked';
    }
    $agama = $row['agama'];
    $tempat_lahir = $row['tempat_lahir'];
    $tanggal_lahir = $row['tanggal_lahir'];
    $tanggal_lahir = date('Y-m-d', strtotime($tanggal_lahir));
    $no_telp = $row['no_telp'];
    $email = $row['email'];
    $alamat = $row['alamat'];
    $usn = $row['usn'];
}

if (isset($_POST['edit'])) {
    $nrp = $_POST['nrp'];
    $nama = $_POST['nama'];
    $j_kelamin = $_POST['jenis_kelamin'];
    $agama = $_POST['agama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $tanggal_lahir = date("d-m-Y", strtotime($tanggal_lahir));
    $tempat_lahir = $_POST['tempat_lahir'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $usn = $_POST['usn'];
    $pwd = md5($_POST['pwd']);

    $sql = "UPDATE userdata SET 
                nama='$nama',
                jenis_kelamin='$j_kelamin',
                agama='$agama',
                tanggal_lahir='$tanggal_lahir',
                tempat_lahir='$tempat_lahir',
                no_telp='$no_telp',
                email='$email',
                alamat='$alamat',
                usn='$usn',
                pwd='$pwd'
                WHERE nrp='$nrp'";

    if (mysqli_query($conn, $sql)) {
        header('Location: profile.php');
    } else {
        echo "Gagal, Error : " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Edit</title>
</head>
<body>
    <div class="container mt-3">
        <div class="text-center">
            <h2>Edit Data</h2>
        </div>
        <form action="" method="post">
            <div class="mt-4 mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="nrp" class="form-label">NRP :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" value=<?php echo "'$nrp'" ?> class="form-control" id="nrp" placeholder="Masukkan NRP" name="nrp" readonly>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="Nama" class="form-label">Nama :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" value=<?php echo "'$nama'" ?> class="form-control" id="nama" placeholder="Masukkan Nama" name="nama" required> 
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label class="form-label">Jenis Kelamin :</label>
                    </div>
                    <div class="col-sm-3">
                        <div class="row g-3">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="Laki-laki" <?php echo $isChecked1?>>
                                    <label class="form-check-label" for="laki">Laki-laki</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan" <?php echo $isChecked2?>>
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="agama" class="form-label">Agama :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" value=<?php echo "'$agama'" ?> class="form-control" id="agama" placeholder="Masukkan Agama" name="agama" required>
                    </div>
                </div>                
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="date" value=<?php echo "'$tanggal_lahir'" ?> class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" value=<?php echo "'$tempat_lahir'" ?> class="form-control" id="tempat_lahir" placeholder="Masukkan Tempat Lahir" name="tempat_lahir" required>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="no_telp" class="form-label">Nomor Telepon :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" value=<?php echo "'$no_telp'" ?> class="form-control" id="no_telp" placeholder="Masukkan Nomor Telepon" name="no_telp" required>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="email" class="form-label">Email :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" value=<?php echo "'$email'" ?> class="form-control" id="email" placeholder="Masukkan Email" name="email" required>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="alamat" class="form-label">Alamat :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" value=<?php echo "'$alamat'" ?> class="form-control" id="alamat" placeholder="Masukkan Alamat" name="alamat" required>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="usn" class="form-label">Username :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" value=<?php echo "'$usn'" ?> class="form-control" id="usn" placeholder="Masukkan Username" name="usn" required>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="pwd" class="form-label">Password :</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="pwd" placeholder="Masukkan Password Baru" name="pwd" required>
                    </div>
                </div>
            </div>
            <br>
            <div class="text-center">
                <button type="submit" id="edit" name="edit" value="edit" class="btn btn-primary btn-lg">Edit Data</button>
            </div>
        </form>
    </div>
</body>
</html>