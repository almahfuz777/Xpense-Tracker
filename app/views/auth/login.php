<!-- app/views/auth/login.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<?php if (isset($message) && $message !== '') { echo "<p>$message</p>"; } ?>

<h2>Login</h2>

<form action="<?= BASE_URL ?>app/controllers/LoginController.php?action=authenticate" method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    
    <input type="submit" value="Login">
</form>

<!-- <p><a href="controllers/auth/forgot_password.php">Forgot Password?</a></p> -->
<p>Don't have an account? <a href="<?= BASE_URL ?>app/controllers/SignupController.php">Sign up here</a></p>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
