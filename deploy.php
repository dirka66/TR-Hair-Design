<?php
/**
 * Script de déploiement pour TR Hair Design
 * Automatise l'installation et la configuration
 */

// Vérification des prérequis
function checkPrerequisites() {
    $errors = [];
    
    // Vérification de PHP
    if (version_compare(PHP_VERSION, '7.4.0', '<')) {
        $errors[] = "PHP 7.4 ou supérieur requis. Version actuelle : " . PHP_VERSION;
    }
    
    // Vérification des extensions PHP
    $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'session'];
    foreach ($requiredExtensions as $ext) {
        if (!extension_loaded($ext)) {
            $errors[] = "Extension PHP '$ext' manquante";
        }
    }
    
    // Vérification des permissions
    $writableDirs = ['public', 'application', 'configs'];
    foreach ($writableDirs as $dir) {
        if (!is_writable($dir)) {
            $errors[] = "Le dossier '$dir' n'est pas accessible en écriture";
        }
    }
    
    return $errors;
}

// Test de connexion à la base de données
function testDatabaseConnection($host, $dbname, $username, $password) {
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return ['success' => true, 'message' => 'Connexion réussie'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur de connexion : ' . $e->getMessage()];
    }
}

// Installation de la base de données
function installDatabase($host, $dbname, $username, $password) {
    try {
        // Connexion sans base de données pour la créer
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Création de la base de données
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Connexion à la base créée
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        
        // Lecture et exécution du script SQL
        $sqlFile = 'configs/bdd_trhairdesign.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }
        }
        
        return ['success' => true, 'message' => 'Base de données installée avec succès'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur d\'installation : ' . $e->getMessage()];
    }
}

// Configuration de l'application
function configureApplication($config) {
    try {
        // Mise à jour du fichier de configuration
        $configContent = "<?php\n";
        $configContent .= "// Configuration générée automatiquement\n\n";
        
        foreach ($config as $key => $value) {
            if (is_string($value)) {
                $configContent .= "define('$key', '$value');\n";
            } elseif (is_bool($value)) {
                $configContent .= "define('$key', " . ($value ? 'true' : 'false') . ");\n";
            } else {
                $configContent .= "define('$key', $value);\n";
            }
        }
        
        file_put_contents('configs/config.php', $configContent);
        
        return ['success' => true, 'message' => 'Configuration appliquée avec succès'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Erreur de configuration : ' . $e->getMessage()];
    }
}

// Création des dossiers nécessaires
function createDirectories() {
    $directories = [
        'public/uploads',
        'cache',
        'logs',
        'backups'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    return ['success' => true, 'message' => 'Dossiers créés avec succès'];
}

// Interface de déploiement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $step = $_POST['step'] ?? '';
    $response = ['success' => false, 'message' => ''];
    
    switch ($step) {
        case 'check_prerequisites':
            $errors = checkPrerequisites();
            if (empty($errors)) {
                $response = ['success' => true, 'message' => 'Tous les prérequis sont satisfaits'];
            } else {
                $response = ['success' => false, 'message' => 'Erreurs détectées : ' . implode(', ', $errors)];
            }
            break;
            
        case 'test_database':
            $host = $_POST['db_host'] ?? '';
            $dbname = $_POST['db_name'] ?? '';
            $username = $_POST['db_user'] ?? '';
            $password = $_POST['db_pass'] ?? '';
            
            $response = testDatabaseConnection($host, $dbname, $username, $password);
            break;
            
        case 'install_database':
            $host = $_POST['db_host'] ?? '';
            $dbname = $_POST['db_name'] ?? '';
            $username = $_POST['db_user'] ?? '';
            $password = $_POST['db_pass'] ?? '';
            
            $response = installDatabase($host, $dbname, $username, $password);
            break;
            
        case 'configure_app':
            $config = [
                'DB_HOST' => $_POST['db_host'] ?? '',
                'DB_NAME' => $_POST['db_name'] ?? '',
                'DB_USER' => $_POST['db_user'] ?? '',
                'DB_PASS' => $_POST['db_pass'] ?? '',
                'BASE_URL' => $_POST['base_url'] ?? '',
                'ADMIN_EMAIL' => $_POST['admin_email'] ?? '',
                'APP_NAME' => 'TR Hair Design',
                'APP_VERSION' => '2.0.0'
            ];
            
            $response = configureApplication($config);
            break;
            
        case 'create_directories':
            $response = createDirectories();
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déploiement - TR Hair Design</title>
    <link rel="stylesheet" href="public/styles/style.css">
    <link rel="stylesheet" href="public/styles/admin-common.css">
    <style>
        .deploy-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .step {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        .step h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #2980b9;
        }
        .btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        .status {
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .progress {
            width: 100%;
            height: 20px;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-bar {
            height: 100%;
            background: #3498db;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="deploy-container">
        <h1>🚀 Déploiement TR Hair Design</h1>
        <p>Assistant d'installation et de configuration</p>
        
        <div class="progress">
            <div class="progress-bar" id="progressBar" style="width: 0%"></div>
        </div>
        
        <!-- Étape 1: Vérification des prérequis -->
        <div class="step" id="step1">
            <h3>1. Vérification des prérequis</h3>
            <p>Vérification de l'environnement et des extensions PHP requises.</p>
            <button class="btn" onclick="checkPrerequisites()">Vérifier les prérequis</button>
            <div id="status1"></div>
        </div>
        
        <!-- Étape 2: Configuration de la base de données -->
        <div class="step" id="step2" style="display: none;">
            <h3>2. Configuration de la base de données</h3>
            <div class="form-group">
                <label>Hôte MySQL :</label>
                <input type="text" id="db_host" value="localhost" placeholder="localhost">
            </div>
            <div class="form-group">
                <label>Nom de la base de données :</label>
                <input type="text" id="db_name" value="trhairdesign" placeholder="trhairdesign">
            </div>
            <div class="form-group">
                <label>Utilisateur MySQL :</label>
                <input type="text" id="db_user" placeholder="root">
            </div>
            <div class="form-group">
                <label>Mot de passe MySQL :</label>
                <input type="password" id="db_pass" placeholder="Mot de passe">
            </div>
            <button class="btn" onclick="testDatabase()">Tester la connexion</button>
            <button class="btn" onclick="installDatabase()" style="display: none;" id="installDbBtn">Installer la base</button>
            <div id="status2"></div>
        </div>
        
        <!-- Étape 3: Configuration de l'application -->
        <div class="step" id="step3" style="display: none;">
            <h3>3. Configuration de l'application</h3>
            <div class="form-group">
                <label>URL du site :</label>
                <input type="url" id="base_url" placeholder="https://votre-domaine.com">
            </div>
            <div class="form-group">
                <label>Email administrateur :</label>
                <input type="email" id="admin_email" placeholder="admin@votre-domaine.com">
            </div>
            <button class="btn" onclick="configureApp()">Configurer l'application</button>
            <div id="status3"></div>
        </div>
        
        <!-- Étape 4: Finalisation -->
        <div class="step" id="step4" style="display: none;">
            <h3>4. Finalisation</h3>
            <p>Création des dossiers nécessaires et finalisation de l'installation.</p>
            <button class="btn" onclick="finalizeInstallation()">Finaliser l'installation</button>
            <div id="status4"></div>
        </div>
        
        <!-- Étape 5: Installation terminée -->
        <div class="step" id="step5" style="display: none;">
            <h3>✅ Installation terminée !</h3>
            <p>TR Hair Design a été installé avec succès.</p>
            <div class="status success">
                <strong>Identifiants par défaut :</strong><br>
                Login : admin<br>
                Mot de passe : admin123<br>
                <strong>⚠️ IMPORTANT :</strong> Changez ces identifiants après la première connexion !
            </div>
            <br>
            <a href="index.php" class="btn">Accéder au site</a>
            <a href="index.php?controleur=Admin" class="btn">Accéder à l'administration</a>
        </div>
    </div>

    <script>
        let currentStep = 1;
        
        function updateProgress() {
            const progress = (currentStep / 5) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
        }
        
        function showStep(step) {
            for (let i = 1; i <= 5; i++) {
                document.getElementById('step' + i).style.display = i === step ? 'block' : 'none';
            }
            currentStep = step;
            updateProgress();
        }
        
        function showStatus(stepId, message, isSuccess) {
            const statusDiv = document.getElementById('status' + stepId);
            statusDiv.className = 'status ' + (isSuccess ? 'success' : 'error');
            statusDiv.innerHTML = message;
        }
        
        async function makeRequest(step, data = {}) {
            const formData = new FormData();
            formData.append('step', step);
            for (const [key, value] of Object.entries(data)) {
                formData.append(key, value);
            }
            
            const response = await fetch('deploy.php', {
                method: 'POST',
                body: formData
            });
            
            return await response.json();
        }
        
        async function checkPrerequisites() {
            const result = await makeRequest('check_prerequisites');
            showStatus(1, result.message, result.success);
            
            if (result.success) {
                setTimeout(() => showStep(2), 1000);
            }
        }
        
        async function testDatabase() {
            const data = {
                db_host: document.getElementById('db_host').value,
                db_name: document.getElementById('db_name').value,
                db_user: document.getElementById('db_user').value,
                db_pass: document.getElementById('db_pass').value
            };
            
            const result = await makeRequest('test_database', data);
            showStatus(2, result.message, result.success);
            
            if (result.success) {
                document.getElementById('installDbBtn').style.display = 'inline-block';
            }
        }
        
        async function installDatabase() {
            const data = {
                db_host: document.getElementById('db_host').value,
                db_name: document.getElementById('db_name').value,
                db_user: document.getElementById('db_user').value,
                db_pass: document.getElementById('db_pass').value
            };
            
            const result = await makeRequest('install_database', data);
            showStatus(2, result.message, result.success);
            
            if (result.success) {
                setTimeout(() => showStep(3), 1000);
            }
        }
        
        async function configureApp() {
            const data = {
                db_host: document.getElementById('db_host').value,
                db_name: document.getElementById('db_name').value,
                db_user: document.getElementById('db_user').value,
                db_pass: document.getElementById('db_pass').value,
                base_url: document.getElementById('base_url').value,
                admin_email: document.getElementById('admin_email').value
            };
            
            const result = await makeRequest('configure_app', data);
            showStatus(3, result.message, result.success);
            
            if (result.success) {
                setTimeout(() => showStep(4), 1000);
            }
        }
        
        async function finalizeInstallation() {
            const result = await makeRequest('create_directories');
            showStatus(4, result.message, result.success);
            
            if (result.success) {
                setTimeout(() => showStep(5), 1000);
            }
        }
        
        // Initialisation
        updateProgress();
    </script>
</body>
</html> 