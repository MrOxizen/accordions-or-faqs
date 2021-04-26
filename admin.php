<?php
if (!defined('ABSPATH'))
    exit;
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

function aor_f_oxilab_toggle($data) {
    echo 'data-toggle = "tooltip" data-placement = "top" title = "' . $data . '"';
}

function accordions_or_faqs_oxilab_admin_head($data) {
    ?>
    <div class="afoxi-admin-style-left-head">
        <span>Custom Settings</span>
        <div class="afoxi-admin-style-left-button">
            <button type="button" class="btn btn-success btn-lg" id="afoxi-admin-model-item-data-button" data-toggle="modal" data-target="#afoxi-admin-model-item-data">Add New Item</button>
        </div>
        <div class="afoxi-admin-shortcode">
            <div class="col-lg-3 col-md-4 col-sm-6 col-xm-12">
                <div class="afoxi-admin-shortcode-head">
                    Shortcode for posts/pages/plugins
                </div>
                <div class="afoxi-admin-shortcode-body">
                    Copy & paste the shortcode directly into any WordPress post or page.
                </div>
                <div class="afoxi-admin-shortcode-code">
                    <input type="text" class="form-control" onclick="this.setSelectionRange(0, this.value.length)" value="[afoxi_ultimate_oxi id=&quot;<?php echo $data; ?>&quot;]">
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 hidden-xs">
                <div class="afoxi-admin-shortcode-head">
                    Shortcode for templates/themes
                </div>
                <div class="afoxi-admin-shortcode-body">
                    Copy & paste this code into a template file to include the slideshow within your theme.
                </div>
                <div class="afoxi-admin-shortcode-code">
                    <input type="text" class="form-control" onclick="this.setSelectionRange(0, this.value.length)" value="&lt;?php echo do_shortcode(&#039;[afoxi_ultimate_oxi  id=&quot;<?php echo $data; ?>&quot;]&#039;); ?&gt;">
                </div>
            </div>
            <div class="col-lg-3 col-md-4 hidden-sm hidden-xs">
                <div class="afoxi-admin-shortcode-head">
                    Shortcode for Visual Composer
                </div>
                <div class="afoxi-admin-shortcode-body">
                    Copy & paste this id into a visual composer into content tabs id.
                </div>
                <div class="afoxi-admin-shortcode-code">
                    <input type="text" class="form-control" onclick="this.setSelectionRange(0, this.value.length)" value="<?php echo $data; ?>">
                </div>
            </div>
            <div class="col-lg-3 hidden-sm hidden-xs hidden-md">
                <div class="afoxi-admin-shortcode-head">
                    If you have any difficulties in using the options
                </div>
                <div class="afoxi-admin-shortcode-body">
                    Create tread at <a href="https://wordpress.org/support/plugin/accordions-or-faqs/" target="_blank" rel="noopener" >Support Forum</a> or contact at oxilab 
                    <a href="https://www.oxilab.org/contact-us" target="_blank" rel="noopener" >Contact</a> page.
                </div>
            </div>
        </div>
    </div>
    <?php
}

function accordions_or_faqs_oxilab_icon_select_admin($data) {
    ?>
    <div class="col-sm-3 icon-paddin-afoxi">
        <input type="radio" name="head-icon-select" id="head-icon-select" value="fa-plus++fa-minus" <?php
        if ($data == 'fa-plus++fa-minus') {
            echo 'checked';
        }
        ?>> <i class="fa fa-plus" aria-hidden="true"></i> <i class="fa fa-minus" aria-hidden="true"></i>
    </div>
    <div class="col-sm-3 icon-paddin-afoxi">
        <input type="radio" name="head-icon-select" id="head-icon-select" value="fa-arrow-right++fa-arrow-down" <?php
        if ($data == 'fa-arrow-right++fa-arrow-down') {
            echo 'checked';
        }
        ?>> <i class="fa fa-arrow-down" aria-hidden="true"></i> <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </div>
    <div class="col-sm-3 icon-paddin-afoxi">
        <input type="radio" name="head-icon-select" id="head-icon-select" value="fa-plus++fa-times" <?php
        if ($data == 'fa-plus++fa-times') {
            echo 'checked';
        }
        ?>> <i class="fa fa-plus" aria-hidden="true"></i> <i class="fa fa-times" aria-hidden="true"></i>
    </div>
    <?php
}

if (empty($_GET['styleid'])) {
    accordions_or_faqs_oxilab_js_css('new');
    include accordions_or_faqs_oxilab_url . 'admin/style-new.php';
}
if (!empty($_GET['styleid']) && is_numeric($_GET['styleid'])) {
    $id = $_GET['styleid'];
    global $wpdb;
    $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $id), ARRAY_A);
    include accordions_or_faqs_oxilab_url . 'admin/' . $styledata['style_name'] . '.php';
    accordions_or_faqs_oxilab_js_css('style');
}

function accordions_or_faqs_oxilab_js_css($data) {
    wp_enqueue_script('jQuery');
    wp_enqueue_style('afoxi-vendor-style', plugins_url('js-css/style.css', __FILE__));
    wp_enqueue_script('afoxi-vendor-bootstrap-jss', plugins_url('js-css/bootstrap.min.js', __FILE__));
    wp_enqueue_style('afoxi-vendor-bootstrap', plugins_url('js-css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('font-awesome', plugins_url('js-css/font-awesome.min.css', __FILE__));
    if ($data == 'style') {
        wp_enqueue_script('afoxi-vendor-minicolors-js', plugins_url('js-css/jquery.minicolors.min.js', __FILE__));
        wp_enqueue_style('afoxi-vendor-minicolors', plugins_url('js-css/jquery.minicolors.css', __FILE__));
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('afoxi-vendor-jss', plugins_url('js-css/vendor.js', __FILE__));
        wp_enqueue_script('afoxi-vendor-font-select', plugins_url('js-css/font-select.js', __FILE__));
    }
}
