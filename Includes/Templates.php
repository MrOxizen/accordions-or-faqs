<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

/**
 * Description of Templates
 *
 * @author biplo
 */
class Templates {

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $database;
    public $local_template;

    /**
     * Define Page Type
     *
     * @since 2.0.1
     */
    public $layouts;

    use \OXI_ACCORDIONS_PLUGINS\Helper\Helper;
    use \OXI_ACCORDIONS_PLUGINS\Helper\Additional;

    public $imported = [];
    public $TEMPLATE;

    /**
     * Constructor of Oxilab tabs Home Page
     *
     * @since 2.0.0
     */
    public function __construct() {
        $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
        $this->layouts = (isset($_GET) ? $_GET : '');
        $this->css_js_load();
        $this->render_html();
    }

    public function css_js_load() {
        $this->admin_template_additional();
        apply_filters('oxi-accordions-plugin/admin_menu', TRUE);
        $template = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->import_table} WHERE type = %s ORDER by name ASC", 'accordions-or-faqs'), ARRAY_A);
        if (count($template) < 1):
            for ($i = 1; $i < 5; $i++) {
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->import_table} (type, name) VALUES (%s, %s)", array('accordions-or-faqs', $i)));
                $this->imported[$i] = $i;
            }
        else:
            foreach ($template as $value) {
                $this->imported[(int) $value['name']] = $value['name'];
            }
        endif;
        ksort($this->imported);
        $this->get_local_tempalte();
    }

    public function get_local_tempalte() {
        $basename = array_map('basename', glob(OXI_ACCORDIONS_PATH . 'demo-template/' . '*.json', GLOB_BRACE));
        foreach ($basename as $key => $value) {
            $onlyname = explode('template-', str_replace('.json', '', $value))[1];
            if ((int) $onlyname):
                $this->local_template[$onlyname] = $value;
            endif;
        }
        ksort($this->local_template);
        return;
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function render_html() {
        ?>
        <div class="oxi-addons-row">
            <?php
            if (array_key_exists('import', $this->layouts)):
                $this->import_header();
                $this->import_body();
            else:
                $this->template_header();
                $this->template_body();
            endif;
            ?>
        </div>
        <?php
    }

    public function import_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Oxilab Accordions › Import Template</h1>
                <p> Select Accordions layout and import for future use. </p>
            </div>
        </div>
        <?php
    }

    public function template_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Oxilab Accordions › Create New</h1>
                <p> Select Accordions layouts, give your Accordions name and create new Accordions. </p>
            </div>
        </div>
        <?php
    }

    public function template_body() {
        ?>
        <div class="oxi-addons-row">
            <?php
            foreach ($this->imported as $value) {
                $Style = 'Template_' . $value;
                if (array_key_exists($value, $this->local_template)):
                    $folder = $this->safe_path(OXI_ACCORDIONS_PATH . 'demo-template/');
                    $template_data = json_decode(file_get_contents($folder . $this->local_template[$value]), true);
                    $Cls = 'OXI_ACCORDIONS_PLUGINS\Layouts\Views\\' . $Style;
                    ?>
                    <div class="oxi-addons-col-1" id="<?php echo $Style; ?>">
                        <div class="oxi-addons-style-preview">
                            <div class="oxi-addons-style-preview-top oxi-addons-center">
                                <?php
                                if (class_exists($Cls) && isset($template_data['style']['rawdata'])):
                                    new $Cls($template_data['style'], $template_data['child']);
                                endif;
                                ?>
                            </div>
                            <div class="oxi-addons-style-preview-bottom">
                                <div class="oxi-addons-style-preview-bottom-left">
                                    <?php echo $template_data['style']['name']; ?>
                                </div>
                                <div class="oxi-addons-style-preview-bottom-right">
                                    <form method="post" style=" display: inline-block; " class="shortcode-addons-template-deactive">
                                        <input type="hidden" name="oxideletestyle" value="<?php echo $value; ?>">
                                        <button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Delete"  type="submit" value="Deactive" name="addonsstyledelete">Deactive</button>  
                                    </form>
                                    <button type="button" class="btn btn-success oxi-addons-addons-template-create oxi-addons-addons-js-create" data-toggle="modal" template-id="<?php echo $value; ?>">Create Style</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endif;
            }
            ?>
        </div>
        <?php
        $this->create_new_modal();
    }

    public function create_new_modal() {
        echo _('<div class="oxi-addons-row">
                        <div class="oxi-addons-col-1 oxi-import">
                            <div class="oxi-addons-style-preview">
                                <div class="oxilab-admin-style-preview-top">
                                    <a href="' . admin_url("admin.php?page=oxi-accordions-ultimate-new&import") . '">
                                        <div class="oxilab-admin-add-new-item">
                                            <span>
                                                <i class="fas fa-plus-circle oxi-icons"></i>  
                                                Import Templates
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>');

        echo __('<div class="modal fade" id="oxi-addons-style-create-modal" >
                        <form method="post" id="oxi-addons-style-modal-form">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">                    
                                        <h4 class="modal-title">New Accordions</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class=" form-group row">
                                            <label for="addons-style-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Give your Shortcode Name Here">Name</label>
                                            <div class="col-sm-6 addons-dtm-laptop-lock">
                                                <input class="form-control" type="text" value="" id="addons-style-name"  name="addons-style-name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="template-id" name="template-id" value="">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="addonsdatasubmit" id="addonsdatasubmit" value="Save">
                                      </div>
                                </div>
                            </div>
                        </form>
                    </div>');
    }

    public function import_body() {
        ?>
        <div class="oxi-addons-row">
            <?php
            foreach ($this->local_template as $id => $value) {
                if (!array_key_exists($id, $this->imported)):
                    $folder = $this->safe_path(OXI_ACCORDIONS_PATH . 'demo-template/');
                    $template_data = json_decode(file_get_contents($folder . $value), true);
                    $Cls = 'OXI_ACCORDIONS_PLUGINS\Layouts\Views\Template_\\' . ucfirst($id);
                    ?>
                    <div class="oxi-addons-col-1" id="Style<?php echo $id; ?>">
                        <div class="oxi-addons-style-preview">
                            <div class="oxi-addons-style-preview-top oxi-addons-center">
                                <?php
                                if (class_exists($Cls) && isset($template_data['style']['rawdata'])):
                                    new $Cls($template_data['style'], $template_data['child']);
                                endif;
                                ?>
                            </div>
                            <div class="oxi-addons-style-preview-bottom">
                                <div class="oxi-addons-style-preview-bottom-left">
                                    <?php echo $template_data['style']['name']; ?>
                                </div>
                                <div class="oxi-addons-style-preview-bottom-right">
                                    <?php
                                    if ($id > 7 && apply_filters(OXI_ACCORDIONS_PREMIUM, true) == false):
                                        ?>
                                        <form method="post" style=" display: inline-block; " class="shortcode-addons-template-pro-only">
                                            <button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Pro Only"  type="submit" value="pro only" name="addonsstyleproonly">Pro Only</button>  
                                        </form>
                                        <?php
                                    else:
                                        ?>
                                        <form method="post" style=" display: inline-block; " class="shortcode-addons-template-import">
                                            <input type="hidden" name="oxiimportstyle" value="<?php echo $id; ?>">
                                            <button class="btn btn-success oxi-addons-addons-template-create" title="import"  type="submit" value="Import" name="addonsstyleimport">Import</button>  
                                        </form>
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endif;
            }
            ?>
        </div>
        <?php
    }

}
