<?php
/**
 * Shared error page renderer (no DB / session deps — safe for 500).
 *
 * @param int    $code
 * @param string $title
 * @param string $message
 * @param string $hint
 */
function smks3_render_error_page(int $code, string $title, string $message, string $hint = ''): void
{
    if (!headers_sent()) {
        http_response_code($code);
        header('Content-Type: text/html; charset=UTF-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('X-Robots-Tag: noindex');
    }

    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $base = rtrim(dirname(dirname($scriptName)), '/\\');
    if ($base === '' || $base === '.' || $base === '/') {
        $base = '';
    }
    $home = ($base === '' ? '' : $base) . '/';
    $favicon = ($base === '' ? '' : $base) . '/images/favicon-smks3.ico';

    $codeEsc = htmlspecialchars((string) $code, ENT_QUOTES, 'UTF-8');
    $titleEsc = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $messageEsc = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $hintEsc = htmlspecialchars($hint, ENT_QUOTES, 'UTF-8');
    $homeEsc = htmlspecialchars($home, ENT_QUOTES, 'UTF-8');
    $faviconEsc = htmlspecialchars($favicon, ENT_QUOTES, 'UTF-8');
    ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= $codeEsc ?> — <?= $titleEsc ?> | SMK Seremban 3</title>
    <link rel="icon" href="<?= $faviconEsc ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --school-primary: #0B3C5D;
            --school-primary-dark: #082a42;
            --school-accent: #1a6fa8;
            --school-pastel: #f4f8fc;
            --school-border: #dce8f2;
            --motion-ease: cubic-bezier(0.22, 1, 0.36, 1);
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1e293b;
            background-color: var(--school-pastel);
            background-image:
                radial-gradient(rgba(11, 60, 93, 0.06) 0.9px, transparent 0.9px),
                linear-gradient(165deg, #f7fafc 0%, #e8f1f8 48%, #d9e8f4 100%);
            background-size: 22px 22px, auto;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100%;
        }
        .error-main {
            width: 100%;
            padding: 2.5rem 1.25rem;
        }
        .error-card {
            width: min(560px, 100%);
            margin: 0 auto;
            text-align: center;
            animation: errorIn 0.55s var(--motion-ease) both;
        }
        @keyframes errorIn {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .error-code {
            font-size: clamp(4.5rem, 16vw, 7rem);
            font-weight: 700;
            line-height: 0.95;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, var(--school-primary) 0%, var(--school-accent) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 0 0 0.35rem;
        }
        .error-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.25rem;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: rgba(11, 60, 93, 0.08);
            color: var(--school-primary);
            font-size: 1.65rem;
        }
        .error-card h1 {
            margin: 0 0 0.75rem;
            font-size: clamp(1.35rem, 3.5vw, 1.75rem);
            font-weight: 700;
            color: var(--school-primary-dark);
            letter-spacing: -0.02em;
        }
        .error-card p {
            margin: 0 auto 0.5rem;
            max-width: 38ch;
            color: #475569;
            font-size: 1.02rem;
            line-height: 1.65;
        }
        .error-card .hint {
            font-size: 0.92rem;
            color: #64748b;
            margin-bottom: 1.75rem;
        }
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            justify-content: center;
        }
        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.7rem 1.2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: transform 0.2s var(--motion-ease), background 0.2s ease, box-shadow 0.2s ease;
        }
        .error-btn:hover { transform: translateY(-2px); }
        .error-btn--primary {
            background: var(--school-primary);
            color: #fff;
            box-shadow: 0 8px 20px rgba(11, 60, 93, 0.22);
        }
        .error-btn--primary:hover {
            background: var(--school-primary-dark);
            color: #fff;
        }
        .error-btn--ghost {
            background: #fff;
            color: var(--school-primary);
            border: 1px solid var(--school-border);
        }
        .error-btn--ghost:hover {
            background: #f8fafc;
            color: var(--school-primary-dark);
        }
        @media (prefers-reduced-motion: reduce) {
            .error-card { animation: none; }
            .error-btn:hover { transform: none; }
        }
    </style>
</head>
<body>
    <main class="error-main">
        <div class="error-card">
            <div class="error-icon" aria-hidden="true">
                <?php if ($code === 404) : ?>
                    <i class="bi bi-compass"></i>
                <?php elseif ($code === 403) : ?>
                    <i class="bi bi-shield-lock"></i>
                <?php elseif ($code === 401) : ?>
                    <i class="bi bi-person-lock"></i>
                <?php elseif ($code === 503) : ?>
                    <i class="bi bi-tools"></i>
                <?php else : ?>
                    <i class="bi bi-exclamation-triangle"></i>
                <?php endif; ?>
            </div>
            <p class="error-code" aria-hidden="true"><?= $codeEsc ?></p>
            <h1><?= $titleEsc ?></h1>
            <?php if ($messageEsc !== '') : ?>
                <p><?= $messageEsc ?></p>
            <?php endif; ?>
            <?php if ($hintEsc !== '') : ?>
                <p class="hint"><?= $hintEsc ?></p>
            <?php endif; ?>
            <div class="error-actions">
                <a class="error-btn error-btn--primary" href="<?= $homeEsc ?>">
                    <i class="bi bi-house-door"></i> Laman Utama
                </a>
                <a class="error-btn error-btn--ghost" href="javascript:history.back()">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </main>
</body>
</html>
    <?php
}
