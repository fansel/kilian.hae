<template>
  <div id="app" class="main-container">
    <Header />
    <main class="content">
      <router-view />
    </main>
    <Footer />
  </div>
</template>

<script>
import Header from './components/Header.vue';
import Home from './router/views/Home.vue';
import Footer from './components/Footer.vue';

export default {
  components: {
    Header,
    Footer,
    Home,
  },
};
</script>

<style>
/* Grundlegendes Layout */
html, body {
  margin: 0;
  padding: 0;
  height: 100%; /* Höhe des gesamten Viewports */
}

#app.main-container {
  display: flex;
  flex-direction: column; /* Vertikale Anordnung */
  min-height: 100vh; /* Mindestens die Höhe des Bildschirms */
}



.content {
  flex: 1; /* Füllt den verbleibenden Platz */
  padding: 20px; /* Optional: Abstand zum Rand */
}


</style>
