<?php
// contact_user.php

// Traitement du formulaire (basique, sans base de données)
$message_envoye = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars($_POST["nom"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    // Exemple : enregistrer dans un fichier (facile à tester sans serveur mail)
    $fichier = fopen("messages_contact.txt", "a");
    fwrite($fichier, "Nom: $nom\nEmail: $email\nMessage: $message\n---\n");
    fclose($fichier);

    $message_envoye = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - CyberGame Café</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

  <!-- HEADER -->
  <header class="bg-gray-800 p-6 shadow-md">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold text-yellow-400">CyberGame Café</h1>
      <nav class="space-x-6 hidden md:flex">
        <a href="accueil_user.php" class="hover:text-yellow-400">Accueil</a>
        <a href="tournoi_user.php" class="hover:text-yellow-400">Tournois</a>
        <a href="tarif_user.php" class="hover:text-yellow-400">Tarifs</a>
        <a href="contact_user.php" class="hover:text-yellow-400">Contact</a>
       <a href="logout.php" class="hover:text-yellow-400">Déconnexion</a>
      </nav>
    </div>
  </header>

  <!-- SECTION CONTACT -->
  <section class="max-w-4xl mx-auto py-12 px-6">
    <h2 class="text-3xl font-bold text-center mb-8">Contactez-nous</h2>

    <?php if ($message_envoye): ?>
      <div class="bg-green-600 text-white p-4 rounded mb-6 text-center">
        ✅ Merci <?= $nom ?>, votre message a bien été envoyé !
      </div>
    <?php endif; ?>

    <div class="grid md:grid-cols-2 gap-8">
      <!-- Formulaire -->
      <form action="contact_user.php" method="POST" class="space-y-4 bg-gray-800 p-6 rounded-xl shadow-lg">
        <div>
          <label class="block mb-1 text-sm">Nom</label>
          <input type="text" name="nom" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
        </div>
        <div>
          <label class="block mb-1 text-sm">Email</label>
          <input type="email" name="email" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none">
        </div>
        <div>
          <label class="block mb-1 text-sm">Message</label>
          <textarea name="message" rows="5" required class="w-full px-4 py-2 rounded bg-gray-900 border border-gray-700 text-white focus:outline-none"></textarea>
        </div>
        <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-2 rounded hover:bg-yellow-300">
          Envoyer
        </button>
      </form>

      <!-- Infos de contact -->
      <div class="bg-gray-800 p-6 rounded-xl shadow-lg flex flex-col justify-center">
        <h3 class="text-xl font-bold text-yellow-400 mb-4">Nos coordonnées</h3>
        <p class="mb-2">📍 Adresse : 28 Rue Baudimont, 62000 Arras</p>
        <p class="mb-2">📞 Téléphone : 01 23 45 67 89</p>
        <p class="mb-6">✉️ Email : contact@cybergamecafe.fr</p>

        <h3 class="text-xl font-bold text-yellow-400 mb-4">Horaires</h3>
        <p>Lundi - Vendredi : 10h - 23h</p>
        <p>Samedi - Dimanche : 12h - 02h</p>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-gray-800 text-center py-6 mt-10">
    <p class="text-gray-500">© 2025 CyberGame Café - Tous droits réservés</p>
  </footer>

</body>
</html>
