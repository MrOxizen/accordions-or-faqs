jQuery.noConflict();
(function ($) {
    $(document).ready(function () {
        var styleid = '';
        var childid = '';

        async function Oxi_Tabs_Admin_Create(functionname, rawdata, styleid, childid, callback) {
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
        $(".oxi-addons-addons-js-create").on("click", function (e) {
            e.preventDefault();
            $('#addons-style-name').val('');
            $('#responsive-tabs-template-id').val($(this).attr('template-id'));
            $("#oxi-addons-style-create-modal").modal("show");
        });

        $("#oxi-addons-style-modal-form").submit(function (e) {
            e.preventDefault();
            var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
            var functionname = "create_new";
            $('.modal-footer').prepend('<span class="spinner sa-spinner-open-left"></span>');
            Oxi_Tabs_Admin_Create(functionname, rawdata, styleid, childid, function (callback) {
                setTimeout(function () {
                    document.location.href = callback;
                }, 1000);
            });
        });

        $(".shortcode-addons-template-deactive").submit(function (e) {
            e.preventDefault();
            var $This = $(this);
            var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
            var functionname = "shortcode_deactive";
            $(this).append('<span class="spinner sa-spinner-open"></span>');
            Oxi_Tabs_Admin_Create(functionname, rawdata, styleid, childid, function (callback) {
                console.log(callback);
                setTimeout(function () {
                    if (callback === "done") {
                        $This.parents('.oxi-addons-col-1').remove();
                    }
                }, 1000);
            });
            return false;
        });
        $(".shortcode-addons-template-import").submit(function (e) {
            e.preventDefault();
            var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
            var functionname = "shortcode_active";
            $(this).prepend('<span class="spinner sa-spinner-open-left"></span>');
            Oxi_Tabs_Admin_Create(functionname, rawdata, styleid, childid, function (callback) {
                setTimeout(function () {
                    document.location.href = callback;
                }, 1000);
            });
            return false;
        });
        $(".shortcode-addons-template-pro-only").submit(function (e) {
            e.preventDefault();
            return false;
        });
    });

})(jQuery)


