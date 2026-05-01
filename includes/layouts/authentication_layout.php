<!DOCTYPE html>
<html lang="en">

<head>
    <?php include INCLUDES_DIRECTORY . '/partials/head.php'; ?>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <?= $content ?>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="footer-admin mt-auto footer-dark">
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
