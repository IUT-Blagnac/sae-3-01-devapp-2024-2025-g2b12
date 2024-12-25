<?php
session_start();

// Vérifiez si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    // Redirigez vers la page de connexion de l'administrateur si l'utilisateur n'est pas connecté
    header("Location: ../connexion_administrateur.php");
    exit();
}
?>

<!-- partie html & head -->
<?php require_once('../include/head.php'); ?>

<!-- partie body -->
<?php require_once('../include/header.php'); ?>

<h2>Espace Administrateur</h2>
<p>Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_login']); ?> !</p>

<?php include_once('../include/footer.php'); ?>