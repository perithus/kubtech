<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

if (kubtech_is_admin()) {
    header('Location: cms.php');
    exit;
}

$error = '';

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (hash_equals(KUBTECH_ADMIN_USER, $username) && hash_equals(KUBTECH_ADMIN_PASS, $password)) {
        $_SESSION['kubtech_admin'] = true;
        kubtech_csrf_token();
        header('Location: cms.php');
        exit;
    }

    $error = 'Niepoprawny login lub haslo.';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logowanie admina | KUB-TECH</title>
  <meta name="robots" content="noindex,nofollow">
  <style>
    :root {
      --bg: #f7f0e7;
      --card: #fffaf4;
      --text: #211c18;
      --muted: #6f645b;
      --line: rgba(86, 57, 31, 0.14);
      --accent: #9b6a42;
      --accent-dark: #5f3e24;
      --danger: #9b443e;
      --shadow: 0 24px 60px rgba(53, 35, 19, 0.12);
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 1.5rem;
      font-family: system-ui, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at top left, rgba(214, 180, 145, 0.36), transparent 22%),
        linear-gradient(180deg, #fffaf4 0%, #f2e8db 100%);
    }

    .card {
      width: min(100%, 460px);
      padding: 2rem;
      border: 1px solid var(--line);
      border-radius: 28px;
      background: rgba(255, 250, 244, 0.96);
      box-shadow: var(--shadow);
    }

    h1 {
      margin: 0 0 0.8rem;
      font-size: clamp(2rem, 5vw, 2.8rem);
      line-height: 1;
    }

    p {
      margin: 0 0 1.5rem;
      color: var(--muted);
      line-height: 1.7;
    }

    label {
      display: block;
      margin-bottom: 0.45rem;
      font-size: 0.92rem;
      font-weight: 700;
    }

    input {
      width: 100%;
      min-height: 52px;
      margin-bottom: 1rem;
      padding: 0.9rem 1rem;
      border: 1px solid var(--line);
      border-radius: 16px;
      font: inherit;
      background: #fff;
    }

    button, a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-height: 52px;
      border-radius: 999px;
      font: inherit;
      text-decoration: none;
      cursor: pointer;
    }

    button {
      width: 100%;
      border: 0;
      background: var(--accent-dark);
      color: #fff;
      font-weight: 700;
    }

    .back {
      margin-top: 1rem;
      color: var(--accent-dark);
      font-weight: 600;
    }

    .error {
      margin-bottom: 1rem;
      padding: 0.9rem 1rem;
      border-radius: 16px;
      background: rgba(155, 68, 62, 0.12);
      color: var(--danger);
    }
  </style>
</head>
<body>
  <main class="card">
    <h1>Panel admina</h1>
    <p>Logowanie chroni CMS przed dostepem osob postronnych. Dane logowania mozesz zmienic przez zmienne srodowiskowe serwera.</p>
    <?php if ($error !== ''): ?>
      <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form method="post" action="admin-login.php">
      <label for="username">Login</label>
      <input id="username" name="username" type="text" autocomplete="username" required>
      <label for="password">Haslo</label>
      <input id="password" name="password" type="password" autocomplete="current-password" required>
      <button type="submit">Zaloguj do CMS</button>
    </form>
    <a class="back" href="kubtech_meble_landing.html">Wroc na strone</a>
  </main>
</body>
</html>
