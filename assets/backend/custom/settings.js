jQuery.noConflict();
(function ($) {
        var styleid = '';
        var childid = '';

        async function OxiAccordionsApi(functionname, rawdata, styleid, childid, callback) {
            if (functionname === "") {
                alert('Confirm Function Name');
                return false;
            }
            let result;
            try {
                result = await $.ajax({
                    url: oxi_accordions_ultimate.ajaxurl,
                    method: 'post',
                    data: {
                        action: 'oxi_accordions_ultimate',
                        _wpnonce: oxi_accordions_ultimate.nonce,
                        functionname: functionname,
                        styleid: styleid,
                        childid: childid,
                        rawdata: rawdata
                    }
                });

                if (result) {
                    try {
                        console.log(JSON.parse(result));
                        return callback(JSON.parse(result));
                    } catch (e) {
                        console.log(result);
                        return callback(result)
                    }
                }

            } catch (error) {
                console.error(error);
            }
        }

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


        $("input[name=oxi_addons_font_awesome] ").on("change", function (e) {
            var $This = $(this), name = $This.attr('name'), $value = $This.val();
            var rawdata = JSON.stringify({value: $value});
            var functionname = "font_awesome";
            $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
            OxiAccordionsApi(functionname, rawdata, styleid, childid, function (callback) {
                $('.' + name).html(callback);
                setTimeout(function () {
                    $('.' + name).html('');
                }, 8000);
            });
        });
        $("input[name=accordions_or_faqs_global_schema] ").on("change", function (e) {
            var $This = $(this), name = $This.attr('name'), $value = $This.val();
            var rawdata = JSON.stringify({value: $value});
            var functionname = "global_schema";
            $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
            OxiAccordionsApi(functionname, rawdata, styleid, childid, function (callback) {
                $('.' + name).html(callback);
                setTimeout(function () {
                    $('.' + name).html('');
                }, 8000);
            });
        });
        $("input[name=oxi_accordions_support_massage] ").on("change", function (e) {
            var $This = $(this), name = $This.attr('name'), $value = $This.val();
            var rawdata = JSON.stringify({value: $value});
            var functionname = "oxi_accordions_support_massage";
            $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
            OxiAccordionsApi(functionname, rawdata, styleid, childid, function (callback) {
                $('.' + name).html(callback);
                setTimeout(function () {
                    $('.' + name).html('');
                }, 8000);
            });
        });
        $("#oxi_accordions_user_permission").on("change", function (e) {
            var $This = $(this), name = $This.attr('name'), $value = $This.val();
            var rawdata = JSON.stringify({name: name, value: $value});
            var functionname = "user_permission";
            $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
            OxiAccordionsApi(functionname, rawdata, styleid, childid, function (callback) {
                $('.' + name).html(callback);
                setTimeout(function () {
                    $('.' + name).html('');
                }, 8000);
            });
        });


        $("input[name=accordions_or_faqs_license_key] ").on("keyup", delay(function (e) {
            var $This = $(this), $value = $This.val();
            if ($value !== $.trim($value)) {
                $value = $.trim($value);
                $This.val($.trim($value));
            }
            var rawdata = JSON.stringify({license: $value});
            var functionname = "oxi_license";
            $('.accordions_or_faqs_license_massage').html('<span class="spinner sa-spinner-open"></span>');
            OxiAccordionsApi(functionname, rawdata, styleid, childid, function (callback) {
                $('.accordions_or_faqs_license_massage').html(callback.massage);
                $('.accordions_or_faqs_license_text .oxi-addons-settings-massage').html(callback.text);
            });
        }, 1000));
    }
)(jQuery)