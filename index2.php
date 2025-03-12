<?php
$dsn = "mysql:host=localhost;dbname=blog_12032025;charset=utf8mb4";
$username = "user_12032025";
$password = "password";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $stmt = $pdo->query("SELECT p.id, p.title, p.content, p.author, p.created_at, c.author
    AS comment_author, c.content AS comment_content, c.created_at AS comment_created_at
    FROM posts p LEFT JOIN comments c ON p.id = c.post_id");

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
            echo "<p>" . nl2br(htmlspecialchars($row["content"])) . "</p>";
            echo "<p><i>Author: " . htmlspecialchars($row["author"]) . " | " . $row["created_at"] . "</i></p>";

            // Display comments if available
            if ($row['comment_content']) {
                echo "<div><strong>Comment by " . htmlspecialchars($row["comment_author"]) . ":</strong><br>";
                echo nl2br(htmlspecialchars($row["comment_content"])) . "<br>";
                echo "<i>Posted on: " . $row["comment_created_at"] . "</i></div><hr>";
            }
        }
    } else {
        echo "No posts found.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $pdo = null; // Close connection
}
