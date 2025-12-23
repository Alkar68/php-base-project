<div class="row align-items-center py-5">
    <div class="col-lg-6">
        <h1 class="display-4 fw-bold mb-3">
            Bienvenue sur <?= e($appName) ?>
        </h1>
        <p class="lead text-muted mb-4">
            Une application moderne développée avec PHP, Bootstrap 5 et architecture MVC.
        </p>
        <div class="d-flex gap-3">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="/register" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> S'inscrire
                </a>
                <a href="/login" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </a>
            <?php else: ?>
                <a href="/dashboard" class="btn btn-primary btn-lg">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-6">
        <img src="https://picsum.photos/600/400" alt="Hero" class="img-fluid rounded shadow">
    </div>
</div>

<div class="row py-5">
    <div class="col-12 mb-4">
        <h2 class="text-center mb-5">Fonctionnalités</h2>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="display-4 text-primary mb-3">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h5 class="card-title">Sécurité</h5>
                <p class="card-text text-muted">
                    Authentification sécurisée, protection CSRF, hachage bcrypt
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="display-4 text-success mb-3">
                    <i class="bi bi-people"></i>
                </div>
                <h5 class="card-title">Gestion utilisateurs</h5>
                <p class="card-text text-muted">
                    CRUD complet, gestion des rôles et permissions
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="display-4 text-warning mb-3">
                    <i class="bi bi-lightning"></i>
                </div>
                <h5 class="card-title">Performance</h5>
                <p class="card-text text-muted">
                    Architecture optimisée, routage efficace
                </p>
            </div>
        </div>
    </div>
</div>
