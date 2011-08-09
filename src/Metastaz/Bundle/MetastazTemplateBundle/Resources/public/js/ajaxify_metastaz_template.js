$(document).ready(function(){
    $('#new_field_link').click(function(event){

        event.preventDefault();

        $.ajax({
          url: $(this).attr('href'),
          context: document.body,
          success: function(data){
            $('.form_container').append(data).live();
          }
        });
    });
    
    $('.cancel').click(function(event){
        // remove form content
    });
});