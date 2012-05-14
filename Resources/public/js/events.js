

$(document).ready(function() {
    $('a#extraFiltersToggle').live("click", function(e){
        e.preventDefault();

        $('div.extraFilters').show();
        $(this).hide();

    }); 
    
}); 

tinyMCE.init({
        // General options
        mode : "specific_textareas",
        editor_selector : "tinymce",
        theme : "advanced",
        plugins : "advimage,iespell,inlinepopups,insertdatetime,paste",

        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,hr,|,outdent,indent,|,undo,redo,|,link,unlink,image,|,forecolor,backcolor",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true
});