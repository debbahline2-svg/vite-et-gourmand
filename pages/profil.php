<?php 
require_once '../includes/header.php'; 
require_once '../includes/db.php'; 
require_once '../includes/user.php';

// Sécurité
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$userInfo = $user->getOneById($_SESSION['user_id']);
?>

<style>
    body { background-color: #0c0b0a !important; color: white; }
    .profil-container { padding: 60px 0; }
    .info-card { background: #161513; border: 1px solid #C17F3A; border-radius: 15px; padding: 30px; margin-bottom: 20px; }
    .gold-text { color: #C17F3A; font-family: 'Playfair Display', serif; }
    .badge-statut { background-color: #C17F3A; color: black; font-weight: bold; padding: 5px 10px; border-radius: 5px; font-size: 0.7rem; }
    .table-dark { --bs-table-bg: #1a1a1a; }
</style>

<div class="container profil-container">
    <?php if (isset($_GET['status']) && $_GET['status'] === 'cancelled'): ?>
        <div class="alert alert-info text-center mb-4"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="info-card text-center">
                <h3 class="gold-text">Mon Profil</h3>
                <p class="mt-4"><strong><?= htmlspecialchars($userInfo['prenom'] . ' ' . $userInfo['nom']) ?></strong></p>
                <a href="edit_profil.php" class="btn btn-outline-warning btn-sm w-100">Modifier mes infos</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="info-card">
                <h3 class="gold-text">Mes Commandes</h3>
                <div class="table-responsive mt-4">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr style="color: #C17F3A;">
                                <th>Date</th><th>Heure</th><th>Menu</th><th>Pers.</th><th>Prix</th><th>Statut</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt_cmd = $db->prepare("SELECT c.*, m.titre FROM commandes c JOIN menus m ON c.id_menu = m.id WHERE c.user_id = :uid ORDER BY c.date_commande DESC");
                            $stmt_cmd->execute([':uid' => $_SESSION['user_id']]);
                            $commandes = $stmt_cmd->fetchAll(PDO::FETCH_ASSOC);

                            if (count($commandes) > 0): 
                                foreach ($commandes as $cmd): 
                                    $statut_propre = strtolower(trim($cmd['statut'] ?? ''));
                                ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($cmd['date_evenement'])) ?></td>
                                        <td><?= !empty($cmd['heure_livraison']) ? date('H:i', strtotime($cmd['heure_livraison'])) : '12:00' ?></td>
                                        <td><?= htmlspecialchars($cmd['titre']) ?></td>
                                        <td><?= $cmd['nb_personnes'] ?></td>
                                        <td><?= number_format($cmd['total_prix'], 2, ',', ' ') ?> €</td>
                                        <td>
                                            <span class="badge-statut"><?= strtoupper($cmd['statut']) ?></span>
                                            <?php if (!empty($cmd['date_modification'])): ?>
                                                <br><small style="color: #888; font-size: 0.7rem; font-style: italic;">
                                                    Modifié le : <?= date('d/m/Y à H:i', strtotime($cmd['date_modification'])) ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($statut_propre === 'en attente'): ?>
                                                <form action="annuler_commande.php" method="POST" onsubmit="return confirm('Annuler cette commande ?');">
                                                    <input type="hidden" name="id_commande" value="<?= $cmd['id'] ?>">
                                                    <input type="text" name="raison" placeholder="Raison ?" required class="form-control form-control-sm mb-1" style="background:#222; color:white; font-size: 0.7rem;">
                                                    <button type="submit" class="btn btn-danger btn-sm" style="font-size: 0.7rem;">Annuler</button>
                                                </form>
                                            <?php else: ?> - <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; 
                            else: ?>
                                <tr><td colspan="7" class="text-center text-muted">Aucune commande.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>