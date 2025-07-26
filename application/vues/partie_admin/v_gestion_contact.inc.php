<?php
// Afficher les messages admin
if (isset($_SESSION['message_admin'])) {
    $typeClass = $_SESSION['type_message_admin'] == 'success' ? 'alert-success' : 'alert-error';
    echo '<div class="alert ' . $typeClass . '">' . $_SESSION['message_admin'] . '</div>';
    unset($_SESSION['message_admin']);
    unset($_SESSION['type_message_admin']);
}
?>

<!-- Gestion des Messages de Contact - Interface Admin -->
<div class="admin-page-header">
    <div>
        <h1><i class="fas fa-envelope"></i> Gestion des Messages</h1>
        <p>Consultez et gérez les messages de contact de vos clients</p>
    </div>
</div>

<!-- Statistiques des messages -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-inbox"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['total'] ?? 0; ?></h3>
            <p>Total</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['non_lus'] ?? 0; ?></h3>
            <p>Non lus</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['lus'] ?? 0; ?></h3>
            <p>Lus</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['cette_semaine'] ?? 0; ?></h3>
            <p>Cette semaine</p>
        </div>
    </div>
</div>

<!-- Liste des messages -->
<div class="table-container">
    <?php if (!empty($lesMessages)): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Expéditeur</th>
                    <th>Contact</th>
                    <th>Sujet</th>
                    <th>Aperçu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesMessages as $message): ?>
                    <tr class="<?= $message['lu'] ? 'read' : 'unread' ?>">
                        <td class="status-cell">
                            <?php if ($message['lu']): ?>
                                <span class="status-badge read">
                                    <i class="fas fa-envelope-open"></i> Lu
                                </span>
                            <?php else: ?>
                                <span class="status-badge unread">
                                    <i class="fas fa-envelope"></i> Non lu
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="date-cell">
                            <div class="date-info">
                                <span class="date"><?= date('d/m/Y', strtotime($message['date_creation'])) ?></span>
                                <span class="time"><?= date('H:i', strtotime($message['date_creation'])) ?></span>
                            </div>
                        </td>
                        <td class="sender-cell">
                            <strong><?= htmlspecialchars($message['prenom'] . ' ' . $message['nom']) ?></strong>
                        </td>
                        <td class="contact-cell">
                            <div class="contact-info">
                                <div><i class="fas fa-envelope"></i> <?= htmlspecialchars($message['email']) ?></div>
                                <?php if (!empty($message['telephone'])): ?>
                                    <div><i class="fas fa-phone"></i> <?= htmlspecialchars($message['telephone']) ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="subject-cell">
                            <span class="subject-badge"><?= htmlspecialchars($message['sujet']) ?></span>
                        </td>
                        <td class="message-cell">
                            <div class="message-preview" title="<?= htmlspecialchars($message['message']) ?>">
                                <?= strlen($message['message']) > 80 ? 
                                    substr(htmlspecialchars($message['message']), 0, 80) . '...' : 
                                    htmlspecialchars($message['message']) ?>
                            </div>
                        </td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                <a href="index.php?controleur=Contact&action=voirMessage&id=<?= $message['id_message'] ?>" 
                                   class="btn-action btn-edit" title="Voir le message">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if (!$message['lu']): ?>
                                    <a href="index.php?controleur=Contact&action=marquerLu&id=<?= $message['id_message'] ?>" 
                                       class="btn-action btn-confirm" title="Marquer comme lu">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="mailto:<?= htmlspecialchars($message['email']) ?>?subject=Re: <?= htmlspecialchars($message['sujet']) ?>" 
                                   class="btn-action btn-edit" title="Répondre">
                                    <i class="fas fa-reply"></i>
                                </a>
                                <a href="index.php?controleur=Contact&action=supprimerMessage&id=<?= $message['id_message'] ?>" 
                                   class="btn-action btn-delete" title="Supprimer"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h3>Aucun message</h3>
            <p>Aucun message n'a été reçu pour le moment.</p>
        </div>
    <?php endif; ?>
</div>

<style>
/* Styles spécifiques pour la page messages */
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-badge.read {
    background: rgba(39, 174, 96, 0.1);
    color: #27ae60;
}

.status-badge.unread {
    background: rgba(243, 156, 18, 0.1);
    color: #f39c12;
}

.subject-badge {
    background: rgba(52, 152, 219, 0.1);
    color: #3498db;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 500;
}

.date-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.date-info .date {
    font-weight: 600;
    color: #2c3e50;
}

.date-info .time {
    font-size: 0.8rem;
    color: #7f8c8d;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.85rem;
}

.contact-info div {
    display: flex;
    align-items: center;
    gap: 6px;
}

.message-preview {
    max-width: 200px;
    font-size: 0.85rem;
    color: #7f8c8d;
    line-height: 1.4;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #7f8c8d;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #bdc3c7;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: #2c3e50;
}

.empty-state p {
    margin: 0;
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .message-preview {
        max-width: 150px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
    
    .btn-action {
        padding: 6px 10px;
        font-size: 0.75rem;
    }
}
</style>
