<?php
use app\Core\Session;

$messages = [
    'success' => Session::flash('success'),
    'error' => Session::flash('error'),
    'warning' => Session::flash('warning'),
    'info' => Session::flash('info')
];

$icons = [
    'success' => 'check-circle-fill',
    'error' => 'exclamation-triangle-fill',
    'warning' => 'exclamation-circle-fill',
    'info' => 'info-circle-fill'
];

foreach ($messages as $type => $message):
    if ($message):
        ?>
        <div class="alert alert-<?= e($type) ?> alert-dismissible fade show" role="alert">
            <i class="bi bi-<?= e($icons[$type]) ?> me-2"></i>
            <?= e($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php
    endif;
endforeach;
?>
