<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titrePage ?? 'Administration TR Hair Design'; ?></title>
    <link href="<?php echo Chemins::STYLES; ?>admin-common.css" rel="stylesheet">
    <link href="<?php echo Chemins::STYLES; ?>style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        /* Force les titres de tableaux en blanc */
        .admin-table th, .rdv-table th, .messages-table th {
            background: linear-gradient(135deg, #2c3e50, #34495e) !important;
            color: white !important;
            font-weight: 600 !important;
        }
        /* Force absolue pour tous les th */
        th {
            background: linear-gradient(135deg, #2c3e50, #34495e) !important;
            color: white !important;
            font-weight: 600 !important;
        }
        /* Double force pour les th dans les tableaux admin */
        .admin-table th, .rdv-table th, .messages-table th, table th {
            background: linear-gradient(135deg, #2c3e50, #34495e) !important;
            color: white !important;
            font-weight: 600 !important;
        }
        /* Triple force avec sélecteur plus spécifique */
        .admin-dashboard th, .admin-dashboard table th {
            background: linear-gradient(135deg, #2c3e50, #34495e) !important;
            color: white !important;
            font-weight: 600 !important;
        }
        /* Force maximale pour UNIQUEMENT les en-têtes de colonnes des tableaux */
        .admin-table thead th, .rdv-table thead th, .messages-table thead th,
        table.admin-table th, table.rdv-table th, table.messages-table th,
        .table-container thead th, .table-container table th {
            background: linear-gradient(135deg, #2c3e50, #34495e) !important;
            color: white !important;
            font-weight: 600 !important;
            text-shadow: none !important;
        }
        /* Force absolue pour le contenu des en-têtes de colonnes uniquement */
        .admin-table thead th *, .rdv-table thead th *, .messages-table thead th *,
        table.admin-table th *, table.rdv-table th *, table.messages-table th *,
        .table-container thead th *, .table-container table th * {
            color: white !important;
        }
        /* Cibler spécifiquement les textes dans les en-têtes de colonnes */
        .admin-table thead th span, .rdv-table thead th span, .messages-table thead th span,
        table.admin-table th span, table.rdv-table th span, table.messages-table th span,
        .table-container thead th span, .table-container table th span {
            color: white !important;
        }
        .table-header h3 {
            color: white !important;
        }
        /* Cibler tous les éléments dans .table-header */
        .table-header, .table-header * {
            color: white !important;
        }
        /* Cibler spécifiquement les titres de colonnes */
        .table-header h1, .table-header h2, .table-header h3, .table-header h4, .table-header h5, .table-header h6 {
            color: white !important;
        }
        /* Force absolue pour les h3 dans table-header */
        .table-header h3 {
            color: white !important;
            background: transparent !important;
        }
        /* Double force pour les h3 */
        div.table-header h3 {
            color: white !important;
        }
        /* Triple force avec sélecteur plus spécifique */
        .admin-dashboard .table-header h3 {
            color: white !important;
        }
        /* Cibler les légendes et labels */
        .table-header legend, .table-header label, .table-header span {
            color: white !important;
        }
    </style>
</head>
<body class="admin-dashboard-body">
    <div class="admin-dashboard">
        <!-- Sidebar moderne -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <div class="admin-logo">
                    <i class="fas fa-cut"></i>
                    <span>TR Hair Design</span>
                </div>
                <div class="admin-user">
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span>Administrateur</span>
                    </div>
                    <a href="index.php?controleur=Admin&action=seDeconnecter" 
                       class="logout-quick" 
                       title="Déconnexion">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>

            <nav class="admin-nav">
                <ul>
                    <li class="nav-section">
                        <span class="nav-section-title">
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de bord
                        </span>
                    </li>
                    <li>
                        <a href="index.php?controleur=Admin&action=afficherIndex" 
                           class="nav-link <?php echo ($pageActive === 'dashboard') ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i>
                            Accueil Admin
                        </a>
                    </li>

                    <li class="nav-section">
                        <span class="nav-section-title">
                            <i class="fas fa-users"></i>
                            Gestion Clients
                        </span>
                    </li>
                    <li>
                        <a href="index.php?controleur=RendezVous&action=listerRendezVous" 
                           class="nav-link <?php echo ($pageActive === 'rendez-vous') ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-alt"></i>
                            Rendez-vous
                        </a>
                    </li>
                    <li>
                        <a href="index.php?controleur=Contact&action=listerMessages" 
                           class="nav-link <?php echo ($pageActive === 'messages') ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i>
                            Messages contact
                        </a>
                    </li>
                    <li>
                        <a href="index.php?controleur=Horaires&action=afficher" 
                           class="nav-link <?php echo ($pageActive === 'horaires') ? 'active' : ''; ?>">
                            <i class="fas fa-clock"></i>
                            Horaires d'ouverture
                        </a>
                    </li>
                    <li>
                        <a href="index.php?controleur=ProduitsModerne&action=listerProduits" 
                           class="nav-link <?php echo ($pageActive === 'produits') ? 'active' : ''; ?>">
                            <i class="fas fa-cut"></i>
                            Services & Tarifs
                        </a>
                    </li>
                    <li>
                        <a href="index.php?controleur=Familles&action=listerFamilles" 
                           class="nav-link <?php echo ($pageActive === 'familles') ? 'active' : ''; ?>">
                            <i class="fas fa-tags"></i>
                            Catégories de services
                        </a>
                    </li>
                    <li>
                        <a href="index.php?controleur=Informations&action=listerInformations" 
                           class="nav-link <?php echo ($pageActive === 'informations') ? 'active' : ''; ?>">
                            <i class="fas fa-newspaper"></i>
                            Actualités & Annonces
                        </a>
                    </li>
                    <li>
                        <a href="index.php?controleur=Galerie&action=afficherGestionGalerie" 
                           class="nav-link <?php echo ($pageActive === 'galerie') ? 'active' : ''; ?>">
                            <i class="fas fa-images"></i>
                            Galerie d'images
                        </a>
                    </li>

                    <li>
                        <a href="index.php?controleur=Admin&action=gererProfil" 
                           class="nav-link <?php echo ($pageActive === 'profil') ? 'active' : ''; ?>">
                            <i class="fas fa-user-cog"></i>
                            Mon compte
                        </a>
                    </li>
                    <li>
                        <a href="index.php" class="nav-link" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            Voir le site
                        </a>
                    </li>
                    <li>
                        <a href="index.php?controleur=Admin&action=seDeconnecter" class="nav-link logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Déconnexion
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Bouton toggle sidebar (visible uniquement sur mobile) -->
        <button class="sidebar-toggle" id="sidebarToggle" style="display: none;">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Overlay pour fermer la sidebar sur mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" style="display: none;"></div>
        
        <!-- Contenu principal -->
        <main class="admin-main">
            <div class="admin-container">
                <?php if (isset($contenu)) echo $contenu; ?>
            </div>
        </main>
    </div>

    <style>
    /* Styles spécifiques pour l'interface admin moderne */
    .admin-dashboard-body {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        overflow-x: hidden;
    }

    .admin-dashboard {
        display: flex;
        min-height: 100vh;
    }

    .admin-sidebar {
        width: 280px;
        background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        color: white;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        z-index: 1000;
        border-right: 1px solid rgba(255,255,255,0.1);
    }

    .admin-main {
        margin-left: 280px;
        flex: 1;
        min-height: 100vh;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
    }

    .admin-container {
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Styles de la sidebar */
    .admin-sidebar-header {
        padding: 25px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .admin-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.4rem;
        font-weight: 700;
        color: white !important;
        margin-bottom: 15px;
    }

    .admin-logo i {
        font-size: 24px;
        color: #3498db !important;
    }

    .admin-nav {
        padding: 20px 0;
    }

    .admin-nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-section {
        margin: 20px 0 10px 0;
    }

    .nav-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #bdc3c7 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: #ecf0f1 !important;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .nav-link:hover {
        background: rgba(255,255,255,0.1);
        border-left-color: #3498db;
        color: white !important;
    }

    .nav-link.active {
        background: rgba(52, 152, 219, 0.2);
        border-left-color: #3498db;
        color: #3498db !important;
    }

    .nav-link.logout {
        color: #e74c3c !important;
        margin-top: 10px;
    }

    .nav-link.logout:hover {
        background: rgba(231, 76, 60, 0.2);
        border-left-color: #e74c3c;
        color: #e74c3c !important;
    }

    .admin-user {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(255, 255, 255, 0.1);
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 15px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        color: white !important;
    }

    .user-info i, .user-info span {
        color: white !important;
    }

    .logout-quick {
        color: #e74c3c !important;
        font-size: 18px;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
        text-decoration: none;
        background: rgba(231, 76, 60, 0.1);
    }

    .logout-quick:hover {
        background: #e74c3c;
        color: white !important;
        transform: scale(1.1);
    }

    /* Media Queries pour responsive */
    @media (max-width: 768px) {
        .admin-sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .admin-sidebar.show {
            transform: translateX(0);
        }
        
        .admin-main {
            margin-left: 0;
            width: 100%;
        }
        
        /* Bouton pour afficher/masquer la sidebar */
        .sidebar-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover {
            background: #34495e;
            transform: scale(1.1);
        }
        
        /* Overlay pour fermer la sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
    }
    
    /* Sur très petits écrans, sidebar en overlay */
    @media (max-width: 480px) {
        .admin-sidebar {
            width: 100%;
        }
    }
    </style>

    <script>
    // Fonction de déconnexion avec confirmation
    function confirmerDeconnexion(event) {
        event.preventDefault();
        
        const confirmation = confirm(
            '🔐 Êtes-vous sûr de vouloir vous déconnecter ?\n\n' +
            'Vous devrez vous reconnecter pour accéder à l\'administration.'
        );
        
        if (confirmation) {
            // Animation de déconnexion
            const loader = document.createElement('div');
            loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Déconnexion...';
            loader.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(0,0,0,0.8);
                color: white;
                padding: 20px 30px;
                border-radius: 10px;
                z-index: 10000;
                font-size: 16px;
            `;
            document.body.appendChild(loader);
            
            // Redirection après animation avec URL sécurisée
            setTimeout(() => {
                const logoutUrl = event.target.href || 'index.php?controleur=Admin&action=seDeconnecter';
                window.location.href = logoutUrl;
            }, 1000);
        }
        
        return false;
    }

    // Attacher la fonction aux liens de déconnexion
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLinks = document.querySelectorAll('a[href*="seDeconnecter"]');
        logoutLinks.forEach(link => {
            link.addEventListener('click', confirmerDeconnexion);
        });
        
        // Gestion de la sidebar responsive
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.admin-sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Afficher le bouton toggle sur mobile
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                sidebarToggle.style.display = 'block';
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            } else {
                sidebarToggle.style.display = 'none';
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        }
        
        // Vérifier la taille d'écran au chargement et au redimensionnement
        checkScreenSize();
        window.addEventListener('resize', checkScreenSize);
        
        // Toggle de la sidebar
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });
        
        // Fermer la sidebar en cliquant sur l'overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
        
        // Fermer la sidebar en cliquant sur un lien (sur mobile)
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    });
    </script>
</body>
</html> 