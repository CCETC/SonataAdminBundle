
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



tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : ",advimage,iespell,inlinepopups,insertdatetime,paste",

        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,image,|,forecolor,backcolor",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_resizing : true

  
});