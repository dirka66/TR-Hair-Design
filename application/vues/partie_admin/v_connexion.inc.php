<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration - Tahir Hair DESIGN</title>
    <link href="<?php echo Chemins::STYLES . 'style.css'; ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">


    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="admin-header">
                <div class="admin-logo">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2>Administration</h2>
                <p>Tahir Hair DESIGN - Accès réservé</p>
            </div>

            <?php if (isset($_GET['message']) && $_GET['message'] === 'deconnecte'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Vous avez été déconnecté avec succès.
                </div>
            <?php endif; ?>

            <form method="post" action="index.php?controleur=Admin&action=verifierConnexion" class="admin-form" id="loginForm">
                <!-- Token CSRF simple -->
                <input type="hidden" name="csrf_token" value="<?php echo bin2hex(random_bytes(16)); ?>">
                
                <div class="form-group">
                    <label for="login">
                        <i class="fas fa-user"></i>
                        Nom d'utilisateur
                    </label>
                    <input type="text" 
                           name="login" 
                           id="login" 
                           required 
                           autocomplete="username"
                           placeholder="Votre nom d'utilisateur">
                </div>

                <div class="form-group">
                    <label for="passe">
                        <i class="fas fa-lock"></i>
                        Mot de passe
                    </label>
                    <div class="password-wrapper">
                        <input type="password" 
                               name="passe" 
                               id="passe" 
                               required 
                               autocomplete="current-password"
                               placeholder="Votre mot de passe">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="connexion_auto" id="connexion_auto">
                        <span class="checkmark"></span>
                        Se souvenir de moi (7 jours)
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </button>
            </form>

            <div class="admin-footer">
                <a href="index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Retour au site
                </a>
            </div>
        </div>
    </div>

    <style>
        /* Masquer spécifiquement les éléments de navigation */
        header:not(.admin-header), 
        nav:not(.admin-nav), 
        #header:not(.admin-header), 
        #navMenu, 
        .nav-toggle, 
        .nav-link {
            display: none !important;
        }
        
        /* Masquer les éléments de navigation spécifiques */
        [class*="header"]:not(.admin-header):not(.admin-logo):not(.admin-login-container):not(.admin-login-card),
        [class*="nav"]:not(.admin-nav) {
            display: none !important;
        }
        
        /* S'assurer que le formulaire admin et ses icônes restent visibles */
        .admin-header, .admin-login-container, .admin-login-card,
        .admin-logo, .fas, .fa, [class*="fa-"] {
            display: block !important;
        }
        
        /* S'assurer que les icônes dans les labels sont visibles */
        .form-group label i,
        .password-toggle i,
        .btn-login i,
        .alert i {
            display: inline-block !important;
        }
        
        .admin-body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .admin-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('<?php echo Chemins::IMAGES; ?>background.jpeg') center/cover;
            opacity: 0.1;
            z-index: 1;
        }

        .admin-login-container {
            width: 100%;
            max-width: 450px;
            padding: var(--spacing-md);
            position: relative;
            z-index: 2;
            margin: 0 auto;
        }

        .admin-login-card {
            background: var(--bg-white);
            border-radius: var(--border-radius-lg);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: var(--text-light);
            padding: var(--spacing-xl) var(--spacing-lg);
            text-align: center;
            position: relative;
        }

        .admin-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        .admin-logo {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-md);
            font-size: 2.2rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition-medium);
            text-align: center;
            position: relative;
        }
        
        .admin-logo i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            width: auto;
            height: auto;
            color: #e94560;
        }

        .admin-logo:hover {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.25);
        }

        .admin-header h2 {
            margin: 0 0 var(--spacing-xs) 0;
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            color: #ffffff !important;
            text-align: center;
        }

        .admin-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1rem;
            font-weight: 300;
            color: #ffffff !important;
            text-align: center;
        }

        .admin-form {
            padding: var(--spacing-xl) var(--spacing-lg);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-group label {
            display: block;
            margin-bottom: var(--spacing-xs);
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-group label i {
            margin-right: var(--spacing-xs);
            color: var(--gold-accent);
            width: 16px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: var(--spacing-sm);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            font-size: 1rem;
            transition: var(--transition-fast);
            background: var(--bg-light);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--gold-accent);
            background: var(--bg-white);
            box-shadow: 0 0 0 3px rgba(233, 69, 96, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: var(--spacing-sm);
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-gray);
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition-fast);
        }

        .password-toggle:hover {
            color: var(--gold-accent);
        }

        .checkbox-group {
            margin-bottom: var(--spacing-xl);
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input[type="checkbox"] {
            margin-right: var(--spacing-sm);
        }

        .btn-login {
            width: 100%;
            padding: var(--spacing-sm) var(--spacing-lg);
            font-size: 1.1rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--gold-accent), #ff6b8a);
            border: none;
            transition: var(--transition-medium);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #d63850, var(--gold-accent));
            transform: translateY(-2px);
        }

        .admin-footer {
            padding: var(--spacing-lg);
            text-align: center;
            background: var(--bg-light);
            border-top: 1px solid var(--border-color);
        }

        .back-link {
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            background: rgba(233, 69, 96, 0.1);
        }

        .back-link:hover {
            color: var(--gold-accent);
            background: rgba(233, 69, 96, 0.2);
            transform: translateX(-2px);
        }

        .back-link i {
            margin-right: var(--spacing-xs);
        }

        .alert {
            margin-bottom: var(--spacing-lg);
            padding: var(--spacing-sm);
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .admin-login-card {
            animation: slideUp 0.8s ease-out, pulse 2s ease-in-out 1s;
        }

        @media (max-width: 480px) {
            .admin-login-container {
                padding: var(--spacing-sm);
            }
            
            .admin-form {
                padding: var(--spacing-lg) var(--spacing-md);
            }
        }
    </style>

    <script>
        // Navigation mobile pour le header admin
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.getElementById('navToggle');
            const navMenu = document.getElementById('navMenu');
            
            if (navToggle && navMenu) {
                navToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('show');
                    const icon = navToggle.querySelector('i');
                    icon.classList.toggle('fa-bars');
                    icon.classList.toggle('fa-times');
                });
                
                // Fermer le menu mobile lors du clic sur un lien
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', function() {
                        navMenu.classList.remove('show');
                        const icon = navToggle.querySelector('i');
                        icon.classList.add('fa-bars');
                        icon.classList.remove('fa-times');
                    });
                });
            }
        });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('passe');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const login = document.getElementById('login').value.trim();
            const password = document.getElementById('passe').value;
            
            if (login.length < 3) {
                alert('Le nom d\'utilisateur doit contenir au moins 3 caractères.');
                e.preventDefault();
                return;
            }
            
            if (password.length < 4) {
                alert('Le mot de passe doit contenir au moins 4 caractères.');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
