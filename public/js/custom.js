
// JavaScript for Back-to-Top button functionality
$(document).ready(function() {
    var backToTopBtn = $('#backToTopBtn');

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            backToTopBtn.fadeIn();
        } else {
            backToTopBtn.fadeOut();
        }
    });

    backToTopBtn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, '300');
    });
    
    // Optional: Smooth scroll for internal links
    $('a[href*="#"]').on('click', function(e) {
        if (this.hash !== "") {
            e.preventDefault();
            var hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 70 // Adjust for fixed header height
            }, 800, function(){
                // window.location.hash = hash; // Optional: update URL hash
            });
        }
    });
});
