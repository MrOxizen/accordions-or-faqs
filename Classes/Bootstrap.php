<?php

namespace OXI_ACCORDIONS_PLUGINS\Classes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Bootstrap
 *
 * @author biplo
 */
use OXI_ACCORDIONS_PLUGINS\Classes\Build_Api as Build_Api;

class Bootstrap {

    use \OXI_ACCORDIONS_PLUGINS\Helper\Public_Helper;
    use \OXI_ACCORDIONS_PLUGINS\Helper\Admin_helper;

    // instance container
    private static $instance = null;

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $database;

    const ADMINMENU = 'get_oxilab_addons_menu';

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct() {
        do_action('oxi-accordions-plugin/before_init');
        // Load translation
        add_action('init', array($this, 'i18n'));
        $this->Shortcode_loader();
        $this->Extension();
        new Build_Api();
        if (is_admin()) {
            $this->Admin_Filters();
            $this->User_Admin();
            $this->User_Reviews();
            if (isset($_GET['page']) && 'oxi-accordions-style-view' === $_GET['page']) {
                new \OXI_ACCORDIONS_PLUGINS\Modules\Template();
            }
        }
    }

    /**
     * Load Textdomain
     *
     * @since 3.1.0
     * @access public
     */
    public function i18n() {
        load_plugin_textdomain('oxi-accordions-plugin');
        $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
    }

    /**
     * Shortcode loader
     *
     * @since 3.1.0
     * @access public
     */
    protected function Shortcode_loader() {
        add_shortcode('oxi-accordions', [$this, 'shortcode']);
        $Tabs_Widget = new \OXI_ACCORDIONS_PLUGINS\Modules\Accordions_Widget();
        add_filter('widget_text', 'do_shortcode');
        add_action('widgets_init', array($Tabs_Widget, 'accordions_register_tabswidget'));
    }

    /**
     * Execute Shortcode
     *
     * @since 3.1.0
     * @access public
     */
    public function shortcode($atts) {
        extract(shortcode_atts(array('id' => ' ',), $atts));
        $styleid = $atts['id'];
        ob_start();
        $this->shortcode_render($styleid, 'user');
        return ob_get_clean();
    }

    public function Admin_Filters() {
        add_filter('oxi-accordions-support-and-comments', array($this, $this->fixed_data('537570706f7274416e64436f6d6d656e7473')));
        add_filter('oxi-accordions-plugin/pro_version', array($this, $this->fixed_data('636865636b5f63757272656e745f74616273')));
        add_filter('oxi-accordions-plugin/admin_menu', array($this, $this->fixed_data('6f78696c61625f61646d696e5f6d656e75')));
    }

    public function User_Admin() {
        add_action('admin_menu', [$this, 'Admin_Menu']);
        add_action('admin_head', [$this, 'Plugins_Icon']);
    }
}
