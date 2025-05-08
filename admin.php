<?php  
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "
<div style='
        background-image: url(\"images/acd.jpg\");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: blue;
        text-align: center;
    '>
    <div>
        <h1>Only Admins can access this page.</h1>
    </div>
</div>";
    exit;
}

if (isset($_GET['delete'])) {
    $car_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
    if ($stmt->execute([$car_id])) {
        echo "<p style='color:green;'>Car deleted successfully!</p>";
    } else {
        echo "<p style='color:red;'>Failed to delete car.</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = basename($_FILES['image']['name']);
        $target_dir = "images/";
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            if (isset($_POST['car_id']) && !empty($_POST['car_id'])) {
                $car_id = $_POST['car_id'];
                $stmt = $conn->prepare("UPDATE cars SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $price, $description, $image, $car_id]);
                echo "<p style='color:green;'>Car updated successfully!</p>";
            } else {
                $stmt = $conn->prepare("INSERT INTO cars (name, price, description, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $price, $description, $image]);
                echo "<p style='color:green;'>Car added successfully!</p>";
            }
        } else {
            echo "<p style='color:red;'>Failed to upload image.</p>";
        }
    } else {
        echo "<p style='color:red;'>Please upload an image.</p>";
    }
}
?>

<div class="admin-cars">
    <h2>Admin Panel - Manage Cars</h2>
    <div class="admin-panel-actions">
    <button onclick="window.location.href='site_statistics.php'" class="btn">View Site Statistics</button>
    </div>
    <div class="add-car">
        <h3>Add or Update a Car</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="car_id" id="car_id">

            <label for="name">Car Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image" id="image" accept="image/*">

            <button type="submit" class="btn">Submit</button>
        </form>
    </div>

    <div class="car-list">
        <h3>Car List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM cars");
                while ($car = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($car['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($car['name']) . "</td>";
                    echo "<td>$" . htmlspecialchars($car['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($car['description']) . "</td>";
                    echo "<td><img src='images/" . htmlspecialchars($car['image']) . "' alt='" . htmlspecialchars($car['name']) . "' style='width:100px;'></td>";
                    echo "<td>
                        <a href='?delete=" . $car['id'] . "' onclick='return confirm(\"Are you sure you want to delete this car?\")'>Delete</a> |
                        <a href='#' onclick='editCar(" . $car['id'] . ", `" . $car['name'] . "`, " . $car['price'] . ", `" . $car['description'] . "`)'>Edit</a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editCar(id, name, price, description) {
    document.getElementById('car_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('price').value = price;
    document.getElementById('description').value = description;
}
</script>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

header {
    background-color: #333;
    color: #fff;
    padding: 10px 0;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 2rem;
}

header nav a {
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    margin: 0 10px;
}

header nav a:hover {
    background-color: #575757;
    border-radius: 5px;
}

.admin-cars {
    padding: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.admin-cars h2 {
    font-size: 2rem;
    color: #333;
    text-align: center;
}

.add-car, .car-list {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.add-car form {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
}

.add-car input, .add-car textarea, .add-car button {
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.add-car input[type="text"], .add-car input[type="number"], .add-car textarea {
    width: 100%;
}

.add-car button {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
}

.add-car button:hover {
    background-color: #45a049;
}

/* Table Styles */
.car-list table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.car-list th, .car-list td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.car-list th {
    background-color: #f1f1f1;
}

.car-list tr:nth-child(even) {
    background-color: #fafafa;
}

.car-list td img {
    max-width: 100px;
    height: auto;
    border-radius: 5px;
}

.car-list td a {
    color: #3498db;
    text-decoration: none;
    margin: 0 5px;
}

.car-list td a:hover {
    text-decoration: underline;
}

.car-list td a:active {
    color: #e74c3c;
}
.admin-panel-actions .btn {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.admin-panel-actions .btn:hover {
    background-color: #2980b9;
}

@media (max-width: 768px) {
    .add-car form {
        grid-template-columns: 1fr;
    }
    
    .admin-cars {
        padding: 15px;
    }
}

</style>
<?php include 'includes/footer.php'; ?>
