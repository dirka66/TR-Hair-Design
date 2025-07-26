<?php
// Sécurité
if (!defined('INDEX_PRINCIPAL')) {
    header('Location: ../index.php');
    exit;
}
?>
<div class="admin-page-header">
    <div>
        <?php if (isset($produit)): ?>
            <h1><i class="fas fa-edit"></i> Modifier un service</h1>
            <p>Modifiez les informations du service</p>
        <?php else: ?>
            <h1><i class="fas fa-plus"></i> Ajouter un service</h1>
            <p>Créez un nouveau service pour votre salon</p>
        <?php endif; ?>
    </div>
    <div class="page-actions">
        <a href="index.php?controleur=ProduitsModerne&action=listerProduits" class="btn-primary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
</div>
<?php if (!empty($messageErreur)) : ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <?php echo htmlspecialchars($messageErreur); ?>
    </div>
<?php endif; ?>

<?php if (empty($familles)): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        Aucune catégorie de service n'est disponible. Veuillez en créer une avant d'ajouter un service.
    </div>
<?php endif; ?>

<div class="admin-form">
    <form method="post" action="index.php?controleur=ProduitsModerne&action=sauvegarderProduit">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
        <div class="form-group">
            <label for="nomProduit">Nom du service *</label>
            <input type="text" name="nomProduit" id="nomProduit" class="form-control" 
                   value="<?php echo htmlspecialchars($produit->nomProduit ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"><?php echo htmlspecialchars($produit->description ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="prix">Prix (€) *</label>
            <input type="number" step="0.01" name="prix" id="prix" class="form-control" 
                   value="<?php echo htmlspecialchars($produit->prix ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="duree">Durée (minutes) *</label>
            <input type="number" name="duree" id="duree" class="form-control" 
                   value="<?php echo htmlspecialchars($produit->duree ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="idFamille">Catégorie *</label>
            <select name="idFamille" id="idFamille" class="form-control" required>
                <option value="">Sélectionner une catégorie</option>
                <?php foreach ($familles as $famille): ?>
                    <option value="<?php echo $famille->idFamille; ?>" 
                            <?php echo (isset($produit->idFamille) && $produit->idFamille == $famille->idFamille) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($famille->nomFamille); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="position">Position (ordre d'affichage)</label>
            <input type="number" name="position" id="position" class="form-control" min="1" 
                   value="<?php echo htmlspecialchars($produit->position ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="actif">Statut</label>
            <select name="actif" id="actif" class="form-control">
                <option value="1" <?php echo (isset($produit->actif) && $produit->actif == 1) ? 'selected' : ''; ?>>Actif</option>
                <option value="0" <?php echo (isset($produit->actif) && $produit->actif == 0) ? 'selected' : ''; ?>>Inactif</option>
            </select>
        </div>
        <div class="form-group">
            <label for="populaire">Populaire</label>
            <select name="populaire" id="populaire" class="form-control">
                <option value="0" <?php echo (isset($produit->populaire) && $produit->populaire == 0) ? 'selected' : ''; ?>>Non</option>
                <option value="1" <?php echo (isset($produit->populaire) && $produit->populaire == 1) ? 'selected' : ''; ?>>Oui</option>
            </select>
        </div>
        <?php if (isset($produit)): ?>
            <input type="hidden" name="idProduit" value="<?php echo $produit->idProduit; ?>">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Modifier le service</button>
        <?php else: ?>
            <button type="submit" class="btn-primary"><i class="fas fa-plus"></i> Ajouter le service</button>
        <?php endif; ?>
    </form>
</div> 