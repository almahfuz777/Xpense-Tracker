<!-- app/views/user/profile.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<!-- Display Messages -->
<?php if (isset($_SESSION['alert-success'])) : ?>
    <div class="alert alert-success">
        <?= $_SESSION['alert-success']; ?>
        <?php unset($_SESSION['alert-success']); ?>
    </div>
<?php elseif (isset($_SESSION['alert-error'])) : ?>
    <div class="alert alert-error">
        <?= $_SESSION['alert-error']; ?>
        <?php unset($_SESSION['alert-error']); ?>
    </div>
<?php endif; ?>

<main class="profile-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>

    <section class="content-area">
        <h1>Your Profile</h1>

        <div class="profile-card">
            <!-- Profile Picture -->
            <div class="profile-pic-container">
                <img src="<?= BASE_URL ?>public/assets/images/uploads/<?= htmlspecialchars($_SESSION['profile_picture'] ?? 'default_profile.jpg'); ?>" alt="Profile Picture" class= "profile-picture">

                <form method="POST" enctype="multipart/form-data" action="app/controllers/ProfileController.php?action=uploadPicture">
                    <input type="file" name="profile_picture" accept="image/*" required>
                    <button type="submit" class="btn">Upload</button>
                </form>
            </div>
            
            <!-- Profile Information -->
            <div class="profile-info">
                <div class="fixed-fields">
                    <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username'] ?? 'Not set') ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($profile['email'] ?? 'Not set') ?></p>
                </div>

                <!-- Editable Info -->
                <?php
                    $editableFields = [
                        'fname' => 'First Name',
                        'lname' => 'Last Name',
                        'phone' => 'Phone',
                        'address' => 'Address',
                        'gender' => 'Gender',
                        'date_of_birth' => 'Date of Birth'
                    ];
                ?>

                <?php foreach ($editableFields as $field => $label): ?>
                    <div class="editable-field">
                        <strong><?= $label ?>:</strong>
                        <span class="field-text"><?= !empty($profile[$field]) ? htmlspecialchars($profile[$field]) : 'Not set' ?></span>
                        <?php if ($field === 'gender'): ?>
                            <select name="gender" class="field-input" style="display: none;">
                                <option value="">Select</option>
                                <option value="Male" <?= ($profile['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= ($profile['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other" <?= ($profile['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        <?php else: ?>
                            <input 
                                type="<?= $field === 'date_of_birth' ? 'date' : 'text' ?>" 
                                name="<?= $field ?>" 
                                class="field-input" 
                                value="<?= htmlspecialchars($profile[$field] ?? '') ?>" 
                                style="display: none;"
                            >
                        <?php endif; ?>
                        <button type="button" class="edit-btn">✎</button>
                        <button type="button" class="save-btn" style="display: none;">✔</button>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
</main>

<script src="<?= BASE_URL ?>public/assets/js/jquery-3.6.0.min.js"></script>
<script src="<?= BASE_URL ?>public/assets/js/profile.js"></script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
