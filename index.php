<?php
include('connect.php');

// Database connection settings
$host = 'localhost';       // Change if necessary
$user = 'root';            // Your MySQL username
$password = '';            // Your MySQL password
$dbname = 'MeleeTools'; // Replace with your actual database name

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch players data
$players_sql = "SELECT id, name FROM Players";
$players_result = $conn->query($players_sql);

// Fetch tournaments data
$tournament_sql = "SELECT id, name, type FROM tournament";
$tournament_result = $conn->query($tournament_sql);

// Fetch standings data (accumulated points, sorted)
$standings_sql = "
    SELECT p.name AS player_name, t.name AS tournament_name, s.points
    FROM standings s
    JOIN Players p ON s.player = p.id
    JOIN tournament t ON s.tournament = t.id
    ORDER BY s.points DESC
";
$standings_result = $conn->query($standings_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Players and Tournaments List</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<h2>MeoWeekly Rankings</h2>

<div class="container">

    <!-- Players Table -->
    <div>
        <h3>Players</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>

            <?php
            if ($players_result->num_rows > 0) {
                while($row = $players_result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No players found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Tournaments Table -->
    <div>
        <h3>Tournaments</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
            </tr>

            <?php
            if ($tournament_result->num_rows > 0) {
                while($row = $tournament_result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['type']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No tournaments found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Standings Table (Accumulated points sorted by highest points) -->
    <div>
        <h3>Standings</h3>
        <table>
            <tr>
                <th>Player</th>
                <th>Tournament</th>
                <th>Points</th>
            </tr>

            <?php
            if ($standings_result->num_rows > 0) {
                while($row = $standings_result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['player_name']) . "</td>
                            <td>" . htmlspecialchars($row['tournament_name']) . "</td>
                            <td>" . htmlspecialchars($row['points']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No standings found.</td></tr>";
            }
            ?>
        </table>
    </div>

</div>

</body>
</html>

<?php
// Close the connection
$conn->close();
