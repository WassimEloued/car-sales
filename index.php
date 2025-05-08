<?php
session_start();
include 'includes/header.php';
?>
<div class="home" style="background-image: url('images/background.jpeg'); background-size: cover; background-position: center;">
    <div class="overlay">
        <h1 style="color: silver">Welcome to Our Luxury Lane</h1>
        <p style="color: silver">Find your dream car from our premium selection.</p>
        
        
    </div>
</div>

    <section class="featured-cars">
        <h2>Featured Cars</h2>
        <div class="car-gallery">
            <?php
            include 'includes/db.php';
            $stmt = $conn->query("SELECT * FROM cars LIMIT 6"); 

            while ($car = $stmt->fetch()) {
                echo "<div class='car-item'>";
                echo "<img src='images/" . htmlspecialchars($car['image']) . "' alt='" . htmlspecialchars($car['name']) . "'>";
                echo "<h3>" . htmlspecialchars($car['name']) . "</h3>";
                echo "<p>Price: $" . htmlspecialchars($car['price']) . "</p>";
                echo "<a href='car-details.php?id=" . $car['id'] . "' class='btn'>View Details</a>";
                echo "</div>";
            }
            ?>
        </div>
    </section>
<?php include 'includes/footer.php'; ?>
