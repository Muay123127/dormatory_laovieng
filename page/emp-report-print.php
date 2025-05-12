<?php
include_once 'login-check.php';
include_once 'laokip-read.php';
$department = "";
$where = "";
if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $where = empty($department) ? " " : "WHERE  d.dno = '$department' ";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../images/icon_logo.jpg">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/mystyle.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <!-- print -->
    <script src="../js/jquery.min.js"></script>

</head>

<body>
    <div class="container-fluid">
        <div class="text-center">
            <img src="images/Emble Logo.png" width="100px" alt="ຮູບກາຊາດ"> <br>
            ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ<br>
            ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ວັດທະນະຖາວອນ<br>
            <p>-----------
                <?php for ($i = 0; $i < 6; $i++) echo '<i class="far fa-star"></i>'; ?>
                -----------</p>
        </div>
        <div class="row">
            <div class="col-6">
                ວິທະຍາໄລ ລາວວຽງ<br>
                ຕັ້ງຢູ່ ບ້ານ ຄໍາຮຸ່ງ ຮ່ອມ 5 ມ.ໄຊທານີ<br>
                ນະຄອນຫຼວງວຽງຈັນ<br>
                ເບີໂທ.............<br>
            </div>
            <div class="col-6 text-end">
                ເລກທີ............/.........<br>
                ທີ່ ນະຄອນຫຼວງວຽງຈັນ, ວັນທີ.....................<br>
            </div>

            <p class="text-center fw-bold h5">ລາຍງານຂໍ້ມູນພະນັກງານ</p>
        </div>

        <table class="table table-bordered border-dark w-100">
            <thead class="bg-secondary text-white text-center">
                <tr>
                    <th>ລະຫັດ</th>
                    <th>ຊື່ ແລະ ນາມສະກຸນ</th>
                    <th>ເພດ</th>
                    <th>ເງິນເດືອນ</th>
                    <th>ເງິນອຸດໜູນ</th>
                    <th>ລາຍຮັບລວມ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $department = "";
                $sum = 0;
                $sql = "SELECT e.empno, e.name, e.gender, d.name AS department, s.sal, e.incentive, s.sal+e.incentive AS total "
                    . " FROM emp e JOIN dept d ON e.dno = d.dno JOIN salary s ON e.grade = s.grade $where ORDER BY department ASC, total DESC";

                $result = mysqli_query($link, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    //ສະແດງຊື່ຂອງພະແນກ ແລະ ຜົນບວກທັງໝົດເງິນເດືອນ ບວກ ເງິນອຸດອຸດໝູນ ຂອງພະແນກນັ້້ນ
                    if (strcmp($department, $row['department']) !== 0) {
                        $department = $row['department'];
                        $sql1 = "SELECT sum(s.sal+e.incentive) FROM emp e JOIN dept d ON e.dno=d.dno JOIN salary s ON e.grade=s.grade "
                            . " WHERE d.name='$department'";
                        $result1 = mysqli_query($link, $sql1);
                        $row1 = mysqli_fetch_array($result1);
                ?>
                        <tr>
                            <td colspan="5" class="fw-bold text-primary" style="background: #D9D9D9;"><?php echo "$department: " . LakLao($row1[0]) ?></td>
                            <td class="fw-bold text-primary text-end" style="background: #D9D9D9;"><?= number_format($row1[0], 0, ",", ".") ?> ກີບ</td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td class="text-center"><?= $row['empno'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td class="text-center"><?= $row['gender'] ?></td>
                        <td class="text-end"><?= number_format($row['sal'], 0, ",", ".") ?></td>
                        <td class="text-end"><?= number_format($row['incentive'], 0, ",", ".") ?></td>
                        <td class="text-end"><?= number_format($row['total'], 0, ",", ".") ?></td>
                    </tr>
                <?php
                    $sum += $row['total'];
                }
                ?>
                <tr>
                    <td colspan="5" class="fw-bold text-danger" style="background: #D9D9D9;">ລວມທັງໝົດ: <?= LakLao($sum) ?></td>
                    <td class="fw-bold text-danger text-end" style="background: #D9D9D9;"><?= number_format($sum, 0, ",", ".") ?> ກີບ</td>
                </tr>

            </tbody>
        </table>

        <table class="border-0 w-100">
            <tr>
                <td class="text-center fw-bold border-0">ລາຍເຊັນ3</td>
                <td class="text-center fw-bold border-0">ລາຍເຊັນ2</td>
                <td class="text-center fw-bold border-0">ລາຍເຊັນ1</td>
            </tr>
        </table>

        <table class="border-0 w-100" style="margin-top: 100px;">
            <tr>
                <td class="text-center fw-bold border-0">ລາຍເຊັນ4</td>
                <td class="text-center fw-bold border-0">ລາຍເຊັນ5</td>
                <td class="text-center fw-bold border-0">ລາຍເຊັນ6</td>
            </tr>
        </table>
                            
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
        window.onafterprint = window.close;
        window.print();
    });
</script>