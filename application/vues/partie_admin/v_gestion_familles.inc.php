<?php
// Sécurité
if (!defined('INDEX_PRINCIPAL')) {
    header('Location: ../../index.php');
    exit;
}

// Utiliser les statistiques passées par le contrôleur ou calculer des fallbacks
if (!isset($statistiques)) {
    $statistiques = [
        'total' => count($familles ?? []),
        'avecCouleur' => count(array_filter($familles ?? [], function($f) { return !empty($f->couleur); })),
        'avecIcone' => count(array_filter($familles ?? [], function($f) { return !empty($f->icone); })),
        'services' => 0 // À calculer si on a accès aux produits
    ];
} else {
    // Si $statistiques est un objet (retourné par le modèle moderne), le convertir en tableau
    if (is_object($statistiques)) {
        $statistiques = [
            'total' => $statistiques->totalFamilles ?? count($familles ?? []),
            'avecCouleur' => count(array_filter($familles ?? [], function($f) { return !empty($f->couleur); })),
            'avecIcone' => count(array_filter($familles ?? [], function($f) { return !empty($f->icone); })),
            'services' => $statistiques->totalProduits ?? 0
        ];
    }
}
?>
<div class="admin-page-header">
    <div>
        <h1><i class="fas fa-tags"></i> Gestion des Catégories</h1>
        <p>Organisez vos services par familles</p>
    </div>
    <div class="page-actions">
        <button class="btn-primary" onclick="document.getElementById('addForm').style.display='block'">
            <i class="fas fa-plus"></i> Ajouter une catégorie
        </button>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-tags"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques['total'] ?? 0; ?></h3>
            <p>Catégories totales</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-palette"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques['avecCouleur'] ?? 0; ?></h3>
            <p>Avec couleur</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-icons"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques['avecIcone'] ?? 0; ?></h3>
            <p>Avec icône</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-cut"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques['services'] ?? 0; ?></h3>
            <p>Services associés</p>
        </div>
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

    <div class="table-container">
        <div class="table-header">
            <h3><i class="fas fa-list"></i> Liste des catégories</h3>
        </div>
        <div class="table-content">
            <?php if (!empty($familles)) : ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de la catégorie</th>
                            <th>Couleur</th>
                            <th>Icône</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($familles as $famille) : ?>
                            <tr>
                                <td><span class="position-badge"><?php echo (int)$famille->idFamille; ?></span></td>
                                <td>
                                    <div class="service-info">
                                        <h6><?php echo htmlspecialchars($famille->nomFamille); ?></h6>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($famille->couleur)) : ?>
                                        <span class="badge-famille" style="background-color: <?php echo htmlspecialchars($famille->couleur); ?>">
                                            <?php echo htmlspecialchars($famille->couleur); ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($famille->icone)) : ?>
                                        <i class="<?php echo htmlspecialchars($famille->icone); ?>" style="font-size: 18px; color: #666;"></i>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <button class="btn-action btn-edit" title="Modifier" onclick="editFamille(<?php echo (int)$famille->idFamille; ?>, '<?php echo htmlspecialchars($famille->nomFamille); ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action btn-delete" title="Supprimer" onclick="deleteFamille(<?php echo (int)$famille->idFamille; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="empty-state">
                    <i class="fas fa-tags"></i>
                    <p>Aucune catégorie trouvée.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulaire d'ajout (caché par défaut) -->
    <div id="addForm" class="admin-form" style="display: none;">
        <div class="table-header">
            <h3><i class="fas fa-plus"></i> Ajouter une nouvelle catégorie</h3>
        </div>
        <div class="form-group">
            <div style="display: flex; gap: 15px; align-items: center;">
                <input type="text" id="newFamilleName" placeholder="Nom de la catégorie" required class="form-control" style="flex: 1;">
                <button type="button" class="btn-primary" onclick="ajouterFamille()">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
                <button type="button" class="btn-action" onclick="document.getElementById('addForm').style.display='none'">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </div>
    </div>

    <script>
    function ajouterFamille() {
        const nomFamille = document.getElementById('newFamilleName').value.trim();
        
        if (!nomFamille) {
            alert('Veuillez entrer un nom de catégorie');
            return;
        }

        const formData = new FormData();
        formData.append('nomFamille', nomFamille);
        
        fetch('index.php?controleur=Familles&action=ajouter', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.succes) {
                location.reload();
            } else {
                alert('Erreur : ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'ajout de la catégorie');
        });
    }

    function editFamille(id, nom) {
        const newNom = prompt('Modifier le nom de la catégorie:', nom);
        if (newNom && newNom.trim() !== '') {
            const formData = new FormData();
            formData.append('idFamille', id);
            formData.append('nomFamille', newNom.trim());
            
            fetch('index.php?controleur=Familles&action=modifier', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.succes) {
                    location.reload();
                } else {
                    alert('Erreur : ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la modification de la catégorie');
            });
        }
    }
    
    function deleteFamille(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
            const formData = new FormData();
            formData.append('idFamille', id);
            
            fetch('index.php?controleur=Familles&action=supprimer', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.succes) {
                    location.reload();
                } else {
                    alert('Erreur : ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression de la catégorie');
            });
        }
    }
    </script>
