<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>

<?php
  $login = isset($_GET['login']) ? htmlspecialchars($_GET['login']) : '';
  $erreur = isset($_GET['erreur']) ? htmlspecialchars($_GET['erreur']) : '';

  echo "<h2>Connexion Administrateur</h2>";
  if ($erreur) {
      echo "<p style='color:red; font-size:20px;'>$erreur</p>";
  }
  echo "<form action='traitement_connexion.php' method='post'>";
      echo "<label for='login'>Login ou Email:</label>";
      echo "<input type='text' id='login' name='login' value='$login' required><br><br>";
      echo "<label for='password'>Mot de passe:</label>";
      echo "<input type='password' id='password' name='password' required><br><br>";
      echo "<label for='remember'>Se souvenir de moi:</label>";
      echo "<input type='checkbox' id='remember' name='remember'><br><br>";
      echo "<input type='hidden' name='action' value='connexion_admin'>";
      echo "<input type='submit' value='Se connecter'>";
  echo "</form>"; 
  
  include_once('include/footer.php');
?>