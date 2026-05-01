// Variables globales
var FRAIS_DOSSIER = 29;
var SEUIL_FRAIS_OFFERT = 1500;
var CODES_PROMO = {
    'BTS2026': 0.10,    // 10% de remise
    'WELCOME': 0.05     // 5% de remise
};
var remiseAppliquee = 0;

// Fonction qui recalcule tout le total
function recalculerTotal() {
    var nb = parseInt(document.getElementById('nb_personnes').value);
    var prixBase = parseFloat(document.getElementById('recapTotal').getAttribute('data-prix'));
    var sousTotal = nb * prixBase;
    var frais = (sousTotal >= SEUIL_FRAIS_OFFERT) ? 0 : FRAIS_DOSSIER;
    var remise = sousTotal * remiseAppliquee;
    var total = sousTotal + frais - remise;

    // Mise à jour du DOM
    document.getElementById('recapNb').textContent = nb;
    document.getElementById('recapSousTotal').textContent = formaterPrix(sousTotal);
    document.getElementById('recapFrais').textContent = (frais === 0) ? 'Offerts' : frais + ' €';
    document.getElementById('recapTotalFinal').textContent = formaterPrix(total);

    // Affichage de la ligne remise si applicable
    var ligneRemise = document.getElementById('ligneRemise');
    if (remise > 0) {
        ligneRemise.style.display = 'flex';
        document.getElementById('recapRemise').textContent = '-' + formaterPrix(remise);
    } else {
        ligneRemise.style.display = 'none';
    }
}

// Vérification du code promo
function verifierCodePromo() {
    var code = document.getElementById('code_promo').value.toUpperCase().trim();
    var msg = document.getElementById('messagePromo');

    if (code === '') {
        remiseAppliquee = 0;
        msg.textContent = '';
        msg.className = 'message-promo';
    } else if (CODES_PROMO[code] !== undefined) {
        remiseAppliquee = CODES_PROMO[code];
        msg.textContent = '✓ Code valide ! Remise de ' + (remiseAppliquee * 100) + '%';
        msg.className = 'message-promo valide';
    } else {
        remiseAppliquee = 0;
        msg.textContent = '✗ Code promo invalide';
        msg.className = 'message-promo invalide';
    }

    recalculerTotal();
}

// Formatage du prix avec espace pour les milliers
function formaterPrix(montant) {
    return Math.round(montant).toLocaleString('fr-FR') + ' €';
}
