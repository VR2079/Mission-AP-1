<?php
/**
 * index.php
 * Page principale : liste des matchs
 */
require_once __DIR__ . '/config_admin_DB.php';

$stmt = $pdo->query("
    SELECT m.id, t.name AS team_name, c.name AS console_name, g.name AS game_name,
           m.opponent, m.match_date, m.created_at
    FROM matches m
    JOIN teams t ON m.team_id = t.id
    JOIN consoles c ON m.console_id = c.id
    JOIN games g ON m.game_id = g.id
    ORDER BY m.created_at DESC
");
$matches = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Liste des matchs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #0b1a2a; /* Bleu foncé */
      color: #ffffff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1 {
      color: #ffffff;
      margin-bottom: 20px;
      border-bottom: 2px solid #1b2a44;
      padding-bottom: 10px;
    }

    .container {
      padding: 30px;
      background-color: #0b1a2a;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.4);
    }

    /* Tableau totalement sombre */
    table {
      width: 100%;
      border-collapse: collapse;
      color: #ffffff;
    }

    thead {
      background-color: #1b2a44; /* En-tête */
    }

    thead th {
      padding: 12px 15px;
      border-bottom: 2px solid #14203b;
    }

    tbody tr {
      background-color: #0d1b2a; /* Ligne sombre */
      border-bottom: 1px solid #14203b;
      transition: background-color 0.2s;
    }

    tbody tr:hover {
      background-color: #14203b;
    }

    tbody td {
      padding: 12px 15px;
      vertical-align: middle;
    }

    /* Boutons harmonisés */
    .btn-primary {
      background-color: #1b2a44;
      border-color: #1b2a44;
      color: #ffffff;
    }
    .btn-primary:hover {
      background-color: #14203b;
      border-color: #14203b;
    }

    .btn-warning {
      background-color: #f0ad4e;
      border-color: #f0ad4e;
      color: #0d1b2a;
    }
    .btn-warning:hover {
      background-color: #d99530;
      border-color: #d99530;
    }

    .btn-danger {
      background-color: #d9534f;
      border-color: #d9534f;
      color: #ffffff;
    }
    .btn-danger:hover {
      background-color: #b52d2a;
      border-color: #b52d2a;
    }

    /* Texte des liens */
    a {
      text-decoration: none;
    }

    /* Responsive pour petits écrans */
    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }
      thead tr {
        display: none;
      }
      tbody tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #14203b;
      }
      tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
      }
      tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        width: calc(50% - 30px);
        font-weight: bold;
        text-align: left;
      }
    }

  </style>
</head>
<body>
  <div class="container">
    <h1>Liste des matchs</h1>

    <a href="create_match_admin.php" class="btn btn-primary mb-3">➕ Créer un match</a>
	<a href="admin.php" class="btn btn-primary mb-3">⬅ Retour</a>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Équipe</th>
          <th>Adversaire</th>
          <th>Console</th>
          <th>Jeu</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($matches as $m): ?>
        <tr>
          <td data-label="ID"><?= htmlspecialchars($m['id']) ?></td>
          <td data-label="Équipe"><?= htmlspecialchars($m['team_name']) ?></td>
          <td data-label="Adversaire"><?= htmlspecialchars($m['opponent'] ?? '-') ?></td>
          <td data-label="Console"><?= htmlspecialchars($m['console_name']) ?></td>
          <td data-label="Jeu"><?= htmlspecialchars($m['game_name']) ?></td>
          <td data-label="Date"><?= $m['match_date'] ? htmlspecialchars($m['match_date']) : '-' ?></td>
          <td data-label="Actions">
            <a href="edit_match.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-warning">✏️ Éditer</a>
            <a href="delete_match.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" 
               onclick="return confirm('Supprimer ce match ?');">🗑️ Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
