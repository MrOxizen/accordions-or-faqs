<?php

namespace OXI_ACCORDIONS_PLUGINS\Helper;

/**
 * Description of Installation
 *
 * @author biplo
 */
class Installation {

    const ADMINMENU = 'get_oxilab_addons_menu';

    protected static $lfe_instance = NULL;

    public function __construct() {
        
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

    /**
     * Access plugin instance. You can create further instances by calling
     */
    public static function get_instance() {
        if (NULL === self::$lfe_instance)
            self::$lfe_instance = new self;

        return self::$lfe_instance;
    }

    /**
     * Plugin upgrade hook
     *
     * @since 2.0.1
     */
    public function plugin_upgrade_hook() {
        $database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
        $database->update_database();
        $this->active_menu();
    }

    /**
     * Plugin activation hook
     *
     * @since 2.0.1
     */
    public function plugin_activation_hook() {
        $database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
        $database->update_database();
        $this->active_menu();
    }

    /**
     * Plugin deactivation hook
     *
     * @since 2.0.1
     */
    public function plugin_deactivation_hook() {
        $this->deactive_menu();
    }

    /**
     * Deactive Accordions Menu.
     * @return mixed
     * 
     * @since 2.0.1
     */
    public function deactive_menu() {
        $response = !empty(get_transient(self::ADMINMENU)) ? get_transient(self::ADMINMENU) : [];
        if (array_key_exists('Accordions', $response)):
            unset($response['Accordions']);
            set_transient(self::ADMINMENU, $response, 10 * DAY_IN_SECONDS);
        endif;
    }

    /**
     * Get  Oxi Accordions Menu.
     * @return mixed
     */
    public function active_menu() {
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
            set_transient(self::ADMINMENU, $response, 10 * DAY_IN_SECONDS);
        endif;
    }

}
