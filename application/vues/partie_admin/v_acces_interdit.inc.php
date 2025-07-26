<div class="admin-login-container">
    <div class="admin-login-card">
        <div class="admin-header" style="background: linear-gradient(135deg, #dc3545, #c82333);">
            <div class="admin-logo">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2>Accès refusé</h2>
            <p>Identifiants incorrects</p>
        </div>

        <div class="admin-form">
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                Les identifiants que vous avez saisis sont incorrects.
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="index.php?controleur=Admin&action=afficherIndex" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la connexion
                </a>
            </div>
        </div>

        <div class="admin-footer">
            <a href="index.php" class="back-link">
                <i class="fas fa-home"></i>
                Retour au site
            </a>
        </div>
    </div>
</div>

<style>
    .alert-error {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
        margin-bottom: var(--spacing-lg);
        padding: var(--spacing-sm);
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
    }
</style>

