<?php
session_start();
include 'includes/header.php';
?>

<div class="register-page" style="background-image: url('images/bgorange.jpeg');">
    <div class="register-container">
        <h2>Create an Account</h2>
        
        <form action="register.php" method="POST" class="register-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" class="btn">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'includes/db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<p style='color: red; text-align: center;'>Passwords do not match.</p>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            echo "<p style='color: red; text-align: center;'>Username is already taken.</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);
            echo "<p style='color: green; text-align: center;'>Registration successful! <a href='login.php'>Login now</a></p>";
        }
    }
}
include 'includes/footer.php';
?>
