

$(document).ready(function() {
    $('a#hiddenFiltersToggle').live("click", function(e){
        e.preventDefault();

        $('div.hiddenFilters').show();
        $(this).hide();

    }); 
    
    // fancy style hack for checkboxes on forms
    $('div.sonata-ba-form input[type="checkbox"]').parent().css('float', 'left');
    $('div.sonata-ba-form input[type="checkbox"]').parent().siblings().css({'float' : 'left', 'margin-right' : '5px'});

});
