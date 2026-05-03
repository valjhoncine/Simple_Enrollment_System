<?php
require FEATURES_DIRECTORY . '/subjects/SubjectService.php';

if ($_SERVER['REQUEST_METHOD'] === HTTP_GET && isset($_GET['action']) && $_GET['action'] === 'subjects') {

    $subjectService = new SubjectService($connection);
    $subjects = $subjectService->getSubjects();

    apiResponse(true, $subjects);
}

$pageTitle = "Subjects";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <!-- <div class="card-header">Example Card</div> -->
        <div class="card-body">
            <a class="btn btn-primary mb-2" href="<?= getRouteUrl($routes, 'subjects-create') ?>">Add New Subject</a>
            <table class="table table-bordered table-striped" id="tableSubjects"></table>
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

<script src="<?= BASE_URL ?>/js/subjects.js"></script>

<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
?>
