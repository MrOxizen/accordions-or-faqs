<?php

namespace OXI_ACCORDIONS_PLUGINS\Classes;

use OXI_ACCORDIONS_PLUGINS\Helper\Database;
use WP_Error;
use WP_REST_Request;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of API
 *
 * author @biplob018
 */
class API {

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $database;
    public $request;
    public $styleid;
    public $childid;

    public function save_action() {
        if (!$this->get_permissions_check()) {
            return new WP_REST_Request('Invalid URL', 422);
            die();
        }
        $wpnonce = sanitize_key(wp_unslash($_REQUEST['_wpnonce']));
        if (!wp_verify_nonce($wpnonce, 'oxi_accordions_ultimate')) :
            return new WP_REST_Request('Invalid URL', 422);
            die();
        endif;

        $functionname = isset($_REQUEST['functionname']) ? sanitize_text_field($_REQUEST['functionname']) : '';
        $this->rawdata = isset($_REQUEST['rawdata']) ? sanitize_post($_REQUEST['rawdata']) : '';
        $this->styleid = isset($_REQUEST['styleid']) ? (int) $_REQUEST['styleid'] : '';
        $this->childid = isset($_REQUEST['childid']) ? (int) $_REQUEST['childid'] : '';
        $action_class = 'post_' . sanitize_key($functionname);
        if (method_exists($this, $action_class)) {
            echo $this->{$action_class}();
        }
        die();
    }

    public function post_shortcode_delete() {
        $styleid = (int) $this->styleid;
        if ($styleid) :
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->parent_table} WHERE id = %d", $styleid));
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE styleid = %d", $styleid));
            return 'done';
        else :
            return 'Silence is Golden';
        endif;
    }

    public function post_shortcode_deactive() {
        $params = $this->validate_post();
        $id = (int) $params['oxideletestyle'];
        if ($id > 0) :
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->import_table} WHERE name = %s and type = %s", $id, 'accordions-or-faqs'));
            return 'done';
        else :
            return 'Silence is Golden';
        endif;
    }

    public function validate_post($data = '') {
        $rawdata = [];
        if (!empty($data)) :
            $arrfiles = json_decode(stripslashes($data), true);
        else :
            $data = $this->rawdata;
            $arrfiles = json_decode(stripslashes($this->rawdata), true);
        endif;
        if (is_array($arrfiles)) :
            $rawdata = array_map([$this, 'allowed_html'], $arrfiles);
        else :
            $rawdata = $this->allowed_html($data);
        endif;
        return $rawdata;
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

    public function post_shortcode_active() {
        $params = $this->validate_post();
        $id = (int) $params['oxiimportstyle'];
        if ($id > 0) :
            $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->import_table} (type, name) VALUES (%s, %s)", ['accordions-or-faqs', $id]));
            return admin_url("admin.php?page=oxi-accordions-ultimate-new#Template_" . $id);
        else :
            return 'Silence is Golden';
        endif;
    }

    public function post_shortcode_export() {


        $styleid = (int) $this->styleid;
        if ($styleid) :
            $style = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->parent_table} WHERE id = %d", $styleid), ARRAY_A);
            $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
            $filename = 'accordions-or-faqs-template-' . $styleid . '.json';
            $files = [
                'style' => $style,
                'child' => $child,
            ];
            $finalfiles = json_encode($files);
            $this->send_file_headers($filename, strlen($finalfiles));
            @ob_end_clean();
            flush();
            echo $finalfiles;
            die;
        else :
            return 'Silence is Golden';
        endif;
    }

    /**
     * Send file headers.
     *
     *
     * @param string $file_name File name.
     * @param int $file_size File size.
     */
    private function send_file_headers($file_name, $file_size) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file_size);
    }

    /**
     * Template Style Data
     *
     * @since 2.0.1
     */
    public function post_elements_template_style() {
        $settings = json_decode(stripslashes($this->rawdata), true);
        $stylesheet = '';
        if ((int) $this->styleid) :
            $transient = 'accordions-or-faqs-template-' . $this->styleid;
            delete_transient($transient);
            $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET rawdata = %s, stylesheet = %s WHERE id = %d", $this->rawdata, $stylesheet, $this->styleid));
            $CLASS = '\OXI_ACCORDIONS_PLUGINS\Layouts\Helper';
            $CLASS = new $CLASS('admin');
            return $CLASS->template_css_render($settings);
        endif;
    }

    /**
     * Template Name Change
     *
     * @since 2.0.1
     */
    public function post_template_name() {
        $settings = $this->validate_post();
        $name = sanitize_text_field($settings['addonsstylename']);
        $id = $settings['addonsstylenameid'];
        if ((int) $id) :
            $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET name = %s WHERE id = %d", $name, $id));
            return 'success';
        endif;
        return 'Silence is Golden';
    }

    /**
     * Template Name Change
     *
     * @since 2.0.1
     */
    public function post_elements_rearrange_modal_data() {
        if ((int) $this->styleid) :
            $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $this->styleid), ARRAY_A);
            $render = [];
            foreach ($child as $k => $value) {
                $data = json_decode(stripcslashes($value['rawdata']));
                $render[$value['id']] = $data;
            }
            return json_encode($render);
        endif;
        return 'Silence is Golden';
    }

    /**
     * Template Name Change
     *
     * @since 2.0.1
     */
    public function post_elements_template_rearrange_save_data() {
        $params = explode(',', $this->validate_post());
        foreach ($params as $value) {
            if ((int) $value) :
                $data = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE id = %d ", $value), ARRAY_A);
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s)", [$data['styleid'], $data['rawdata']]));
                $redirect_id = $this->database->wpdb->insert_id;
                if ($redirect_id == 0) {
                    return;
                }
                if ($redirect_id != 0) {
                    $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE id = %d", $value));
                }
            endif;
        }
        return 'success';
    }

    /**
     * Template Modal Data
     *
     * @since 2.0.1
     */
    public function post_elements_template_modal_data() {
        if ((int) $this->styleid) :
            if ((int) $this->childid) :
                $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->child_table} SET rawdata = %s WHERE id = %d", $this->rawdata, $this->childid));
            else :
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s )", [$this->styleid, $this->rawdata]));
            endif;
        endif;
        return 'ok';
    }

    /**
     * Template Template Render
     *
     * @since 2.0.1
     */
    public function post_elements_template_render_data() {
        $transient = 'accordions-or-faqs-template-' . $this->styleid;
        set_transient($transient, $this->rawdata, 1 * HOUR_IN_SECONDS);
        return 'Transient Done';
    }

    /**
     * Template Modal Data Edit Form
     *
     * @since 2.0.1
     */
    public function post_elements_template_modal_data_edit() {
        if ((int) $this->childid) :
            $listdata = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE id = %d ", $this->childid), ARRAY_A);
            $returnfile = json_decode(stripslashes($listdata['rawdata']), true);
            $returnfile['shortcodeitemid'] = $this->childid;
            return json_encode($returnfile);
        else :
            return 'Silence is Golden';
        endif;
    }

    /**
     * Template Child Delete Data
     *
     * @since 2.0.1
     */
    public function post_elements_template_modal_data_delete() {
        if ((int) $this->childid) :
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE id = %d ", $this->childid));
            return 'done';
        else :
            return 'Silence is Golden';
        endif;
    }

    /**
     * Admin Notice API  loader
     * @return void
     * @return void
     */
    public function post_oxi_recommended() {
        $data = 'done';
        update_option('accordions-or-faqs-recommended', $data);
        return $data;
    }

    /**
     * Admin Notice Recommended  loader
     * @return void
     */
    public function post_notice_dissmiss() {
        $notice = sanitize_text_field($this->request['notice']);
        if ($notice == 'maybe') :
            $data = strtotime("now");
            update_option('accordions-or-faqs-activation-date', $data);
        else :
            update_option('accordions-or-faqs-activation-notice', $notice);
        endif;
        return $notice;
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_user_permission() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = $this->validate_post();
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_accordions_user_permission', $value);
        delete_transient('oxi_accordions_user_permission_role');
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_font_awesome() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = $this->validate_post();
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_font_awesome', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_global_schema() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = $this->validate_post();
        $value = sanitize_text_field($rawdata['value']);
        update_option('accordions_or_faqs_global_schema', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_oxi_accordions_support_massage() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = $this->validate_post();
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_accordions_support_massage', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin License
     * @return void
     */
    public function post_oxi_license() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = $this->validate_post();
        $new = sanitize_text_field($rawdata['license']);
        $old = get_option('accordions_or_faqs_license_key');
        $status = get_option('accordions_or_faqs_license_status');
        if ($new == '') :
            if ($old != '' && $status == 'valid') :
                $this->deactivate_license($old);
            endif;
            delete_option('accordions_or_faqs_license_key');
            $data = ['massage' => '<span class="oxi-confirmation-blank"></span>', 'text' => ''];
        else :
            update_option('accordions_or_faqs_license_key', $new);
            delete_option('accordions_or_faqs_license_status');
            $r = $this->activate_license($new);
            if ($r == 'success') :
                $data = ['massage' => '<span class="oxi-confirmation-success"></span>', 'text' => 'Active'];
            else :
                $data = ['massage' => '<span class="oxi-confirmation-failed"></span>', 'text' => $r];
            endif;
        endif;
        return json_encode($data);
    }

    public function deactivate_license($key) {
        $api_params = [
            'edd_action' => 'deactivate_license',
            'license' => $key,
            'item_name' => urlencode('Accordions - Multiple Accordions or FAQs Builders'),
            'url' => home_url()
        ];
        $response = wp_remote_post('https://www.oxilab.org', ['timeout' => 15, 'sslverify' => false, 'body' => $api_params]);
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = esc_html('An error occurred, please try again.');
            }
            return $message;
        }
        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ($license_data->license == 'deactivated') {
            delete_option('accordions_or_faqs_license_status');
            delete_option('accordions_or_faqs_license_key');
        }
        return 'success';
    }

    public function activate_license($key) {
        $api_params = [
            'edd_action' => 'activate_license',
            'license' => $key,
            'item_name' => urlencode('Accordions - Multiple Accordions or FAQs Builders'),
            'url' => home_url()
        ];

        $response = wp_remote_post('https://www.oxilab.org', ['timeout' => 15, 'sslverify' => false, 'body' => $api_params]);

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = esc_html('An error occurred, please try again.');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {

                switch ($license_data->error) {

                    case 'expired':

                        $message = sprintf(
                                'Your license key expired on %s.',
                                date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp'))),
                        );
                        break;

                    case 'revoked':

                        $message = esc_html('Your license key has been disabled.');
                        break;

                    case 'missing':

                        $message = esc_html('Invalid license.');
                        break;

                    case 'invalid':
                    case 'site_inactive':

                        $message = esc_html('Your license is not active for this URL.');
                        break;

                    case 'item_name_mismatch':

                        $message = sprintf(esc_html('This appears to be an invalid license key for %s.'), 'accordions-or-faqs');
                        break;

                    case 'no_activations_left':

                        $message = esc_html('Your license key has reached its activation limit.');
                        break;

                    default:

                        $message = esc_html('An error occurred, please try again.');
                        break;
                }
            }
        }

        if (!empty($message)) {
            return $message;
        }
        update_option('accordions_or_faqs_license_status', $license_data->license);
        return 'success';
    }

    public function array_replace($arr = [], $search = '', $replace = '') {
        array_walk($arr, function (&$v) use ($search, $replace) {
            $v = str_replace($search, $replace, $v);
        });
        return $arr;
    }

    public function post_create_new_accordions() {
        $params = $this->validate_post();
        $folder = $this->safe_path(OXI_ACCORDIONS_PATH . 'demo-template/');
        $filename = sanitize_text_field($params['template-id']);
        $name = sanitize_text_field($params['addons-style-name']);
        $data = json_decode(file_get_contents($folder . $filename), true);

        if (empty($data)) {
            return new WP_Error('file_error', 'Invalid File');
        }

        $content = $data['style'];

        if (!is_array($content) || $content['type'] != 'accordions-or-faqs') {
            return new WP_Error('file_error', 'Invalid Content In File');
        }

        return $this->post_json_import($data, $name);
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function post_json_import($params, $name = 'truee') {

        if (!is_array($params) || $params['style']['type'] != 'accordions-or-faqs') {
            return new WP_Error('file_error', 'Invalid Content In File');
        }
        $style = $params['style'];
        $child = $params['child'];
        if ($name != 'truee') :
            $style['name'] = $name;
        endif;
        $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, type, rawdata) VALUES ( %s, %s, %s)", [$style['name'], 'accordions-or-faqs', $style['rawdata']]));
        $redirect_id = $this->database->wpdb->insert_id;

        if ($redirect_id > 0) :
            $raw = json_decode(stripslashes($style['rawdata']), true);
            $raw['style-id'] = $redirect_id;
            $CLASS = '\OXI_ACCORDIONS_PLUGINS\Layouts\Helper';
            $CLASS = new $CLASS('admin');
            $CLASS->template_css_render($raw);
            foreach ($child as $value) {
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d,  %s)", [$redirect_id, $value['rawdata']]));
            }
            return admin_url("admin.php?page=oxi-accordions-ultimate-new&styleid=$redirect_id");
        endif;
    }

    /**
     * Constructor of plugin class
     *
     * @since 2.0.1
     */
    public function __construct() {
        $this->database = new Database();
        add_action('wp_ajax_oxi_accordions_ultimate', [$this, 'save_action']);
    }

    public function get_permissions_check() {
        $transient = get_transient('oxi_accordions_user_permission_role');

        if (false === $transient) {
            $user_role = get_option('oxi_accordions_user_permission');
            $role_object = get_role($user_role);
            $first_key = '';
            if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
                reset($role_object->capabilities);
                $first_key = key($role_object->capabilities);
            } else {
                $first_key = 'manage_options';
            }
            $transient = 'oxi_accordions_user_permission_role';
            set_transient($transient, $first_key, 1 * HOUR_IN_SECONDS);
            return current_user_can($first_key);
        }
        return current_user_can($transient);
    }

}
