$(document).ready(function() {
       $('.expanded_select_all_checkbox').change(function(){
           $(this).parent().siblings('ul.inputs-list').find("label input[type='checkbox']").attr('checked', $(this).is(':checked'));
       });
       $('.expanded_select_all_checkbox_no_ul').change(function(){
           $(this).parent().siblings().last().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
       });

       $('.filter-results-toggle').live('click', function(e) {
           e.preventDefault();
           $(this).hide();
           $('tr.filter-container.inactive').show();
           $('table.filter-table').show();
           $('#submit-filters').show();
           $('.filter-results-cancel').show();           
       });
       $('.filter-results-cancel').live('click', function(e) {
           e.preventDefault();
           $(this).hide();
           $('table.filter-table').hide();
           $('#submit-filters').hide();
           $('.filter-results-toggle').show();
       });
       $('.more-filters-toggle').live('click', function(e) {
           e.preventDefault();
           $(this).hide();
           console.log($('tr.filter-container.inactive'));
           $('tr.filter-container.inactive').show();
           $('.more-filters-cancel').show();
       });
       $('.more-filters-cancel').live('click', function(e) {
           e.preventDefault();
           $(this).hide();
           $('tr.filter-container.inactive').hide();
           $('.more-filters-toggle').show();
       });
}); 