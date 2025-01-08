<template>
  <main class="home-container">
    <div class="image-container" v-if="homeImage">
      <img :src="homeImage" alt="Startseitenbild" />
    </div>
  </main>
</template>



<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Home',
  data() {
    return {
      homeImage: '', // Pfad zum Startseitenbild
    };
  },
  created() {
    this.loadHomeImage();
  },
  methods: {
    async loadHomeImage() {
      try {
        const response = await fetch('/gallery.php'); // PHP-Endpunkt
        if (!response.ok) {
          throw new Error(`HTTP error: ${response.status}`);
        }

        const files: string[] = await response.json();

        // Finde das erste Bild mit `_S`
        const file = files.find((filename) => filename.includes('_S.'));
        if (file) {
          this.homeImage = `/gallery/${file}`;
        }
      } catch (error) {
        console.error('Fehler beim Laden des Startseitenbildes:', error);
      }
    },
  },
});
</script>

<style>

.home-container {
    display: flex;
    justify-content: center; /* Horizontale Zentrierung */
    align-items: center;    /* Vertikale Zentrierung */
    height: 80vh;           /* Höhe des Hauptbereichs */
    padding: 0;             /* Kein zusätzliches Padding, um Unregelmäßigkeiten zu vermeiden */
    box-sizing: border-box;
  }
  
  .image-container {
    margin-top: auto;
    display: flex;
    justify-content: center; /* Bild innerhalb des Containers zentrieren */
    align-items: center;
    width: 100%;             /* Container auf gesamte Breite setzen */
  }
  
  .image-container img {
    max-width: 50%;          /* Reduzierte Bildbreite */
    height: auto;            /* Seitenverhältnis beibehalten */
    margin: 0;               /* Kein zusätzlicher Abstand */
    border: none;            /* Kein Rahmen */
  }


/* Mobile view */
@media (max-width: 768px) {
  .home-container {
    height: auto;           /* Flexible height */
    padding: 20px;          /* Additional padding for mobile */
  }

  .image-container img {
    max-width: 90%;         /* Adjust image size for smaller screens */
  }
}

/* Very small devices (e.g., smartphones in portrait mode) */
@media (max-width: 480px) {
  .home-container {
    padding: 10px;          /* Less padding for very small devices */
  }

  .image-container img {
    max-width: 100%;        /* Image fills nearly the entire screen */
  }
}

</style>