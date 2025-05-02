<?php
include_once 'login-check.php';
include_once 'function/function.php';

//ສ້າງຟັງຊັນເພື່ອ ສ້າງລະຫັດອັດຕະໂນມັດ
function autoID(){
    $Stu_ID = "";
    global $link;

    $sql = "SELECT Max(Stu_ID) AS Stu_ID FROM student";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    if(empty($row['Stu_ID'])) {
        $Stu_ID = "STU0001";
    }else {
        $id = substr($row['Stu_ID'], 3, strlen($row['Stu_ID']));
        $id++;
        $Stu_ID = "STU" . sprintf("%04d", $id);
    }
    return $Stu_ID;
}
//ເອີ້ນໃຊ້ autoID   
    autoID();

    //ຖ້າກົດປຸ່ມເພີ້ມຂໍ້ມູນ
    if (isset($_POST['btnAdd'])) {
        $Stu_ID = autoID();
        $Stu_name = data_input($_POST['Stu_name']);
        $gender = $_POST['gender'];
        $date_birth = $_POST['date_birth'];
        $S_id = data_input($_POST['S_id']);
        $cid = data_input($_POST['cid']);
        $Sets = data_input($_POST['Sets']);
        $Gen = data_input($_POST['Gen']);
        $address = nl2br(trim(stripslashes($_POST['address'])));
        $Parent = isset($_POST['Parent']) ? data_input($_POST['Parent']) : '';
        $Parent_Tell = isset($_POST['Parent_Tell']) ? data_input($_POST['Parent_Tell']) : '';
        $tell = isset($_POST['tell']) ? data_input($_POST['tell']) : '';
        $status = "ຍັງບໍ່ເຂົ້າພັກ";
    
        $sql = "INSERT INTO student (
            Stu_ID, Stu_name, gender, date_birth, S_id, cid, Sets, Gen, address, Parent, Parent_Tell, status, tell
        ) VALUES (
            '$Stu_ID', '$Stu_name', '$gender', '$date_birth', '$S_id', '$cid', '$Sets', '$Gen', '$address', '$Parent', '$Parent_Tell', '$status', '$tell'
        )";
        
    
        $result = mysqli_query($link, $sql);
        if ($result) {
            $message = '<script> swal("ສໍາເລັດ","ຂໍ້ມູນບັນທຶກລົງຖານຂໍ້ມູນແລ້ວ","success",{button: "ຕົກລົງ",}); </script>';
            $Stu_name=$gender=$date_birth=$S_id=$cid=$Sets=$Gen=$address=$Parent=$Parent_Tell=$status=$tell = null;
        } else {
            echo mysqli_error($link);
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

    <title>ລະບົບຈັດການຂໍ້ມູນນັກສຶກສາ</title>
    <link rel="icon" href="images/icon_logo.jpg">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/mystyle.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

    <script src="js/sweetalert.min.js"></script>
    <!-- price format -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.priceformat.min.js"></script>

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
            <div class="container-fluid px-4">

                <span class="d-flex justify-content-end mt-2">
                    <a href="student-management.php" class="btn btn-secondary"> <i class="fas fa-address-card"></i>&nbsp;ສະແດງຂໍ້ມູນ</a>
                </span>
                <div class="card border-primary">
                    <div class="card-header bg-info text-white h5">ຟອມປ້ອນຂໍ້ມູນນັກສຶກສາ</div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!--ລະຫັດພະນັກງານ-->
                                            <div class="mb-3">
                                                <label for="Stu_ID" class="form-label">ລະຫັດນັກສຶກສາ:</label>
                                                <input type="text" class="form-control" id="Stu_ID" placeholder="ກະລຸນາປ້ອນລະຫັດນັກສຶກສາ" name="Stu_ID" value="" placeholder="ລະຫັດໃສ່ອັດຕະໂນມັດ" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!--ຊື່ພະນັກງານ-->
                                            <div class="mb-3">
                                                <label for="Stu_name" class="form-label">ຊື່ນັກງານ:</label>
                                                <input type="text" class="form-control" id="Stu_name" placeholder="ກະລຸນາປ້ອນຊື່ ແລະ ນາມສະກຸນ" name="Stu_name" value="<?= @$Stu_name ?>" required>
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

                                        
                                        <div class="col-md-6">
                                            <!--ສາຂາ-->
                                            <div class="mb-3">
                                                <label for="S_id" class="form-label">ສາຂາ</label>
                                                <select class="form-select" id="S_id" name="S_id" required="true">
                                                    <option value="">----ເລືອກສາຂາ-----</option>
                                                    <?php
                                                    $sql = "SELECT S_id, name FROM supplier ORDER  BY name ASC";
                                                    $result = mysqli_query($link, $sql);
                                                    while($row = mysqli_fetch_assoc($result)){
                                                    ?>
                                                        <option value="<?= @$row['S_id'] ?>" <?php if(@$S_id==@$row['S_id']) echo 'selected'?>>
                                                            <?= @$row['name'] ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                
                                      
                                <div class="mb-3">
                                    <label for="Gen">ຮຸ້ນທີ:</label>

                                    <input type="text" class="form-control" id="Gen	" placeholder="ປ້ອນຮຸ້ນທີ" name="Gen" value="<?= @$Gen ?>">
                                </div>
                            </div>

                          
                            <div class="col-md-6">
                                            <!--ພາກຮຽນ-->
                                            <div class="mb-3">
                                                <label for="cid" class="form-label">ພາກຮຽນ</label>
                                                <select class="form-select" id="cid" name="cid" required="true">
                                                    <option value="">----ເລືອກພາກຮຽນ-----</option>
                                                    <?php
                                                    $sql = "SELECT cid, Name FROM customer ORDER  BY Name ASC";
                                                    $result = mysqli_query($link, $sql);
                                                    while($row = mysqli_fetch_assoc($result)){
                                                    ?>
                                                        <option value="<?= @$row['cid'] ?>" <?php if(@$cid==@$row['cid']) echo 'selected'?>>
                                                            <?= @$row['Name'] ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                <div class="col-md-6">
                                       
                                       <div class="mb-3">
                                           <label for="Sets">ຊຸດຮຽນ:</label>
                                           <input type="text" class="form-control" id="Sets" placeholder="ປ້ອນຊຸດຮຽນ" name="Sets" value="<?= @$Sets ?>">
                                       </div>
                                   </div>

                            
                                        <div class="col-md-6">
                                    <!--ເງິນອຸດໜູນ-->
                                    <div class="mb-3">
                                        <label for="Parent" class="form-label">ຜູ້ປົກຄອງ</label>
                                        <input type="text" class="form-control" id="Parent" placeholder="ປ້ອນຊື່ຜູ້ປົກຄອງ" name="Parent" value="<?= @$Parent ?>">
                                    </div>
                                </div>

                                      
                                        <div class="col-md-6">
                                    <!--ເງິນອຸດໜູນ-->
                                    <div class="mb-3">
                                        <label for="tell" class="form-label">ເບີໂທລະສັບນັກສຶກສາ</label>
                                        <input type="text" class="form-control" id="tell" placeholder="ປ້ອນເບີໂທລະສັບນັກສຶກສາ" name="tell" value="<?= @$tell ?>"required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!--ເງິນອຸດໜູນ-->
                                    <div class="mb-3">
                                        <label for="Parent_Tell	" class="form-label">ເບີໂທລະສັບຜູ້ປົກຄອງ</label>
                                        <input type="text" class="form-control" id="Parent_Tell	" placeholder="ປ້ອນຊື່ຜູ້ປົກຄອງ" name="Parent_Tell" value="<?= @$Parent_Tell ?>">
                                    </div>
                                </div>


                                    </div>
                                </div>
                               
 
                                <div class="col-md-12 text-center">
                                    <!--ປຸ່ມ-->
                                    <input type="submit" name="btnAdd" value="ເພີ້ມຂໍ້ມູນ" class="btn btn-primary" style="width: 100px; border-radius: 20px">
                                    &nbsp;&nbsp;
                                    <input type="reset" value="ຍົກເລີກ" class="btn btn-warning" style="width: 100px; border-radius: 20px">
                                    &nbsp;&nbsp;
                                    <button onclick="window.location.reload(true)" class="btn btn-success" style="width: 100px; border-radius: 20px;">ໂຫຼດຄືນໃໝ່</button>
                                </div>

                                                   </div>
                                   </div>
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