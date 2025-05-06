
<?php
session_start();
require_once 'connect-db.php';    //sh incloud ka dai

if(isset($_POST['btnLogin'])) {
    $username = mysqli_real_escape_string($link, $_POST['email']);
    $password = md5(mysqli_real_escape_string($link, $_POST['pwd']));
    $pwd = $_POST['pwd'];// ເກັບໄວ້ຄ້າງຟອມກໍລະນີລັອກອິນບໍ່ຜ່ານ

    $sql = "SELECT *FROM users WHERE username='$username' AND password= '$password' ";
    $result = mysqli_query($link, $sql);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['name'] = $row['name'];
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header('location: index.php');

    } else {
        $message = '<script>swal("ຜິດພາດ", "ບັນຊີເຂົ້າໃຊ້ ແລະ ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ","error",{button: "ຕົກລົງ"})</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>ລະບົບຈັດການຂໍ້ມູນພະນັກງານ</title>
    <link rel="icon" href="images/icon_logo.jpg">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/mystyle.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

    <script src="js/sweetalert.min.js"></script>

</head>

<body class="sb-nav-fixed">
    <!-- ສະແດງ message ແຈ້ງເຕືອນ -->
    <?= @$message ?>

    <!-- ດຶງເມນູເຂົ້າມາ  -->
    <?php include_once 'menu.php' ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="row mt-4">
                    <div class="offset-md-3 col-md-6">
                        <div class="card" style="margin-top: 20%;">
                            <div class="card-header bg-info h4 text-center">ຟອມເຂົ້າໃຊ້ລະບົບ</div>
                            <div class="card-body" style="background-color: #E7E9EB">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">ບັນຊີເຂົ້າໃຊ້ (Email):</label>
                                        <input type="email" class="form-control" id="email" placeholder="ປ້ອນ email" name="email" value="<?= @$username ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pwd" class="form-label">ລະຫັດຜ່ານ:</label>
                                        <input type="password" class="form-control" id="pwd" placeholder="ປ້ອນລະຫັດຜ່ານ" name="pwd" value="<?= @$pwd ?>" required>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" name="btnLogin" class="btn btn-primary btn-block">ເຂົ້າໃຊ້ງານ</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- footer -->
        <?php include_once 'footer.php' ?>

    </div>

</body>

</html>
<script>
    /* ບໍ່ໃຫ້ມັນຊັບມິດຄືນ*/
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>