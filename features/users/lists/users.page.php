<?php
include FEATURES_DIRECTORY . '/users/functions/users.function.php';
$pageTitle = "Users";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <!-- <div class="card-header">Users Page Table</div> -->
        <div class="card-body">
            <a class="btn btn-primary mb-2" href="<?= getRouteUrl($routes, 'users-create') ?>">Add New User</a>
            <table class="table table-bordered table-striped" id="tableUsers"></table>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$scripts = ob_start();
?>
<script src="<?= BASE_URL ?>/js/users.js"></script>
<?php
$scripts = ob_get_clean();
include dirname(__DIR__) . '/../../includes/layouts/protected_layout.php';
?>
