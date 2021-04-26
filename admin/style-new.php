<?php
if (!defined('ABSPATH'))
    exit;
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}
if (!empty($_POST['submit'])) {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'accordionsadddata')) {
        die('You do not have sufficient permissions to access this page.');
    } else {
        $style = sanitize_text_field($_POST['style']);
        if ($style == 'style1') {
            $name = sanitize_text_field($_POST['style-name']);
            $style_name = 'style1';
            $css = 'heading-font-size |20| heading-font-color |#949494|heading-font-active-color |#00d6ab| heading-font-familly |Anonymous+Pro| heading-font-weight |400| heading-padding |10| heading-icon-size |10| heading-icon-width |30| heading-icon-color |#ffffff| heading-icon-border-radius |5| content-font-size |17| content-font-color |#4f4f4f| content-background-color |#ffffff| content-padding-top |2| content-padding-right |2| content-padding-bottom |2| content-padding-left |2| content-line-height |1.4| content-font-familly |Anonymous+Pro| content-font-weight |100| content-text-align |left| content-box-shadow |7| content-box-shadow-color |#d1d1d1| content-border-radius-up |1| content-border-radius-bottom |5| head-icon-select |fa-arrow-right++fa-arrow-down||';
        }
        if ($style == 'style2') {
            $name = sanitize_text_field($_POST['style-name']);
            $style_name = 'style2';
            $css = 'heading-font-size |23| heading-font-color |#636363| heading-font-familly |Annie+Use+Your+Telescope| heading-font-weight |700| heading-padding |10| head-icon-select |fa-plus++fa-times| heading-icon-size |10| heading-icon-width |26| heading-icon-color |#ffffff| heading-icon-background-color |#01702a| content-font-size |16| content-font-color |#2e2e2e| content-padding-top |2| content-padding-right |3| content-padding-bottom |2| content-padding-left |2| content-line-height |1.5| content-font-familly |Anonymous+Pro| content-font-weight |400| content-text-align |left| |';
        }
        if ($style == 'style3') {
            $name = sanitize_text_field($_POST['style-name']);
            $style_name = 'style3';
            $css = 'heading-font-size |20| heading-font-familly |4| heading-font-weight |100| heading-padding-top |15| heading-padding-left |20| heading-margin |5| heading-icon-size |20| heading-icon-active-size |60| heading-icon-width |50| heading-icon-active-width |100| heading-box-shadow |3| heading-box-shadow-color |#212121| content-font-size |16| content-padding-top |0| content-padding-right |12| content-padding-bottom |15| content-padding-left |20| content-line-height |1.5| content-font-familly |Annie+Use+Your+Telescope| content-font-weight |400| content-text-align |left||';
        }
        if ($style == 'style4') {
            $name = sanitize_text_field($_POST['style-name']);
            $style_name = 'style4';
            $css = 'heading-font-size |21| heading-font-color |#b8b8b8|heading-font-active-color |#5c5c5c| heading-background-color |#964a4a|heading-background-active-color |#ccb3b3|heading-border-color |#d17171|heading-border-active-color |#c40404| heading-font-familly |Anonymous+Pro| heading-font-weight |600| heading-padding |18| heading-margin-bottom |20| heading-icon-size |30| heading-icon-width |50| heading-box-shadow |6| heading-box-shadow-color |#e08282| content-font-size |16| content-font-color |#9e9e9e| content-background-color |#ffffff| content-padding-top |15| content-padding-right |10| content-padding-bottom |20| content-padding-left |10| content-line-height |1.7| content-font-familly |Anonymous+Pro| content-font-weight |400| content-text-align |center| content-box-shadow |1| content-box-shadow-color |#262626||';
        }
        if ($style == 'style5') {
            $name = sanitize_text_field($_POST['style-name']);
            $style_name = 'style5';
            $css = 'heading-font-size |18| heading-font-color |#4f4f4f|heading-font-active-color |#ffffff| heading-background-color |#5ce4ff|heading-background-active-color |#8a8a8a| heading-icon-color |#383838|heading-icon-active-color |#ffffff|heading-icon-background-color |#14ff7e|heading-icon-background-active-color |#00baa4| heading-icon-size |30| heading-icon-width |60| heading-font-familly |Anonymous+Pro| heading-font-weight |700| heading-padding-left |40| heading-margin-bottom |10| heading-box-shadow |2| heading-box-shadow-color |#3d3d3d| content-font-size |17| content-font-color |#595959| content-background-color |#ffffff| content-padding-top |15| content-padding-right |15| content-padding-bottom |15| content-padding-left |15| content-line-height |1.5| content-font-familly |Anonymous+Pro| content-font-weight |200| content-text-align |left| content-box-shadow |2| content-box-shadow-color |#8f8f8f|| ';
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
        $wpdb->query($wpdb->prepare("INSERT INTO {$table_name} (name, style_name, css) VALUES ( %s, %s, %s )", array($name, $style_name, $css)));
        $redirect_id = $wpdb->insert_id;
        if ($redirect_id == 0) {
            $url = admin_url("admin.php?page=accordions-or-faqs-oxilab-new");
        }
        if ($redirect_id != 0) {
            $url = admin_url("admin.php?page=accordions-or-faqs-oxilab-new&styleid=$redirect_id");
        }
        echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
        exit;
    }
}
?>
<div class="wrap">
    <div class="afoxi-admin-wrapper">
        <h1>Select Style</h1>
        <div class="afoxi-admin-select-row">
            <div class="afoxi-admin-select-row-upper">
                <style>
                    .afoxi-wrapper{
                        width: 100%;
                        float:left;
                    }
                    .afoxi-heading-style-3{
                        width: 100%;
                        float: left;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        padding: 10px 0;
                    }
                    .afoxi-heading-style-3 .heading-data{
                        width: calc(100% - 40px);
                        float: left;
                        color: #949494;
                        font-size: 20px;
                        font-family:  Anonymous Pro;
                        font-weight: 400;
                        line-height: 100%;
                    }
                    .afoxi-heading-style-3.active .heading-data, .afoxi-heading-style-3:hover  .heading-data{
                        color:  #00d6ab;
                    }
                    .afoxi-heading-style-3.active .span-active{
                        display: flex;
                        float: left;
                        align-items: center;
                        justify-content: center;
                        width: 30px;
                        height: 30px;
                        color:  #00d6ab;
                        border-radius: 5px;
                        border: 1px solid ;
                        border-color:#00d6ab;
                        margin-right: 10px;
                        background-color: #ffffff;
                    }
                    .afoxi-heading-style-3 .span-deactive{
                        display: flex;
                        float: left;
                        align-items: center;
                        justify-content: center;
                        width: 30px;
                        height: 30px;
                        color:  #ffffff;
                        border-radius: 5px;
                        border: 1px solid  ;
                        border-color: #00d6ab;
                        margin-right: 10px;
                        background-color:  #00d6ab; 
                    }
                    .afoxi-heading-style-3:hover .span-deactive{
                        color:  #00d6ab;
                        border-color: #00d6ab;
                        background-color:  #ffffff; 
                    }
                    .afoxi-heading-style-3 .span-active{
                        display: none;
                    }
                    .afoxi-heading-style-3.active .span-deactive{
                        display: none;
                    }
                    .afoxi-heading-style-3 span .fa{
                        font-size: 10px;
                    }
                    .afoxi-content-style-3{
                        display: none;
                        width: 100%;
                        float: left;
                        font-size: 17px;
                        color:#4f4f4f;
                        background-color: #ffffff;
                        font-weight: 100;
                        font-family:  Anonymous Pro;
                        line-height: 1.4;
                        text-align: left;
                        border-radius:1px 1px 5px 5px;
                        box-shadow:  0  0 7px #d1d1d1;
                        padding: 2% 2%  2% 2%;
                    }
                </style>
                <div class="afoxi-wrapper">  <div class="afoxi-heading-style-3" ref="#afoxi-heading-style-3-id-2">
                        <span class="span-active"><i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
                        <div class="heading-data"> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
                    </div>
                    <div class="afoxi-content-style-3" id="afoxi-heading-style-3-id-2">
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                    </div>  <div class="afoxi-heading-style-3" ref="#afoxi-heading-style-3-id-3">
                        <span class="span-active"><i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
                        <div class="heading-data"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                    </div>
                    <div class="afoxi-content-style-3" id="afoxi-heading-style-3-id-3">
                        Integer placerat eleifend lectus finibus blandit. Duis non semper est. Aliquam ac magna laoreet, facilisis odio ac, ornare velit. Vestibulum eu enim eget felis iaculis semper. Nulla sed felis augue. Mauris quis enim aliquam, semper lectus id, maximus tellus. Pellentesque eu pretium velit.
                    </div>  <div class="afoxi-heading-style-3" ref="#afoxi-heading-style-3-id-15">
                        <span class="span-active"><i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
                        <div class="heading-data"> Neque porro quisquam est qui dolorem ipsum quia dolor</div>
                    </div>
                    <div class="afoxi-content-style-3" id="afoxi-heading-style-3-id-15">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    </div>  <div class="afoxi-heading-style-3" ref="#afoxi-heading-style-3-id-16">
                        <span class="span-active"><i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
                        <div class="heading-data"> Content here, content here', making it look like readable</div>
                    </div>
                    <div class="afoxi-content-style-3" id="afoxi-heading-style-3-id-16">
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using
                    </div></div>    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery(".afoxi-heading-style-3:first").addClass("active");
                            jQuery(".afoxi-heading-style-3:first").next().slideDown();
                            jQuery(".afoxi-heading-style-3").click(function () {
                                jQuery(".afoxi-heading-style-3").removeClass("active");
                                jQuery(this).toggleClass("active");
                                jQuery(".afoxi-content-style-3").slideUp();
                                var activeTab = jQuery(this).attr("ref");
                                jQuery(activeTab).slideDown();

                            });
                        });
                </script>
            </div>
            <div class="afoxi-admin-select-row-buttom">
                <div class="afoxi-admin-select-row-buttom-left">
                    Style 1
                </div>
                <div class="afoxi-admin-select-row-buttom-right">
                    <button type="button" class="btn btn-success"  data-target="style1">Select</button>
                </div>

            </div>
        </div>
        <div class="afoxi-admin-select-row">
            <div class="afoxi-admin-select-row-upper">
                <style>
                    .afoxi-wrapper{
                        width: 100%;
                        float:left;
                    }
                    .afoxi-heading-style-6{
                        width: 100%;
                        float: left;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        padding: 10px 0;
                    }
                    .afoxi-heading-style-6 .heading-data{
                        width: calc(100% - 36px);
                        float: left;
                        color: #636363;
                        font-size: 23px;
                        font-family:  Annie Use Your Telescope;
                        font-weight: 700;
                        line-height: 100%;
                    }
                    .afoxi-heading-style-6.active .span-active{
                        display: flex;
                        float: left;
                        align-items: center;
                        justify-content: center;
                        width: 26px;
                        height: 26px;
                        color:  #01702a;
                        margin-right: 10px;
                        background-color:#ffffff;
                    }
                    .afoxi-heading-style-6 .span-deactive{
                        display: flex;
                        float: left;
                        align-items: center;
                        justify-content: center;
                        width:26px;
                        height:26px;
                        color:  #ffffff;
                        margin-right: 10px;
                        background-color:  #01702a; 
                    }
                    .afoxi-heading-style-6 .span-active{
                        display: none;
                    }
                    .afoxi-heading-style-6.active .span-deactive{
                        display: none;
                    }
                    .afoxi-heading-style-6 span .fa{
                        font-size: 10px;
                    }
                    .afoxi-content-style-6{
                        display: none;
                        width: 100%;
                        float: left;
                        font-size: 16px;
                        color:#2e2e2e;
                        font-weight: 1.5;
                        font-family:  Anonymous Pro;
                        line-height: 1.5;
                        text-align: left;
                        padding: 2% 3%  2% 2%;
                    }
                </style>
                <div class="afoxi-wrapper">  <div class="afoxi-heading-style-6" ref="#afoxi-heading-style-6-id-25">
                        <span class="span-active"><i class="fa fa-times" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        <div class="heading-data"> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
                    </div>
                    <div class="afoxi-content-style-6" id="afoxi-heading-style-6-id-25">
                        Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                    </div>  <div class="afoxi-heading-style-6" ref="#afoxi-heading-style-6-id-26">
                        <span class="span-active"><i class="fa fa-times" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        <div class="heading-data"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                    </div>
                    <div class="afoxi-content-style-6" id="afoxi-heading-style-6-id-26">
                        Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                    </div>  <div class="afoxi-heading-style-6" ref="#afoxi-heading-style-6-id-27">
                        <span class="span-active"><i class="fa fa-times" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        <div class="heading-data"> Neque porro quisquam est qui dolorem ipsum quia dolor</div>
                    </div>
                    <div class="afoxi-content-style-6" id="afoxi-heading-style-6-id-27">
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use
                    </div>  <div class="afoxi-heading-style-6" ref="#afoxi-heading-style-6-id-28">
                        <span class="span-active"><i class="fa fa-times" aria-hidden="true"></i></span>
                        <span class="span-deactive"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        <div class="heading-data"> Content here, content here', making it look like readable</div>
                    </div>
                    <div class="afoxi-content-style-6" id="afoxi-heading-style-6-id-28">
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use
                    </div></div>    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery(".afoxi-heading-style-6:first").addClass("active");
                            jQuery(".afoxi-heading-style-6:first").next().slideDown();
                            jQuery(".afoxi-heading-style-6").click(function () {
                                jQuery(".afoxi-heading-style-6").removeClass("active");
                                jQuery(this).toggleClass("active");
                                jQuery(".afoxi-content-style-6").slideUp();
                                var activeTab = jQuery(this).attr("ref");
                                jQuery(activeTab).slideDown();

                            });
                        });
                </script>
            </div>
            <div class="afoxi-admin-select-row-buttom">
                <div class="afoxi-admin-select-row-buttom-left">
                    Style 2
                </div>
                <div class="afoxi-admin-select-row-buttom-right">
                    <div class="afoxi-admin-select-row-buttom-right">
                        <button type="button" class="btn btn-success"  data-target="style2">Select</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="afoxi-admin-select-row">
            <div class="afoxi-admin-select-row-upper">
                <style>
                    .afoxi-wrapper{
                        width: 100%;
                        float:left;
                    }
                    .afoxi-heading-style-7{
                        width: 100%;
                        float: left;
                        cursor: pointer;
                        display: flex;
                        align-items:  stretch;
                        margin: 5px 0;
                        box-shadow: 0 0 3px #212121;
                    }
                    .afoxi-heading-style-7 .afoxi-heading-style-7-content{
                        width: calc(100% - 50px);
                        float: left;
                    }
                    .afoxi-heading-style-7.active .afoxi-heading-style-7-content{
                        width: calc(100% - 100px);
                    }
                    .afoxi-heading-style-7.active .afoxi-heading-style-7-icon{
                        width: 100px;
                    }
                    .afoxi-heading-style-7-content .heading-data{
                        font-size: 20px;
                        padding: 15px 10px 15px 20px;
                        font-family: 4;
                        font-weight: 100;
                    }
                    .afoxi-heading-style-7-icon{
                        display: flex;
                        float: left;
                        width: 50px;
                        align-items: center;
                        justify-content: center;
                    }
                    .afoxi-heading-style-7-icon .fa{
                        font-size: 20px;
                    }
                    .afoxi-heading-style-7.active .afoxi-heading-style-7-icon .fa{
                        font-size: 60px;
                    }
                    .afoxi-content-style-7{
                        display: none;
                        width: 100%;
                        float: left;
                        font-size: 16px;
                        font-weight: 400;
                        font-family:  Annie Use Your Telescope;
                        line-height: 1.5;
                        text-align: left;
                        padding: 0px 12px  15px 20px;
                    }
                </style>
                <div class="afoxi-wrapper"> <div class="afoxi-heading-style-7" ref="#afoxi-heading-style-7-id-6">
                        <div class="afoxi-heading-style-7-icon" style=" color: #ffffff; background-color: #9e7575;">
                            <i class="fa fa-coffee" aria-hidden="true"></i>
                        </div>
                        <div class="afoxi-heading-style-7-content"  style="color:#ffffff;background-color: #bf6767">
                            <div class="heading-data"> 
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="afoxi-content-style-7" id="afoxi-heading-style-7-id-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore eten dolore magna aliqua. Ut enim ad minim veniam, quis exercitation ullamco laboris nisi ut aliquip ex ea com mmodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.Eu fugiat nu lla pariatur. Excepteur sint occaecat cupidatat non proident.
                            </div>
                        </div>
                    </div><div class="afoxi-heading-style-7" ref="#afoxi-heading-style-7-id-7">
                        <div class="afoxi-heading-style-7-icon" style=" color: #ffffff; background-color: #757575;">
                            <i class="fa fa-coffee" aria-hidden="true"></i>
                        </div>
                        <div class="afoxi-heading-style-7-content"  style="color:#ffffff;background-color: #a1a1a1">
                            <div class="heading-data"> 
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </div>
                            <div class="afoxi-content-style-7" id="afoxi-heading-style-7-id-7">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore eten dolore magna aliqua. Ut enim ad minim veniam, quis exercitation ullamco laboris nisi ut aliquip ex ea com mmodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.Eu fugiat nu lla pariatur. Excepteur sint occaecat cupidatat non proident.
                            </div>
                        </div>
                    </div><div class="afoxi-heading-style-7" ref="#afoxi-heading-style-7-id-8">
                        <div class="afoxi-heading-style-7-icon" style=" color: #ffffff; background-color: #045c15;">
                            <i class="fa fa-coffee" aria-hidden="true"></i>
                        </div>
                        <div class="afoxi-heading-style-7-content"  style="color:#ffffff;background-color: #11ab6d">
                            <div class="heading-data"> 
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="afoxi-content-style-7" id="afoxi-heading-style-7-id-8">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore eten dolore magna aliqua. Ut enim ad minim veniam, quis exercitation ullamco laboris nisi ut aliquip ex ea com mmodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.Eu fugiat nu lla pariatur. Excepteur sint occaecat cupidatat non proident.
                            </div>
                        </div>
                    </div><div class="afoxi-heading-style-7" ref="#afoxi-heading-style-7-id-9">
                        <div class="afoxi-heading-style-7-icon" style=" color: #ffffff; background-color: #a8a8a8;">
                            <i class="fa fa-coffee" aria-hidden="true"></i>
                        </div>
                        <div class="afoxi-heading-style-7-content"  style="color:#ffffff;background-color: #bf6767">
                            <div class="heading-data"> 
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="afoxi-content-style-7" id="afoxi-heading-style-7-id-9">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore eten dolore magna aliqua. Ut enim ad minim veniam, quis exercitation ullamco laboris nisi ut aliquip ex ea com mmodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.Eu fugiat nu lla pariatur. Excepteur sint occaecat cupidatat non proident.
                            </div>
                        </div>
                    </div>    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery(".afoxi-heading-style-7:first").addClass("active");
                            jQuery(".afoxi-content-style-7:first").slideDown();
                            jQuery(".afoxi-heading-style-7").click(function () {
                                jQuery(".afoxi-heading-style-7").removeClass("active");
                                jQuery(this).toggleClass("active");
                                jQuery(".afoxi-content-style-7").slideUp();
                                var activeTab = jQuery(this).attr("ref");
                                jQuery(activeTab).slideDown();

                            });
                        });
                    </script>
                </div>
                <div class="afoxi-admin-select-row-buttom">
                    <div class="afoxi-admin-select-row-buttom-left">
                        Style 3
                    </div>
                    <div class="afoxi-admin-select-row-buttom-right">
                        <div class="afoxi-admin-select-row-buttom-right">
                            <button type="button" class="btn btn-success"  data-target="style3">Select</button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="afoxi-admin-select-row">
                <div class="afoxi-admin-select-row-upper">
                    <style>
                        .afoxi-wrapper{
                            width: 100%;
                            float:left;
                        }
                        .afoxi-heading-style-9{
                            width: 100%;
                            float: left;
                            cursor: pointer;
                            position: relative;
                            display: flex;
                            align-items: center;
                            background-color: #964a4a;
                            border-top: 2px solid;
                            border-color: #d17171;
                            padding: 18px 0;
                            margin-bottom: 20px;
                            box-shadow: 0 0 6px #e08282;
                        }
                        .afoxi-heading-style-9:first-child{
                            margin-top: 20px;
                        }
                        .afoxi-heading-style-9.active{
                            background-color: #ccb3b3;
                            border-color:  #c40404;
                        }
                        .afoxi-heading-style-9 .afoxi-heading-icon{
                            width: 50px;
                            float: left;
                            text-align: center;
                        }
                        .afoxi-heading-style-9 .afoxi-heading-icon .fa{
                            font-size: 30px;
                            color: #b8b8b8;
                        }
                        .afoxi-heading-style-9.active .afoxi-heading-icon .fa{
                            color:  #5c5c5c;
                        }
                        .afoxi-heading-style-9 .heading-data{
                            width: calc(100% - 50px);
                            font-size: 21px;
                            color: #b8b8b8;
                            font-family:  Anonymous Pro;
                            font-weight: 600;
                            padding-left: 10px;
                        }
                        .afoxi-heading-style-9.active .heading-data{
                            color: #5c5c5c;
                        }
                        .afoxi-heading-style-9.active .afoxi-heading-absulote{
                            position: absolute;
                            display: block;
                            bottom: -10px;
                            left: 50px;
                            width: 0;
                            height: 0;
                            border-left: 10px solid transparent;
                            border-right:10px solid transparent;
                            border-top: 10px solid #ccb3b3;
                        }
                        .afoxi-content-style-9{
                            width: 100%;
                            float: left;
                            display: none;
                            text-align: center;
                            font-size: 16px;
                            color: #9e9e9e;
                            background-color: #ffffff;
                            font-family:  Anonymous Pro;
                            font-weight: 400;
                            line-height: 1.7;
                            padding:  15px 10px 20px 10px;
                            margin-bottom: 20px;
                            box-shadow: 0 0 1px #262626;
                        }
                    </style>
                    <div class="afoxi-wrapper"><div class="afoxi-heading-style-9 afoxi-heading" ref="#afoxi-content-style-9-id-10">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-coffee" aria-hidden="true"></i>
                            </div>
                            <div class="heading-data">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="afoxi-heading-absulote">
                            </div>
                        </div>
                        <div class="afoxi-content-style-9 afoxi-content" id="afoxi-content-style-9-id-10">
                            Responsive Tabs with Accordions is an awesome WordPress Content Tabs Plugin with many nice features. Creating an Awesome Tabs with Responsive Tabs with Accordions is fast and easy. Simply Add Title and content into tabs panel and set or customize Tabs all from one page. You can choose from
                        </div><div class="afoxi-heading-style-9 afoxi-heading" ref="#afoxi-content-style-9-id-22">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-bitbucket" aria-hidden="true"></i>
                            </div>
                            <div class="heading-data">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </div>
                            <div class="afoxi-heading-absulote">
                            </div>
                        </div>
                        <div class="afoxi-content-style-9 afoxi-content" id="afoxi-content-style-9-id-22">
                            Responsive Tabs with Accordions is an awesome WordPress Content Tabs Plugin with many nice features. Creating an Awesome Tabs with Responsive Tabs with Accordions is fast and easy. Simply Add Title and content into tabs panel and set or customize Tabs all from one page. You can choose from
                        </div><div class="afoxi-heading-style-9 afoxi-heading" ref="#afoxi-content-style-9-id-23">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-windows" aria-hidden="true"></i>
                            </div>
                            <div class="heading-data">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </div>
                            <div class="afoxi-heading-absulote">
                            </div>
                        </div>
                        <div class="afoxi-content-style-9 afoxi-content" id="afoxi-content-style-9-id-23">
                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure
                        </div><div class="afoxi-heading-style-9 afoxi-heading" ref="#afoxi-content-style-9-id-24">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-leaf" aria-hidden="true"></i>
                            </div>
                            <div class="heading-data">
                                Neque porro quisquam est qui dolorem ipsum quia dolor
                            </div>
                            <div class="afoxi-heading-absulote">
                            </div>
                        </div>
                        <div class="afoxi-content-style-9 afoxi-content" id="afoxi-content-style-9-id-24">
                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure
                        </div></div>    <script type="text/javascript">
                            jQuery(document).ready(function () {
                                jQuery(".afoxi-heading-style-9:first").addClass("active");
                                jQuery(".afoxi-content-style-9:first").slideDown();
                                jQuery(".afoxi-heading-style-9").click(function () {
                                    jQuery(".afoxi-heading-style-9").removeClass("active");
                                    jQuery(this).toggleClass("active");
                                    jQuery(".afoxi-content-style-9").slideUp();
                                    var activeTab = jQuery(this).attr("ref");
                                    jQuery(activeTab).slideDown();

                                });
                            });
                    </script>
                </div>
                <div class="afoxi-admin-select-row-buttom">
                    <div class="afoxi-admin-select-row-buttom-left">
                        Style 4
                    </div>
                    <div class="afoxi-admin-select-row-buttom-right">
                        <button type="button" class="btn btn-success"  data-target="style4">Select</button>
                    </div>

                </div>
            </div>
            <div class="afoxi-admin-select-row">
                <div class="afoxi-admin-select-row-upper">
                    <style>
                        .afoxi-wrapper{
                            width: 100%;
                            float:left;
                        }
                        .afoxi-heading-style-10{
                            width: 100%;
                            float: left;
                            cursor: pointer;
                            overflow: hidden;
                            display: flex;
                            align-items: center;
                            background-color: #5ce4ff;
                            margin-bottom: 10px;
                            box-shadow: 0 0 2px #3d3d3d;
                        }
                        .afoxi-heading-style-10:first-child{
                            margin-top: 10px;
                        }
                        .afoxi-heading-style-10.active{
                            background-color: #8a8a8a;
                        }
                        .afoxi-heading-style-10 .afoxi-heading-icon{
                            width: 60px;
                            float: left;
                            height: 60px;
                            color: #383838;
                            background-color: #14ff7e;
                            display: flex;
                            align-items: center;
                            position: relative;
                            justify-content: center;
                        }
                        .afoxi-heading-style-10.active .afoxi-heading-icon{
                            color: #ffffff;
                            background-color: #00baa4;
                        }
                        .afoxi-heading-style-10 .afoxi-heading-icon .fa{
                            font-size: 30px;
                        }
                        .afoxi-heading-style-10 .afoxi-heading-icon .afoxi-heading-icon-absolute{
                            position: absolute;
                            display: block;
                            top: 0;
                            bottom: 0;
                            width: 0;
                            height: 0;
                            right: -30px;
                            border-top: 30px solid transparent;
                            border-left: 30px solid ;
                            border-left-color: #14ff7e;
                            border-bottom: 30px solid transparent;
                        }
                        .afoxi-heading-style-10.active .afoxi-heading-icon .afoxi-heading-icon-absolute{
                            border-left-color: #00baa4;
                        }
                        .afoxi-heading-style-10 .heading-data{
                            width: calc(100% - 60px);
                            float: left;
                            font-size: 18px;
                            color: #4f4f4f;
                            font-family:  Anonymous Pro;
                            font-weight:  700;
                            padding-left: 40px;
                        }
                        .afoxi-heading-style-10.active .heading-data{
                            color: #ffffff;
                        }
                        .afoxi-content-style-10{
                            width: 100%;
                            float: left;
                            display: none;
                            font-size: 17px;
                            color: #595959;
                            background-color: #ffffff;
                            padding: 15px 15px 15px 15px;
                            font-family:  Anonymous Pro;
                            font-weight: 200;
                            line-height: 1.5;
                            text-align: left;
                            box-shadow: 0 0 2px #8f8f8f;
                            margin-bottom: 10px;
                        }


                    </style>

                    <div class="afoxi-wrapper"><div class="afoxi-heading-style-10 afoxi-heading" ref="#afoxi-content-style-10-id-12">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-coffee" aria-hidden="true"></i>
                                <div class="afoxi-heading-icon-absolute">
                                </div>
                            </div>
                            <div class="heading-data">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry
                            </div>
                        </div>
                        <div class="afoxi-content-style-10 afoxi-content" id="afoxi-content-style-10-id-12">
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                        </div><div class="afoxi-heading-style-10 afoxi-heading" ref="#afoxi-content-style-10-id-17">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                                <div class="afoxi-heading-icon-absolute">
                                </div>
                            </div>
                            <div class="heading-data">
                                Content here, content here', making it look like readable
                            </div>
                        </div>
                        <div class="afoxi-content-style-10 afoxi-content" id="afoxi-content-style-10-id-17">
                            Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                        </div><div class="afoxi-heading-style-10 afoxi-heading" ref="#afoxi-content-style-10-id-18">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                <div class="afoxi-heading-icon-absolute">
                                </div>
                            </div>
                            <div class="heading-data">
                                There are many variations of passages of Lorem Ipsum available
                            </div>
                        </div>
                        <div class="afoxi-content-style-10 afoxi-content" id="afoxi-content-style-10-id-18">
                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure
                        </div><div class="afoxi-heading-style-10 afoxi-heading" ref="#afoxi-content-style-10-id-20">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-leaf" aria-hidden="true"></i>
                                <div class="afoxi-heading-icon-absolute">
                                </div>
                            </div>
                            <div class="heading-data">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                        </div>
                        <div class="afoxi-content-style-10 afoxi-content" id="afoxi-content-style-10-id-20">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt enim lacinia hendrerit rhoncus. Cras mattis nibh id rutrum congue. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi quis molestie est. Mauris auctor ipsum a vehicula vestibulum. Nunc dignissim dui id nisl congue consequat. Aliquam tincidunt dui sit amet massa feugiat gravida. Cras volutpat magna vel metus sodales egestas. Donec interdum condimentum sem, eu mattis urna euismod sit amet. Pellentesque lacinia mattis elementum. Duis at tincidunt lacus. Ut efficitur lorem eu lacus blandit bibendum. Aliquam ac lacus in libero vulputate sodales.
                        </div><div class="afoxi-heading-style-10 afoxi-heading" ref="#afoxi-content-style-10-id-21">
                            <div class="afoxi-heading-icon">
                                <i class="fa fa-file" aria-hidden="true"></i>
                                <div class="afoxi-heading-icon-absolute">
                                </div>
                            </div>
                            <div class="heading-data">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                        </div>
                        <div class="afoxi-content-style-10 afoxi-content" id="afoxi-content-style-10-id-21">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt enim lacinia hendrerit rhoncus. Cras mattis nibh id rutrum congue. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi quis molestie est. Mauris auctor ipsum a vehicula vestibulum. Nunc dignissim dui id nisl congue consequat. Aliquam tincidunt dui sit amet massa feugiat gravida. Cras volutpat magna vel metus sodales egestas. Donec interdum condimentum sem, eu mattis urna euismod sit amet. Pellentesque lacinia mattis elementum. Duis at tincidunt lacus. Ut efficitur lorem eu lacus blandit bibendum. Aliquam ac lacus in libero vulputate sodales.
                        </div></div>    <script type="text/javascript">
                            jQuery(document).ready(function () {
                                jQuery(".afoxi-heading-style-10:first").addClass("active");
                                jQuery(".afoxi-content-style-10:first").slideDown();
                                jQuery(".afoxi-heading-style-10").click(function () {
                                    jQuery(".afoxi-heading-style-10").removeClass("active");
                                    jQuery(this).toggleClass("active");
                                    jQuery(".afoxi-content-style-10").slideUp();
                                    var activeTab = jQuery(this).attr("ref");
                                    jQuery(activeTab).slideDown();

                                });
                            });
                    </script>
                </div>
                <div class="afoxi-admin-select-row-buttom">
                    <div class="afoxi-admin-select-row-buttom-left">
                        Style 5
                    </div>
                    <div class="afoxi-admin-select-row-buttom-right">
                        <button type="button" class="btn btn-success"  data-target="style5">Select</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="afoxi-style-data" >
        <form method="post">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Style Name</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row form-group-sm">
                            <label for="style-name" class="col-sm-6 col-form-label" >Style Name</label>
                            <div class="col-sm-6 nopadding">
                                <input class="form-control" type="text" value=""  name="style-name">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <?php echo wp_nonce_field("accordionsadddata"); ?>
                        <input type="hidden" name="style" id="style" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="submit" value="Save">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(".btn-success").click(function () {
                var value = jQuery(this).attr('data-target');
                jQuery('#style').val(value);
                jQuery("#afoxi-style-data").modal("show");
            });
        });
    </script>