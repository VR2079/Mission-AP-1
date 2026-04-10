<?php
// inscription.php
$erreur = "";
$succes = "";

// Connexion à la base de données
$host = "localhost";
$user = "root";   // modifie si besoin
$pass = "root";       // mot de passe MySQL si tu en as un
$db   = "ap_bdd";     // ta base

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur connexion BDD : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pseudo   = trim($_POST["pseudo"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];

    // Vérifications simples
    if (empty($pseudo) || empty($email) || empty($password) || empty($confirm)) {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "Adresse e-mail invalide.";
    } elseif ($password !== $confirm) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si email ou pseudo existent déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $pseudo]);

        if ($stmt->rowCount() > 0) {
            $erreur = "⚠️ Email ou pseudo déjà utilisé.";
        } else {
            // Hacher le mot de passe
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la table users
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$pseudo, $email, $hash])) {
                $succes = "✅ Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                $erreur = "❌ Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription - CyberGame Café</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">

  <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-md">
    <!-- Logo / Titre -->
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-yellow-400">CyberGame Café</h1>
      <p class="text-gray-400">Créez votre compte</p>
    </div>

    <!-- Messages d'erreur ou de succès -->
    <?php if ($erreur): ?>
      <div class="bg-red-600 text-white p-3 rounded mb-4 text-center">
        <?= htmlspecialchars($erreur) ?>
      </div>
    <?php endif; ?>

    <?php if ($succes): ?>
      <div class="bg-green-600 text-white p-3 rounded mb-4 text-center">
        <?= htmlspecialchars($succes) ?>
      </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <form action="" method="POST" class="space-y-4">
      <div>
        <label for="pseudo" class="block text-sm mb-1">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" placeholder="Votre pseudo"
          class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none focus:border-yellow-400"
          value="<?= isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : '' ?>">
      </div>
      <div>
        <label for="email" class="block text-sm mb-1">Adresse e-mail</label>
        <input type="email" id="email" name="email" placeholder="exemple@mail.com"
          class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none focus:border-yellow-400"
          value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      </div>
      <div>
        <label for="password" class="block text-sm mb-1">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="••••••••"
          class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none focus:border-yellow-400">
      </div>
      <div>
        <label for="confirm" class="block text-sm mb-1">Confirmer le mot de passe</label>
        <input type="password" id="confirm" name="confirm" placeholder="••••••••"
          class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none focus:border-yellow-400">
      </div>
      <button type="submit"
        class="w-full bg-yellow-400 text-black font-bold py-2 rounded hover:bg-yellow-300">
        S'inscrire
      </button>
    </form>

    <!-- Lien vers connexion -->
    <div class="mt-6 text-center">
      <p class="text-gray-400">Déjà un compte ?</p>
      <a href="Connexion.php" class="inline-block mt-2 bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">
        Se connecter
      </a>
    </div>
  </div>

</body>
</html>

