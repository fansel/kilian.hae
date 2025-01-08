<template>
  <div class="header-container">
    <!-- Schriftzug, führt zur Startseite -->
    <h1>
      <router-link to="/" class="title-link">Kilian Hä.</router-link>
    </h1>

    <!-- Navigation -->
    <header>
      <nav>
        <ul>
          <li><router-link to="/work">WERKE</router-link></li>
          <li><router-link to="/vita">LEBEN</router-link></li>
          <li><router-link to="/contact">KONTAKT</router-link></li>
        </ul>
      </nav>
    </header>
  </div>
</template>

<script>
export default {
  name: "Header",
};
</script>

<style scoped>
/* Container für Header und Schriftzug */
.header-container {
  text-align: center; /* Zentriert den gesamten Inhalt */
  margin-top: 20px; /* Abstand nach oben */
}

/* Schriftzug */
h1 {
  font-size: 4rem; /* Große Schriftgröße */
  margin: 0; /* Kein zusätzlicher Abstand */
  text-transform: uppercase; /* Text in Großbuchstaben */
}

/* Link im Schriftzug */
.title-link {
  text-decoration: none; /* Keine Unterstreichung */
  color: black; /* Standardfarbe */
  transition: transform 0.2s ease, color 0.2s ease; /* Animation beim Hover */
}

.title-link:hover {
  transform: scale(1.1); /* Vergrößern beim Hover */
  color: black; /* Optional: Farbe ändern beim Hover */
}

nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  gap: 30px;
}

/* Header: Navigation zentriert, schlicht und dezent */
header {
  display: flex;
  justify-content: flex-start; /* Links ausgerichtet */
  align-items: center; /* Zentriert innerhalb des Headers */
  margin-left: 10cm; /* Verschiebt den Header-Inhalt 5 cm nach rechts */
  margin-top: 3cm; /* Verschiebt den Header-Inhalt 2 cm nach unten */
}

nav a,
nav .router-link-active {
  text-decoration: none;
  color: black;
  font-size: 1rem;
  font-weight: bold;
  transition: transform 0.2s ease, color 0.2s ease; /* Animation für Hover */
}

nav a:hover,
nav a:focus,
nav .router-link-active:hover {
  text-decoration: underline;
  transform: translateY(-2px); /* Verschiebt den Link leicht nach oben */
}

/* Für Mobilgeräte (maximale Breite 768px) */
@media (max-width: 768px) {
  nav ul {
    flex-direction: column; /* Navigation vertikal anordnen */
    gap: 10px; /* Weniger Abstand zwischen den Links */
    text-align: left; /* Links ausrichten */
    padding: 0;
    margin-left: 20px; /* Abstand von der linken Seite */
  }

  header {
    flex-direction: column; /* Navigation und Schriftzug untereinander */
    align-items: flex-start; /* Navigation links ausrichten */
    margin-left: 10px; /* Abstand von der linken Seite */
    margin-top: 10px; /* Weniger Abstand nach oben */
  }

  h1 {
    text-align: center; /* Schriftzug bleibt zentriert */
    margin-bottom: 20px; /* Abstand zwischen Schriftzug und Menü */
  }
}
</style>
