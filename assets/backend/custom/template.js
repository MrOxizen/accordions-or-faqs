jQuery.noConflict();
(function ($) {
    $(document).ready(function () {
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

        $(".oxi-addons-addons-js-create").on("click", function (e) {
            e.preventDefault();
            $('#addons-style-name').val('');
            $('#template-id').val($(this).attr('template-id'));
            $("#oxi-addons-style-create-modal").modal("show");
        });

        $("#oxi-addons-style-modal-form").submit(function (e) {
            e.preventDefault();
            var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
            var functionname = "create_new_accordions";
            $('.modal-footer').prepend('<span class="spinner sa-spinner-open-left"></span>');
            OxiAccordionsApi(functionname, rawdata, styleid, childid, function (callback) {
                setTimeout(function () {
                    document.location.href = callback;
                }, 1000);
            });
        });
    });

})(jQuery)


