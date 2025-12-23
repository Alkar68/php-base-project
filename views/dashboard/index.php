<div class="row">
    <div class="col-12 mb-4">
        <h1 class="h3">
            <i class="bi bi-speedometer2"></i> Tableau de bord
        </h1>
        <p class="text-muted">Bienvenue <?= e($user->getFullName()) ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Utilisateurs</h6>
                        <h2 class="mb-0"><?= e($totalUsers) ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Statut</h6>
                        <h5 class="mb-0">Actif</h5>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Rôle</h6>
                        <h5 class="mb-0">ID: <?= e($user->getRoleId()) ?></h5>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-shield"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Dernière connexion</h6>
                        <small><?= $user->getLastLoginAt()?->format('d/m/Y H:i') ?? 'N/A' ?></small>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Activité récente</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Aucune activité récente</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Profil</h5>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> <?= e($user->getEmail()) ?></p>
                <p><strong>Nom:</strong> <?= e($user->getFullName()) ?></p>
                <p class="mb-0"><strong>Inscrit le:</strong> <?= $user->getCreatedAt()?->format('d/m/Y') ?></p>
            </div>
        </div>
    </div>
</div>
