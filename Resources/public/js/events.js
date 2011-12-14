

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

tinyMCE.init({
        // General options
        mode : "specific_textareas",
        editor_selector : "tinymce",
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