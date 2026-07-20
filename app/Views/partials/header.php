<?php $session = session(); ?>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f7fb;
}

/* ===== NAVBAR ===== */
.navbar {
    background: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    position: sticky;
    top: 0;
    z-index: 1000;
}

/* LOGO */
.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: bold;
    color: #2c3e50;
}

/* RIGHT MENU */
.nav-right a {
    text-decoration: none;
    margin-left: 15px;
    color: #2c3e50;
    font-weight: 500;
}

.nav-right a:hover {
    color: #3498db;
}

.btn-logout {
    background: #ff6b6b;
    color: white !important;
    padding: 6px 10px;
    border-radius: 6px;
}

/* ===== GLOBAL UI ===== */
.container {
    width: 90%;
    max-width: 1000px;
    margin: 30px auto;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
}

input, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ddd;
}

button {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 14px;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #2980b9;
}
</style>

<div class="navbar">

    <div class="logo">
        <!-- Panier SVG -->
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
            <path d="M6 6h15l-1.5 9h-12L6 6Z" stroke="#3498db" stroke-width="2"/>
            <path d="M6 6L5 3H2" stroke="#3498db" stroke-width="2"/>
            <circle cx="9" cy="20" r="1.5" fill="#2c3e50"/>
            <circle cx="18" cy="20" r="1.5" fill="#2c3e50"/>
        </svg>

        SuperMarché
    </div>

    <div class="nav-right">

        <?php if ($session->get('isLoggedIn')): ?>
            <span>👤 <?= esc($session->get('nom')) ?></span>
            <a class="btn-logout" href="<?= base_url('/logout') ?>">Déconnexion</a>
        <?php else: ?>
            <a href="<?= base_url('/') ?>">Connexion</a>
        <?php endif; ?>

    </div>

</div>