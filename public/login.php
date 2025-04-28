<?php require_once('../src/functions/login.php'); ?>

<?php include('../templates/head.php'); ?>

<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="login.php">
            <h2>Entrar</h2>
            
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>

                <?php if (!empty($erros['email'])): ?>
                    <p class="error-message"><?= $erros['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" name="password" id="password" required>

                <?php if (!empty($erros['password'])): ?>
                    <p class="error-message"><?= $erros['password'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit">Entrar</button>

            <p class="register-link">
                Não tem uma conta? <a href="register.php">Cadastre-se</a>
            </p>
        </form>
    
        <?php include('../templates/footer.php'); ?>
    </div>
</body>
</html>