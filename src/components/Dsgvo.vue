<template>
    <div class="dsgvo">
      <h1>Datenschutzerklärung</h1>
  
      <p>
        Hallo! Ich bin Kilian Hä. und betreibe diese Website, um meine Werke mit dir zu teilen.
        Der Schutz deiner Daten ist mir wichtig, und ich möchte, dass du dich hier wohlfühlst.
      </p>
  
      <h2>1. Verantwortliche Stelle</h2>
      <p>
        Die verantwortliche Stelle für die Verarbeitung deiner personenbezogenen Daten findest du im 
        <router-link to="/impress">Impressum</router-link>.
      </p>
  
      <h2>2. Hosting</h2>
      <p>
        Diese Website wird bei einem externen Hosting-Anbieter betrieben. Der Anbieter speichert
        möglicherweise automatisch technische Informationen wie:
      </p>
      <ul>
        <li>Deine IP-Adresse</li>
        <li>Die Seiten, die du aufrufst</li>
        <li>Den Zeitpunkt deines Besuchs</li>
        <li>Informationen über deinen Browser und dein Betriebssystem</li>
      </ul>
      <p>
        Diese Daten werden ausschließlich vom Hosting-Anbieter verarbeitet und dienen der technischen
        Bereitstellung und Sicherheit der Website. Genauere Informationen findest du in der
        Datenschutzerklärung des Hosting-Anbieters.
      </p>
  
      <h2>3. Google Fonts</h2>
      <p>
        Ich benutze Schriftarten von Google Fonts, aber keine Angst: Sie sind lokal eingebunden. Das
        bedeutet, dass keine Daten an Google übertragen werden, wenn du meine Seite besuchst.
      </p>
  
      <h2>4. Deine Rechte</h2>
      <p>Du hast das Recht:</p>
      <ul>
        <li>Zu wissen, welche Daten über dich gespeichert werden</li>
        <li>Falsche Daten korrigieren zu lassen</li>
        <li>Deine Daten löschen zu lassen, wenn du das möchtest</li>
      </ul>
      <p>
        Wenn du Fragen hast oder von diesen Rechten Gebrauch machen möchtest, schreibe mir eine E-Mail
        an <a href="mailto:info@kilianhae.de">info@kilianhae.de</a>.
      </p>
  
      <h2>5. Änderungen dieser Datenschutzerklärung</h2>
      <p>
        Manchmal muss ich diese Erklärung aktualisieren, zum Beispiel, wenn sich rechtliche
        Anforderungen ändern. Die jeweils aktuelle Version findest du immer hier.
      </p>
    </div>
  </template>
  
  <script>
  export default {
    name: "Dsgvo",
  };
  </script>
  
  <style>
  .dsgvo {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    padding: 20px;
  }
  
  h1 {
    font-size: 2rem;
    margin-bottom: 20px;
  }
  
  h2 {
    font-size: 1.5rem;
    margin-top: 20px;
    margin-bottom: 10px;
  }
  
  p {
    margin-bottom: 15px;
  }
  
  ul {
    margin: 0;
    padding-left: 20px;
  }
  
  li {
    margin-bottom: 5px;
  }
  
  a {
    color: #0073e6;
    text-decoration: none;
  }
  
  a:hover {
    text-decoration: underline;
  }
  </style>
  