<?php
require_once 'login-check.php';
require_once 'function/function.php';

//ຮັບຄ່າຈາກຟອມເມື່ອກົດປຸ່ມ btnAdd
if (isset($_POST['btnAdd'])) {
    $grade = data_input($_POST['grade']);
    $sal = str_replace(".", "", data_input($_POST['sal']));

    //ກວດສອບ ຂັ້ນເງິນເດືອນ grade ວ່າຊໍ້າກັນ ຫຼື ບໍ່
    $sql = "SELECT *FROM salary WHERE grade='$grade' ";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        $error_grade = "ລະຫັດນີ້ຖືກນໍາໃຊ້ແລ້ວ";
    } else {
        $sql = "INSERT INTO salary(grade, sal) VALUES ('$grade', '$sal')";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $grade = $sal = "";
            $message = '<script>swal("ສໍາເລັດ", "ຂໍ້ມູນບັນທຶກລົງໃນຖານຂໍ້ມູນສໍາເລັດ", "success", {button: "ຕົກລົງ",});</script>';
        } else {
            echo mysqli_error($link);
        }
    }
} else if (@$_GET['action'] == 'edit') { //ຮັບຄ່າເມື່ອກົດ ແກ້ໄຂໃນຕາຕະລາງ ແລ້ວ ເອົາຄ່າຄ້າງຟອມ
    $grade = $_GET['grade'];
    $sql = "SELECT *FROM salary WHERE grade='$grade'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $sal = $row['sal'];
} else if (isset($_POST['btnEdit'])) { //ຖ້າກົດປຸ່ມແກ້ໄຂ
    $grade = data_input($_POST['grade']);
    $sal = str_replace(".", "", data_input($_POST['sal']));

    $sql = "UPDATE salary SET sal='$sal' WHERE grade='$grade'";
    $result = mysqli_query($link, $sql);
    if ($result) {
        $grade = $sal = "";
        $message = '<script>swal("ສໍາເລັດ", "ປັບປຸງຂໍ້ມູນໃນຖານຂໍ້ມູນສໍາເລັດ", "success", {button: "ຕົກລົງ",});</script>';
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
    <link rel="icon" href="images/icon_logo.jpg">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/mystyle.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

    <script src="js/sweetalert.min.js"></script>
    <!-- datatable -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap5.min.js"></script>

    <script src="js/jquery.priceformat.min.js"></script>

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
                            <legend class="float-none w-auto p-2 h5">ຈັດການຂໍ້ມູນເງິນເດືອນ</legend>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                <div class="mb-3">
                                    <label for="grade" class="form-label">ຂັ້ນເງິນເດືອນ:</label>
                                    <input type="text" class="form-control" id="grade" placeholder="ປ້ອນຊື່ ຂັ້ນເງິນເດືອນ" name="grade" value="<?= @$grade ?>" required maxlength="3" <?php if (@$_GET['action'] == 'edit') echo 'readonly'; ?>>
                                    <div class="form-control-feedback">
                                        <div class="text-danger align-middle">
                                            <!-- ສະແດງ error ເມື່ອຂັ້ນເງິນເດືອນຊໍ້າກັນ  -->
                                            <?= @$error_grade ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sal" class="form-label">ເງິນເດືອນ:</label>
                                    <input type="text" class="form-control" id="sal" placeholder="ປ້ອນຈໍານວນເງິນເດືອນ" name="sal" value="<?= @$sal ?>" required>
                                </div>
                                <?php
                                if (@$_GET['action'] == 'edit') {
                                    echo '<button type="submit" name="btnEdit" class="btn btn-info" style="width: 110px; border-radius: 20px"><i class="fas fa-edit"></i>&nbsp;&nbsp;ແກ້ໄຂ</button> ';
                                } else {
                                    echo '<button type="submit" name="btnAdd" class="btn btn-primary" style="width: 110px; border-radius: 20px"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;ເພີ້ມ</button> ';
                                }
                                ?>

                                <a href="salary.php" class="btn btn-warning" style="width: 110px; border-radius: 20px"><i class="fas fa-sync"></i>&nbsp;&nbsp;ຍົກເລີກ</a>

                            </form>
                        </fieldset>
                    </div>

                    <div class="col-md-8 mt-4">
                        <fieldset class="border border-primary p-2 px-4 pb-4" style="border-radius: 15px;">
                            <table id="example" class="table table-striped" style="width:100%">
                                <thead class="bg-secondary text-center text-white">
                                    <tr>
                                        <th>ລໍາດັບ</th>
                                        <th>ຂັ້້ນເງິນເດືອນ</th>
                                        <th>ເງິນເດືອນ</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT *FROM salary";
                                    $result = mysqli_query($link, $sql);
                                    $num = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $num++ ?></td>
                                            <td class="text-center"><?= $row['grade'] ?></td>
                                            <td class="text-end"><?= number_format($row['sal'], 0, ",", ".") ?></td>
                                            <td class="text-center" style="width: 80px">
                                                <a href="salary.php?action=edit&grade=<?= $row['grade'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="ແກ້ໄຂຂໍ້ມູນ"><i class="fas fa-edit text-success"></i></a>
                                                <a href="#" onclick="deletedata('<?= $row['grade'] ?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="ລືບຂໍ້ມູນ"><i class="fas fa-trash-alt text-danger"></i></a>
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
        $('#sal').priceFormat({
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

    //ສ້າງຟັງຊັນ deletedata ເພື່ອລືບຂໍ້ມູນ
    function deletedata(id) {
        //alert(id);
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
                        url: "salary-delete.php",
                        method: "post",
                        data: {
                            grade: id
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