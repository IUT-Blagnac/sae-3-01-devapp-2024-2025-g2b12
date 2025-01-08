<!-- Partie HTML et HEAD -->
<?php require_once('./include/head.php'); ?>

<?php $total = htmlentities($_GET['Total']); ?>

<div class="container-fluid flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="row w-100">
        <main class="container" style="margin-top:8%; margin-bottom:5%;">
            <div class="form-container">
                <h2>Commander</h2>
                <form action='traitement_commande.php' method='post'>
                <input type='hidden' name='Total' value='<?php echo htmlspecialchars($total); ?>'>
                <h3>Adresse de livraison</h3>
                <label for='lieuLivraison'>Lieu de livraison:</label>
                <select id='lieuLivraison' name='lieuLivraison' required>
                    <option value='Domicile'>Domicile</option>
                    <option value='Relais'>Point Relais</option>
                </select><br><br>
                <div id="adresse-livraison">
                    <label for='numRueAdresse'>Numéro de rue:</label>
                    <input type='text' id='numRueAdresse' name='numRueAdresse' pattern="\d+"
                        title="Veuillez entrer un numéro de rue valide" required><br><br>
                    <label for='rueAdresse'>Rue:</label>
                    <input type='text' id='rueAdresse' name='rueAdresse' required><br><br>
                    <label for='cdPostalAdresse'>Code postal:</label>
                    <input type='text' id='cdPostalAdresse' name='cdPostalAdresse' pattern="\d{5}"
                        title="Veuillez entrer un code postal valide à 5 chiffres" required><br><br>
                    <label for='villeAdresse'>Ville:</label>
                    <input type='text' id='villeAdresse' name='villeAdresse' required><br><br>
                    <label for='paysAdresse'>Pays:</label>
                    <input type='text' id='paysAdresse' name='paysAdresse' required><br><br>
                    <input type='hidden' id='idAdresse' name='idAdresse'>
                </div>
                <div id="relais-livraison" style="display: none;">
                    <label for='searchRelais'>Rechercher un point relais (ville ou code postal):</label>
                    <input type='text' id='searchRelais' name='searchRelais'><br><br>
                    <label for='idRelais'>Sélectionnez un point relais:</label>
                    <select id='idRelais' name='idRelais'>
                    </select><br><br>
                </div>

                <h3>Méthode de paiement</h3>
                <label for='modePaiement'>Mode de paiement:</label>
                <select id='modePaiement' name='modePaiement' required>
                    <option value='CB'>Carte Bancaire</option>
                    <option value='Paypal'>Paypal</option>
                    <option value='Virement'>Virement</option>
                </select><br><br>
                <div id="payment-details">
                    <label for='numCB'>Numéro de carte bancaire:</label>
                    <input type='text' id='numCB' name='numCB' pattern="\d{16}"
                        title="Veuillez entrer un numéro de carte valide à 16 chiffres" required><br><br>
                    <label for='dateExpCB'>Date d'expiration (MM/AA):</label>
                    <input type='text' id='dateExpCB' name='dateExpCB' pattern="\d{2}/\d{2}"
                        title="Veuillez entrer une date d'expiration valide (MM/AA)" required><br><br>
                    <label for='titulaireCB'>Titulaire de la carte:</label>
                    <input type='text' id='titulaireCB' name='titulaireCB' required><br><br>
                </div>
                <input type='submit' value='Valider la commande'>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include_once('include/footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lieuLivraisonSelect = document.getElementById('lieuLivraison');
        const adresseLivraisonDiv = document.getElementById('adresse-livraison');
        const relaisLivraisonDiv = document.getElementById('relais-livraison');
        const searchRelaisInput = document.getElementById('searchRelais');
        const idRelaisSelect = document.getElementById('idRelais');
        const modePaiementSelect = document.getElementById('modePaiement');
        const paymentDetailsDiv = document.getElementById('payment-details');
        const form = document.querySelector('form');

        function initializeDateExpCBInput() {
            const dateExpCBInput = document.getElementById('dateExpCB');
            if (dateExpCBInput) {
                dateExpCBInput.addEventListener('input', function (e) {
                    let value = e.target.value;

                    // 5 caratcteres max ( MM/AA )
                    if (value.length > 5) {
                        value = value.slice(0, 5);
                        e.target.value = value;
                    }

                    if (e.inputType === 'deleteContentBackward') {
                        if (value.length === 3 && value.includes('/')) {
                            e.target.value = value.slice(0, 2);
                        } else if (value.length === 2 && value[1] === '/') {
                            e.target.value = value[0];
                        }
                    } else {
                        if (value.length === 2 && !value.includes('/')) {
                            e.target.value = value + '/';
                        }
                    }

                    // Mois valide ( entre 01 et 12)
                    const month = parseInt(value.slice(0, 2), 10);
                    if (month < 1 || month > 12) {
                        dateExpCBInput.setCustomValidity('Le mois doit être compris entre 01 et 12.');
                    } else {
                        dateExpCBInput.setCustomValidity('');
                    }

                    // Date d'expiration de CB valide ( pas antérieure à la date actuelle sinon CB invalide )
                    if (value.length === 5) {
                        const [inputMonth, inputYear] = value.split('/');
                        const currentDate = new Date();
                        const inputDate = new Date(`20${inputYear}`, inputMonth - 1);
                        if (inputDate < currentDate) {
                            dateExpCBInput.setCustomValidity('La date d\'expiration ne peut pas être antérieure à la date actuelle.');
                        } else {
                            dateExpCBInput.setCustomValidity('');
                        }
                    }
                });
            }
        }

        function initializeNumPaypalInput() {
            const numPaypalInput = document.getElementById('numPaypal');
            if (numPaypalInput) {
                numPaypalInput.addEventListener('input', function (e) {
                    let value = e.target.value;

                    // 10 caratcteres max ( 1 à 10 chiffres pour la bd )
                    if (value.length > 10) {
                        value = value.slice(0, 10);
                        e.target.value = value;
                    }
                });
            }
        }

        function initializeNumVirementInput() {
            const numVirementInput = document.getElementById('numVirement');
            if (numVirementInput) {
                numVirementInput.addEventListener('input', function (e) {
                    let value = e.target.value;

                    // 110 caratcteres max ( 1 à 10 chiffres pour la bd )
                    if (value.length > 10) {
                        value = value.slice(0, 10);
                        e.target.value = value;
                    }
                });
            }
        }

        // Cette fonction est utilisée car sans fonction, les regex de payement sur cb ne fonctionne pas avant une resélection du mode de payement
        function initializePaymentDetails()
        // Affichage dynamique du formulaire en fonction de la methode de payement choisi
        {
            const selectedPaymentMethod = modePaiementSelect.value;
            if (selectedPaymentMethod === 'CB') {
                paymentDetailsDiv.innerHTML = `
                    <label for='numCB'>Numéro de carte bancaire:</label>
                    <input type='text' id='numCB' name='numCB' pattern="\\d{16}" title="Veuillez entrer un numéro de carte valide à 16 chiffres" required><br><br>
                    <label for='dateExpCB'>Date d'expiration (MM/AA):</label>
                    <input type='text' id='dateExpCB' name='dateExpCB' pattern="(0[1-9]|1[0-2])/\\d{2}" title="Veuillez entrer une date d'expiration valide (MM/AA)" required><br><br>
                    <label for='titulaireCB'>Titulaire de la carte:</label>
                    <input type='text' id='titulaireCB' name='titulaireCB' required><br><br>
                `;
                initializeDateExpCBInput();
            } else if (selectedPaymentMethod === 'Paypal') {
                paymentDetailsDiv.innerHTML = `
                    <label for='numPaypal'>Numéro de votre compte Paypal : </label><br>
                    <input type='text' id='numPaypal' name='numPaypal' pattern="\\d{1,10}" title="Veuillez entrer un numéro de compte valide à 10 chiffres" required><br><br>
                `;
                initializeNumPaypalInput();
            } else if (selectedPaymentMethod === 'Virement') {
                paymentDetailsDiv.innerHTML = `
                    <label for='numVirement'>Numéro de compte pour virement:</label>
                    <input type='text' id='numVirement' name='numVirement' pattern="\\d{1,10}" title="Veuillez entrer un numéro de compte valide à 10 chiffres" required><br><br>
                `;
                initializeNumVirementInput();
            }
        }

        // Affichage dynamique du formulaire en fonction du mode de livraison choisi
        lieuLivraisonSelect.addEventListener('change', function () {
            if (this.value === 'Domicile') {
                adresseLivraisonDiv.style.display = 'block';
                relaisLivraisonDiv.style.display = 'none';
                idRelaisSelect.removeAttribute('required');
                document.getElementById('numRueAdresse').setAttribute('required', 'required');
                document.getElementById('rueAdresse').setAttribute('required', 'required');
                document.getElementById('cdPostalAdresse').setAttribute('required', 'required');
                document.getElementById('villeAdresse').setAttribute('required', 'required');
                document.getElementById('paysAdresse').setAttribute('required', 'required');
            } else if (this.value === 'Relais') {
                adresseLivraisonDiv.style.display = 'none';
                relaisLivraisonDiv.style.display = 'block';
                idRelaisSelect.setAttribute('required', 'required');
                document.getElementById('numRueAdresse').removeAttribute('required');
                document.getElementById('rueAdresse').removeAttribute('required');
                document.getElementById('cdPostalAdresse').removeAttribute('required');
                document.getElementById('villeAdresse').removeAttribute('required');
                document.getElementById('paysAdresse').removeAttribute('required');
            }
        });

        // Recherche dynamique des points relais en fonction du nom de la ville ou du code postal dans la bd
        searchRelaisInput.addEventListener('input', function () {
            const searchValue = this.value.trim().toLowerCase();
            if (searchValue.length > 0) {
                fetch(`search_relais.php?query=${searchValue}`)
                    .then(response => response.json())
                    .then(data => {
                        idRelaisSelect.innerHTML = '';
                        data.forEach(relais => {
                            const option = document.createElement('option');
                            option.value = relais.idRelais;
                            option.textContent = `${relais.nomRelais} - ${relais.rueRelais}, ${relais.villeRelais}`;
                            idRelaisSelect.appendChild(option);
                        });
                    });
            } else {
                idRelaisSelect.innerHTML = '';
            }
        });

        modePaiementSelect.addEventListener('change', initializePaymentDetails);

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            // Regex du num de paypal attendue ( 1 à 10 chiffres pas de lettres )
            const selectedPaymentMethod = modePaiementSelect.value;
            if (selectedPaymentMethod === 'Paypal') {
                const numPaypal = document.getElementById('numPaypal').value;
                const numPaypalPattern = /^\d{1,10}$/;
                if (!numPaypalPattern.test(numPaypal)) {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Veuillez entrer un numéro de compte PayPal valide (1 à 10 chiffres).',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            }

            // Regex du num de virement attendue ( 1 à 10 chiffres pas de lettres )
            if (selectedPaymentMethod === 'Virement') {
                const numVirement = document.getElementById('numVirement').value;
                const accountPattern = /^\d{1,10}$/;
                if (!accountPattern.test(numVirement)) {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Veuillez entrer un numéro de compte valide pour le virement (1 à 10 chiffres).',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            }

            // Vérifiez que l'adresse de livraison est définie dans la BD, sinon pop up erreur
            if (lieuLivraisonSelect.value === 'Relais' && !idRelaisSelect.value) {
                Swal.fire({
                    title: 'Erreur',
                    text: 'Veuillez sélectionner un point relais.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Traitement de la commande',
                text: 'Votre commande est en cours de traitement...',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(function () {
                form.submit(); // Submit the form after the delay
            }, 2000); // Delay form submission by 2 seconds
        });

        // Pop up d'erreur si erreur envoyé dans l'url
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error') && urlParams.get('error') === 'true') {
            const errorMessage = urlParams.get('message') || 'Il y a eu un problème lors de la commande.';
            Swal.fire({
                title: 'Erreur',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

        // Initialisation des détails de paiement et d'un écouteur d'événements pour le champ dateExpCB au chargement de la page
        initializePaymentDetails();
    });
</script>

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
    .form-container select,
    .form-container input[type="email"],
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