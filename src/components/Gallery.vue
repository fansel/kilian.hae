<template>
  <div class="main-container">
    <!-- Kategorien -->
    <div class="categories">
      <!-- Dropdown for Mobile -->
      <select class="category-dropdown" @change="handleDropdownChange" v-if="isMobile">
        <option value="">ALLE</option>
        <option v-for="(material, index) in uniqueMaterials" :key="index" :value="material">
          {{ material }}
        </option>
      </select>

      <!-- Buttons for Desktop -->
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
        <img :src="item.src" :alt="item.title" />
        <div class="hover-info">
          <p class="title">{{ item.title }}</p>
          <p class="details">{{ item.month ? item.month + ' ' : '' }}{{ item.year }}</p>
          <p class="details">{{ item.dimensions }}</p>
        </div>
      </div>
    </div>

    <!-- Popup -->
    <div v-if="selectedIndex !== null" class="popup">
      <div class="popup-content">
        <button class="close-button" @click="closePopup">X</button>
        <button class="prev-button" @click="prevImage">&lt;</button>
        <img
          :src="filteredItems[selectedIndex].src"
          :alt="filteredItems[selectedIndex].title"
          class="popup-image"
        />
        <button class="next-button" @click="nextImage">&gt;</button>
        <div class="popup-details">
          <h2>{{ filteredItems[selectedIndex].title }}</h2>
          <p><strong>Material:</strong> {{ filteredItems[selectedIndex].material }}</p>
          <p>
            <strong>Monat & Jahr:</strong>
            {{ filteredItems[selectedIndex].month ? filteredItems[selectedIndex].month + ' ' : '' }}{{ filteredItems[selectedIndex].year }}
          </p>
          <p><strong>Maße:</strong> {{ filteredItems[selectedIndex].dimensions }}</p>
          <p><strong>Verfügbarkeit:</strong> {{ currentAvailability }}</p>
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
      filteredMaterial: '', // Currently filtered category
      selectedIndex: null as number | null, // Index of the current image
      isMobile: window.innerWidth <= 768, // Determine if the device is mobile
    };
  },
  computed: {
    filteredItems() {
      return this.filteredMaterial
        ? this.galleryItems.filter((item) => item.material === this.filteredMaterial)
        : this.galleryItems;
    },
    currentAvailability(): string | null {
      if (this.selectedIndex === null) {
        console.error('No selected index (selectedIndex is null)');
        return null;
      }

      const currentItem = this.filteredItems[this.selectedIndex];
      if (!currentItem) {
        console.error('No element in filteredItems for selectedIndex:', this.selectedIndex);
        return null;
      }

      // Check availability based on the filename
      return currentItem.src.endsWith('_x.jpg') || currentItem.src.endsWith('_x_S.jpg') 
  ? 'not available' 
  : 'available';

    },
    uniqueMaterials() {
      return [...new Set(this.galleryItems.map((item) => item.material))];
    },
  },
  created() {
    this.loadGallery();
    window.addEventListener('resize', this.checkIfMobile);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.checkIfMobile);
  },
  methods: {
    async loadGallery() {
      try {
        const response = await fetch('/gallery.php'); // Your PHP endpoint
        if (!response.ok) {
          throw new Error(`HTTP error: ${response.status}`);
        }

        const files: string[] = await response.json();
        console.log('API Response:', files);

        this.galleryItems = files.map((filename) => {
          const src = `/gallery/${filename}`;
          const [titlePart, material, datePart, dimensionsPart] = filename.split('_');
          const [month, year] = datePart.includes('-')
            ? datePart.split('-')
            : [null, datePart.replace(/\.\w+$/, '')]; // Remove file extension
          const dimensions = dimensionsPart.replace(/\.\w+$/, '').replace('-', 'x');
          const availability = filename.includes('_x.') ? 'Sold' : 'Available';

          return {
            src,
            title: titlePart.replace(/-/g, ' '),
            material,
            month: month ? this.translateMonth(month) : null,
            year: parseInt(year, 10),
            dimensions,
            availability,
          };
        });

        console.log('Gallery Items:', this.galleryItems);
      } catch (error) {
        console.error('Error loading the gallery:', error);
      }
    },
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
    resetFilter() {
      this.filteredMaterial = '';
    },
    handleCategoryClick(material: string) {
      this.filteredMaterial = material;
    },
    handleDropdownChange(event: Event) {
      const target = event.target as HTMLSelectElement;
      this.filteredMaterial = target.value;
    },
    openPopup(index: number) {
      this.selectedIndex = index;
    },
    closePopup() {
      this.selectedIndex = null;
    },
    prevImage() {
      if (this.selectedIndex !== null) {
        this.selectedIndex =
          (this.selectedIndex - 1 + this.filteredItems.length) % this.filteredItems.length;
      }
    },
    nextImage() {
      if (this.selectedIndex !== null) {
        this.selectedIndex = (this.selectedIndex + 1) % this.filteredItems.length;
      }
    },
    checkIfMobile() {
      this.isMobile = window.innerWidth <= 768;
    },
  },
});
</script>

  
  





<style scoped>
/* Popup Navigation Buttons */
.prev-button,
.next-button {
  background: none;
  border: none;
  font-size: 2rem;
  color: black; /* Buttons sind jetzt schwarz */
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  padding: 0 20px; /* Abstand vom Bildrand */
  z-index: 10;
}

.prev-button {
  left: -40px; /* Links vom Bild positionieren */
}

.next-button {
  right: -40px; /* Rechts vom Bild positionieren */
}

.prev-button:hover,
.next-button:hover {
  color: red; /* Hover-Effekt für bessere Sichtbarkeit */
}

/* Zusätzlicher Schutz, falls das Bild kleiner als erwartet ist */
.popup-content {
  position: relative;
  padding: 20px;
  display: flex;
  align-items: center;
}


/* Hauptlayout */
html, body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  height: 100%;
  overflow-y: auto; /* Scrollen auf der gesamten Website */
}

/* Main Container */
.main-container {
  display: flex; /* Flexbox-Layout für nebeneinanderstehende Elemente */
  align-items: flex-start; /* Elemente oben ausrichten */
  margin: 0;
  height: auto; /* Scrollhöhe dynamisch */
}

/* Kategorienleiste */
.categories {
  position: sticky; /* Fixiert beim Scrollen */
  top: 0; /* Direkt am oberen Rand */
  width: 200px; /* Feste Breite */
  background: white;
  z-index: 10;
  padding: 10px 20px;
  display: flex;
  flex-direction: column; /* Buttons untereinander */
  gap: 10px; /* Abstand zwischen Buttons */
}

/* Buttons */
.category-button {
  all: unset; /* Entfernt alle globalen Button-Stile */
  background: none;
  padding: 10px;
  text-align: center;
  cursor: pointer;
  font-family:  Arial, Helvetica, sans-serif, monospace;
  font-size: 1rem;
  transition:  0.2s ease, transform 0.2s ease;
}

.category-button:hover {
text-decoration: underline;
  transform: translateY(-2px);
}

.category-button:focus {
    text-decoration: underline;
}

/* Galerie */
.gallery {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* Genau 3 Spalten */
  gap: 20px; /* Abstand zwischen den Galerie-Elementen */
  justify-content: center; /* Optional: Zentriert die Galerie */
  align-items: start; /* Richtet Elemente oben aus */
}

/* Galerie-Elemente */
.gallery-item {
  position: relative;
  display: block; /* Box passt sich dem Inhalt an */
}

/* Bilder */
.gallery-item img {
  width: 100%; /* Füllt die Breite der Zelle */
  height: auto; /* Beibehaltung des Seitenverhältnisses */
  display: block;
  object-fit: contain; /* Bild wird vollständig angezeigt */
}


.gallery-item:hover img {
  transform: scale(1.05); /* Leichte Vergrößerung beim Hover */
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

/* Popup */
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
}

.popup-image {
  width: 50%;
  border-radius: 10px;
  margin-top: 50px;
}

.popup-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.close-button {
    position: absolute;
    background-color: #fff;
    top: -20px; /* Minimal über dem Bild positioniert */
    right: 10px;
    font-size: 1.5rem;
    border: none; /* Optional: Rahmen für bessere Sichtbarkeit */
  }

.close-button:hover {
  color: red;
}

/* Hauptlayout */
html, body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  height: 100%;
  overflow-y: auto;
}

/* Mobile Media Query */
@media (max-width: 768px) {
  /* Kategorienleiste */
  .categories {
    position: static; /* Kein Sticky auf mobilen Geräten */
    width: 100%; /* Volle Breite */
    padding: 10px;
    display: flex;
    flex-wrap: wrap; /* Kategorien umbrechen */
    gap: 10px;
  }

  .category-button {
    width: calc(50% - 10px); /* Zwei Buttons pro Reihe */
    text-align: center;
    padding: 10px;
    font-size: 1rem;
  }

  /* Galerie */
  .gallery {
    grid-template-columns: 1fr; /* Nur eine Spalte */
    gap: 10px;
  }

  .gallery-item {
    width: 100%;
  }

/* Mobile Media Query */
@media (max-width: 768px) {
    /* Popup */
    .popup {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0;
    overflow: hidden; /* Kein Scrollen im Popup */
    width: 100vw; /* Vollbildbreite */
    height: 100vh; /* Vollbildhöhe */
  }

  .popup-content {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
    background: white;
    padding: 10px;
    position: relative;
    box-sizing: border-box; /* Padding wird in die Größe einberechnet */
  }

  .popup-image {
    width: 100%;
    max-height: 50%; /* Bild nimmt maximal die obere Hälfte ein */
    object-fit: contain; /* Bild wird vollständig angezeigt */
    margin-bottom: 10px;
  }

  .popup-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-around; /* Fakten gleichmäßig verteilen */
    align-items: center;
    padding: 10px;
    box-sizing: border-box; /* Padding wird in die Größe einberechnet */
    text-align: center;
  }

  .close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.5rem;
    background: none;
    border: none;
    padding: 5px;
    z-index: 10;
  }

 /* Navigationsbuttons */
 nav {
    margin-top: auto; /* Buttons ganz unten */
    display: flex;
    justify-content: center;
    gap: 10px; /* Abstand zwischen den Buttons */
  }

  .prev-button,
  .next-button {
    font-size: 1rem;
    padding: 10px 20px;
    background: white;
    border-radius: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
  }

  /* Zurück und Weiter nebeneinander */
  .prev-button {
    order: 1;
  }

  .next-button {
    order: 2;
  }
  /* Zurück und Weiter nebeneinander, leicht ins Bild gerückt */
  .prev-button {
    transform: translate3d(40px,70px,0px); /* Rückt den Zurück-Button etwas ins Bild */
  }

  .next-button {
    transform: translate3d(-40px,70px,0px);

  }

  .popup-content nav {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
  }
}

.category-dropdown {
  appearance: none; /* Entfernt allgemeine Standard-Styles */
  background-color: white; /* Weißer Hintergrund */
  border-radius: 5px; /* Abgerundete Ecken */
  padding: 10px; /* Innenabstand */
  font-family: Arial, Helvetica, sans-serif, monospace; /* Konsistente Schrift */
  font-size: 1rem; /* Schriftgröße */
  font-weight: bold; /* Fettschrift */
  color: black; /* Schwarzer Text */
  cursor: pointer; /* Hand-Cursor */
  width: 100%; /* Volle Breite für Mobilgeräte */
  text-decoration: underline;
  box-sizing: border-box; /* Padding wird in die Breite einbezogen */
}
}

</style>