<?php
if (!defined('ABSPATH'))
    exit;
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}
wp_enqueue_style('afoxi-vendor-style', plugins_url('js-css/style.css', __FILE__));
wp_enqueue_script('afoxi-vendor-bootstrap-jss', plugins_url('js-css/bootstrap.min.js', __FILE__));
wp_enqueue_style('afoxi-vendor-bootstrap', plugins_url('js-css/bootstrap.min.css', __FILE__));
wp_enqueue_style('font-awesome', plugins_url('js-css/font-awesome.min.css', __FILE__));
?>
<div class="wrap">
    <h1> Accordions or FAQs <a href="<?php echo admin_url("admin.php?page=accordions-or-faqs-oxilab-new"); ?>" class="btn btn-primary"> Add New</a></h1>
    <div class="afoxi-admin-wrapper table-responsive" style="margin-top: 20px; margin-bottom: 20px;">
        <table class="table table-hover widefat " style="background-color: #fff; border: 1px solid #ccc">
            <thead>
                <tr>
                    <th style="width: 11%">ID</th>
                    <th style="width: 13%">Name</th>
                    <th style="width: 13%">Template</th>
                    <th style="width: 50%">Shortcode</th>
                    <th style="width: 13%">Edit Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;
                if (!empty($_POST['delete']) && is_numeric($_POST['id'])) {
                    $nonce = $_REQUEST['_wpnonce'];
                    if (!wp_verify_nonce($nonce, 'accordionsdeletehomedata')) {
                        die('You do not have sufficient permissions to access this page.');
                    } else {
                        global $wpdb;
                        $id = (int) $_POST['id'];
                        $table_name = $wpdb->prefix . 'accordions_or_faqs_style';
                        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %d", $id));
                    }
                }
                $data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'accordions_or_faqs_style ORDER BY id DESC', ARRAY_A);
                foreach ($data as $value) {
                    $id = $value['id'];
                    echo ' <tr>';
                    echo ' <td>' . $id . '</td>';
                    echo '  <td >' . $value['name'] . '</td>';
                    echo ' <td >' . $value['style_name'] . '</td>';
                    echo '<td ><span>Shortcode <input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="[afoxi_ultimate_oxi id=&quot;' . $id . '&quot;]"></span>'
                    . '<span>Php Code <input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="&lt;?php echo do_shortcode(&#039;[afoxi_ultimate_oxi  id=&quot;' . $id . '&quot;]&#039;); ?&gt;"></span></td>';
                    echo '<td >
                                    <a href="' . admin_url("admin.php?page=accordions-or-faqs-oxilab-new&styleid=$id") . '"  class="btn btn-info" style="float:left; margin-right: 5px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <form method="post">' . wp_nonce_field("accordionsdeletehomedata") . '
                                            <input type="hidden" name="id" value="' . $id . '">
                                            <button class="btn btn-danger" style="float:left" type="submit" value="delete" name="delete"><i class="fa fa-trash" aria-hidden="true"></i></button>  
                                    </form>
                                   
                             </td>';
                    echo ' </tr>';
                }
                ?>

            </tbody>
        </table> 
    </div>
</div>