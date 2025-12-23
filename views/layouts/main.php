<!DOCTYPE html>
<html lang="<?= e($_ENV['APP_LOCALE'] ?? 'fr') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= e($csrfToken ?? '') ?>">
    <title><?= e($title ?? 'Application') ?> - <?= e($_ENV['APP_NAME']) ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container my-4">
    <?php require __DIR__ . '/../partials/flash.php'; ?>
    <?= $content ?>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/app.js"></script>
</body>
</html>
