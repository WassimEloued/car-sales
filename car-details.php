<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';
?>
<div class="carsd-listing" style="background-image: url('images/bg1.jpg');">
    <?php
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $car = $stmt->fetch();

    if ($car) {
        echo "<div class='card-details'>";
        echo "<h2>" . htmlspecialchars($car['name']) . "</h2>";
        echo "<img src='images/" . htmlspecialchars($car['image']) . "' alt='" . htmlspecialchars($car['name']) . "' class='card-details-image'>";
        echo "<p class='card-price'>Price: $" . number_format(htmlspecialchars($car['price'])) . "</p>";
        echo "<p class='card-description'>" . htmlspecialchars($car['description']) . "</p>";
        echo "<a href='buy.php?id=" . htmlspecialchars($car['id']) . "' class='buy-button'>Buy Now</a>";
        echo "</div>";
    } else {
        echo "<p class='not-found'>Car not found.</p>";
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>
