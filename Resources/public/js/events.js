
$(document).ready(function() {
    $('a#hiddenFiltersToggle').live("click", function(e){
        e.preventDefault();
       
        if($(this).hasClass('off'))
        {
            $('div.hiddenFilters').show();
            $(this).addClass('on');
            $(this).removeClass('off');
            $(this).html('less filters');
        }
        else
        {
            $('div.hiddenFilters').hide();
            $(this).addClass('off');
            $(this).removeClass('on');
            $(this).html('more filters');             
        }
        
    });
});