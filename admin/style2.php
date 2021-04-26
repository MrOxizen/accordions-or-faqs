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
        if ($_POST['item-id'] == '') {
            $wpdb->query($wpdb->prepare("INSERT INTO {$table_list} (title, files, styleid) VALUES ( %s, %s, %d)", array($title, $details, $styleid)));
        }
        if ($_POST['item-id'] != '' && is_numeric($_POST['item-id'])) {
            $item_id = (int) $_POST['item-id'];
            $data = $wpdb->update("$table_list", array("title" => $title, "files" => $details), array('id' => $item_id), array('%s', '%s'), array('%d'));
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
        $data = 'heading-font-size |' . sanitize_text_field($_POST['heading-font-size']) . '| heading-font-color |' . sanitize_hex_color($_POST['heading-font-color']) . '| heading-font-familly |' . sanitize_text_field($_POST['heading-font-familly']) . '| heading-font-weight |' . sanitize_text_field($_POST['heading-font-weight']) . '|'
                . ' heading-padding |' . sanitize_text_field($_POST['heading-padding']) . '| head-icon-select |' . sanitize_text_field($_POST['head-icon-select']) . '| heading-icon-size |' . sanitize_text_field($_POST['heading-icon-size']) . '| heading-icon-width |' . sanitize_text_field($_POST['heading-icon-width']) . '|'
                . ' heading-icon-color |' . sanitize_hex_color($_POST['heading-icon-color']) . '| heading-icon-background-color |' . sanitize_hex_color($_POST['heading-icon-background-color']) . '| content-font-size |' . sanitize_text_field($_POST['content-font-size']) . '| content-font-color |' . sanitize_hex_color($_POST['content-font-color']) . '| content-padding-top |' . sanitize_text_field($_POST['content-padding-top']) . '| content-padding-right |' . sanitize_text_field($_POST['content-padding-right']) . '| content-padding-bottom |' . sanitize_text_field($_POST['content-padding-bottom']) . '| content-padding-left |' . sanitize_text_field($_POST['content-padding-left']) . '|'
                . ' content-line-height |' . sanitize_text_field($_POST['content-line-height']) . '| content-font-familly |' . sanitize_text_field($_POST['content-font-familly']) . '| content-font-weight |' . sanitize_text_field($_POST['content-font-weight']) . '|'
                . ' content-text-align |' . sanitize_text_field($_POST['content-text-align']) . '| |';
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
                        display: flex;
                        align-items: center;
                        padding: <?php echo $styledata[9]; ?>px 0;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?> .heading-data{
                        width: calc(100% - <?php echo $styledata[15] + 10; ?>px);
                        float: left;
                        color: <?php echo $styledata[3]; ?>;
                        font-size: <?php echo $styledata[1]; ?>px;
                        font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[5]); ?>;
                        font-weight: <?php echo $styledata[7]; ?>;
                        line-height: 100%;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?>.active .span-active{
                        display: flex;
                        float: left;
                        align-items: center;
                        justify-content: center;
                        width: <?php echo $styledata[15]; ?>px;
                        height: <?php echo $styledata[15]; ?>px;
                        color:  <?php echo $styledata[19]; ?>;
                        margin-right: 10px;
                        background-color:<?php echo $styledata[17]; ?>;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?> .span-deactive{
                        display: flex;
                        float: left;
                        align-items: center;
                        justify-content: center;
                        width:<?php echo $styledata[15]; ?>px;
                        height:<?php echo $styledata[15]; ?>px;
                        color:  <?php echo $styledata[17]; ?>;
                        margin-right: 10px;
                        background-color:  <?php echo $styledata[19]; ?>; 
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?> .span-active{
                        display: none;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?>.active .span-deactive{
                        display: none;
                    }
                    .afoxi-heading-style-<?php echo $styleid; ?> span .fa{
                        font-size: <?php echo $styledata[13]; ?>px;
                    }
                    .afoxi-content-style-<?php echo $styleid; ?>{
                        display: none;
                        width: 100%;
                        float: left;
                        font-size: <?php echo $styledata[21]; ?>px;
                        color:<?php echo $styledata[23]; ?>;
                        font-weight: <?php echo $styledata[33]; ?>;
                        font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[35]); ?>;
                        line-height: <?php echo $styledata[33]; ?>;
                        text-align: <?php echo $styledata[39]; ?>;
                        padding: <?php echo $styledata[25]; ?>% <?php echo $styledata[27]; ?>%  <?php echo $styledata[29]; ?>% <?php echo $styledata[31]; ?>%;
                    }
                </style>
                <?php
                $iconselect = explode('++', $styledata[11]);
                echo '<div class="afoxi-wrapper">';
                foreach ($listdata as $value) {
                    echo '  <div class="afoxi-heading-style-' . $styleid . ' afoxi-heading" ref="#afoxi-heading-style-' . $styleid . '-id-' . $value['id'] . '">
                        <div class="afoxi-admin-absulote"> 
                            <div class="afoxi-style-absulate-edit">
                                <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#afoxi-edit-heading"><i class="fa fa-cog" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Settings"></i></button>
                            </div> 
                            <div class="afoxi-style-absulate-edit">
                                <form method="post">
                                '.wp_nonce_field("accordionseditdata").'
                                    <input type="hidden" name="item-id" value="' . $value['id'] . '">
                                    <button class="btn btn-primary btn-xs" type="submit" value="edit" name="edit"><i class="fa fa-pencil-square-o" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Edit"></i></button>
                                </form>
                            </div> 
                            <div class="afoxi-style-absulate-delete">
                                <form method="post">
                                '.wp_nonce_field("accordionsdeletedata").'
                                    <input type="hidden" name="item-id" value="' . $value['id'] . '">
                                    <button class="btn btn-danger btn-xs" type="submit" value="delete" name="delete"><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Delete"></i></button>  
                                </form>
                            </div>
                        </div>
                        <span class="span-active"><i class="fa ' . $iconselect[1] . '" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa ' . $iconselect[0] . '" aria-hidden="true"></i></span>
                        <div class="heading-data"> ' . accordions_or_faqs_oxilab_special_charecter($value['title']) . '</div>
                    </div>
                    <div class="afoxi-content-style-' . $styleid . ' afoxi-content" id="afoxi-heading-style-' . $styleid . '-id-' . $value['id'] . '">
                        <div class="afoxi-admin-absulote"> 
                            <div class="afoxi-style-absulate-edit">
                                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#afoxi-edit-content"><i class="fa fa-cog" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Settings"></i></button>
                            </div> 
                        </div>
                      ' . accordions_or_faqs_oxilab_special_charecter($value['files']) . '
                    </div>';
                }
                echo '</div>';
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery(".afoxi-heading-style-<?php echo $styleid; ?>:first").addClass("active");
                        jQuery(".afoxi-heading-style-<?php echo $styleid; ?>:first").next().slideDown();
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
            jQuery('#heading-font-familly').fontselect().change(function () {
                var font = jQuery(this).val().replace(/\+/g, ' ');
                font = font.split(':');
                jQuery("<style type='text/css'> .afoxi-heading .heading-data{ font-family:" + font[0] + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-font-weight").on("change", function () {
                var idvalue = jQuery('#heading-font-weight').val();
                jQuery("<style type='text/css'> .afoxi-heading .heading-data{ font-weight:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-padding").on("change", function () {
                var idvalue = jQuery('#heading-padding').val() + 'px 0';
                jQuery("<style type='text/css'> .afoxi-heading { padding:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-size").on("change", function () {
                var idvalue = jQuery('#heading-icon-size').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-heading span .fa{ font-size:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-width").on("change", function () {
                var idvalue = jQuery('#heading-icon-width').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-heading .span-deactive, .afoxi-heading .span-active{ width:" + idvalue + "; height:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-color").on("change", function () {
                var idvalue = jQuery('#heading-icon-color').val();
                jQuery("<style type='text/css'> .afoxi-heading .span-deactive{ color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
                jQuery("<style type='text/css'> .afoxi-heading.active .span-active{ background-color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#heading-icon-background-color").on("change", function () {
                var idvalue = jQuery('#heading-icon-background-color').val();
                jQuery("<style type='text/css'> .afoxi-heading .span-deactive{ background-color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
                jQuery("<style type='text/css'> .afoxi-heading.active .span-active{ color:" + idvalue + ";} </style>").appendTo(".afoxi-heading");
            });
            jQuery("#content-font-size").on("change", function () {
                var idvalue = jQuery('#content-font-size').val() + 'px';
                jQuery("<style type='text/css'> .afoxi-content{ font-size:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-font-color").on("change", function () {
                var idvalue = jQuery('#content-font-color').val();
                jQuery("<style type='text/css'> .afoxi-content{ color:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-padding-top").on("change", function () {
                var idvalue = jQuery('#content-padding-top').val() + '%';
                jQuery("<style type='text/css'> .afoxi-content{ padding-top:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-padding-right").on("change", function () {
                var idvalue = jQuery('#content-padding-right').val() + '%';
                jQuery("<style type='text/css'> .afoxi-content{ padding-right:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-padding-bottom").on("change", function () {
                var idvalue = jQuery('#content-padding-bottom').val() + '%';
                jQuery("<style type='text/css'> .afoxi-content{ padding-bottom:" + idvalue + ";} </style>").appendTo(".afoxi-content");
            });
            jQuery("#content-padding-left").on("change", function () {
                var idvalue = jQuery('#content-padding-left').val() + '%';
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
                            <label for="heading-font-familly" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Choose Your Title Preferred font, Based on Google Font"> Font Family </label>
                            <div class="col-sm-6 nopadding">
                                <input class="afoxi-admin-font" type="text" name="heading-font-familly" id="heading-font-familly" value="<?php echo $styledata[5]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-font-weight" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Title Font Weight, Based on CSS Weight" >Font Weight </label>
                            <div class="col-sm-6 nopadding">
                                <select class="form-control" id="heading-font-weight" name="heading-font-weight">
                                    <option value="100" <?php
                                    if ($styledata[7] == '100') {
                                        echo 'selected';
                                    }
                                    ?>>100</option>
                                    <option value="200" <?php
                                    if ($styledata[7] == '200') {
                                        echo 'selected';
                                    }
                                    ?>>200</option>
                                    <option value="300" <?php
                                    if ($styledata[7] == '300') {
                                        echo 'selected';
                                    }
                                    ?>>300</option>
                                    <option value="400" <?php
                                    if ($styledata[7] == '400') {
                                        echo 'selected';
                                    }
                                    ?>>400</option>
                                    <option value="500" <?php
                                    if ($styledata[7] == '500') {
                                        echo 'selected';
                                    }
                                    ?>>500</option>
                                    <option value="600" <?php
                                    if ($styledata[7] == '600') {
                                        echo 'selected';
                                    }
                                    ?>>600</option>
                                    <option value="700" <?php
                                    if ($styledata[7] == '700') {
                                        echo 'selected';
                                    }
                                    ?>>700</option>
                                    <option value="800" <?php
                                    if ($styledata[7] == '800') {
                                        echo 'selected';
                                    }
                                    ?>>800</option>
                                    <option value="900" <?php
                                    if ($styledata[7] == '900') {
                                        echo 'selected';
                                    }
                                    ?>>900</option>
                                    <option value="normal" <?php
                                    if ($styledata[7] == 'normal') {
                                        echo 'selected';
                                    }
                                    ?>>Normal</option>
                                    <option value="bold" <?php
                                    if ($styledata[7] == 'bold') {
                                        echo 'selected';
                                    }
                                    ?>>Bold</option>
                                    <option value="lighter" <?php
                                    if ($styledata[7] == 'lighter') {
                                        echo 'selected';
                                    }
                                    ?>>Lighter</option>
                                    <option value="initial" <?php
                                    if ($styledata[7] == 'initial') {
                                        echo 'selected';
                                    }
                                    ?>>Initial</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-padding" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Use Padding to generate space around Title, Based on Pixel ">Padding </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[9]; ?>" id="heading-padding" name="heading-padding">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label class="col-sm-12 col-form-label" data-toggle="tooltip" data-placement="top" title="Set your Custom Close Open Icon">Icon Select </label>
                            <?php accordions_or_faqs_oxilab_icon_select_admin($styledata[11]); ?>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-size" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Set your Custom Close Open Icon Size">Icon Size </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[13]; ?>" id="heading-icon-size" name="heading-icon-size">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-width" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Set your Custom  Close Open Icon Space">Icon Width </label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[15]; ?>" id="heading-icon-width" name="heading-icon-width">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-color" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Set your Custom  Close Open Icon Color">Icon Color </label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-icon-color" name="heading-icon-color" value="<?php echo $styledata[17]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="heading-icon-background-color" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Set your Custom  Close Open Icon Background Color">Icon Background Color </label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="heading-icon-background-color" name="heading-icon-background-color" value="<?php echo $styledata[19]; ?>">
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
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[21]; ?>" id="content-font-size" name="content-font-size">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-font-color" class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="Set Custom Content Font Color, Based on Color">Color</label>
                            <div class="col-sm-6 nopadding">
                                <input type="text" class="form-control afoxi-vendor-color" id="content-font-color" name="content-font-color" value="<?php echo $styledata[23]; ?>">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-padding-top" class="col-sm-4 col-form-label" data-toggle="tooltip" data-placement="top" title="Use Padding to Generate Space Around Content as top, right, bottom, left. Based on Pixel ">Padding </label>
                            <div class="col-sm-2 nopadding2">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[25]; ?>" id="content-padding-top" name="content-padding-top">
                            </div>
                            <div class="col-sm-2 nopadding2">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[27]; ?>" id="content-padding-right" name="content-padding-right">
                            </div>
                            <div class="col-sm-2 nopadding2">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[29]; ?>" id="content-padding-bottom" name="content-padding-bottom">
                            </div>
                            <div class="col-sm-2 nopadding2">
                                <input class="form-control" type="number"  min="0" value="<?php echo $styledata[31]; ?>" id="content-padding-left" name="content-padding-left">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-line-height" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Content Font Line Height, Based on Point">Line Height</label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="number" step="0.1"  min="0" value="<?php echo $styledata[33]; ?>" id="content-line-height" name="content-line-height">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-font-familly" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Choose Your Content Font, Based on Google Font"> Font Family</label>
                            <div class="col-sm-6 nopadding">
                                <input class="cau-admin-font" value="<?php echo $styledata[35]; ?>" type="text" name="content-font-familly" id="content-font-familly">
                            </div>
                        </div>
                        <div class="form-group row form-group-sm">
                            <label for="content-font-weight" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Use Font weight to Generate Custom. Based on Point ">Font Weight </label>
                            <div class="col-sm-6 nopadding">
                                <select class="form-control" id="content-font-weight" name="content-font-weight">
                                    <option value="100" <?php
                                    if ($styledata[37] == '100') {
                                        echo 'selected';
                                    }
                                    ?>>100</option>
                                    <option value="200" <?php
                                    if ($styledata[37] == '200') {
                                        echo 'selected';
                                    }
                                    ?>>200</option>
                                    <option value="300" <?php
                                    if ($styledata[37] == '300') {
                                        echo 'selected';
                                    }
                                    ?>>300</option>
                                    <option value="400" <?php
                                    if ($styledata[37] == '400') {
                                        echo 'selected';
                                    }
                                    ?>>400</option>
                                    <option value="500" <?php
                                    if ($styledata[37] == '500') {
                                        echo 'selected';
                                    }
                                    ?>>500</option>
                                    <option value="600" <?php
                                    if ($styledata[37] == '600') {
                                        echo 'selected';
                                    }
                                    ?>>600</option>
                                    <option value="700" <?php
                                    if ($styledata[37] == '700') {
                                        echo 'selected';
                                    }
                                    ?>>700</option>
                                    <option value="800" <?php
                                    if ($styledata[37] == '800') {
                                        echo 'selected';
                                    }
                                    ?>>800</option>
                                    <option value="900" <?php
                                    if ($styledata[37] == '900') {
                                        echo 'selected';
                                    }
                                    ?>>900</option>
                                    <option value="normal" <?php
                                    if ($styledata[37] == 'normal') {
                                        echo 'selected';
                                    }
                                    ?>>Normal</option>
                                    <option value="bold" <?php
                                    if ($styledata[37] == 'bold') {
                                        echo 'selected';
                                    }
                                    ?>>Bold</option>
                                    <option value="lighter" <?php
                                    if ($styledata[37] == 'lighter') {
                                        echo 'selected';
                                    }
                                    ?>>Lighter</option>
                                    <option value="initial" <?php
                                    if ($styledata[37] == 'initial') {
                                        echo 'selected';
                                    }
                                    ?>>Initial</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row form-group-sm">
                            <label for="content-text-align" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Text Align will align of your content.">Text Align </label>
                            <div class="col-sm-6 nopadding">
                                <select class="form-control" id="content-text-align" name="content-text-align">
                                    <option value="left" <?php
                                    if ($styledata[39] == 'left') {
                                        echo 'selected';
                                    }
                                    ?>>Left</option>
                                    <option value="center" <?php
                                    if ($styledata[39] == 'center') {
                                        echo 'selected';
                                    }
                                    ?>>Center</option>
                                    <option value="right" <?php
                                    if ($styledata[39] == 'right') {
                                        echo 'selected';
                                    }
                                    ?>>Right</option>
                                </select>
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