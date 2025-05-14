<?php
include_once 'login-check.php';
require_once '../function/function.php';

//ຮັບຄ່າຈາກ URL
if(isset($_GET['empno'])) {
    $empno = $_GET['empno'];
    $sql = "SELECT *FROM emp WHERE empno='$empno'";
    $result = mysqli_query($link, $sql);
    $row =  mysqli_fetch_assoc($result);

    $empno = $row['empno'];
    $empname = $row['name'];
    $gender = $row['gender'];
    $date_birth = $row['dateOfBirth'];
    $address = $row['address'];
    $incentive = $row['incentive'];
    $language = $row['language'];
    $file_image = is_numeric($row['picture']) ? "avatar_img.png" : $row['picture'];
    $salary = $row['grade'];
    $department = $row['dno'];
}
//ຮັບຄ່າຈາກຟອມເມືອກົດປຸ່ມ btnEdit
if (isset($_POST['btnEdit'])) {
    $empno = data_input($_POST['empno']);
    $empname = data_input($_POST['empname']);
    $gender = $_POST['gender'];
    $date_birth = $_POST['date_birth'];
    $address = nl2br(trim(stripslashes($_POST['address'])));

    //ຮັບຂໍ້ມູນປະເພດຮູບ
    $file_image = $_FILES['file_image']['name'];
    $file_tmp = $_FILES['file_image']['tmp_name'];

    $department = $_POST['department'];
    $salary = $_POST['salary'];
    $incentive = str_replace(".", "", $_POST['incentive']);

    if (empty($_POST['language'])) {
        $language = "";
    } else {
        $language = implode(",", $_POST['language']);
    }

    //ຖ້າບໍ່ເລືອກຮູບໃໝ່
    if (empty($file_image)) {
        $sql = "UPDATE emp SET name='$empname', gender='$gender', "
            . " dateOfBirth='$date_birth', address='$address', incentive='$incentive', "
            . " language='$language', grade='$salary', dno='$department' "
            . " WHERE empno='$empno'";
        if (mysqli_query($link, $sql)) {
            header('location: emp-management.php');
        } else {
            echo mysqli_error($link);
        }
    } else { //ກໍລະນີເລືອກຮູບໃໝ່
        //ປ່ຽນຊື່ຮູບ
        $file_image = round(round(microtime(TRUE))) . $file_image;

        $sql = "UPDATE emp SET name='$empname', gender='$gender', "
            . " dateOfBirth='$date_birth', address='$address', incentive='$incentive', "
            . " language='$language',picture='$file_image', grade='$salary', dno='$department' "
            . " WHERE empno='$empno'";
        if (mysqli_query($link, $sql)) {
            $oldpicture = $_POST['oldpicture'];
            unlink("images/$oldpicture");
            //ຍ້າຍຮູບໄປເກັບໃນໂຟນເດີ images
            move_uploaded_file($file_tmp, "images/" . $file_image);
            header('location: emp-management.php');
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
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/mystyle.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>

    <script src="../js/sweetalert.min.js"></script>
    <!-- price format -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.priceformat.min.js"></script>

    <!-- ສໍາລັບຮູບ -->
    <style>
        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        #img-upload {
            width: 150px;
            height: 185px;
            margin-bottom: 20px;
        }
    </style>
</head>

</head>

<body class="sb-nav-fixed">
    <!-- ສະແດງ message ແຈ້ງເຕືອນ -->
    <?= @$message ?>

    <!-- ດຶງເມນູເຂົ້າມາ  -->
    <?php include_once 'menu.php'
    ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4" style="margin-top: 30px;">
                <div class="card border-primary">
                    <div class="card-header bg-info text-white h5">ຟອມແກ້ໄຂຂໍ້ມູນພະນັກງານ</div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                            <!--ເກັບຊື່ຮູບເກົ່າໄວ້ເພື່ອໄວ້ລືບ-->
                            <input type="hidden" name="oldpicture" value=" <?= $file_image?>">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!--ລະຫັດພະນັກງານ-->
                                            <div class="mb-3">
                                                <label for="empno" class="form-label">ລະຫັດພະນັກງານ:</label>
                                                <input type="text" class="form-control" id="empno" name="empno" value="<?= @$empno ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!--ຊື່ພະນັກງານ-->
                                            <div class="mb-3">
                                                <label for="empname" class="form-label">ຊື່ນັກງານ:</label>
                                                <input type="text" class="form-control" id="empname" placeholder="ກະລຸນາປ້ອນຊື່ພະນັກງານ" name="empname" value="<?= @$empname ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!--ເພດ-->
                                            <fieldset class="mb-3">
                                                <p>ເພດ</p>
                                                <div class="form-check-inline">
                                                    <input type="radio" class="form-check-input" id="gender1" name="gender" value="ຊ" <?php if (@$gender == '' || @$gender == 'ຊ') echo 'checked'; ?>>
                                                    <label class="form-check-label" for="gender1">ຊາຍ</label>
                                                </div>
                                                <div class="form-check-inline">
                                                    <input type="radio" class="form-check-input" id="gender2" name="gender" value="ຍ" <?php if (@$gender == 'ຍ') echo 'checked'; ?>>
                                                    <label class="form-check-label" for="gender2">ຍິງ</label>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <!--ວັນເດືອນປີເກີດ-->
                                            <div class="mb-3">
                                                <label for="date_birth" class="form-label">ວັນ, ເດືອນ ປີເກີດ:</label>
                                                <input type="date" class="form-control" id="date_birth" name="date_birth" value="<?= @$date_birth ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <!--ທີ່ຢູ່-->
                                            <div class="mb-3">
                                                <label for="address">ທີ່ຢູ່:</label>
                                                <textarea class="form-control" rows="5" id="address" name="address"><?= @strip_tags($address) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!--ຮູບ-->
                                            <div style="text-align: center">
                                                <img id='img-upload' />
                                                <div id="temp_img">
                                                    <img src="images/<?= @$file_image ?>" alt="ຮູບພະນັກງານ" width="150px" height="180px" />
                                                </div>
                                                <!--ເລືອກຮູບພາບ-->
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <span class="btn btn-info btn-file">
                                                            ເລືອກຮູບ<input type="file" id="imgInp" name="file_image" accept="image/*">
                                                        </span>
                                                    </span>
                                                    <input type="text" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <!--ພະແນກ-->
                                            <div class="mb-3">
                                                <label for="deparment" class="form-label">ພະແນກ</label>
                                                <select class="form-select" id="deparment" name="department" required="true">
                                                    <option value="">----ເລືອກພະແນກ-----</option>
                                                    <?php
                                                    $sql = "SELECT dno, name FROM dept ORDER BY name ASC";
                                                    $result = mysqli_query($link, $sql);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                        <option value="<?= @$row['dno'] ?>" <?php if (@$department == @$row['dno']) echo 'selected' ?>>
                                                            <?= @$row['name'] ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!--ຂັ້ນເງິນເດືອນ-->
                                    <div class="mb-3">
                                        <label for="salary" class="form-label">ຂັ້ນເງິນເດືອນ</label>
                                        <select class="form-select" id="salary" name="salary">
                                            <option value="">----ເລືອກຂັ້ນເງິນເດືອນ-----</option>
                                            <?php
                                            $sql = "SELECT *FROM salary ORDER BY sal ASC";
                                            $result = mysqli_query($link, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?= @$row['grade'] ?>" <?php if (@$salary == @$row['grade']) echo 'selected' ?>>
                                                    <?= @$row['grade'] . " => " . number_format(@$row['sal'], 0, ",", ".") ?>
                                                </option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!--ເງິນອຸດໜູນ-->
                                    <div class="mb-3">
                                        <label for="incentive" class="form-label">ເງິນອຸດໜູນ</label>
                                        <input type="text" class="form-control" id="incentive" placeholder="ປ້ອນເງິນອຸດໜູນ" name="incentive" value="<?= @$incentive ?>" min="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!--ພາສາຕ່າງປະເທດ-->
                                    <fieldset class="form-group">
                                        <p>ພາສາຕ່າງປະເທດ</p>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="language[]" value="ອັງກິດ" <?php if (@strpos(@$language, "ອັງກິດ") !== FALSE) echo 'checked'; ?>>
                                            <label class="form-check-label">ອັງກິດ</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="language[]" value="ຈີນ" <?php if (@strpos(@$language, "ຈີນ") !== FALSE) echo 'checked'; ?>>
                                            <label class="form-check-label">ຈີນ</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="language[]" value="ຫວຽດນາມ" <?php if (@strpos(@$language, "ຫວຽດນາມ") !== FALSE) echo 'checked'; ?>>
                                            <label class="form-check-label">ຫວຽດນາມ</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="language[]" value="ຝຣັ່ງ" <?php if (@strpos(@$language, "ຝຣັ່ງ") !== FALSE) echo 'checked'; ?>>
                                            <label class="form-check-label">ຝຣັ່ງ</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="language[]" value="ອື່ນໆ..." <?php if (@strpos(@$language, "ອື່ນໆ...") !== FALSE) echo 'checked'; ?>>
                                            <label class="form-check-label">ອື່ນໆ...</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-12 text-center">
                                    <!--ປຸ່ມ-->
                                    <input type="submit" name="btnEdit" value="ແກ້ໄຂຂໍ້ມູນ" class="btn btn-primary" style="width: 100px; border-radius: 20px">
                                    &nbsp;&nbsp;
                                    <a href="emp-management.php" class="btn btn-warning" style="width: 100px; border-radius: 20px">ຍົກເລີກ</a>
                                    
                                </div>
                            </div>

                        </form>
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
    $(document).ready(function() {
        /*ເລືອກຮູບພາບ*/
        $('#img-upload').hide();
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
            $('#temp_img').hide(); /*ໃຫ້ເຊືອງເມືອເລືອກຮູບ*/
            $('#img-upload').show();
        });

        $('.btn-file :file').on('fileselect', function(event, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = label;

            if (input.length) {
                input.val(log);
            } else {
                if (log)
                    alert(log);
            }

        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#img-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function() {
            readURL(this);
        });
        /*ສິ້ນສຸດເລືອກຮູບ*/

        /*ແຍກຈຸດຫຼັກພັນ ....*/
        $('#incentive').priceFormat({
            prefix: '',
            thousandsSeparator: '.',
            centsLimit: 0
        });
    });

    /* ບໍ່ໃຫ້ມັນຊັບມິດຄືນ*/
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>