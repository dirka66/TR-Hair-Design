<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - Administration</title>
    <link href="<?php echo Chemins::STYLES . 'style.css'; ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2>Erreur</h2>
            <p class="error-message"><?php echo $erreur ?? 'Une erreur inattendue s\'est produite.'; ?></p>
            <div class="error-actions">
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
                <a href="index.php?controleur=Admin&action=afficherIndex" class="btn btn-outline">
                    <i class="fas fa-user-shield"></i> Administration
                </a>
            </div>
        </div>
    </div>

    <style>
        .error-container {
            max-width: 500px;
            margin: 100px auto;
            text-align: center;
            background: var(--bg-white);
            padding: var(--spacing-xxl);
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-medium);
        }

        .error-icon {
            font-size: 4rem;
            color: var(--gold-accent);
            margin-bottom: var(--spacing-lg);
        }

        .error-message {
            font-size: 1.1rem;
            color: var(--text-gray);
            margin-bottom: var(--spacing-lg);
        }

        .error-actions {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</body>
</html>
