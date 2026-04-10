<?php
session_start();

// Connexion à la base
$host = "localhost";
$user = "root";   // modifie si besoin
$pass = "root";       // ton mot de passe MySQL si tu en as un
$db   = "ap_bdd";     // ta base

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur connexion BDD : " . $e->getMessage());
}

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        // Vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            // Sauvegarde des infos en session
            $_SESSION["user_id"]  = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"]     = $user["role"];

            // Redirection selon le rôle
            if ($user["role"] === "admin") {
                header("Location: admin.php");
                exit;
            } else {
                header("Location: user.php");
                exit;
            }
        } else {
            $erreur = "Identifiants incorrects.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - CyberGame Café</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex flex-col items-center justify-center min-h-screen space-y-4">

  <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-md">
    <!-- Logo / Titre -->
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-yellow-400">CyberGame Café</h1>
      <p class="text-gray-400">Connectez-vous à votre compte</p>
    </div>

    <!-- Formulaire de connexion -->
    <form action="#" method="POST" class="space-y-4">
      <div>
        <label for="email" class="block text-sm mb-1">Adresse e-mail</label>
        <input type="email" id="email" name="email" placeholder="exemple@mail.com"
          class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none focus:border-yellow-400">
      </div>
      <div>
        <label for="password" class="block text-sm mb-1">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="••••••••"
          class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none focus:border-yellow-400">
      </div>
      <button type="submit"
        class="w-full bg-yellow-400 text-black font-bold py-2 rounded hover:bg-yellow-300">
        Se connecter
      </button>
    </form>

    <!-- Liens -->
    <div class="mt-6 text-center space-y-4">
      <a href="#" class="text-sm text-gray-400 hover:text-yellow-400">Mot de passe oublié ?</a>
      <div>
        <p class="text-gray-400">Pas encore de compte ?</p>
        <a href="inscription.php" class="inline-block mt-2 bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">
          Créer un compte
        </a>
      </div>
    </div>
  </div>

  <!-- Bouton retour placé en dessous -->
  <a href="http://localhost:90/2%20site%20ap/site/Accueil.php">
    <button type="button" class="mt-6 bg-gray-700 px-4 py-2 rounded hover:bg-gray-600">
      ⬅ Retour au site
    </button>
  </a>

</body>
</html>
