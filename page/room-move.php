<?php
require_once 'login-check.php';
require_once '../function/function.php';

// ຖ້າກົດປຸ່ມຍ້າຍຫ້ອງ
if (isset($_POST['btnMoveRoom'])) {
    $Booking_ID = data_input($_POST['Booking_ID']);
    $New_Room_ID = data_input($_POST['New_Room_ID']);
    $Move_Date = date('Y-m-d'); // ວັນທີປັດຈຸບັນ
    
    // ດຶງຂໍ້ມູນການຈອງເກົ່າ
    $sql_booking = "SELECT Stu_ID, Room_ID FROM booking WHERE Booking_ID='$Booking_ID'";
    $result_booking = mysqli_query($link, $sql_booking);
    $booking_data = mysqli_fetch_assoc($result_booking);
    $Old_Room_ID = $booking_data['Room_ID'];
    $Stu_ID = $booking_data['Stu_ID'];
    
    // ກວດສອບວ່າຫ້ອງໃໝ່ວ່າງຫຼືບໍ່
    $sql_room = "SELECT * FROM room WHERE Room_ID='$New_Room_ID'";
    $result_room = mysqli_query($link, $sql_room);
    $room_data = mysqli_fetch_assoc($result_room);
    
    if ($room_data['Statuss'] != 'ວ່າງ') {
        $message = '<script>swal("ຜິດພາດ", "ຫ້ອງໃໝ່ບໍ່ວ່າງ", "error", {button: "ຕົກລົງ",});</script>';
        goto end_process;
    }
    
    // ບັນທຶກປະຫວັດການຍ້າຍຫ້ອງ
    $Move_ID = "";
    $sql = "SELECT MAX(SUBSTRING(Move_ID, 3)) AS max_id FROM room_move_history";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    if (empty($row['max_id'])) {
        $Move_ID = "MV0001";
    } else {
        $id = $row['max_id'] + 1;
        $Move_ID = "MV" . sprintf("%04d", $id);
    }
    
    $sql_history = "INSERT INTO room_move_history (Move_ID, Booking_ID, Old_Room_ID, New_Room_ID, Move_Date) 
                    VALUES ('$Move_ID', '$Booking_ID', '$Old_Room_ID', '$New_Room_ID', '$Move_Date')";
    $result_history = mysqli_query($link, $sql_history);
    
    if ($result_history) {
        // ອັບເດດຂໍ້ມູນການຈອງ
        $sql = "UPDATE booking SET Room_ID='$New_Room_ID' WHERE Booking_ID='$Booking_ID'";
        $result = mysqli_query($link, $sql);
        
        if ($result) {
            // ອັບເດດສະຖານະຫ້ອງເກົ່າ
            $sql_old_room = "UPDATE room SET Statuss='ວ່າງ', Persons=Persons-1 WHERE Room_ID='$Old_Room_ID'";
            mysqli_query($link, $sql_old_room);
            
            // ອັບເດດສະຖານະຫ້ອງໃໝ່
            $sql_new_room = "UPDATE room SET Statuss='ບໍ່ວ່າງ', Persons=Persons+1 WHERE Room_ID='$New_Room_ID'";
            mysqli_query($link, $sql_new_room);
            
            $message = '<script>swal("ສໍາເລັດ", "ການຍ້າຍຫ້ອງສໍາເລັດແລ້ວ", "success", {button: "ຕົກລົງ",});</script>';
        } else {
            echo mysqli_error($link);
        }
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

end_process:
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລະບົບຈັດການຫໍພັກ - ຍ້າຍຫ້ອງ</title>
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
                                <table id="moveRoomTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ລະຫັດການຈອງ</th>
                                            <th>ລະຫັດນັກສຶກສາ</th>
                                            <th>ຊື່ນັກສຶກສາ</th>
                                            <th>ຫ້ອງພັກປັດຈຸບັນ</th>
                                            <th>ວັນທີເຂົ້າພັກ</th>
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
                                        ?>
                                        <tr>
                                            <td><?php echo $row['Booking_ID']?></td>
                                            <td><?php echo $row['Stu_ID']?></td>
                                            <td><?php echo $row['Stu_name']?></td>
                                            <td>ຕຶກ <?php echo $row['Build']?> - ຫ້ອງ <?php echo $row['R_number']?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row['Check_in']))?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#moveRoomModal<?php echo $row['Booking_ID']?>">
                                                    <i class="fas fa-exchange-alt"></i> ຍ້າຍຫ້ອງ
                                                </button>
                                                
                                                <!-- Modal ຍ້າຍຫ້ອງ -->
                                                <div class="modal fade" id="moveRoomModal<?php echo $row['Booking_ID']?>" tabindex="-1" aria-labelledby="moveRoomModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title" id="moveRoomModalLabel">ຍ້າຍຫ້ອງ</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="moveRoomForm<?php echo $row['Booking_ID']?>">
                                                                    <input type="hidden" name="Booking_ID" value="<?php echo $row['Booking_ID']?>">
                                                                    
                                                                    <div class="mb-3">
                                                                        <label class="form-label">ນັກສຶກສາ:</label>
                                                                        <input type="text" class="form-control" value="<?php echo $row['Stu_name']?>" readonly>
                                                                    </div>
                                                                    
                                                                    <div class="mb-3">
                                                                        <label class="form-label">ຫ້ອງພັກປັດຈຸບັນ:</label>
                                                                        <input type="text" class="form-control" value="ຕຶກ <?php echo $row['Build']?> - ຫ້ອງ <?php echo $row['R_number']?>" readonly>
                                                                    </div>
                                                                    
                                                                    <div class="mb-3">
                                                                        <label for="New_Room_ID" class="form-label">ຫ້ອງພັກໃໝ່:</label>
                                                                        <select class="form-select" id="New_Room_ID" name="New_Room_ID" required>
                                                                            <option value="">----ເລືອກຫ້ອງພັກໃໝ່-----</option>
                                                                            <?php
                                                                            $sql_rooms = "SELECT Room_ID, R_number, Build FROM room WHERE Statuss='ວ່າງ' ORDER BY Build, R_number ASC";
                                                                            $result_rooms = mysqli_query($link, $sql_rooms);
                                                                            while($room = mysqli_fetch_assoc($result_rooms)){
                                                                                echo "<option value='{$room['Room_ID']}'>ຕຶກ {$room['Build']} - ຫ້ອງ {$room['R_number']}</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ຍົກເລີກ</button>
                                                                <button type="submit" form="moveRoomForm<?php echo $row['Booking_ID']?>" name="btnMoveRoom" class="btn btn-primary">ຍ້າຍຫ້ອງ</button>
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
                    
                    <!-- ປະຫວັດການຍ້າຍຫ້ອງ -->
                    <div class="card border-primary mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ປະຫວັດການຍ້າຍຫ້ອງ</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="historyTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ລະຫັດການຍ້າຍ</th>
                                            <th>ລະຫັດການຈອງ</th>
                                            <th>ຊື່ນັກສຶກສາ</th>
                                            <th>ຫ້ອງເກົ່າ</th>
                                            <th>ຫ້ອງໃໝ່</th>
                                            <th>ວັນທີຍ້າຍ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT h.*, b.Stu_ID, s.Stu_name, 
                                                r1.R_number as old_room_number, r1.Build as old_building,
                                                r2.R_number as new_room_number, r2.Build as new_building
                                                FROM room_move_history h
                                                JOIN booking b ON h.Booking_ID = b.Booking_ID
                                                JOIN student s ON b.Stu_ID = s.Stu_ID
                                                JOIN room r1 ON h.Old_Room_ID = r1.Room_ID
                                                JOIN room r2 ON h.New_Room_ID = r2.Room_ID
                                                ORDER BY h.Move_Date DESC";
                                        $result = mysqli_query($link, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['Move_ID']?></td>
                                            <td><?php echo $row['Booking_ID']?></td>
                                            <td><?php echo $row['Stu_name']?></td>
                                            <td>ຕຶກ <?php echo $row['old_building']?> - ຫ້ອງ <?php echo $row['old_room_number']?></td>
                                            <td>ຕຶກ <?php echo $row['new_building']?> - ຫ້ອງ <?php echo $row['new_room_number']?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row['Move_Date']))?></td>
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
        $('#moveRoomTable').DataTable({
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
        
        $('#historyTable').DataTable({
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