<?php
/**
 * Script de nettoyage pour le déploiement
 * Supprime tous les fichiers de test et temporaires
 */

echo "=== NETTOYAGE POUR DÉPLOIEMENT ===\n";
echo "Suppression des fichiers de test et temporaires...\n\n";

// Liste des fichiers à supprimer
$filesToDelete = [
    // Fichiers de test
    'test_*.php',
    'debug_*.php',
    'test_*.html',
    'test_*.txt',
    
    // Fichiers de debug
    'debug_*.log',
    '*.log',
    'trace_*.txt',
    
    // Fichiers temporaires
    'temp_*.php',
    'backup_*.php',
    'old_*.php',
    
    // Fichiers spécifiques
    'test_admin.php',
    'test_ajax.php',
    'test_bdd.php',
    'test_controleur.php',
    'test_creneaux_simple.php',
    'test_diagnostic.php',
    'test_direct.php',
    'test_donnees.php',
    'test_trace.txt',
    'debug_ajax.log',
    'debug_complet.html',
    'debug_rdv.html',
    'check_system.php',
    'migrate.php',
    'maintenance.html'
];

// Liste des patterns à rechercher
$patterns = [
    'test_*.php',
    'debug_*.php',
    '*.log',
    'test_*.html',
    'test_*.txt',
    'debug_*.log',
    'trace_*.txt',
    'temp_*.php',
    'backup_*.php',
    'old_*.php'
];

$deletedFiles = [];
$errors = [];

echo "Recherche des fichiers à supprimer...\n";

// Fonction pour supprimer un fichier
function deleteFile($file) {
    if (file_exists($file) && is_file($file)) {
        if (unlink($file)) {
            return true;
        } else {
            return false;
        }
    }
    return null; // Fichier n'existe pas
}

// Suppression des fichiers spécifiques
foreach ($filesToDelete as $file) {
    if (file_exists($file)) {
        if (deleteFile($file)) {
            $deletedFiles[] = $file;
            echo "✅ Supprimé : $file\n";
        } else {
            $errors[] = "❌ Impossible de supprimer : $file";
            echo "❌ Impossible de supprimer : $file\n";
        }
    }
}

// Recherche avec patterns glob
foreach ($patterns as $pattern) {
    $files = glob($pattern);
    foreach ($files as $file) {
        if (file_exists($file) && is_file($file)) {
            if (deleteFile($file)) {
                $deletedFiles[] = $file;
                echo "✅ Supprimé : $file\n";
            } else {
                $errors[] = "❌ Impossible de supprimer : $file";
                echo "❌ Impossible de supprimer : $file\n";
            }
        }
    }
}

// Création des dossiers nécessaires
echo "\nCréation des dossiers nécessaires...\n";

$directories = [
    'logs',
    'public/uploads',
    'public/uploads/images',
    'cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Dossier créé : $dir\n";
        } else {
            echo "❌ Impossible de créer le dossier : $dir\n";
        }
    } else {
        echo "✅ Dossier existe déjà : $dir\n";
    }
}

// Création du fichier .htaccess pour les logs
$htaccessLogs = "Order Deny,Allow\nDeny from all";
if (file_put_contents('logs/.htaccess', $htaccessLogs)) {
    echo "✅ Protection .htaccess créée pour logs/\n";
}

// Résumé
echo "\n=== RÉSUMÉ DU NETTOYAGE ===\n";
echo "Fichiers supprimés : " . count($deletedFiles) . "\n";
echo "Erreurs : " . count($errors) . "\n";

if (!empty($deletedFiles)) {
    echo "\n📋 Fichiers supprimés :\n";
    foreach ($deletedFiles as $file) {
        echo "   - $file\n";
    }
}

if (!empty($errors)) {
    echo "\n❌ Erreurs :\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
}

// Vérification de la taille du projet
$totalSize = 0;
$fileCount = 0;

function calculateSize($dir) {
    global $totalSize, $fileCount;
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($files as $file) {
        if ($file->isFile()) {
            $totalSize += $file->getSize();
            $fileCount++;
        }
    }
}

calculateSize('.');

echo "\n📊 Statistiques du projet :\n";
echo "Nombre de fichiers : $fileCount\n";
echo "Taille totale : " . round($totalSize / 1024 / 1024, 2) . " MB\n";

// Vérification des fichiers essentiels
echo "\n🔍 Vérification des fichiers essentiels :\n";

$essentialFiles = [
    'index.php',
    'configs/mysql_config.class.php',
    'configs/chemins.class.php',
    'configs/bdd_trhairdesign.sql',
    'application/controleurs/ControleurAdmin.class.php',
    'application/modeles/gestion_basededonnee.class.php',
    'public/styles/style.css',
    'public/js/main.js',
    '.htaccess',
    'deploy.php',
    'README_DEPLOIEMENT.md'
];

foreach ($essentialFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file\n";
    } else {
        echo "❌ $file (MANQUANT)\n";
    }
}

echo "\n=== NETTOYAGE TERMINÉ ===\n";
echo "✅ Votre projet est prêt pour le déploiement !\n\n";

echo "📋 PROCHAINES ÉTAPES :\n";
echo "1. Uploadez tous les fichiers sur Byethost\n";
echo "2. Importez la base de données\n";
echo "3. Configurez les permissions\n";
echo "4. Testez le site\n\n";

echo "🌐 URL de déploiement : http://trhairdesign.byethost15.com\n";
echo "📚 Consultez README_DEPLOIEMENT.md pour les détails\n";

?> 