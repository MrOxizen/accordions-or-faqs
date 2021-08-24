jQuery.noConflict();
(function ($) {
    var styleid = '';
    var childid = '';
    async function Oxi_Accordions_Settings(functionname, rawdata, styleid, childid, callback) {
        if (functionname === "") {
            alert('Confirm Function Name');
            return false;
        }
        let result;
        try {
            result = await $.ajax({
              url: oxiaccordionsultimate.root + 'oxiaccordionsultimate/v1/' + functionname,
                method: 'POST',
                dataType: "json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', oxiaccordionsultimate.nonce);
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
    $(document.body).on("click", "input", function (e) {
        var $This = $(this), name = $This.attr('name'), $value = $This.val();
        var rawdata = JSON.stringify({name: name, value: $value});
        var functionname = "oxi_settings";
        $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
        Oxi_Accordions_Settings(functionname, rawdata, styleid, childid, function (callback) {
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
        Oxi_Accordions_Settings(functionname, rawdata, styleid, childid, function (callback) {
            $('.' + name).html(callback);
            setTimeout(function () {
                $('.' + name).html('');
            }, 8000);
        });
    });
  
    $("input[name=responsive_tabs_with_accordions_license_key] ").on("keyup", delay(function (e) {
        var $This = $(this), $value = $This.val();
        if ($value !== $.trim($value)) {
            $value = $.trim($value);
            $This.val($.trim($value));
        }
        var rawdata = JSON.stringify({license: $value});
        var functionname = "oxi_license";
        $('.responsive_tabs_with_accordions_license_massage').html('<span class="spinner sa-spinner-open"></span>');
        Oxi_Accordions_Settings(functionname, rawdata, styleid, childid, function (callback) {
            $('.responsive_tabs_with_accordions_license_massage').html(callback.massage);
            $('.responsive_tabs_with_accordions_license_text .oxi-addons-settings-massage').html(callback.text);
        });
    }, 1000));
}
)(jQuery)