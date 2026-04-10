<?php
// reservation.php
$plateforme = isset($_GET["plateforme"]) ? $_GET["plateforme"] : "Non spécifiée";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réservation - CyberGame Café</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">

  <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-bold text-yellow-400 mb-4 text-center">Réserver une session</h1>
    <p class="text-center mb-6">Plateforme choisie : <span class="font-bold"><?= htmlspecialchars($plateforme) ?></span></p>

    <form action="#" method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 text-sm">Nom</label>
        <input type="text" name="nom" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
      </div>
      <div>
        <label class="block mb-1 text-sm">Email</label>
        <input type="email" name="email" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
      </div>
      <div>
        <label class="block mb-1 text-sm">Durée (heures)</label>
        <input type="number" name="duree" min="1" max="8" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
      </div>
      <button type="button"
    onclick="window.location.href='Connexion.php'"  class="w-full bg-yellow-400 text-black font-bold py-2 rounded hover:bg-yellow-300">
        Confirmer la réservation
      </button>
    </form>
  </div>

</body>
</html>
