<?php

/*
  Plugin Name: Accordions or FAQs
  Plugin URI: https://www.oxilab.org/
  Description: Accordions or FAQs is the most easiest accordions or faqs builder Plugin. Create multiple accordion or  collapse faqs with this.
  Author: Biplob Adhikari
  Author URI: http://www.oxilab.org/
  Version: 1.2
 */
if (!defined('ABSPATH'))
    exit;

$accordions_or_faqs_oxilab_version = '1.2';
define('accordions_or_faqs_oxilab_url', plugin_dir_path(__FILE__));

include accordions_or_faqs_oxilab_url . 'public-data.php';
add_shortcode('afoxi_ultimate_oxi', 'afoxi_ultimate_oxi_shortcode');

function afoxi_ultimate_oxi_shortcode($atts) {
    extract(shortcode_atts(array('id' => ' ',), $atts));
    $styleid = $atts['id'];
    ob_start();
    afoxi_ultimate_oxi_shortcode_function($styleid);
    return ob_get_clean();
}

add_action('vc_before_init', 'afoxi_ultimate_oxi_VC_extension');
add_shortcode('afoxi_ultimate_oxi_VC', 'afoxi_ultimate_oxi_VC_shortcode');

function afoxi_ultimate_oxi_VC_shortcode($atts) {
    extract(shortcode_atts(array(
        'id' => ''
                    ), $atts));
    $styleid = $atts['id'];
    ob_start();
    afoxi_ultimate_oxi_shortcode_function($styleid);
    return ob_get_clean();
}

function afoxi_ultimate_oxi_VC_extension() {
    vc_map(array(
        "name" => __("Accordions or FAQs"),
        "base" => "afoxi_ultimate_oxi_VC",
        "category" => __("Content"),
        "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("ID"),
                "param_name" => "id",
                "description" => __("Input your Tabs ID in input box")
            ),
        )
    ));
}

add_action('admin_menu', 'accordions_or_faqs_oxilab_menu');

function accordions_or_faqs_oxilab_menu() {
    add_menu_page('Accordions FAQs', 'Accordions FAQs', 'manage_options', 'accordions-or-faqs-oxilab', 'accordions_or_faqs_oxilab_home');
    add_submenu_page('accordions-or-faqs-oxilab', 'List All ', 'List All', 'manage_options', 'accordions-or-faqs-oxilab', 'accordions_or_faqs_oxilab_home');
    add_submenu_page('accordions-or-faqs-oxilab', 'New Items', 'New Items', 'manage_options', 'accordions-or-faqs-oxilab-new', 'accordions_or_faqs_oxilab_new');
}

function accordions_or_faqs_oxilab_home() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    include accordions_or_faqs_oxilab_url . 'home.php';
}

function accordions_or_faqs_oxilab_new() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    include accordions_or_faqs_oxilab_url . 'admin.php';
}

function accordions_or_faqs_oxilab_special_charecter($data) {
    $data = str_replace("\'", "'", $data);
    $data = str_replace('\"', '"', $data);
    return $data;
}

function accordions_or_faqs_oxilab_font_familly_charecter($data) {
    $data = str_replace('+', ' ', $data);
    $data = explode(':', $data);
    return $data[0];
}

function accordions_or_faqs_oxilab_custom_post_type_icon() {
    ?>
    <style type='text/css' media='screen'>
        #adminmenu #toplevel_page_accordions-or-faqs-oxilab  div.wp-menu-image:before {
            content: "\f203";
        }
    </style>
    <?php

}

add_action('admin_head', 'accordions_or_faqs_oxilab_custom_post_type_icon');
register_activation_hook(__FILE__, 'accordions_or_faqs_oxilab_install');

function accordions_or_faqs_oxilab_install() {
    global $wpdb;
    global $accordions_or_faqs_oxilab_version;

    $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
    $table_list = $wpdb->prefix . 'accordions_or_faqs_items';

    $charset_collate = $wpdb->get_charset_collate();

    $sql1 = "CREATE TABLE $table_name (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                name varchar(50) NOT NULL,
		style_name varchar(10) NOT NULL,
                css varchar(1000) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
    $sql2 = "CREATE TABLE $table_list (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                styleid mediumint(6) NOT NULL,
		title varchar(100),
                files text,
                css varchar(1000),
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql1);
    dbDelta($sql2);

    add_option('accordions_or_faqs_oxilab_version', $accordions_or_faqs_oxilab_version);
    set_transient('_Accordions_or_faqs_oxilab_welcome_activation_redirect', true, 30);
}


add_action('admin_init', 'Accordions_or_faqs_oxilab_welcome_activation_redirect');

function Accordions_or_faqs_oxilab_welcome_activation_redirect() {
    if (!get_transient('_Accordions_or_faqs_oxilab_welcome_activation_redirect')) {
        return;
    }
    delete_transient('_Accordions_or_faqs_oxilab_welcome_activation_redirect');
    if (is_network_admin() || isset($_GET['activate-multi'])) {
        return;
    }
    wp_safe_redirect(add_query_arg(array('page' => 'accordions_or_faqs_oxilab-welcome'), admin_url('index.php')));
}

add_action('admin_menu', 'Accordions_or_faqs_oxilab_welcome_pages');

function Accordions_or_faqs_oxilab_welcome_pages() {
    add_dashboard_page(
            'Welcome To Accordions or FAQs', 'Welcome To Accordions or FAQs', 'read', 'accordions_or_faqs_oxilab-welcome', 'accordions_or_faqs_oxilab_welcome'
    );
}


function accordions_or_faqs_oxilab_welcome() {
    wp_enqueue_style('accordions_or_faqs_oxilab-welcome', plugins_url('js-css/admin-welcome.css', __FILE__));
    ?>
    <div class="wrap about-wrap">
        <h1>Welcome to Accordions or FAQs</h1>
        <div class="about-text">
            Thank you for choosing Accordions or FAQs - the most friendly WordPress accordions or faqs Plugins. Here's how to get started.
        </div>
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active">
                Getting Started		
            </a>
        </h2>
        <p class="about-description">
            Use the tips below to get started using Accordions or FAQs. You will be up and running in no time.	
        </p>    
        <div class="feature-section">
            <h3>Have any Bugs or Suggestion</h3>
            <p>Your suggestions will make this plugin even better, Even if you get any bugs on Accordions or FAQs so let us to know, We will try to solved within few hours</p>
            <p><a href="https://www.oxilab.org/contact-us" target="_blank" rel="noopener" class="aofoxilab-image-features-button button button-primary">Contact Us</a>
                <a href="https://wordpress.org/support/plugin/accordions-or-faqs/" target="_blank" rel="noopener" class="aofoxilab-image-features-button button button-primary">Support Forum</a></p>

        </div>

    </div>
    <?php
}

add_action('admin_head', 'accordions_or_faqs_oxilab_welcome_remove_menus');

function accordions_or_faqs_oxilab_welcome_remove_menus() {
    remove_submenu_page('index.php', 'accordions_or_faqs_oxilab-welcome');
}