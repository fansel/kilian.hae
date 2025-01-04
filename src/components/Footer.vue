<template>
    <footer>
      <ul>
        <li><router-link to="/contact">KONTAKT</router-link></li>
        <li><router-link to="/impress">IMPRESSUM</router-link></li>
        <li><router-link to="/privacy-policy">DATENSCHUTZERKLÄRUNG</router-link></li>
      </ul>
      <p>Copyright © 2025 Kilian Hä</p>
    </footer>
  </template>
  
  <style scoped>


html, body {
  background-color: #ffffff;
  margin: 0;
  padding: 0;
}
footer {
  position: relative; /* Stellt sicher, dass der Footer über anderen Elementen bleibt */
  z-index: 10; /* Höherer z-index, um Überlagerungen zu vermeiden */
  background-color: #ffffff;
  padding: 20px;
  padding: 20px;
  box-shadow: none;
  border: none;
}

/* Footer */
footer {
  background-color: #ffffff; /* Leichtes Grau als Hintergrund */
  text-align: center; /* Zentrierter Text */
  padding: 10px 20px; /* Abstand innen */
  width: 100%; /* Füllt die Breite */
  font-size: 0.9rem; /* Kleinere Schriftgröße */
  margin-bottom: auto;
  position: relative;
}
footer ul {
  list-style: none;
  display: flex;
  justify-content: center;
  gap: 20px;
  box-shadow: none;
  border: none;
  background-color: #ffffff;
  padding: 0;
  margin: 0;
}

footer a,
footer router-link {
  text-decoration: none;
  color: inherit;
  background-color: #ffffff;
  box-shadow: none;
  border: none;
  pointer-events: auto; /* Sicherstellen, dass Links klickbar sind */
}

/* Mobile Ansicht */
@media (max-width: 768px) {
  footer ul {
    gap: 10px;
  }

  footer a,
  footer p,
  footer router-link {
    background-color: #ffffff;
    font-size: 0.6rem; /* Etwas kleinere Schriftgröße für Mobilgeräte */
  }

  footer p {
    font-size: 0.5rem; /* Copyright verkleinern */
  }
}




  </style>
  