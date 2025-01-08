<template>
  <div class="main-container">
    <!-- Kategorien -->
    <div class="categories">
      <!-- Dropdown for Mobile -->
      <select class="category-dropdown" @change="handleDropdownChange" v-if="isMobile">
        <option value="">ALLE</option>
        <option
          v-for="(material, index) in uniqueMaterials"
          :key="index"
          :value="material"
        >
          {{ material }}
        </option>
      </select>

      <!-- Buttons für Desktop -->
      <div class="category-buttons" v-if="!isMobile">
        <button class="category-button" @click="resetFilter">ALLE</button>
        <button
          v-for="(material, index) in uniqueMaterials"
          :key="index"
          class="category-button"
          @click="handleCategoryClick(material)"
        >
          {{ material }}
        </button>
      </div>
    </div>

    <!-- Galerie -->
    <div class="gallery">
      <div
        v-for="(item, index) in filteredItems"
        :key="index"
        class="gallery-item"
        @click="openPopup(index)"
      >
        <!-- Lazy Loading -->
        <img
          :src="item.src"
          :alt="item.title"
          loading="lazy"
        />
        <div class="hover-info">
          <p class="title">{{ item.title }}</p>
          <p class="details">
            {{ item.month ? item.month + ' ' : '' }}{{ item.year }}
          </p>
          <p class="details">{{ item.dimensions }}</p>
        </div>
      </div>
    </div>

    <!-- Popup -->
    <div v-if="selectedIndex !== null" class="popup">
      <div class="popup-content">
        <!-- Schließen -->
        <button class="close-button" @click="closePopup">X</button>

        <!-- Navigation -->
        <button class="prev-button" @click="prevImage">&lt;</button>
        <img
          :src="filteredItems[selectedIndex].src"
          :alt="filteredItems[selectedIndex].title"
          class="popup-image"
        />
        <button class="next-button" @click="nextImage">&gt;</button>

        <!-- Popup-Details -->
        <div class="popup-details">
          <h2>{{ filteredItems[selectedIndex].title }}</h2>
          <p>
            <strong>Material:</strong>
            {{ filteredItems[selectedIndex].material }}
          </p>
          <p>
            <strong>Monat &amp; Jahr:</strong>
            {{ filteredItems[selectedIndex].month
              ? filteredItems[selectedIndex].month + ' '
              : '' }}{{ filteredItems[selectedIndex].year }}
          </p>
          <p>
            <strong>Maße:</strong>
            {{ filteredItems[selectedIndex].dimensions }}
          </p>
          <p>
            <strong>Verfügbarkeit:</strong>
            {{ currentAvailability }}
          </p>

          <!-- Teilen / Interesse -->
          <div class="share-container">
            <a
              :href="mailtoLink"
              class="share-link"
              title="Teilen / Interesse bekunden"
            >
              📧 Ich bin interessiert
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Gallery',
  data() {
    return {
      galleryItems: [] as {
        src: string;
        title: string;
        material: string;
        month: string | null;
        year: number;
        dimensions: string;
        availability: string;
      }[],
      filteredMaterial: '',         // Kategorie-Filter
      selectedIndex: null as number | null,
      isMobile: window.innerWidth <= 768,
    };
  },
  computed: {
    /* Filtered Items nach Material */
    filteredItems() {
      if (this.filteredMaterial) {
        return this.galleryItems.filter((item) => item.material === this.filteredMaterial);
      }
      return this.galleryItems;
    },
    /* "verfügbar" / "nicht verfügbar" */
    currentAvailability(): string | null {
      if (this.selectedIndex === null) {
        return null;
      }
      const currentItem = this.filteredItems[this.selectedIndex];
      if (!currentItem) {
        return null;
      }
      // Wir schauen nur, ob "_x" oder "_x_S" im Dateinamen ist:
      if (currentItem.src.includes('_x') || currentItem.src.includes('_x_S')) {
        return 'nicht verfügbar';
      }
      return 'verfügbar';
    },
    /* Eindeutige Materialien -> Buttons / Dropdown */
    uniqueMaterials() {
      const allMats = this.galleryItems.map((item) => item.material);
      return [...new Set(allMats)];
    },
    /* Erzeugt Mailto-Link mit /work?work=... */
    mailtoLink(): string {
      if (this.selectedIndex === null) {
        return '#';
      }
      const currentItem = this.filteredItems[this.selectedIndex];
      if (!currentItem) {
        return '#';
      }
      // Du kannst die Domain anpassen:
      const shareUrl = window.location.origin + `/work?work=${encodeURIComponent(currentItem.title)}`;
      const subject = `Interesse an: ${currentItem.title}`;
      const body =
        'Hallo,\n\n' +
        `ich interessiere mich für "${currentItem.title}".\n` +
        `Link: ${shareUrl}\n\n` +
        'Viele Grüße!';

      return `mailto:jo.haerterich@gmx.de?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    },
  },
  async created() {
    // Galerie laden
    await this.loadGallery();

    // Prüfe direkt, ob URL ein ?work=... hat -> popup öffnen
    const params = new URLSearchParams(window.location.search);
    const requestedWork = params.get('work');
    if (requestedWork) {
      // Warte etwas, bis galleryItems gefüllt sind
      // (hier als einfache Lösung. Alternativ nach loadGallery() an passender Stelle)
      setTimeout(() => {
        this.openPopupByTitle(requestedWork as string);
      }, 300);
    }

    // Resize-Listener
    window.addEventListener('resize', this.checkIfMobile);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.checkIfMobile);
  },
  methods: {
    /* Galerie-Dateien per AJAX abholen */
    async loadGallery() {
      try {
        const response = await fetch('/gallery.php', {
          cache: 'force-cache',
        });
        if (!response.ok) {
          throw new Error(`HTTP-Fehler: ${response.status}`);
        }

        // Serverseitig gefilterte Liste korrekter Dateinamen
        const files: string[] = await response.json();
        console.log('API-Antwort:', files);

        // Wir setzen das Array
        this.galleryItems = files.map((filename) => {
          // Pfad zum Bild
          const src = `/gallery/${encodeURIComponent(filename)}`;
          const decodedFilename = decodeURIComponent(filename);

          // Auseinandernehmen in 4 Teile: title, material, datePart, dimensionsPart
          // => Server garantiert: min. 4 Teile und plausibles Jahr
          const parts = decodedFilename.split('_');
          const [titlePart, material, datePart, dimensionsPart] = parts;

          // Datum
          let month: string | null = null;
          let yearStr: string;
          if (datePart.includes('-')) {
            const splitted = datePart.split('-');
            month = splitted[0];
            yearStr = splitted[1];
          } else {
            // nur Jahr
            // -> Dateiendung weg
            yearStr = datePart.replace(/\.\w+$/, '');
          }
          // Maße z.B. "30-40.png" -> "30-40" -> "30x40"
          const dimensionClean = dimensionsPart.replace(/\.\w+$/, '').replace('-', 'x');

          // Optional: availability
          const availability = filename.includes('_x.') ? 'Sold' : 'Available';

          return {
            src,
            title: titlePart.replace(/-/g, ' '),
            material,
            month: month ? this.translateMonth(month) : null,
            year: parseInt(yearStr, 10),
            dimensions: dimensionClean,
            availability,
          };
        });
      } catch (error) {
        console.error('Fehler beim Laden der Galerie:', error);
      }
    },

    /* Hilfsfunktion: Deutsche Monatsnamen */
    translateMonth(month: string): string {
      const months: Record<string, string> = {
        Januar: 'Januar',
        Februar: 'Februar',
        März: 'März',
        April: 'April',
        Mai: 'Mai',
        Juni: 'Juni',
        Juli: 'Juli',
        August: 'August',
        September: 'September',
        Oktober: 'Oktober',
        November: 'November',
        Dezember: 'Dezember',
      };
      return months[month] || month;
    },

    /* Filter zurücksetzen */
    resetFilter() {
      this.filteredMaterial = '';
    },
    /* Beim Klick auf Material-Button */
    handleCategoryClick(material: string) {
      this.filteredMaterial = material;
    },
    /* Dropdown in Mobile */
    handleDropdownChange(event: Event) {
      const target = event.target as HTMLSelectElement;
      this.filteredMaterial = target.value;
    },

    /* Popup öffnen + URL /work?work=... setzen */
    openPopup(index: number) {
      this.selectedIndex = index;
      const item = this.filteredItems[index];
      if (item) {
        const encodedTitle = encodeURIComponent(item.title);
        window.history.pushState({}, '', `/work?work=${encodedTitle}`);
      }
    },
    /* Popup per Title öffnen (z.B. beim Seitenaufruf mit ?work=...) */
    openPopupByTitle(workTitle: string) {
      const decoded = decodeURIComponent(workTitle);
      const foundIndex = this.filteredItems.findIndex((item) => item.title === decoded);
      if (foundIndex >= 0) {
        this.openPopup(foundIndex);
      }
    },

    closePopup() {
      this.selectedIndex = null;
      window.history.pushState({}, '', '/work'); // Zurück zu /work ohne ?-Parameter
    },

    prevImage() {
      if (this.selectedIndex !== null) {
        this.selectedIndex =
          (this.selectedIndex - 1 + this.filteredItems.length) %
          this.filteredItems.length;
        this.syncUrlWithCurrentIndex();
      }
    },
    nextImage() {
      if (this.selectedIndex !== null) {
        this.selectedIndex =
          (this.selectedIndex + 1) % this.filteredItems.length;
        this.syncUrlWithCurrentIndex();
      }
    },
    /* Aktualisiert die URL beim Vor/Zurückblättern */
    syncUrlWithCurrentIndex() {
      if (this.selectedIndex === null) return;
      const item = this.filteredItems[this.selectedIndex];
      if (!item) return;
      const encodedTitle = encodeURIComponent(item.title);
      window.history.pushState({}, '', `/work?work=${encodedTitle}`);
    },

    checkIfMobile() {
      this.isMobile = window.innerWidth <= 768;
    },
  },
});
</script>

<style scoped>
/* ----------------------------------
   Popup-Navigation (Vor/Zurück)
---------------------------------- */
.prev-button,
.next-button {
  background: none;
  border: none;
  font-size: 2rem;
  color: black;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  padding: 0 20px;
  z-index: 10;
}

.prev-button {
  left: -40px;
}

.next-button {
  right: -40px;
}

.prev-button:hover,
.next-button:hover {
  color: red;
}

/* Damit Bild und Buttons sich nicht überlappen, 
   haben wir .popup-image unten max-width/height */
.popup-content {
  position: relative;
  padding: 20px;
  display: flex;
  align-items: center;
}

/* ----------------------------------
   Hauptlayout
---------------------------------- */
html, body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  height: 100%;
  overflow-y: auto;
}

.main-container {
  display: flex;
  align-items: flex-start;
  margin: 0;
  height: auto;
}

/* ----------------------------------
   Kategorien
---------------------------------- */
.categories {
  position: sticky;
  top: 0;
  width: 200px;
  background: white;
  z-index: 10;
  padding: 10px 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.category-button {
  all: unset;
  background: none;
  padding: 10px;
  text-align: center;
  cursor: pointer;
  font-family: Arial, Helvetica, sans-serif, monospace;
  font-size: 1rem;
  transition: 0.2s ease, transform 0.2s ease;
}

.category-button:hover {
  text-decoration: underline;
  transform: translateY(-2px);
}

.category-button:focus {
  text-decoration: underline;
}

/* ----------------------------------
   Galerie
---------------------------------- */
.gallery {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  justify-content: center;
  align-items: start;
}

.gallery-item {
  position: relative;
  display: block;
}

.gallery-item img {
  width: 100%;
  height: auto;
  display: block;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.gallery-item:hover img {
  transform: scale(1.05);
}

/* Hover-Info */
.hover-info {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.gallery-item:hover .hover-info {
  opacity: 1;
}

/* ----------------------------------
   Popup
---------------------------------- */
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.popup-content {
  background: white;
  border-radius: 10px;
  max-width: 800px;
  width: 90%;
  display: flex;
  gap: 20px;
  padding: 20px;
  position: relative;
  box-sizing: border-box;
}

.popup-image {
  width: auto;       /* Keine Breitenlimitierung */
  max-width: 40vw;  /* Schutz gegen Bildschirmüberlauf */
  height: auto;
  max-height: 80vh;  /* Erlaubt vertikale Ausdehnung bis 80% der Viewport-Höhe */
  object-fit: contain;

}

.popup-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.close-button {
  /* Button fixieren */
  position: absolute;
  top: 20px;
  right: 20px;
  /* Graues Kästchen entfernen: */
  background: none;
  border: none;
  outline: none;
  color: black;
  cursor: pointer;
  font-size: 1.5rem;
  z-index: 9999;
}

.close-button:hover,
.close-button:focus {
  color: red;
  outline: none;
}
@media (max-width: 768px) {
  .categories {
    position: static;
    width: 100%;
    padding: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .category-button {
    width: calc(50% - 10px);
    text-align: center;
    padding: 10px;
    font-size: 0.9rem; /* Schrift etwas kleiner */
  }

  .gallery {
    grid-template-columns: 1fr;
    gap: 10px;
  }

  .gallery-item {
    width: 100%;
  }

  .popup {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0;
    overflow: hidden; /* Kein Scrollen im Popup */
    width: 100vw;
    height: 100vh;
  }

  .popup-content {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
    background: white;
    padding: 10px;
    position: relative;
    box-sizing: border-box;
    border-radius: 0;
  }

  /* Bild soll größer sein: 60% Höhe statt 50% */
  .popup-image {
    width: 100%;
    max-height: 60%; 
    object-fit: contain;
    margin-bottom: 5px; /* Weniger Abstand nach unten */
  }

  /* Schrift verkleinern, Abstände reduzieren */
  .popup-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    gap: 4px; /* engerer Zeilenabstand */
    padding: 5px;
    box-sizing: border-box;
    text-align: center;
    font-size: 0.9rem; /* Schrift insgesamt kleiner */
  }

  .close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.3rem; /* Button minimal kleiner */
    background: none;
    border: none;
    padding: 5px;
    z-index: 10;
  }

  .prev-button,
  .next-button {
    font-size: 0.9rem; /* Ebenfalls kleiner */
    padding: 8px 16px;
    background: white;
    border-radius: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
  }

  .prev-button {
    order: 1;
    transform: translate3d(30px, 70px, 0px);
  }

  .next-button {
    order: 2;
    transform: translate3d(-30px, 70px, 0px);
  }

  .popup-content nav {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
  }

  .category-dropdown {
    appearance: none;
    background-color: white;
    border-radius: 5px;
    padding: 8px;
    font-family: Arial, Helvetica, sans-serif, monospace;
    font-size: 0.9rem; 
    font-weight: bold;
    color: black;
    cursor: pointer;
    width: 100%;
    text-decoration: underline;
    box-sizing: border-box;
  }
}
</style>