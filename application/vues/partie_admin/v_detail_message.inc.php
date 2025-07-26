<?php
// Afficher les messages d'alerte
if (isset($_SESSION['message_admin'])) {
    $typeClass = $_SESSION['type_message_admin'] == 'success' ? 'alert-success' : 'alert-error';
    echo '<div class="alert ' . $typeClass . '">' . $_SESSION['message_admin'] . '</div>';
    unset($_SESSION['message_admin']);
    unset($_SESSION['type_message_admin']);
}
?>

<div class="admin-page-header">
    <div>
        <h1><i class="fas fa-envelope-open"></i> Détail du message</h1>
        <p>Consultez les détails de ce message de contact</p>
    </div>
    <div class="page-actions">
        <a href="index.php?controleur=Contact&action=listerMessages" class="btn-primary">
            <i class="fas fa-arrow-left"></i> Retour aux messages
        </a>
    </div>
</div>

<?php if (isset($message)) : ?>
    <div class="admin-form">
        <div class="message-header">
            <div class="message-info">
                <h3><?php echo htmlspecialchars($message['sujet']); ?></h3>
                <div class="message-meta">
                    <span class="sender">
                        <i class="fas fa-user"></i>
                        <strong><?php echo htmlspecialchars($message['prenom'] . ' ' . $message['nom']); ?></strong>
                    </span>
                    <span class="date">
                        <i class="fas fa-calendar"></i>
                        <?php echo date('d/m/Y à H:i', strtotime($message['date_creation'])); ?>
                    </span>
                    <span class="status-badge <?php echo $message['lu'] ? 'read' : 'unread'; ?>">
                        <i class="fas fa-<?php echo $message['lu'] ? 'envelope-open' : 'envelope'; ?>"></i>
                        <?php echo $message['lu'] ? 'Lu' : 'Non lu'; ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="message-content">
            <div class="contact-details">
                <h4><i class="fas fa-address-card"></i> Informations de contact</h4>
                <div class="contact-grid">
                    <div class="contact-item">
                        <label>Nom complet :</label>
                        <span><?php echo htmlspecialchars($message['prenom'] . ' ' . $message['nom']); ?></span>
                    </div>
                    
                    <div class="contact-item">
                        <label>Email :</label>
                        <span>
                            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                                <?php echo htmlspecialchars($message['email']); ?>
                            </a>
                        </span>
                    </div>
                    
                    <?php if (!empty($message['telephone'])) : ?>
                        <div class="contact-item">
                            <label>Téléphone :</label>
                            <span>
                                <a href="tel:<?php echo htmlspecialchars($message['telephone']); ?>">
                                    <?php echo htmlspecialchars($message['telephone']); ?>
                                </a>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="contact-item">
                        <label>Sujet :</label>
                        <span class="subject-badge"><?php echo htmlspecialchars($message['sujet']); ?></span>
                    </div>
                </div>
            </div>

            <div class="message-body">
                <h4><i class="fas fa-comment"></i> Message</h4>
                <div class="message-text">
                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                </div>
            </div>
        </div>

        <div class="message-actions">
            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo htmlspecialchars($message['sujet']); ?>" 
               class="btn-primary">
                <i class="fas fa-reply"></i> Répondre
            </a>
            
            <a href="index.php?controleur=Contact&action=supprimerMessage&id=<?php echo $message['id_message']; ?>" 
               class="btn-action btn-delete"
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                <i class="fas fa-trash"></i> Supprimer
            </a>
        </div>
    </div>
<?php else : ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        Message introuvable.
    </div>
<?php endif; ?>

<style>
/* Styles spécifiques pour la page de détail des messages */
.message-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.message-info h3 {
    color: #2c3e50;
    margin: 0 0 15px 0;
    font-size: 1.8rem;
}

.message-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: center;
}

.message-meta span {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #7f8c8d;
    font-size: 0.9rem;
}

.message-meta .sender {
    color: #2c3e50;
    font-weight: 600;
}

.contact-details, .message-body {
    margin-bottom: 30px;
}

.contact-details h4, .message-body h4 {
    color: #2c3e50;
    margin: 0 0 20px 0;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.contact-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.contact-item label {
    font-weight: 600;
    color: #7f8c8d;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.contact-item span {
    color: #2c3e50;
    font-size: 1rem;
}

.contact-item a {
    color: #3498db;
    text-decoration: none;
}

.contact-item a:hover {
    text-decoration: underline;
}

.message-text {
    background: rgba(255,255,255,0.8);
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #3498db;
    line-height: 1.6;
    color: #2c3e50;
    white-space: pre-wrap;
}

.message-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .message-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .message-actions {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
