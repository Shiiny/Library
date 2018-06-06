$(document).ready(function() {
    // Ajout option nouvelle catégorie
    var list = $('#appbundle_book_category');
    $(list).append( '<option id="newCategory">Nouvelle catégorie</option>' );

    var newCategory = $('#newCategory');

    // Si l'utilisateur choisit nouvelle catégorie
    $(list).on('change', function(){
        if($(newCategory).is(':selected')) {
            // Modale avec input pour saisir le titre de la nouvelle catégorie
            $('#addCategory').modal('show');

            $('#addNewCategory').click(function(){
                var newCategoryTitle = $('#appbundle_category_name').val();
                newCategoryTitle = newCategoryTitle.charAt(0).toUpperCase() + newCategoryTitle.substring(1);

                // On ajoute celle-ci à la liste déroulante et on ferme la modale
                $(list).prepend( '<option selected>' + newCategoryTitle +'</option>' );
                $('#addCategory').modal('hide');
            });
        }
    });
});

// Bouton pour ajouter un prof
$(document).ready(function () {
    var listProf = $('#appbundle_book_author');
    var newProf = $('#newProf');

    // Si l'utilisateur clique sur le bouton
    $(newProf).click(function() {
        // Modale avec input pour saisir le nom et prenom du prof
        $('#addProf').modal('show');

        $('#addNewProf').click(function(){
            // Concatène le nom et prenom puis l'
            var AuthorFirstName = $('#appbundle_addprof_firstName').val();
            var newAuthorFirstName = AuthorFirstName.charAt(0).toUpperCase() + AuthorFirstName.substring(1);
            var AuthorLastName = $('#appbundle_addprof_lastName').val();
            var newAuthorLastName = AuthorLastName.charAt(0).toUpperCase() + AuthorLastName.substring(1)
            newAuthor =  newAuthorFirstName + ' ' + newAuthorLastName;

            // Ajoute à la liste déroulante. Ferme la modale
            $(listProf).prepend( '<option selected>' + newAuthor +'</option>' );
            $('#addProf').modal('hide');
        });
    });
});

// Récupération de la valeur du select pour les roles
$(document).ready(function() {
    $('select').change(function () {
        var input = '#input_' + this.id;
        var optionSelected = 'select#' + this.id + ' option:selected';
        var newRole = $(optionSelected).val();
        $(input).val(newRole);
    });
});

// Masque les messages flash après 3s
$(document).ready(function(){
    setTimeout(function() {
        $('#flash').fadeOut('slow');
    }, 3000); // <-- time in milliseconds
});

// Applique une rotation au chevron de la sidebar
$(document).ready(function() {
    let chevrons = document.querySelectorAll('.cs__sidebar__accordion__title')

    for( let i = 0; i < chevrons.length; i++) {
        chevrons[i].addEventListener('click', function () {
            let child = [].slice.call(chevrons[i].children)
            if (child[0].style.transform === 'rotate(180deg)') {
                child[0].style.transform = 'rotate(0deg)'
            }
            else {
                child[0].style.transform = 'rotate(180deg)'
            }
        })
    }
});
