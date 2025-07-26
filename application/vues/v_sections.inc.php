<div class="container">
    <!-- Section Hero -->
    <section class="hero" id="accueil">
        <div class="hero-content">
            <h2>Bienvenue chez Tahir Hair DESIGN</h2>
            <p>Votre salon de coiffure moderne spécialisé dans les coupes pour hommes, les styles tendance et les soins capillaires de haute qualité.</p>
            <a href="#services" class="btn-main">
                <i class="fas fa-cut"></i> Découvrez nos services
            </a>
        </div>
    </section>

    <!-- Section À propos -->
    <section class="section" id="a-propos">
        <h2 class="section-title">À propos de nous</h2>
        <div class="about-content">
            <div class="about-text">
                <p>Tahir Hair DESIGN est un salon de coiffure moderne spécialisé dans les coupes pour hommes, les styles tendance, et les soins capillaires de haute qualité. Avec plusieurs années d'expérience, nous nous engageons à offrir des services adaptés à vos besoins et à vous faire vivre une expérience exceptionnelle.</p>
                
                <p>Notre histoire : Tahir Hair DESIGN a été créé par Tahir, un coiffeur passionné et talentueux qui a développé son savoir-faire à travers des années de formation et d'expérience. Notre salon, situé au cœur de la ville, est un espace dédié à la beauté et au style masculin.</p>
                
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-award"></i>
                        <h4>Expertise</h4>
                        <p>Années d'expérience dans la coiffure masculine</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-heart"></i>
                        <h4>Passion</h4>
                        <p>Dédié à votre satisfaction et votre style</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-star"></i>
                        <h4>Qualité</h4>
                        <p>Produits et services de haute qualité</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Informations -->
    <section class="section" id="infos">
        <h2 class="section-title">Informations pratiques</h2>
        <div class="cards">
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Nos Horaires</h3>
                <div class="card-content">
                    <div class="horaires-preview">
                        <?php
                        // Affichage simplifié des horaires
                        if (isset($lesHoraires) && !empty($lesHoraires)) {
                            // Mapping des jours de semaine PHP (1-7) vers les IDs des horaires (1-7)
                            $jourActuel = date('N'); // 1=Lundi, 7=Dimanche
                            $horaireAujourdhui = null;
                            
                            // Les horaires sont stockés avec idHoraire 1-7 pour Lundi-Dimanche
                            foreach ($lesHoraires as $horaire) {
                                if (isset($horaire->idHoraire) && $horaire->idHoraire == $jourActuel) {
                                    $horaireAujourdhui = $horaire;
                                    break;
                                }
                            }
                            
                            if ($horaireAujourdhui) {
                                $jourNom = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'][$jourActuel];
                                echo "<p><strong>Aujourd'hui ($jourNom) :</strong><br>";
                                
                                if (isset($horaireAujourdhui->ferme) && $horaireAujourdhui->ferme == 1) {
                                    echo "Fermé";
                                } else {
                                    $ouvertureMatin = $horaireAujourdhui->heureOuvertureMatin ?? '00:00';
                                    $fermetureMatin = $horaireAujourdhui->heureFermetureMatin ?? '00:00';
                                    $ouvertureAprem = $horaireAujourdhui->heureOuvertureAprem ?? '00:00';
                                    $fermetureAprem = $horaireAujourdhui->heureFermetureAprem ?? '00:00';
                                    
                                    if ($ouvertureMatin != '00:00') {
                                        echo $ouvertureMatin . " - " . $fermetureMatin;
                                        if ($ouvertureAprem != '00:00') {
                                            echo " / " . $ouvertureAprem . " - " . $fermetureAprem;
                                        }
                                    } elseif ($ouvertureAprem != '00:00') {
                                        echo $ouvertureAprem . " - " . $fermetureAprem;
                                    } else {
                                        echo "Fermé";
                                    }
                                }
                                echo "</p>";
                            } else {
                                echo "<p>Mar-Dim : 9h-12h / 14h-19h<br>Lundi : Fermé</p>";
                            }
                        } else {
                            echo "<p>Mar-Dim : 9h-12h / 14h-19h<br>Lundi : Fermé</p>";
                        }
                        ?>
                    </div>
                    <a href="#" class="card-link" onclick="showHorairesModal()">
                        <i class="fas fa-arrow-right"></i>
                        Voir tous les horaires
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <h3>Nos Tarifs</h3>
                <div class="card-content">
                    <div class="tarifs-preview">
                        <div class="tarif-item">
                            <span>Coupe Femme</span>
                            <span class="price">35€</span>
                        </div>
                        <div class="tarif-item">
                            <span>Coupe Homme</span>
                            <span class="price">25€</span>
                        </div>
                        <div class="tarif-item">
                            <span>Coloration</span>
                            <span class="price">65€</span>
                        </div>
                        <div class="more-services">+ 10 autres services</div>
                    </div>
                    <a href="#" class="card-link" onclick="showTarifsModal()">
                        <i class="fas fa-arrow-right"></i>
                        Voir tous les tarifs
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3>Dernières Informations</h3>
                <div class="card-content">
                    <div class="infos-preview">
                        <?php
                        // Affichage simplifié des informations (juste la première)
                        if (isset($lesInformations) && !empty($lesInformations)) {
                            $premiereInfo = $lesInformations[0];
                            echo "<div class='info-item'>";
                            echo "<h4>" . htmlspecialchars($premiereInfo->titreInformation ?? 'Information') . "</h4>";
                            $texte = $premiereInfo->libelleInformation ?? '';
                            if (strlen($texte) > 80) {
                                $texte = substr($texte, 0, 80) . "...";
                            }
                            echo "<p>" . htmlspecialchars($texte) . "</p>";
                            echo "</div>";
                            if (count($lesInformations) > 1) {
                                echo "<div class='more-infos'>+ " . (count($lesInformations) - 1) . " autre(s) information(s)</div>";
                            }
                        } else {
                            echo "<div class='info-item'>";
                            echo "<h4>Nouveau salon TR Hair Design !</h4>";
                            echo "<p>Nous sommes ravis de vous accueillir dans notre nouveau salon...</p>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <a href="#" class="card-link" onclick="showInfosModal()">
                        <i class="fas fa-arrow-right"></i>
                        Voir toutes les infos
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Services -->
    <section class="section" id="services">
        <h2 class="section-title">Nos Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-cut"></i>
                </div>
                <h3>Coupes pour hommes</h3>
                <p>Coupes classiques et modernes adaptées à votre style et à la forme de votre visage.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <h3>Styles personnalisés</h3>
                <p>Création de styles uniques selon vos préférences et les dernières tendances.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Soins capillaires</h3>
                <p>Traitements et soins pour maintenir la santé et la beauté de vos cheveux.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Styling professionnel</h3>
                <p>Coiffage pour événements spéciaux et occasions professionnelles.</p>
            </div>
        </div>
    </section>

    <!-- Section Galerie -->
    <section class="section" id="galerie">
        <h2 class="section-title">Notre Galerie</h2>
        <?php
        // Utilisation des images de la galerie chargées dans index.php
        // $lesImagesGalerie est déjà disponible depuis le chargement dans index.php
        ?>
        
        <?php if (!empty($lesImagesGalerie)): ?>
            <div class="gallery-grid">
                <?php foreach ($lesImagesGalerie as $index => $image): ?>
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <?php if (file_exists($image->cheminImage)): ?>
                            <img src="<?php echo htmlspecialchars($image->cheminImage); ?>" 
                                 alt="<?php echo htmlspecialchars($image->titreImage); ?>" 
                                 loading="lazy">
                        <?php else: ?>
                            <img src="<?php echo Chemins::IMAGES; ?>image-placeholder.jpg" 
                                 alt="Image non disponible" 
                                 loading="lazy">
                        <?php endif; ?>
                        
                        <div class="gallery-overlay">
                            <h4><?php echo htmlspecialchars($image->titreImage); ?></h4>
                            <?php if (!empty($image->description)): ?>
                                <p><?php echo htmlspecialchars(substr($image->description, 0, 80)); ?><?php echo strlen($image->description) > 80 ? '...' : ''; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Galerie par défaut si aucune image en base -->
            <div class="gallery-grid">
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                    <img src="<?php echo Chemins::IMAGES; ?>image1.jpg" alt="Coupe tendance homme" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Coupe Moderne</h4>
                        <p>Style tendance et élégant</p>
                    </div>
                </div>
                
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                    <img src="<?php echo Chemins::IMAGES; ?>hamza.jpg" alt="Salon de coiffure moderne" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Notre Salon</h4>
                        <p>Espace moderne et accueillant</p>
                    </div>
                </div>
                
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                    <img src="<?php echo Chemins::IMAGES; ?>background.jpeg" alt="Soins capillaires professionnels" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Soins Premium</h4>
                        <p>Traitements de qualité</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Widget Elfsight -->
        <div class="social-widget">
            <script src="https://static.elfsight.com/platform/platform.js" async></script>
            <div class="elfsight-app-ab5b99f3-8413-4d79-808c-d4afbce21822" data-elfsight-app-lazy></div>
        </div>
    </section>

    <!-- Section Rendez-vous -->
    <section class="section" id="rendez-vous">
        <h2 class="section-title">Prendre rendez-vous</h2>
        <p class="section-subtitle">Réservez votre créneau en quelques clics. Nous vous contacterons pour confirmer votre rendez-vous.</p>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['type_message'] === 'success' ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['type_message']); ?>
        <?php endif; ?>
        
        <div class="rdv-container">
            <div class="rdv-form-container">
                <form method="POST" action="index.php?controleur=RendezVous&action=traiterPriseRendezVous" class="rdv-form" id="formRendezVous">
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
                            <label for="telephone">Téléphone <span class="required">*</span></label>
                            <input type="tel" id="telephone" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label for="email_rdv">Email</label>
                            <input type="email" id="email_rdv" name="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="service_id">Service souhaité <span class="required">*</span></label>
                        <select id="service_id" name="service_id" required>
                            <option value="">-- Choisissez un service --</option>
                            <?php if (isset($lesServices)) : ?>
                                <?php foreach ($lesServices as $service) : ?>
                                    <option value="<?php echo $service->idProduit; ?>">
                                        <?php echo htmlspecialchars($service->nomProduit); ?> - 
                                        <?php echo number_format($service->prix, 2); ?>€
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_rdv">Date souhaitée <span class="required">*</span></label>
                            <input type="date" id="date_rdv" name="date_rdv" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="heure_rdv">Heure souhaitée <span class="required">*</span></label>
                            <select id="heure_rdv" name="heure_rdv" required>
                                <option value="">-- Choisissez d'abord une date --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message_rdv">Message (optionnel)</label>
                        <textarea id="message_rdv" name="message" rows="4" placeholder="Précisez vos besoins, préférences..."></textarea>
                    </div>

                    <div class="form-submit">
                        <button type="submit" name="submit_rdv" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i>
                            Demander le rendez-vous
                        </button>
                    </div>
                </form>
            </div>

            <div class="rdv-info">
                <div class="info-card">
                    <h3><i class="fas fa-clock"></i> Horaires d'ouverture</h3>
                    <div class="horaires-simple">
                        <?php
                        // Affichage simplifié des horaires
                        $jours = [
                            1 => 'Lundi',
                            2 => 'Mardi',
                            3 => 'Mercredi',
                            4 => 'Jeudi',
                            5 => 'Vendredi',
                            6 => 'Samedi',
                            7 => 'Dimanche'
                        ];
                        
                        if (!empty($lesHoraires)) {
                            // Grouper les jours avec les mêmes horaires
                            $horairesGroupes = [];
                            foreach ($lesHoraires as $horaire) {
                                if (isset($horaire->ferme) && $horaire->ferme) {
                                    $key = 'ferme';
                                } else {
                                    $matin = ($horaire->heureOuvertureMatin ?? '00:00') . '-' . ($horaire->heureFermetureMatin ?? '00:00');
                                    $aprem = ($horaire->heureOuvertureAprem ?? '00:00') . '-' . ($horaire->heureFermetureAprem ?? '00:00');
                                    $key = $matin . '|' . $aprem;
                                }
                                
                                if (!isset($horairesGroupes[$key])) {
                                    $horairesGroupes[$key] = [];
                                }
                                $horairesGroupes[$key][] = $horaire->idHoraire;
                            }
                            
                            // Afficher les groupes
                            foreach ($horairesGroupes as $horaires => $joursIds) {
                                echo '<div class="horaire-groupe">';
                                
                                // Noms des jours
                                $joursNoms = [];
                                foreach ($joursIds as $jourId) {
                                    $joursNoms[] = $jours[$jourId];
                                }
                                echo '<span class="jours-groupe">' . implode(', ', $joursNoms) . '</span>';
                                
                                // Horaires
                                if ($horaires === 'ferme') {
                                    echo '<span class="horaire-groupe">Fermé</span>';
                                } else {
                                    list($matin, $aprem) = explode('|', $horaires);
                                    if ($matin !== '00:00-00:00') {
                                        echo '<span class="horaire-groupe">' . $matin . '</span>';
                                    }
                                    if ($aprem !== '00:00-00:00') {
                                        echo '<span class="horaire-groupe">' . $aprem . '</span>';
                                    }
                                }
                                
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="horaire-groupe"><span>Aucun horaire disponible</span></div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="info-card">
                    <h3><i class="fas fa-info-circle"></i> À savoir</h3>
                    <ul class="info-list">
                        <li>Votre demande sera traitée dans les plus brefs délais</li>
                        <li>Nous vous contacterons pour confirmer votre rendez-vous</li>
                        <li>Possibilité d'annulation jusqu'à 24h avant</li>
                        <li>Consultation personnalisée offerte</li>
                    </ul>
                </div>

                <div class="info-card">
                    <h3><i class="fas fa-phone"></i> Contact direct</h3>
                    <p>Pour toute urgence ou question :</p>
                    <div class="contact-direct">
                        <a href="tel:0782916164" class="contact-link">
                            <i class="fas fa-phone"></i>
                            07 82 91 61 64
                        </a>
                        <a href="mailto:kadircetintas023@gmail.com" class="contact-link">
                            <i class="fas fa-envelope"></i>
                            kadircetintas023@gmail.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section class="section" id="contact">
        <h2 class="section-title">Contactez-nous</h2>
        <div class="contact-container">
            <div class="contact-info">
                <h3>Informations de contact</h3>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Adresse</h4>
                        <p>TR Hair Design<br>43 Av. Georges Clemenceau<br>34500 Béziers, France</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Téléphone</h4>
                        <p>07 82 91 61 64</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>kadircetintas023@gmail.com</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Horaires</h4>
                        <?php
                        // Déterminer si le salon est ouvert actuellement
                        $jourActuel = date('N'); // 1=Lundi, 7=Dimanche
                        $heureActuelle = date('H:i');
                        $estOuvert = false;
                        $statutActuel = "Fermé";
                        
                        if (!empty($lesHoraires)) {
                            foreach ($lesHoraires as $horaire) {
                                if (isset($horaire->idHoraire) && $horaire->idHoraire == $jourActuel) {
                                    // Vérifier si le salon est fermé ce jour
                                    if (isset($horaire->ferme) && $horaire->ferme == 1) {
                                        $statutActuel = "Fermé aujourd'hui";
                                        break;
                                    }
                                    
                                    // Vérifier les heures d'ouverture
                                    $ouvertureMatin = $horaire->heureOuvertureMatin ?? '00:00';
                                    $fermetureMatin = $horaire->heureFermetureMatin ?? '00:00';
                                    $ouvertureAprem = $horaire->heureOuvertureAprem ?? '00:00';
                                    $fermetureAprem = $horaire->heureFermetureAprem ?? '00:00';
                                    
                                    // Vérifier si l'heure actuelle est dans les plages d'ouverture
                                    // Conversion en minutes pour une comparaison précise
                                    $heureActuelleMinutes = (int)substr($heureActuelle, 0, 2) * 60 + (int)substr($heureActuelle, 3, 2);
                                    
                                    if ($ouvertureMatin != '00:00') {
                                        $ouvertureMatinMinutes = (int)substr($ouvertureMatin, 0, 2) * 60 + (int)substr($ouvertureMatin, 3, 2);
                                        $fermetureMatinMinutes = (int)substr($fermetureMatin, 0, 2) * 60 + (int)substr($fermetureMatin, 3, 2);
                                        
                                        if ($heureActuelleMinutes >= $ouvertureMatinMinutes && $heureActuelleMinutes <= $fermetureMatinMinutes) {
                                            $estOuvert = true;
                                            $statutActuel = "Ouvert";
                                        }
                                    }
                                    
                                    if ($ouvertureAprem != '00:00') {
                                        $ouvertureApremMinutes = (int)substr($ouvertureAprem, 0, 2) * 60 + (int)substr($ouvertureAprem, 3, 2);
                                        $fermetureApremMinutes = (int)substr($fermetureAprem, 0, 2) * 60 + (int)substr($fermetureAprem, 3, 2);
                                        
                                        if ($heureActuelleMinutes >= $ouvertureApremMinutes && $heureActuelleMinutes <= $fermetureApremMinutes) {
                                            $estOuvert = true;
                                            $statutActuel = "Ouvert";
                                        }
                                    }
                                    
                                    // Vérifier si on est dans la pause déjeuner
                                    if (!$estOuvert && $fermetureMatin != '00:00' && $ouvertureAprem != '00:00') {
                                        $fermetureMatinMinutes = (int)substr($fermetureMatin, 0, 2) * 60 + (int)substr($fermetureMatin, 3, 2);
                                        $ouvertureApremMinutes = (int)substr($ouvertureAprem, 0, 2) * 60 + (int)substr($ouvertureAprem, 3, 2);
                                        
                                        if ($heureActuelleMinutes > $fermetureMatinMinutes && $heureActuelleMinutes < $ouvertureApremMinutes) {
                                            $statutActuel = "Pause déjeuner";
                                        }
                                    }
                                    
                                    // Afficher les horaires du jour
                                    $jourNom = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'][$jourActuel];
                                    echo '<p><strong>' . $jourNom . ' :</strong></p>';
                                    echo '<div class="horaires-jour">';
                                    
                                    if ($ouvertureMatin != '00:00') {
                                        echo '<div class="plage-horaire-jour">' . $ouvertureMatin . ' - ' . $fermetureMatin . '</div>';
                                        if ($ouvertureAprem != '00:00') {
                                            echo '<div class="plage-horaire-jour">' . $ouvertureAprem . ' - ' . $fermetureAprem . '</div>';
                                        }
                                    } elseif ($ouvertureAprem != '00:00') {
                                        echo '<div class="plage-horaire-jour">' . $ouvertureAprem . ' - ' . $fermetureAprem . '</div>';
                                    } else {
                                        echo '<div class="plage-horaire-jour">Fermé</div>';
                                    }
                                    echo '</div>';
                                    break;
                                }
                            }
                        }
                        
                        // Afficher le statut actuel
                        if ($statutActuel === "Pause déjeuner") {
                            $statutClass = 'statut-pause';
                            $icone = 'coffee';
                        } else {
                            $statutClass = $estOuvert ? 'statut-ouvert' : 'statut-ferme';
                            $icone = $estOuvert ? 'check-circle' : 'times-circle';
                        }
                        echo '<p class="' . $statutClass . '"><i class="fas fa-' . $icone . '"></i> ' . $statutActuel . '</p>';
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-wrapper">
                <h3>Envoyez-nous un message</h3>
                <form class="contact-form" id="contactForm" autocomplete="off">
                    <div class="form-group">
                        <label for="name">Nom complet *</label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" required placeholder="Votre nom complet">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse email *</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" required placeholder="votre.email@exemple.com">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <div class="input-wrapper">
                            <input type="tel" id="phone" name="phone" placeholder="+33 1 23 45 67 89">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <div class="input-wrapper">
                            <textarea id="message" name="message" required placeholder="Décrivez votre demande..." rows="5"></textarea>
                            <i class="fas fa-comment-dots"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                    
                    <div id="contact-success" class="success-message">
                        <i class="fas fa-check-circle"></i>
                        Merci pour votre message ! Nous vous répondrons rapidement.
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
// Gestion du formulaire de contact
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Animation de soumission
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
    submitBtn.disabled = true;
    
    // Simulation d'envoi (remplacer par votre logique d'envoi)
    setTimeout(() => {
        document.getElementById('contact-success').style.display = 'block';
        this.reset();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Masquer le message après 5 secondes
        setTimeout(() => {
            document.getElementById('contact-success').style.display = 'none';
        }, 5000);
    }, 2000);
});

// Animation des cartes au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationDelay = `${Math.random() * 0.5}s`;
            entry.target.classList.add('animate-fade-in');
        }
    });
}, observerOptions);

// Observer tous les éléments animables
document.querySelectorAll('.card, .service-card, .gallery-item, .feature').forEach(el => {
    observer.observe(el);
});

// Gestion de la prise de rendez-vous
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date_rdv');
    const heureSelect = document.getElementById('heure_rdv');
    
    if (dateInput && heureSelect) {
        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                // Afficher un indicateur de chargement
                heureSelect.innerHTML = '<option value="">Chargement des créneaux...</option>';
                heureSelect.disabled = true;
                
                // Charger les créneaux disponibles via AJAX
                fetch(`ajax_creneaux.php?date=${selectedDate}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    heureSelect.innerHTML = '<option value="">-- Choisissez un créneau --</option>';
                    heureSelect.disabled = false;
                    
                    // Vérifier si c'est un tableau de créneaux ou une erreur
                    if (Array.isArray(data)) {
                        if (data.length > 0) {
                            data.forEach(creneau => {
                                const option = document.createElement('option');
                                option.value = creneau;
                                option.textContent = creneau;
                                heureSelect.appendChild(option);
                            });
                        } else {
                            heureSelect.innerHTML = '<option value="">Aucun créneau disponible pour cette date</option>';
                        }
                    } else if (data.error) {
                        console.error('Erreur serveur:', data.error);
                        heureSelect.innerHTML = '<option value="">Erreur: ' + data.error + '</option>';
                    } else {
                        heureSelect.innerHTML = '<option value="">Format de réponse invalide</option>';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des créneaux:', error);
                    heureSelect.innerHTML = '<option value="">Erreur de chargement des créneaux</option>';
                    heureSelect.disabled = false;
                });
            } else {
                heureSelect.innerHTML = '<option value="">-- Choisissez d\'abord une date --</option>';
                heureSelect.disabled = true;
            }
        });
    }
});

// Fonctions pour les modales des cartes
function showHorairesModal() {
    <?php if (isset($lesHoraires) && !empty($lesHoraires)): ?>
    const horairesData = <?php echo json_encode($lesHoraires); ?>;
    const joursNoms = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    let horairesHtml = '<div class="horaires-list">';
    
    for (let i = 1; i <= 7; i++) {
        const horaire = horairesData.find(h => h.idHoraire == i);
        horairesHtml += `<div class="horaire-item"><strong>${joursNoms[i]} :</strong> `;
        
        if (horaire && horaire.ferme != 1) {
            if (horaire.heureOuvertureMatin && horaire.heureOuvertureMatin !== '00:00') {
                horairesHtml += `${horaire.heureOuvertureMatin} - ${horaire.heureFermetureMatin}`;
                if (horaire.heureOuvertureAprem && horaire.heureOuvertureAprem !== '00:00') {
                    horairesHtml += ` / ${horaire.heureOuvertureAprem} - ${horaire.heureFermetureAprem}`;
                }
            } else if (horaire.heureOuvertureAprem && horaire.heureOuvertureAprem !== '00:00') {
                horairesHtml += `${horaire.heureOuvertureAprem} - ${horaire.heureFermetureAprem}`;
            } else {
                horairesHtml += 'Fermé';
            }
        } else {
            horairesHtml += 'Fermé';
        }
        horairesHtml += '</div>';
    }
    
    horairesHtml += '</div>';
    showModal('Horaires d\'ouverture', horairesHtml);
    <?php else: ?>
    showModal('Horaires d\'ouverture', `
        <div class="horaires-list">
            <div class="horaire-item"><strong>Lundi :</strong> Fermé</div>
            <div class="horaire-item"><strong>Mardi :</strong> 09:00 - 12:00 / 14:00 - 19:00</div>
            <div class="horaire-item"><strong>Mercredi :</strong> 09:00 - 12:00 / 14:00 - 19:00</div>
            <div class="horaire-item"><strong>Jeudi :</strong> 09:00 - 12:00 / 14:00 - 19:00</div>
            <div class="horaire-item"><strong>Vendredi :</strong> 09:00 - 12:00 / 14:00 - 19:00</div>
            <div class="horaire-item"><strong>Samedi :</strong> 09:00 - 12:00 / 14:00 - 19:00</div>
            <div class="horaire-item"><strong>Dimanche :</strong> 09:00 - 12:00 / 14:00 - 19:00</div>
        </div>
    `);
    <?php endif; ?>
}

function showTarifsModal() {
    showModal('Nos tarifs', `
        <div class="tarifs-grid">
            <div class="tarif-category">
                <h3>Coupes</h3>
                <div class="tarif-item"><span>Coupe Femme</span><span>35€</span></div>
                <div class="tarif-item"><span>Coupe Homme</span><span>25€</span></div>
                <div class="tarif-item"><span>Brushing</span><span>20€</span></div>
                <div class="tarif-item"><span>Coupe + Barbe</span><span>40€</span></div>
            </div>
            <div class="tarif-category">
                <h3>Colorations</h3>
                <div class="tarif-item"><span>Coloration complète</span><span>65€</span></div>
                <div class="tarif-item"><span>Mèches</span><span>75€</span></div>
                <div class="tarif-item"><span>Balayage</span><span>85€</span></div>
            </div>
            <div class="tarif-category">
                <h3>Soins</h3>
                <div class="tarif-item"><span>Shampooing + Soin</span><span>15€</span></div>
                <div class="tarif-item"><span>Masque réparateur</span><span>25€</span></div>
            </div>
            <div class="tarif-category">
                <h3>Événementiel</h3>
                <div class="tarif-item"><span>Chignon de mariée</span><span>120€</span></div>
                <div class="tarif-item"><span>Coiffure de soirée</span><span>60€</span></div>
            </div>
        </div>
    `);
}

function showInfosModal() {
    <?php if (isset($lesInformations) && !empty($lesInformations)): ?>
    const infosData = <?php echo json_encode($lesInformations); ?>;
    let infosHtml = '<div class="infos-list">';
    infosData.forEach(info => {
        infosHtml += `
            <div class="info-article">
                <h3>${info.titreInformation}</h3>
                <p>${info.libelleInformation}</p>
            </div>
        `;
    });
    infosHtml += '</div>';
    showModal('Informations importantes', infosHtml);
    <?php else: ?>
    showModal('Informations importantes', `
        <div class="infos-list">
            <div class="info-article">
                <h3>Nouveau salon TR Hair Design !</h3>
                <p>Nous sommes ravis de vous accueillir dans notre nouveau salon de coiffure moderne. Venez découvrir nos prestations haut de gamme dans un cadre chaleureux et professionnel.</p>
            </div>
            <div class="info-article">
                <h3>Promotion du mois</h3>
                <p>Tout le mois de janvier, profitez de -20% sur toutes nos colorations ! Prenez rendez-vous dès maintenant.</p>
            </div>
            <div class="info-article">
                <h3>Horaires spéciaux</h3>
                <p>Attention : Le salon sera fermé exceptionnellement le 14 février pour formation de l'équipe. Merci de votre compréhension.</p>
            </div>
        </div>
    `);
    <?php endif; ?>
}

function showModal(title, content) {
    // Créer la modale si elle n'existe pas
    let modal = document.getElementById('infoModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'infoModal';
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle"></h2>
                    <span class="close">&times;</span>
                </div>
                <div id="modalBody"></div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Ajouter les événements
        modal.querySelector('.close').onclick = function() {
            modal.style.display = 'none';
        };
        
        modal.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    }
    
    // Mettre à jour le contenu et afficher
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalBody').innerHTML = content;
    modal.style.display = 'block';
}
</script>
