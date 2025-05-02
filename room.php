<?php
require_once 'login-check.php';
require_once 'function/function.php';

//ຮັບຄ່າຈາກຟອມເມື່ອກົດປຸ່ມ btnAdd
if (isset($_POST['btnAdd'])) {
    $Room_ID = data_input($_POST['Room_ID']);
    $R_number = data_input($_POST['R_number']);
    $Build = data_input($_POST['Build']);
    $Meter_electrict = data_input($_POST['Meter_electrict']);
    $Price = str_replace(".", "", data_input($_POST['Price']));
    $Persons = data_input($_POST['Persons']);
    $Statuss = "ວ່າງ";


    // $Statuss = data_input($_POST['Status']);
    $TotalCapacity = data_input($_POST['TotalCapacity']);
    $RoomType = $_POST['RoomType'];
    $booked = 0; // ຕັ້ງຄ່າຄ່າເລີ່ມຕົ້ນເປັນ 0


    //ກວດສອບ ຂັ້ນເງິນເດືອນ grade ວ່າຊໍ້າກັນ ຫຼື ບໍ່
    $sql = "SELECT * FROM room WHERE Room_ID='$Room_ID' OR R_number='$R_number'";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['Room_ID'] == $Room_ID) {
            $error_Room_ID = "ລະຫັດນີ້ຖືກນໍາໃຊ້ແລ້ວ";
        } else if ($row['R_number'] == $R_number) {
            $error_R_number = "ເບີຫ້ອງນີ້ຖືກນໍາໃຊ້ແລ້ວ";
        }
    } else {
        // ບ່ມີຊໍ້າ ແລ້ວຄ່ອຍ Insert
        $sql = "INSERT INTO room(Room_ID, R_number, Build, Meter_electrict, Price, Persons, Statuss, TotalCapacity, RoomType, booked)
                VALUES ('$Room_ID', '$R_number', '$Build', '$Meter_electrict', '$Price', '$Persons', '$Statuss', '$TotalCapacity', '$RoomType', '$booked')";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $Room_ID = $R_number = $Build = $Meter_electrict = $Price = $Persons = $Statuss = $TotalCapacity = $RoomType = $booked = "";
            $message = '<script>swal("ສໍາເລັດ", "ຂໍ້ມູນບັນທຶກລົງໃນຖານຂໍ້ມູນສໍາເລັດ", "success", {button: "ຕົກລົງ",});</script>';
        } else {
            echo mysqli_error($link);
        }
    }
    
} else if (@$_GET['action'] == 'edit') { //ຮັບຄ່າເມື່ອກົດ ແກ້ໄຂໃນຕາຕະລາງ ແລ້ວ ເອົາຄ່າຄ້າງຟອມ
    $Room_ID = $_GET['Room_ID'];
    $sql = "SELECT *FROM room WHERE Room_ID='$Room_ID'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $R_number = $row['R_number'];
    $Build = $row['Build'];
    $Meter_electrict = $row['Meter_electrict'];
    $Price = $row['Price'];
    $Persons = $row['Persons'];
    $Statuss = $row['Statuss']; // ดึงมาจากฐานข้อมูล
    $TotalCapacity = $row['TotalCapacity'];
    $RoomType = $row['RoomType'];
    $booked = 0;
    //  $booked = $row['booked'];
} else if (isset($_POST['btnEdit'])) { //ຖ້າກົດປຸ່ມແກ້ໄຂ
    $Room_ID = data_input($_POST['Room_ID']);
    $R_number = data_input($_POST['R_number']);
    $Build = data_input($_POST['Build']);
    $Meter_electrict = data_input($_POST['Meter_electrict']);
    $Price = data_input($_POST['Price']);
    $Persons = data_input($_POST['Persons']);
    $Statuss =$data_input['Statuss'];
    $TotalCapacity = data_input($_POST['TotalCapacity']);
    $RoomType = $_POST['RoomType'];
    // $booked = data_input($_POST['booked']);
    $booked = 0;
    $Price = str_replace(".", "", data_input($_POST['Price']));

    $sql = "UPDATE room SET R_number='$R_number',Build='$Build',Meter_electrict='$Meter_electrict',Price='$Price',Persons='$Persons',Statuss='$Statuss',TotalCapacity='$TotalCapacity',RoomType='$RoomType',booked='$booked' WHERE Room_ID='$Room_ID'";
    $result = mysqli_query($link, $sql);
    if ($result) {
        $Room_ID = $R_number = $Build = $Meter_electrict = $Price = $Persons = $Statuss = $TotalCapacity = $RoomType = $booked = "";
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
                            <legend class="float-none w-auto p-2 h5">ຈັດການຂໍ້ມູນຫ້ອງ</legend>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                <div class="mb-3">
                                    <label for="Room_ID" class="form-label">ລະຫັດຫ້ອງ:</label>
                                    <input type="text" class="form-control" id="Room_ID" placeholder="ປ້ອນລະຫັດຫ້ອງ" name="Room_ID" value="<?= @$Room_ID ?>" required maxlength="3" <?php if (@$_GET['action'] == 'edit') echo 'readonly'; ?>>
                                    <div class="form-control-feedback">
                                        <div class="text-danger align-middle">
                                            <!-- ສະແດງ error ເມື່ອຂັ້ນເງິນເດືອນຊໍ້າກັນ  -->
                                            <?= @$error_Room_ID ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="R_number" class="form-label">ເບີຫ້ອງ:</label>
                                    <input type="text" class="form-control" id="R_number" placeholder="ປ້ອນເບີຫ້ອງ" name="R_number" value="<?= @$R_number ?>" required>
                                    <div class="form-control-feedback">
                                    <div class="text-danger align-middle">
                                            <!-- ສະແດງ error ເມື່ອຂັ້ນເງິນເດືອນຊໍ້າກັນ  -->
                                            <?= @$error_R_number ?>
                                        </div>
                                </div>
                                </div>
                                <div class="mb-3">
                                    <label for="Build" class="form-label">ຊື່ຕຶກ:</label>
                                    <input type="text" class="form-control" id="Build" placeholder="ປ້ອນຊື່ຕຶກ" name="Build" value="<?= @$Build ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Meter_electrict" class="form-label">ເລກກົງເຕີໄຟ:</label>
                                    <input type="text" class="form-control" id="Meter_electrict" placeholder="ປ້ອນເລກກົງເຕີໄຟ" name="Meter_electrict" value="<?= @$Meter_electrict ?>" >
                                </div>
                                <div class="mb-3">
                                    <label for="Price" class="form-label">ລາຄາ:</label>
                                    <input type="text" class="form-control" id="Price" placeholder="ປ້ອນລາຄາ" name="Price" value="<?= @$Price ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Persons" class="form-label">ຈໍານວນຄົນ:</label>
                                    <input type="text" class="form-control" id="Persons" placeholder="ປ້ອນຈໍານວນຄົນ" name="Persons" value="<?= @$Persons ?>" required>
                                </div>
                              
                               
                                <div class="mb-3">
                                    <label for="TotalCapacity" class="form-label">ຈໍານວນຄົນທີ່ຮັບໄດ້:</label>
                                    <input type="text" class="form-control" id="TotalCapacity" placeholder="ປ້ອນຈໍານວນຄົນທີ່ຮັບໄດ້" name="TotalCapacity" value="<?= @$TotalCapacity ?>" required>
                                </div>
                                <!--   <div class="mb-3">
                                    <label for="RoomType" class="form-label">ປະເພດຫ້ອງ:</label>
                                    <input type="text" class="form-control" id="RoomType" placeholder="ປ້ອນປະເພດຫ້ອງ" name="RoomType" value="<>" required>
                                </div>
                                    -->
                                    <div class="col-md-6">
                                            <!--ເພດ-->
                                            <fieldset class="mb-3">
                                                <p>ເພດ</p>
                                                <div class="form-check-inline">
                                                    <input type="radio" class="form-check-input" id="RoomType1" name="RoomType" value="ຊ" <?php if (@$RoomType == '' || @$RoomType == 'ຊ') echo 'checked'; ?>required>
                                                    <label class="form-check-label" for="RoomType1">ຊາຍ</label>
                                                </div>
                                                <div class="form-check-inline">
                                                    <input type="radio" class="form-check-input" id="RoomType2" name="RoomType" value="ຍ" <?php if (@$RoomType == 'ຍ') echo 'checked'; ?>required>
                                                    <label class="form-check-label" for="RoomType2">ຍິງ</label>
                                                </div>
                                            </fieldset>
                                        </div>


                              
                                <?php
                                if (@$_GET['action'] == 'edit') {
                                    echo '<button type="submit" name="btnEdit" class="btn btn-info" style="width: 110px; border-radius: 20px"><i class="fas fa-edit"></i>&nbsp;&nbsp;ແກ້ໄຂ</button> ';
                                } else {
                                    echo '<button type="submit" name="btnAdd" class="btn btn-primary" style="width: 110px; border-radius: 20px"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;ເພີ້ມ</button> ';
                                }
                                ?>

                                <a href="room.php" class="btn btn-warning" style="width: 110px; border-radius: 20px"><i class="fas fa-sync"></i>&nbsp;&nbsp;ຍົກເລີກ</a>

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
                                        <th>ເບີຫ້ອງ</th>
                                        <th>ຕຶກ</th>
                                        <th>ເລກໄຟ</th>
                                        <th>ລາຄາ</th>
                                        <th>ຈ/ນຄົນຢູ່</th>
                                        <th>ສະຖານະ</th>
                                        <th>ຈ/ນທີ່ຮັບໄດ້</th>
                                        <th>ປະເພດ</th>
                                        <th>ຈ/ນຈອງ</th>


                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT *FROM room";
                                    $result = mysqli_query($link, $sql);
                                    $num = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $num++ ?></td>
                                            <td class="text-center"><?= @$row['Room_ID'] ?></td> 
                                    
                                            <td><?= @$row['R_number'] ?></td>
                                            <td><?= @$row['Build'] ?></td>
                                            <td><?= @$row['Meter_electrict'] ?></td>
                                            <td class="text-end"><?= number_format($row['Price'], 0, ",", ".") ?></td>
                                            <td><?= @$row['Persons'] ?></td>
                                            <td><?= @$row['Statuss'] ?></td>
                                            <td><?= @$row['TotalCapacity'] ?></td>
                                            <td><?= @$row['RoomType'] ?></td>
                                            <td><?= @$row['booked'] ?></td>
                                  
                                            <td class="text-center" style="width: 80px">
                                            <a href="room.php?action=edit&Room_ID=<?= @$row['Room_ID'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="ແກ້ໄຂຂໍ້ມູນ"><i class="fas fa-edit text-success"></i></a>
                                            <a href="#" onclick="deletedata('<?= $row['Room_ID'] ?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="ລືບຂໍ້ມູນ"><i class="fas fa-trash-alt text-danger"></i></a>
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
        $('#Price').priceFormat({
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
                        url: "room-delete.php",
                        method: "post",
                        data: {
                            Room_ID: id
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