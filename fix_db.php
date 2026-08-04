<?php
$conn = new mysqli("localhost", "root", "", "food_recipe_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "ALTER TABLE bookmarks DROP FOREIGN KEY bookmarks_ibfk_2";
if ($conn->query($sql) === TRUE) {
    echo "Foreign key dropped successfully.\n";
} else {
    echo "Error dropping foreign key: " . $conn->error . "\n";
}
$conn->close();
?>
