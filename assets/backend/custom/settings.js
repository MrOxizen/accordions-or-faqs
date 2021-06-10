jQuery.noConflict();
(function ($) {
    var styleid = '';
    var childid = '';
    async function Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, callback) {
        if (functionname === "") {
            alert('Confirm Function Name');
            return false;
        }
        let result;
        try {
            result = await $.ajax({
                url: oxilabtabsultimate.root + 'oxilabtabsultimate/v1/' + functionname,
                method: 'POST',
                dataType: "json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', oxilabtabsultimate.nonce);
                },
                data: {
                    styleid: styleid,
                    childid: childid,
                    rawdata: rawdata
                }
            });
            console.log(result);
            return callback(result);
        } catch (error) {
            console.error(error);
        }
    }
    function checkwoocommerce() {
        if ($('input[name=oxilab_tabs_woocommerce]:checked').val() === 'yes') {
            $('.oxilab_tabs_woocommerce_active').slideDown();
        } else {
            $('.oxilab_tabs_woocommerce_active').slideUp();
        }
        return true;
    }
    checkwoocommerce();

    $(document.body).on("click", "input", function (e) {
        var $This = $(this), name = $This.attr('name'), $value = $This.val();
        var rawdata = JSON.stringify({name: name, value: $value});
        var functionname = "oxi_settings";
        $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
        if (name === 'oxilab_tabs_woocommerce') {
            if ($value === 'yes') {
                $('.oxilab_tabs_woocommerce_active').slideDown();
            } else {
                $('.oxilab_tabs_woocommerce_active').slideUp();
            }
        }
        Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, function (callback) {
            $('.' + name).html(callback);
            setTimeout(function () {
                $('.' + name).html('');
            }, 8000);
        });
    });
    $(document.body).on("change", "select", function (e) {
        var $This = $(this), name = $This.attr('name'), $value = $This.val();
        var rawdata = JSON.stringify({name: name, value: $value});
        var functionname = "oxi_settings";
        $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
        Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, function (callback) {
            $('.' + name).html(callback);
            setTimeout(function () {
                $('.' + name).html('');
            }, 8000);
        });
    });

    $("input[name=oxi_addons_fixed_header_size] ").on("keyup", delay(function (e) {
        var $This = $(this), name = $This.attr('name'), $value = $This.val();
        var rawdata = JSON.stringify({name: name, value: $value});
        var functionname = "oxi_settings";
        $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
        Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, function (callback) {
            $('.' + name).html(callback);
            setTimeout(function () {
                $('.' + name).html('');
            }, 8000);
        });
    }, 1000));
    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }
    $("input[name=responsive_tabs_with_accordions_license_key] ").on("keyup", delay(function (e) {
        var $This = $(this), $value = $This.val();
        if ($value !== $.trim($value)) {
            $value = $.trim($value);
            $This.val($.trim($value));
        }
        var rawdata = JSON.stringify({license: $value});
        var functionname = "oxi_license";
        $('.responsive_tabs_with_accordions_license_massage').html('<span class="spinner sa-spinner-open"></span>');
        Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, function (callback) {
            $('.responsive_tabs_with_accordions_license_massage').html(callback.massage);
            $('.responsive_tabs_with_accordions_license_text .oxi-addons-settings-massage').html(callback.text);
        });
    }, 1000));
}
)(jQuery)