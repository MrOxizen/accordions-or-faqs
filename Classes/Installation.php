<?php

namespace OXI_TABS_PLUGINS\Classes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Installation
 *
 * @author biplo
 */
class Installation {

    protected static $lfe_instance = NULL;

    const ADMINMENU = 'get_oxilab_addons_menu';

    /**
     * Constructor of Shortcode Addons
     *
     * @since 2.0.0
     */
    public function __construct() {
        
    }

    /**
     * Access plugin instance. You can create further instances by calling
     */
    public static function get_instance() {
        if (NULL === self::$lfe_instance)
            self::$lfe_instance = new self;

        return self::$lfe_instance;
    }

    public function Datatase() {
        $database = new \OXI_TABS_PLUGINS\Helper\Database();
        $database->update_database();
    }

    public function Tabs_Datatase() {
        $this->Datatase();
    }

    /**
     * Get  Oxi Tabs Menu.
     * @return mixed
     */
    public function Tabs_Menu() {
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
    }

    /**
     * Get  Oxi Tabs Menu Deactive.
     * @return mixed
     */
    public function Tabs_Menu_Deactive() {
        delete_transient(self::ADMINMENU);
    }

    /**
     * Plugin activation hook
     *
     * @since 3.1.0
     */
    public function plugin_activation_hook() {
        $this->Tabs_Menu();
        $this->Tabs_Datatase();
        // Redirect to options page
        set_transient('oxi_tabs_activation_redirect', true, 30);
    }

    /**
     * Plugin deactivation hook
     *
     * @since 3.1.0
     */
    public function plugin_deactivation_hook() {
        $this->Tabs_Menu_Deactive();
    }

    

    /**
     * Plugin upgrade hook
     *
     * @since 1.0.0
     */
    public function plugin_upgrade_hook($upgrader_object, $options) {
        if ($options['action'] == 'update' && $options['type'] == 'plugin') {
            if (isset($options['plugins'][OXI_TABS_TEXTDOMAIN])) {
                $this->Tabs_Menu();
                $this->Tabs_Datatase();
            }
        }
    }

}
