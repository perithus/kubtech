<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($method === 'GET') {
    kubtech_json_response([
        'projects' => kubtech_sort_projects(kubtech_read_projects()),
    ]);
}

if ($method !== 'POST') {
    kubtech_json_response(['message' => 'Metoda nie jest obslugiwana.'], 405);
}

if (!kubtech_is_admin()) {
    kubtech_json_response(['message' => 'Brak autoryzacji.'], 401);
}

$input = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($input)) {
    kubtech_json_response(['message' => 'Niepoprawne dane wejściowe.'], 422);
}

if (!kubtech_verify_csrf($input['csrfToken'] ?? null)) {
    kubtech_json_response(['message' => 'Sesja wygasla. Odswiez panel i sprobuj ponownie.'], 419);
}

$action = (string) ($input['action'] ?? '');
$projects = kubtech_read_projects();

if ($action === 'save') {
    $project = kubtech_sanitize_project(is_array($input['project'] ?? null) ? $input['project'] : []);

    if ($project['title'] === '' || $project['category'] === '' || empty($project['images'])) {
        kubtech_json_response(['message' => 'Projekt musi miec tytul, kategorie i przynajmniej jedno zdjecie.'], 422);
    }

    $saved = false;
    foreach ($projects as $index => $existingProject) {
        if (($existingProject['id'] ?? '') === $project['id']) {
            $project['createdAt'] = $existingProject['createdAt'] ?? $project['createdAt'];
            $projects[$index] = $project;
            $saved = true;
            break;
        }
    }

    if (!$saved) {
        $projects[] = $project;
    }

    kubtech_write_projects(kubtech_sort_projects($projects));
    kubtech_json_response([
        'message' => 'Realizacja zostala zapisana.',
        'project' => $project,
    ]);
}

if ($action === 'delete') {
    $id = trim((string) ($input['id'] ?? ''));
    if ($id === '') {
        kubtech_json_response(['message' => 'Brakuje identyfikatora realizacji.'], 422);
    }

    $filtered = array_values(array_filter($projects, static fn (array $project): bool => ($project['id'] ?? '') !== $id));
    kubtech_write_projects(kubtech_sort_projects($filtered));

    kubtech_json_response(['message' => 'Realizacja zostala usunieta.']);
}

kubtech_json_response(['message' => 'Nieznana akcja.'], 400);
