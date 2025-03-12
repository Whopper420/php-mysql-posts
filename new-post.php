<?php

$servername = "localhost";
$username = "user_12032025";
$password = "password";
$dbname = "blog_12032025";


$title = $content = $author = "";
$titleErr = $contentErr = $authorErr = "";
$successMessage = "";
$errorMessage = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["title"])) {
        $titleErr = "Virsraksts ir obligāts";
    } else {
        $title = test_input($_POST["title"]);
    }


    if (empty($_POST["content"])) {
        $contentErr = "Saturs ir obligāts";
    } else {
        $content = test_input($_POST["content"]);
    }


    if (empty($_POST["author"])) {
        $authorErr = "Autors ir obligāts";
    } else {
        $author = test_input($_POST["author"]);
    }


    if (empty($titleErr) && empty($contentErr) && empty($authorErr)) {
        try {

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $stmt = $conn->prepare("INSERT INTO posts (title, content, author) VALUES (:title, :content, :author)");


            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':author', $author);


            $stmt->execute();

            $successMessage = "Raksts veiksmīgi pievienots!";


            $title = $content = $author = "";
        } catch (PDOException $e) {
            $errorMessage = "Kļūda: " . $e->getMessage();
        }


        $conn = null;
    }
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jauns raksts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 200px;
            resize: vertical;
        }

        .error {
            color: #d9534f;
            font-size: 14px;
            margin-top: 5px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
        }

        button:hover {
            background-color: #4cae4c;
        }

        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border-left: 5px solid #5cb85c;
        }

        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border-left: 5px solid #d9534f;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Izveidot jaunu rakstu</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="success-message">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="title">Virsraksts:</label>
                <input type="text" id="title" name="title" value="<?php echo $title; ?>">
                <?php if (!empty($titleErr)): ?>
                    <div class="error"><?php echo $titleErr; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="content">Saturs:</label>
                <textarea id="content" name="content"><?php echo $content; ?></textarea>
                <?php if (!empty($contentErr)): ?>
                    <div class="error"><?php echo $contentErr; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="author">Autors:</label>
                <input type="text" id="author" name="author" value="<?php echo $author; ?>">
                <?php if (!empty($authorErr)): ?>
                    <div class="error"><?php echo $authorErr; ?></div>
                <?php endif; ?>
            </div>

            <button type="submit">Saglabāt rakstu</button>
        </form>
    </div>
</body>

</html>