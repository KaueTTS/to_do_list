<?php require_once('../src/functions/register.php'); ?>

<?php include('../templates/head.php'); ?>

<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="register.php">
            <h2>Registra-se</h2>

            <div class="input-group">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($nome) ?>" required>
                <?php if (!empty($erros['name'])): ?>
                    <p class="error-message"><?= $erros['name'] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>
                <?php if (!empty($erros['email'])): ?>
                    <p class="error-message"><?= $erros['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" required>
                <?php if (!empty($erros['password'])): ?>
                    <p class="error-message"><?= $erros['password'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit">Cadastrar</button>

            <p class="register-link">
                Já tem uma conta? <a href="login.php">Entrar</a>
            </p>
        </form>

        <?php include('../templates/footer.php'); ?>
    </div>
</body>
</html>