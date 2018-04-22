$(document).ready(function() {
    //Ajout option nouvelle catégorie
    var list = $('#appbundle_book_category');
    $(list).append( '<option id="newCategory">Nouvelle catégorie</option>' );

    var newCategory = $('#newCategory');

    //Si l'utilisateur choisit nouvelle catégorie
    $(list).on('change', function(){
        if($(newCategory).is(':selected')) {
            //Modale avec input pour saisir le titre de la nouvelle catégorie
            $('#addCategory').modal('show');

            $('#addNewCategory').click(function(){
                var newCategoryTitle = $('#appbundle_category_name').val();
                console.log(newCategoryTitle);
                newCategoryTitle = newCategoryTitle.charAt(0).toUpperCase() + newCategoryTitle.substring(1);

                //On ajoute celle-ci à la liste déroulante et on ferme la modale
                $(list).prepend( '<option selected>' + newCategoryTitle +'</option>' );
                $('#addCategory').modal('hide');
            });
        }
    });
    //Ajout option nouvelle catégorie
    var listProf = $('#appbundle_book_author');
    var prof = $('#prof');
    $(prof).append( '<button id="newProf">Nouveau professeur</button>' );

    var newProf = $('#newProf');

    //Si l'utilisateur choisit nouvelle catégorie
        $(newProf).click(function() {
            //Modale avec input pour saisir le titre de la nouvelle catégorie
            $('#addProf').modal('show');

            $('#addNewProf').click(function(){
                var AuthorFirstName = $('#appbundle_addprof_firstName').val();
                var newAuthorFirstName = AuthorFirstName.charAt(0).toUpperCase() + AuthorFirstName.substring(1);
                var AuthorLastName = $('#appbundle_addprof_lastName').val();
                var newAuthorLastName = AuthorLastName.charAt(0).toUpperCase() + AuthorLastName.substring(1)
                newAuthor =  newAuthorFirstName + ' ' + newAuthorLastName;

                //On ajoute celle-ci à la liste déroulante et on ferme la modale
                $(listProf).prepend( '<option selected>' + newAuthor +'</option>' );
                $('#addProf').modal('hide');
            });
        });
});

// Récupération de la valeur du select pour les roles
$('select').change(function(){
    var input = '#input_' + this.id;
    var optionSelected = 'select#' + this.id + ' option:selected';
    var newRole = $(optionSelected).val();
    $(input).val(newRole);
});