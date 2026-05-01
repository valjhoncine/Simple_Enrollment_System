<!DOCTYPE html>
<html lang="en">

<head>
    <?php include INCLUDES_DIRECTORY . '/partials/head.php'; ?>
</head>

<body class="bg-white">
    <div id="layoutError">
        <div id="layoutError_content">
            <main>
                <div class="container-xl px-4">
                    <?= $content ?>
                </div>
            </main>
        </div>
        <div id="layoutError_footer">
            <footer class="footer-admin mt-auto footer-light">
                <?php include INCLUDES_DIRECTORY . '/partials/footer.php'; ?>
            </footer>
        </div>
    </div>

    <?php include INCLUDES_DIRECTORY . '/partials/scripts.php'; ?>
</body>

</html>
