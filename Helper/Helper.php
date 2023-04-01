<?php

namespace OXI_ACCORDIONS_PLUGINS\Helper;

if (!defined('ABSPATH'))
    exit;

/**
 *
 * author @biplob018
 */
trait Helper {

    public function redirect_on_activation() {
        if (get_transient('accordions_or_faqs_activation_redirect')) :
            delete_transient('accordions_or_faqs_activation_redirect');
            if (is_network_admin() || isset($_GET['activate-multi'])) :
                return;
            endif;
            wp_safe_redirect(admin_url("admin.php?page=oxi-accordions-ultimate-welcome"));
        endif;
    }

    /**
     * Plugin Admin Top Menu
     *
     * @since 2.0.1
     */
    public function oxilab_admin_menu($agr) {
        $response = [
            'Shortcode' => [
                'name' => 'Shortcode',
                'homepage' => 'oxi-accordions-ultimate'
            ],
            'Create New' => [
                'name' => 'Create New',
                'homepage' => 'oxi-accordions-ultimate-new'
            ],
        ];
        ?>

        <div class="oxi-addons-wrapper">
            <div class="oxilab-new-admin-menu">
                <div class="oxi-site-logo">
                    <a href="<?php echo esc_url($this->admin_url_convert('oxi-accordions-ultimate')) ?>"
                       class="header-logo"
                       style=" background-image: url(<?php echo esc_url(OXI_ACCORDIONS_URL . 'assets/image/sa-logo.png') ?>);">
                    </a>
                </div>
                <nav class="oxilab-sa-admin-nav">
                    <ul class="oxilab-sa-admin-menu">
        <?php
        $GETPage = $this->validate_post($_GET['page']);
        foreach ($response as $key => $value) {
            ?>
                            <li <?php echo $GETPage == $value['homepage'] ? ' class="active" ' : '' ?>>
                                <a href="<?php echo esc_url($this->admin_url_convert($value['homepage'])) ?>"><?php echo esc_html($this->name_converter($value['name'])) ?></a>
                            </li>

            <?php
        }
        ?>
                    </ul>
                    <ul class="oxilab-sa-admin-menu2">
        <?php if (apply_filters(OXI_ACCORDIONS_PREMIUM, false) == false) { ?>
                            <li class="fazil-class">
                                <a target="_blank" href="https://www.oxilabdemos.com/accordions/pricing">Upgrade</a>
                            </li>
        <?php } ?>

                        <li class="saadmin-doc">
                            <a target="_black" href="https://www.oxilabdemos.com/accordions/docs">Docs</a>
                        </li>
                        <li class="saadmin-doc">
                            <a target="_black"
                               href="https://wordpress.org/support/plugin/accordions-or-faqs/">Support
                            </a>
                        </li>
                        <li class="saadmin-set">
                            <a href="<?php echo admin_url('admin.php?page=oxi-accordions-ultimate-settings') ?>">
                                <span class="dashicons dashicons-admin-generic"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php
    }

    /**
     * Plugin fixed
     *
     * @since 2.0.1
     */
    public function fixed_data($agr) {
        return hex2bin($agr);
    }

    /**
     * Plugin fixed debugging data
     *
     * @since 2.0.1
     */
    public function fixed_debug_data($str) {
        return bin2hex($str);
    }

    public function admin_icon() {
        ?>
        <style type='text/css' media='screen'>
            #adminmenu #toplevel_page_oxi-accordions-ultimate div.wp-menu-image:before {
                content: "\f163";
            }
        </style>
        <?php
    }

    public function accordions_shortcode($atts) {
        extract(shortcode_atts(['id' => ' ',], $atts));
        $styleid = $atts['id'];
        ob_start();
        $CLASS = '\OXI_ACCORDIONS_PLUGINS\Includes\Shortcode';
        if (class_exists($CLASS)) :
            new $CLASS($styleid, 'user');
        endif;
        return ob_get_clean();
    }

    public function shortcode_render($id, $user) {
        return;
    }

    /**
     * Plugin check Current Accordions
     *
     * @since 2.0.1
     */
    public function check_current_accordions($agr) {
        $vs = get_option($this->fixed_data('6163636f7264696f6e735f6f725f666171735f6c6963656e73655f737461747573'));
        if ($vs == $this->fixed_data('76616c6964')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Plugin Name Convert to View
     *
     * @since 2.0.1
     */
    public function name_converter($data) {
        $data = str_replace('tyle', 'tyle ', $data);
        return ucwords($data);
    }

    public function admin_url_convert($agr) {
        return admin_url(strpos($agr, 'edit') !== false ? $agr : 'admin.php?page=' . $agr);
    }

    public function supportandcomments($agr) {
        if (get_option('oxi_accordions_support_massage') == 'no') {
            return;
        }
        ?>
        <div class="oxi-addons-admin-notifications">
            <h3>
                <span class="dashicons dashicons-flag"></span>
                Trouble or Need Support?
            </h3>
            <p></p>
            <div class="oxi-addons-admin-notifications-holder">
                <div class="oxi-addons-admin-notifications-alert">
                    <p>Unable to create your desire design or need any help? You can
                        <a href="https://wordpress.org/support/plugin/accordions-or-faqs#new-post">Ask any question</a>
                        and get reply from our expert members. We will be glad to answer any question you may have about our plugin.
                    </p>
        <?php if (apply_filters(OXI_ACCORDIONS_PREMIUM, false) == false) { ?>
                        <p>By the way, did you know we also have a
                            <a href="https://www.oxilabdemos.com/accordions/pricing">Premium Version</a>
                            ? It offers lots of options with automatic update. It also comes with 16/5 personal support.
                        </p>
            <?php
        }
        ?>
                    <p>Thanks Again!</p>
                    <p></p>
                </div>
            </div>
            <p></p>
        </div>
        <?php
    }

    public function home_page() {
        new \OXI_ACCORDIONS_PLUGINS\Includes\Front_Page();
    }

    public function create_new() {
        $styleid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        if (!empty($styleid) && $styleid > 0) :
            $database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
            $style = $database->wpdb->get_row($database->wpdb->prepare('SELECT * FROM ' . $database->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            if (is_array($style)) :
                $cls = '\OXI_ACCORDIONS_PLUGINS\Layouts\Helper';
                if (class_exists($cls)) :
                    new $cls();
                endif;
            else :
                new \OXI_ACCORDIONS_PLUGINS\Includes\Templates();
            endif;
        else :
            new \OXI_ACCORDIONS_PLUGINS\Includes\Templates();
        endif;
    }

    public function user_settings() {
        new \OXI_ACCORDIONS_PLUGINS\Includes\Settings();
    }

    public function oxilab_plugins() {
        if (current_user_can('activate_plugins')) :
            new \OXI_ACCORDIONS_PLUGINS\Oxilab\Plugins();
        endif;
    }

    public function welcome_page() {
        new \OXI_ACCORDIONS_PLUGINS\Oxilab\Welcome();
    }

    public function User_Reviews() {
        $user_role = get_option('oxi_accordions_user_permission');
        $role_object = get_role($user_role);
        $first_key = '';
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
            reset($role_object->capabilities);
            $first_key = key($role_object->capabilities);
        } else {
            $first_key = 'manage_options';
        }
        if (!current_user_can($first_key)) :
            return;
        endif;

        if (current_user_can('activate_plugins')) :
            $this->admin_recommended();
        endif;

        $this->admin_notice();
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_notice_status() {
        $data = get_option('accordions_or_faqs_no_bug');
        return $data;
    }

    /**
     * Admin Install date Check
     *
     * @since 2.0.0
     */
    public function installation_date() {
        $data = get_option('accordions_or_faqs_activation_date');
        if (empty($data)) :
            $data = strtotime("now");
            update_option('accordions_or_faqs_activation_date', $data);
        endif;
        return $data;
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_recommended_status() {
        $data = get_option('accordions_or_faqs_recommended');
        return $data;
    }

    public function admin_recommended() {
        if (!empty($this->admin_recommended_status())) :
            return;
        endif;
        if (strtotime('-1 days') < $this->installation_date()) :
            return;
        endif;
        new \OXI_ACCORDIONS_PLUGINS\Oxilab\Recommend();
    }

    public function admin_notice() {
        if (!empty($this->admin_notice_status())) :
            return;
        endif;
        if (strtotime('-7 days') < $this->installation_date()) :
            return;
        endif;
        new \OXI_ACCORDIONS_PLUGINS\Oxilab\Reviews();
    }

    public function User_Admin() {
        add_filter('oxi-accordions-plugin/support-and-comments', [$this, $this->fixed_data('737570706f7274616e64636f6d6d656e7473')]);
        add_filter('oxi-accordions-plugin/pro_version', [$this, $this->fixed_data('636865636b5f63757272656e745f6163636f7264696f6e73')]);
        add_filter('oxi-accordions-plugin/admin_menu', [$this, $this->fixed_data('6f78696c61625f61646d696e5f6d656e75')]);
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_head', [$this, 'admin_icon']);
        add_action('admin_init', [$this, 'redirect_on_activation']);
    }

    public function allowed_html($rawdata) {
        $allowed_tags = [
            'a' => [
                'class' => [],
                'href' => [],
                'rel' => [],
                'title' => [],
            ],
            'abbr' => [
                'title' => [],
            ],
            'b' => [],
            'br' => [],
            'blockquote' => [
                'cite' => [],
            ],
            'cite' => [
                'title' => [],
            ],
            'code' => [],
            'del' => [
                'datetime' => [],
                'title' => [],
            ],
            'dd' => [],
            'div' => [
                'class' => [],
                'title' => [],
                'style' => [],
                'id' => [],
            ],
            'table' => [
                'class' => [],
                'id' => [],
                'style' => [],
            ],
            'button' => [
                'class' => [],
                'type' => [],
                'value' => [],
            ],
            'thead' => [],
            'tbody' => [],
            'tr' => [],
            'td' => [],
            'dt' => [],
            'em' => [],
            'h1' => [],
            'h2' => [],
            'h3' => [],
            'h4' => [],
            'h5' => [],
            'h6' => [],
            'i' => [
                'class' => [],
            ],
            'img' => [
                'alt' => [],
                'class' => [],
                'height' => [],
                'src' => [],
                'width' => [],
            ],
            'li' => [
                'class' => [],
            ],
            'ol' => [
                'class' => [],
            ],
            'p' => [
                'class' => [],
            ],
            'q' => [
                'cite' => [],
                'title' => [],
            ],
            'span' => [
                'class' => [],
                'title' => [],
                'style' => [],
            ],
            'strike' => [],
            'strong' => [],
            'ul' => [
                'class' => [],
            ],
        ];
        if (is_array($rawdata)) :
            return $rawdata = array_map([$this, 'allowed_html'], $rawdata);
        else :
            return wp_kses($rawdata, $allowed_tags);
        endif;
    }

    public function validate_post($files = '') {

        $rawdata = [];
        if (!empty($files)) :
            $data = json_decode(stripslashes($files), true);
        endif;
        if (is_array($data)) :
            $rawdata = array_map([$this, 'allowed_html'], $data);
        else :
            $rawdata = $this->allowed_html($files);
        endif;

        return $rawdata;
    }

    public function admin_menu() {
        $user_role = get_option('oxi_accordions_user_permission');
        $role_object = get_role($user_role);
        $first_key = '';
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
            reset($role_object->capabilities);
            $first_key = key($role_object->capabilities);
        } else {
            $first_key = 'manage_options';
        }
        add_menu_page('Accordions', 'Accordions', $first_key, 'oxi-accordions-ultimate', [$this, 'home_page']);
        add_submenu_page('oxi-accordions-ultimate', 'Accordions', 'Shortcode', $first_key, 'oxi-accordions-ultimate', [$this, 'home_page']);
        add_submenu_page('oxi-accordions-ultimate', 'Create New', 'Create New', $first_key, 'oxi-accordions-ultimate-new', [$this, 'create_new']);
        add_submenu_page('oxi-accordions-ultimate', 'Settings', 'Settings', 'manage_options', 'oxi-accordions-ultimate-settings', [$this, 'user_settings']);
        add_submenu_page('oxi-accordions-ultimate', 'Oxilab Plugins', 'Oxilab Plugins', 'manage_options', 'oxi-accordions-ultimate-plugins', [$this, 'oxilab_plugins']);
        add_submenu_page('oxi-accordions-ultimate', 'Welcome To Accordions - Multiple Accordions or FAQs Builders', 'Support', $first_key, 'oxi-accordions-ultimate-welcome', [$this, 'welcome_page']);
    }

}
