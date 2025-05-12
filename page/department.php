<?php
include_once 'login-check.php';
require_once '../function/function.php';

if (isset($_POST['btnAdd'])) {
    $dno = data_input($_POST['dno']);
    $name = data_input($_POST['name']);
    $loc = data_input($_POST['loc']);
    $incentive = str_replace(".", "", $_POST['incentive']);
    //ກວດສອບວ່າລະຫັດຊໍ້າຫຼືບໍ່
    $sql = "SELECT *FROM dept WHERE dno='$dno' ";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        $error_dno = "ລະຫັດນີ້ຖືກນໍາໃຊ້ແລ້ວ";
    } else {
        $sql = "INSERT INTO dept VALUES('$dno', '$name', '$loc', '$incentive')";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $message = '<script>swal("ສໍາເລັດ", "ຂໍ້ມູນຖືກບັນທຶກລົງໃນຖານຂໍ້ມູນແລ້ວ",
            "success",{button: "ຕົກລົງ"});</script>';
            $dno = $name = $loc = $incentive = null;
        } else {
            echo mysqli_error($link);
        }
    }
} else if (@$_GET['action'] == 'edit') { //ຖ້າກົດເຄື່ອງໝາຍແກ້ໄຂທີ່ຫ້ອງຕາຕະລາງໃຫ້ເອົງຄ່າຂື້ນຄ້າງຟອມ
    $dno = $_GET['dno'];
    $sql = "SELECT *FROM dept WHERE dno='$dno'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $loc = $row['loc'];
    $incentive = $row['incentive'];
} else if (isset($_POST['btnEdit'])) {
    $dno = data_input($_POST['dno']);
    $name = data_input($_POST['name']);
    $loc = data_input($_POST['loc']);
    $incentive = str_replace(".", "", $_POST['incentive']);

    $sql = "UPDATE dept SET name='$name',loc='$loc', incentive='$incentive' WHERE dno='$dno'";
    $result = mysqli_query($link, $sql);
    if ($result) {
        $message = '<script>swal("ສໍາເລັດ", "ປັບປຸງຂໍ້ມູນໃນຖານຂໍ້ມູນແລ້ວ","success",{button: "ຕົກລົງ"});</script>';
        $dno = $name = $loc = $incentive = null;
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

    <title>ລະບົບຈັດການຂໍ້ມູນພະນັກງານ</title>
    <link rel="icon" href="../images/icon_logo.jpg">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/mystyle.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>

    <script src="../js/sweetalert.min.js"></script>
    <!-- datatable -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap5.min.js"></script>

    <script src="../js/jquery.priceformat.min.js"></script>

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
                    <div class="col-md-4">
                        <fieldset class="border border-primary p-2 px-4 pb-4" style="border-radius: 15px; background-color: #E7E9EB">
                            <legend class="float-none w-auto p-2 h5">ຈັດການຂໍ້ມູນພະແນກ</legend>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                <div class="mb-3">
                                    <label for="dno" class="form-label">ລະຫັດພະແນກ:</label>
                                    <input type="text" class="form-control" id="dno" placeholder="ປ້ອນລະຫັດພະແນກ" name="dno" value="<?= @$dno ?>" required maxlength="3" <?php if (@$_GET['action'] == 'edit') echo 'readonly'; ?>>
                                    <div class="form-control-feedback">
                                        <div class="text-danger align-middle">
                                            <!-- ສະແດງ error ເມື່ອລະຫັດພະແນກຊໍ້າກັນ  -->
                                            <?= @$error_dno ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">ຊື່ພະແນກ:</label>
                                    <input type="text" class="form-control" id="name" placeholder="ປ້ອນຊື່ພະແນກ" name="name" value="<?= @$name ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="loc" class="form-label">ສະຖານທີ່:</label>
                                    <input type="text" class="form-control" id="loc" placeholder="ສະຖານທີ່" name="loc" value="<?= @$loc ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="incentive" class="form-label">ເງິນອຸດໜູນ:</label>
                                    <input type="text" class="form-control" id="incentive" placeholder="ປ້ອນຈໍານວນເງິນອຸດໜູນ" name="incentive" value="<?= @$incentive ?>" required>
                                </div>
                                <?php
                                if (@$_GET['action'] == 'edit') {
                                    echo '<button type="submit" name="btnEdit" class="btn btn-info" style="width: 110px; border-radius: 20px"><i class="fas fa-edit"></i>&nbsp;&nbsp;ແກ້ໄຂ</button> ';
                                } else {
                                    echo '<button type="submit" name="btnAdd" class="btn btn-primary" style="width: 110px; border-radius: 20px"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;ເພີ້ມ</button> ';
                                }
                                ?>
                                <a href="department.php" class="btn btn-warning" style="width: 110px; border-radius: 20px"><i class="fas fa-sync"></i>&nbsp;&nbsp;ຍົກເລີກ</a>

                            </form>
                        </fieldset>
                    </div>

                    <div class="col-md-8 mt-4">
                        <fieldset class="border border-primary p-2 px-4 pb-4" style="border-radius: 15px;">
                            <table id="example" class="table table-striped" style="width:100%">
                                <thead class="bg-secondary text-center text-white">
                                    <tr>
                                        <th>ລໍາດັບ</th>
                                        <th>ລະຫັດພະແນກ</th>
                                        <th>ຊື່ພະແນກ</th>
                                        <th>ສະຖານທີ່</th>
                                        <th>ເງິນອຸດໜູນ</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM dept ORDER BY name ASC";
                                    $result = mysqli_query($link, $sql);
                                    $number = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $number++ ?></td>
                                            <td class="text-center"><?= $row['dno'] ?></td>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['loc'] ?></td>
                                            <td class="text-center"><?= number_format($row['incentive'], 0, ",", ".") ?></td>
                                            <td class="text-center" style="width: 80px">
                                                <a href="department.php?action=edit&dno=<?= $row['dno'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="ແກ້ໄຂຂໍ້ມູນ">
                                                    <i class="fas fa-edit text-success"></i>
                                                </a>
                                                <a href="#" onclick="deletedata('<?= $row['dno'] ?>')" data-bs-placement="tooltip" title="ລືບຂໍ້ມູນ">
                                                    <i class="fas fa-trash-alt text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </fieldset>
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
        $('#example').DataTable();

        /*ພີມໃຫ້ມີຫຼັງຈຸດ */
        $('#incentive').priceFormat({
            prefix: '',
            thousandsSeparator: '.',
            centsLimit: 0
        });

    });

    /*ໃຊ້ Tooltrip ເວລາເລືອນເມົ້າໄປເທິງໃຫ້ສະແດງຂໍ້ຄວາມ */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    /* ບໍ່ໃຫ້ມັນຊັບມິດຄືນ*/
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    //ສ້າງຟັງຊັນເພື່ອລົບ
    function deletedata(id) {
        // alert(id);
        swal({
                title: "ເຈົ້າຕ້ອງການລືບແທ້ ຫຼື ບໍ່?",
                text: "ຂໍ້ມູນລະຫັດ " + id + ", ເມື່ອລືບຈະບໍ່ສາມາດກູ້ຂໍ້ມູນຄືນໄດ້!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                buttons: ['ຍົກເລີກ', 'ຕົກລົງ']
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "dept-delete.php",
                        method: "post",
                        data: {
                            dno: id
                        },
                        success: function(data) {
                            if (data) {
                                alert(data);
                            } else {
                                swal("ສໍາເລັດ", "ຂໍ້ມູນຖືກລືບອອກຈາກຖານຂໍ້ມູນແລ້ວ", "success", {
                                    button: "ຕົກລົງ",
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 1000); //1000 = 1ວິນາທີ
                            }
                        }
                    });

                } else {
                    swal("ຂໍ້ມູນຂອງທ່ານຍັງປອດໄພ!", {
                        button: "ຕົກລົງ",
                    });
                }
            });
    }
</script>