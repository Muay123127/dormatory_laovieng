<?php
require_once 'login-check.php';
require_once 'function/function.php';

// ຖ້າກົດປຸ່ມ Check-in ສຳລັບການຈອງທີ່ມີຢູ່ແລ້ວ
if (isset($_POST['btnCheckinBooked'])) {
    $Booking_ID = data_input($_POST['Booking_ID']);
    $Check_in_date = date('Y-m-d'); // ວັນທີປັດຈຸບັນ
    
    // ອັບເດດຂໍ້ມູນການຈອງ
    $sql = "UPDATE booking SET Status='ເຂົ້າພັກແລ້ວ', Check_in='$Check_in_date' WHERE Booking_ID='$Booking_ID'";
    $result = mysqli_query($link, $sql);
    
    if ($result) {
        // ດຶງຂໍ້ມູນການຈອງ
        $sql_booking = "SELECT Stu_ID, Room_ID FROM booking WHERE Booking_ID='$Booking_ID'";
        $result_booking = mysqli_query($link, $sql_booking);
        $booking_data = mysqli_fetch_assoc($result_booking);
        
        // ອັບເດດສະຖານະຫ້ອງ
        $sql_room = "UPDATE room SET Statuss='ບໍ່ວ່າງ' WHERE Room_ID='{$booking_data['Room_ID']}'";
        mysqli_query($link, $sql_room);
        
        // ອັບເດດສະຖານະນັກສຶກສາ
        $sql_student = "UPDATE student SET status='ເຂົ້າພັກແລ້ວ' WHERE Stu_ID='{$booking_data['Stu_ID']}'";
        mysqli_query($link, $sql_student);
        
        $message = '<script>swal("ສໍາເລັດ", "ການເຂົ້າພັກສໍາເລັດແລ້ວ", "success", {button: "ຕົກລົງ",});</script>';
    } else {
        echo mysqli_error($link);
    }
}

// ຖ້າກົດປຸ່ມ Check-in ສຳລັບການເຂົ້າພັກແບບ Walk-in
if (isset($_POST['btnCheckinWalkin'])) {
    $Stu_ID = data_input($_POST['Stu_ID']);
    $Room_ID = data_input($_POST['Room_ID']);
    $Check_in_date = date('Y-m-d'); // ວັນທີປັດຈຸບັນ
    
    // ກວດສອບວ່ານັກສຶກສາມີການຈອງຫ້ອງແລ້ວຫຼືບໍ່
    $sql_check = "SELECT * FROM booking WHERE Stu_ID='$Stu_ID' AND (Status='ຈອງແລ້ວ' OR Status='ເຂົ້າພັກແລ້ວ')";
    $result_check = mysqli_query($link, $sql_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        $message = '<script>swal("ຜິດພາດ", "ນັກສຶກສາຄົນນີ້ໄດ້ຈອງຫຼືເຂົ້າພັກແລ້ວ", "error", {button: "ຕົກລົງ",});</script>';
        goto end_process;
    }
    
    // ກວດສອບວ່າຫ້ອງວ່າງຫຼືບໍ່
    $sql_room = "SELECT * FROM room WHERE Room_ID='$Room_ID'";
    $result_room = mysqli_query($link, $sql_room);
    $room_data = mysqli_fetch_assoc($result_room);
    
    if ($room_data['Statuss'] != 'ວ່າງ') {
        $message = '<script>swal("ຜິດພາດ", "ຫ້ອງນີ້ບໍ່ວ່າງ", "error", {button: "ຕົກລົງ",});</script>';
        goto end_process;
    }
    
    // ສ້າງລະຫັດການຈອງໃໝ່
    $Booking_ID = "";
    $sql = "SELECT MAX(SUBSTRING(Booking_ID, 3)) AS max_id FROM booking";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    if (empty($row['max_id'])) {
        $Booking_ID = "BK0001";
    } else {
        $id = $row['max_id'] + 1;
        $Booking_ID = "BK" . sprintf("%04d", $id);
    }
    
    // ເພີ່ມຂໍ້ມູນການຈອງແບບ Walk-in
    $sql = "INSERT INTO booking (Booking_ID, Stu_ID, Room_ID, Booking_Date, Check_in, Status) 
            VALUES ('$Booking_ID', '$Stu_ID', '$Room_ID', '$Check_in_date', '$Check_in_date', 'ເຂົ້າພັກແລ້ວ')";
    $result = mysqli_query($link, $sql);
    
    if ($result) {
        // ອັບເດດສະຖານະຫ້ອງ
        $sql_room_update = "UPDATE room SET Statuss='ບໍ່ວ່າງ', booked=booked+1 WHERE Room_ID='$Room_ID'";
        mysqli_query($link, $sql_room_update);
        
        // ອັບເດດສະຖານະນັກສຶກສາ
        $sql_student = "UPDATE student SET status='ເຂົ້າພັກແລ້ວ' WHERE Stu_ID='$Stu_ID'";
        mysqli_query($link, $sql_student);
        
        $message = '<script>swal("ສໍາເລັດ", "ການເຂົ້າພັກແບບ Walk-in ສໍາເລັດແລ້ວ", "success", {button: "ຕົກລົງ",});</script>';
        $Stu_ID = $Room_ID = null;
    } else {
        echo mysqli_error($link);
    }
}

end_process:
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລະບົບຈັດການຫໍພັກ - ການເຂົ້າພັກ</title>
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
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($message)) echo $message; ?>
                    
                    <!-- ແຖບເມນູ -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="booked-tab" data-bs-toggle="tab" data-bs-target="#booked" type="button" role="tab" aria-controls="booked" aria-selected="true">ເຂົ້າພັກຈາກການຈອງ</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="walkin-tab" data-bs-toggle="tab" data-bs-target="#walkin" type="button" role="tab" aria-controls="walkin" aria-selected="false">ເຂົ້າພັກແບບ Walk-in</button>
                        </li>
                    </ul>
                    
                    <!-- ເນື້ອຫາແຖບເມນູ -->
                    <div class="tab-content" id="myTabContent">
                        <!-- ແຖບເຂົ້າພັກຈາກການຈອງ -->
                        <div class="tab-pane fade show active" id="booked" role="tabpanel" aria-labelledby="booked-tab">
                            <div class="card border-primary mt-3">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title">ລາຍການຈອງທີ່ລໍຖ້າການເຂົ້າພັກ</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="bookingTable" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ລະຫັດການຈອງ</th>
                                                    <th>ຊື່ນັກສຶກສາ</th>
                                                    <th>ຫ້ອງພັກ</th>
                                                    <th>ວັນທີຈອງ</th>
                                                    <th>ວັນທີເຂົ້າພັກ (ຕາມແຜນ)</th>
                                                    <th>ຈັດການ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT b.*, s.Stu_name, r.R_number, r.Build
                                                        FROM booking b
                                                        JOIN student s ON b.Stu_ID = s.Stu_ID
                                                        JOIN room r ON b.Room_ID = r.Room_ID
                                                        WHERE b.Status='ຈອງແລ້ວ'
                                                        ORDER BY b.Check_in ASC";
                                                $result = mysqli_query($link, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['Booking_ID']?></td>
                                                    <td><?php echo $row['Stu_name']?></td>
                                                    <td>ຕຶກ <?php echo $row['Build']?> - ຫ້ອງ <?php echo $row['R_number']?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($row['Booking_Date']))?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($row['Check_in']))?></td>
                                                    <td>
                                                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                                            <input type="hidden" name="Booking_ID" value="<?php echo $row['Booking_ID']?>">
                                                            <button type="submit" name="btnCheckinBooked" class="btn btn-success btn-sm" onclick="return confirm('ຢືນຢັນການເຂົ້າພັກ?')">
                                                                <i class="fas fa-check-circle"></i> ເຂົ້າພັກ
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ແຖບເຂົ້າພັກແບບ Walk-in -->
                        <div class="tab-pane fade" id="walkin" role="tabpanel" aria-labelledby="walkin-tab">
                            <div class="card border-primary mt-3">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title">ຟອມເຂົ້າພັກແບບ Walk-in</h5>
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!--ລະຫັດນັກສຶກສາ-->
                                                <div class="mb-3">
                                                    <label for="Stu_ID" class="form-label">ນັກສຶກສາ:</label>
                                                    <select class="form-select" id="Stu_ID" name="Stu_ID" required>
                                                        <option value="">----ເລືອກນັກສຶກສາ-----</option>
                                                        <?php
                                                        $sql = "SELECT Stu_ID, Stu_name FROM student WHERE status='ຍັງບໍ່ເຂົ້າພັກ' ORDER BY Stu_name ASC";
                                                        $result = mysqli_query($link, $sql);
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            echo "<option value='{$row['Stu_ID']}'>{$row['Stu_name']} ({$row['Stu_ID']})</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!--ລະຫັດຫ້ອງ-->
                                                <div class="mb-3">
                                                    <label for="Room_ID" class="form-label">ຫ້ອງພັກ:</label>
                                                    <select class="form-select" id="Room_ID" name="Room_ID" required>
                                                        <option value="">----ເລືອກຫ້ອງພັກ-----</option>
                                                        <?php
                                                        $sql = "SELECT Room_ID, R_number, Build FROM room WHERE Statuss='ວ່າງ' ORDER BY Build, R_number ASC";
                                                        $result = mysqli_query($link, $sql);
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            echo "<option value='{$row['Room_ID']}'>ຕຶກ {$row['Build']} - ຫ້ອງ {$row['R_number']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-center">
                                                <!--ປຸ່ມ-->
                                                <input type="submit" name="btnCheckinWalkin" value="ເຂົ້າພັກ" class="btn btn-primary" style="width: 100px; border-radius: 20px">
                                                &nbsp;&nbsp;
                                                <input type="reset" value="ຍົກເລີກ" class="btn btn-warning" style="width: 100px; border-radius: 20px">
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
        $('#bookingTable').DataTable({
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