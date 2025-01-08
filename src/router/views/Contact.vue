<template>
  <div class="contact-container">
    <h1 class="hidden-title">Kontakt</h1>
    <p class="email">
      <a href="mailto:mailme@kunst.de">
        jo.haerterich<br />@gmx.de
      </a>
    </p>
  </div>
</template>

<script>
export default {
  name: "Contact",
};
</script>

<style>


.hidden-title {
  position: absolute;
  top: -1000px; /* Unsichtbar, nur für Semantik */
}

.contact-container {
  display: flex; /* Flexbox aktivieren */
  justify-content: center; /* Horizontal zentrieren */
  align-items: center; /* Vertikal zentrieren */ 
  background: white;
  font-family: Arial, Helvetica, sans-serif, monospace;
  padding-bottom: 20px; /* Platz für den Footer */
}


.email {
  font-size: 3rem; /* Große Schrift */
  text-align: center;
  line-height: 1.2;
  letter-spacing: 0.1rem;
  margin-bottom: auto;
}

.email a {
  color: black;
  text-decoration: none;
  display: inline-block;
  transition: color 0.3s ease, transform 0.3s ease;
}

.email a:hover {
  color: gray;
  transform: scale(1.08); /* Leichte Vergrößerung beim Hover */
}

/* Mobile Ansicht */
@media (max-width: 768px) {
  .email {
    font-size: 2.5rem; /* Schriftgröße für Mobilgeräte */
    margin-bottom: auto;
  }
}

</style>

