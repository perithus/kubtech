(function () {
  const PUBLIC_ENDPOINT = "cms-api.php";
  const ADMIN_ENDPOINT = "cms-api.php";
  let renderedProjects = [];

  const defaultProjects = [
    {
      id: "default-kuchnia",
      category: "Kuchnia",
      location: "Leczyca",
      title: "Nowoczesna kuchnia dla domu pod Leczyca",
      description: "Jasna zabudowa kuchenna wykonana pod konkretny uklad pomieszczenia, z wygodna strefa robocza, miejscem do przechowywania i spojnym wykonczeniem frontow.",
      tags: ["Debowe akcenty", "Zabudowa AGD", "Na wymiar"],
      createdAt: "2026-05-04T17:30:00.000Z",
      updatedAt: "2026-05-04T17:30:00.000Z",
      images: [
        {
          id: "default-kuchnia-1",
          src: "https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1400&q=80",
          alt: "Nowoczesna kuchnia na wymiar z drewnianymi frontami",
          label: "Widok glowny"
        },
        {
          id: "default-kuchnia-2",
          src: "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80",
          alt: "Kuchnia na wymiar z wyspa i zabudowa robocza",
          label: "Strefa robocza"
        },
        {
          id: "default-kuchnia-3",
          src: "https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=900&q=80",
          alt: "Detal zabudowy kuchennej i systemow przechowywania",
          label: "Detal zabudowy"
        },
        {
          id: "default-kuchnia-4",
          src: "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=900&q=80",
          alt: "Blat i strefa przygotowywania w kuchni na wymiar",
          label: "Blat"
        }
      ]
    },
    {
      id: "default-lazienka",
      category: "Lazienka",
      location: "Zgierz",
      title: "Zabudowa lazienkowa z miejscem do przechowywania",
      description: "Zabudowa lazienkowa zaprojektowana z mysla o codziennym komforcie, odpornosci na wilgoc i estetycznym ukryciu najpotrzebniejszych rzeczy.",
      tags: ["Wilgocioodporna plyta", "Spojne wykonczenie", "Przechowywanie"],
      createdAt: "2026-05-04T17:31:00.000Z",
      updatedAt: "2026-05-04T17:31:00.000Z",
      images: [
        {
          id: "default-lazienka-1",
          src: "https://images.unsplash.com/photo-1620626011761-996317b8d101?auto=format&fit=crop&w=1400&q=80",
          alt: "Meble lazienkowe na wymiar z miejscem do przechowywania",
          label: "Widok glowny"
        },
        {
          id: "default-lazienka-2",
          src: "https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=900&q=80",
          alt: "Detal frontow i zabudowy lazienkowej",
          label: "Detal frontow"
        },
        {
          id: "default-lazienka-3",
          src: "https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=900&q=80",
          alt: "Szafka lazienkowa na wymiar pod umywalke",
          label: "Szafka pod umywalke"
        }
      ]
    },
    {
      id: "default-garderoba",
      category: "Garderoba",
      location: "Lodz",
      title: "Garderoba pod skos z wykorzystaniem calej wysokosci",
      description: "Projekt wykonany pod trudniejsza geometrie poddasza, z przegrodami, szufladami i strefa na dluzsze ubrania.",
      tags: ["Pod skosem", "Szuflady", "Pelna wysokosc"],
      createdAt: "2026-05-04T17:32:00.000Z",
      updatedAt: "2026-05-04T17:32:00.000Z",
      images: [
        {
          id: "default-garderoba-1",
          src: "https://images.unsplash.com/photo-1556020685-ae41abfc9365?auto=format&fit=crop&w=1400&q=80",
          alt: "Garderoba na wymiar z szufladami i oswietleniem",
          label: "Widok glowny"
        },
        {
          id: "default-garderoba-2",
          src: "https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=900&q=80",
          alt: "Wnetrze garderoby z podzialem na strefy przechowywania",
          label: "Wnetrze zabudowy"
        },
        {
          id: "default-garderoba-3",
          src: "https://images.unsplash.com/photo-1560185008-b033106af5c3?auto=format&fit=crop&w=900&q=80",
          alt: "System szuflad w garderobie na wymiar",
          label: "System szuflad"
        }
      ]
    }
  ];

  function escapeHtml(value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function createId(prefix) {
    return prefix + "-" + Math.random().toString(36).slice(2, 10);
  }

  function sortProjects(projects) {
    return [...projects].sort((a, b) => {
      const dateA = new Date(a.updatedAt || a.createdAt || 0).getTime();
      const dateB = new Date(b.updatedAt || b.createdAt || 0).getTime();
      return dateB - dateA;
    });
  }

  async function parseResponse(response) {
    const text = await response.text();
    let payload = null;

    try {
      payload = text ? JSON.parse(text) : {};
    } catch (error) {
      payload = { message: text || "Nieznana odpowiedz serwera." };
    }

    if (!response.ok) {
      const message = payload && payload.message ? payload.message : "Wystapil blad podczas komunikacji z serwerem.";
      throw new Error(message);
    }

    return payload;
  }

  async function getProjects() {
    const response = await fetch(PUBLIC_ENDPOINT, {
      headers: {
        "Accept": "application/json"
      }
    });
    const payload = await parseResponse(response);
    return sortProjects(Array.isArray(payload.projects) ? payload.projects : []);
  }

  async function loadProjectsWithFallback() {
    try {
      const projects = await getProjects();
      return projects.length ? projects : defaultProjects;
    } catch (error) {
      return defaultProjects;
    }
  }

  async function saveProject(project, csrfToken) {
    const response = await fetch(ADMIN_ENDPOINT, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify({
        action: "save",
        csrfToken: csrfToken || "",
        project: project
      })
    });

    const payload = await parseResponse(response);
    return payload.project;
  }

  async function deleteProject(id, csrfToken) {
    const response = await fetch(ADMIN_ENDPOINT, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify({
        action: "delete",
        csrfToken: csrfToken || "",
        id: id
      })
    });

    return parseResponse(response);
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
      reader.onerror = () => reject(reader.error || new Error("Nie udalo sie odczytac pliku."));
      reader.readAsDataURL(file);
    });
  }

  async function resizeImage(file, options) {
    const settings = Object.assign(
      {
        maxWidth: 1800,
        maxHeight: 1400,
        quality: 0.84
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
    const context = canvas.getContext("2d");

    canvas.width = width;
    canvas.height = height;
    context.drawImage(source, 0, 0, width, height);

    return {
      id: createId("image"),
      src: canvas.toDataURL("image/jpeg", settings.quality),
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

  function projectCardTemplate(project) {
    const allImages = Array.isArray(project.images) ? project.images : [];
    const images = allImages.slice(0, 3);
    const totalImages = allImages.length;
    const metaParts = [project.category, project.location].filter(Boolean);
    const tags = Array.isArray(project.tags) ? project.tags : [];
    const galleryClass = "project-gallery count-" + Math.max(1, Math.min(images.length, 3));

    const imagesMarkup = images.map((image, index) => {
      const remaining = totalImages - images.length;
      const isLastVisible = index === images.length - 1;
      const label = remaining > 0 && isLastVisible
        ? (image.label || "Wiecej zdjec") + " · +" + remaining
        : (image.label || (index === 0 ? "Widok glowny" : "Ujecie"));

      return (
        '<figure class="project-shot' + (index === 0 ? " featured" : " slot-" + (index + 1)) + '" role="button" tabindex="0" data-project-id="' + escapeHtml(project.id) + '" data-image-index="' + index + '">' +
          '<img src="' + escapeHtml(image.src) + '" alt="' + escapeHtml(image.alt || project.title || "Realizacja mebli na wymiar") + '">' +
          '<figcaption class="project-shot-label">' + escapeHtml(label) + "</figcaption>" +
        "</figure>"
      );
    }).join("");

    const tagMarkup = tags.map((tag) => '<span class="project-chip">' + escapeHtml(tag) + "</span>").join("");

    return (
      '<article class="project-card" data-category="' + escapeHtml((project.category || "").toLowerCase()) + '">' +
        '<div class="project-info">' +
          '<div class="project-meta">' + escapeHtml(metaParts.join(" · ") || "Realizacja") + "</div>" +
          '<h3 class="project-title">' + escapeHtml(project.title || "Nowa realizacja") + "</h3>" +
          '<p class="project-desc">' + escapeHtml(project.description || "Opis realizacji pojawi sie tutaj po zapisaniu projektu w CMS.") + "</p>" +
          '<div class="project-highlights">' + tagMarkup + "</div>" +
        "</div>" +
        '<div class="' + galleryClass + '">' + imagesMarkup + "</div>" +
      "</article>"
    );
  }

  async function renderProjects(containerOrSelector, projects) {
    const container = typeof containerOrSelector === "string"
      ? document.querySelector(containerOrSelector)
      : containerOrSelector;

    if (!container) {
      return [];
    }

    const records = projects || await loadProjectsWithFallback();
    renderedProjects = records;

    if (!records.length) {
      container.innerHTML = '<div class="empty-projects">Brak realizacji do wyswietlenia.</div>';
      return records;
    }

    container.innerHTML = records.map(projectCardTemplate).join("");
    return records;
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
    getRenderedProjects: function () {
      return renderedProjects;
    }
  };
})();
