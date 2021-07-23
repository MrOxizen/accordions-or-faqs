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
        if (!empty($this->styleid)):
//            $styleid = (int) $this->styleid;
//            $newdata = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
//            $old = false;
//            if (array_key_exists('css', $newdata) && $newdata['css'] != ''):
//                $old = true;
//                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, style_name, rawdata, css) VALUES (%s, %s, %s, %s)", array($this->rawdata, $newdata['style_name'], $newdata['rawdata'], $newdata['css'])));
//            else:
//                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, style_name, rawdata) VALUES ( %s, %s, %s)", array($this->rawdata, $newdata['style_name'], $newdata['rawdata'])));
//            endif;
//            $redirect_id = $this->database->wpdb->insert_id;
//            if ($redirect_id > 0):
//                if ($old == true):
//                    $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
//                    foreach ($child as $value) {
//                        $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata, title, files, css) VALUES (%d, %s, %s, %s, %s)", array($redirect_id, $value['rawdata'], $value['title'], $value['files'], $value['css'])));
//                    }
//                else:
//                    $raw = json_decode(stripslashes($newdata['rawdata']), true);
//                    $raw['style-id'] = $redirect_id;
//                    $name = ucfirst($newdata['style_name']);
//                    $CLASS = '\OXI_TABS_PLUGINS\Render\Admin\\' . $name;
//                    $C = new $CLASS('admin');
//                    $f = $C->template_css_render($raw);
//                    $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
//                    foreach ($child as $value) {
//                        $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s)", array($redirect_id, $value['rawdata'])));
//                    }
//                endif;
//                return admin_url("admin.php?page=oxi-tabs-ultimate-new&styleid=$redirect_id");
//            endif;
        else:

            $params = json_decode(stripslashes($this->rawdata), true);

            $folder = $this->safe_path(OXI_ACCORDIONS_PATH . 'demo-template/');
            $filename = 'accordions-or-faqs-template-' . $params['template-id'] . '.json';

            return $this->post_json_import($folder, $filename, $params['addons-style-name']);

        endif;
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

            $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, type, style_name, rawdata) VALUES ( %s, %s, %s, %s)", array($style['name'], OXI_ACCORDIONS_TEXTDOMAIN, $style['style_name'], $style['rawdata'])));
            $redirect_id = $this->database->wpdb->insert_id;

            if ($redirect_id > 0):
                $raw = json_decode(stripslashes($style['rawdata']), true);
                $raw['style-id'] = $redirect_id;
//                $style_name = ucfirst($style['style_name']);
////                $CLASS = '\OXI_ACCORDIONS_PLUGINS\Layouts\Admin\\' . $style_name;
////                $C = new $CLASS('admin');
////
                //    $f = $C->template_css_render($raw);
                foreach ($child as $value) {
                    $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d,  %s)", array($redirect_id, $value['rawdata'])));
                }
                return admin_url("admin.php?page=oxi-accordions-ultimate-new&styleid=$redirect_id");
            endif;
        }
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
}
