<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "trzydenary_db", 3306);

if ($mysqli->connect_errno) {
    echo "❌ Błąd połączenia z MySQL: " . $mysqli->connect_error;
} else {
    echo "✅ Połączenie z MySQL działa!";
}
?>
