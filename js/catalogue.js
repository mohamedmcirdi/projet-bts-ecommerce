// Fonction de filtrage des voyages par budget
// Cette fonction est appelée quand l'utilisateur change la valeur du select #filtreBudget

function filtrerDestinations() {
    var budget = document.getElementById('filtreBudget').value;
    var cartes = document.querySelectorAll('.card');
    var nbVisibles = 0;

    cartes.forEach(function(carte) {
        var prix = parseInt(carte.getAttribute('data-prix'));

        if (budget === 'tous') {
            carte.style.display = 'block';
            nbVisibles++;
        } else if (prix <= parseInt(budget)) {
            carte.style.display = 'block';
            nbVisibles++;
        } else {
            carte.style.display = 'none';
        }
    });

    // Si aucune carte ne correspond, on pourrait afficher un message
    // (à enrichir plus tard avec un message "Aucun voyage trouvé")
    console.log('Voyages visibles : ' + nbVisibles);
}
