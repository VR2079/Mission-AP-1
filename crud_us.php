<?php
require_once __DIR__ . '/config_user_CC.php';

// Pagination / listing simple
$stmt = $pdo->query("SELECT m.id, t.name AS team_name, c.name AS console_name, g.name AS game_name, m.opponent, m.match_date, m.created_at
FROM matches m
JOIN teams t ON m.team_id = t.id
JOIN consoles c ON m.console_id = c.id
JOIN games g ON m.game_id = g.id
ORDER BY m.created_at DESC");
$matches = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Liste des matchs</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>table{border-collapse:collapse}td,th{padding:8px;border:1px solid #ddd}</style>
</head>
<body>
<h1>Matchs</h1>
<p><a href="create.php">Créer un nouveau match</a></p>
<table>
<thead>
<tr><th>ID</th><th>Team</th><th>Opponent</th><th>Console</th><th>Game</th><th>Date</th><th>Actions</th></tr>
</thead>
<tbody>
<?php foreach($matches as $m): ?>
<tr>
<td><?= htmlspecialchars($m['id']) ?></td>
<td><?= htmlspecialchars($m['team_name']) ?></td>
<td><?= htmlspecialchars($m['opponent']) ?></td>
<td><?= htmlspecialchars($m['console_name']) ?></td>
<td><?= htmlspecialchars($m['game_name']) ?></td>
<td><?= $m['match_date'] ? htmlspecialchars($m['match_date']) : '-' ?></td>
<td>
<a href="edit.php?id=<?= urlencode($m['id']) ?>">Éditer</a> |
<a href="delete.php?id=<?= urlencode($m['id']) ?>" onclick="return confirm('Supprimer ce match ?');">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</body>
</html>