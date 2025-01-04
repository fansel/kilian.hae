<template>
    <div class="impressum">
      <h1>Impressum</h1>
      <p><strong>Angaben gemäß § 5 TMG:</strong></p>
      <p>
        Johannes Härterich<br />
        <br />
        aka. Kilian Hä<br />
        Neustädter Straße 19<br />
        04315 Leipzig<br />
      </p>
      <p>
        <strong>Kontakt:</strong><br />
        E-Mail: jo.haerterich@gmx.de
      </p>
      <p>
        <strong>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV:</strong><br />
        Johannes Härterich<br />
        Neustädter Straße 19<br />
        04315 Leipzig
      </p>
    </div>
  </template>
  
  <script>
  export default {
    name: "Impressum",
  };
  </script>
  
  <style scoped>
  .impressum {
    padding: 20px;
    line-height: 1.6;
  }
  
  h1 {
    font-size: 1.8rem;
    margin-bottom: 10px;
  }
  
  p {
    margin-bottom: 15px;
  }
  
  strong {
    font-weight: bold;
  }
  </style>
  