<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';
kubtech_require_admin();
$csrfToken = kubtech_csrf_token();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KUB-TECH CMS | Realizacje</title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; }
    :root {
      --bg: #f5eee4;
      --surface: rgba(255, 250, 243, 0.96);
      --surface-soft: #f0e5d5;
      --text: #241e1a;
      --muted: #695f56;
      --line: rgba(74, 46, 20, 0.14);
      --accent: #9a673d;
      --accent-dark: #5b3a20;
      --danger: #9b443e;
      --success: #2f6b4d;
      --shadow: 0 24px 60px rgba(53, 35, 19, 0.12);
      --serif: "Cormorant Garamond", Georgia, serif;
      --sans: "Manrope", system-ui, sans-serif;
    }

    body {
      margin: 0;
      font-family: var(--sans);
      color: var(--text);
      background:
        radial-gradient(circle at top left, rgba(154, 103, 61, 0.18), transparent 24%),
        linear-gradient(180deg, #fffaf3 0%, #f5eee4 50%, #f1e7da 100%);
    }

    .shell {
      width: min(1440px, calc(100% - 2rem));
      margin: 0 auto;
      padding: 2rem 0;
    }

    .topbar,
    .intro,
    .panel {
      border-radius: 30px;
      background: var(--surface);
      box-shadow: var(--shadow);
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      padding: 1.2rem 1.5rem;
    }

    .brand {
      font-family: var(--serif);
      font-size: 1.9rem;
      font-weight: 600;
      letter-spacing: 0.04em;
    }

    .brand span { color: var(--accent); }

    .topbar-actions,
    .form-actions,
    .project-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
    }

    .btn,
    .btn-secondary,
    .btn-danger,
    .mini-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-height: 48px;
      padding: 0.9rem 1.2rem;
      border: 0;
      border-radius: 999px;
      cursor: pointer;
      font: inherit;
      font-size: 0.83rem;
      font-weight: 800;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      text-decoration: none;
      transition: transform 0.2s ease, background 0.2s ease;
    }

    .btn { background: var(--text); color: #fff; }
    .btn-secondary { background: transparent; color: var(--text); border: 1px solid var(--line); }
    .btn-danger, .mini-btn { background: rgba(155, 68, 62, 0.12); color: var(--danger); }
    .btn:hover, .btn-secondary:hover, .btn-danger:hover, .mini-btn:hover { transform: translateY(-1px); }

    .intro {
      margin-bottom: 1.5rem;
      padding: 2rem;
    }

    .eyebrow {
      margin-bottom: 0.9rem;
      color: var(--accent);
      font-size: 0.76rem;
      font-weight: 800;
      letter-spacing: 0.16em;
      text-transform: uppercase;
    }

    .intro h1 {
      margin: 0 0 1rem;
      font-family: var(--serif);
      font-size: clamp(2.4rem, 4vw, 4.2rem);
      font-weight: 500;
      line-height: 0.98;
    }

    .intro p {
      max-width: 72ch;
      margin: 0;
      color: var(--muted);
      line-height: 1.8;
    }

    .layout {
      display: grid;
      grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
      gap: 1.4rem;
      align-items: start;
    }

    .panel {
      padding: 1.5rem;
    }

    .panel h2 {
      margin: 0 0 0.35rem;
      font-family: var(--serif);
      font-size: 2rem;
      font-weight: 500;
    }

    .panel-sub {
      margin: 0 0 1.25rem;
      color: var(--muted);
      line-height: 1.7;
    }

    .security-note {
      margin-bottom: 1.2rem;
      padding: 1rem 1.1rem;
      border: 1px solid rgba(154, 103, 61, 0.16);
      border-radius: 20px;
      background: rgba(240, 229, 213, 0.35);
      color: var(--muted);
      line-height: 1.7;
      font-size: 0.9rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1rem;
    }

    .field {
      display: grid;
      gap: 0.45rem;
      margin-bottom: 1rem;
    }

    .field.full { grid-column: 1 / -1; }

    .label {
      font-size: 0.88rem;
      font-weight: 800;
    }

    .input,
    .textarea,
    .file-input {
      width: 100%;
      min-height: 52px;
      padding: 0.92rem 1rem;
      border: 1px solid var(--line);
      border-radius: 18px;
      background: #fff;
      color: var(--text);
      font: inherit;
    }

    .textarea {
      min-height: 142px;
      resize: vertical;
    }

    .hint {
      color: var(--muted);
      font-size: 0.84rem;
      line-height: 1.7;
    }

    .image-list,
    .projects-list {
      display: grid;
      gap: 1rem;
    }

    .image-card,
    .project-row {
      display: grid;
      gap: 1rem;
      padding: 0.95rem;
      border: 1px solid var(--line);
      border-radius: 24px;
      background: rgba(240, 229, 213, 0.28);
    }

    .image-card {
      grid-template-columns: 120px minmax(0, 1fr);
    }

    .image-thumb {
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 16px;
      background: var(--surface-soft);
    }

    .image-fields {
      display: grid;
      gap: 0.7rem;
    }

    .inline-grid {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 0.7rem;
      align-items: end;
    }

    .project-row {
      grid-template-columns: 110px minmax(0, 1fr) auto;
      align-items: center;
    }

    .project-cover {
      width: 110px;
      height: 90px;
      object-fit: cover;
      border-radius: 16px;
      background: var(--surface-soft);
    }

    .project-row h3 {
      margin: 0 0 0.35rem;
      font-size: 1rem;
    }

    .project-row p {
      margin: 0;
      color: var(--muted);
      font-size: 0.88rem;
      line-height: 1.6;
    }

    .project-meta {
      margin-top: 0.45rem;
      color: var(--accent);
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0.12em;
      text-transform: uppercase;
    }

    .empty {
      padding: 1.2rem;
      border: 1px dashed var(--line);
      border-radius: 22px;
      color: var(--muted);
      line-height: 1.7;
      text-align: center;
    }

    .status {
      display: none;
      margin-top: 1rem;
      padding: 0.95rem 1rem;
      border-radius: 18px;
      font-size: 0.9rem;
      line-height: 1.6;
    }

    .status.is-visible { display: block; }
    .status.success { background: rgba(47, 107, 77, 0.1); color: var(--success); }
    .status.error { background: rgba(155, 68, 62, 0.1); color: var(--danger); }

    @media (max-width: 1080px) {
      .layout { grid-template-columns: 1fr; }
    }

    @media (max-width: 760px) {
      .shell { width: min(100%, calc(100% - 1rem)); padding: 1rem 0; }
      .topbar { flex-direction: column; align-items: stretch; }
      .form-grid,
      .image-card,
      .project-row,
      .inline-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="shell">
    <div class="topbar">
      <div class="brand">KUB<span>·</span>TECH CMS</div>
      <div class="topbar-actions">
        <a class="btn-secondary" href="kubtech_meble_landing.html">Zobacz strone</a>
        <a class="btn-secondary" href="admin-logout.php">Wyloguj</a>
      </div>
    </div>

    <section class="intro">
      <div class="eyebrow">Panel admina</div>
      <h1>Dodawanie realizacji i zdjec bez grzebania w HTML</h1>
      <p>Ten CMS zapisuje realizacje po stronie serwera w pliku JSON, a landing odczytuje je automatycznie przez API. Dzięki temu mozesz dodawac wiecej projektow i miec te same dane na stronie publicznej oraz w panelu.</p>
    </section>

    <div class="layout">
      <section class="panel">
        <h2>Edytor realizacji</h2>
        <p class="panel-sub">Dodaj nowy projekt albo edytuj istniejacy. Pierwsze zdjecie w liscie bedzie traktowane jako glowne.</p>
        <div class="security-note">Dostep do tego panelu jest ograniczony do zalogowanego admina przez sesje PHP. Dla bezpieczenstwa zmien domyslny login i haslo przez zmienne srodowiskowe: <code>KUBTECH_ADMIN_USER</code> i <code>KUBTECH_ADMIN_PASS</code>.</div>

        <form id="projectForm">
          <input type="hidden" id="projectId">

          <div class="form-grid">
            <div class="field">
              <label class="label" for="category">Typ realizacji</label>
              <input class="input" id="category" type="text" placeholder="Np. Kuchnia, lazienka, garderoba" required>
            </div>
            <div class="field">
              <label class="label" for="location">Lokalizacja</label>
              <input class="input" id="location" type="text" placeholder="Np. Leczyca" required>
            </div>
            <div class="field full">
              <label class="label" for="title">Tytul realizacji</label>
              <input class="input" id="title" type="text" placeholder="Np. Nowoczesna kuchnia z wyspa" required>
            </div>
            <div class="field full">
              <label class="label" for="description">Opis</label>
              <textarea class="textarea" id="description" placeholder="Opisz projekt, materialy, charakter wnetrza i to, co warto pokazac klientowi." required></textarea>
            </div>
            <div class="field full">
              <label class="label" for="tags">Tagi</label>
              <input class="input" id="tags" type="text" placeholder="Np. deb, zabudowa AGD, pod sufit">
              <div class="hint">Oddzielaj tagi przecinkami. Pojawia sie na stronie jako wyrozniki realizacji.</div>
            </div>
            <div class="field full">
              <label class="label" for="images">Zdjecia realizacji</label>
              <input class="file-input" id="images" type="file" accept="image/*" multiple>
              <div class="hint">Mozesz dodac wieksza liczbe ujec tego samego projektu. Zdjecia sa pomniejszane w przegladarce przed zapisaniem, zeby CMS dzialal sprawnie.</div>
            </div>
          </div>

          <div class="image-list" id="imageList"></div>

          <div class="form-actions">
            <button class="btn" type="submit">Zapisz realizacje</button>
            <button class="btn-secondary" type="button" id="resetForm">Wyczysc formularz</button>
          </div>

          <div class="status success" id="successStatus"></div>
          <div class="status error" id="errorStatus"></div>
        </form>
      </section>

      <section class="panel">
        <h2>Zapisane realizacje</h2>
        <p class="panel-sub">Tutaj zobaczysz wszystkie projekty zapisane po stronie serwera. Mozesz je edytowac albo usunac.</p>
        <div class="projects-list" id="projectsList"></div>
      </section>
    </div>
  </div>

  <script>
    window.KUBTECH_CSRF_TOKEN = <?= json_encode($csrfToken, JSON_UNESCAPED_SLASHES) ?>;
  </script>
  <script src="kubtech_cms.js"></script>
  <script>
    const projectForm = document.getElementById("projectForm");
    const imageInput = document.getElementById("images");
    const imageList = document.getElementById("imageList");
    const projectsList = document.getElementById("projectsList");
    const resetFormButton = document.getElementById("resetForm");
    const successStatus = document.getElementById("successStatus");
    const errorStatus = document.getElementById("errorStatus");

    let draftImages = [];
    let currentProjects = [];

    function clearStatus() {
      successStatus.className = "status success";
      errorStatus.className = "status error";
      successStatus.textContent = "";
      errorStatus.textContent = "";
    }

    function showStatus(type, message) {
      clearStatus();
      const target = type === "error" ? errorStatus : successStatus;
      target.textContent = message;
      target.classList.add("is-visible");
    }

    function resetForm() {
      projectForm.reset();
      document.getElementById("projectId").value = "";
      draftImages = [];
      renderDraftImages();
      clearStatus();
    }

    function renderDraftImages() {
      if (!draftImages.length) {
        imageList.innerHTML = '<div class="empty">Ta realizacja nie ma jeszcze zdjec. Dodaj kilka ujec tego samego projektu, zeby lepiej pokazac efekt koncowy.</div>';
        return;
      }

      imageList.innerHTML = draftImages.map((image, index) => (
        '<div class="image-card">' +
          '<img class="image-thumb" src="' + image.src + '" alt="' + (image.alt || "Zdjecie realizacji") + '">' +
          '<div class="image-fields">' +
            '<div class="inline-grid">' +
              '<div class="field" style="margin-bottom:0;">' +
                '<label class="label" for="label-' + image.id + '">Etykieta zdjecia</label>' +
                '<input class="input" id="label-' + image.id + '" data-image-id="' + image.id + '" data-field="label" type="text" value="' + (image.label || ("Widok " + (index + 1))) + '">' +
              '</div>' +
              '<button class="mini-btn" type="button" data-remove-image="' + image.id + '">Usun</button>' +
            '</div>' +
            '<div class="field" style="margin-bottom:0;">' +
              '<label class="label" for="alt-' + image.id + '">Opis ALT</label>' +
              '<input class="input" id="alt-' + image.id + '" data-image-id="' + image.id + '" data-field="alt" type="text" value="' + (image.alt || "") + '">' +
            '</div>' +
          '</div>' +
        '</div>'
      )).join("");
    }

    function fillForm(project) {
      document.getElementById("projectId").value = project.id || "";
      document.getElementById("category").value = project.category || "";
      document.getElementById("location").value = project.location || "";
      document.getElementById("title").value = project.title || "";
      document.getElementById("description").value = project.description || "";
      document.getElementById("tags").value = Array.isArray(project.tags) ? project.tags.join(", ") : "";
      draftImages = Array.isArray(project.images) ? project.images.map((image) => ({ ...image })) : [];
      renderDraftImages();
      window.scrollTo({ top: 0, behavior: "smooth" });
    }

    async function refreshProjects() {
      try {
        currentProjects = await window.KubtechCMS.getProjects();

        if (!currentProjects.length) {
          projectsList.innerHTML = '<div class="empty">Nie ma jeszcze zapisanych realizacji. Po zapisaniu nowego projektu pojawi sie tutaj lista do edycji.</div>';
          return;
        }

        projectsList.innerHTML = currentProjects.map((project) => {
          const cover = project.images && project.images[0] ? project.images[0].src : "";
          const meta = [project.category, project.location].filter(Boolean).join(" · ");
          const photos = Array.isArray(project.images) ? project.images.length : 0;
          return (
            '<article class="project-row">' +
              '<img class="project-cover" src="' + cover + '" alt="' + (project.title || "Realizacja") + '">' +
              '<div>' +
                '<h3>' + project.title + '</h3>' +
                '<p>' + project.description + '</p>' +
                '<div class="project-meta">' + meta + ' · ' + photos + ' zdjec</div>' +
              '</div>' +
              '<div class="project-actions">' +
                '<button class="btn-secondary" type="button" data-edit-project="' + project.id + '">Edytuj</button>' +
                '<button class="btn-danger" type="button" data-delete-project="' + project.id + '">Usun</button>' +
              '</div>' +
            '</article>'
          );
        }).join("");
      } catch (error) {
        showStatus("error", error.message || "Nie udalo sie odczytac zapisanych realizacji.");
      }
    }

    imageInput.addEventListener("change", async (event) => {
      clearStatus();
      const files = event.target.files;
      if (!files || !files.length) {
        return;
      }

      try {
        const images = await window.KubtechCMS.filesToImages(files);
        const withLabels = images.map((image, index) => ({
          ...image,
          label: "Widok " + (draftImages.length + index + 1)
        }));
        draftImages = draftImages.concat(withLabels);
        renderDraftImages();
        imageInput.value = "";
      } catch (error) {
        showStatus("error", "Nie udalo sie przetworzyc wybranych zdjec.");
      }
    });

    imageList.addEventListener("input", (event) => {
      const field = event.target.getAttribute("data-field");
      const imageId = event.target.getAttribute("data-image-id");
      if (!field || !imageId) {
        return;
      }

      draftImages = draftImages.map((image) => image.id === imageId
        ? { ...image, [field]: event.target.value }
        : image);
    });

    imageList.addEventListener("click", (event) => {
      const removeId = event.target.getAttribute("data-remove-image");
      if (!removeId) {
        return;
      }

      draftImages = draftImages.filter((image) => image.id !== removeId);
      renderDraftImages();
    });

    projectsList.addEventListener("click", async (event) => {
      const editId = event.target.getAttribute("data-edit-project");
      const deleteId = event.target.getAttribute("data-delete-project");

      if (editId) {
        const project = currentProjects.find((item) => item.id === editId);
        if (project) {
          fillForm(project);
          clearStatus();
        }
      }

      if (deleteId) {
        const confirmed = window.confirm("Usunac te realizacje z CMS?");
        if (!confirmed) {
          return;
        }

        try {
          await window.KubtechCMS.deleteProject(deleteId, window.KUBTECH_CSRF_TOKEN);
          if (document.getElementById("projectId").value === deleteId) {
            resetForm();
          }
          await refreshProjects();
          showStatus("success", "Realizacja zostala usunieta.");
        } catch (error) {
          showStatus("error", error.message || "Nie udalo sie usunac realizacji.");
        }
      }
    });

    resetFormButton.addEventListener("click", resetForm);

    projectForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      clearStatus();

      if (!draftImages.length) {
        showStatus("error", "Dodaj przynajmniej jedno zdjecie realizacji.");
        return;
      }

      const payload = {
        id: document.getElementById("projectId").value || undefined,
        category: document.getElementById("category").value.trim(),
        location: document.getElementById("location").value.trim(),
        title: document.getElementById("title").value.trim(),
        description: document.getElementById("description").value.trim(),
        tags: document.getElementById("tags").value.split(",").map((tag) => tag.trim()).filter(Boolean),
        images: draftImages
      };

      try {
        await window.KubtechCMS.saveProject(payload, window.KUBTECH_CSRF_TOKEN);
        resetForm();
        await refreshProjects();
        showStatus("success", "Realizacja zostala zapisana i jest juz dostepna na stronie publicznej.");
      } catch (error) {
        showStatus("error", error.message || "Nie udalo sie zapisac realizacji.");
      }
    });

    renderDraftImages();
    refreshProjects();
  </script>
</body>
</html>
