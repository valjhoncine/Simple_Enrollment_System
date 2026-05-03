<?php
require FEATURES_DIRECTORY . '/schedules/ScheduleService.php';

if ($_SERVER['REQUEST_METHOD'] === HTTP_GET && isset($_GET['action']) && $_GET['action'] === 'schedules') {

    $scheduleService = new ScheduleService($connection);
    $response = $scheduleService->getSchedules();

    apiResponse(true, $response);
}

$pageTitle = "Subject Schedules";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <!-- <div class="card-header">Example Card</div> -->
        <div class="card-body">
            <a class="btn btn-primary mb-2" href="<?= getRouteUrl($routes, 'schedules-create') ?>">Add New Schedule</a>
            <table class="table table-bordered table-striped" id="tableSchedules"></table>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$scripts = ob_start();
?>

<?php
if (isset($_SESSION[REQUEST_RESOURCE_NOT_FOUND])) {
    $ifMes = $_SESSION[REQUEST_RESOURCE_NOT_FOUND];
    unset($_SESSION[REQUEST_RESOURCE_NOT_FOUND]);
?>
    <script>
        Swal.fire({
            title: "<?= $ifMes ?>",
            icon: "error"
        });
    </script>
<?php
}
?>

<script src="<?= BASE_URL ?>/js/schedules.js"></script>

<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
?>
