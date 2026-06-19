<?php
$servername = "localhost";
$username = "root";
$password = ""; // update if you use a different MySQL password
$dbname = "week7db";

// Prefer mysqli when available, otherwise use PDO
if (class_exists('mysqli')) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} else {
    try {
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
        $conn = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Ensure necessary schema additions for security features and transactions
try {
    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        // create transactions table if missing
        $conn->query("CREATE TABLE IF NOT EXISTS transactions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            type ENUM('deposit','withdraw','transfer') NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            target_account VARCHAR(50),
            status ENUM('pending','completed','failed') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // add failed_login_attempts if missing
        $res = $conn->query("SELECT COUNT(*) AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='". $conn->real_escape_string($dbname) ."' AND TABLE_NAME='users' AND COLUMN_NAME='failed_login_attempts'");
        $row = $res->fetch_assoc();
        if ($row && (int)$row['c'] === 0) {
            $conn->query("ALTER TABLE users ADD COLUMN failed_login_attempts INT DEFAULT 0");
        }

        // add locked_until if missing
        $res = $conn->query("SELECT COUNT(*) AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='". $conn->real_escape_string($dbname) ."' AND TABLE_NAME='users' AND COLUMN_NAME='locked_until'");
        $row = $res->fetch_assoc();
        if ($row && (int)$row['c'] === 0) {
            $conn->query("ALTER TABLE users ADD COLUMN locked_until DATETIME NULL");
        }
    } else {
        // PDO path
        $conn->exec("CREATE TABLE IF NOT EXISTS transactions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            type ENUM('deposit','withdraw','transfer') NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            target_account VARCHAR(50),
            status ENUM('pending','completed','failed') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=:db AND TABLE_NAME='users' AND COLUMN_NAME='failed_login_attempts'");
        $stmt->execute([':db'=>$dbname]);
        $c = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($c && (int)$c['c'] === 0) {
            $conn->exec("ALTER TABLE users ADD COLUMN failed_login_attempts INT DEFAULT 0");
        }
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=:db AND TABLE_NAME='users' AND COLUMN_NAME='locked_until'");
        $stmt->execute([':db'=>$dbname]);
        $c = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($c && (int)$c['c'] === 0) {
            $conn->exec("ALTER TABLE users ADD COLUMN locked_until DATETIME NULL");
        }
    }
} catch (Exception $e) {
    // Non-fatal — ignore migration errors to avoid breaking runtime
}

// Optional: uncomment to debug successful connection
// echo "Connected to $dbname";

?>
