<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Navbar Brand-->
    <!--<a class="navbar-brand ps-3" href="index.php"><i style="color:gold; font-family: Times New Roman">ຫໍພັກວິທະຍາໄລ ລາວວຽງ</i></a> -->

    <a class="navbar-brand ps-3 d-flex flex-column align-items-start" href="index.php">
        <img src="images/logo.png" alt="Logo" style="height: 60px; width: auto; margin-top: 50px; ">
        <span
            style="color: white; font-family: 'Phetsarath OT', sans-serif; font-weight: bold; font-size: 1.2rem; margin-top: 5px;">

            ວິທະຍາໄລ ລາວວຽງ
        </span>
    </a>
    </a>

    <!-- Sidebar Toggle-->
    <!-- <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button> -->
    <!-- Navbar Search-->

    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <!-- <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>  -->
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i
                    class="fas fa-user"></i></a>
            <ul class="dropdown-menu dropdown-menu-end">
                <?php
                if (! empty($_SESSION['username']) && ! empty($_SESSION['password'])) {
                    echo '<li><a class="dropdown-item" href="profile.php">ໂປຣໄຟລ໌</a></li>';
                    if ($_SESSION['role'] == 'admin') {
                        echo '<li><a class="dropdown-item" href="register.php">ຈັດການຜູ້ໃຊ້ລະບົບ</a></li>';
                    }
                    echo '<li><a class="dropdown-item" href="logout.php">ອອກຈາກໃຊ້ລະບົບ</a></li>';
                } else {
                    echo '<li><a class="dropdown-item" href="login-form.php "> ເຂົ້າໃຊ້ລະບົບ</a></li>';
                }
            ?>

            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading"></div>
                    <div class="sb-sidenav-menu-heading"></div>
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                        ໜ້າຫຼັກ
                    </a>


                    <?php
                if ($_SESSION['role'] == 'admin') {?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapsemanagement" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-database"></i></div>
                        ຈັດການຂໍ້ມູນ

                    </a>
                    <div class="collapse" id="collapsemanagement" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="room.php">ຈັດການຂໍ້ມູນຫ້ອງ</a>
                            <a class="nav-link" href="salary.php">ຈັດການຂໍ້ມູນຂັ້ນເງິນເດືອນ</a>
                            <a class="nav-link" href="department.php">ຈັດການຂໍ້ມູນພະແນກ</a>
                            <a class="nav-link" href="customer.php">ຈັດການຂໍ້ມູນພາກຮຽນ</a>
                            <a class="nav-link" href="supplier.php">ຈັດການຂໍ້ມູນສາຂາຮຽນ</a>

                            <a class="nav-link" href="emp-management.php">ຈັດການຂໍ້ມູນພະນັກງານ</a>
                        </nav>
                    </div>
                    <?php }
              ?>
                    <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsereport" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                        ຫນ້າວຽກ
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a> -->

                    <a class="nav-link" href="student-management.php">
                        <div class="sb-nav-link-icon"><i class="bi bi-person-lines-fill"></i></div>
                        ຂໍ້ມູນນັກສຶກສາ
                    </a>




                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                        aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        ໜ້າວຽກ
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="booking-management.php">ຈອງຫ້ອງ</a>
                            <a class="nav-link" href="checkin.php">ເຂົ້າພັກ</a>
                            <a class="nav-link" href="#">ອອກພັກ</a>
                            <a class="nav-link" href="#">ຍ້າຍຫ້ອງ</a>
                            <a class="nav-link" href="#">ເບິ່ງສະມາຊິກໃນຫ້ອງ</a>
                        </nav>
                    </div>

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsereport"
                        aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                        ລາຍງານ
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsereport" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="emp-report.php">ລາຍງານຂໍ້ມູນພະນັກງານ</a>
                            <a class="nav-link" href="#">ລາຍງານຂໍ້ມູນພະແນກ</a>
                            <a class="nav-link" href="#">ລາຍງານຂໍ້ມູນຂັ້ນເງິນເດືອນ</a>
                        </nav>
                    </div>
                    <div class="sb-sidenav-menu-heading">ADDONS</div>
                    <a class="nav-link" href="chart.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        ເສັ້ນກຣາບ Chart.js
                    </a>
                    <a class="nav-link" href="highChart.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        ເສັ້ນກຣາບ HighCharts
                    </a>
                    <a class="nav-link" href="#">
                        <div class="sb-nav-link-icon"><i class="fas fa-phone-volume"></i></div>
                        ຕິດຕໍ່ພວກເຮົາ
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">ຜູ້ເຂົ້າໃຊ້ລະບົບ: <?php echo @$_SESSION['name'] ?>
                </div>
            </div>
        </nav>
    </div>