<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Widget
 *
 * author @biplob018
 */
class Widget extends \WP_Widget {

    function __construct() {
        parent::__construct(
                'oxi_ultimate_accordions_widget', esc_html__('Accordions', 'oxi_ultimate_accordions_widget_widget'), array('description' => esc_html__('Accordions - Multiple Accordions or FAQs Builders', 'oxi_ultimate_accordions_widget_widget'),)
        );
    }

    public function register_accordions_widget() {
        register_widget($this);
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        $CLASS = '\OXI_ACCORDIONS_PLUGINS\Includes\Shortcode';
        if (class_exists($CLASS)):
            new $CLASS($title, 'user');
        endif;
        echo $args['after_widget'];
    }

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = esc_html__('1', 'oxi_ultimate_accordions_widget_widget');
        }
        ?>
        <p>
            <label for="<?php echo esc_html($this->get_field_id('title')); ?>"><?php esc_html__('Style ID:'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }

}
