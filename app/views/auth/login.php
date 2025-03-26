<!-- app/views/auth/login.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<?php if (isset($message) && $message !== ''): ?>
    <p id="session-message"><?= htmlspecialchars($message); ?></p>
<?php endif; ?>

<main class="auth-container">
    <h2>Login</h2>

    <!-- Display Errors -->
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>* <?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="app/controllers/LoginController.php?action=authenticate" method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>
        
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password" required>
        <label>
            <input type="checkbox" name="remember"> Remember Me
        </label>
        
        <input type="submit" class="btn" value="Login">
    </form>

    <p><a href="#">Forgot Password?</a></p>

    <p>Don't have an account? <a href="<?= BASE_URL ?>app/controllers/SignupController.php">Sign up here</a></p>

</main>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
