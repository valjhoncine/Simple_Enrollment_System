<?php
$pageTitle = "500 Error";

ob_start();
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="text-center mt-4">
            <img class="img-fluid p-4" src="<?= BASE_URL ?>/assets/sb-admin/img/errors/500-internal-server-error.svg" alt="" />
            <p class="lead">The server encountered an internal error or misconfiguration and was unable to complete your request.</p>
            <a class="text-arrow-icon" href="javascript:history.back()">
                <i class="ms-0 me-1" data-feather="arrow-left"></i>
                Go Back
            </a>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/errors_layout.php';
?>
