<?php
require_once __DIR__ . '/config_admin_DB.php';

// Informations du tournoi (ici statiques, tu peux créer une table pour rendre dynamique)
$tournament = [
    'date' => 'Samedi 15 Novembre - 14h00',
    'format' => '4v4 - Double Élimination',
    'prize' => '500€ + Récompenses'
];

// Récupérer toutes les équipes
$teams = $pdo->query("SELECT id, name FROM teams ORDER BY name")->fetchAll();

// Récupérer tous les matchs
$matches = $pdo->query("
    SELECT m.match_date, t1.name AS team1, COALESCE(m.opponent, 'À définir') AS team2
    FROM matches m
    JOIN teams t1 ON m.team_id = t1.id
    ORDER BY m.match_date ASC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organisation Tournoi - CyberGame Café</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organisation Tournoi - CyberGame Café</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

  <!-- HEADER -->
  <header class="bg-gray-800 p-6 shadow-md">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold text-yellow-400">CyberGame Café</h1>
      <nav class="space-x-6 hidden md:flex">
        <a href="Accueil.php" class="hover:text-yellow-400">Accueil</a>
        <a href="Tournoi.php" class="hover:text-yellow-400">Tournois</a>
        <a href="Tarifs.php" class="hover:text-yellow-400">Tarifs</a>
        <a href="Contact.php" class="hover:text-yellow-400">Contact</a>
        <a href="Connexion.php" class="hover:text-yellow-400">Connexion</a>
      </nav>
    </div>
  </header>

 <!-- BANNIERE TOURNOI -->
	<section class="relative w-full h-64 md:h-96 overflow-hidden">
	<!-- Vidéo en boucle sans son -->
	<video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-contain bg-black">
		<source src="medias%20&%20logos/bannière_tournois_SF2.mp4" type="video/mp4">
		<!-- si jamais le navigateur ne peut pas lire la vidéo (rare) -->
		Votre navigateur ne supporte pas la lecture vidéo.
	</video>
	</section>

  <!-- INFOS TOURNOI -->
<section class="max-w-6xl mx-auto py-12 px-6">
  <h3 class="text-2xl font-bold mb-6">Informations principales</h3>
  <div class="grid md:grid-cols-3 gap-6">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
      <h4 class="text-yellow-400 font-bold">📅 Date</h4>
      <p class="mt-2"><?= htmlspecialchars($tournament['date']) ?></p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
      <h4 class="text-yellow-400 font-bold">🎮 Format</h4>
      <p class="mt-2"><?= htmlspecialchars($tournament['format']) ?></p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
      <h4 class="text-yellow-400 font-bold">💰 Cash Prize</h4>
      <p class="mt-2"><?= htmlspecialchars($tournament['prize']) ?></p>
    </div>
  </div>
  <div class="mt-8 text-center">
  <?php if (isset($_SESSION["user_id"])): ?>
    <!-- Utilisateur connecté : lien direct -->
    <a href="liste_match.php" 
       class="bg-yellow-400 text-black font-bold px-6 py-3 rounded-lg hover:bg-yellow-300">
      S'inscrire maintenant
    </a>
  <?php else: ?>
    <!-- Pas connecté : pop-up puis redirection -->
		<button onclick="alert('Vous devez vous connecter pour vous inscrire.'); window.location.href='Connexion.php';"
            class="bg-yellow-400 text-black font-bold px-6 py-3 rounded-lg hover:bg-yellow-300">
		S'inscrire maintenant
		</button>
	<?php endif; ?>
	</div>

</section>

<!-- PARTICIPANTS -->
<section class="bg-gray-800 py-12 px-6">
  <div class="max-w-6xl mx-auto">
    <h3 class="text-2xl font-bold mb-6">Participants</h3>
    <div class="grid md:grid-cols-4 gap-6">
      <?php foreach($teams as $team): ?>
      <div class="bg-gray-900 p-4 rounded-lg text-center shadow-lg">
        <img src="medias & logos/logo <?= htmlspecialchars($team['name']) ?>.jpg" 
             alt="<?= htmlspecialchars($team['name']) ?> Logo" 
             class="mx-auto mb-4 rounded-full w-24 h-24 object-cover">
        <h4 class="font-bold"><?= htmlspecialchars($team['name']) ?></h4>
        <p class="text-sm text-gray-400">4 Joueurs</p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PLANNING -->
<section class="max-w-6xl mx-auto py-12 px-6">
  <h3 class="text-2xl font-bold mb-6">Planning des matchs</h3>
  <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <table class="w-full text-left border-collapse">
      <thead class="bg-gray-700">
        <tr>
          <th class="px-6 py-3">Jour</th>
          <th class="px-6 py-3">Heure</th>
          <th class="px-6 py-3">Match</th>
          <th class="px-6 py-3">Résultat</th>
        </tr>
      </thead>
    <tbody>
        <?php foreach ($matches as $m): ?>
        <?php
          $date = $m['match_date'] ? strtotime($m['match_date']) : null;
          $jour = $date ? date('d/m/Y', $date) : '-';
          $heure = $date ? date('H:i', $date) : '-';
        ?>
        <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
          <td class="px-6 py-3"><?= $jour ?></td>
          <td class="px-6 py-3"><?= $heure ?></td>
          <td class="px-6 py-3">
    <?php if (isset($m['team2']) && strtolower(trim($m['team2'])) === 'team solo'): ?>
      🕹️ Match solo — <?= htmlspecialchars($m['team1']) ?>
    <?php else: ?>
      <?= htmlspecialchars($m['team1']) ?> vs <?= htmlspecialchars($m['team2']) ?>
    <?php endif; ?>
  </td>
  <td class="px-6 py-3"><?= !empty($m['result']) ? htmlspecialchars($m['result']) : 'À venir' ?></td>
</tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>


  <!-- FOOTER -->
  <footer class="bg-gray-900 text-center py-6 mt-10">
    <p class="text-gray-500">© 2025 CyberGame Café - Tous droits réservés</p>
  </footer>

</body>
</html>
 
  