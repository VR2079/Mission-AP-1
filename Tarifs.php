<?php
// tarifs.php

// --- Liste des tarifs (modifiable facilement) ---
$tarifs = [
    [
        "plateforme" => "PC",
        "prix" => "2,5€ / heure",
        "jeux" => ["League of Legends", "CSGO", "Valorant", "Rocket League", "Fortnite", "Call Of duty : Black Ops 7"]
    ],
    [
        "plateforme" => "PlayStation 5",
        "prix" => "3€ / heure",
        "jeux" => ["EA Sports FC 26", "Call Of Duty : Black Ops 7", "Gran Turismo 7", "Fortnite", "Mortal Kombat 1", "Valorant"]
    ],
    [
        "plateforme" => "Xbox Series X/S",
        "prix" => "3€ / heure",
        "jeux" => ["Call Of Duty : Black Ops 7", "Forza Horizon 5", "EA Sports FC 26", "Fortnite", "Rocket League"]
    ],
    [
        "plateforme" => "Nintendo Switch 1",
        "prix" => "2.5€ / heure",
        "jeux" => ["Mario Kart 8 Deluxe", "Smash Bros Ultimate", "Splatoon 3", "Tetris 99", "Hyrule Warrios : Age Of Calamity"]
    ],
    [
        "plateforme" => "Nintendo Switch 2",
        "prix" => "3€ / heure",
        "jeux" => ["Hyrule Warriors : Age Of Imprisonment", "Super Smash Bros Ultimate", "Mario Tennis Fever", "Mario Kart World"]
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tarifs - CyberGame Café</title>
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

  <!-- SECTION TARIFS -->
  <section class="max-w-6xl mx-auto py-12 px-6">
    <h2 class="text-3xl font-bold text-center mb-10">Nos Tarifs de session</h2>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($tarifs as $t) : ?>
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg flex flex-col justify-between">
          <div>
            <h3 class="text-xl font-bold text-yellow-400 mb-2"><?= htmlspecialchars($t["plateforme"]) ?></h3>
            <p class="text-lg font-semibold mb-4"><?= htmlspecialchars($t["prix"]) ?></p>
            <h4 class="text-md font-semibold mb-2">Jeux disponibles :</h4>
            <ul class="list-disc list-inside text-gray-300 mb-4">
              <?php foreach ($t["jeux"] as $jeu) : ?>
                <li><?= htmlspecialchars($jeu) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>

          <!-- BOUTON RÉSERVER -->
          <form action="reservation.php" method="GET" class="mt-4">
            <input type="hidden" name="plateforme" value="<?= htmlspecialchars($t["plateforme"]) ?>">
            <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-2 rounded hover:bg-yellow-300">
              Réserver
            </button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-gray-800 text-center py-6 mt-10">
    <p class="text-gray-500">© 2025 CyberGame Café - Tous droits réservés</p>
  </footer>

</body>
</html>
