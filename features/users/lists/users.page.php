<?php
$pageTitle = "Users";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">Users Page Table</div>
        <div class="card-body">
            This is a blank page. You can use this page as a boilerplate for creating new pages! This page uses the compact page header format, which allows you to create pages with a very minimal and slim page header so you can get right to showcasing your content.
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/../../includes/layouts/protected_layout.php';
?>
