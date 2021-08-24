<?php

namespace OXI_ACCORDIONS_PLUGINS\Helper;

/**
 *
 * @author biplo
 */
trait Helper {

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
        extract(shortcode_atts(array('id' => ' ',), $atts));
        $styleid = $atts['id'];
        ob_start();
        $CLASS = '\OXI_ACCORDIONS_PLUGINS\Includes\Shortcode';
        if (class_exists($CLASS)):
            new $CLASS($styleid, 'user');
        endif;
        return ob_get_clean();
    }

    public function shortcode_render($id, $user) {
        return;
    }

    /**
     * Plugin check Current Tabs
     *
     * @since 2.0.1
     */
    public function check_current_accordions($agr) {
        $vs = get_option($this->fixed_data('726573706f6e736976655f746162735f776974685f6163636f7264696f6e735f6c6963656e73655f737461747573'));
        if ($vs == $this->fixed_data('76616c6964')) {
            return true;
        } else {
            return true;
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
        echo '  <div class="oxi-addons-admin-notifications">
                    <h3>
                        <span class="dashicons dashicons-flag"></span> 
                        Notifications
                    </h3>
                    <p></p>
                    <div class="oxi-addons-admin-notifications-holder">
                        <div class="oxi-addons-admin-notifications-alert">
                            <p>Thank you for using my Accordions - Multiple Accordions or FAQs Builders. I Just wanted to see if you have any questions or concerns about my plugins. If you do, Please do not hesitate to <a href="https://wordpress.org/support/plugin/accordions-or-faqs#new-post">file a bug report</a>. </p>
                            ' . (apply_filters(OXI_ACCORDIONS_PREMIUM, false) ? '' : '<p>By the way, did you know we also have a <a href="https://oxilab.org/responsive-accordions/pricing">Premium Version</a>? It offers lots of options with automatic update. It also comes with 16/5 personal support.</p>') . '
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
     * @since 2.0.1
     */
    public function oxilab_admin_menu($agr) {
        $admin_menu = 'get_oxilab_addons_menu';
        $response = !empty(get_transient($admin_menu)) ? get_transient($admin_menu) : [];
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
            set_transient($admin_menu, $response, 10 * DAY_IN_SECONDS);
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
                               ' . (apply_filters(OXI_ACCORDIONS_PREMIUM, false) == FALSE ? ' <li class="fazil-class" ><a target="_blank" href="https://oxilab.org/responsive-accordions/pricing">Upgrade</a></li>' : '') . '
                               <li class="saadmin-doc"><a target="_black" href="https://oxilab.org/responsive-accordions/docs/">Docs</a></li>
                               <li class="saadmin-doc"><a target="_black" href="https://wordpress.org/support/plugin/accordions-or-faqs/">Support</a></li>
                               <li class="saadmin-set"><a href="' . admin_url('admin.php?page=oxi-accordions-ultimate-settings') . '"><span class="dashicons dashicons-admin-generic"></span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                ' . $sub;
        echo __($menu, OXI_ACCORDIONS_TEXTDOMAIN);
    }

    public function admin_menu() {
        $user_role = get_option('oxi_addons_user_permission');
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
        add_submenu_page('oxi-accordions-ultimate', 'Settings', 'Settings', $first_key, 'oxi-accordions-ultimate-settings', [$this, 'user_settings']);
        add_submenu_page('oxi-accordions-ultimate', 'Oxilab Plugins', 'Oxilab Plugins', $first_key, 'oxi-accordions-ultimate-plugins', [$this, 'oxilab_plugins']);
        add_submenu_page('oxi-accordions-ultimate', 'Welcome To Accordions - Multiple Accordions or FAQs Builders', 'Support', $first_key, 'oxi-accordions-ultimate-welcome', [$this, 'welcome_page']);
    }

    public function home_page() {
        new \OXI_ACCORDIONS_PLUGINS\Includes\Front_Page();
    }

    public function create_new() {
        $styleid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        if (!empty($styleid) && $styleid > 0):
            $database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
            $style = $database->wpdb->get_row($database->wpdb->prepare('SELECT * FROM ' . $database->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            if (is_array($style)):
                $template = ucfirst(str_replace('-', '_', $style['style_name']));
                $cls = '\OXI_ACCORDIONS_PLUGINS\Layouts\Admin\\' . $template;
                if (class_exists($cls)):
                    new $cls();
                endif;
            else:
                new \OXI_ACCORDIONS_PLUGINS\Includes\Templates();
            endif;

        else:
            new \OXI_ACCORDIONS_PLUGINS\Includes\Templates();
        endif;
    }

    public function user_settings() {
          new \OXI_ACCORDIONS_PLUGINS\Includes\Settings();
    }

    public function oxilab_plugins() {
        //   new \OXI_TABS_PLUGINS\Page\Plugins();
    }

    public function welcome_page() {
        //new \OXI_TABS_PLUGINS\Page\Welcome();
    }

}
