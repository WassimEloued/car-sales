<?php 
session_start();
include 'includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Only admins can access this page.");
}

require_once 'includes/fpdf/fpdf.php';

// Fetch total statistics
$total_cars = $conn->query("SELECT COUNT(*) AS total FROM cars")->fetch()['total'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch()['total'];
$latest_car = $conn->query("SELECT name FROM cars ORDER BY id DESC LIMIT 1")->fetch()['name'];

// Fetch most bought car
$most_bought_car = $conn->query("
    SELECT cars.name, COUNT(achat.id_car) AS purchases 
    FROM achat 
    JOIN cars ON achat.id_car = cars.id 
    GROUP BY achat.id_car 
    ORDER BY purchases DESC 
    LIMIT 1
")->fetch();

// Fetch most expensive car
$most_expensive_car = $conn->query("
    SELECT name, price 
    FROM cars 
    ORDER BY price DESC 
    LIMIT 1
")->fetch();

// Fetch cheapest car
$cheapest_car = $conn->query("
    SELECT name, price 
    FROM cars 
    ORDER BY price ASC 
    LIMIT 1
")->fetch();

if (isset($_GET['download_pdf'])) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'Site Statistics', 0, 1, 'C');

    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(60, 10, 'Total Cars:', 1);
    $pdf->Cell(0, 10, $total_cars, 1, 1);

    $pdf->Cell(60, 10, 'Total Users:', 1);
    $pdf->Cell(0, 10, $total_users, 1, 1);

    $pdf->Cell(60, 10, 'Latest Car Added:', 1);
    $pdf->Cell(0, 10, $latest_car, 1, 1);

    if ($most_bought_car) {
        $pdf->Cell(60, 10, 'Most Bought Car:', 1);
        $pdf->Cell(0, 10, $most_bought_car['name'] . " (" . $most_bought_car['purchases'] . " sales)", 1, 1);
    }

    $pdf->Cell(60, 10, 'Most Expensive Car:', 1);
    $pdf->Cell(0, 10, $most_expensive_car['name'] . " ($" . number_format($most_expensive_car['price']) . ")", 1, 1);

    $pdf->Cell(60, 10, 'Cheapest Car:', 1);
    $pdf->Cell(0, 10, $cheapest_car['name'] . " ($" . number_format($cheapest_car['price']) . ")", 1, 1);

    $pdf->Output('D', 'site_statistics.pdf');
    exit;
}

?>

<div class="admin-stats">
    <h2>Site Statistics</h2>
    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Cars</h3>
            <p><?php echo $total_cars; ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>
        <div class="stat-box">
            <h3>Latest Car Added</h3>
            <p><?php echo htmlspecialchars($latest_car); ?></p>
        </div>
        <?php if ($most_bought_car): ?>
        <div class="stat-box">
            <h3>Most Bought Car</h3>
            <p><?php echo htmlspecialchars($most_bought_car['name']); ?> (<?php echo $most_bought_car['purchases']; ?> sales)</p>
        </div>
        <?php endif; ?>
        <div class="stat-box">
            <h3>Most Expensive Car</h3>
            <p><?php echo htmlspecialchars($most_expensive_car['name']); ?> ($<?php echo number_format($most_expensive_car['price']); ?>)</p>
        </div>
        <div class="stat-box">
            <h3>Cheapest Car</h3>
            <p><?php echo htmlspecialchars($cheapest_car['name']); ?> ($<?php echo number_format($cheapest_car['price']); ?>)</p>
        </div>
    </div>
    <div>
        <a href="?download_pdf=1" class="btn">Download as PDF</a>
    </div>
</div>

<style>
.admin-stats {
    padding: 30px;
    max-width: 800px;
    margin: 0 auto;
}

.stats-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.stat-box {
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    text-align: center;
    flex: 1;
    min-width: 250px;
}

.stat-box h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 10px;
}

.stat-box p {
    font-size: 1.2rem;
    color: #4CAF50;
}

.btn {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    text-decoration: none;
}

.btn:hover {
    background-color: #2980b9;
}
</style>
