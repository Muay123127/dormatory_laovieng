<?php
require_once 'login-check.php';
require_once '../function/function.php';

// ຮັບຄ່າຈາກ URL
if (@$_GET['action'] == 'edit') {
    $Booking_ID = $_GET['Booking_ID'];
    $sql = "SELECT * FROM booking WHERE Booking_ID='$Booking_ID'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    
    $Stu_ID = $row['Stu_ID'];
    $Room_ID = $row['Room_ID'];
    $Booking_Date = $row['Booking_Date'];
    $Check_in = $row['Check_in'];
    $Status = $row['Status'];
}

// ຖ້າກົດປຸ່ມແກ້ໄຂຂໍ້ມູນ
if (isset($_POST['btnEdit'])) {
    $Booking_ID = data_input($_POST['Booking_ID']);
    $Stu_ID_new = data_input($_POST['Stu_ID']);
    $Room_ID_new = data_input($_POST['Room_ID']);
    $Check_in = data_input($_POST['Check_in']);
    $Status = data_input($_POST['Status']);
    
    // ຮັບຄ່າເກົ່າເພື່ອໃຊ້ໃນການປັບປຸງຂໍ້ມູນ
    $Stu_ID_old = data_input($_POST['Stu_ID_old']);
    $Room_ID_old = data_input($_POST['Room_ID_old']);
    
    // ກວດສອບວ່າມີການປ່ຽນແປງນັກສຶກສາຫຼືບໍ່
    if ($Stu_ID_new != $Stu_ID_old) {
        // ກວດສອບວ່ານັກສຶກສາໃໝ່ມີການຈອງຫ້ອງແລ້ວຫຼືບໍ່
        $sql_check = "SELECT * FROM booking WHERE Stu_ID='$Stu_ID_new' AND Status='ຈອງແລ້ວ' AND Booking_ID != '$Booking_ID'";
        $result_check = mysqli_query($link, $sql_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            $message = '<script>swal("ຜິດພາດ", "ນັກສຶກສາຄົນນີ້ໄດ້ຈອງຫ້ອງແລ້ວ", "error", {button: "ຕົກລົງ",});</script>';
            goto end_process;
        }
    }
    
    // ກວດສອບວ່າມີການປ່ຽນແປງຫ້ອງຫຼືບໍ່
    if ($Room_ID_new != $Room_ID_old) {
        // ກວດສອບວ່າຫ້ອງໃໝ່ວ່າງຫຼືບໍ່
        $sql_room = "SELECT * FROM room WHERE Room_ID='$Room_ID_new'";
        $result_room = mysqli_query($link, $sql_room);
        $room_data = mysqli_fetch_assoc($result_room);
        
        if ($room_data['Statuss'] != 'ວ່າງ' && $room_data['Room_ID'] != $Room_ID_old) {
            $message = '<script>swal("ຜິດພາດ", "ຫ້ອງນີ້ບໍ່ວ່າງ", "error", {button: "ຕົກລົງ",});</script>';
            goto end_process;
        }
    }
    
    // ອັບເດດຂໍ້ມູນການຈອງ
    $sql = "UPDATE booking SET Stu_ID='$Stu_ID_new', Room_ID='$Room_ID_new', Check_in='$Check_in', Status='$Status' 
            WHERE Booking_ID='$Booking_ID'";
    $result = mysqli_query($link, $sql);
    
    if ($result) {
        // ຖ້າມີການປ່ຽນແປງນັກສຶກສາ
        if ($Stu_ID_new != $Stu_ID_old) {
            // ອັບເດດສະຖານະນັກສຶກສາເກົ່າ
            $sql_student_old = "UPDATE student SET status='ຍັງບໍ່ເຂົ້າພັກ' WHERE Stu_ID='$Stu_ID_old'";
            mysqli_query($link, $sql_student_old);
            
            // ອັບເດດສະຖານະນັກສຶກສາໃໝ່
            $sql_student_new = "UPDATE student SET status='ເຂົ້າພັກແລ້ວ' WHERE Stu_ID='$Stu_ID_new'";
            mysqli_query($link, $sql_student_new);
        }
        
        // ຖ້າມີການປ່ຽນແປງຫ້ອງ
        if ($Room_ID_new != $Room_ID_old) {
            // ອັບເດດສະຖານະຫ້ອງເກົ່າ
            $sql_room_old = "UPDATE room SET Statuss='ວ່າງ', booked=booked-1 WHERE Room_ID='$Room_ID_old'";
            mysqli_query($link, $sql_room_old);
            
            // ອັບເດດສະຖານະຫ້ອງໃໝ່
            $sql_room_new = "UPDATE room SET Statuss='ຈອງແລ້ວ', booked=booked+1 WHERE Room_ID='$Room_ID_new'";
            mysqli_query($link, $sql_room_new);
        }
        
        // ຖ້າສະຖານະປ່ຽນເປັນຍົກເລີກ
        if ($Status == 'ຍົກເລີກ') {
            // ອັບເດດສະຖານະຫ້ອງ
            $sql_room_cancel = "UPDATE room SET Statuss='ວ່າງ', booked=booked-1 WHERE Room_ID='$Room_ID_new'";
            mysqli_query($link, $sql_room_cancel);
            
            // ອັບເດດສະຖານະນັກສຶກສາ
            $sql_student_cancel = "UPDATE student SET status='ຍັງບໍ່ເຂົ້າພັກ' WHERE Stu_ID='$Stu_ID_new'";
            mysqli_query($link, $sql_student_cancel);
        }
        
        header('location: booking-management.php');
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
    <title>ລະບົບຈັດການຫໍພັກ - ແກ້ໄຂຂໍ້ມູນການຈອງ</title>
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
                <div class="col-md-9">
                    <?php if (isset($message)) echo $message; ?>
                    <span class="d-flex justify-content-end mt-4">
                        <a href="booking-management.php" class="btn btn-secondary"> <i
                                class="fas fa-list"></i>&nbsp;ສະແດງຂໍ້ມູນການຈອງ</a>
                    </span>
                    <div class="card border-primary">
                        <div class="card-header bg-info text-white h5">ຟອມແກ້ໄຂຂໍ້ມູນການຈອງຫ້ອງພັກ</div>
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!--ລະຫັດການຈອງ-->
                                        <div class="mb-3">
                                            <label for="Booking_ID" class="form-label">ລະຫັດການຈອງ:</label>
                                            <input type="text" class="form-control" id="Booking_ID" name="Booking_ID"
                                                value="<?= @$Booking_ID ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!--ວັນທີຈອງ-->
                                        <div class="mb-3">
                                            <label for="Booking_Date" class="form-label">ວັນທີຈອງ:</label>
                                            <input type="date" class="form-control" id="Booking_Date"
                                                name="Booking_Date" value="<?= @$Booking_Date ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!--ລະຫັດນັກສຶກສາ-->
                                        <div class="mb-3">
                                            <label for="Stu_ID" class="form-label">ນັກສຶກສາ:</label>
                                            <input type="hidden" name="Stu_ID_old" value="<?= @$Stu_ID ?>">
                                            <select class="form-select" id="Stu_ID" name="Stu_ID" required>
                                                <?php
                                            // ດຶງຂໍ້ມູນນັກສຶກສາທີ່ກຳລັງແກ້ໄຂ
                                            $sql_current = "SELECT Stu_ID, Stu_name FROM student WHERE Stu_ID='$Stu_ID'";
                                            $result_current = mysqli_query($link, $sql_current);
                                            $row_current = mysqli_fetch_assoc($result_current);
                                            echo "<option value='{$row_current['Stu_ID']}'>{$row_current['Stu_name']} ({$row_current['Stu_ID']})</option>";
                                            
                                            // ດຶງຂໍ້ມູນນັກສຶກສາທີ່ຍັງບໍ່ໄດ້ຈອງ
                                            $sql = "SELECT Stu_ID, Stu_name FROM student WHERE status='ຍັງບໍ່ເຂົ້າພັກ' AND Stu_ID != '$Stu_ID' ORDER BY Stu_name ASC";
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
                                            <input type="hidden" name="Room_ID_old" value="<?= @$Room_ID ?>">
                                            <select class="form-select" id="Room_ID" name="Room_ID" required>
                                                <?php
                                            // ດຶງຂໍ້ມູນຫ້ອງທີ່ກຳລັງແກ້ໄຂ
                                            $sql_current = "SELECT Room_ID, R_number, Build FROM room WHERE Room_ID='$Room_ID'";
                                            $result_current = mysqli_query($link, $sql_current);
                                            $row_current = mysqli_fetch_assoc($result_current);
                                            echo "<option value='{$row_current['Room_ID']}'>ຕຶກ {$row_current['Build']} - ຫ້ອງ {$row_current['R_number']}</option>";
                                            
                                            // ດຶງຂໍ້ມູນຫ້ອງທີ່ວ່າງ
                                            $sql = "SELECT Room_ID, R_number, Build FROM room WHERE Statuss='ວ່າງ' AND Room_ID != '$Room_ID' ORDER BY Build, R_number ASC";
                                            $result = mysqli_query($link, $sql);
                                            while($row = mysqli_fetch_assoc($result)){
                                                echo "<option value='{$row['Room_ID']}'>ຕຶກ {$row['Build']} - ຫ້ອງ {$row['R_number']}</option>";
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!--ວັນທີເຂົ້າພັກ-->
                                        <div class="mb-3">
                                            <label for="Check_in" class="form-label">ວັນທີເຂົ້າພັກ:</label>
                                            <input type="date" class="form-control" id="Check_in" name="Check_in"
                                                value="<?= @$Check_in ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!--ສະຖານະ-->
                                        <div class="mb-3">
                                            <label for="Status" class="form-label">ສະຖານະ:</label>
                                            <select class="form-select" id="Status" name="Status" required>
                                                <option value="ຈອງແລ້ວ"
                                                    <?= (@$Status == 'ຈອງແລ້ວ') ? 'selected' : '' ?>>ຈອງແລ້ວ</option>
                                                <option value="ຍົກເລີກ"
                                                    <?= (@$Status == 'ຍົກເລີກ') ? 'selected' : '' ?>>ຍົກເລີກ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <!--ປຸ່ມ-->
                                        <input type="submit" name="btnEdit" value="ແກ້ໄຂຂໍ້ມູນ" class="btn btn-primary"
                                            style="width: 100px; border-radius: 20px">
                                        &nbsp;&nbsp;
                                        <a href="booking-management.php" class="btn btn-warning"
                                            style="width: 100px; border-radius: 20px">ຍົກເລີກ</a>
                                        &nbsp;&nbsp;
                                        <button type="button" onclick="window.location.reload(true)"
                                            class="btn btn-success"
                                            style="width: 100px; border-radius: 20px;">ໂຫຼດຄືນໃໝ່</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php include 'footer.php'; ?>
</body>

</html>