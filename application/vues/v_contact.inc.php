<?php
// Afficher les messages
if (isset($_SESSION['message'])) {
    $typeClass = $_SESSION['type_message'] == 'success' ? 'alert-success' : 'alert-error';
    echo '<div class="alert ' . $typeClass . '">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
    unset($_SESSION['type_message']);
}
?>

<section class="contact-section">
    <div class="container">
        <div class="contact-header">
            <h2><i class="fas fa-envelope"></i> Nous contacter</h2>
            <p>Une question ? Un conseil ? N'hésitez pas à nous écrire, nous vous répondrons rapidement.</p>
        </div>

        <div class="contact-content">
            <div class="contact-form-container">
                <form method="POST" action="index.php?controleur=Contact&action=traiterContact" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom <span class="required">*</span></label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom <span class="required">*</span></label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sujet">Sujet <span class="required">*</span></label>
                        <select id="sujet" name="sujet" required>
                            <option value="">-- Choisissez un sujet --</option>
                            <option value="Demande d'information">Demande d'information</option>
                            <option value="Prise de rendez-vous">Prise de rendez-vous</option>
                            <option value="Réclamation">Réclamation</option>
                            <option value="Compliment">Compliment</option>
                            <option value="Partenariat">Partenariat</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Votre message <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="6" required placeholder="Décrivez votre demande en détail..."></textarea>
                    </div>

                    <div class="form-submit">
                        <button type="submit" name="submit_contact" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer le message
                        </button>
                    </div>
                </form>
            </div>

            <div class="contact-info">
                <div class="info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Notre salon</h3>
                    <div class="salon-info">
                        <p><strong>TR Hair Design</strong></p>
                        <p>123 Rue de la Beauté<br>75001 Paris</p>
                        <div class="contact-links">
                            <a href="tel:0123456789" class="contact-link">
                                <i class="fas fa-phone"></i>
                                01 23 45 67 89
                            </a>
                            <a href="mailto:contact@trhairdesign.com" class="contact-link">
                                <i class="fas fa-envelope"></i>
                                contact@trhairdesign.com
                            </a>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <h3><i class="fas fa-clock"></i> Horaires d'ouverture</h3>
                    <div class="horaires-list">
                        <div class="horaire-item">
                            <span>Lundi</span>
                            <span>Fermé</span>
                        </div>
                        <div class="horaire-item">
                            <span>Mardi - Vendredi</span>
                            <span>9h00 - 19h00</span>
                        </div>
                        <div class="horaire-item">
                            <span>Samedi</span>
                            <span>9h00 - 18h00</span>
                        </div>
                        <div class="horaire-item">
                            <span>Dimanche</span>
                            <span>10h00 - 16h00</span>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <h3><i class="fas fa-info-circle"></i> Informations utiles</h3>
                    <ul class="info-list">
                        <li>Réponse sous 24h en moyenne</li>
                        <li>Consultation personnalisée gratuite</li>
                        <li>Devis gratuit pour prestations spéciales</li>
                        <li>Parking gratuit à proximité</li>
                        <li>Accessible aux personnes à mobilité réduite</li>
                    </ul>
                </div>

                <div class="info-card">
                    <h3><i class="fas fa-share-alt"></i> Suivez-nous</h3>
                    <div class="social-links">
                        <a href="#" class="social-link facebook">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                        <a href="#" class="social-link instagram">
                            <i class="fab fa-instagram"></i>
                            Instagram
                        </a>
                        <a href="#" class="social-link youtube">
                            <i class="fab fa-youtube"></i>
                            YouTube
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
