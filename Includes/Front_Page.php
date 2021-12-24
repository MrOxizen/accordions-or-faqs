<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Index
 *
 * author @biplob018
 */
class Front_Page {

    use \OXI_ACCORDIONS_PLUGINS\Helper\Additional;

    /**
     * Database
     *
     * @since 2.0.1
     */
    public $database;

    public function __construct() {
        $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database;
        $this->additional_load();
        $this->public_render();
    }

    public function database_data() {
        return $this->database->wpdb->get_results($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE type = %s ', 'accordions-or-faqs'), ARRAY_A);
    }

    public function additional_load() {
        $this->database_data();
        $this->admin_front_additional();
        apply_filters('oxi-accordions-plugin/admin_menu', TRUE);
    }

    /**
     * Generate safe path
     * @since v2.0.1
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function public_render() {
        ?>
        <div class="oxi-addons-row">
            <?php
            $this->admin_header();
            $this->created_shortcode();
            $this->create_new();
            ?>
        </div>
        <?php
    }

    public function admin_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Oxilab Accordions › Home
                </h1>
                <p> Collect Accordions Shortcode, Edit, Delect, Clone or Export it. </p>
            </div>
        </div>
        <?php
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

    public function create_new() {
        ?>
        <div class="oxi-addons-row">
            <div class="oxi-addons-col-1 oxi-import">
                <div class="oxi-addons-style-preview">
                    <div class="oxilab-admin-style-preview-top">
                        <a href="#" id="oxilab-accordions-import-json">
                            <div class="oxilab-admin-add-new-item">
                                <span>
                                    <i class="fas fa-plus-circle oxi-icons"></i>
                                    Import Accordions
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="oxi-addons-style-create-modal" >
            <form method="post" id="oxi-addons-style-modal-form">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tabs Clone</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class=" form-group row">
                                <label for="addons-style-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Give your Shortcode Name Here">Name</label>
                                <div class="col-sm-6 addons-dtm-laptop-lock">
                                    <input class="form-control" type="text" value="" id="addons-style-name"  name="addons-style-name" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <input type="hidden" id="oxistyleid" name="oxistyleid" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" name="addonsdatasubmit" id="addonsdatasubmit" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php
    }

    public function created_shortcode() {
        ?>
        <div class="oxi-addons-row"> <div class="oxi-addons-row table-responsive abop" style="margin-bottom: 20px; opacity: 0; height: 0px">
                <table class="table table-hover widefat oxi_addons_table_data" style="background-color: #fff; border: 1px solid #ccc">
                    <thead>
                        <tr>
                            <th style="width: 15%">ID</th>
                            <th style="width: 25%">Name</th>
                            <th style="width: 35%">Shortcode</th>
                            <th style="width: 25%">Edit Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        foreach ($this->database_data() as $value) {
                            $id = $value['id'];
                            ?>
                            <tr>
                                <td><?php echo esc_html($id) ?></td>
                                <td><?php echo esc_html(ucwords($value['name'])) ?></td>
                                <td><span>Shortcode &nbsp;&nbsp;<input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="[oxi_accordions id=&quot;<?php echo esc_attr($id) ?>&quot;]"></span> <br>
                                    <span>Php Code &nbsp;&nbsp; <input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="&lt;?php echo do_shortcode(&#039;[oxi_accordions id=&quot;<?php echo esc_attr($id) ?>&quot;]&#039;); ?&gt;"></span></td>
                                <td>
                                    <a href="<?php echo esc_url(admin_url("admin.php?page=oxi-accordions-ultimate-new&styleid=" . esc_attr($id) . "")) ?>"  title="Edit"  class="btn btn-info" style="float:left; margin-right: 5px; margin-left: 5px;">Edit</a>
                                    <form method="post" class="oxi-addons-style-delete">
                                        <input type="hidden" name="oxideleteid" id="oxideleteid" value="<?php echo esc_attr($id) ?>">
                                        <button class="btn btn-danger" style="float:left"  title="Delete"  type="submit" value="delete" name="addonsdatadelete">Delete</button>
                                    </form>
                                    <a href="<?php echo esc_url_raw(rest_url()) . 'oxiaccordionsultimate/v1/shortcode_export?styleid=' . $id . '&_wpnonce=' . wp_create_nonce('wp_rest') ?>"  title="Export"  class="btn btn-info" style="float:left; margin-right: 5px; margin-left: 5px;">Export</a>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <br>
            <br></div>
        <?php
    }

}
