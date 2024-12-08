<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>

<?php
  echo "<h2>Connexion</h2>";
  echo "<form action='traitement_connexion.php' method='post'>";
      echo "<label for='login'>Login ou Email:</label>";
      echo "<input type='text' id='login' name='login' required><br><br>";
      echo "<label for='password'>Mot de passe:</label>";
      echo "<input type='password' id='password' name='password' required><br><br>";
      echo "<input type='hidden' name='action' value='connexion'>";
      echo "<input type='submit' value='Se connecter'>";
  echo "</form>"; 
  
  include_once('include/footer.php');
?>