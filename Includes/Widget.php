<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

/**
 * Description of Widget
 *
 * @author biplo
 */
class Widget extends \WP_Widget {

    function __construct() {
        parent::__construct(
                'oxi_ultimate_accordions_widget', __('Accordions', 'oxi_ultimate_accordions_widget_widget'), array('description' => __('Accordions - Multiple Accordions or FAQs Builders', 'oxi_ultimate_accordions_widget_widget'),)
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
            $title = __('1', 'oxi_ultimate_accordions_widget_widget');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Style ID:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}