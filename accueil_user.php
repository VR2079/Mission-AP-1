<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CyberGame Café</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white font-sans">

	<?php
			include("header.php");
			?>

  <!-- NAVBAR -->
  <nav class="bg-gray-800 p-4 sticky top-0 shadow-md">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
      <h1 class="text-xl font-bold text-yellow-400">CyberGame Café</h1>
      <ul class="hidden md:flex space-x-6">
        <li><a href="accueil_user.php" class="hover:text-yellow-400">Accueil</a></li>
        <li><a href="tournoi_user.php" class="hover:text-yellow-400">Tournois</a></li>
        <li><a href="tarif_user.php" class="hover:text-yellow-400">Tarifs</a></li>
        <li><a href="contact_user.php" class="hover:text-yellow-400">Contact</a></li>
        <li><a href="logout.php" class="hover:text-yellow-400">Déconnexion</a></li>
      </ul>
      <button class="md:hidden px-2 py-1 bg-yellow-400 text-black rounded">Menu</button>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section id="accueil" class="text-center py-20 bg-gradient-to-r from-gray-900 to-gray-700">
    <h2 class="text-4xl md:text-6xl font-bold mb-4">Bienvenue au CyberGame Café</h2>
    <p class="text-lg md:text-xl text-gray-300 mb-6">
      Jouez, entraînez-vous et participez à nos tournois LAN & online.
    </p>
    <a href="tournoi_user.php" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-bold hover:bg-yellow-300">
      Voir les tournois
    </a>
  </section>

  <!-- TOURNOIS -->
  <section id="tournois" class="max-w-6xl mx-auto py-16 px-6">
    <h3 class="text-3xl font-bold mb-8 text-center">Tournois à venir</h3>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Exemple tournoi -->
      <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
        <h4 class="text-xl font-bold text-yellow-400">Tournoi Mario Kart World</h4>
        <p class="text-gray-300 mt-2">Mode 4v4  • Cash Prize 150€ • Samedi 7 Mars 2026</p>
      </div>
      <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
        <h4 class="text-xl font-bold text-yellow-400">Tournoi Super Smash Bros Ultimate (switch 1) </h4>
        <p class="text-gray-300 mt-2">LAN • Cash Prize 160€ • Vendredi 26 Décembre 2025</p>
      </div>
      <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
        <h4 class="text-xl font-bold text-yellow-400">Tournoi Fortnite (PC)</h4>
        <p class="text-gray-300 mt-2">Online • Cash Prize 300€ • Dimanche 21 Décembre 2025</p>
      </div>
    </div>
  </section>

  <!-- TARIFS -->
  <section id="tarifs" class="bg-gray-800 py-16 px-6">
    <h3 class="text-3xl font-bold mb-8 text-center">Nos Tarifs</h3>
    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
      <div class="bg-gray-900 p-6 rounded-lg text-center shadow-lg">
        <h4 class="text-xl font-bold">1 Heure</h4>
        <p class="text-2xl text-yellow-400 font-bold mt-2">2,5€</p>
      </div>
      <div class="bg-gray-900 p-6 rounded-lg text-center shadow-lg">
        <h4 class="text-xl font-bold">Pack 5 Heures</h4>
        <p class="text-2xl text-yellow-400 font-bold mt-2">8€</p>
      </div>
      <div class="bg-gray-900 p-6 rounded-lg text-center shadow-lg">
        <h4 class="text-xl font-bold">Abonnement Mensuel</h4>
        <p class="text-2xl text-yellow-400 font-bold mt-2">50€</p>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="py-16 px-6 max-w-4xl mx-auto text-center">
    <h3 class="text-3xl font-bold mb-6">Contact & Réservations</h3>
    <p class="text-gray-300 mb-4">📍 28 Rue Baudimont, 62000 Arras</p>
    <p class="text-gray-300 mb-4">📞 01 23 45 67 89</p>
    <p class="text-gray-300 mb-6">📧 contact@cybergame.fr</p>
    <a href="mailto:contact@cybergame.fr" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-bold hover:bg-yellow-300">
      Réserver une place
    </a>
  </section>

			<?php
			include("footer.php");
			?>

</body>
</html>
