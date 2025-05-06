<?php
    require_once 'login-check.php';
    require_once 'function/function.php';

    // ຖ້າມີການລຶບຂໍ້ມູນ
    if (@$_GET['action'] == 'delete') {
        $Booking_ID = $_GET['Booking_ID'];

        // ຮັບຂໍ້ມູນຫ້ອງແລະນັກສຶກສາກ່ອນລຶບ
        $sql_get      = "SELECT Room_ID, Stu_ID FROM booking WHERE Booking_ID='$Booking_ID'";
        $result_get   = mysqli_query($link, $sql_get);
        $booking_data = mysqli_fetch_assoc($result_get);
        $Room_ID      = $booking_data['Room_ID'];
        $Stu_ID       = $booking_data['Stu_ID'];

        // ລຶບຂໍ້ມູນການຈອງ
        $sql    = "DELETE FROM booking WHERE Booking_ID='$Booking_ID'";
        $result = mysqli_query($link, $sql);

        if ($result) {
            // ອັບເດດສະຖານະຫ້ອງ
            $sql_room = "UPDATE room SET Statuss='ວ່າງ', booked=booked-1 WHERE Room_ID='$Room_ID'";
            mysqli_query($link, $sql_room);

            // ອັບເດດສະຖານະນັກສຶກສາ
            $sql_student = "UPDATE student SET status='ຍັງບໍ່ເຂົ້າພັກ' WHERE Stu_ID='$Stu_ID'";
            mysqli_query($link, $sql_student);

            header("location: booking-management.php");
        } else {
            echo mysqli_error($link);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ລະບົບຈັດການຫໍພັກ - ຈັດການຂໍ້ມູນການຈອງ</title>
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
                <div class="card border-primary">
                    <div class="card-header bg-info text-white">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">ຂໍ້ມູນການຈອງຫ້ອງພັກ</h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="booking-add.php" class="btn btn-light"><i class="fas fa-plus-circle"></i>
                                    ເພີ່ມຂໍ້ມູນ</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ລະຫັດການຈອງ</th>
                                        <th>ຊື່ນັກສຶກສາ</th>
                                        <th>ຫ້ອງພັກ</th>
                                        <th>ວັນທີຈອງ</th>
                                        <th>ວັນທີເຂົ້າພັກ</th>
                                        <th>ສະຖານະ</th>
                                        <th>ຈັດການ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "SELECT b.*, s.Stu_name, r.R_number, r.Build
                                            FROM booking b
                                            JOIN student s ON b.Stu_ID = s.Stu_ID
                                            JOIN room r ON b.Room_ID = r.Room_ID
                                            ORDER BY b.Booking_Date DESC";
                                        $result = mysqli_query($link, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                    <tr>
                                        <td><?php echo $row['Booking_ID']?></td>
                                        <td><?php echo $row['Stu_name']?></td>
                                        <td>ຕຶກ <?php echo $row['Build']?> - ຫ້ອງ <?php echo $row['R_number']?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['Booking_Date']))?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['Check_in']))?></td>
                                        <td><?php echo $row['Status']?></td>
                                        <td>
                                            <a href="booking-edit.php?Booking_ID=<?php echo $row['Booking_ID']?>&action=edit"
                                                class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="booking-management.php?Booking_ID=<?php echo $row['Booking_ID']?>&action=delete"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('ທ່ານຕ້ອງການລຶບຂໍ້ມູນນີ້ແທ້ບໍ່?')"><i
                                                    class="fas fa-trash-alt"></i></a>
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
        $('#example').DataTable({
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