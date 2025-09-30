<?php
http_response_code(404);
$title = isset($title) ? $title : 'Page Not Found';
$message = isset($message) ? $message : 'The page you are looking for could not be found.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 - <?= htmlspecialchars($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
        .wrap { max-width: 680px; margin: auto; }
        h1 { color: #c0392b; }
        a { color: #2c3e50; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>404 - Not Found</h1>
        <p><?= htmlspecialchars($message) ?></p>
        <p><a href="/ecommerce/">Go back to Home</a></p>
    </div>
</body>
</html>
