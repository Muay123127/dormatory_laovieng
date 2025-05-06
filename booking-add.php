<?php
require_once 'login-check.php';
require_once 'function/function.php';

//ສ້າງຟັງຊັນເພື່ອ ສ້າງລະຫັດອັດຕະໂນມັດ
function autoID()
{
    $Booking_ID = "";
    global $link;

    $sql = "SELECT Max(Booking_ID) AS Booking_ID FROM booking";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    if (empty($row['Booking_ID'])) {
        $Booking_ID = "BK0001";
    } else {
        $id = substr($row['Booking_ID'], 2, strlen($row['Booking_ID']));
        $id++;
        $Booking_ID = "BK" . sprintf("%04d", $id);
    }
    return $Booking_ID;
}
//ເອີ້ນໃຊ້ autoID   
autoID();

//ຖ້າກົດປຸ່ມເພີ້ມຂໍ້ມູນ
if (isset($_POST['btnAdd'])) {
    $Booking_ID = autoID();
    $Stu_ID = data_input($_POST['Stu_ID']);
    $Room_ID = data_input($_POST['Room_ID']);
    $Booking_Date = date('Y-m-d');
    $Check_in = data_input($_POST['Check_in']);
    $Status = "ຈອງແລ້ວ";
    
    // ກວດສອບວ່ານັກສຶກສາມີການຈອງຫ້ອງແລ້ວຫຼືບໍ່
    $sql_check = "SELECT * FROM booking WHERE Stu_ID='$Stu_ID' AND Status='ຈອງແລ້ວ'";
    $result_check = mysqli_query($link, $sql_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        $message = '<script>swal("ຜິດພາດ", "ນັກສຶກສາຄົນນີ້ໄດ້ຈອງຫ້ອງແລ້ວ", "error", {button: "ຕົກລົງ",});</script>';
    } else {
        // ກວດສອບວ່າຫ້ອງວ່າງຫຼືບໍ່
        $sql_room = "SELECT * FROM room WHERE Room_ID='$Room_ID'";
        $result_room = mysqli_query($link, $sql_room);
        $room_data = mysqli_fetch_assoc($result_room);
        
        if ($room_data['Statuss'] != 'ວ່າງ') {
            $message = '<script>swal("ຜິດພາດ", "ຫ້ອງນີ້ບໍ່ວ່າງ", "error", {button: "ຕົກລົງ",});</script>';
        } else {
            // ເພີ່ມຂໍ້ມູນການຈອງ
            $sql = "INSERT INTO booking (Booking_ID, Stu_ID, Room_ID, Booking_Date, Check_in, Status) 
                    VALUES ('$Booking_ID', '$Stu_ID', '$Room_ID', '$Booking_Date', '$Check_in', '$Status')";
            $result = mysqli_query($link, $sql);
            
            if ($result) {
                // ອັບເດດສະຖານະຫ້ອງ
                $sql_update = "UPDATE room SET Statuss='ຈອງແລ້ວ', booked=booked+1 WHERE Room_ID='$Room_ID'";
                mysqli_query($link, $sql_update);
                
                // ອັບເດດສະຖານະນັກສຶກສາ
                $sql_student = "UPDATE student SET status='ເຂົ້າພັກແລ້ວ' WHERE Stu_ID='$Stu_ID'";
                mysqli_query($link, $sql_student);
                
                $message = '<script>swal("ສໍາເລັດ", "ຂໍ້ມູນການຈອງບັນທຶກລົງຖານຂໍ້ມູນແລ້ວ", "success", {button: "ຕົກລົງ",});</script>';
                $Stu_ID = $Room_ID = $Check_in = null;
            } else {
                echo mysqli_error($link);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລະບົບຈັດການຫໍພັກ - ເພີ່ມຂໍ້ມູນການຈອງ</title>
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
            <div class="col-md-9">
                <?php if (isset($message)) echo $message; ?>
                <span class="d-flex justify-content-end mt-4">
                    <a href="booking-management.php" class="btn btn-secondary"> <i class="fas fa-list"></i>&nbsp;ສະແດງຂໍ້ມູນການຈອງ</a>
                </span>
                <div class="card border-primary">
                    <div class="card-header bg-info text-white h5">ຟອມປ້ອນຂໍ້ມູນການຈອງຫ້ອງພັກ</div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <!--ລະຫັດການຈອງ-->
                                    <div class="mb-3">
                                        <label for="Booking_ID" class="form-label">ລະຫັດການຈອງ:</label>
                                        <input type="text" class="form-control" id="Booking_ID" name="Booking_ID" value="<?= autoID() ?>" readonly>
                                    </div>
                                </div>
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
                                            $sql = "SELECT Room_ID, R_number, Build FROM room WHERE  (TotalCapacity+booked) > 5 ORDER BY Build, R_number ASC";
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
                                        <input type="date" class="form-control" id="Check_in" name="Check_in" required>
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