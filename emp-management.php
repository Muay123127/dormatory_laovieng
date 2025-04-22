<?php
require_once 'login-check.php';

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
    <!-- ດຶງເມນູເຂົ້າມາ  -->
    <?php include_once 'menu.php' ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h4 class="mt-4">ຈັດການຂໍ້ມູນພະນັກງານ</h4>
                <p class="d-flex justify-content-end">
                    <a href="emp-add.php" class="btn btn-success"><i class="fas fa-plus-circle"></i>&nbsp;ເພີ້ມຂໍ້ມູນ</a>
                </p>

                <table id="example" class="table table-striped" style="width:100%">
                    <thead class="bg-secondary text-center text-white">
                        <tr>
                            <th>ລໍາດັບ</th>
                            <th>ລະຫັດພະນັກງານ</th>
                            <th>ຊື່ພະນັກງານ</th>
                            <th>ເພດ</th>
                            <th>ພະແນກ</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT e.empno, e.name, e.gender, d.name AS department 
                            FROM emp e JOIN dept d ON e.dno = d.dno ORDER BY e.empno DESC" ;
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_assoc($result)){
                            ?>
                
                        <tr>
                            <td class="text-center"><?= @++$number?></td>
                            <td class="text-center"><?=@$row['empno']?></td>
                            <td><?=@$row['name']?></td>
                            <td class="text-center"><?=@$row['gender']?></td>
                            <td><?=@$row['department']?></td>
                            <td class="text-center" style="width: 80px">
                                <a href="#"onclick="viewdata('<?= $row['empno'] ?>')" data-bs-toggle="tooltip" data-bs-placement="left" title="ລາຍລະອຽດ"><i class="fas fa-eye text-primary"></i></a>
                                <a href="emp-edit.php?empno=<?= $row['empno'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="ແກ້ໄຂຂໍ້ມູນ"><i class="fas fa-edit text-success"></i></a>
                                <a href="#" onclick="deletedata('<?= $row['empno'] ?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="ລືບຂໍ້ມູນ"><i class="fas fa-trash-alt text-danger"></i></a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- The Modal ສະແດງລາຍລະອຽດຂໍ້ມູນ-->
            <div class="modal fade" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">ລາຍລະອຽດຂໍ້ມູນພະນັກງານ</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal body ສະແດງລາຍລະອຽດ-->
                        <div class="modal-body" id="employee_detail">

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ປິດ</button>
                        </div>

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

    /**ສ້າງຟັງຊັນ ສະແດງລາຍລະອຽດຂໍ້ມູນພະນັກງານ */
    function viewdata(id) {
        //alert(id);
        $.ajax({
            url: "emp-view.php",
            method: "post",
            data: {
                empno: id
            },
            success: function(data) {
                $('#employee_detail').html(data);
                $('#myModal').modal("show");
            }
        });
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
                        url: "emp-delete.php",
                        method: "post",
                        data: {
                            empno: id
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