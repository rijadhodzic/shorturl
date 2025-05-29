<?php
/**
 * Secure Short URL Generator
 * Author: Rijad Hodzic
 * License: MIT
 * Description: Lightweight PHP URL shortener with password, expiry, and ad support.
 * GitHub: https://github.com/rijadhodzic/shorturl
 */
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

function generateSlug($length = 6) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

$shortUrl = $htmlCode = $forumLink = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['token'], $_POST['csrf_token'] ?? '')) {
        die("<div class='alert alert-danger'>Invalid CSRF token.</div>");
    }

    $url = filter_var($_POST['url'], FILTER_VALIDATE_URL);
    $password = $_POST['password'] ?? '';
    if (strlen($password) > 255) die("Password too long.");
    $expires = intval($_POST['expires']);
    $expires = ($expires > 0 && $expires <= 31) ? time() + ($expires * 86400) : null;

    if ($url) {
        if (!is_dir("json")) mkdir("json", 0755, true);
        $slug = generateSlug();
        while (file_exists("json/$slug.json")) {
            $slug = generateSlug();
        }

        $data = [
            'url' => $url,
            'expires' => $expires,
            'password' => $password ? password_hash($password, PASSWORD_DEFAULT) : null
        ];
        file_put_contents("json/$slug.json", json_encode($data, JSON_PRETTY_PRINT));
        $shortUrl = htmlspecialchars($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/redirect.php?u=$slug");
        $htmlCode = '&lt;a href="' . $shortUrl . '"&gt;' . $shortUrl . '&lt;/a&gt;';
        $forumLink = '[url=' . $shortUrl . ']' . $shortUrl . '[/url]';
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid URL.</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Secure Short URL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #fff;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            margin-top: 5vh;
        }
        .output-box {
            background: #1f1f1f;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 20px;
        }
        input.form-control, button.btn {
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h1 class="mb-4 text-center">Create a Secure Short URL</h1>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['token']; ?>">
            <div class="mb-3">
                <label class="form-label">Destination URL</label>
                <input type="url" name="url" required class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Password (optional)</label>
                <input type="text" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Expiration (days)</label>
                <input type="number" name="expires" min="1" max="31" value="7" class="form-control">
            </div>
            <button class="btn btn-warning w-100">Generate Short URL</button>
        </form>

        <?php if ($shortUrl): ?>
        <div class="output-box mt-4">
            <h5 class="text-success">Your Short URL</h5>
            <p><strong>Direct:</strong> <a href="<?= $shortUrl ?>" class="text-info" target="_blank"><?= $shortUrl ?></a></p>
            <p><strong>HTML:</strong><br><code><?= $htmlCode ?></code></p>
            <p><strong>Forum:</strong><br><code><?= $forumLink ?></code></p>
        </div>
        <?php endif; ?>

        <footer class="mt-5 text-center">
            <p><a href="https://backlinkexchange.org" class="text-secondary" target="_blank">Backlink Exchange</a></p>
        </footer>
    </div>
</div>
</body>
</html>
