<?php
/**
 * Redirect Page
 * Author: Rijad Hodzic
 * License: MIT
 */
session_start();
$slug = $_GET['u'] ?? '';
if (!preg_match('/^[a-zA-Z0-9]+$/', $slug)) {
    http_response_code(404);
    echo "Invalid slug.";
    exit;
}

$file = "json/$slug.json";
if (!file_exists($file)) {
    http_response_code(404);
    echo "Link not found.";
    exit;
}

$data = json_decode(file_get_contents($file), true);
if ($data['expires'] && time() > $data['expires']) {
    unlink($file);
    http_response_code(410);
    echo "Link expired.";
    exit;
}

if (!isset($_SESSION['access_granted'])) $_SESSION['access_granted'] = [];

if ($data['password']) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['login_attempts'][$slug])) $_SESSION['login_attempts'][$slug] = 0;
        if ($_SESSION['login_attempts'][$slug] > 5) {
            die("Too many failed attempts.");
        }

        $pass = $_POST['password'] ?? '';
        if (password_verify($pass, $data['password'])) {
            $_SESSION['access_granted'][$slug] = true;
        } else {
            $_SESSION['login_attempts'][$slug]++;
            echo "<div class='alert alert-danger'>Incorrect password.</div>";
        }
    }

    if (empty($_SESSION['access_granted'][$slug])) {
        echo '<!DOCTYPE html><html><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-dark text-white"><div class="container py-5"><h3>This link is password protected</h3><form method="post"><input type="password" name="password" class="form-control mb-3"><button class="btn btn-warning">Submit</button></form></div>
<footer class="text-center mt-5 text-secondary">
    <p><a href="https://backlinkexchange.org" class="text-secondary" target="_blank">Backlink Exchange</a></p>
</footer>
</body>
</html>';
        exit;
    }
}

if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit('Invalid redirect URL.');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        let countdown = 10;
        const url = <?php
/**
 * Redirect Page
 * Author: Rijad Hodzic
 * License: MIT
 */ echo json_encode($data['url']); ?>;
        function updateCounter() {
            document.getElementById('counter').innerText = countdown;
            if (countdown <= 0) {
                window.location.href = url;
            } else {
                countdown--;
                setTimeout(updateCounter, 1000);
            }
        }
        window.onload = updateCounter;
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #fff;
        }
        .ad-box {
            background: #1e1e1e;
            border: 1px solid #333;
            padding: 1rem;
            margin: 2rem auto;
            max-width: 600px;
            text-align: center;
        }
    </style>
</head>
<body class="text-center">
<div class="container py-5">
    <h1>Redirecting in <span id="counter">10</span> seconds...</h1>

    <!-- Advertising Code Placeholder -->
    <div class="ad-box">
        <!-- INSERT YOUR AD CODE BELOW -->
        <p>Advertisement</p>
        <script>
        // Example ad script
        // Replace with actual ad network code
        </script>
        <!-- END AD CODE -->
    </div>

</div>

<footer class="text-center mt-5 text-secondary">
    <p><a href="https://backlinkexchange.org" class="text-secondary" target="_blank">Backlink Exchange</a></p>
</footer>
</body>

</html>
