<!-- Contenu du tableau de bord -->
                <!-- En-tête de page -->
                <header class="admin-page-header">
                    <div class="page-title">
                        <h1><i class="fas fa-tachometer-alt"></i> Tableau de bord</h1>
                        <p>Vue d'ensemble de votre salon de coiffure</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-secondary" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <a href="index.php?controleur=Admin&action=seDeconnecter" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </div>
                </header>

                <!-- Statistiques rapides -->
                <div class="stats-grid">
                    <div class="stat-card primary">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Rendez-vous</h3>
                            <p class="stat-number"><?php echo $statistiques['rendez_vous'] ?? 0; ?></p>
                            <span class="stat-label">Cette semaine</span>
                        </div>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>+<?php echo $statistiques['rdv_tendance'] ?? 0; ?>%</span>
                        </div>
                    </div>

                    <div class="stat-card secondary">
                        <div class="stat-icon">
                            <i class="fas fa-cut"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Services</h3>
                            <p class="stat-number"><?php echo $statistiques['services'] ?? 0; ?></p>
                            <span class="stat-label">prestations actives</span>
                        </div>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>+<?php echo $statistiques['services_tendance'] ?? 0; ?></span>
                        </div>
                    </div>

                    <div class="stat-card accent">
                        <div class="stat-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Messages</h3>
                            <p class="stat-number"><?php echo $statistiques['messages'] ?? 0; ?></p>
                            <span class="stat-label">non lus</span>
                        </div>
                        <div class="stat-trend urgent">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Nouveau</span>
                        </div>
                    </div>

                    <div class="stat-card success">
                        <div class="stat-icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Revenus</h3>
                            <p class="stat-number">€<?php echo number_format($statistiques['revenus'] ?? 0, 0, ',', ' '); ?></p>
                            <span class="stat-label">Ce mois</span>
                        </div>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>+<?php echo $statistiques['revenus_tendance'] ?? 0; ?>%</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <section class="quick-actions">
                    <div class="section-header">
                        <h2><i class="fas fa-bolt"></i> Actions rapides</h2>
                        <p>Gérez facilement votre salon</p>
                    </div>
                    
                    <div class="actions-grid">
                        <div class="action-card modern">
                            <a href="index.php?controleur=Horaires&action=afficher" class="card-link"></a>
                            <div class="card-header">
                                <div class="action-icon clock">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="card-badge">Essentiel</div>
                            </div>
                            <div class="card-content">
                                <h3>Horaires d'ouverture</h3>
                                <p>Gérez les heures d'ouverture de votre salon</p>
                            </div>
                        </div>

                        <div class="action-card modern">
                            <a href="index.php?controleur=ProduitsModerne&action=listerProduits" class="card-link"></a>
                            <div class="card-header">
                                <div class="action-icon services">
                                    <i class="fas fa-cut"></i>
                                </div>
                                <div class="card-badge hot">Populaire</div>
                            </div>
                            <div class="card-content">
                                <h3>Services & Tarifs</h3>
                                <p>Ajoutez ou modifiez vos prestations</p>
                            </div>
                        </div>

                        <div class="action-card modern">
                            <a href="index.php?controleur=Familles&action=gererFamilles" class="card-link"></a>
                            <div class="card-header">
                                <div class="action-icon categories">
                                    <i class="fas fa-tags"></i>
                                </div>
                            </div>
                            <div class="card-content">
                                <h3>Catégories</h3>
                                <p>Organisez vos services par familles</p>
                            </div>
                        </div>

                        <div class="action-card modern">
                            <a href="index.php?controleur=Informations&action=listerInformations" class="card-link"></a>
                            <div class="card-header">
                                <div class="action-icon news">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            </div>
                            <div class="card-content">
                                <h3>Actualités</h3>
                                <p>Publiez des informations importantes</p>
                            </div>
                        </div>

                        <div class="action-card modern">
                            <a href="index.php?controleur=RendezVous&action=listerRendezVous" class="card-link"></a>
                            <div class="card-header">
                                <div class="action-icon appointments">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="card-badge urgent"><?php echo $statistiques['rendez_vous'] ?? 0; ?> nouveaux</div>
                            </div>
                            <div class="card-content">
                                <h3>Rendez-vous</h3>
                                <p>Gérez les demandes de RDV clients</p>
                            </div>
                        </div>

                        <div class="action-card modern">
                            <a href="index.php?controleur=Contact&action=listerMessages" class="card-link"></a>
                            <div class="card-header">
                                <div class="action-icon messages">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="card-badge new">Nouveau</div>
                            </div>
                            <div class="card-content">
                                <h3>Messages</h3>
                                <p>Consultez les messages clients</p>
                            </div>
                        </div>

                        <div class="action-card modern preview">
                            <a href="index.php" target="_blank" class="card-link"></a>
                            <div class="card-header">
                                <div class="action-icon preview">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                            <div class="card-content">
                                <h3>Aperçu du site</h3>
                                <p>Voyez votre site comme vos clients</p>
                            </div>
                        </div>

                        <div class="action-card modern coming-soon">
                            <div class="card-header">
                                <div class="action-icon gallery">
                                    <i class="fas fa-images"></i>
                                </div>
                                <div class="card-badge soon">Bientôt</div>
                            </div>
                            <div class="card-content">
                                <h3>Galerie photos</h3>
                                <p>Gérez vos photos de réalisations</p>
                            </div>
                        </div>
                    </div>
                </section>
    <style>
    /* Styles spécifiques pour le tableau de bord */
    .admin-page-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7)) !important;
        backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title h1 {
        color: #2c3e50;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .page-title p {
        color: #7f8c8d;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Statistiques modernes */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7)) !important;
        backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3498db, #2980b9);
    }

    .stat-card.secondary::before { background: linear-gradient(90deg, #e74c3c, #c0392b); }
    .stat-card.accent::before { background: linear-gradient(90deg, #f39c12, #e67e22); }
    .stat-card.success::before { background: linear-gradient(90deg, #27ae60, #229954); }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 24px;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
    }

    .stat-card.secondary .stat-icon {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
    }

    .stat-card.accent .stat-icon {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        box-shadow: 0 8px 25px rgba(243, 156, 18, 0.3);
    }

    .stat-card.success .stat-icon {
        background: linear-gradient(135deg, #27ae60, #229954);
        box-shadow: 0 8px 25px rgba(39, 174, 96, 0.3);
    }

    .stat-content h3 {
        color: #2c3e50;
        margin: 0 0 10px 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .stat-number {
        font-size: 2.8rem;
        font-weight: 700;
        margin: 10px 0;
        color: #2c3e50;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .stat-trend {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(39, 174, 96, 0.1);
        color: #27ae60;
    }

    .stat-trend.urgent {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    /* Actions rapides modernes */
    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-header h2 {
        color: #2c3e50;
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .section-header p {
        color: #7f8c8d;
        font-size: 1.1rem;
        margin: 0;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 25px;
    }

    @media (max-width: 1400px) {
        .actions-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
    }

    @media (max-width: 1024px) {
        .actions-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .actions-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .action-card.modern {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85)) !important;
        backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 350px;
        display: flex;
        flex-direction: column;
        position: relative;
        cursor: pointer;
    }

    .action-card.modern:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    /* Effet de clic */
    .action-card.modern:active {
        transform: translateY(-5px) scale(1.01);
        transition: transform 0.1s ease;
    }

    /* Rendre toute la carte cliquable */
    .action-card.modern .card-link {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        text-decoration: none;
        cursor: pointer;
        display: block;
    }

    .card-header {
        padding: 30px 30px 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .action-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .action-icon.clock { background: linear-gradient(135deg, #3498db, #2980b9); }
    .action-icon.services { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .action-icon.categories { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
    .action-icon.news { background: linear-gradient(135deg, #f39c12, #e67e22); }
    .action-icon.appointments { background: linear-gradient(135deg, #27ae60, #229954); }
    .action-icon.messages { background: linear-gradient(135deg, #34495e, #2c3e50); }
    .action-icon.preview { background: linear-gradient(135deg, #16a085, #1abc9c); }
    .action-icon.gallery { background: linear-gradient(135deg, #e91e63, #ad1457); }

    .card-badge {
        padding: 6px 14px;
        border-radius: 14px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(39, 174, 96, 0.1);
        color: #27ae60;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-badge.hot {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .card-badge.urgent {
        background: rgba(255, 193, 7, 0.1);
        color: #f39c12;
    }

    .card-badge.new {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }

    .card-badge.soon {
        background: rgba(149, 165, 166, 0.1);
        color: #95a5a6;
    }

    .card-content {
        padding: 25px 30px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .card-content h3 {
        color: #2c3e50;
        margin: 0 0 15px 0;
        font-size: 1.4rem;
        font-weight: 600;
    }

    .card-content p {
        color: #7f8c8d;
        margin: 0;
        font-size: 1rem;
        line-height: 1.5;
    }

    .btn-logout {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 12px 20px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-logout:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .page-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    </style>

    <script>
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stat-card, .action-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
    </script>
