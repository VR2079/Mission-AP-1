<?php
/**
 * edit.php
 * Formulaire d'édition d’un match existant
 */
require_once __DIR__ . '/config_admin_DB.php';
require_once __DIR__ . '/functions.php';

if (!isset($_GET['id'])) {
    header('Location: index_ad.php');
    exit;
}

$id = (int)$_GET['id'];
$match = findMatch($pdo, $id);

if (!$match) {
    echo "<h1>Match introuvable</h1>";
    exit;
}

$teams    = getTeams($pdo);
$consoles = getConsoles($pdo);
$games    = getGamesByConsole($pdo, $match['console_id']);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_id    = (int)($_POST['team_id'] ?? 0);
    $opponent   = trim($_POST['opponent'] ?? '');
    $console_id = (int)($_POST['console_id'] ?? 0);
    $game_id    = (int)($_POST['game_id'] ?? 0);
    $match_date = $_POST['match_date'] ?? null;

    if ($team_id <= 0)    $errors[] = "Veuillez sélectionner une équipe.";
    if ($console_id <= 0) $errors[] = "Veuillez sélectionner une console.";
    if ($game_id <= 0)    $errors[] = "Veuillez sélectionner un jeu.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE matches
            SET team_id = ?, opponent = ?, console_id = ?, game_id = ?, match_date = ?
            WHERE id = ?
        ");
        $stmt->execute([$team_id, $opponent ?: null, $console_id, $game_id, $match_date, $id]);
        header('Location: index_ad.php');
        exit;
    }
} else {
    // Préremplissage
    $team_id    = $match['team_id'];
    $console_id = $match['console_id'];
    $game_id    = $match['game_id'];
    $opponent   = $match['opponent'];
    $match_date = $match['match_date'] ? date('Y-m-d\TH:i', strtotime($match['match_date'])) : '';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Éditer le match #<?= $match['id'] ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h1 class="mb-4">✏️ Éditer le match #<?= htmlspecialchars($match['id']) ?></h1>
    <a href="index_ad.php" class="btn btn-secondary mb-3">⬅ Retour</a>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" class="card shadow-sm p-4 bg-white">
      <!-- Équipe -->
      <div class="mb-3">
        <label class="form-label">Équipe</label>
        <select name="team_id" class="form-select" required>
          <option value="0">-- Sélectionner une équipe --</option>
          <?php foreach ($teams as $t): ?>
            <option value="<?= $t['id'] ?>" <?= $team_id==$t['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($t['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Adversaire -->
      <div class="mb-3">
        <label class="form-label">Adversaire (optionnel)</label>
        <input type="text" name="opponent" class="form-control" value="<?= htmlspecialchars($opponent) ?>">
      </div>

      <!-- Console -->
      <div class="mb-3">
        <label class="form-label">Console</label>
        <select name="console_id" id="console-select" class="form-select" required>
          <option value="0">-- Sélectionner une console --</option>
          <?php foreach ($consoles as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $console_id==$c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Jeu -->
      <div class="mb-3">
        <label class="form-label">Jeu</label>
        <select name="game_id" id="game-select" class="form-select" required>
          <option value="0">-- Sélectionner un jeu --</option>
          <?php foreach ($games as $g): ?>
            <option value="<?= $g['id'] ?>" <?= $game_id==$g['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($g['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Date -->
      <div class="mb-3">
        <label class="form-label">Date et heure</label>
        <input type="datetime-local" name="match_date" class="form-control" value="<?= htmlspecialchars($match_date) ?>">
      </div>

      <!-- Bouton -->
      <button type="submit" class="btn btn-warning">💾 Enregistrer les modifications</button>
    </form>
  </div>

  <!-- Script pour charger dynamiquement les jeux selon la console -->
  <script>
  async function loadGames(consoleId, selectedId = null) {
    const sel = document.getElementById('game-select');
    sel.innerHTML = '<option value="0">Chargement...</option>';
    if (!consoleId) {
      sel.innerHTML = '<option value="0">-- Sélectionner une console d\'abord --</option>';
      return;
    }
    try {
      const res = await fetch('get_games.php?console_id=' + encodeURIComponent(consoleId));
      const games = await res.json();
      sel.innerHTML = '<option value="0">-- Sélectionner un jeu --</option>';
      games.forEach(g => {
        const opt = new Option(g.name, g.id);
        if (selectedId && selectedId == g.id) opt.selected = true;
        sel.appendChild(opt);
      });
    } catch (e) {
      sel.innerHTML = '<option value="0">Erreur de chargement</option>';
    }
  }

  document.getElementById('console-select').addEventListener('change', function() {
    loadGames(this.value);
  });

  // Charger les jeux pour la console actuelle
  loadGames(<?= $console_id ?>, <?= $game_id ?>);
  </script>
</body>
</html>
