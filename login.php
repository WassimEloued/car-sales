<?php
session_start();
include 'includes/header.php';
?>

<div class="login-page" style="background-image: url('images/bgblue.jpeg');">
    <div class="login-container">
        <h2>Login to Your Account</h2>
        
        <form action="login.php" method="POST" class="login-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'includes/db.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role']; 

        if ($user['role'] === 'admin') {
            header('Location: admin.php'); 
        } else {
            header('Location: index.php'); 
        }
        exit;
    } else {
        echo "<p style='color: red; text-align: center;'>Invalid login credentials</p>";
    }
}
include 'includes/footer.php';
?>
