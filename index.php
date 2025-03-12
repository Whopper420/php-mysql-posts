<?php
$dsn = "mysql:host=localhost;dbname=blog_12032025;charset=utf8mb4";
$username = "user_12032025";
$password = "password";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $stmt = $pdo->query("SELECT id, title, content, author, created_at FROM posts");

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
            echo "<p>" . nl2br(htmlspecialchars($row["content"])) . "</p>";
            echo "<p><i>Autors: " . htmlspecialchars($row["author"]) . " | " . $row["created_at"] . "</i></p>";

            // Fetch comments for the current post
            $commentStmt = $pdo->prepare("SELECT author, content, created_at FROM comments WHERE post_id = ?");
            $commentStmt->execute([$row['id']]);
            $comments = $commentStmt->fetchAll();

            if ($comments) {
                echo "<h4>Komentāri:</h4><ul>";
                foreach ($comments as $comment) {
                    echo "<li><strong>" . htmlspecialchars($comment["author"]) . ":</strong> " . nl2br(htmlspecialchars($comment["content"])) .
                        " <i>(" . $comment["created_at"] . ")</i></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Nav komentāru.</p>";
            }

            echo "<hr>";
        }
    } else {
        echo "Nav ierakstu.";
    }
} catch (PDOException $e) {
    die("Savienojuma kļūda: " . $e->getMessage());
}
?>

}