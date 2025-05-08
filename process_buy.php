<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $price = $_POST['price'];
    $buyer_id = $_SESSION['user_id'];

    // Check if the car is still in stock
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();

    if ($car) {
        // Insert into `achat` table
        $stmt = $conn->prepare("INSERT INTO achat (id_facture, price, id_buyer, id_car) VALUES (UUID(), ?, ?, ?)");
        $stmt->execute([$price, $buyer_id, $car_id]);

        // Redirect to a success page
        header("Location: success.php");
        exit;
    } else {
        echo "<p>Car is no longer available.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
