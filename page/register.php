<?php
session_start();
include_once 'connect-db.php';
require_once '../function/function.php';

//ຮັບຄ່າຈາກຟ້ອມເມື່ອກົດປຸ່ມ ລົງທະບຽນ
if(isset($_POST['btnRegister'])) {
    $name = data_input($_POST['name']);
    $tel = data_input($_POST['tel']);
    $username = data_input($_POST['username']);
    $pwd = data_input($_POST['pwd']);
    $con_pwd = data_input($_POST['con_pwd']);

    //ກວດສອບ username ມີໃນລະບົບແລ້ວ ຫຼື ບໍ່
    $sql = "SELECT *FROM users WHERE username = '$username'";
    $result = mysqli_query($link, $sql);
    if(mysqli_num_rows($result) > 0){
        $error_username = " ບັນຊີເຂົ້າໃຊ້ນີ້ ຖືກນຳໃຊ້ເເລ້ວ";
    }

    //ກວດລະຫັດຜ່ານ ແລະ ລະຫັດຢືນຢັ້ນກົງກັນ ຫຼື ບໍ່
    if($pwd !== $con_pwd) {
        $error_password = "ລະຫັດຜ່ານ ແລະ ລະຫັດຢືນຢັນບໍ່ກົງກັນ";
    }
    //ກ່ອນເພີ່ມຂໍ້ມູນລົງຖານຂໍ້ມູນຕ້ອງກວດສອບກ່ອນ ຖ້າ $error_username ແລະ $error_password
    if (empty($error_username) && empty($error_password)) {
        $pwd = md5($pwd); //ເຂົ້າລະຫັດຜ່ານ
        $sql = "INSERT INTO users(`name`, `tel`, `username`, `password`) VALUES ('$name', '$tel', '$username', '$pwd')";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $pwd;
            header('location: profile.php');
        } else {
            echo mysqli_error($link);
        }
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
    <link rel="icon" href="../images/icon_logo.jpg">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/mystyle.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>

</head>

<body class="sb-nav-fixed">
    <!-- ດຶງເມນູເຂົ້າມາ  -->
    <?php include_once 'menu.php' ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="row">
                <div class="offset-md-3 col-md-6 mt-4">
                    <fieldset class="border border-primary p-2 px-2 pb-4" style="border-radius: 15px; background-color: #E7E9EB">
                        <legend class="float-none w-auto p-2 h5">ລົງທະບຽນເຂົ້າໃຊ້ລະບົບ</legend>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                            <div class="mb-3">
                                <label for="name" class="form-label">ຊື່ ແລະ ນາມສະກຸນ:</label>
                                <input type="text" class="form-control" id="name" placeholder="ປ້ອນຊື່ ແລະ ນາມສະກຸນ" name="name" value="<?=@$name?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="tel" class="form-label">ເບີໂທລະສັບ:</label>
                                <input type="text" class="form-control" id="tel" placeholder="ປ້ອນເບີໂທລະສັບ" name="tel" value="<?=@$tel?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">ບັນຊີເຂົ້າໃຊ້:</label>
                                <input type="email" class="form-control" id="username" placeholder="ປ້ອນອີເມວບັນຊີເຂົ້າໃຊ້" name="username" value="<?=@$username?>" required>
                                <div class="form-control-feedback">
                                    <div class="text-danger align-middle">
                                        <!-- ແຈ້ງເຕືອນບັນຊີເຂົ້າໃຊ້ຊໍ້າ -->
                                        <?=@$error_username?> 
                                       
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pwd" class="form-label">ລະຫັດຜ່ານ:</label>
                                <input type="password" class="form-control" id="pwd" placeholder="ປ້ອນລະຫັດຜ່ານ" name="pwd" value="<?=@$pwd?>" required>
                                <div class="form-control-feedback">
                                    <div class="text-danger align-middle">
                                        <!-- ແຈ້ງລະຫັດຜ່ານບໍ່ກົງກັນ -->
                                        <?=@$error_password?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="con_pwd" class="form-label">ລະຫັດຜ່ານອີກຄັ້ງ:</label>
                                <input type="password" class="form-control" id="con_pwd" placeholder="ປ້ອນລະຫັດຜ່ານອີກຄັ້ງ" name="con_pwd" value="" required>
                            </div>

                            <button type="submit" name="btnRegister" class="btn btn-primary"><i class="fas fa-registered"></i>&nbsp;ລົງທະບຽນ</button>
                            <button type="reset" class="btn btn-warning"><i class="fas fa-sync-alt"></i>&nbsp;ຍົກເລີກ</button>

                        </form>
                    </fieldset>
                </div>
            </div>
        </main>
        <!-- footer -->
        <?php include_once 'footer.php' ?>

    </div>

</body>

</html>

<script>
    //ບໍ່ໃຫ້ຟອມ submit ຄ່າຄືນໃໝ່
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>