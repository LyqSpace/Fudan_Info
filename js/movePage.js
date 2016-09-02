;
$(document).ready(function () {
    var w = window

    var onLeaveFn = null
    var initialPos = 0
    var $section = $('.mp-section')
    var $slides = $('.mp-slide')

    if (localStorage && localStorage.getItem) {
        var item = localStorage.getItem('movepage-' + w.location.href.pathname)
        if (item !== null) {
            initialPos = +item
        }
    }

    function init() {
        moveTo(initialPos, true)
    }

    function moveTo(index, noAnimate) {
        if (onLeaveFn) {
            onLeaveFn(initialPos, index)
        }

        if (!noAnimate) {
            $section.addClass('animate')
        }

        $slides.css('transform', 'translate3d(-' + (index * 100) + '%, 0, 0)')
        $slides.css('-o-transform', '-o-translate3d(-' + (index * 100) + '%, 0, 0)')
        $slides.css('-ms-transform', '-ms-translate3d(-' + (index * 100) + '%, 0, 0)')
        $slides.css('-moz-transform', '-moz-translate3d(-' + (index * 100) + '%, 0, 0)')
        $slides.css('-webkit-transform', '-webkit-translate3d(-' + (index * 100) + '%, 0, 0)')

        if (localStorage && localStorage.setItem) {
            localStorage.setItem('movepage-' + w.location.href.pathname, index)
        }

        initialPos = index
    }

    function onLeave(fn) {
        onLeaveFn = fn
    }

    w.mp = {
        init: init,
        moveTo: moveTo,
        onLeave: onLeave
    }

})
