// Calcul dynamique du prix total selon le nombre de personnes
// Le prix de base est lu depuis l'attribut data-prix-base de #prixTotal

function calculerPrix() {
    var nbPersonnes = parseInt(document.getElementById('nbPersonnes').value);
    var prixTotal = document.getElementById('prixTotal');
    var prixParPersonne = parseFloat(prixTotal.getAttribute('data-prix-base'));

    var total = nbPersonnes * prixParPersonne;

    // Formatage du nombre avec séparateur de milliers (ex: 1 798 €)
    var totalFormate = total.toLocaleString('fr-FR');

    prixTotal.textContent = 'Total : ' + totalFormate + ' €';
}
