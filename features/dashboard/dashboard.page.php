<?php
gfGuardStudentEnrollment($routes);
require FEATURES_DIRECTORY . '/dashboard/DashboardService.php';

$dashboardService = new DashboardService($connection);

$totalCourse = $dashboardService->getTotalCourses();
$totalUsers = $dashboardService->getTotalUsers();
$totalStudents = $dashboardService->getTotalStudents();

$pageTitle = "Dashboard";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-start-lg border-start-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small fw-bold text-primary mb-1">Number of Courses</div>
                                    <div class="h3"><?= $totalCourse ?></div>
                                </div>
                                <div class="ms-2"><i class="fas fa-dollar-sign fa-2x text-gray-200"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-start-lg border-start-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small fw-bold text-primary mb-1">Number of Users</div>
                                    <div class="h3"><?= $totalUsers ?></div>
                                </div>
                                <div class="ms-2"><i class="fas fa-dollar-sign fa-2x text-gray-200"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-start-lg border-start-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small fw-bold text-primary mb-1">Number of Students</div>
                                    <div class="h3"><?= $totalStudents ?></div>
                                </div>
                                <div class="ms-2"><i class="fas fa-dollar-sign fa-2x text-gray-200"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/../includes/layouts/protected_layout.php';
?>
