<?php
include_once 'login-check.php';
include_once 'function/function.php';


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

                <span class="d-flex justify-content-end mt-4">
                    <a href="emp-management.php" class="btn btn-secondary"> <i class="fas fa-address-card"></i>&nbsp;ສະແດງຂໍ້ມູນ</a>
                </span>
                <div class="card border-primary">
                    <div class="card-header bg-info text-white h5">ຟອມປ້ອນຂໍ້ມູນພະນັກງານ</div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!--ລະຫັດພະນັກງານ-->
                                        </div>
                                        <div class="col-md-6">
                                            <!--ຊື່ພະນັກງານ-->
                                        </div>
                                        <div class="col-md-6">
                                            <!--ເພດ-->
                                            <fieldset class="mb-3">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <!--ວັນເດືອນປີເກີດ-->

                                        </div>
                                        <div class="col-md-12">
                                            <!--ທີ່ຢູ່-->

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!--ຮູບ-->

                                        </div>
                                        <div class="col-md-12">
                                            <!--ພະແນກ-->

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!--ຂັ້ນເງິນເດືອນ-->

                                </div>
                                <div class="col-md-4">
                                    <!--ເງິນອຸດໜູນ-->

                                </div>
                                <div class="col-md-4">
                                    <!--ພາສາຕ່າງປະເທດ-->
                                    <fieldset class="form-group">

                                    </fieldset>
                                </div>
                                <div class="col-md-12 text-center">
                                    <!--ປຸ່ມ-->
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