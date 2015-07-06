<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Out Team Widget
 *
 * Displays team widget
 *
 * @author   Pearlcore
 * @category Widgets
 * @package  Pc_Our_Team/Inc
 * @version  1.0
 * @extends  WC_Widget
 */
class pearlcore_team_widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        $this->widget_cssclass = 'woocommerce pearlcore_team_widget';
        $this->widget_description = __("Use this widget to display the Our Team anywhere on the site.", 'pearlcore_team_widget_domain');
        $this->widget_id = 'pearlcore_team_widget';
        $this->widget_name = __('Pearlcore Our Team', 'pearlcore_team_widget_domain');

        $widget_ops = array(
            'classname' => $this->widget_cssclass,
            'description' => $this->widget_description
        );

        $this->WP_Widget($this->widget_id, $this->widget_name, $widget_ops);
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title)):
            echo $args['before_title'] . $title . $args['after_title'];
        endif;
        // This is where you run the code and display the output
        include PC_TEAM_PATH . 'inc/template/widget.php';
    }

    // Widget Backend
    public function form($instance) {
        if (isset($instance['title'])) :
            $title = $instance['title'];
        else :
            $title = __('Meet Our Team', 'pearlcore_team_widget_domain');
        endif;
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo _e($title); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}
