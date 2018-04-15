$(document).ready(function() {

    //Ajout option nouvelle catégorie
    var list = $('#appbundle_book_category');
    $(list).append( '<option id="newCategory">Nouvelle catégorie</option>' );
    console.log(list);

    var newCategory = $('#newCategory');

    //Si l'utilisateur choisit nouvelle catégorie
    $(list).on('change', function(){
        if($(newCategory).is(':selected')) {
            //Modale avec input pour saisir le titre de la nouvelle catégorie
            $('#addCategory').modal('show');

            $('#addNewCategory').on('click', function(){
                var newCategoryTitle = $('#appbundle_category_name').val();
                console.log(newCategoryTitle);
                newCategoryTitle = newCategoryTitle.charAt(0).toUpperCase() + newCategoryTitle.substring(1);

                //On ajoute celle-ci à la liste déroulante et on ferme la modale
                $(list).prepend( '<option selected>' + newCategoryTitle +'</option>' );
                $('#addCategory').modal('hide');
            });
        }
    });
});
