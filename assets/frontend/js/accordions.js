jQuery.noConflict();
(function ($) {




    $('body').find('[class*=oxi-accordions-wrapper-]').each(function (e) {
        e.preventDefault();
        var _this = $(this), id = _this.attr('id'), _explode = id.split("-"),
                _parent = _explode[3],
                _template = $('.oxi-accordions-ultimate-template-' + _parent),
                _animation = _template.data('oxi-animation'),
                _accordions_toggle = _template.data('oxi-accordions-toggle'),
                _preloader = _template.data('oxi-preloader');


    });

    $(document).ready(function () {
        $('.oxi-accordions-preloader').each(function () {
            $(this).css("opacity", "1");
        });
    });

    $(document).ready(function () {
        /* Check Url if there have any ID*/
        var trigger = '', hash_link = window.location.hash;
        if (hash_link.includes("oxi-accordions-trigger-")) {
            var explode = hash_link.split("-"), parent = explode[3], child = explode[4];
            OxiAccordionsController(parent, child);
        } else {
            $('[class*=oxi-accordions-wrapper-]').each(function () {
                var This = $(this), id = This.attr('id'), explode = id.split("-"), parent = explode[3];
                OxiAccordionsController(parent);
            });
        }
        /* Check any btn click for confirm event for tabs*/
        $(document).on('click', '[id^="oxi-accordions-trigger-"]', function (e) {
            e.preventDefault();
            var wrapper = $(this).attr('id'), explode = wrapper.split("-"), parent = explode[3], child = explode[4];
            OxiAccordionsController(parent, child);
        });


        $('a[href*="#oxi-accordions-trigger-"]').click(function (e) {
            e.preventDefault();
            var wrapper = $(this).attr('href'), explode = wrapper.split("-"), parent = explode[3], child = explode[4];
            OxiAccordionsController(parent, child);
        });



        /* Tabs Header Hover  Data Confirmation*/
        $(".oxi-accordions-hover-event .oxi-accordions-header-body").hover(function () {
            var link = $(this).data("link");
            if (typeof link !== typeof undefined && link !== false && $(".shortcode-addons-template-body").length === 0) {
                var target = '_self';
                if (link.target === 'yes') {
                    var target = ", '_blank'";
                }
                window.open("" + link.url + "", "" + target + "");
            } else {
                var t = $(this).data('oxitarget'), explode = t.split("-"), parent = explode[3], child = explode[4];
                OxiAccordionsController(parent, child);

            }
        });
        /* Tabs Header Click Data Confirmation*/
        $(document).on('click', '.oxi-accordions-click-event .oxi-accordions-header-body', function () {
            var link = $(this).data("link");
            if (typeof link !== typeof undefined && link !== false && $(".shortcode-addons-template-body").length === 0) {
                var target = '_self';
                if (link.target === 'yes') {
                    var target = ", '_blank'";
                }
                window.open("" + link.url + "", "" + target + "");
            } else {
                var t = $(this).data('oxitarget'), explode = t.split("-"), parent = explode[3], child = explode[4];
                OxiAccordionsController(parent, child);
            }
        });
        function OxiAccordionsController(p = '', c = '') {
            var cls = '#oxi-accordions-wrapper-' + p + " > .oxi-addons-row > .oxi-accordions-ultimate-style";
            var accordions = '#oxi-accordions-wrapper-' + p + " > .oxi-addons-row > .oxi-accordions-ultimate-style > .oxi-accordions-single-card-" + p;
            var j = $(cls).data('oxi-accordions');
            if (c === '') {
                $(accordions).each(function () {
                    var ref = $(this).find(".oxi-accordions-header-body");
                    var attr = $(ref).attr('default-opening');
                    if (typeof attr !== 'undefined' && attr === 'yes') {
                        $(this).addClass("oxi-accordions-expand");
                    }
                });
            } else {
                var Headerbody = '.oxi-accordions-single-card-' + p + '-' + c;
                if ($(Headerbody).hasClass('oxi-accordions-expand')) {
                    if (j.type === 'oxi-accordions-toggle' && !$(accordions).hasClass('oxi-accordions-hover-event')) {
                        $(Headerbody).removeClass("oxi-accordions-expand");
                    }
                    return false;
                } else {
                    if (j.type === 'oxi-accordions-toggle') {
                        $(Headerbody).addClass("oxi-accordions-expand");
                    } else {
                        $(accordions).removeClass("oxi-accordions-expand");
                        $(Headerbody).addClass("oxi-accordions-expand");
                    }
                }
        }
        }

        if ($("#oxi-addons-iframe-background-color").length) {
            var value = $('#oxi-addons-iframe-background-color').val();
            $('.shortcode-addons-template-body').css('background', value);
        }
    });

})(jQuery);