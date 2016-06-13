jQuery(function () {
    var $ = jQuery,
        $W = $(window),
        speakersAutoScrollPeriod = 5000, // scroll the speakers this often (msecs)
        speakersCarousel,
        speakersCarouselIndicators;

    /** indicate if the jQuery item is at least partially visible in the view */
    // From http://stackoverflow.com/questions/8981463
    var isScrolledIntoView = function($elem)
    {
        var docViewTop = $W.scrollTop();
        var docViewBottom = docViewTop + $W.height();

        var elemTop = $elem.offset().top;
        var elemBottom = elemTop + $elem.height();

        return (( elemTop >= docViewTop) && (elemTop <= docViewBottom)) ||
            ((elemBottom >= docViewTop) && (elemBottom <= docViewBottom));
    }

    /** Timer callback for speakers auto-scroll */
    var speakersAutoScrollTimer = function() {
        var contentWidth = speakersCarousel.find(".item.active .speaker").width()*3,
            availableWidth = speakersCarousel.width();
        var activeIndicator = speakersCarouselIndicators.children("li.active");

        if (activeIndicator.length &&               // active?
            availableWidth >= contentWidth &&       // wide enough?
            isScrolledIntoView(speakersCarousel) && // scrolled into view?
            !speakersCarousel.is(":hover")) {       // mouse not hovering over it?
            speakersCarouselIndicators.children().get(
                ( activeIndicator.index() + 1 ) %
                speakersCarouselIndicators.children().length
            ).click()                                 // click to advance to the next pane
        }
        window.setTimeout(speakersAutoScrollTimer, speakersAutoScrollPeriod);
    };

    speakersCarousel = $("#speakers-carousel");
    if (speakersCarousel.length) {
        // don't animate on narrow display
        speakersCarouselIndicators = speakersCarousel.children(".carousel-indicators");
        if (speakersCarouselIndicators.children().length > 1) {
            window.setTimeout(speakersAutoScrollTimer, speakersAutoScrollPeriod);
        }
    }

});