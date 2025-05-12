<?php
include_once 'login-check.php';
require_once '../function/function.php';

if (isset($_POST['btnAdd'])) {
    $cid = data_input($_POST['cid']);
    $Name = data_input($_POST['Name']);
    $phone = data_input($_POST['phone']);
   // $incentive = str_replace(".", "", $_POST['incentive']);
    //ກວດສອບວ່າລະຫັດຊໍ້າຫຼືບໍ່
    $sql = "SELECT *FROM part WHERE cid='$cid' ";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        $error_cid = "ລະຫັດນີ້ຖືກນໍາໃຊ້ແລ້ວ";
    } else {
        $sql = "INSERT INTO part VALUES('$cid', '$Name', '$phone')";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $message = '<script>swal("ສໍາເລັດ", "ຂໍ້ມູນຖືກບັນທຶກລົງໃນຖານຂໍ້ມູນແລ້ວ",
            "success",{button: "ຕົກລົງ"});</script>';
            $cid = $Name = $phone = null;
        } else {
            echo mysqli_error($link);
        }
    }
} else if (@$_GET['action'] == 'edit') { //ຖ້າກົດເຄື່ອງໝາຍແກ້ໄຂທີ່ຫ້ອງຕາຕະລາງໃຫ້ເອົງຄ່າຂື້ນຄ້າງຟອມ
    $cid = $_GET['cid'];
    $sql = "SELECT *FROM part WHERE cid='$cid'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $Name = $row['Name'];
    $phone = $row['phone'];
  //  $incentive = $row['incentive'];
} else if (isset($_POST['btnEdit'])){
    $cid= data_input($_POST['cid']);
    $Name = data_input($_POST['Name']);
    $phone = data_input($_POST['phone']);
  //  $incentive = str_replace(".", "", $_POST['incentive']);
   
    $sql = "UPDATE part SET Name='$Name',phone='$phone' WHERE cid='$cid'";
    $result = mysqli_query($link, $sql);
    if ($result) {
         $message = '<script>swal("ສໍາເລັດ", "ປັບປຸງຂໍ້ມູນໃນຖານຂໍ້ມູນແລ້ວ","success",{button: "ຕົກລົງ"});</script>';
        $cid = $Name = $phone  = null;
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
                            <legend class="float-none w-auto p-2 h5">ຈັດການຂໍ້ມູນພາກຮຽນ</legend>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                <div class="mb-3">
                                    <label for="cid" class="form-label">ລະຫັດພາກຮຽນ</label>
                                    <input type="text" class="form-control" id="cid" placeholder="ປ້ອນລະຫັດພາກ" name="cid" value="<?= @$cid ?>" required maxlength="3" <?php if (@$_GET['action'] == 'edit') echo 'readonly'; ?>>
                                    <div class="form-control-feedback">
                                        <div class="text-danger align-middle">
                                            <!-- ສະແດງ error ເມື່ອລະຫັດພະແນກຊໍ້າກັນ  -->
                                            <?= @$error_cid ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="Name" class="form-label">ພາກຮຽນ:</label>
                                    <input type="text" class="form-control" id="Name" placeholder="ປ້ອນພາກຮຽນ" name="Name" value="<?= @$Name ?>" required>
                                </div>

                               <div class="mb-3">
                                    <label for="phone" class="form-label">ເວລາ:</label>
                                    <input type="text" class="form-control" id="phone" placeholder="ປ້ອນເວລາ" name="phone" value="<?= @$phone ?>" required>
                                </div>

                                <!-- <div class="mb-3">
                                    <label for="incentive" class="form-label">ເງິນອຸດໜູນ:</label>
                                    <input type="text" class="form-control" id="incentive" placeholder="ປ້ອນຈໍານວນເງິນອຸດໜູນ" name="incentive" value="" required>
                                </div> -->
                                <?php
                                 if (@$_GET['action'] == 'edit') {
                                     echo '<button type="submit" name="btnEdit" class="btn btn-info" style="width: 110px; border-radius: 20px"><i class="fas fa-edit"></i>&nbsp;&nbsp;ແກ້ໄຂ</button> ';
                                 } else {
                                     echo '<button type="submit" name="btnAdd" class="btn btn-primary" style="width: 110px; border-radius: 20px"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;ເພີ້ມ</button> ';
                                 }
                                ?>
                                <a href="customer.php" class="btn btn-warning" style="width: 110px; border-radius: 20px"><i class="fas fa-sync"></i>&nbsp;&nbsp;ຍົກເລີກ</a>

                            </form>
                        </fieldset>
                    </div>

                    <div class="col-md-8 mt-4">
                        <fieldset class="border border-primary p-2 px-4 pb-4" style="border-radius: 15px;">
                            <table id="example" class="table table-striped" style="width:100%">
                                <thead class="bg-secondary text-center text-white">
                                    <tr>
                                        <th>ລໍາດັບ</th>
                                        <th>ລະຫັດ</th>
                                        <th>ພາກຮຽນ</th>
                                        <th>ເວລາ</th>
                                       <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                 <?php
                                    $sql = "SELECT *FROM part ORDER BY Name ASC";
                                    $result = mysqli_query($link, $sql);
                                    $number = 1;
                                    while($row = mysqli_fetch_assoc($result)) {           
                                    ?> 
                                        <tr>
                                            <td class="text-center"><?= @$number++ ?></td>
                                            <td class="text-center"><?= @$row['cid'] ?></td> 
                                            <td><?= @$row['Name'] ?></td>
                                            <td><?= @$row['phone'] ?></td>
                                         
                                            <td class="text-center" style="width: 80px">
                                                <a href="customer.php?action=edit&cid=<?= @$row['cid'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="ແກ້ໄຂຂໍ້ມູນ"><i class="fas fa-edit text-success"></i></a>
                                                <a href="#" onclick="deletedata('<?= $row['cid'] ?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="ລືບຂໍ້ມູນ"><i class="fas fa-trash-alt text-danger"></i></a>
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
    function deletedata(id){
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
                        url: "cus-delete.php",
                        method: "post",
                        data: {
                            cid: id
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