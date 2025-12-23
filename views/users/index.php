<div class="row">
    <div class="col-12 mb-4">
        <h1 class="h3">
            <i class="bi bi-people"></i> Gestion des utilisateurs
        </h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Nom</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= e($user->getId()) ?></td>
                        <td><?= e($user->getEmail()) ?></td>
                        <td><?= e($user->getFullName()) ?></td>
                        <td>
                            <span class="badge bg-secondary">ID: <?= e($user->getRoleId()) ?></span>
                        </td>
                        <td>
                            <?php if ($user->isActive()): ?>
                                <span class="badge bg-success">Actif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $user->getCreatedAt()?->format('d/m/Y') ?></td>
                        <td>
                            <a href="/users/<?= e($user->getId()) ?>" class="btn btn-sm btn-info" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/users/<?= e($user->getId()) ?>/edit" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="/users/<?= e($user->getId()) ?>/delete" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
