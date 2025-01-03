<!--
Formulaire de connexion administrateur.

Date de dernière modification :
- Lundi 30 décembre 2024 -

Auteurs : Nolhan Biblocque, Victor Jockin
- Équipe 2B12 -
-->

<?php require_once('../include/form/form_head.php'); ?>
<?php
    $login  = isset($_GET['login'])   ? htmlspecialchars($_GET['login'])  : '' ;
    $erreur = isset($_GET['erreur'])  ? htmlspecialchars($_GET['erreur']) : '' ;
?>
<h2>Connexion Administrateur</h2>
<center><p><em>- En cours de développement -</em></p></center>
<?php if ($erreur) { echo "<p style='color:red; font-size:20px;'>$erreur</p>"; } ?>
<form action='../traitement_connexion.php' method='post'>
    <label for='login'>Login ou Email:</label>
    <input type='text' id='login' name='login' value='$login' required><br><br>
    <label for='password'>Mot de passe:</label>
    <input type='password' id='password' name='password' required><br><br>
    <label for='remember'>Se souvenir de moi:</label>
    <input type='checkbox' id='remember' name='remember'><br><br>
    <input type='hidden' name='action' value='connexion_admin'>
    <input type='submit' value='Se connecter'>
</form>