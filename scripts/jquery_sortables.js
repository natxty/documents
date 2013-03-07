
$(function() {
    $( "#sortable" ).sortable({
        update: function() {
            var order = $('#sortable').sortable('serialize');
            $.ajax({
               type: "POST",
               url: "/padmin/scripts/ajax/save_sort.php",
               data: 'auth=ZG5IxGRGtwlLTNfW&' + order,
               success: function(response){
                 //alert(response);
                 if (response != 'Success') { alert('Save Sort Error! ' + response); }
               }
            });
        }
    });
    $( "#sortable" ).disableSelection();
});
