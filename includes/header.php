<?php 
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Lane</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="flex-wrapper">
    <main id="main-content" 
        <?php if ($current_page == "index.php"): ?>
            style="background-image: url('images/bghd.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 20vh; padding: 5px; color: #fff;"
        <?php endif; ?>
        <?php if ($current_page == "cars.php"): ?>
            style="background-image: url('images/bgg.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 10vh; padding: 5px; color: #fff;"
        <?php endif; ?>
        <?php if ($current_page == "car-details.php"): ?>
            style="background-image: url('images/bghd1.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 10vh; padding: 5px; color: #fff;"
        <?php endif; ?>
        <?php if ($current_page == "login.php"): ?>
            style="background-image: url('images/bgb.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 10vh; padding: 5px; color: #fff;"
        <?php endif; ?>
        <?php if ($current_page == "register.php"): ?>
            style="background-image: url('images/bgo.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 10vh; padding: 5px; color: #fff;"
        <?php endif; ?>
        >
        <header>
            <div class="navbar">
                <h1><a href="index.php" style="color: silver; text-decoration: none;">Luxury Lane</a></h1>
                <nav>
                    <a href="index.php">Home</a>
                    <a href="cars.php">Cars</a>
                    
                    <!-- Admin-specific link -->
                        <a href="admin.php">Staff</a>

                    <!-- User session management -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="register.php">Register</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>
    </main>
</div>
</body>
</html>
