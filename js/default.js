//Reload chat every few seconds
$(document).ready(
    function() {
        setInterval(function() {
            $('#chat_frame').load('index.php' + ' #chat_frame');
        }, 3000); //3 seconds
    });

//Flash message disappear
jQuery(document).ready(function($){
    if('.fadeout-message'){
        setTimeout(function() {
            $('.flash_message').slideUp(1200);
        }, 5000);
    }
});

//Message load ID for form
$(document).ready(
    function() {
        $('#tabs a').click(function() {
            $('#message_id').val(this.id).change();
        })});


//Ajax for messages
jQuery(document).submit(function(e){
    var form = jQuery(e.target);
    if(form.is("#chat_form")){
        e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: form.attr("action"),
            data: form.serialize()
        });
    }
});