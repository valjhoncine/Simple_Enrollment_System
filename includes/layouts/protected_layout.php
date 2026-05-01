<!DOCTYPE html>
<html lang="en">

<head>
    <?php include INCLUDES_DIRECTORY . '/partials/head.php'; ?>
</head>

<body class="nav-fixed">
    <nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
        <!-- Sidenav Toggle Button-->
        <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
        <!-- Navbar Brand-->
        <!-- * * Tip * * You can use text or an image for your navbar brand.-->
        <!-- * * * * * * When using an image, we recommend the SVG format.-->
        <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
        <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="<?= getRouteUrl($routes, "dashboard") ?>">Online Enrollment</a>
        <!-- Navbar Items-->
        <ul class="navbar-nav align-items-center ms-auto">
            <!-- User Dropdown-->
            <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src="<?= BASE_URL ?>/assets/sb-admin/img/profiles/profile-1.png" /></a>
                <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                    <h6 class="dropdown-header d-flex align-items-center">
                        <img class="dropdown-user-img" src="<?= BASE_URL ?>/assets/sb-admin/img/profiles/profile-1.png" />
                        <div class="dropdown-user-details">
                            <div class="dropdown-user-details-name"><?= $currentUserFullname ?? "" ?></div>
                            <div class="dropdown-user-details-email"><?= $currentUserEmail ?? "" ?></div>
                        </div>
                    </h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#!">
                        <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                        Account
                    </a>
                    <form method="post" action="<?= getRouteUrl($routes, "logout") ?>">
                        <input type="hidden" name="action" value="logout" required>
                        <button type="submit" class="dropdown-item">
                            <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sidenav shadow-right sidenav-light">
                <div class="sidenav-menu">
                    <div class="nav accordion" id="accordionSidenav">

                        <!-- Sidenav Heading (Addons)-->
                        <div class="sidenav-menu-heading"></div>
                        <!-- Sidenav Link (Charts)-->
                        <a class="nav-link <?= ($activeSideNavigation == "dashboard") ? "active" : "" ?>" href="<?= getRouteUrl($routes, "dashboard") ?>">
                            <div class="nav-link-icon"><i data-feather="bar-chart"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link <?= ($activeSideNavigation == "users") ? "active" : "" ?>" href="<?= getRouteUrl($routes, "users") ?>">
                            <div class="nav-link-icon"><i data-feather="bar-chart"></i></div>
                            Users
                        </a>
                    </div>
                </div>
                <!-- Sidenav Footer-->
                <div class="sidenav-footer">
                    <div class="sidenav-footer-content">
                        <div class="sidenav-footer-subtitle">Logged in as:</div>
                        <div class="sidenav-footer-title"><?= $currentUserFullname ?? "" ?></div>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                    <div class="container-xl px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="file"></i></div>
                                        <?= ucfirst($activeSideNavigation) ?>
                                    </h1>
                                </div>
                                <!-- <div class="col-12 col-xl-auto mb-3"></div> -->
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <?= $content ?>
            </main>
            <footer class="footer-admin mt-auto footer-light">
                <?php include INCLUDES_DIRECTORY . '/partials/footer.php'; ?>
            </footer>
        </div>
    </div>

    <?php
    include INCLUDES_DIRECTORY . '/partials/scripts.php';
    if (isset($scripts)) {
        echo $scripts;
    }
    ?>
</body>

</html>
