<!-- inclusion head de formulaire -->
<?php require_once('./include/form/form_head.php') ; ?>

<body>
    <!-- traitement de la création du compte -->
    <!-- auteur : Nolhan Biblocque -->
    <?php
        $login = isset($_GET['login']) ? htmlspecialchars($_GET['login']) : '';
        $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
        $nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '';
        $prenom = isset($_GET['prenom']) ? htmlspecialchars($_GET['prenom']) : '';
        $numTel = isset($_GET['numTel']) ? htmlspecialchars($_GET['numTel']) : '';

        if (isset($_GET['erreur']))
        {
            echo "<p style='color:red'>" . htmlspecialchars($_GET['erreur']) . "</p>";
        }
    ?>

    <!-- formulaire de création de compte -->
    <!-- auteur : Victor Jockin -->
    <?php //echo "<br/><strong>-  En cours de développement  -</strong><br/><br/>" ; ?>
    <div class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h1 class="form-title">Créer un compte</h1>
            </div>
            <div class="form-content">
                <form action='traitement_connexion.php' method='post'>
                    <div class="input-container">
                        <label class='input-field-label' for='prenom'>Prénom</label>
                        <input type='text' id='prenom' name='prenom' value='<?php echo $prenom; ?>' class="input-field" required>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='nom'>Nom</label>
                        <input type='text' id='nom' name='nom' value='<?php echo $nom; ?>' class="input-field" required>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='login'>Nom d'utilisateur (login)</label>
                        <input type='text' id='login' name='login' value='<?php echo $login; ?>' class="input-field" required>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='password'>Mot de passe</label>
                        <input type='password' id='password' name='password' class="input-field" required>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='email'>Email</label>
                        <input type='email' id='email' name='email' value='<?php echo $email; ?>' class="input-field" required>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='numTel'>Numéro de téléphone</label>
                        <input type='text' id='numTel' name='numTel' value='<?php echo $numTel; ?>' class="input-field" required>
                    </div>
                    <!--<input type='hidden' name='action' value='creer_compte'>-->
                    <input class='validation-button' type='submit' value="CRÉER">
                </form>
            </div>
        </div>
    </div>
</body>