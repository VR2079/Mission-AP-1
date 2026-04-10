<?php
session_start();
require_once __DIR__ . '/config_admin_CC.php';

// --- Récupération des utilisateurs ---
try {
    $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur récupération utilisateurs : " . $e->getMessage());
}

// --- Paramètres placeholder ---
$settings = [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Corps et fond sombre */
body {
    font-family:'Arial',sans-serif;
    background-color:#0a0c14;
    color:#e0e0e0;
    margin:0;
}

/* Loader */
#loader {
    position:fixed; top:0; left:0; width:100%; height:100%;
    background:#0a0c14;
    display:flex; align-items:center; justify-content:center; flex-direction:column;
    z-index:9999;
}
.spinner {
    width:60px; height:60px;
    border:6px solid rgba(200,200,200,0.2);
    border-top-color:#666;
    border-radius:50%;
    animation:spin 1s linear infinite;
    margin-bottom:15px;
}
@keyframes spin {0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}

/* Header */
header {
    width:100%; background:#11131a;
    padding:15px 30px; display:flex;
    justify-content:space-between; align-items:center;
    position:sticky; top:0; z-index:1000;
    box-shadow:0 4px 10px rgba(0,0,0,0.7);
}
header h1 { font-size:1.5rem; color:#ccc; margin:0; }
header nav a { color:#ccc; margin-left:20px; text-decoration:none; font-weight:500; transition:0.3s; }
header nav a:hover { color:#aaa; }

/* Sections */
.section { display:none; padding:30px; background:#0a0c14; }
.section.active { display:block; }

/* Dashboard Cards */
.dashboard { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:20px; }
.card {
    background:#0a0c14; 
    border-radius:12px;
    padding:20px;
    box-shadow:0 4px 15px rgba(0,0,0,0.7);
    transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover { transform: translateY(-5px); box-shadow:0 8px 25px rgba(0,0,0,0.9);}
.card h3 { color:#aaa; margin-bottom:10px; }
.card p { color:#aaa; font-size:1rem; }

/* Tableau sombre pour utilisateurs et matchs */
.table-dark {
    width:100%; border-collapse: separate; border-spacing:0;
    background-color:#11131a; color:#ddd; border-radius:8px; overflow:hidden;
}
.table-dark th, .table-dark td {
    padding:12px 15px; text-align:left; border-bottom:1px solid #22232a;
}
.table-dark thead { background-color:#14151e; color:#ccc; }
.table-dark tbody tr:nth-child(even){ background-color:#10111a; }
.table-dark tbody tr:hover { background-color:#1a1b24; }

/* Boutons sobres */
.btn-primary { background:#333; color:#ddd; border:none; }
.btn-primary:hover { background:#555; }
.btn-warning { background:#555; color:#ddd; border:none; }
.btn-warning:hover { background:#777; }
.btn-danger { background:#7a1f1f; color:#ddd; border:none; }
.btn-danger:hover { background:#a22a2a; }

/* Responsive */
@media(max-width:768px){
    header { flex-direction:column; gap:10px; }
    header nav a { margin-left:0; }
}
</style>
</head>
<body>

<div id="loader">
    <div class="spinner"></div>
    <p>Chargement du dashboard...</p>
</div>

<div id="adminContent" style="display:none;">

<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <a href="#" onclick="showSection('dashboard')">Dashboard</a>
        <a href="#" onclick="showSection('users')">Utilisateurs</a>
        <a href="#" onclick="showSection('matches')">Matchs</a>
        <a href="#" onclick="showSection('settings')">Paramètres</a>
        <a href="accueil_user.php">Retour Site</a>
		<a href="logout.php">Déconnexion</a>
    </nav>
</header>

<!-- Dashboard -->
<div id="dashboard" class="section active">
    <div class="dashboard">

        <!-- Carte Utilisateurs -->
        <div class="card cursor-pointer" onclick="showSection('users')">
            <h3>Utilisateurs</h3>
            <p><?= count($users) ?></p>
        </div>

        <!-- Carte Matchs -->
        <div class="card cursor-pointer" onclick="showSection('matches')">
            <h3>Matchs</h3>
            <p>Voir dans la section Matchs</p>
        </div>

        <!-- Carte Paramètres -->
        <div class="card cursor-pointer" onclick="showSection('settings')">
            <h3>Paramètres</h3>
            <p><?= count($settings) ?></p>
        </div>

    </div>
</div>

<script>
/**
 * Affiche la section correspondant à l'ID donné
 * et cache les autres sections.
 */
function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<style>
.dashboard {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}

.card {
  background: #1e293b;
  color: #fff;
  padding: 2rem;
  border-radius: 12px;
  text-align: center;
  transition: transform 0.2s, background 0.3s;
}

.card:hover {
  background: #334155;
  transform: translateY(-5px);
  cursor: pointer;
}
</style>

<!-- Gestion Utilisateurs -->
<div id="users" class="section">
    <h2 style="color:#ccc;">Gestion des Utilisateurs</h2>
    <a href="create_user.php" class="btn btn-primary mb-3">➕ Ajouter utilisateur</a>
    <div class="table-responsive">
        <table class="table-dark table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Créé le</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td><?= htmlspecialchars($u['created_at']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">✏️ Éditer</a>
                        <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?');">🗑️ Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Gestion Matchs -->
<div id="matches" class="section">
    <h2 style="color:#ccc;">Gestion des Matchs</h2>
    <?php include 'index_ad.php'; // Inclut ton CRUD sombre existant ?>
</div>

<!-- Paramètres -->
<div id="settings" class="section">
    <h2 style="color:#ccc;">Paramètres</h2>
    <p>Section à modifier pour gérer les paramètres du site.</p>
</div>

</div>

<script>
window.addEventListener('load', function(){
    setTimeout(function(){
        document.getElementById('loader').style.display='none';
        document.getElementById('adminContent').style.display='block';
    },800);
});
function showSection(id){
    document.querySelectorAll('.section').forEach(s=>s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
}
</script>

</body>
</html>
