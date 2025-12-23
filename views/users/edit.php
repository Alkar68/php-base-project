<div class="row">
    <div class="col-12 mb-4">
        <h1 class="h3">
            <i class="bi bi-pencil"></i> Modifier l'utilisateur
        </h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/users/<?= e($user->getId()) ?>/update">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstname" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="firstname" name="firstname"
                           value="<?= e($user->getFirstname()) ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="lastname" name="lastname"
                           value="<?= e($user->getLastname()) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= e($user->getEmail()) ?>" required>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Rôle</label>
                <select class="form-select" id="role_id" name="role_id" required>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= e($role->getId()) ?>"
                            <?= $user->getRoleId() === $role->getId() ? 'selected' : '' ?>>
                            <?= e($role->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                       value="1" <?= $user->isActive() ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Compte actif</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
                <a href="/users" class="btn btn-secondary">
                    <i class="bi bi-x"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
