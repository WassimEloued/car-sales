<?php 
session_start();
include 'includes/header.php';
include 'includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Car</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header and Footer */
        header, footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
        }

        header a, footer a {
            color: #ff6600;
            text-decoration: none;
        }

        header a:hover, footer a:hover {
            text-decoration: underline;
        }

        /* Form Styling */
        .purchase-form {
            width: 100%;
            max-width: 600px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .purchase-form h2 {
            margin-bottom: 1rem;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        form label {
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        form button {
            padding: 0.75rem;
            font-size: 1rem;
            background-color: #ff6600;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form button:hover {
            background-color: #e65c00;
        }

        /* Success and Error Messages */
        .success-message {
            text-align: center;
            padding: 2rem;
            background: #e0ffe0;
            border: 1px solid #00b300;
            border-radius: 8px;
            margin: 2rem auto;
            max-width: 600px;
        }

        .error-message {
            color: red;
            margin-top: 1rem;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .purchase-form, .success-message {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
<?php
if (isset($_GET['id'])) {
    $car_id = $_GET['id'];

    // Fetch car details from the database
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();

    if ($car) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Form submission handling
            $buyer_name = trim($_POST['buyer_name']);
            $buyer_email = trim($_POST['buyer_email']);
            $buyer_phone = trim($_POST['buyer_phone']);
            $user_id = $_SESSION['user']; // Get logged-in user's ID
            $price = $car['price'];

            // Validate form inputs
            if (empty($buyer_name) || empty($buyer_email) || empty($buyer_phone)) {
                echo "<p class='error-message'>Error: All fields are required. Please fill out the form completely.</p>";
            } else {
                try {
                    // Insert into the `achat` table
                    $stmt = $conn->prepare("INSERT INTO achat (id_facture, price, id_buyer, id_car) VALUES (UUID(), ?, ?, ?)");
                    $stmt->execute([$price, $user_id, $car_id]);

                    // Success message
                    echo "<div class='success-message'>";
                    echo "<h2>Purchase Successful!</h2>";
                    echo "<p>Thank you, " . htmlspecialchars($buyer_name) . ", for purchasing the " . htmlspecialchars($car['name']) . ".</p>";
                    echo "<p>Total Price: $" . number_format($price) . "</p>";
                    echo "<a href='index.php' class='back-button'>Back to Home</a>";
                    echo "</div>";
                } catch (PDOException $e) {
                    echo "<p class='error-message'>Error: " . $e->getMessage() . "</p>";
                }
            }
        } else {
            // Display the purchase form
            ?>
            <div class="purchase-form">
                <h2>Buy <?= htmlspecialchars($car['name']); ?></h2>
                <form method="POST">
                    <div>
                        <label for="buyer_name">Your Name:</label>
                        <input type="text" name="buyer_name" id="buyer_name" required>
                    </div>
                    <div>
                        <label for="buyer_email">Your Email:</label>
                        <input type="email" name="buyer_email" id="buyer_email" required>
                    </div>
                    <div>
                        <label for="buyer_phone">Your Phone:</label>
                        <input type="number" name="buyer_phone" id="buyer_phone" required>
                    </div>
                    <button type="submit" class="buy-button">Confirm Purchase</button>
                </form>
            </div>
            <?php
        }
    } else {
        echo "<p class='error-message'>Car not found.</p>";
    }
} else {
    echo "<p class='error-message'>Invalid car ID.</p>";
}


?>
</body>
</html>
<?php
include 'includes/footer.php';

?>