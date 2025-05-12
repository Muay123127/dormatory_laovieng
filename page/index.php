<?php
require_once 'login-check.php';
require_once '../function/function.php';

// ດຶງຂໍ້ມູນສະຖິຕິຕ່າງໆ
// 1. ຈຳນວນນັກສຶກສາທັງໝົດ
$sql_students = "SELECT COUNT(*) as total FROM student";
$result_students = mysqli_query($link, $sql_students);
$total_students = mysqli_fetch_assoc($result_students)['total'];

// 2. ຈຳນວນຫ້ອງທັງໝົດ
$sql_rooms = "SELECT COUNT(*) as total FROM room";
$result_rooms = mysqli_query($link, $sql_rooms);
$total_rooms = mysqli_fetch_assoc($result_rooms)['total'];

// 3. ຈຳນວນຫ້ອງທີ່ວ່າງ
$sql_available_rooms = "SELECT COUNT(*) as total FROM room WHERE Statuss='ວ່າງ'";
$result_available_rooms = mysqli_query($link, $sql_available_rooms);
$available_rooms = mysqli_fetch_assoc($result_available_rooms)['total'];

// 4. ຈຳນວນນັກສຶກສາທີ່ກຳລັງພັກຢູ່
$sql_active_students = "SELECT COUNT(*) as total FROM booking WHERE Status='ເຂົ້າພັກແລ້ວ'";
$result_active_students = mysqli_query($link, $sql_active_students);
$active_students = mysqli_fetch_assoc($result_active_students)['total'];

// 5. ຂໍ້ມູນການຈອງຫ້ອງລ່າສຸດ
$sql_recent_bookings = "SELECT b.*, s.Stu_name, r.R_number, r.Build
                        FROM booking b
                        JOIN student s ON b.Stu_ID = s.Stu_ID
                        JOIN room r ON b.Room_ID = r.Room_ID
                        ORDER BY b.Booking_Date DESC
                        LIMIT 5";
$result_recent_bookings = mysqli_query($link, $sql_recent_bookings);

// 6. ຂໍ້ມູນການຍ້າຍຫ້ອງລ່າສຸດ
$sql_recent_moves = "SELECT h.*, s.Stu_name, 
                    r1.R_number as old_room_number, r1.Build as old_building,
                    r2.R_number as new_room_number, r2.Build as new_building
                    FROM room_move_history h
                    JOIN booking b ON h.Booking_ID = b.Booking_ID
                    JOIN student s ON b.Stu_ID = s.Stu_ID
                    JOIN room r1 ON h.Old_Room_ID = r1.Room_ID
                    JOIN room r2 ON h.New_Room_ID = r2.Room_ID
                    ORDER BY h.Move_Date DESC
                    LIMIT 5";
$result_recent_moves = mysqli_query($link, $sql_recent_moves);

// 7. ຂໍ້ມູນສຳລັບກຣາຟວົງກົມສະແດງອັດຕາການເຂົ້າພັກ
$sql_occupancy = "SELECT 
                    SUM(CASE WHEN Statuss = 'ວ່າງ' THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN Statuss = 'ບໍ່ວ່າງ' THEN 1 ELSE 0 END) as occupied
                  FROM room";
$result_occupancy = mysqli_query($link, $sql_occupancy);
$occupancy_data = mysqli_fetch_assoc($result_occupancy);

// 8. ຂໍ້ມູນສຳລັບກຣາຟແທ່ງສະແດງຈຳນວນນັກສຶກສາແຍກຕາມຕຶກ
$sql_building_stats = "SELECT r.Build, COUNT(b.Booking_ID) as student_count
                      FROM room r
                      LEFT JOIN booking b ON r.Room_ID = b.Room_ID AND b.Status = 'ເຂົ້າພັກແລ້ວ'
                      GROUP BY r.Build
                      ORDER BY r.Build";
$result_building_stats = mysqli_query($link, $sql_building_stats);
$building_labels = [];
$building_data = [];
while ($row = mysqli_fetch_assoc($result_building_stats)) {
    $building_labels[] = 'ຕຶກ ' . $row['Build'];
    $building_data[] = $row['student_count'];
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

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/mystyle.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>

    <!-- datatable -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap5.min.js"></script>

    <!-- Chart.js -->
    <script src="../js/Chart.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <!-- ດຶງເມນູເຂົ້າມາ  -->
    <?php include_once 'menu.php' ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                
                <!-- ແຖບສະຖິຕິ -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?php echo number_format($total_students); ?></h5>
                                        <div>ນັກສຶກສາທັງໝົດ</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-users fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="student-management.php">ເບິ່ງລາຍລະອຽດ</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?php echo number_format($active_students); ?></h5>
                                        <div>ນັກສຶກສາທີ່ກຳລັງພັກຢູ່</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-bed fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="room-members.php">ເບິ່ງລາຍລະອຽດ</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?php echo number_format($total_rooms); ?></h5>
                                        <div>ຫ້ອງທັງໝົດ</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-door-open fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="room.php">ເບິ່ງລາຍລະອຽດ</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-danger text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?php echo number_format($available_rooms); ?></h5>
                                        <div>ຫ້ອງທີ່ວ່າງ</div>
                                    </div>
                                    <div>
                                        <i class="fas fa-door-closed fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="room-members.php">ເບິ່ງລາຍລະອຽດ</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- ກຣາຟ -->
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-pie me-1"></i>
                                ອັດຕາການເຂົ້າພັກຂອງຫ້ອງ
                            </div>
                            <div class="card-body">
                                <canvas id="occupancyChart" width="100%" height="40"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-bar me-1"></i>
                                ຈຳນວນນັກສຶກສາແຍກຕາມຕຶກ
                            </div>
                            <div class="card-body">
                                <canvas id="buildingChart" width="100%" height="40"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- ຕາຕະລາງຂໍ້ມູນ -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-history me-1"></i>
                                ການຈອງຫ້ອງລ່າສຸດ
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ລະຫັດການຈອງ</th>
                                                <th>ຊື່ນັກສຶກສາ</th>
                                                <th>ຫ້ອງ</th>
                                                <th>ວັນທີຈອງ</th>
                                                <th>ສະຖານະ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result_recent_bookings)) { ?>
                                            <tr>
                                                <td><?php echo $row['Booking_ID'] ?></td>
                                                <td><?php echo $row['Stu_name'] ?></td>
                                                <td>ຕຶກ <?php echo $row['Build'] ?> - ຫ້ອງ <?php echo $row['R_number'] ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($row['Booking_Date'])) ?></td>
                                                <td>
                                                    <?php 
                                                    if ($row['Status'] == 'ຈອງແລ້ວ') {
                                                        echo '<span class="badge bg-warning">ຈອງແລ້ວ</span>';
                                                    } elseif ($row['Status'] == 'ເຂົ້າພັກແລ້ວ') {
                                                        echo '<span class="badge bg-success">ເຂົ້າພັກແລ້ວ</span>';
                                                    } elseif ($row['Status'] == 'ຍົກເລີກ') {
                                                        echo '<span class="badge bg-danger">ຍົກເລີກ</span>';
                                                    } elseif ($row['Status'] == 'ອອກແລ້ວ') {
                                                        echo '<span class="badge bg-secondary">ອອກແລ້ວ</span>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php if (mysqli_num_rows($result_recent_bookings) == 0) { ?>
                                            <tr>
                                                <td colspan="5" class="text-center">ບໍ່ມີຂໍ້ມູນການຈອງ</td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-2">
                                    <a href="booking-management.php" class="btn btn-sm btn-primary">ເບິ່ງທັງໝົດ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-exchange-alt me-1"></i>
                                ການຍ້າຍຫ້ອງລ່າສຸດ
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ລະຫັດການຍ້າຍ</th>
                                                <th>ຊື່ນັກສຶກສາ</th>
                                                <th>ຈາກຫ້ອງ</th>
                                                <th>ໄປຫ້ອງ</th>
                                                <th>ວັນທີຍ້າຍ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result_recent_moves)) { ?>
                                            <tr>
                                                <td><?php echo $row['Move_ID'] ?></td>
                                                <td><?php echo $row['Stu_name'] ?></td>
                                                <td>ຕຶກ <?php echo $row['old_building'] ?> - ຫ້ອງ <?php echo $row['old_room_number'] ?></td>
                                                <td>ຕຶກ <?php echo $row['new_building'] ?> - ຫ້ອງ <?php echo $row['new_room_number'] ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($row['Move_Date'])) ?></td>
                                            </tr>
                                            <?php } ?>
                                            <?php if (mysqli_num_rows($result_recent_moves) == 0) { ?>
                                            <tr>
                                                <td colspan="5" class="text-center">ບໍ່ມີຂໍ້ມູນການຍ້າຍຫ້ອງ</td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-2">
                                    <a href="room-move.php" class="btn btn-sm btn-primary">ເບິ່ງທັງໝົດ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- ຂໍ້ມູນຫ້ອງທີ່ວ່າງ -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-door-open me-1"></i>
                        ຫ້ອງທີ່ວ່າງ
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="availableRoomsTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ລຳດັບ</th>
                                        <th>ຕຶກ</th>
                                        <th>ເບີຫ້ອງ</th>
                                        <th>ປະເພດ</th>
                                        <th>ຄວາມຈຸ</th>
                                        <th>ລາຄາ</th>
                                        <th>ຈັດການ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM room WHERE Statuss='ວ່າງ' ORDER BY Build, R_number ASC";
                                    $result = mysqli_query($link, $sql);
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $row['Build']?></td>
                                        <td><?php echo $row['R_number']?></td>
                                        <td><?php echo ($row['RoomType'] == 'ຊ') ? 'ຫ້ອງຊາຍ' : 'ຫ້ອງຍິງ' ?></td>
                                        <td><?php echo $row['TotalCapacity']?></td>
                                        <td><?php echo number_format($row['Price'], 0, ",", ".")?> ກີບ</td>
                                        <td>
                                            <a href="booking-add.php?room_id=<?php echo $row['Room_ID']?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus-circle"></i> ຈອງຫ້ອງ
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php if (mysqli_num_rows($result) == 0) { ?>
                                    <tr>
                                        <td colspan="7" class="text-center">ບໍ່ມີຫ້ອງວ່າງ</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- footer -->
        <?php include_once 'footer.php' ?>
    </div>

    <script>
    $(document).ready(function() {
        $('#availableRoomsTable').DataTable({
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
        
        // ກຣາຟວົງກົມສະແດງອັດຕາການເຂົ້າພັກ
        var occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
        var occupancyChart = new Chart(occupancyCtx, {
            type: 'pie',
            data: {
                labels: ['ຫ້ອງວ່າງ', 'ຫ້ອງບໍ່ວ່າງ'],
                datasets: [{
                    data: [<?php echo $occupancy_data['available']; ?>, <?php echo $occupancy_data['occupied']; ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                            return data.labels[tooltipItem.index] + ': ' + currentValue + ' ຫ້ອງ (' + percentage + "%)";
                        }
                    }
                }
            }
        });
        
        // ກຣາຟແທ່ງສະແດງຈຳນວນນັກສຶກສາແຍກຕາມຕຶກ
        var buildingCtx = document.getElementById('buildingChart').getContext('2d');
        var buildingChart = new Chart(buildingCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($building_labels); ?>,
                datasets: [{
                    label: 'ຈຳນວນນັກສຶກສາ',
                    data: <?php echo json_encode($building_data); ?>,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {if (value % 1 === 0) {return value;}}
                        }
                    }]
                }
            }
        });
    });
    </script>
</body>
</html>
