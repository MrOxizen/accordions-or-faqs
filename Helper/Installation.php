<?php

namespace OXI_ACCORDIONS_PLUGINS\Helper;

/**
 * Description of Installation
 *
 * @author biplo
 */
class Installation {

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
    }

    /**
     * Plugin activation hook
     *
     * @since 2.0.1
     */
    public function plugin_activation_hook() {
        $database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
        $database->update_database();

        set_transient('accordions_or_faqs_activation_redirect', true, 30);
    }

}
