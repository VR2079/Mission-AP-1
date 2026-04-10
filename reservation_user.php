<?php
// reservation_user.php
require_once __DIR__ . '/config_user_DB.php'; // connexion PDO à match_db

// Vérifier que la plateforme est passée en GET
if (!isset($_GET['plateforme'])) {
    header('Location: tarif_user.php');
    exit;
}

$plateforme = $_GET['plateforme'];

// Récupérer l'ID de la console correspondante dans la BDD
$stmt = $pdo->prepare("SELECT id FROM consoles WHERE name = ?");
$stmt->execute([$plateforme]);
$console = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$console) {
    echo "<p>Plateforme inconnue.</p>";
    exit;
}

$console_id = $console['id'];

// Récupération des jeux disponibles sur cette console
$stmt2 = $pdo->prepare("SELECT id, name FROM games WHERE console_id = ? ORDER BY name");
$stmt2->execute([$console_id]);
$jeux = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$message_envoye = false;

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom    = htmlspecialchars($_POST["nom"]);
    $email  = htmlspecialchars($_POST["email"]);
    $duree  = (int)($_POST["duree"] ?? 1);
    $jeu_id = (int)($_POST["jeu"]);

    // Vérifier que le jeu appartient bien à la console sélectionnée
    $stmt3 = $pdo->prepare("SELECT name FROM games WHERE id = ? AND console_id = ?");
    $stmt3->execute([$jeu_id, $console_id]);
    $jeu = $stmt3->fetchColumn();

    if (!$jeu) {
        $erreur = "Le jeu sélectionné n'est pas disponible sur cette plateforme.";
    } else {
        // Exemple : enregistrer dans un fichier
        $fichier = fopen("reservations.txt", "a");
        fwrite($fichier, "Nom: $nom\nEmail: $email\nPlateforme: $plateforme\nJeu: $jeu\nDurée: $duree heures\n---\n");
        fclose($fichier);
        $message_envoye = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réservation - CyberGame Café</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <?php if ($message_envoye): ?>
  <!-- Redirection automatique après 4 secondes -->
  <meta http-equiv="refresh" content="4;url=tarif_user.php">
  <?php endif; ?>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">

  <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-bold text-yellow-400 mb-4 text-center">Réserver une session</h1>

    <?php if ($message_envoye): ?>
      <div class="bg-green-600 text-white p-4 rounded mb-4 text-center">
        ✅ Merci <?= htmlspecialchars($nom) ?>, votre réservation pour <strong><?= htmlspecialchars($jeu) ?></strong> a bien été prise en compte !<br>
        <span class="text-sm">Vous allez être redirigé vers les tarifs dans quelques instants</span>
      </div>
    <?php else: ?>
      <?php if (!empty($erreur)): ?>
        <div class="bg-red-600 text-white p-3 rounded mb-4 text-center">
          <?= htmlspecialchars($erreur) ?>
        </div>
      <?php endif; ?>
      <form action="?plateforme=<?= urlencode($plateforme) ?>" method="POST" class="space-y-4">
        <div>
          <label class="block mb-1 text-sm">Nom</label>
          <input type="text" name="nom" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
        </div>
        <div>
          <label class="block mb-1 text-sm">Email</label>
          <input type="email" name="email" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
        </div>
        <div>
          <label class="block mb-1 text-sm">Jeu</label>
          <select name="jeu" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
            <option value="">-- Sélectionner un jeu --</option>
            <?php foreach($jeux as $j): ?>
              <option value="<?= $j['id'] ?>"><?= htmlspecialchars($j['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block mb-1 text-sm">Durée (heures)</label>
          <input type="number" name="duree" min="1" max="8" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
        </div>
        <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-2 rounded hover:bg-yellow-300">
          Confirmer la réservation
        </button>
      </form>
    <?php endif; ?>
  </div>

</body>
</html>
