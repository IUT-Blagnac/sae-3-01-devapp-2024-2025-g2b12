<!-- inclusion head de formulaire -->
<?php require_once('./include/form/form_head.php'); ?>

<body>
    <!-- traitement de la création du compte -->
    <!-- auteur : Nolhan Biblocque -->
    <?php
        $login = isset($_GET['login']) ? htmlspecialchars($_GET['login']) : '';
        $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
        $nom = isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '';
        $prenom = isset($_GET['prenom']) ? htmlspecialchars($_GET['prenom']) : '';
        $numTel = isset($_GET['numTel']) ? htmlspecialchars($_GET['numTel']) : '';

        $loginError = isset($_GET['loginError']) ? htmlspecialchars($_GET['loginError']) : '';
        $emailError = isset($_GET['emailError']) ? htmlspecialchars($_GET['emailError']) : '';
        $passwordError = isset($_GET['passwordError']) ? htmlspecialchars($_GET['passwordError']) : '';
        $nomError = isset($_GET['nomError']) ? htmlspecialchars($_GET['nomError']) : '';
        $prenomError = isset($_GET['prenomError']) ? htmlspecialchars($_GET['prenomError']) : '';
        $numTelError = isset($_GET['numTelError']) ? htmlspecialchars($_GET['numTelError']) : '';
    ?>

    <div class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h1 class="form-title">Créer un compte</h1>
            </div>
            <div class="form-content">
                <form action='traitement_connexion.php' method='post'>
                    <input type='hidden' name='action' value='creer_compte'>
                    <div class="input-container">
                        <label class='input-field-label' for='prenom'>Prénom</label>
                        <input type='text' id='prenom' name='prenom' value='<?php echo $prenom; ?>' class="input-field" required>
                        <?php if ($prenomError) echo "<p style='color:red'>$prenomError</p>"; ?>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='nom'>Nom</label>
                        <input type='text' id='nom' name='nom' value='<?php echo $nom; ?>' class="input-field" required>
                        <?php if ($nomError) echo "<p style='color:red'>$nomError</p>"; ?>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='login'>Nom d'utilisateur (login)</label>
                        <input type='text' id='login' name='login' value='<?php echo $login; ?>' class="input-field" required>
                        <?php if ($loginError) echo "<p style='color:red'>$loginError</p>"; ?>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='password'>Mot de passe</label>
                        <input type='password' id='password' name='password' class="input-field" required>
                        <small class="form-text text-muted" style="font-size: 0.7em; color: #6c757d;">Le mot de passe doit contenir au moins 8 caractères, dont une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.</small>
                        <?php if ($passwordError) echo "<p style='color:red'>$passwordError</p>"; ?>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='email'>Email</label>
                        <input type='email' id='email' name='email' value='<?php echo $email; ?>' class="input-field" required>
                        <?php if ($emailError) echo "<p style='color:red'>$emailError</p>"; ?>
                    </div>
                    <div class="input-container">
                        <label class='input-field-label' for='numTel'>Numéro de téléphone</label>
                        <input type='text' id='numTel' name='numTel' value='<?php echo $numTel; ?>' class="input-field" required>
                        <?php if ($numTelError) echo "<p style='color:red'>$numTelError</p>"; ?>
                    </div>
                    
                    <input class='validation-button' type='submit' value="CRÉER">
                </form>
            </div>
        </div>
    </div>
</body>