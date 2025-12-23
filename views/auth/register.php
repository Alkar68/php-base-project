<div class="card shadow">
    <div class="card-body p-5">
        <h2 class="text-center mb-4">
            <i class="bi bi-person-plus"></i> Inscription
        </h2>

        <form method="POST" action="/register">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstname" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <small class="text-muted">Minimum 8 caractères</small>
            </div>

            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-person-plus"></i> S'inscrire
            </button>

            <div class="text-center">
                <span class="text-muted">Déjà un compte ?</span>
                <a href="/login" class="text-decoration-none">Se connecter</a>
            </div>
        </form>
    </div>
</div>
