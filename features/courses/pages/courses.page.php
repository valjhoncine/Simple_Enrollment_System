<?php
require FEATURES_DIRECTORY . '/courses/functions/courses.function.php';
$pageTitle = "Courses";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <!-- <div class="card-header">Example Card</div> -->
        <div class="card-body">
            <a class="btn btn-primary mb-2" href="<?= getRouteUrl($routes, 'courses-create') ?>">Add New Course</a>
            <table class="table table-bordered table-striped" id="tableCourses"></table>
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

<script src="<?= BASE_URL ?>/js/courses.js"></script>

<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
?>
