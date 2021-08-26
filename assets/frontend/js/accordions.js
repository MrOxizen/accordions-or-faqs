jQuery.noConflict();
(function ($) {

    $('body').find('.oxi-accordions-wrapper').each(function () {
        var _this = $(this), id = _this.attr('id'), _explode = id.split("-"),
                _parent = _explode[3],
                _template = $('.oxi-accordions-ultimate-template-' + _parent),
                _trigger = _template.data('oxi-trigger'),
                _accordions_type = _template.data('oxi-accordions-type'),
                _auto_play = _template.data('oxi-auto-play'),
                _transition = $(".oxi-accordions-content-card-" + _parent).css('transition-duration');

        $(".oxi-accordions-content-card-" + _parent).on("hide.bs.oxicollapse", function (e) {
            $(this).parent(".oxi-accordions-single-card-" + _parent).removeClass("oxi-accordions-expand");
            $(this).children(".oxi-accordions-content-body").removeClass("animate__animated");
            e.stopPropagation();
        });
        $(".oxi-accordions-content-card-" + _parent).on("show.bs.oxicollapse", function (e) {
            $(this).parent(".oxi-accordions-single-card-" + _parent).addClass("oxi-accordions-expand");
            $(this).children(".oxi-accordions-content-body").addClass("animate__animated");
            e.stopPropagation();
        });
        $(".oxi-accordions-content-card-" + _parent).each(function () {
            var This = $(this);
            if (This.hasClass("show")) {
                var animation = This.children(".oxi-accordions-content-body").attr('oxi-animation');
               
                if (typeof animation !== typeof undefined && animation !== false && animation.length > 0) {
                    This.children(".oxi-accordions-content-body").addClass('animate__animated');
                }
                This.parent(".oxi-accordions-single-card-" + _parent).addClass("oxi-accordions-expand");
            }
        });

        if (_trigger === 'auto') {
            _index_number = 0
            var total_accordions = $(".oxi-accordions-single-card-" + _parent).length;
            function autoplay() {
                $(".oxi-accordions-single-card-" + _parent + " .oxi-accordions-header").eq(_index_number).trigger('click');
                _index_number++;
                if (_index_number === total_accordions) {
                    _index_number = 0;
                }
            }
            var interval = setInterval(autoplay, _auto_play);
        }

        if (_trigger === 'hover') {
            $(".oxi-accordions-single-card-" + _parent).on("mouseenter", function () {
                $(this).children(".oxi-accordions-content-card").oxicollapse("show");
            }).on("mouseleave", function () {
                
                $(this).children(".oxi-accordions-content-card").oxicollapse("hide");
            });
        }



    });

    $("div[oxi-animation]").each(function () {
        var animation = $(this).attr('oxi-animation');
        Ths = $(this);
        if (typeof animation !== typeof undefined && animation !== false && animation.length > 0) {
            Ths.addClass(animation);
        }
    });

    $(document).ready(function () {
        $('.oxi-accordions-preloader').each(function () {
            $(this).css("opacity", "1");
        });
    });
    if ($("#oxi-addons-iframe-background-color").length) {
        var value = $('#oxi-addons-iframe-background-color').val();
        $('.shortcode-addons-template-body').css('background', value);
    }
})(jQuery);

