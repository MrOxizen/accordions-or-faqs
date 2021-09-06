<?php

namespace OXI_ACCORDIONS_PLUGINS\Classes;

/**
 * Description of API
 *
 * @author biplo
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

    /**
     * Constructor of plugin class
     *
     * @since 2.0.1
     */
    public function __construct() {
        $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
        $this->build_api();
    }

    public function build_api() {
        add_action('rest_api_init', function () {
            register_rest_route(untrailingslashit('oxiaccordionsultimate/v1/'), '/(?P<action>\w+)/', array(
                'methods' => array('GET', 'POST'),
                'callback' => [$this, 'api_action'],
                'permission_callback' => '__return_true'
            ));
        });
    }

    public function api_action($request) {
        $this->request = $request;
        $this->rawdata = addslashes($request['rawdata']);
        $this->styleid = $request['styleid'];
        $this->childid = $request['childid'];
        $class = $request['class'];
        $action_class = strtolower($request->get_method()) . '_' . sanitize_key($request['action']);

        if ($class != ''):
            $args = $request['args'];
            $optional = $request['optional'];
            ob_start();
            $CLASS = new $class;
            $CLASS->__construct($request['action'], $this->rawdata, $args, $optional);
            return ob_get_clean();
        else:
            if (method_exists($this, $action_class)) {
                return $this->{$action_class}();
            }
        endif;
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function array_replace($arr = [], $search = '', $replace = '') {
        array_walk($arr, function (&$v) use ($search, $replace) {
            $v = str_replace($search, $replace, $v);
        });
        return $arr;
    }

    public function post_create_new_accordions() {
        $params = json_decode(stripslashes($this->rawdata), true);
        $folder = $this->safe_path(OXI_ACCORDIONS_PATH . 'demo-template/');
        $filename = $params['template-id'];
        return $this->post_json_import($folder, $filename, $params['addons-style-name']);
    }

    public function post_json_import($folder, $filename, $name = 'truee') {

        if (is_file($folder . $filename)) {
            $this->rawdata = file_get_contents($folder . $filename);

            $params = json_decode($this->rawdata, true);
            $style = $params['style'];
            $child = $params['child'];
            if ($name != 'truee'):
                $style['name'] = $name;
            endif;
            $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, type, rawdata) VALUES ( %s, %s, %s)", array($style['name'], OXI_ACCORDIONS_TEXTDOMAIN, $style['rawdata'])));
            $redirect_id = $this->database->wpdb->insert_id;

            if ($redirect_id > 0):
                $raw = json_decode(stripslashes($style['rawdata']), true);
                $raw['style-id'] = $redirect_id;
                $CLASS = '\OXI_ACCORDIONS_PLUGINS\Layouts\Helper';
                $CLASS = new $CLASS('admin');
                $CLASS->template_css_render($raw);
                foreach ($child as $value) {
                    $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d,  %s)", array($redirect_id, $value['rawdata'])));
                }
                return admin_url("admin.php?page=oxi-accordions-ultimate-new&styleid=$redirect_id");
            endif;
        }
    }

    public function post_shortcode_delete() {
        $styleid = (int) $this->styleid;
        if ($styleid):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->parent_table} WHERE id = %d", $styleid));
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE styleid = %d", $styleid));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    public function post_shortcode_deactive() {
        $params = json_decode(stripslashes($this->rawdata), true);
        $id = (int) $params['oxideletestyle'];
        if ($id > 0):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->import_table} WHERE name = %s and type = %s", $id, OXI_ACCORDIONS_TEXTDOMAIN));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    public function post_shortcode_active() {
        $params = json_decode(stripslashes($this->rawdata), true);
        $id = (int) $params['oxiimportstyle'];
        if ($id > 0):
            $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->import_table} (type, name) VALUES (%s, %s)", array(OXI_ACCORDIONS_TEXTDOMAIN, $id)));
            return admin_url("admin.php?page=oxi-accordions-ultimate-new#Template_" . $id);
        else:
            return 'Silence is Golden';
        endif;
    }

    public function get_shortcode_export() {
        $styleid = (int) $this->styleid;
        if ($styleid):
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
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Send file headers.
     *
     *
     * @param string $file_name File name.
     * @param int    $file_size File size.
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
        if ((int) $this->styleid):
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
        $settings = json_decode(stripslashes($this->rawdata), true);
        $name = sanitize_text_field($settings['addonsstylename']);
        $id = $settings['addonsstylenameid'];
        if ((int) $id):
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
        if ((int) $this->styleid):
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
     * @since 9.3.0
     */
    public function post_elements_template_rearrange_save_data() {
        $params = explode(',', $this->rawdata);
        foreach ($params as $value) {
            if ((int) $value):
                $data = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE id = %d ", $value), ARRAY_A);
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s)", array($data['styleid'], $data['rawdata'])));
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
     * @since 9.3.0
     */
    public function post_elements_template_modal_data() {
        if ((int) $this->styleid):
            if ((int) $this->childid):
                $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->child_table} SET rawdata = %s WHERE id = %d", $this->rawdata, $this->childid));
            else:
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s )", array($this->styleid, $this->rawdata)));
            endif;
        endif;
        return 'ok';
    }

    /**
     * Template Template Render
     *
     * @since 9.3.0
     */
    public function post_elements_template_render_data() {
        $transient = 'accordions-or-faqs-template-' . $this->styleid;
        set_transient($transient, $this->rawdata, 1 * HOUR_IN_SECONDS);
        return 'Transient Done';
    }

    /**
     * Template Modal Data Edit Form 
     *
     * @since 9.3.0
     */
    public function post_elements_template_modal_data_edit() {
        if ((int) $this->childid):
            $listdata = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE id = %d ", $this->childid), ARRAY_A);
            $returnfile = json_decode(stripslashes($listdata['rawdata']), true);
            $returnfile['shortcodeitemid'] = $this->childid;
            return json_encode($returnfile);
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Template Child Delete Data
     *
     * @since 9.3.0
     */
    public function post_elements_template_modal_data_delete() {
        if ((int) $this->childid):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE id = %d ", $this->childid));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Admin Notice API  loader
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
        $notice = $this->request['notice'];
        if ($notice == 'maybe'):
            $data = strtotime("now");
            update_option('accordions-or-faqs-activation-date', $data);
        else:
            update_option('accordions-or-faqs-activation-notice', $notice);
        endif;
        return $notice;
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_oxi_settings() {
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        update_option($rawdata['name'], $rawdata['value']);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin License
     * @return void
     */
    public function post_oxi_license() {
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        $new = $rawdata['license'];
        $old = get_option('accordions_or_faqs_license_key');
        $status = get_option('accordions_or_faqs_license_status');
        if ($new == ''):
            if ($old != '' && $status == 'valid'):
                $this->deactivate_license($old);
            endif;
            delete_option('accordions_or_faqs_license_key');
            $data = ['massage' => '<span class="oxi-confirmation-blank"></span>', 'text' => ''];
        else:
            update_option('accordions_or_faqs_license_key', $new);
            delete_option('accordions_or_faqs_license_status');
            $r = $this->activate_license($new);
            if ($r == 'success'):
                $data = ['massage' => '<span class="oxi-confirmation-success"></span>', 'text' => 'Active'];
            else:
                $data = ['massage' => '<span class="oxi-confirmation-failed"></span>', 'text' => $r];
            endif;
        endif;
        return $data;
    }

    public function activate_license($key) {
        $api_params = array(
            'edd_action' => 'activate_license',
            'license' => $key,
            'item_name' => urlencode('Accordions - Multiple Accordions or FAQs Builders'),
            'url' => home_url()
        );

        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {

                switch ($license_data->error) {

                    case 'expired' :

                        $message = sprintf(
                                __('Your license key expired on %s.'), date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;

                    case 'revoked' :

                        $message = __('Your license key has been disabled.');
                        break;

                    case 'missing' :

                        $message = __('Invalid license.');
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = __('Your license is not active for this URL.');
                        break;

                    case 'item_name_mismatch' :

                        $message = sprintf(__('This appears to be an invalid license key for %s.'), Responsive_Tabs_with_Accordions);
                        break;

                    case 'no_activations_left':

                        $message = __('Your license key has reached its activation limit.');
                        break;

                    default :

                        $message = __('An error occurred, please try again.');
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

    public function deactivate_license($key) {
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $key,
            'item_name' => urlencode('Accordions - Multiple Accordions or FAQs Builders'),
            'url' => home_url()
        );
        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
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

}
