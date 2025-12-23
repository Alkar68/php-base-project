<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5><?= e(config('APP_NAME')) ?></h5>
                <p class="text-muted">Application développée avec PHP</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">
                    &copy; <?= date('Y') ?> <?= e(config('APP_NAME')) ?>. Tous droits réservés.
                </p>
            </div>
        </div>
    </div>
</footer>
