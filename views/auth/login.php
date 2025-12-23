<div class="card shadow">
    <div class="card-body p-5">
        <h2 class="text-center mb-4">
            <i class="bi bi-box-arrow-in-right"></i> Connexion
        </h2>

        <form method="POST" action="/login">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>

            <div class="mb-3 text-end">
                <a href="/forgot-password" class="text-decoration-none">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-box-arrow-in-right"></i> Se connecter
            </button>

            <div class="text-center">
                <span class="text-muted">Pas encore de compte ?</span>
                <a href="/register" class="text-decoration-none">S'inscrire</a>
            </div>
        </form>
    </div>
</div>
