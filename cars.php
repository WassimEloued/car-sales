<?php
session_start();
include 'includes/header.php';
?>
<div class="cars-listing" style="background-image: url('images/bggrey.jpg');">
    <h2 style="color: silver;text-align: center;">Available Cars</h2>
    <div class="car-list">
        <?php
        include 'includes/db.php';
        $stmt = $conn->query("SELECT * FROM cars");

        while ($car = $stmt->fetch()) {
            echo "<div class='car-card'>";
            echo "<h3>" . htmlspecialchars($car['name']) . "</h3>";
            echo "<img src='images/" . htmlspecialchars($car['image']) . "' alt='" . htmlspecialchars($car['name']) . "' class='car-image'>";
            echo "<p>Price: $" . number_format(htmlspecialchars($car['price'])) . "</p>";
            echo "<a href='car-details.php?id=" . htmlspecialchars($car['id']) . "' class='details-link'>More Details</a>";
            echo "</div>";
        }
        ?>
    </div>

</div>
<?php include 'includes/footer.php'; ?>

