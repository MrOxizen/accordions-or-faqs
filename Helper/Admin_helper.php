<?php

namespace OXI_ACCORDIONS_PLUGINS\Helper;

trait Admin_helper {

    /**
     * Plugin fixed
     *
     * @since 3.1.0
     */
    public function fixed_data($agr) {
        return hex2bin($agr);
    }

    /**
     * Plugin fixed debugging data
     *
     * @since 3.1.0
     */
    public function fixed_debug_data($str) {
        return bin2hex($str);
    }

    public function Plugins_Icon() {
        ?>
        <style type='text/css' media='screen'>
            #adminmenu #toplevel_page_oxi-accordions-ultimate  div.wp-menu-image:before {
                content: "\f163";
            }
        </style>
        <?php

    }

    /**
     * Plugin check Current Tabs
     *
     * @since 2.0.0
     */
    public function check_current_tabs($agr) {
        $vs = get_option($this->fixed_data('726573706f6e736976655f746162735f776974685f6163636f7264696f6e735f6c6963656e73655f737461747573'));
        if ($vs == $this->fixed_data('76616c6964')) {
            return true;
        } else {
            return false;
        }
    }

    public function admin_url_convert($agr) {
        return admin_url(strpos($agr, 'edit') !== false ? $agr : 'admin.php?page=' . $agr);
    }

    public function SupportAndComments($agr) {
        echo '  <div class="oxi-addons-admin-notifications">
                    <h3>
                        <span class="dashicons dashicons-flag"></span> 
                        Notifications
                    </h3>
                    <p></p>
                    <div class="oxi-addons-admin-notifications-holder">
                        <div class="oxi-addons-admin-notifications-alert">
                            <p>Thank you for using my Accordions - Multiple Accordions or FAQs Builder. I Just wanted to see if you have any questions or concerns about my plugins. If you do, Please do not hesitate to <a href="https://wordpress.org/support/plugin/vc-tabs#new-post">file a bug report</a>. </p>
                            ' . (apply_filters('oxi-tabs-plugin/pro_version', false) ? '' : '<p>By the way, did you know we also have a <a href="https://oxilab.org/responsive-tabs/pricing">Premium Version</a>? It offers lots of options with automatic update. It also comes with 16/5 personal support.</p>') . '
                            <p>Thanks Again!</p>
                            <p></p>
                        </div>                     
                    </div>
                    <p></p>
                </div>';
    }

    /**
     * Plugin Admin Top Menu
     *
     * @since 2.0.0
     */
    public function oxilab_admin_menu($agr) {
        $response = !empty(get_transient(self::ADMINMENU)) ? get_transient(self::ADMINMENU) : [];
        if (!array_key_exists('Accordions', $response)):
            $response['Accordions']['Shortcode'] = [
                'name' => 'Shortcode',
                'homepage' => 'oxi-accordions-ultimate'
            ];
            $response['Accordions']['Create New'] = [
                'name' => 'Create New',
                'homepage' => 'oxi-accordions-ultimate-new'
            ];
            $response['Accordions']['Import Template'] = [
                'name' => 'Import Template',
                'homepage' => 'oxi-accordions-ultimate-new&import'
            ];
            set_transient(self::ADMINMENU, $response, 10 * DAY_IN_SECONDS);
        endif;
        $bgimage = OXI_ACCORDIONS_URL . 'assets/image/sa-logo.png';
        $sub = '';

        $menu = '<div class="oxi-addons-wrapper">
                    <div class="oxilab-new-admin-menu">
                        <div class="oxi-site-logo">
                            <a href="' . $this->admin_url_convert('oxi-accordions-ultimate') . '" class="header-logo" style=" background-image: url(' . $bgimage . ');">
                            </a>
                        </div>
                        <nav class="oxilab-sa-admin-nav">
                            <ul class="oxilab-sa-admin-menu">';

        $GETPage = sanitize_text_field($_GET['page']);
        if (count($response) == 1):
            foreach ($response['Accordions'] as $key => $value) {
                $active = ($GETPage == $value['homepage'] ? ' class="active" ' : '');
                $menu .= '<li ' . $active . '><a href="' . $this->admin_url_convert($value['homepage']) . '">' . $this->name_converter($value['name']) . '</a></li>';
            }
        else:
            foreach ($response as $key => $value) {
                $active = ($key == 'Accordions' ? 'active' : '');
                $menu .= '<li class="' . $active . '"><a class="oxi-nev-drop-menu" href="#">' . $this->name_converter($key) . '</a>';
                $menu .= '   <div class="oxi-nev-d-menu">
                                    <div class="oxi-nev-drop-menu-li">';
                foreach ($value as $key2 => $submenu) {
                    $menu .= '<a href="' . $this->admin_url_convert($submenu['homepage']) . '">' . $this->name_converter($submenu['name']) . '</a>';
                }
                $menu .= '</div>';
                $menu .= '</li>';
            }
            if (strpos($GETPage, 'oxi-accordions-ultimate') !== false):
                $sub .= '<div class="shortcode-addons-main-tab-header">';
                foreach ($response['Accordions'] as $key => $value) {
                    $active = ($GETPage == $value['homepage'] ? 'oxi-active' : '');
                    $sub .= '<a href="' . $this->admin_url_convert($value['homepage']) . '">
                                <div class="shortcode-addons-header ' . $active . '">' . $this->name_converter($value['name']) . '</div>
                              </a>';
                }
                $sub .= '</div>';
            endif;
        endif;
        $menu .= '              </ul>
                            <ul class="oxilab-sa-admin-menu2">
                               ' . (apply_filters('oxi-tabs-plugin/pro_version', false) == FALSE ? ' <li class="fazil-class" ><a target="_blank" href="https://oxilab.org/responsive-tabs/pricing">Upgrade</a></li>' : '') . '
                               <li class="saadmin-doc"><a target="_black" href="https://oxilab.org/responsive-tabs/docs/">Docs</a></li>
                               <li class="saadmin-doc"><a target="_black" href="https://wordpress.org/support/plugin/vc-tabs/">Support</a></li>
                               <li class="saadmin-set"><a href="' . admin_url('admin.php?page=oxi-accordions-ultimate-settings') . '"><span class="dashicons dashicons-admin-generic"></span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                ' . $sub;
        echo __($menu, OXI_ACCORDIONS_TEXTDOMAIN);
    }

    public function Admin_Menu() {
        $user_role = get_option('oxi_addons_user_permission');
        $role_object = get_role($user_role);
        $first_key = '';
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
            reset($role_object->capabilities);
            $first_key = key($role_object->capabilities);
        } else {
            $first_key = 'manage_options';
        }
        add_menu_page('Accordions', 'Accordions', $first_key, 'oxi-accordions-ultimate', [$this, 'plugings_home']);
        add_submenu_page('oxi-accordions-ultimate', 'Content Tabs', 'Shortcode', $first_key, 'oxi-accordions-ultimate', [$this, 'plugings_home']);
        add_submenu_page('oxi-accordions-ultimate', 'Create New', 'Create New', $first_key, 'oxi-accordions-ultimate-new', [$this, 'shortcode_create']);
        add_submenu_page('oxi-accordions-ultimate', 'Settings', 'Settings', $first_key, 'oxi-accordions-ultimate-settings', [$this, 'plugins_settings']);
        add_submenu_page('oxi-accordions-ultimate', 'Oxilab Plugins', 'Oxilab Plugins', $first_key, 'oxi-accordions-ultimate-plugins', [$this, 'oxilab_plugins']);
        add_submenu_page('oxi-accordions-ultimate', 'Welcome To Responsive Tabs with  Accordions', 'Support', $first_key, 'oxi-accordions-ultimate-welcome', [$this, 'oxi_tabs_welcome']);
    }

    public function plugings_home() {
        new \OXI_ACCORDIONS_PLUGINS\Page\Home();
    }

    public function shortcode_create() {
        $styleid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        if (!empty($styleid) && $styleid > 0):
            $style = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            $template = ucfirst($style['style_name']);
            if (!array_key_exists('rawdata', $style)):
                $Installation = new \OXI_ACCORDIONS_PLUGINS\Classes\Installation();
                $Installation->Datatase();
            endif;
            $row = json_decode(stripslashes($style['rawdata']), true);
            if (is_array($row)):
                $cls = '\OXI_ACCORDIONS_PLUGINS\Render\Admin\\' . $template;
            else:
                $cls = '\OXI_ACCORDIONS_PLUGINS\Render\Old_Admin\\' . $template;
            endif;
            new $cls();
        else:
            new \OXI_ACCORDIONS_PLUGINS\Page\Create();
        endif;
    }

    public function plugins_settings() {
        new \OXI_ACCORDIONS_PLUGINS\Page\Settings();
    }

    public function oxilab_plugins() {
        new \OXI_ACCORDIONS_PLUGINS\Page\Plugins();
    }

    public function oxi_tabs_welcome() {
        new \OXI_ACCORDIONS_PLUGINS\Page\Welcome();
    }

    public function User_Reviews() {
        $this->admin_recommended();
        $this->admin_notice();
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_notice_status() {
        $data = get_option('oxi_responsive_tabs_accordions_no_bug');
        return $data;
    }

    /**
     * Admin Install date Check
     *
     * @since 2.0.0
     */
    public function installation_date() {
        $data = get_option('oxi_responsive_accordions_activation_date');
        if (empty($data)):
            $data = strtotime("now");
            update_option('oxi_responsive_accordions_activation_date', $data);
        endif;
        return $data;
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_recommended_status() {
        $data = get_option('oxi_responsive_accordions_recommended');
        return $data;
    }

    public function admin_recommended() {
        if (!empty($this->admin_recommended_status())):
            return;
        endif;
        if (strtotime('-1 days') < $this->installation_date()):
            return;
        endif;
        new \OXI_ACCORDIONS_PLUGINS\Classes\Support_Recommended();
    }

    public function admin_notice() {
        if (!empty($this->admin_notice_status())):
            return;
        endif;
        if (strtotime('-7 days') < $this->installation_date()):
            return;
        endif;
        new \OXI_ACCORDIONS_PLUGINS\Classes\Support_Reviews();
    }

}
