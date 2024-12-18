<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>
<!-- <?php require_once('./include/menu.php'); ?>-->


<style>
.form-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    background-color: #fff;
}

.form-container h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-container label {
    font-weight: bold;
}

.form-container input[type="text"],
.form-container input[type="password"],
.form-container input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.form-container input[type="submit"] {
    background-color: rgba(136, 172, 223);
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.form-container input[type="submit"]:hover {
    background-color: rgb(67, 83, 107);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.form-container p {
    text-align: center;
}

.form-container a {
    color: #007bff;
    text-decoration: none;
}

.form-container a:hover {
    text-decoration: underline;
}

</style>

<div class="container-fluid flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="row w-100">
        <main class="container" style="margin-top:8%; margin-bottom:5%;">
            <div class="form-container">
                <h2>Connexion</h2>
                <form action='traitement_connexion.php' method='post'>
                    <label for='login'>Login ou Email:</label>
                    <input type='text' id='login' name='login' required><br><br>
                    <label for='password'>Mot de passe:</label>
                    <input type='password' id='password' name='password' required><br><br>
                    <label for='remember'>Se souvenir de moi:</label>
                    <input type='checkbox' id='remember' name='remember'><br><br>
                    <input type='hidden' name='action' value='connexion'>
                    <input type='submit' value='Se connecter'>
                </form>
                <br>
                <p>Vous n'avez pas de compte ? <a href='creer_compte.php'>Créer un compte</a></p>
            </div>
        </main>
    </div>
</div>

<?php include_once('include/footer.php'); ?>