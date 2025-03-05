<!-- app/views/auth/signup.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<h2>Sign Up</h2>

<!-- Display Errors -->
<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="<?= BASE_URL ?>app/controllers/SignupController.php?action=register" method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    
    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" required><br><br>
    
    <input type="submit" value="Register">
</form>

<p>Already have an account? <a href="<?= BASE_URL ?>app/controllers/LoginController.php">Login here</a></p>

<?php include VIEW_PATH . '/partials/footer.php'; ?>