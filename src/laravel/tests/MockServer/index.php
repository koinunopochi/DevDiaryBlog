<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
  case '/normal':
    header('Content-Type: text/html');
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Normal Page Title</title>
    <meta property="og:title" content="Normal OG Title" />
    <meta property="og:image" content="https://example.com/normal-image.jpg" />
</head>
<body>
    <h1>Normal Page</h1>
</body>
</html>
HTML;
    break;

  case '/no-og-tags':
    header('Content-Type: text/html');
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Page Without OG Tags</title>
</head>
<body>
    <h1>Page Without OG Tags</h1>
</body>
</html>
HTML;
    break;

  case '/not-found':
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
    break;

  case '/server-error':
    header("HTTP/1.0 500 Internal Server Error");
    echo "500 Internal Server Error";
    break;

  default:
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
    break;
}
