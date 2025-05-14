<?php
require_once 'login-check.php';
require_once '../function/function.php';

$selected_room = "";
$room_info = null;

// ຖ້າກົດປຸ່ມຄົ້ນຫາຫ້ອງ
if (isset($_POST['btnSearchRoom']) || isset($_GET['room_id'])) {
    if (isset($_POST['Room_ID'])) {
        $selected_room = data_input($_POST['Room_ID']);
    } else if (isset($_GET['room_id'])) {
        $selected_room = data_input($_GET['room_id']);
    }
    
    // ດຶງຂໍ້ມູນຫ້ອງ
    $sql_room = "SELECT * FROM room WHERE Room_ID='$selected_room'";
    $result_room = mysqli_query($link, $sql_room);
    $room_info = mysqli_fetch_assoc($result_room);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລະບົບຈັດການຫໍພັກ - ເບິ່ງສະມາຊິກໃນຫ້ອງ</title>
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
                    <div class="card border-primary">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ເລືອກຫ້ອງເພື່ອເບິ່ງສະມາຊິກ</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="row g-3">
                                <div class="col-md-6">
                                    <label for="Room_ID" class="form-label">ເລືອກຫ້ອງ:</label>
                                    <select class="form-select" id="Room_ID" name="Room_ID" required>
                                        <option value="">----ເລືອກຫ້ອງ-----</option>
                                        <?php
                                        $sql = "SELECT r.Room_ID, r.R_number, r.Build, r.Persons, COUNT(b.Booking_ID) as member_count 
                                                FROM room r 
                                                LEFT JOIN booking b ON r.Room_ID = b.Room_ID AND b.Status='ເຂົ້າພັກແລ້ວ'
                                                GROUP BY r.Room_ID
                                                ORDER BY r.Build, r.R_number ASC";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_assoc($result)){
                                            $selected = ($row['Room_ID'] == $selected_room) ? 'selected' : '';
                                            echo "<option value='{$row['Room_ID']}' $selected>ຕຶກ {$row['Build']} - ຫ້ອງ {$row['R_number']} ({$row['member_count']} ຄົນ)</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" name="btnSearchRoom" class="btn btn-primary">
                                        <i class="fas fa-search"></i> ຄົ້ນຫາ
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <?php if ($room_info): ?>
                    <div class="card border-primary mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ຂໍ້ມູນຫ້ອງ: ຕຶກ <?php echo $room_info['Build'] ?> - ຫ້ອງ <?php echo $room_info['R_number'] ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>ລະຫັດຫ້ອງ:</th>
                                            <td><?php echo $room_info['Room_ID'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>ຕຶກ:</th>
                                            <td><?php echo $room_info['Build'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>ເບີຫ້ອງ:</th>
                                            <td><?php echo $room_info['R_number'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>ເລກໄຟ:</th>
                                            <td><?php echo $room_info['Meter_electrict'] ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>ລາຄາ:</th>
                                            <td><?php echo number_format($room_info['Price'], 0, ",", ".") ?> ກີບ</td>
                                        </tr>
                                        <tr>
                                            <th>ຈຳນວນຄົນຢູ່:</th>
                                            <td><?php echo $room_info['Persons'] ?> / <?php echo $room_info['TotalCapacity'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>ສະຖານະ:</th>
                                            <td>
                                                <?php 
                                                if ($room_info['Statuss'] == 'ວ່າງ') {
                                                    echo '<span class="badge bg-success">ວ່າງ</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">ບໍ່ວ່າງ</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>ປະເພດ:</th>
                                            <td><?php echo ($room_info['RoomType'] == 'ຊ') ? 'ຫ້ອງຊາຍ' : 'ຫ້ອງຍິງ' ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-primary mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ລາຍຊື່ນັກສຶກສາໃນຫ້ອງ</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="membersTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ລຳດັບ</th>
                                            <th>ລະຫັດການຈອງ</th>
                                            <th>ລະຫັດນັກສຶກສາ</th>
                                            <th>ຊື່-ນາມສະກຸນ</th>
                                            <th>ເບີໂທ</th>
                                            <th>ວັນທີເຂົ້າພັກ</th>
                                            <th>ໄລຍະເວລາພັກ</th>
                                            <th>ຈັດການ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT b.*, s.Stu_name, s.tell
                                                FROM booking b
                                                JOIN student s ON b.Stu_ID = s.Stu_ID
                                                WHERE b.Room_ID='$selected_room' AND b.Status='ເຂົ້າພັກແລ້ວ'
                                                ORDER BY b.Check_in ASC";
                                        $result = mysqli_query($link, $sql);
                                        $i = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // ຄຳນວນໄລຍະເວລາພັກ
                                            $check_in_date = new DateTime($row['Check_in']);
                                            $today = new DateTime();
                                            $interval = $check_in_date->diff($today);
                                            $days = $interval->days;
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row['Booking_ID']?></td>
                                            <td><?php echo $row['Stu_ID']?></td>
                                            <td><?php echo $row['Stu_name']?></td>
                                            <td><?php echo $row['tell']?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row['Check_in']))?></td>
                                            <td><?php echo $days?> ວັນ</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="room-move.php?booking_id=<?php echo $row['Booking_ID']?>" class="btn btn-warning btn-sm" title="ຍ້າຍຫ້ອງ">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </a>
                                                    <a href="checkout.php?booking_id=<?php echo $row['Booking_ID']?>" class="btn btn-danger btn-sm" title="ອອກພັກ">
                                                        <i class="fas fa-sign-out-alt"></i>
                                                    </a>
                                                    <a href="student-detail.php?stu_id=<?php echo $row['Stu_ID']?>" class="btn btn-info btn-sm" title="ເບິ່ງຂໍ້ມູນນັກສຶກສາ">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if (mysqli_num_rows($result) == 0): ?>
                                        <tr>
                                            <td colspan="8" class="text-center">ບໍ່ມີນັກສຶກສາໃນຫ້ອງນີ້</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- ຕາຕະລາງສະຫຼຸບຂໍ້ມູນຫ້ອງທັງໝົດ -->
                    <div class="card border-primary mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ສະຫຼຸບຂໍ້ມູນຫ້ອງທັງໝົດ</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="roomSummaryTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ລຳດັບ</th>
                                            <th>ຕຶກ</th>
                                            <th>ເບີຫ້ອງ</th>
                                            <th>ປະເພດ</th>
                                            <th>ຈຳນວນຄົນຢູ່</th>
                                            <th>ຄວາມຈຸ</th>
                                            <th>ສະຖານະ</th>
                                            <th>ຈັດການ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT r.*, COUNT(b.Booking_ID) as member_count 
                                                FROM room r 
                                                LEFT JOIN booking b ON r.Room_ID = b.Room_ID AND b.Status='ເຂົ້າພັກແລ້ວ'
                                                GROUP BY r.Room_ID
                                                ORDER BY r.Build, r.R_number ASC";
                                        $result = mysqli_query($link, $sql);
                                        $i = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row['Build']?></td>
                                            <td><?php echo $row['R_number']?></td>
                                            <td><?php echo ($row['RoomType'] == 'ຊ') ? 'ຫ້ອງຊາຍ' : 'ຫ້ອງຍິງ' ?></td>
                                            <td><?php echo $row['member_count']?></td>
                                            <td><?php echo $row['TotalCapacity']?></td>
                                            <td>
                                                <?php 
                                                if ($row['Statuss'] == 'ວ່າງ') {
                                                    echo '<span class="badge bg-success">ວ່າງ</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">ບໍ່ວ່າງ</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="room-members.php?room_id=<?php echo $row['Room_ID']?>" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-users"></i> ເບິ່ງສະມາຊິກ
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } ?>
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
        $('#membersTable').DataTable({
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
        
        $('#roomSummaryTable').DataTable({
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