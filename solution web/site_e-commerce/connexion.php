<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>
<!-- <?php require_once('./include/menu.php'); ?>-->

<?php

  echo"<br>";
  echo"<br>";
  echo"<br>";
  echo"<br>";
  echo "<h2>Connexion</h2>";
  
  echo "<form action='traitement_connexion.php' method='post'>";
      echo "<label for='login'>Login ou Email:</label>";
      echo "<input type='text' id='login' name='login' required><br><br>";
      echo "<label for='password'>Mot de passe:</label>";
      echo "<input type='password' id='password' name='password' required><br><br>";
      echo "<label for='remember'>Se souvenir de moi:</label>";
      echo "<input type='checkbox' id='remember' name='remember'><br><br>";
      echo "<input type='hidden' name='action' value='connexion'>";
      echo "<input type='submit' value='Se connecter'>";
  echo "</form>"; 

  echo "<br>";
  echo "<p>Vous n'avez pas de compte ? <a href='creer_compte.php'>Créer un compte</a></p>";

  include_once('include/footer.php');
?>