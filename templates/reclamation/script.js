const generatePdfBtn = document.querySelector('table tbody tr:last-child button');

generatePdfBtn.addEventListener('click', () => {
  // Créer un nouveau document PDF
  const pdf = new jsPDF();

  // Ajouter un en-tête au document
  pdf.text('Liste des réclamations', 20, 20);

  // Parcourir la liste des réclamations
  for (const reclamation of document.querySelectorAll('table tbody tr')) {
    // Extraire les informations de la réclamation (ID, type, contenu)
    const id = reclamation.querySelector('td:nth-child(1)').textContent;
    const type = reclamation.querySelector('td:nth-child(2)').textContent;
    const contenu = reclamation.querySelector('td:nth-child(3)').textContent;

    // Ajouter les informations de la réclamation au document
    pdf.text(`ID: ${id}`, 20, 30 + i * 10);
    pdf.text(`Type: ${type}`, 40, 30 + i * 10);
    pdf.text(`Contenu: ${contenu}`, 60, 30 + i * 10);
  }

  // Générer et télécharger le PDF
  pdf.download('reclamations.pdf');

  // Imprimer le nom du bouton sur la console
  console.log(generatePdfBtn.id);
});
