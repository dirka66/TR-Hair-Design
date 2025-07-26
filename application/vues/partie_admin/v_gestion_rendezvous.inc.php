<?php
// Afficher les messages admin
if (isset($_SESSION['message_admin'])) {
    $typeClass = $_SESSION['type_message_admin'] == 'success' ? 'alert-success' : 'alert-error';
    echo '<div class="alert ' . $typeClass . '">' . $_SESSION['message_admin'] . '</div>';
    unset($_SESSION['message_admin']);
    unset($_SESSION['type_message_admin']);
}
?>

<div class="admin-page-header">
    <h1><i class="fas fa-calendar-alt"></i> Gestion des Rendez-vous</h1>
    <p>Gérez les demandes de rendez-vous de vos clients</p>
</div>

<div class="rdv-stats">
    <?php 
    $gestionRdv = new gestion_rendezvous();
    $stats = $gestionRdv->obtenirStatistiquesRendezVous();
    ?>
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['en_attente']; ?></h3>
            <p>En attente</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['confirmes']; ?></h3>
            <p>Confirmés</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['aujourd_hui']; ?></h3>
            <p>Aujourd'hui</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['total']; ?></h3>
            <p>Total</p>
        </div>
    </div>
</div>

<div class="rdv-table-container">
    <?php if (!empty($lesRendezVous)) : ?>
        <table class="rdv-table">
            <thead>
                <tr>
                    <th>Date/Heure</th>
                    <th>Client</th>
                    <th>Contact</th>
                    <th>Service</th>
                    <th>Statut</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesRendezVous as $rdv) : ?>
                    <tr class="rdv-row status-<?php echo $rdv['statut']; ?>">
                        <td class="rdv-datetime">
                            <div class="datetime-info">
                                <span class="date"><?php echo date('d/m/Y', strtotime($rdv['dateRendezVous'])); ?></span>
                                <span class="time"><?php echo date('H:i', strtotime($rdv['dateRendezVous'])); ?></span>
                            </div>
                        </td>
                        <td class="rdv-client">
                            <div class="client-info">
                                <strong><?php echo htmlspecialchars($rdv['prenom'] . ' ' . $rdv['nom']); ?></strong>
                                <small>Demandé le <?php echo date('d/m/Y à H:i', strtotime($rdv['dateCreation'])); ?></small>
                            </div>
                        </td>
                        <td class="rdv-contact">
                            <div class="contact-info">
                                <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($rdv['telephone']); ?></div>
                                <?php if (!empty($rdv['email'])) : ?>
                                    <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($rdv['email']); ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="rdv-service">
                            <div class="service-info">
                                <strong><?php echo htmlspecialchars($rdv['nomProduit']); ?></strong>
                                <span class="price"><?php echo number_format($rdv['prix'], 2); ?>€</span>
                            </div>
                        </td>
                        <td class="rdv-statut">
                            <?php 
                            $statutClass = '';
                            $statutText = '';
                            switch($rdv['statut']) {
                                case 'attente':
                                    $statutClass = 'status-pending';
                                    $statutText = 'En attente';
                                    break;
                                case 'confirme':
                                    $statutClass = 'status-confirmed';
                                    $statutText = 'Confirmé';
                                    break;
                                case 'annule':
                                    $statutClass = 'status-cancelled';
                                    $statutText = 'Annulé';
                                    break;
                                case 'termine':
                                    $statutClass = 'status-completed';
                                    $statutText = 'Terminé';
                                    break;
                                default:
                                    $statutClass = 'status-unknown';
                                    $statutText = ucfirst($rdv['statut']);
                            }
                            ?>
                            <span class="status-badge <?php echo $statutClass; ?>">
                                <?php echo $statutText; ?>
                            </span>
                        </td>
                        <td class="rdv-message">
                            <?php if (!empty($rdv['commentaire'])) : ?>
                                <div class="message-preview" title="<?php echo htmlspecialchars($rdv['commentaire']); ?>">
                                    <?php echo strlen($rdv['commentaire']) > 50 ? substr(htmlspecialchars($rdv['commentaire']), 0, 50) . '...' : htmlspecialchars($rdv['commentaire']); ?>
                                </div>
                            <?php else : ?>
                                <span class="no-message">Aucun message</span>
                            <?php endif; ?>
                        </td>
                        <td class="rdv-actions">
                            <div class="action-buttons">
                                <?php if ($rdv['statut'] == 'attente') : ?>
                                    <button type="button" 
                                            class="btn btn-sm btn-success" 
                                            onclick="confirmerRendezVous(<?php echo $rdv['idRendezVous']; ?>)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="supprimerRendezVous(<?php echo $rdv['idRendezVous']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3>Aucun rendez-vous</h3>
            <p>Aucune demande de rendez-vous n'a été reçue pour le moment.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmerRendezVous(id) {
    if (confirm('Confirmer ce rendez-vous ?')) {
        fetch('index.php?controleur=RendezVous&action=confirmerRendezVous', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.succes) {
                showNotification('Rendez-vous confirmé avec succès', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification('Erreur: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors de la confirmation', 'error');
        });
    }
}

function supprimerRendezVous(id) {
    if (confirm('Supprimer ce rendez-vous ?')) {
        fetch('index.php?controleur=RendezVous&action=supprimerRendezVous', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.succes) {
                showNotification('Rendez-vous supprimé avec succès', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification('Erreur: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors de la suppression', 'error');
        });
    }
}

function showNotification(message, type) {
    // Créer une notification temporaire
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'error'}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Styles pour les animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
