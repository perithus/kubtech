<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

date_default_timezone_set('Europe/Warsaw');

define('KUBTECH_DATA_FILE', __DIR__ . '/../data/projects.json');
define('KUBTECH_PUBLIC_BASE_URL', rtrim((string) (getenv('KUBTECH_SITE_URL') ?: 'https://kubtech-meble.pl'), '/'));
define('KUBTECH_ADMIN_USER', (string) (getenv('KUBTECH_ADMIN_USER') ?: 'admin'));
define('KUBTECH_ADMIN_PASS', (string) (getenv('KUBTECH_ADMIN_PASS') ?: 'ZmienToHaslo!2026'));
define('KUBTECH_CONTACT_EMAIL', (string) (getenv('KUBTECH_CONTACT_EMAIL') ?: 'kontakt@kubtech-meble.pl'));
define('KUBTECH_PHONE', (string) (getenv('KUBTECH_PHONE') ?: '+48723998100'));

function kubtech_default_projects(): array
{
    return [
        [
            'id' => 'default-kuchnia',
            'category' => 'Kuchnia',
            'location' => 'Leczyca',
            'title' => 'Nowoczesna kuchnia dla domu pod Leczyca',
            'description' => 'Jasna zabudowa kuchenna wykonana pod konkretny uklad pomieszczenia, z wygodna strefa robocza, miejscem do przechowywania i spojnym wykonczeniem frontow.',
            'tags' => ['Debowe akcenty', 'Zabudowa AGD', 'Na wymiar'],
            'createdAt' => '2026-05-04T17:30:00.000Z',
            'updatedAt' => '2026-05-04T17:30:00.000Z',
            'images' => [
                [
                    'id' => 'default-kuchnia-1',
                    'src' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1400&q=80',
                    'alt' => 'Nowoczesna kuchnia na wymiar z drewnianymi frontami',
                    'label' => 'Widok glowny',
                ],
                [
                    'id' => 'default-kuchnia-2',
                    'src' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80',
                    'alt' => 'Kuchnia na wymiar z wyspa i zabudowa robocza',
                    'label' => 'Strefa robocza',
                ],
                [
                    'id' => 'default-kuchnia-3',
                    'src' => 'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=900&q=80',
                    'alt' => 'Detal zabudowy kuchennej i systemow przechowywania',
                    'label' => 'Detal zabudowy',
                ],
                [
                    'id' => 'default-kuchnia-4',
                    'src' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=900&q=80',
                    'alt' => 'Blat i strefa przygotowywania w kuchni na wymiar',
                    'label' => 'Blat',
                ],
            ],
        ],
        [
            'id' => 'default-lazienka',
            'category' => 'Lazienka',
            'location' => 'Zgierz',
            'title' => 'Zabudowa lazienkowa z miejscem do przechowywania',
            'description' => 'Zabudowa lazienkowa zaprojektowana z mysla o codziennym komforcie, odpornosci na wilgoc i estetycznym ukryciu najpotrzebniejszych rzeczy.',
            'tags' => ['Wilgocioodporna plyta', 'Spojne wykonczenie', 'Przechowywanie'],
            'createdAt' => '2026-05-04T17:31:00.000Z',
            'updatedAt' => '2026-05-04T17:31:00.000Z',
            'images' => [
                [
                    'id' => 'default-lazienka-1',
                    'src' => 'https://images.unsplash.com/photo-1620626011761-996317b8d101?auto=format&fit=crop&w=1400&q=80',
                    'alt' => 'Meble lazienkowe na wymiar z miejscem do przechowywania',
                    'label' => 'Widok glowny',
                ],
                [
                    'id' => 'default-lazienka-2',
                    'src' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=900&q=80',
                    'alt' => 'Detal frontow i zabudowy lazienkowej',
                    'label' => 'Detal frontow',
                ],
                [
                    'id' => 'default-lazienka-3',
                    'src' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=900&q=80',
                    'alt' => 'Szafka lazienkowa na wymiar pod umywalke',
                    'label' => 'Szafka pod umywalke',
                ],
            ],
        ],
        [
            'id' => 'default-garderoba',
            'category' => 'Garderoba',
            'location' => 'Lodz',
            'title' => 'Garderoba pod skos z wykorzystaniem calej wysokosci',
            'description' => 'Projekt wykonany pod trudniejsza geometrie poddasza, z przegrodami, szufladami i strefa na dluzsze ubrania.',
            'tags' => ['Pod skosem', 'Szuflady', 'Pelna wysokosc'],
            'createdAt' => '2026-05-04T17:32:00.000Z',
            'updatedAt' => '2026-05-04T17:32:00.000Z',
            'images' => [
                [
                    'id' => 'default-garderoba-1',
                    'src' => 'https://images.unsplash.com/photo-1556020685-ae41abfc9365?auto=format&fit=crop&w=1400&q=80',
                    'alt' => 'Garderoba na wymiar z szufladami i oswietleniem',
                    'label' => 'Widok glowny',
                ],
                [
                    'id' => 'default-garderoba-2',
                    'src' => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=900&q=80',
                    'alt' => 'Wnetrze garderoby z podzialem na strefy przechowywania',
                    'label' => 'Wnetrze zabudowy',
                ],
                [
                    'id' => 'default-garderoba-3',
                    'src' => 'https://images.unsplash.com/photo-1560185008-b033106af5c3?auto=format&fit=crop&w=900&q=80',
                    'alt' => 'System szuflad w garderobie na wymiar',
                    'label' => 'System szuflad',
                ],
            ],
        ],
    ];
}

function kubtech_ensure_data_file(): void
{
    $directory = dirname(KUBTECH_DATA_FILE);
    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }

    if (!is_file(KUBTECH_DATA_FILE)) {
        file_put_contents(
            KUBTECH_DATA_FILE,
            json_encode(kubtech_default_projects(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
    }
}

function kubtech_read_projects(): array
{
    kubtech_ensure_data_file();
    $raw = file_get_contents(KUBTECH_DATA_FILE);
    if ($raw === false || trim($raw) === '') {
        return kubtech_default_projects();
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : kubtech_default_projects();
}

function kubtech_write_projects(array $projects): void
{
    kubtech_ensure_data_file();
    file_put_contents(
        KUBTECH_DATA_FILE,
        json_encode(array_values($projects), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );
}

function kubtech_sort_projects(array $projects): array
{
    usort($projects, static function (array $left, array $right): int {
        $leftDate = strtotime($left['updatedAt'] ?? $left['createdAt'] ?? '1970-01-01');
        $rightDate = strtotime($right['updatedAt'] ?? $right['createdAt'] ?? '1970-01-01');
        return $rightDate <=> $leftDate;
    });

    return $projects;
}

function kubtech_is_admin(): bool
{
    return !empty($_SESSION['kubtech_admin']);
}

function kubtech_require_admin(): void
{
    if (!kubtech_is_admin()) {
        header('Location: admin-login.php');
        exit;
    }
}

function kubtech_csrf_token(): string
{
    if (empty($_SESSION['kubtech_csrf'])) {
        $_SESSION['kubtech_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['kubtech_csrf'];
}

function kubtech_verify_csrf(?string $token): bool
{
    return is_string($token)
        && !empty($_SESSION['kubtech_csrf'])
        && hash_equals($_SESSION['kubtech_csrf'], $token);
}

function kubtech_json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function kubtech_sanitize_project(array $project): array
{
    $now = gmdate('c');
    $existingCreatedAt = isset($project['createdAt']) && is_string($project['createdAt']) ? $project['createdAt'] : $now;
    $images = [];

    foreach (($project['images'] ?? []) as $image) {
        if (!is_array($image)) {
            continue;
        }

        $src = trim((string) ($image['src'] ?? ''));
        if ($src === '') {
            continue;
        }

        $images[] = [
            'id' => trim((string) ($image['id'] ?? ('image-' . bin2hex(random_bytes(4))))) ?: ('image-' . bin2hex(random_bytes(4))),
            'src' => $src,
            'alt' => trim((string) ($image['alt'] ?? '')),
            'label' => trim((string) ($image['label'] ?? 'Widok')),
        ];
    }

    return [
        'id' => trim((string) ($project['id'] ?? ('project-' . bin2hex(random_bytes(4))))) ?: ('project-' . bin2hex(random_bytes(4))),
        'category' => trim((string) ($project['category'] ?? 'Realizacja')),
        'location' => trim((string) ($project['location'] ?? '')),
        'title' => trim((string) ($project['title'] ?? 'Nowa realizacja')),
        'description' => trim((string) ($project['description'] ?? '')),
        'tags' => array_values(array_filter(array_map(
            static fn ($tag) => trim((string) $tag),
            is_array($project['tags'] ?? null) ? $project['tags'] : []
        ))),
        'createdAt' => $existingCreatedAt,
        'updatedAt' => $now,
        'images' => $images,
    ];
}
