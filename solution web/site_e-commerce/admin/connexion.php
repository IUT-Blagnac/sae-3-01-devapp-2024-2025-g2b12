<!--
Formulaire de connexion administrateur.

Date de dernière modification :
- Lundi 30 décembre 2024 -

Auteurs : Nolhan Biblocque, Victor Jockin
- Équipe 2B12 -
-->

<?php require_once('./include/head.php'); ?>
<?php
    $login  = isset($_COOKIE['admin_login']) ? htmlspecialchars($_COOKIE['admin_login']) : '' ;
    $erreur = isset($_GET['erreur'])  ? htmlspecialchars($_GET['erreur']) : '' ;
?>
<h1>Connexion Administrateur</h1>
<?php if ($erreur) { echo "<p style='color:red; font-size:20px;'>$erreur</p>"; } ?>
<form action='../traitement_connexion.php' method='post'>
    <div>
        <label for='login'>Login ou Email</label>
        <input type='text' id='login' name='login' value='<?php print $login ;?>' required>
    </div>
    <div>
        <label for='password'>Mot de passe</label>
        <input type='password' id='password' name='password' required>
    </div>
    <div class='checkbox-container'>
        <label for='remember'>Se souvenir de moi :</label>
        <input type='checkbox' id='remember' name='remember'>
    </div>
    <input type='hidden' name='action' value='connexion_admin'>
    <input type='submit' value='Se connecter'>
</form>