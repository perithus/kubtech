(function () {
  const DB_NAME = "kubtech-cms";
  const DB_VERSION = 1;
  const STORE_NAME = "projects";
  let renderedProjects = [];

  const defaultProjects = [
    {
      id: "default-kuchnia",
      category: "Kuchnia",
      location: "Łęczyca",
      title: "Nowoczesna kuchnia dla domu pod Łęczycą",
      description: "Jasna zabudowa kuchenna wykonana pod konkretny układ pomieszczenia, z wygodną strefą roboczą, miejscem do przechowywania i spójnym wykończeniem frontów.",
      tags: ["Dąb i beż", "Zabudowa AGD", "Na wymiar"],
      createdAt: "2026-05-04T17:30:00.000Z",
      updatedAt: "2026-05-04T17:30:00.000Z",
      images: [
        {
          id: "default-kuchnia-1",
          src: "https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1200&q=80",
          alt: "Główne ujęcie kuchni na wymiar z drewnianymi frontami",
          label: "Widok główny"
        },
        {
          id: "default-kuchnia-2",
          src: "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80",
          alt: "Boczny kadr tej samej kuchni z wyspą",
          label: "Strefa robocza"
        },
        {
          id: "default-kuchnia-3",
          src: "https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=900&q=80",
          alt: "Detal zabudowy i przechowywania w tej samej kuchni",
          label: "Detal zabudowy"
        },
        {
          id: "default-kuchnia-4",
          src: "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=900&q=80",
          alt: "Blat i strefa przygotowywania w kuchni na wymiar",
          label: "Blat"
        },
        {
          id: "default-kuchnia-5",
          src: "https://images.unsplash.com/photo-1556909212-d5b604d0c90d?auto=format&fit=crop&w=900&q=80",
          alt: "Fronty i detale wykończenia kuchni na wymiar",
          label: "Fronty"
        }
      ]
    },
    {
      id: "default-lazienka",
      category: "Łazienka",
      location: "Zgierz",
      title: "Zabudowa łazienkowa z miejscem do przechowywania",
      description: "Zabudowa łazienkowa zaprojektowana z myślą o codziennym komforcie, odporności na wilgoć i estetycznym ukryciu najpotrzebniejszych rzeczy.",
      tags: ["Odporność na wilgoć", "Spójne wykończenie", "Przechowywanie"],
      createdAt: "2026-05-04T17:31:00.000Z",
      updatedAt: "2026-05-04T17:31:00.000Z",
      images: [
        {
          id: "default-lazienka-1",
          src: "https://images.unsplash.com/photo-1620626011761-996317b8d101?auto=format&fit=crop&w=1200&q=80",
          alt: "Główne ujęcie mebli łazienkowych na wymiar",
          label: "Widok główny"
        },
        {
          id: "default-lazienka-2",
          src: "https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=900&q=80",
          alt: "Detal przechowywania i zabudowy w łazience",
          label: "Detal frontów"
        },
        {
          id: "default-lazienka-3",
          src: "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80",
          alt: "Drugie ujęcie tej samej realizacji łazienkowej",
          label: "Drugie ujęcie"
        },
        {
          id: "default-lazienka-4",
          src: "https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=900&q=80",
          alt: "Detal umywalki i zabudowy łazienkowej",
          label: "Umywalka"
        },
        {
          id: "default-lazienka-5",
          src: "https://images.unsplash.com/photo-1600566752355-35792bedcfea?auto=format&fit=crop&w=900&q=80",
          alt: "Przechowywanie w zabudowie łazienkowej",
          label: "Przechowywanie"
        }
      ]
    }
  ];

  function supportsIndexedDb() {
    return typeof indexedDB !== "undefined";
  }

  function openDb() {
    return new Promise((resolve, reject) => {
      if (!supportsIndexedDb()) {
        reject(new Error("IndexedDB is not supported in this browser."));
        return;
      }

      const request = indexedDB.open(DB_NAME, DB_VERSION);

      request.onupgradeneeded = () => {
        const db = request.result;
        if (!db.objectStoreNames.contains(STORE_NAME)) {
          db.createObjectStore(STORE_NAME, { keyPath: "id" });
        }
      };

      request.onsuccess = () => resolve(request.result);
      request.onerror = () => reject(request.error || new Error("Cannot open database."));
    });
  }

  async function withStore(mode, callback) {
    const db = await openDb();
    return new Promise((resolve, reject) => {
      const transaction = db.transaction(STORE_NAME, mode);
      const store = transaction.objectStore(STORE_NAME);
      const result = callback(store, transaction);

      transaction.oncomplete = () => {
        db.close();
        resolve(result);
      };
      transaction.onerror = () => {
        db.close();
        reject(transaction.error || new Error("Database transaction failed."));
      };
      transaction.onabort = () => {
        db.close();
        reject(transaction.error || new Error("Database transaction aborted."));
      };
    });
  }

  function requestToPromise(request) {
    return new Promise((resolve, reject) => {
      request.onsuccess = () => resolve(request.result);
      request.onerror = () => reject(request.error || new Error("Request failed."));
    });
  }

  function sortProjects(projects) {
    return [...projects].sort((a, b) => {
      const dateA = new Date(a.updatedAt || a.createdAt || 0).getTime();
      const dateB = new Date(b.updatedAt || b.createdAt || 0).getTime();
      return dateB - dateA;
    });
  }

  async function getProjects() {
    if (!supportsIndexedDb()) {
      return [];
    }

    const db = await openDb();
    return new Promise((resolve, reject) => {
      const transaction = db.transaction(STORE_NAME, "readonly");
      const store = transaction.objectStore(STORE_NAME);
      const request = store.getAll();

      request.onsuccess = () => {
        db.close();
        resolve(sortProjects(request.result || []));
      };
      request.onerror = () => {
        db.close();
        reject(request.error || new Error("Cannot read projects."));
      };
    });
  }

  async function saveProject(project) {
    const now = new Date().toISOString();
    const payload = {
      ...project,
      id: project.id || createId("project"),
      createdAt: project.createdAt || now,
      updatedAt: now,
      images: Array.isArray(project.images) ? project.images : []
    };

    await withStore("readwrite", (store) => {
      store.put(payload);
    });

    return payload;
  }

  async function deleteProject(id) {
    await withStore("readwrite", (store) => {
      store.delete(id);
    });
  }

  function createId(prefix) {
    return prefix + "-" + Math.random().toString(36).slice(2, 10);
  }

  function fileToImage(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => {
        const image = new Image();
        image.onload = () => resolve(image);
        image.onerror = reject;
        image.src = reader.result;
      };
      reader.onerror = () => reject(reader.error || new Error("Cannot read file."));
      reader.readAsDataURL(file);
    });
  }

  async function resizeImage(file, options) {
    const settings = Object.assign(
      {
        maxWidth: 1800,
        maxHeight: 1400,
        quality: 0.82
      },
      options || {}
    );

    const source = await fileToImage(file);
    const ratio = Math.min(
      1,
      settings.maxWidth / source.width,
      settings.maxHeight / source.height
    );

    const width = Math.round(source.width * ratio);
    const height = Math.round(source.height * ratio);
    const canvas = document.createElement("canvas");
    canvas.width = width;
    canvas.height = height;

    const context = canvas.getContext("2d");
    context.drawImage(source, 0, 0, width, height);

    const src = canvas.toDataURL("image/jpeg", settings.quality);

    return {
      id: createId("image"),
      src: src,
      alt: file.name.replace(/\.[^.]+$/, "").replace(/[-_]+/g, " "),
      label: "Widok"
    };
  }

  async function filesToImages(fileList, options) {
    const files = Array.from(fileList || []);
    const results = [];

    for (const file of files) {
      results.push(await resizeImage(file, options));
    }

    return results;
  }

  function escapeHtml(value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function projectCardTemplate(project) {
    const allImages = Array.isArray(project.images) ? project.images : [];
    const images = allImages.slice(0, 5);
    const meta = [project.category, project.location].filter(Boolean).join(" · ");
    const totalImages = allImages.length;
    const tags = Array.isArray(project.tags) ? project.tags : [];

    const imagesMarkup = images.map((image, index) => {
      const remaining = totalImages - images.length;
      const isLastVisible = index === images.length - 1;
      const label = remaining > 0 && isLastVisible
        ? escapeHtml((image.label || "Więcej zdjęć") + " · +" + remaining)
        : escapeHtml(image.label || (index === 0 ? "Widok główny" : "Ujęcie"));

      return (
        '<figure class="project-shot' + (index === 0 ? " featured" : "") + '" role="button" tabindex="0" data-project-id="' + escapeHtml(project.id) + '" data-image-index="' + index + '">' +
          '<img src="' + escapeHtml(image.src) + '" alt="' + escapeHtml(image.alt || project.title) + '">' +
          '<figcaption class="project-shot-label">' + label + "</figcaption>" +
        "</figure>"
      );
    }).join("");

    const galleryMarkup = !images.length
      ? '<div class="project-shot featured"><div class="project-shot-label">Dodaj zdjęcia realizacji</div></div>'
      : imagesMarkup;

    const tagsMarkup = tags.map((tag) => (
      '<span class="project-chip">' + escapeHtml(tag) + "</span>"
    )).join("");

    return (
      '<article class="project-card" data-project-id="' + escapeHtml(project.id) + '">' +
        '<div class="project-info">' +
          '<div class="project-meta">' + escapeHtml(meta || "Realizacja") + "</div>" +
          '<h3 class="project-title">' + escapeHtml(project.title || "Nowa realizacja") + "</h3>" +
          '<p class="project-desc">' + escapeHtml(project.description || "Dodaj opis realizacji w CMS-ie.") + "</p>" +
          '<div class="project-highlights">' + tagsMarkup + "</div>" +
        "</div>" +
        '<div class="project-gallery">' +
          galleryMarkup +
        "</div>" +
      "</article>"
    );
  }

  async function loadProjectsWithFallback() {
    try {
      const stored = await getProjects();
      return stored.length ? stored : defaultProjects;
    } catch (error) {
      return defaultProjects;
    }
  }

  async function renderProjects(containerOrSelector, projects) {
    const container = typeof containerOrSelector === "string"
      ? document.querySelector(containerOrSelector)
      : containerOrSelector;

    if (!container) {
      return;
    }

    const records = projects || await loadProjectsWithFallback();

    if (!records.length) {
      container.innerHTML = '<div class="project-card"><div class="project-info"><div class="project-meta">Brak realizacji</div><h3 class="project-title">Dodaj pierwszy projekt w CMS</h3><p class="project-desc">Po zapisaniu realizacji w panelu administracyjnym pojawią się tutaj automatycznie.</p></div></div>';
      return;
    }

    renderedProjects = records;
    container.innerHTML = records.map(projectCardTemplate).join("");
  }

  window.KubtechCMS = {
    defaultProjects,
    getProjects,
    loadProjectsWithFallback,
    saveProject,
    deleteProject,
    filesToImages,
    createId,
    renderProjects,
    getRenderedProjects: () => renderedProjects
  };
})();
