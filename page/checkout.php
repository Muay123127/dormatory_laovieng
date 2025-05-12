<?php
require_once 'login-check.php';
require_once '../function/function.php';

// ຖ້າກົດປຸ່ມ Check-out
if (isset($_POST['btnCheckout'])) {
    $Booking_ID = data_input($_POST['Booking_ID']);
    $Checkout_date = date('Y-m-d'); // ວັນທີປັດຈຸບັນ
    
    // ດຶງຂໍ້ມູນການຈອງ
    $sql_booking = "SELECT Stu_ID, Room_ID FROM booking WHERE Booking_ID='$Booking_ID'";
    $result_booking = mysqli_query($link, $sql_booking);
    $booking_data = mysqli_fetch_assoc($result_booking);
    
    // ອັບເດດຂໍ້ມູນການຈອງ
    $sql = "UPDATE booking SET Status='ອອກແລ້ວ', Checkout_date='$Checkout_date' WHERE Booking_ID='$Booking_ID'";
    $result = mysqli_query($link, $sql);
    
    if ($result) {
        // ອັບເດດສະຖານະຫ້ອງ
        $sql_room = "UPDATE room SET Statuss='ວ່າງ', Persons=Persons-1 WHERE Room_ID='{$booking_data['Room_ID']}'";
        mysqli_query($link, $sql_room);
        
        // ອັບເດດສະຖານະນັກສຶກສາ
        $sql_student = "UPDATE student SET status='ອອກແລ້ວ' WHERE Stu_ID='{$booking_data['Stu_ID']}'";
        mysqli_query($link, $sql_student);
        
        $message = '<script>swal("ສໍາເລັດ", "ການອອກພັກສໍາເລັດແລ້ວ", "success", {button: "ຕົກລົງ",});</script>';
    } else {
        echo mysqli_error($link);
    }
}

// ຖ້າກົດປຸ່ມຄົ້ນຫາ
if (isset($_POST['btnSearch'])) {
    $search_term = data_input($_POST['search_term']);
    $search_condition = "AND (b.Booking_ID LIKE '%$search_term%' OR s.Stu_name LIKE '%$search_term%' OR s.Stu_ID LIKE '%$search_term%' OR r.R_number LIKE '%$search_term%')";
} else {
    $search_condition = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລະບົບຈັດການຫໍພັກ - ການອອກພັກ</title>
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
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($message)) echo $message; ?>
                    
                    <div class="card border-primary">
                        <div class="card-header bg-info text-white">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">ລາຍການນັກສຶກສາທີ່ກຳລັງພັກຢູ່</h5>
                                </div>
                                <div class="col-md-6">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="d-flex">
                                        <input type="text" name="search_term" class="form-control me-2" placeholder="ຄົ້ນຫາ...">
                                        <button type="submit" name="btnSearch" class="btn btn-light"><i class="fas fa-search"></i> ຄົ້ນຫາ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="checkoutTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ລະຫັດການຈອງ</th>
                                            <th>ລະຫັດນັກສຶກສາ</th>
                                            <th>ຊື່ນັກສຶກສາ</th>
                                            <th>ຫ້ອງພັກ</th>
                                            <th>ວັນທີເຂົ້າພັກ</th>
                                            <th>ໄລຍະເວລາພັກ</th>
                                            <th>ຈັດການ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT b.*, s.Stu_name, r.R_number, r.Build
                                                FROM booking b
                                                JOIN student s ON b.Stu_ID = s.Stu_ID
                                                JOIN room r ON b.Room_ID = r.Room_ID
                                                WHERE b.Status='ເຂົ້າພັກແລ້ວ' $search_condition
                                                ORDER BY b.Check_in ASC";
                                        $result = mysqli_query($link, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // ຄຳນວນໄລຍະເວລາພັກ
                                            $check_in_date = new DateTime($row['Check_in']);
                                            $today = new DateTime();
                                            $interval = $check_in_date->diff($today);
                                            $days = $interval->days;
                                        ?>
                                        <tr>
                                            <td><?php echo $row['Booking_ID']?></td>
                                            <td><?php echo $row['Stu_ID']?></td>
                                            <td><?php echo $row['Stu_name']?></td>
                                            <td>ຕຶກ <?php echo $row['Build']?> - ຫ້ອງ <?php echo $row['R_number']?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row['Check_in']))?></td>
                                            <td><?php echo $days?> ວັນ</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#checkoutModal<?php echo $row['Booking_ID']?>">
                                                    <i class="fas fa-sign-out-alt"></i> ອອກພັກ
                                                </button>
                                                
                                                <!-- Modal ຢືນຢັນການອອກພັກ -->
                                                <div class="modal fade" id="checkoutModal<?php echo $row['Booking_ID']?>" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title" id="checkoutModalLabel">ຢືນຢັນການອອກພັກ</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>ທ່ານຕ້ອງການຢືນຢັນການອອກພັກຂອງນັກສຶກສາ <strong><?php echo $row['Stu_name']?></strong> ຈາກຫ້ອງ <strong>ຕຶກ <?php echo $row['Build']?> - ຫ້ອງ <?php echo $row['R_number']?></strong> ແທ້ບໍ່?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                                                    <input type="hidden" name="Booking_ID" value="<?php echo $row['Booking_ID']?>">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ຍົກເລີກ</button>
                                                                    <button type="submit" name="btnCheckout" class="btn btn-primary">ຢືນຢັນການອອກພັກ</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    
    <script>
    $(document).ready(function() {
        $('#checkoutTable').DataTable({
            "language": {
                "lengthMenu": "ສະແດງ _MENU_ ລາຍການ",
                "zeroRecords": "ບໍ່ພົບຂໍ້ມູນ",
                "info": "ສະແດງ _START_ ເຖິງ _END_ ຈາກ _TOTAL_ ລາຍການ",
                "infoEmpty": "ສະແດງ 0 ເຖິງ 0 ຈາກ 0 ລາຍການ",
                "infoFiltered": "(ກັ່ນຕອງຈາກ _MAX_ ລາຍການທັງໝົດ)",
                "search": "ຄົ້ນຫາ:",
                "paginate": {
                    "first": "ໜ້າທຳອິດ",
                    "last": "ໜ້າສຸດທ້າຍ",
                    "next": "ໜ້າຕໍ່ໄປ",
                    "previous": "ໜ້າກ່ອນ"
                }
            }
        });
    });
    </script>
</body>
</html>