<nav class="menu-content">
    <div class="inner">
        <div class="menu">
            <div class="menu-user">
                <?php if($user) : ?>
                    <span><?= $user->utilisateur ?></span>
                    <a class="menu-item" href="logout.php">deconnexion</a>
                <?php endif; ?>
            </div>
            <a class="menu-item" href="../page/article.php">articles</a>
            <a class="menu-item" href="../page/fabrication.php">fabrications</a>
            <a class="menu-item" href="../page/mesure.php">mesure</a>
            <a class="menu-item" href="../page/client.php">client</a>
            <?php if($user->role === 'admin') : ?>
                <a class="menu-item" href="../page/register.php">Ajoutez un utilisateur</a>
            <?php endif; ?> 
        </div>
    </div>
</nav>