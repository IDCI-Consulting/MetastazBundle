$(document).ready(function(){
    $('.create_link').click(function(event){

        var link = $(this);
        event.preventDefault();

        $.ajax({
          url: $(this).attr('href'),
          context: document.body,
          success: function(data){
            $('.form_container').append(data);
            link.hide();
          }
        });
    });
});