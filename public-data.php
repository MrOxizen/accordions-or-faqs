<?php
if (!defined('ABSPATH'))
    exit;

function afoxi_ultimate_oxi_shortcode_function($data) {
    $id = (int) $data;
    global $wpdb;
    $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
    $style = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $id), ARRAY_A);
    if ($style['style_name'] == 'style1') {
        afoxi_ultimate_oxi_shortcode_style1($id);
    }
    if ($style['style_name'] == 'style2') {
        afoxi_ultimate_oxi_shortcode_style2($id);
    }
    if ($style['style_name'] == 'style3') {
        afoxi_ultimate_oxi_shortcode_style3($id);
    }
    if ($style['style_name'] == 'style4') {
        afoxi_ultimate_oxi_shortcode_style4($id);
    }
    if ($style['style_name'] == 'style5') {
        afoxi_ultimate_oxi_shortcode_style5($id);
    }
    wp_enqueue_style('font-awesome', plugins_url('js-css/font-awesome.min.css', __FILE__));
}

function afoxi_ultimate_oxi_shortcode_style1($data) {
    $styleid = $data;
    global $wpdb;
    $table_list = $wpdb->prefix . 'accordions_or_faqs_items';
    $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
    $listdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_list WHERE styleid = %d ORDER by id ASC ", $styleid), ARRAY_A);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $styleid), ARRAY_A);
    $styledata = $styledata['css'];
    $styledata = explode('|', $styledata);
    wp_enqueue_style('cau-google-font', 'https://fonts.googleapis.com/css?family=' . $styledata[7] . '|' . $styledata[37] . '');
    ?>
    <style>
        .afoxi-wrapper{
            width: 100%;
            float:left;
            padding: 20px;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>{
            width: 100%;
            float: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: <?php echo $styledata[11]; ?>px 0;
        }
        .afoxi-heading-style-<?php echo $styleid; ?> .heading-data{
            width: calc(100% - <?php echo $styledata[15] + 10; ?>px);
            float: left;
            color: <?php echo $styledata[3]; ?>;
            font-size: <?php echo $styledata[1]; ?>px;
            font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[7]); ?>;
            font-weight: <?php echo $styledata[9]; ?>;
            line-height: 100%;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active .heading-data, .afoxi-heading-style-<?php echo $styleid; ?>:hover  .heading-data{
            color:  <?php echo $styledata[5]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active .span-active{
            display: flex;
            float: left;
            align-items: center;
            justify-content: center;
            width: <?php echo $styledata[15]; ?>px;
            height: <?php echo $styledata[15]; ?>px;
            color:  <?php echo $styledata[5]; ?>;
            border-radius: <?php echo $styledata[19]; ?>px;
            border: 1px solid ;
            border-color:<?php echo $styledata[5]; ?>;
            margin-right: 10px;
            background-color: <?php echo $styledata[17]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?> .span-deactive{
            display: flex;
            float: left;
            align-items: center;
            justify-content: center;
            width: <?php echo $styledata[15]; ?>px;
            height: <?php echo $styledata[15]; ?>px;
            color:  <?php echo $styledata[17]; ?>;
            border-radius: <?php echo $styledata[19]; ?>px;
            border: 1px solid  ;
            border-color: <?php echo $styledata[5]; ?>;
            margin-right: 10px;
            background-color:  <?php echo $styledata[5]; ?>; 
        }
        .afoxi-heading-style-<?php echo $styleid; ?>:hover .span-deactive{
            color:  <?php echo $styledata[5]; ?>;
            border-color: <?php echo $styledata[5]; ?>;
            background-color:  <?php echo $styledata[17]; ?>; 
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
            background-color: <?php echo $styledata[25]; ?>;
            font-weight: <?php echo $styledata[39]; ?>;
            font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[37]); ?>;
            line-height: <?php echo $styledata[35]; ?>;
            text-align: <?php echo $styledata[41]; ?>;
            border-radius:<?php echo $styledata[47]; ?>px <?php echo $styledata[47]; ?>px <?php echo $styledata[49]; ?>px <?php echo $styledata[49]; ?>px;
            box-shadow:  0  0 <?php echo $styledata[43]; ?>px <?php echo $styledata[45]; ?>;
            padding: <?php echo $styledata[27]; ?>% <?php echo $styledata[29]; ?>%  <?php echo $styledata[31]; ?>% <?php echo $styledata[33]; ?>%;
        }
    </style>
    <?php
    $iconselect = explode('++', $styledata[51]);
    echo '<div class="afoxi-wrapper">';
    foreach ($listdata as $value) {
        echo '  <div class="afoxi-heading-style-' . $styleid . '" ref="#afoxi-heading-style-' . $styleid . '-id-' . $value['id'] . '">
                                    <span class="span-active"><i class="fa ' . $iconselect[1] . '" aria-hidden="true"></i></span>
                                    <span class="span-deactive"><i class="fa ' . $iconselect[0] . '" aria-hidden="true"></i></span>
                                    <div class="heading-data"> ' . accordions_or_faqs_oxilab_special_charecter($value['title']) . '</div>
                                </div>
                                <div class="afoxi-content-style-' . $styleid . '" id="afoxi-heading-style-' . $styleid . '-id-' . $value['id'] . '">
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
    <?php
}

function afoxi_ultimate_oxi_shortcode_style2($data) {
    $styleid = $data;
    global $wpdb;
    $table_list = $wpdb->prefix . 'accordions_or_faqs_items';
    $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
    $listdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_list WHERE styleid = %d ORDER by id ASC ", $styleid), ARRAY_A);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $styleid), ARRAY_A);
    $styledata = $styledata['css'];
    $styledata = explode('|', $styledata);
    wp_enqueue_style('cau-google-font', 'https://fonts.googleapis.com/css?family=' . $styledata[5] . '|' . $styledata[35] . '');
    ?>
    <style>
        .afoxi-wrapper{
            width: 100%;
            float:left;
            padding: 20px;
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
        echo '  <div class="afoxi-heading-style-' . $styleid . '" ref="#afoxi-heading-style-' . $styleid . '-id-' . $value['id'] . '">
                        <span class="span-active"><i class="fa ' . $iconselect[1] . '" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa ' . $iconselect[0] . '" aria-hidden="true"></i></span>
                        <div class="heading-data"> ' . accordions_or_faqs_oxilab_special_charecter($value['title']) . '</div>
                    </div>
                    <div class="afoxi-content-style-' . $styleid . '" id="afoxi-heading-style-' . $styleid . '-id-' . $value['id'] . '">
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
    <?php
}

function afoxi_ultimate_oxi_shortcode_style3($data) {
    $styleid = $data;
    global $wpdb;
    $table_list = $wpdb->prefix . 'accordions_or_faqs_items';
    $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
    $listdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_list WHERE styleid = %d ORDER by id ASC ", $styleid), ARRAY_A);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $styleid), ARRAY_A);
    $styledata = $styledata['css'];
    $styledata = explode('|', $styledata);
    wp_enqueue_style('cau-google-font', 'https://fonts.googleapis.com/css?family=' . $styledata[3] . '|' . $styledata[37] . '');
    ?>
    <style>
        .afoxi-wrapper{
            width: 100%;
            float:left;
            padding: 20px;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>{
            width: 100%;
            float: left;
            cursor: pointer;
            display: flex;
            align-items:  stretch;
            margin: <?php echo $styledata[11]; ?>px 0;
            box-shadow: 0 0 <?php echo $styledata[21]; ?>px <?php echo $styledata[23]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?> .afoxi-heading-style-<?php echo $styleid; ?>-content{
            width: calc(100% - <?php echo $styledata[17]; ?>px);
            float: left;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active .afoxi-heading-style-<?php echo $styleid; ?>-content{
            width: calc(100% - <?php echo $styledata[19]; ?>px);
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active .afoxi-heading-style-<?php echo $styleid; ?>-icon{
            width: <?php echo $styledata[19]; ?>px;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>-content .heading-data{
            font-size: <?php echo $styledata[1]; ?>px;
            padding: <?php echo $styledata[7]; ?>px 10px <?php echo $styledata[7]; ?>px <?php echo $styledata[9]; ?>px;
            font-family: <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[3]); ?>;
            font-weight: <?php echo $styledata[5]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>-icon{
            display: flex;
            float: left;
            width: <?php echo $styledata[17]; ?>px;
            align-items: center;
            justify-content: center;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>-icon .fa{
            font-size: <?php echo $styledata[13]; ?>px;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active .afoxi-heading-style-<?php echo $styleid; ?>-icon .fa{
            font-size: <?php echo $styledata[15]; ?>px;
        }
        .afoxi-content-style-<?php echo $styleid; ?>{
            display: none;
            width: 100%;
            float: left;
            font-size: <?php echo $styledata[25]; ?>px;
            font-weight: <?php echo $styledata[39]; ?>;
            font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[37]); ?>;
            line-height: <?php echo $styledata[35]; ?>;
            text-align: <?php echo $styledata[41]; ?>;
            padding: <?php echo $styledata[27]; ?>px <?php echo $styledata[29]; ?>px  <?php echo $styledata[31]; ?>px <?php echo $styledata[33]; ?>px;
        }
    </style>
    <?php
    echo '<div class="afoxi-wrapper"> ';

    foreach ($listdata as $value) {
        $cssdata = explode('|', $value['css']);
        echo '<div class="afoxi-heading-style-' . $styleid . '" ref="#afoxi-heading-style-' . $styleid . '-id-' . $value['id'] . '">
                                <div class="afoxi-heading-style-' . $styleid . '-icon" style=" color: ' . $cssdata[3] . '; background-color: ' . $cssdata[7] . ';">
                                    <i class="fa ' . $cssdata[1] . '" aria-hidden="true"></i>
                                </div>
                                <div class="afoxi-heading-style-' . $styleid . '-content"  style="color:' . $cssdata[3] . ';background-color: ' . $cssdata[5] . '">
                                    <div class="heading-data"> 
                                        ' . accordions_or_faqs_oxilab_special_charecter($value['title']) . '
                                        </div>
                                    <div class="afoxi-content-style-' . $styleid . '" id="afoxi-heading-style-' . $styleid . '-id-' . $value['id'] . '">
                                        ' . accordions_or_faqs_oxilab_special_charecter($value['files']) . '
                                    </div>
                                </div>
                    </div>';
    }
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
    <?php
}

function afoxi_ultimate_oxi_shortcode_style4($data) {
    $styleid = $data;
    global $wpdb;
    $table_list = $wpdb->prefix . 'accordions_or_faqs_items';
    $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
    $listdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_list WHERE styleid = %d ORDER by id ASC ", $styleid), ARRAY_A);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $styleid), ARRAY_A);
    $styledata = $styledata['css'];
    $styledata = explode('|', $styledata);
    wp_enqueue_style('cau-google-font', 'https://fonts.googleapis.com/css?family=' . $styledata[15] . '|' . $styledata[47] . '');
    ?>
    <style>
        .afoxi-wrapper{
            width: 100%;
            float:left;
            padding: 20px;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>{
            width: 100%;
            float: left;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            background-color: <?php echo $styledata[7]; ?>;
            border-top: 2px solid;
            border-color: <?php echo $styledata[11]; ?>;
            padding: <?php echo $styledata[19]; ?>px 0;
            margin-bottom: <?php echo $styledata[21]; ?>px;
            box-shadow: 0 0 <?php echo $styledata[27]; ?>px <?php echo $styledata[29]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>:first-child{
            margin-top: <?php echo $styledata[21]; ?>px;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active{
            background-color: <?php echo $styledata[9]; ?>;
            border-color:  <?php echo $styledata[13]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?> .afoxi-heading-icon{
            width: <?php echo $styledata[25]; ?>px;
            float: left;
            text-align: center;
        }
        .afoxi-heading-style-<?php echo $styleid; ?> .afoxi-heading-icon .fa{
            font-size: <?php echo $styledata[23]; ?>px;
            color: <?php echo $styledata[3]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active .afoxi-heading-icon .fa{
            color:  <?php echo $styledata[5]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?> .heading-data{
            width: calc(100% - <?php echo $styledata[25]; ?>px);
            font-size: <?php echo $styledata[1]; ?>px;
            color: <?php echo $styledata[3]; ?>;
            font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[15]); ?>;
            font-weight: <?php echo $styledata[17]; ?>;
            padding-left: 10px;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active .heading-data{
            color: <?php echo $styledata[5]; ?>;
        }
        .afoxi-heading-style-<?php echo $styleid; ?>.active .afoxi-heading-absulote{
            position: absolute;
            display: block;
            bottom: -10px;
            left: <?php echo $styledata[25]; ?>px;
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right:10px solid transparent;
            border-top: 10px solid <?php echo $styledata[9]; ?>;
        }
        .afoxi-content-style-<?php echo $styleid; ?>{
            width: 100%;
            float: left;
            display: none;
            text-align: <?php echo $styledata[51]; ?>;
            font-size: <?php echo $styledata[31]; ?>px;
            color: <?php echo $styledata[33]; ?>;
            background-color: <?php echo $styledata[35]; ?>;
            font-family:  <?php echo accordions_or_faqs_oxilab_font_familly_charecter($styledata[47]); ?>;
            font-weight: <?php echo $styledata[49]; ?>;
            line-height: <?php echo $styledata[45]; ?>;
            padding:  <?php echo $styledata[37]; ?>px <?php echo $styledata[39]; ?>px <?php echo $styledata[41]; ?>px <?php echo $styledata[43]; ?>px;
            margin-bottom: <?php echo $styledata[21]; ?>px;
            box-shadow: 0 0 <?php echo $styledata[53]; ?>px <?php echo $styledata[55]; ?>;
        }
    </style>
    <?php
    echo '<div class="afoxi-wrapper">';
    foreach ($listdata as $value) {
        echo '<div class="afoxi-heading-style-' . $styleid . ' afoxi-heading" ref="#afoxi-content-style-' . $styleid . '-id-' . $value['id'] . '">
                                <div class="afoxi-heading-icon">
                                    <i class="fa ' . accordions_or_faqs_oxilab_special_charecter($value['css']) . '" aria-hidden="true"></i>
                                </div>
                                <div class="heading-data">
                                   ' . accordions_or_faqs_oxilab_special_charecter($value['title']) . '
                                </div>
                                <div class="afoxi-heading-absulote">
                                </div>
                            </div>
                            <div class="afoxi-content-style-' . $styleid . ' afoxi-content" id="afoxi-content-style-' . $styleid . '-id-' . $value['id'] . '">
                                ' . accordions_or_faqs_oxilab_special_charecter($value['files']) . '
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
    <?php
}

function afoxi_ultimate_oxi_shortcode_style5($data) {
    $styleid = $data;
    global $wpdb;
    $table_list = $wpdb->prefix . 'accordions_or_faqs_items';
    $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
    $listdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_list WHERE styleid = %d ORDER by id ASC ", $styleid), ARRAY_A);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $styleid), ARRAY_A);
    $styledata = $styledata['css'];
    $styledata = explode('|', $styledata);
    wp_enqueue_style('cau-google-font', 'https://fonts.googleapis.com/css?family=' . $styledata[23] . '|' . $styledata[51] . '');
    ?>
    <style>
        .afoxi-wrapper{
            width: 100%;
            float:left;
            padding: 20px;
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
                            </div>
                            <div class="afoxi-content-style-' . $styleid . ' afoxi-content" id="afoxi-content-style-' . $styleid . '-id-' . $value['id'] . '">
                                 ' . accordions_or_faqs_oxilab_special_charecter($value['files']) . '
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
    <?php
}
