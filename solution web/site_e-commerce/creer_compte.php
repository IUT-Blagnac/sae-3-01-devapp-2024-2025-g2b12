<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>

<?php
  $login = isset($_GET['login']) ? htmlspecialchars($_GET['login']) : '';
  $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
  $nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '';
  $prenom = isset($_GET['prenom']) ? htmlspecialchars($_GET['prenom']) : '';
  $numTel = isset($_GET['numTel']) ? htmlspecialchars($_GET['numTel']) : '';

  if (isset($_GET['erreur'])) {
      echo "<p style='color:red'>" . htmlspecialchars($_GET['erreur']) . "</p>";
  }
?>

<h2>Créer un compte</h2>
<form action='traitement_connexion.php' method='post'>
    <label for='login'>Login:</label>
    <input type='text' id='login' name='login' value='<?php echo $login; ?>' required><br><br>
    <label for='email'>Email:</label>
    <input type='email' id='email' name='email' value='<?php echo $email; ?>' required><br><br>
    <label for='password'>Mot de passe:</label>
    <input type='password' id='password' name='password' required><br><br>
    <label for='nom'>Nom:</label>
    <input type='text' id='nom' name='nom' value='<?php echo $nom; ?>' required><br><br>
    <label for='prenom'>Prénom:</label>
    <input type='text' id='prenom' name='prenom' value='<?php echo $prenom; ?>' required><br><br>
    <label for='numTel'>Numéro de téléphone:</label>
    <input type='text' id='numTel' name='numTel' value='<?php echo $numTel; ?>' required><br><br>
    <input type='hidden' name='action' value='creer_compte'>
    <input type='submit' value='Créer un compte'>
</form>

<?php include_once('include/footer.php'); ?>