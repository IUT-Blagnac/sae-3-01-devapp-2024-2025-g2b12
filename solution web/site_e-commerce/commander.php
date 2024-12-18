<?php
session_start();
include './include/Connect.inc.php'; // Fichier pour la connexion à la base de données

// Partie HTML et HEAD
require_once('./include/head.php');
require_once('./include/header.php');
require_once('./include/menu.php');
?>

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

<div class="container-fluid flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="row w-100">
        <main class="container" style="margin-top:8%; margin-bottom:5%;">
            <div class="form-container">
                <h2>Commander</h2>
                <form action='traitement_commande.php' method='post'>
                    <h3>Adresse de livraison</h3>
                    <label for='numRueAdresse'>Numéro de rue:</label>
                    <input type='text' id='numRueAdresse' name='numRueAdresse' pattern="\d+" title="Veuillez entrer un numéro de rue valide" required><br><br>
                    <label for='rueAdresse'>Rue:</label>
                    <input type='text' id='rueAdresse' name='rueAdresse' required><br><br>
                    <label for='cdPostalAdresse'>Code postal:</label>
                    <input type='text' id='cdPostalAdresse' name='cdPostalAdresse' pattern="\d{5}" title="Veuillez entrer un code postal valide à 5 chiffres" required><br><br>
                    <label for='villeAdresse'>Ville:</label>
                    <input type='text' id='villeAdresse' name='villeAdresse' required><br><br>
                    <label for='paysAdresse'>Pays:</label>
                    <input type='text' id='paysAdresse' name='paysAdresse' required><br><br>

                    <h3>Méthode de paiement</h3>
                    <label for='modePaiement'>Mode de paiement:</label>
                    <select id='modePaiement' name='modePaiement' required>
                        <option value='CB'>Carte Bancaire</option>
                        <option value='Paypal'>Paypal</option>
                        <option value='Virement'>Virement</option>
                    </select><br><br>
                    <div id="payment-details">
                        <!-- Les détails de paiement seront affichés ici en fonction du mode de paiement sélectionné -->
                    </div>
                    <input type='submit' value='Valider la commande'>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include_once('include/footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modePaiementSelect = document.getElementById('modePaiement');
        const paymentDetailsDiv = document.getElementById('payment-details');
        const form = document.querySelector('form');

        modePaiementSelect.addEventListener('change', function() {
            const selectedPaymentMethod = this.value;
            paymentDetailsDiv.innerHTML = ''; // Clear previous payment details

            if (selectedPaymentMethod === 'CB') {
                paymentDetailsDiv.innerHTML = `
                    <label for='numCB'>Numéro de carte bancaire:</label>
                    <input type='text' id='numCB' name='numCB' pattern="\\d{16}" title="Veuillez entrer un numéro de carte valide à 16 chiffres" required><br><br>
                    <label for='dateExpCB'>Date d'expiration (MM/YY):</label>
                    <input type='text' id='dateExpCB' name='dateExpCB' pattern="(0[1-9]|1[0-2])\\/\\d{2}" title="Veuillez entrer une date d'expiration valide au format MM/YY" maxlength="5" required><br><br>
                    <label for='titulaireCB'>Titulaire de la carte:</label>
                    <input type='text' id='titulaireCB' name='titulaireCB' required><br><br>
                `;

                const dateExpCBInput = document.getElementById('dateExpCB');
                dateExpCBInput.addEventListener('input', function(e) {
                    let value = e.target.value;
                    if (value.length === 2 && !value.includes('/')) {
                        e.target.value = value + '/';
                    }
                });

                dateExpCBInput.addEventListener('keydown', function(e) {
                    let value = e.target.value;
                    if (e.key === 'Backspace' && value.length === 3 && value.includes('/')) {
                        e.target.value = value.slice(0, -1);
                    }
                });
            } else if (selectedPaymentMethod === 'Paypal') {
                paymentDetailsDiv.innerHTML = `
                    <label for='numPaypal'>E-mail de votre compte Paypal:</label><br>
                    <input type='email' id='numPaypal' name='numPaypal' required><br><br>
                `;
            } else if (selectedPaymentMethod === 'Virement') {
                paymentDetailsDiv.innerHTML = `
                    <label for='numVirement'>Numéro de compte pour virement:</label>
                    <input type='text' id='numVirement' name='numVirement' pattern="\\d+" title="Veuillez entrer un numéro de compte valide" required><br><br>
                `;
            }
        });

        // Trigger change event to display the initial payment details
        modePaiementSelect.dispatchEvent(new Event('change'));

        // Show loading popup on form submit
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting immediately

            // Validate PayPal email
            const selectedPaymentMethod = modePaiementSelect.value;
            if (selectedPaymentMethod === 'Paypal') {
                const numPaypal = document.getElementById('numPaypal').value;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(numPaypal)) {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Veuillez entrer une adresse email PayPal valide.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            }

            // Validate Virement account number
            if (selectedPaymentMethod === 'Virement') {
                const numVirement = document.getElementById('numVirement').value;
                const accountPattern = /^\d+$/;
                if (!accountPattern.test(numVirement)) {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Veuillez entrer un numéro de compte valide pour le virement.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
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

            setTimeout(function() {
                form.submit(); // Submit the form after the delay
            }, 2000); // Delay form submission by 2 seconds
        });

        // Handle error popup
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error') && urlParams.get('error') === 'true') {
            const errorMessage = urlParams.get('message') || 'Il y a eu un problème lors de la commande.';
            Swal.fire({
                title: 'Erreur',
                text: errorMessage,
                icon: 'error',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    window.location.href = 'index.php'; // Redirect to homepage after 3 seconds
                }
            });
        }
    });
</script>