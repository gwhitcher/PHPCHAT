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