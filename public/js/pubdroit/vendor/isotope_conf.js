$(function(){
    // Conteneur pour isotope
    var $container = $('#content');

    $('#filters').hide();
    $('#link-filter').click(function(event){
        event.preventDefault()
        $('#filters').toggle();
        return false;
    });

    var filters = {
        "Categorie": [],
        "Famille": [],
        "Taille": []
    }
    function UpdateSelector(elem, array) {
        // Prend le selector de ce filtre
        var selector = elem.attr('data-filter');
        // Si c'est un show all
        if(selector == '*') {
            // Prend la class de l'élement
            var my_class = elem.attr("class").split(' ')[0];
            // Retire la class "actif" de tout les liens de ce type de filtre
            $('#filters a.' + my_class).parent('li').removeClass('actif');
            // Vide le tableau des filtres
            array.length = 0;
            return;
        }
        // S'il est actif
        if(elem.parent('li').hasClass('actif')) {
            var idx = array.indexOf(selector);
            // Si on le trouve dans le tableau
            if(idx != -1) {
                // On le retire
                array.splice(idx, 1); 
            }
        } else {
            // On l'ajotue au tableau
            array.push(selector);
        }
    }
    function FilterClick() {
        var item = $(this);
        // Si c'est sur un li qu'on a cliqué
        if(item.is("li")) {
            // On change le selecteur pour la balise a du li
            item = $(this).find('a');
        }
        // Ajoute ou retire le nouveau selecteur au tableau correspondant
        switch(item.attr("class").split(' ')[0]) {
            case "cat":
            UpdateSelector(item, filters.Categorie);
            break;
            case "fam":
            UpdateSelector(item, filters.Famille);
            break;
            case "tai":
            UpdateSelector(item, filters.Taille);
            break;
        }

        // Construit le selecteur pour le trie
        // .cat_1.cat_2.fam_2, .cat_1.cat_2.fam_2
        var cat_line = filters.Categorie.join('');
        var tab_fam = filters.Famille;
        var tab_tai = filters.Taille;
        // S'il n'y a ni filtre par famille ni par taille
        if(tab_fam.length == 0 && tab_tai.length == 0) {
            // Le selecteur c'est bêtement la catégorie
            var selector = cat_line;
        } else {
            // Tableau de selecteur
            var selector = [];
            // S'il y a un filtre par famille
            if(tab_fam.length > 0) {
                // On parcours toutes les familles
                for(var i=0; i < tab_fam.length; i++) {
                    // Ajoute le selecteur (categorie + famille)
                    selector.push(cat_line + tab_fam[i]);
                }
            }
            // S'il y a un filtre par taille
            if(tab_tai.length > 0) {
                // Crée un tableau temporaire pour les selecteur déjà existant
                var array_tmp = [];
                // S'il n'y a pas de filtre par famille
                if(selector.length == 0) {
                    // On concatène cat_line et la taille
                    array_tmp.push(cat_line);
                } else {
                    // Sinon tout le tableau
                    array_tmp = selector;
                }

                selector = [];
                for(var i=0; i < tab_tai.length; i++) {
                    for(var j=0; j < array_tmp.length; j++) {
                        // Ajoute le selecteur (categorie + famille)
                        selector.push(array_tmp[j] + tab_tai[i]);
                    }
                }
            }
            selector = selector.join(', ');
        }
        if(selector == '') {
            selector = '*';
        }
        //console.log('selector = ' + selector);
        // Applique le filtre
        $container.isotope({ filter: selector });
        // Change le style du lien
        item.parent('li').toggleClass('actif');        
        return false;       
    }
    // filter items when filter link is clicked
    $('#filters a').click(FilterClick);
    $('#filters li').click(FilterClick);

    $('div.block').click(function () {
        // S'il n'a pas encore été ouvert
        if($(this).children('div.product-info').length == 0) {
            // Ferme le/les ancien(s) div déjà ouvert
            $('div.product-info').parent('div.block').click();
            // Récupère les infos
            var id = $(this).attr('id').substring(5);
            var content = produits[id][1];            

            // Largeur actuel + largeur de l'encart + petite marge
            var width = $(this).width() + 110;
            $(this).stop().animate({
                width: width + 'px'
            }, 500, function() {
                // Animation complete.
                // Crée un nouvel élement div pour les infos du produit
                var $newItems = $('<div class="product-info" >' + content + '</div>');
                // Ajoute l'élement
                $(this).append( $newItems );
                // Refait le layout
                $container.isotope('reLayout');
            });
        } else {
            // Largeur actuel - largeur de l'encart - petite marge
            var width = $(this).width() - 110;
            // Fadeout sur le div produit
            $(this).find('div.product-info').fadeOut(300, function() {
                // Animation complete.
                // Animation qui réduit la taille
                $(this).parent("div.block").stop().animate({
                    width: width + 'px'
                }, 500, function() {
                    // Animation complete.
                    // Détache le div du DOM
                    $(this).find('div.product-info').detach();
                    // Refait le layout
                    $container.isotope('reLayout');
                });
            });
        }         
    });
});