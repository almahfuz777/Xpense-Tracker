<!-- app/views/auth/signup.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main class="auth-container">
    <h2>Sign Up</h2>

    <!-- Display Errors -->
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>app/controllers/SignupController.php?action=register" method="POST">
        <label for="username">Username:</label>
        <input type="text" placeholder="Enter a username" name="username" required>
        
        <label for="email">Email:</label>
        <input type="email" placeholder="Enter your email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" placeholder="Create password" name="password" required>
        
        <label for="c_password">Confirm Password:</label>
        <input type="password" placeholder="Confirm password" name="confirm_password" required>
        
        <input type="submit" class="btn" value="Register">
    </form>

    <p>Already have an account? <a href="app/controllers/LoginController.php">Login here</a></p>
</main>

<?php include VIEW_PATH . '/partials/footer.php'; ?>