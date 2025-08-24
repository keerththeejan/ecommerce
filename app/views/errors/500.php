<?php
http_response_code(500);
$title = isset($title) ? $title : 'Server Error';
$message = isset($message) ? $message : 'An unexpected error occurred. Please try again later.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>500 - <?= htmlspecialchars($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
        .wrap { max-width: 680px; margin: auto; }
        h1 { color: #e67e22; }
        pre { background: #f7f7f7; padding: 12px; overflow: auto; }
        a { color: #2c3e50; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>500 - Internal Server Error</h1>
        <p><?= htmlspecialchars($message) ?></p>
        <p><a href="/ecommerce/">Go back to Home</a></p>
    </div>
</body>
</html>
