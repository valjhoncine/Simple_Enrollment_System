<?php
require FEATURES_DIRECTORY . '/enrollment/EnrollmentService.php';

$enrollmentService = new EnrollmentService($connection);
if ($_SERVER['REQUEST_METHOD'] === HTTP_GET && isset($_GET['action']) && $_GET['action'] === 'enrollments') {
    $response = $enrollmentService->getEnrollmentApplications(gfGetCourseId());

    apiResponse(true, $response);
}

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST['action']) && $_POST['action'] === 'approve_enrollment') {
    $request = $_POST;

    $enrollment = $enrollmentService->getEnrollmentById($request["enrollment_id"]);
    if (!$enrollment) {
        apiResponse(false, null, [REQUEST_RESOURCE_NOT_FOUND => "Application not found."]);
    }

    $enrollment->approveOrDecline(1, Auth::id());
    try {
        $response = $enrollmentService->approveEnrollment($enrollment);
        if ($response) {
            apiResponse(true, $response);
        } else {
            throw new Exception(INSERT_FAILED);
        }
    } catch (Exception $ex) {
        apiResponse(false, null, [INSERT_FAILED => "Cannot process request, an unexpected error occurred."]);
    }
}
if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST['action']) && $_POST['action'] === 'decline_enrollment') {
    $request = $_POST;

    $enrollment = $enrollmentService->getEnrollmentById($request["enrollment_id"]);
    if (!$enrollment) {
        apiResponse(false, null, [REQUEST_RESOURCE_NOT_FOUND => "Application not found."]);
    }

    $enrollment->approveOrDecline(-1, Auth::id());
    try {
        $response = $enrollmentService->approveEnrollment($enrollment);
        if ($response) {
            apiResponse(true, $response);
        } else {
            throw new Exception(INSERT_FAILED);
        }
    } catch (Exception $ex) {
        apiResponse(false, null, [INSERT_FAILED => "Cannot process request, an unexpected error occurred."]);
    }
}

$pageTitle = "Enrollment Applications";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <!-- <div class="card-header">Example Card</div> -->
        <div class="card-body">
            <table class="table table-bordered table-striped" id="tableEnrollmentApplications"></table>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$scripts = ob_start();
?>

<script src="<?= BASE_URL ?>/js/enrollment-application.js"></script>

<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
?>
