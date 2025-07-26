<?php
// Sécurité
if (!defined('INDEX_PRINCIPAL')) {
    header('Location: ../index.php');
    exit;
}
?>

<div class="admin-page-header">
    <div>
        <h1><i class="fas fa-user-cog"></i> Gestion de mon compte</h1>
        <p>Modifiez vos informations personnelles et votre mot de passe</p>
    </div>
</div>

<?php if (!empty($messageSucces)) : ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo htmlspecialchars($messageSucces); ?>
    </div>
<?php endif; ?>

<?php if (!empty($messageErreur)) : ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <?php echo htmlspecialchars($messageErreur); ?>
    </div>
<?php endif; ?>

<div class="admin-container-grid">
    <!-- Informations du compte -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Informations du compte</h3>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Nom d'utilisateur :</label>
                    <span><?php echo htmlspecialchars($adminInfo['login']); ?></span>
                </div>
                <div class="info-item">
                    <label>Rôle :</label>
                    <span class="badge badge-primary"><?php echo htmlspecialchars($adminInfo['role']); ?></span>
                </div>
                <div class="info-item">
                    <label>Dernière connexion :</label>
                    <span><?php echo htmlspecialchars($adminInfo['derniere_connexion']); ?></span>
                </div>
                <div class="info-item">
                    <label>Compte créé le :</label>
                    <span><?php echo htmlspecialchars($adminInfo['date_creation']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-edit"></i> Modifier mes informations</h3>
        </div>
        <div class="card-content">
            <form method="post" action="index.php?controleur=Admin&action=gererProfil" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="login">Nom d'utilisateur *</label>
                        <input type="text" name="login" id="login" class="form-control" 
                               value="<?php echo htmlspecialchars($adminInfo['login']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse email *</label>
                        <input type="email" name="email" id="email" class="form-control" 
                               value="<?php echo htmlspecialchars($adminInfo['email']); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" name="nom" id="nom" class="form-control" 
                               value="<?php echo htmlspecialchars($adminInfo['nom']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" name="prenom" id="prenom" class="form-control" 
                               value="<?php echo htmlspecialchars($adminInfo['prenom']); ?>" required>
                    </div>
                </div>

                <div class="form-section">
                    <h4><i class="fas fa-lock"></i> Changer le mot de passe</h4>
                    <p class="form-help">Laissez vide si vous ne souhaitez pas changer votre mot de passe</p>
                    
                    <div class="form-group">
                        <label for="mot_de_passe_actuel">Mot de passe actuel</label>
                        <input type="password" name="mot_de_passe_actuel" id="mot_de_passe_actuel" class="form-control">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nouveau_mot_de_passe">Nouveau mot de passe</label>
                            <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" class="form-control">
                            <small class="form-help">Minimum 8 caractères</small>
                        </div>
                        <div class="form-group">
                            <label for="confirmation_mot_de_passe">Confirmation du nouveau mot de passe</label>
                            <input type="password" name="confirmation_mot_de_passe" id="confirmation_mot_de_passe" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                    <a href="index.php?controleur=Admin&action=afficherIndex" class="btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.admin-container-grid {
    display: grid;
    gap: 2rem;
    margin-top: 2rem;
}

.admin-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
}

.card-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.card-content {
    padding: 2rem;
}

.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.info-item label {
    font-weight: 600;
    color: #495057;
}

.info-item span {
    color: #6c757d;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-primary {
    background: #667eea;
    color: white;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.form-section h4 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-help {
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style> 