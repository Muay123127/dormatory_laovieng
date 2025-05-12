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
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/mystyle.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

    <script src="js/sweetalert.min.js"></script>
    <!-- datatable -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap5.min.js"></script>

    <script src="../js/jquery.priceformat.min.js"></script>


</head>

<body class="sb-nav-fixed">
    <!-- ດຶງເມນູເຂົ້າມາ  -->
    <?php include_once 'menu.php' ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>

                ເນື້ອຫາ

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

    //ສະແດງຂໍ້ມູນໃນຕາຕະລາງ
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>