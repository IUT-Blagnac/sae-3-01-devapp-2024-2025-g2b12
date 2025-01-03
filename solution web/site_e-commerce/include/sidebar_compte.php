<style>
    .sidebar {
        background-color: #f8f9fa;
        padding: 15px;
        border-right: 1px solid #dee2e6;
        height: 100vh; /* Prendre toute la hauteur de la vue */
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 200px; /* Réduire la largeur de la barre latérale */
        position: fixed; /* Fixer la barre latérale */
    }
    .sidebar a {
        display: block;
        padding: 10px;
        color: #333;
        text-decoration: none;
        text-align: center;
    }
    .sidebar a:hover {
        background-color: #e9ecef;
        color: #007bff;
    }
    .sidebar .logout {
        color: #fff;
        background-color: #dc3545; /* Rouge Bootstrap */
    }
    .sidebar .logout:hover {
        background-color: #c82333; /* Rouge plus foncé pour le hover */
    }
</style>

<nav class="sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="visualiser_compte.php">Détail compte</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="visualiser_commande.php">Mes commandes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link logout" href="deconnexion.php">Déconnexion</a>
            </li>
        </ul>
    </div>
</nav>