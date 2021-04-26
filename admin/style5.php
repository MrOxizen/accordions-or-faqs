<?php
if (!defined('ABSPATH'))
    exit;
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}
$styleid = (int) $_GET['styleid'];
global $wpdb;
$table_list = $wpdb->prefix . 'accordions_or_faqs_items';
$table_name = $wpdb->prefix . 'accordions_or_faqs_style';
$title = '';
$files = '';
$icon = '';
$itemid = '';
if (!empty($_REQUEST['_wpnonce'])) {
    $nonce = $_REQUEST['_wpnonce'];
}
if (!empty($_POST['submit']) && $_POST['submit'] == 'submit') {
    if (!wp_verify_nonce($nonce, 'accordionsnewdata')) {
        die('You do not have sufficient permissions to access this page.');
    } else {
        $title = sanitize_text_field($_POST['afoxi-title']);
        $details = sanitize_text_field($_POST['afoxi-details']);
        $css = sanitize_text_field($_POST['afoxi-icon']);
        if ($_POST['item-id'] == '') {
            $wpdb->query($wpdb->prepare("INSERT INTO {$table_list} (title, files, css, styleid) VALUES ( %s, %s, %s, %d)", array($title, $details, $css, $styleid)));
        }
        if ($_POST['item-id'] != '' && is_numeric($_POST['item-id'])) {
            $item_id = (int) $_POST['item-id'];
            $data = $wpdb->update("$table_list", array("title" => $title, "files" => $details, "css" => $css), array('id' => $item_id), array('%s', '%s', '%s'), array('%d'));
        }
    }
}
if (!empty($_POST['edit']) && is_numeric($_POST['item-id'])) {
    if (!wp_verify_nonce($nonce, 'accordionseditdata')) {
        die('You do not have sufficient permissions to access this page.');
    } else {
        $item_id = (int) $_POST['item-id'];
        $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_list WHERE id = %d ", $item_id), ARRAY_A);
        $title = accordions_or_faqs_oxilab_special_charecter($data['title']);
        $files = accordions_or_faqs_oxilab_special_charecter($data['files']);
        $icon = accordions_or_faqs_oxilab_special_charecter($data['css']);
        $itemid = $data['id'];
        echo '<script type="text/javascript"> jQuery(document).ready(function () { jQuery("#afoxi-admin-model-item-data").modal("show"); });</script>';
    }
}
if (!empty($_POST['delete']) && is_numeric($_POST['item-id'])) {
    if (!wp_verify_nonce($nonce, 'accordionsdeletedata')) {
        die('You do not have sufficient permissions to access this page.');
    } else {
        $item_id = (int) $_POST['item-id'];
        $wpdb->query($wpdb->prepare("DELETE FROM {$table_list} WHERE id = %d ", $item_id));
    }
}
if (!empty($_POST['data-submit']) && $_POST['data-submit'] == 'Save') {
    if (!wp_verify_nonce($nonce, 'accordionsstyledata')) {
        die('You do not have sufficient permissions to access this page.');
    } else {
        $data = 'heading-font-size |' . sanitize_text_field($_POST['heading-font-size']) . '| heading-font-color |' . sanitize_hex_color($_POST['heading-font-color']) . '|heading-font-active-color |' . sanitize_hex_color($_POST['heading-font-active-color']) . '| heading-background-color |' . sanitize_hex_color($_POST['heading-background-color']) . '|heading-background-active-color |' . sanitize_hex_color($_POST['heading-background-active-color']) . '| heading-icon-color |' . sanitize_hex_color($_POST['heading-icon-color']) . '|heading-icon-active-color |' . sanitize_hex_color($_POST['heading-icon-active-color']) . '|heading-icon-background-color |' . sanitize_hex_color($_POST['heading-icon-background-color']) . '|heading-icon-background-active-color |' . sanitize_hex_color($_POST['heading-icon-background-active-color']) . '|'
                . ' heading-icon-size |' . sanitize_text_field($_POST['heading-icon-size']) . '| heading-icon-width |' . sanitize_text_field($_POST['heading-icon-width']) . '| heading-font-familly |' . sanitize_text_field($_POST['heading-font-familly']) . '| heading-font-weight |' . sanitize_text_field($_POST['heading-font-weight']) . '|'
                . ' heading-padding-left |' . sanitize_text_field($_POST['heading-padding-left']) . '| heading-margin-bottom |' . sanitize_text_field($_POST['heading-margin-bottom']) . '| heading-box-shadow |' . sanitize_text_field($_POST['heading-box-shadow']) . '| heading-box-shadow-color |' . sanitize_hex_color($_POST['heading-box-shadow-color']) . '|'
                . ' content-font-size |' . sanitize_text_field($_POST['content-font-size']) . '| content-font-color |' . sanitize_hex_color($_POST['content-font-color']) . '| content-background-color |' . sanitize_hex_color($_POST['content-background-color']) . '| content-padding-top |' . sanitize_text_field($_POST['content-padding-top']) . '| content-padding-right |' . sanitize_text_field($_POST['content-padding-right']) . '| content-padding-bottom |' . sanitize_text_field($_POST['content-padding-bottom']) . '| content-padding-left |' . sanitize_text_field($_POST['content-padding-left']) . '|'
                . ' content-line-height |' . sanitize_text_field($_POST['content-line-height']) . '| content-font-familly |' . sanitize_text_field($_POST['content-font-familly']) . '| content-font-weight |' . sanitize_text_field($_POST['content-font-weight']) . '|'
                . ' content-text-align |' . sanitize_text_field($_POST['content-text-align']) . '| content-box-shadow |' . sanitize_text_field($_POST['content-box-shadow']) . '| content-box-shadow-color |' . sanitize_hex_color($_POST['content-box-shadow-color']) . '||';
        $data = sanitize_text_field($data);
        $wpdb->query($wpdb->prepare("UPDATE $table_name SET css = %s WHERE id = %d", $data, $styleid));
    }
}
$listdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_list WHERE styleid = %d ORDER by id ASC ", $styleid), ARRAY_A);
$styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $styleid), ARRAY_A);
$styledata = $styledata['css'];
$styledata = explode('|', $styledata);
?>

<div class="wrap">
    <div class="afoxi-admin-wrapper">
        <div class="afoxi-admin-style-left">
            <?php accordions_or_faqs_oxilab_admin_head($styleid); ?>
            <div class="afoxi-admin-style-left-template">
                <style>
                    .afoxi-wrapper{
                        width: 100%;
                        float:left;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?>{
                        width: 100%;
                        float: left;
                        cursor: pointer;
                        overflow: hidden;
                        display: flex;
                        align-items: center;
                        background-color: <?php echo $styledata[7]; ?>;
                        margin-bottom: <?php echo $styledata[29]; ?>px;
                        box-shadow: 0 0 <?php echo $styledata[31]; ?>px <?php echo $styledata[33]; ?>;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?>:first-child{
                        margin-top: <?php echo $styledata[29]; ?>px;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?>.active{
                        background-color: <?php echo $styledata[9]; ?>;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?> .afoxi-heading-icon{
                        width: <?php echo $styledata[21]; ?>px;
                        float: left;
                        height: <?php echo $styledata[21]; ?>px;
                        color: <?php echo $styledata[11]; ?>;
                        background-color: <?php echo $styledata[15]; ?>;
                        display: flex;
                        align-items: center;
                        position: relative;
                        justify-content: center;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?>.active .afoxi-heading-icon{
                        color: <?php echo $styledata[13]; ?>;
                        background-color: <?php echo $styledata[17]; ?>;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?> .afoxi-heading-icon .fa{
                        font-size: <?php echo $styledata[19]; ?>px;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?> .afoxi-heading-icon .afoxi-heading-icon-absolute{
                        position: absolute;
                        display: block;
                        top: 0;
                        bottom: 0;
                        width: 0;
                        height: 0;
                        right: -<?php echo $styledata[21] / 2; ?>px;
                        border-top: <?php echo $styledata[21] / 2; ?>px solid transparent;
                        border-left: <?php echo $styledata[21] / 2; ?>px solid ;
                        border-left-color: <?php echo $styledata[15]; ?>;
                        border-bottom: <?php echo $styledata[21] / 2; ?>px solid transparent;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?>.active .afoxi-heading-icon .afoxi-heading-icon-absolute{
                        border-left-color: <?php echo $styledata[17]; ?>;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?> .heading-data{
                        width: calc(100% - <?php echo $styledata[21]; ?>px);
                        float: left;
                        font-size: <?php echo $styledata[1]; ?>px;
                        color: <?php echo $styledata[3]; ?>;
                        font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[23]); ?>;
                        font-weight:  <?php echo $styledata[25]; ?>;
                        padding-left: <?php echo $styledata[27]; ?>px;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?>.active .heading-data{
                        color: <?php echo $styledata[5]; ?>;
                    }
                    .afoxi-content-style-<?php echo $styleid; ?>{
                        width: 100%;
                        float: left;
                        display: none;
                        font-size: <?php echo $styledata[35]; ?>px;
                        color: <?php echo $styledata[37]; ?>;
                        background-color: <?php echo $styledata[39]; ?>;
                        padding: <?php echo $styledata[41]; ?>px <?php echo $styledata[43]; ?>px <?php echo $styledata[45]; ?>px <?php echo $styledata[47]; ?>px;
                        font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[51]); ?>;
                        font-weight: <?php echo $styledata[53]; ?>;
                        line-height: <?php echo $styledata[49]; ?>;
                        text-align: <?php echo $styledata[55]; ?>;
                        box-shadow: 0 0 <?php echo $styledata[57]; ?>px <?php echo $styledata[59]; ?>;
                        margin-bottom: <?php echo $styledata[29]; ?>px;
                    }


                </style>

                <?php
                echo '<div class="afoxi-wrapper">';
                foreach ($listdata as $value) {
                    echo '<div class="afoxi-heading-style-' . $styleid . ' afoxi-heading" ref="#afoxi-content-style-' . $styleid . '-id-' . $value['id'] . '">
                                <div class="afoxi-heading-icon">
                                    <i class="fa ' . accordions_or_faqs_oxilab_special_charecter($value['css']) . '" aria-hidden="true"></i>
                                    <div class="afoxi-heading-icon-absolute">
                                    </div>
                                </div>
                                <div class="heading-data">
                                     ' . accordions_or_faqs_oxilab_special_charecter($value['title']) . '
                                </div>
                                <div class="afoxi-admin-absulote"> 
                                    <div class="afoxi-style-absulate-edit">
                                        <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#afoxi-edit-heading" ><i class="fa fa-cog" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Settings"></i></button>
                                    </div> 
                                    <div class="afoxi-style-absulate-edit">
                                        <form method="post">
                                        ' . wp_nonce_field("accordionseditdata") . '
                                            <input type="hidden" name="item-id" value="' . $value['id'] . '">
                                            <button class="btn btn-primary btn-xs" type="submit" value="edit" name="edit"><i class="fa fa-pencil-square-o" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Edit"></i></button>
                                        </form>
                                    </div> 
                                    <div class="afoxi-style-absulate-delete">
                                        <form method="post">
                                        ' . wp_nonce_field("accordionsdeletedata") . '
                                            <input type="hidden" name="item-id" value="' . $value['id'] . '">
                                            <button class="btn btn-danger btn-xs" type="submit" value="delete" name="delete"><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Delete"></i></button>  
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="afoxi-content-style-' . $styleid . ' afoxi-content" id="afoxi-content-style-' . $styleid . '-id-' . $value['id'] . '">
                                 ' . accordions_or_faqs_oxilab_special_charecter($value['files']) . '
                                <div class="afoxi-admin-absulote"> 
                                    <div class="afoxi-style-absulate-edit">
                                        <button class="btn btn-info" type="button" data-toggle="modal" data-target="#afoxi-edit-content"><i class="fa fa-cog" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Settings"></i></button>
                                    </div> 
                                </div>
                            </div>';
                }
                echo '</div>';
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery(".afoxi-heading-style-<?php echo $styleid; ?>:first").addClass("active");
                        jQuery(".afoxi-content-style-<?php echo $styleid; ?>:first").slideDown();
                        jQuery(".afoxi-heading-style-<?php echo $styleid; ?>").click(function () {
                            jQuery(".afoxi-heading-style-<?php echo $styleid; ?>").removeClass("active");
                            jQuery(this).toggleClass("active");
                            jQuery(".afoxi-content-style-<?php echo $styleid; ?>").slideUp();
                            var activeTab = jQuery(this).attr("ref");
                            jQuery(activeTab).slideDown();

                        });
                    });
                </script>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery("#heading-font-size").on("change", function () {
                var idvalue = jQuery('#heading-font-size').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-heading .heading-data{ font-size:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-font-color").on("change", function () {
                var idvalue = jQuery('#heading-font-color').val();
                jQuery("<style type='text/css'> .afoxi-heading .heading-data{ color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-font-active-color").on("change", function () {
                var idvalue = jQuery('#heading-font-active-color').val();
                jQuery("<style type='text/css'> .afoxi-heading.active .heading-data{ color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-background-color").on("change", function () {
                var idvalue = jQuery('#heading-background-color').val();
                jQuery("<style type='text/css'> .afoxi-heading { background-color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-background-active-color").on("change", function () {
                var idvalue = jQuery('#heading-background-active-color').val();
                jQuery("<style type='text/css'> .afoxi-heading.active { background-color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-color").on("change", function () {
                var idvalue = jQuery('#heading-icon-color').val();
                jQuery("<style type='text/css'> .afoxi-heading .afoxi-heading-icon { color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-active-color").on("change", function () {
                var idvalue = jQuery('#heading-icon-active-color').val();
                jQuery("<style type='text/css'> .afoxi-heading.active .afoxi-heading-icon { color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-background-color").on("change", function () {
                var idvalue = jQuery('#heading-icon-background-color').val();
                jQuery("<style type='text/css'> .afoxi-heading .afoxi-heading-icon { background-color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
                jQuery("<style type='text/css'> .afoxi-heading .afoxi-heading-icon .afoxi-heading-icon-absolute{ border-left-color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-background-active-color").on("change", function () {
                var idvalue = jQuery('#heading-icon-background-active-color').val();
                jQuery("<style type='text/css'> .afoxi-heading.active .afoxi-heading-icon { background-color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
                jQuery("<style type='text/css'> .afoxi-heading.active .afoxi-heading-icon .afoxi-heading-icon-absolute{ border-left-color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-size").on("change", function () {
                var idvalue = jQuery('#heading-icon-size').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-heading .afoxi-heading-icon .fa { font-size:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-width").on("change", function () {
                var idvalue = jQuery('#heading-icon-width').val() / 2;
                var idvalue2 = jQuery('#heading-icon-width').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-heading .afoxi-heading-icon { width:" + idvalue2 + "; height:" + idvalue2 + ";} </style>").appendTo(".afoxi-heading");
                jQuery("<style type='text/css'> .afoxi-heading .afoxi-heading-icon .afoxi-heading-icon-absolute, .afoxi-heading.active .afoxi-heading-icon .afoxi-heading-icon-absolute{ right: -" + idvalue + "px; border-top: " + idvalue + "px solid transparent;  border-bottom: " + idvalue + "px solid transparent; border-left: " + idvalue + "px solid;} </style>").appendTo(".afoxi-heading");
            });
            jQuery('#heading-font-familly').fontselect().change(function () {
                var font = jQuery(this).val().replace(/\+/g, ' ');
                font = font.split(':');
                jQuery("<style type='text/css'> .afoxi-heading .heading-data{ font-family:" + font[0] + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-font-weight").on("change", function () {
                var idvalue = jQuery('#heading-font-weight').val();
                jQuery("<style type='text/css'> .afoxi-heading .heading-data{ font-weight:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-padding-left").on("change", function () {
                var idvalue = jQuery('#heading-padding').val() + 'px ';
                jQuery("<style type='text/css'> .afoxi-heading .heading-data{ padding-left:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-margin-bottom").on("change", function () {
                var idvalue = jQuery('#heading-margin-bottom').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-heading { margin-bottom:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
                jQuery("<style type='text/css'> .afoxi-heading:first-child { margin-top:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-box-shadow").on("change", function () {
                var idvalue = jQuery('#heading-box-shadow').val() + 'px ';
                var idvalue2 = jQuery('#heading-box-shadow-color').val();
                jQuery("<style type='text/css'> .afoxi-heading { box-shadow: 0 0 " + idvalue + idvalue2 + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-box-shadow-color").on("change", function () {
                var idvalue = jQuery('#heading-box-shadow').val() + 'px ';
                var idvalue2 = jQuery('#heading-box-shadow-color').val();
                jQuery("<style type='text/css'> .afoxi-heading { box-shadow: 0 0 " + idvalue + idvalue2 + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#content-font-size").on("change", function () {
                var idvalue = jQuery('#content-font-size').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-content{ font-size:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-font-color").on("change", function () {
                var idvalue = jQuery('#content-font-color').val();
                jQuery("<style type='text/css'> .afoxi-content{ color:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-background-color").on("change", function () {
                var idvalue = jQuery('#content-background-color').val();
                jQuery("<style type='text/css'> .afoxi-content{ background-color:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-padding-top").on("change", function () {
                var idvalue = jQuery('#content-padding-top').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-content{ padding-top:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-padding-right").on("change", function () {
                var idvalue = jQuery('#content-padding-right').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-content{ padding-right:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-padding-bottom").on("change", function () {
                var idvalue = jQuery('#content-padding-bottom').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-content{ padding-bottom:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-padding-left").on("change", function () {
                var idvalue = jQuery('#content-padding-left').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-content{ padding-left:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-line-height").on("change", function () {
                var idvalue = jQuery('#content-line-height').val();
                jQuery("<style type='text/css'> .afoxi-content{ line-height:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery('#content-font-familly').fontselect().change(function () {
                var font = jQuery(this).val().replace(/\+/g, ' ');
                font = font.split(':');
                jQuery("<style type='text/css'> .afoxi-content{ font-family:" + font[0] + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-font-weight").on("change", function () {
                var idvalue = jQuery('#content-font-weight').val();
                jQuery("<style type='text/css'> .afoxi-content{ font-weight:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-text-align").on("change", function () {
                var idvalue = jQuery('#content-text-align').val();
                jQuery("<style type='text/css'> .afoxi-content{ text-align:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-box-shadow").on("change", function () {
                var idvalue = jQuery('#content-box-shadow').val() + 'px ';
                var idvalue2 = jQuery('#content-box-shadow-color').val();
                jQuery("<style type='text/css'> .afoxi-content{ box-shadow: 0 0 " + idvalue + idvalue2 + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-box-shadow-color").on("change", function () {
                var idvalue = jQuery('#content-box-shadow').val() + 'px ';
                var idvalue2 = jQuery('#content-box-shadow-color').val();
                jQuery("<style type='text/css'> .afoxi-content{ box-shadow: 0 0 " + idvalue + idvalue2 + ";} </style>").appendTo(".afoxi-content");
            });
        });
    </script>
    <form method="post">
        <div class="modal fade afoxi-draggable" id="afoxi-edit-heading" data-backdrop="false" >
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Title</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row form-group-sm">
                            <label for="heading-font-size" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Title Font Size, Based on Pixel ">Font Size </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[1]; ?>" id="heading-font-size" name="heading-font-size">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-font-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Your Title Font Color, Based on Color" >Color</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-font-color" name="heading-font-color" value="<?php echo $styledata[3]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-font-active-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Your Title Font Active Color, Based on Color" >Active Color</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-font-active-color" name="heading-font-active-color" value="<?php echo $styledata[5]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-background-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Your Title Background Color, Based on Color" >Background Color</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-background-color" name="heading-background-color" value="<?php echo $styledata[7]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-background-active-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Your Title Background Active Color, Based on Color" >Background Active</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-background-active-color" name="heading-background-active-color" value="<?php echo $styledata[9]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Your Title Icon Color, Based on Color" >Icon Color</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-icon-color" name="heading-icon-color" value="<?php echo $styledata[11]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-active-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Your Title Icon Active Color, Based on Color" >Icon Active</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-icon-active-color" name="heading-icon-active-color" value="<?php echo $styledata[13]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-background-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Your Title Icon Background Color, Based on Color" >Icon Background</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-icon-background-color" name="heading-icon-background-color" value="<?php echo $styledata[15]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-background-active-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Your Title Icon BAckground Color, Based on Color" >Icon Background Active</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-icon-background-active-color" name="heading-icon-background-active-color" value="<?php echo $styledata[17]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-size" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Set your Custom Close Open Icon Size">Icon Size </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[19]; ?>" id="heading-icon-size" name="heading-icon-size">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-width" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Set your Custom  Close Open Icon Space">Icon Width </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[21]; ?>" id="heading-icon-width" name="heading-icon-width">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-font-familly" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Choose Your Title Preferred font, Based on Google Font"> Font Family </label>
                            <div class="col-sm-6 nopadding">
                                <input class="afoxi-admin-font" type="text" name="heading-font-familly" id="heading-font-familly" value="<?php echo $styledata[23]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-font-weight" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Title Font Weight, Based on CSS Weight" >Font Weight </label>
                            <div class="col-sm-6 nopadding">
                                <select class="form-control" id="heading-font-weight" name="heading-font-weight">
                                    <option value="100" <?php
                                    if ($styledata[25] == '100') {
                                        echo 'selected';
                                    }
                                    ?>>100</option>
                                    <option value="200" <?php
                                    if ($styledata[25] == '200') {
                                        echo 'selected';
                                    }
                                    ?>>200</option>
                                    <option value="300" <?php
                                    if ($styledata[25] == '300') {
                                        echo 'selected';
                                    }
                                    ?>>300</option>
                                    <option value="400" <?php
                                    if ($styledata[25] == '400') {
                                        echo 'selected';
                                    }
                                    ?>>400</option>
                                    <option value="500" <?php
                                    if ($styledata[25] == '500') {
                                        echo 'selected';
                                    }
                                    ?>>500</option>
                                    <option value="600" <?php
                                    if ($styledata[25] == '600') {
                                        echo 'selected';
                                    }
                                    ?>>600</option>
                                    <option value="700" <?php
                                    if ($styledata[25] == '700') {
                                        echo 'selected';
                                    }
                                    ?>>700</option>
                                    <option value="800" <?php
                                    if ($styledata[25] == '800') {
                                        echo 'selected';
                                    }
                                    ?>>800</option>
                                    <option value="900" <?php
                                    if ($styledata[25] == '900') {
                                        echo 'selected';
                                    }
                                    ?>>900</option>
                                    <option value="normal" <?php
                                    if ($styledata[25] == 'normal') {
                                        echo 'selected';
                                    }
                                    ?>>Normal</option>
                                    <option value="bold" <?php
                                    if ($styledata[25] == 'bold') {
                                        echo 'selected';
                                    }
                                    ?>>Bold</option>
                                    <option value="lighter" <?php
                                    if ($styledata[25] == 'lighter') {
                                        echo 'selected';
                                    }
                                    ?>>Lighter</option>
                                    <option value="initial" <?php
                                    if ($styledata[25] == 'initial') {
                                        echo 'selected';
                                    }
                                    ?>>Initial</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-padding-left" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Use Padding to generate space around Title, Based on Pixel ">Padding Left</label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[27]; ?>" id="heading-padding-left" name="heading-padding-left">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-margin-bottom" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Use Padding to generate space around Title, Based on Pixel ">Margin Bottom </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[29]; ?>" id="heading-margin-bottom" name="heading-margin-bottom">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-box-shadow" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Title Box Shadow ">Box Shadow </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[31]; ?>" id="heading-box-shadow" name="heading-box-shadow">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-box-shadow-color" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Title Box Shadow Color">Shadow Color </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control afoxi-vendor-color" type="text"  value="<?php echo $styledata[33]; ?>" id="heading-box-shadow-color" name="heading-box-shadow-color">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="data-submit" value="Save">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade afoxi-draggable" id="afoxi-edit-content" data-backdrop="false" >
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Content</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row form-group-sm">
                            <label for="content-font-size" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Content Font Size, Based on Pixel ">Font Size </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[35]; ?>" id="content-font-size" name="content-font-size">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-font-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Custom Content Font Color, Based on Color">Color</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="content-font-color" name="content-font-color" value="<?php echo $styledata[37]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-background-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Custom Background Color of Content Box">Background Color</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="content-background-color" name="content-background-color" value="<?php echo $styledata[39]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-padding-top" class="col-sm-4 col-form-label" data-toggle="tooltip" data-placement="top" title="Use Padding to Generate Space Around Content as top, right, bottom, left. Based on Pixel ">Padding </label>
                            <div class="col-sm-2 nopadding2">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[41]; ?>" id="content-padding-top" name="content-padding-top">
                            </div>
                            <div class="col-sm-2 nopadding2">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[43]; ?>" id="content-padding-right" name="content-padding-right">
                            </div>
                            <div class="col-sm-2 nopadding2">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[45]; ?>" id="content-padding-bottom" name="content-padding-bottom">
                            </div>
                            <div class="col-sm-2 nopadding2">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[47]; ?>" id="content-padding-left" name="content-padding-left">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-line-height" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Content Font Line Height, Based on Point">Line Height</label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number" step="0.1"  min="0" value="<?php echo $styledata[49]; ?>" id="content-line-height" name="content-line-height">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-font-familly" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Choose Your Content Font, Based on Google Font"> Font Family</label>
                            <div class="col-sm-6 nopadding">
                                <input class="cau-admin-font" value="<?php echo $styledata[51]; ?>" type="text" name="content-font-familly" id="content-font-familly">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-font-weight" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Cahne Font Style With Font Weight. Based on Pixel ">Font Weight </label>
                            <div class="col-sm-6 nopadding">
                                <select class="form-control" id="content-font-weight" name="content-font-weight">
                                    <option value="100" <?php
                                    if ($styledata[53] == '100') {
                                        echo 'selected';
                                    }
                                    ?>>100</option>
                                    <option value="200" <?php
                                    if ($styledata[53] == '200') {
                                        echo 'selected';
                                    }
                                    ?>>200</option>
                                    <option value="300" <?php
                                    if ($styledata[53] == '300') {
                                        echo 'selected';
                                    }
                                    ?>>300</option>
                                    <option value="400" <?php
                                    if ($styledata[53] == '400') {
                                        echo 'selected';
                                    }
                                    ?>>400</option>
                                    <option value="500" <?php
                                    if ($styledata[53] == '500') {
                                        echo 'selected';
                                    }
                                    ?>>500</option>
                                    <option value="600" <?php
                                    if ($styledata[53] == '600') {
                                        echo 'selected';
                                    }
                                    ?>>600</option>
                                    <option value="700" <?php
                                    if ($styledata[53] == '700') {
                                        echo 'selected';
                                    }
                                    ?>>700</option>
                                    <option value="800" <?php
                                    if ($styledata[53] == '800') {
                                        echo 'selected';
                                    }
                                    ?>>800</option>
                                    <option value="900" <?php
                                    if ($styledata[53] == '900') {
                                        echo 'selected';
                                    }
                                    ?>>900</option>
                                    <option value="normal" <?php
                                    if ($styledata[53] == 'normal') {
                                        echo 'selected';
                                    }
                                    ?>>Normal</option>
                                    <option value="bold" <?php
                                    if ($styledata[53] == 'bold') {
                                        echo 'selected';
                                    }
                                    ?>>Bold</option>
                                    <option value="lighter" <?php
                                    if ($styledata[53] == 'lighter') {
                                        echo 'selected';
                                    }
                                    ?>>Lighter</option>
                                    <option value="initial" <?php
                                    if ($styledata[53] == 'initial') {
                                        echo 'selected';
                                    }
                                    ?>>Initial</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row form-group-sm">
                            <label for="content-text-align" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Use Text Align to change Align. Based on Pixel ">Text Align </label>
                            <div class="col-sm-6 nopadding">
                                <select class="form-control" id="content-text-align" name="content-text-align">
                                    <option value="left" <?php
                                    if ($styledata[55] == 'left') {
                                        echo 'selected';
                                    }
                                    ?>>Left</option>
                                    <option value="center" <?php
                                    if ($styledata[55] == 'center') {
                                        echo 'selected';
                                    }
                                    ?>>Center</option>
                                    <option value="right" <?php
                                    if ($styledata[55] == 'right') {
                                        echo 'selected';
                                    }
                                    ?>>Right</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-box-shadow" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Content Box Shadow, Based on Pixel">Box Shadow</label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number" step="1" min="0" value="<?php echo $styledata[57]; ?>" id="content-box-shadow" name="content-box-shadow">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-box-shadow-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Custom Box Shadow Color of Content Box">Box Shadow Color</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="content-box-shadow-color" name="content-box-shadow-color" value="<?php echo $styledata[59]; ?>">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <?php echo wp_nonce_field("accordionsstyledata"); ?>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="data-submit" value="Save">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="afoxi-admin-model-item-data" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Items Add or Edit</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="afoxi-title"  data-toggle="tooltip" data-placement="top">Title</label>
                            <input type="text "class="form-control" id="afoxi-title" name="afoxi-title" value="<?php echo accordions_or_faqs_oxilab_special_charecter($title); ?>">
                            <small class="form-text text-muted">Add or Modify Your Tabs Title.</small>
                        </div>
                        <div class="form-group">
                            <label for="afoxi-icon"  data-toggle="tooltip" data-placement="top">Font Awesome Icon</label>
                            <input type="text "class="form-control" id="afoxi-icon" name="afoxi-icon" value="<?php echo accordions_or_faqs_oxilab_special_charecter($icon); ?>">
                            <small class="form-text text-muted">Add or Modify Your Tabs Icon. as example fa-facebook</small>
                        </div>
                        <div class="form-group">
                            <label for="afoxi-details">Details:</label>
                            <textarea class="form-control" rows="4" id="afoxi-details" name="afoxi-details"><?php echo accordions_or_faqs_oxilab_special_charecter($files); ?></textarea>
                            <small class="form-text text-muted">Add or Modify Your Content.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <?php echo wp_nonce_field("accordionsnewdata"); ?>
                        <input type="hidden" id="item-id" name="item-id" value="<?php echo $item_id; ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" id="item-submit" name="submit" value="submit">
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>