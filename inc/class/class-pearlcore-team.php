<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    die;
}

function pc_team_update_order() {
    $post_id = $_POST['id'];
    $pc_member_order = $_POST['pc_member_order'];
    update_post_meta($post_id, 'pc_member_order', $pc_member_order);
    exit();
}

add_action('wp_ajax_pc_team_update_order', 'pc_team_update_order');
add_action('wp_ajax_pc_team_update_order', 'pc_team_update_order');

/**
 * The Core functionality of the plugin.
 *
 * @class 		Pearlcore_Team_Plugin
 * @version		1.0
 * @package		Pc_Our_Team/inc/
 * @category            Class
 * @author 		Pearlcore
 */
if (!class_exists('Pearlcore_Team_Plugin')) :

    class Pearlcore_Team_Plugin {

        /**
         * @var string
         */
        const VERSION = '1.0';

        /**
         * @var out-team The single instance of the class
         * @since 1.0
         */
        private static $instance = null;
        private $options;

        /**
         * Main our-team Instance
         *
         * Ensures only one instance of our-team is loaded or can be loaded.
         *
         * @since 1.0
         * @static
         * @return void
         */
        public static function instance() {
            if (!self::$instance) :
                self::$instance = new self;
                self::$instance->get_options();
                self::$instance->add_hooks();
            endif;
        }

        /**
         * our-team Constructor.
         */
        public function __construct() {
            $this->pc_includes();
        }

        /**
         * Include required core files used in admin and on the frontend.
         */
        public function pc_includes() {
            include PC_TEAM_PATH . 'inc/class/class-pearlcore-widget.php';
        }

        /**
         * Plugin Activation function 
         */
        public static function activate() {

            $options = array(
                'template' => 'grid',
                'social' => 'yes',
                'social_style' => 'square',
                'single_social' => 'yes',
                'single_skills' => 'yes',
                'name' => 'yes',
                'title' => 'yes',
                'profile_link' => 'yes',
                'member_count' => -1,
                'text_color' => '1F7DCF',
                'honeycomb_color' => '1F7DCF',
                'columns' => '3',
                'margin' => 5,
                'height' => 170,
                'single_template' => 'standard',
                'redirect' => true,
                'single_image_size' => 'small',
                'single_image_style' => 'square',
            );

            if (!get_option('pearlcore_team_options')) {
                add_option('pearlcore_team_options', $options);
                $options['redirect'] = true;
                update_option('pearlcore_team_options', $options);
            }
        }

        public static function deactivate() {
            
        }

        /**
         * Hook into actions and filters
         * @since  1.0
         */
        private function add_hooks() {
            add_action('init', array($this, 'team_members'));
            add_action('init', array($this, 'team_member_positions'), 0);
            add_action('admin_init', array($this, 'pearlcore_team_activation_redirect'));
            add_action('admin_menu', array($this, 'pearlcore_team_menu'));
            add_action('admin_enqueue_scripts', array($this, 'pearlcore_team_load_admin_styles_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'pearlcore_team_load_styles_scripts'));
            add_shortcode('our-team', array($this, 'set_our_team'));
            add_action('add_meta_boxes', array($this, 'pearlcore_team_member_info_box'));
            add_action('save_post', array($this, 'team_member_box_save'));
            add_action('widgets_init', array($this, 'pc_load_widget'));
            add_action('wp_ajax_pearlcore_team_update_pm', array($this, 'pearlcore_team_update_order'));
            add_action('wp_head', array($this, 'pc_custom_styles'));
            add_filter('the_content', array($this, 'pearlcore_set_single_content'));
            add_filter('single_template', array($this, 'pearlcore_team_get_single_template'));

            add_filter('manage_team_member_posts_columns', array($this, 'ST4_columns_head'));
            add_action('manage_team_member_posts_custom_column', array($this, 'ST4_columns_content'), 10, 2);
        }

        // GET FEATURED IMAGE
        public function ST4_get_featured_image($post_ID) {
            $post_thumbnail_id = get_post_thumbnail_id($post_ID);
            if ($post_thumbnail_id) :
                $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
                return $post_thumbnail_img[0];
            endif;
        }

        // ADD NEW COLUMN
        public function ST4_columns_head($defaults) {
            $defaults['featured_image'] = 'Image';
            $defaults['job_title'] = 'Job Title';
            $defaults['member_group'] = 'Group';
            return $defaults;
        }

        // SHOW THE FEATURED IMAGE
        public function ST4_columns_content($column_name, $post_ID) {
            if ($column_name == 'featured_image') :
                $post_featured_image = $this->ST4_get_featured_image($post_ID);
                if (!$post_featured_image) :
                    $post_featured_image = PC_TEAM_URL . 'inc/img/noprofile.jpg';
                endif;
                echo '<img width="80" src="' . $post_featured_image . '" />';

            endif;
            if ($column_name == 'job_title'):
                echo get_post_meta($post_ID, 'team_member_title', true);
            endif;
            if ($column_name == 'member_group'):
                $pc_member_positions = $this->get_the_category_custompost($post_ID, 'team_member_position');
                foreach ($pc_member_positions as $key => $pc_member_position):
                    echo $pc_member_position->name;
                    if (end($pc_member_positions) !== $pc_member_position) :
                        echo ', ';
                    endif;
                endforeach;
            endif;
        }

        /**
         * Get Category
         * 
         * @param int $id
         * @param string $tcat
         * @return array
         */
        public function get_the_category_custompost($id = false, $tcat = 'category') {
            $categories = get_the_terms($id, $tcat);
            if (!$categories):
                $categories = array();
            endif;
            $categories = array_values($categories);

            foreach (array_keys($categories) as $key) :
                _make_cat_compat($categories[$key]);
            endforeach;

            return apply_filters('get_the_categories', $categories);
        }

        private function get_options() {
            if (get_option('pearlcore_team_options')) :
                $this->options = get_option('pearlcore_team_options');
            endif;
        }

        /**
         * @todo check if redirect option is set and redirect
         */
        public function pearlcore_team_activation_redirect() {
            if ($this->options['redirect']) :
                $old_val = $this->options;
                $old_val['redirect'] = false;
                update_option('pearlcore_team_options', $old_val);
                wp_safe_redirect(admin_url('edit.php?post_type=team_member&page=pearlcore_team_settings'));
            endif;
        }

        /**
         * out-team Admin Menu
         * 
         * @since     1.0
         */
        public function pearlcore_team_menu() {

            add_submenu_page('edit.php?post_type=team_member', 'Settings', 'Settings', 'administrator', 'pearlcore_team_settings', array($this, 'pearlcore_team_settings'));
            add_submenu_page('edit.php?post_type=team_member', 'Re-Order Members', 'Re-Order Members', 'administrator', 'pearlcore_team_reorder', array($this, 'pearlcore_team_reorder'));
        }

        /**
         * Load Re-order Template
         */
        public function pearlcore_team_reorder() {
            include_once PC_TEAM_PATH . 'admin/reorder.php';
        }

        /**
         * Load Setting Teemplate 
         */
        public function pearlcore_team_settings() {
            if (isset($_REQUEST['pc_our_team_save']) && $_REQUEST['pc_our_team_save'] == 'Update') :
                update_option('pearlcore_team_options', $_REQUEST['pearlcore_team_options']);
            endif;
            include_once PC_TEAM_PATH . 'admin/options.php';
        }

        /**
         * Register Admin Side Script and Style
         * 
         * @param string $hook
         */
        public function pearlcore_team_load_admin_styles_scripts($hook) {

            wp_enqueue_style('pearlcore_team_admin_style', PC_TEAM_URL . 'inc/style/pc_our_team_admin.css');
            wp_enqueue_script('pearlcore_team_color_script', PC_TEAM_URL . 'inc/script/jscolor/jscolor.js', array('jquery'));
            wp_enqueue_script('pearlcore_team_script', PC_TEAM_URL . 'inc/script/pc_our_team_admin.js', array('jquery'));
        }

        /**
         * Register Publice Side Script and Style
         */
        function pearlcore_team_load_styles_scripts() {

            // plugin main style
            wp_enqueue_style('pearlcore_team_default_style', PC_TEAM_URL . 'inc/style/pc_our_team.css', false, '1.0');

            wp_enqueue_script('pearlcore_team_default_script_hc', PC_TEAM_URL . 'inc/script/hc.js', array('jquery'), '1.0', true);
            wp_enqueue_script('pearlcore_team_default_script_carousel', PC_TEAM_URL . 'inc/script/carousel.js', array('jquery'), '1.0', true);
            wp_enqueue_script('pearlcore_team_default_script_jquery.roundabout.min', PC_TEAM_URL . 'inc/script/jquery.roundabout.min.js', array('jquery'), '1.0', true);
            wp_enqueue_script('pearlcore_team_default_script_wookmark', PC_TEAM_URL . 'inc/script/wookmark.js', array('jquery'), '1.0', true);

            // plugin main script
            wp_enqueue_script('pearlcore_team_default_script', PC_TEAM_URL . 'inc/script/pc_our_team.js', array('jquery'), '1.0', true);
        }

        /**
         * Shortcode function to Dieplay Our Team
         * 
         * @param array $atts
         * @return content
         */
        function set_our_team($atts) {
            $wb_user_attr = extract(shortcode_atts(array(
                'group' => '',
                'template' => '',
                            ), $atts));
            if (isset($template) && !empty($template)):
                $template = $template;
            else:
                if (isset($this->options['template']) && !empty($this->options['template'])):
                    $template = $this->options['template'];
                else:
                    $template = 'grid';
                endif;
            endif;
            ob_start();
            if ($template == 'grid' || $template == 'grid_circles' || $template == 'grid_circles2' || $template == 'grid_image'):
                include PC_TEAM_PATH . 'inc/template/grid.php';
            else:
                include PC_TEAM_PATH . 'inc/template/' . $template . '.php';
            endif;

            $output = ob_get_clean();
            return $output;
        }

        /**
         * Register core post types.
         */
        function team_members() {
            $labels = array(
                'name' => _x('Team', 'post type general name'),
                'singular_name' => _x('Team Member', 'post type singular name'),
                'add_new' => _x('Add New', 'book'),
                'add_new_item' => __('Add New Member'),
                'edit_item' => __('Edit Member'),
                'new_item' => __('New Team Member'),
                'all_items' => __('All Team Members'),
                'view_item' => __('View Team Member'),
                'search_items' => __('Search Team Members'),
                'not_found' => __('No member found'),
                'not_found_in_trash' => __('No member found in the Trash'),
                'parent_item_colon' => '',
                'menu_name' => 'Our Team'
            );
            $args = array(
                'labels' => $labels,
                'description' => 'Holds our team members specific data',
                'public' => true,
                'menu_icon' => PC_TEAM_URL . 'inc/img/icon.png',
                'supports' => array('title', 'editor', 'thumbnail', 'post-formats'),
                'has_archive' => false,
            );
            register_post_type('team_member', $args);
            flush_rewrite_rules();
        }

        /**
         * Register core taxonomies.
         */
        public function team_member_positions() {
            $labels = array(
                'name' => _x('Groups', 'taxonomy general name'),
                'singular_name' => _x('Group', 'taxonomy singular name'),
                'search_items' => __('Search Groups'),
                'all_items' => __('All Groups'),
                'parent_item' => __('Parent Group'),
                'parent_item_colon' => __('Parent Group:'),
                'edit_item' => __('Edit Group'),
                'update_item' => __('Update Group'),
                'add_new_item' => __('Add New Group'),
                'new_item_name' => __('New Group'),
                'menu_name' => __('Groups'),
            );
            $args = array(
                'labels' => $labels,
                'hierarchical' => true,
            );
            register_taxonomy('team_member_position', 'team_member', $args);
        }

        /**
         * Register Post Meta
         */
        public function pearlcore_team_member_info_box() {

            add_meta_box(
                    'pearlcore_team_member_info_box', __('Additional Information', 'myplugin_textdomain'), array($this, 'pearlcore_team_member_info_box_content'), 'team_member', 'normal', 'high'
            );
        }

        /**
         * Post Meta Data Fields
         * 
         * @param array $post
         */
        public function pearlcore_team_member_info_box_content($post) {
            //nonce
            wp_nonce_field(plugin_basename(__FILE__), 'pearlcore_team_member_info_box_content_nonce');

            /**
             * Social
             */
            echo '<p><em>Fields that are left blank, will simply not display any output</em></p>';

            echo '<div class="pc_options_table">';

            echo '<table>';

            echo '<tr><td><lablel for="team_member_title">Job Title</lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_title', true) . '" id="team_member_title" name="team_member_title" placeholder="Enter Job Title"/></td></tr>';

            echo '<tr><td><lablel for="team_member_email"><img src="' . PC_TEAM_URL . 'inc/img/email.png" width="20px"/></lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_email', true) . '" id="team_member_email" name="team_member_email" placeholder="Enter Email Address"/></td></tr>';

            echo '<tr><td><lablel for="team_member_facebook"><img src="' . PC_TEAM_URL . 'inc/img/fb.png" width="20px"/></lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_facebook', true) . '" id="team_member_facebook" name="team_member_facebook" placeholder="Enter Facebook URL"/></td></tr>';

            echo '<tr><td><label for="team_member_twitter"><img src="' . PC_TEAM_URL . 'inc/img/twitter.png" width="20px"/></lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_twitter', true) . '" id="team_member_twitter" name="team_member_twitter" placeholder="Enter Twitter URL"/></td></tr>';

            echo '<tr><td><lablel for="team_member_linkedin"><img src="' . PC_TEAM_URL . 'inc/img/linkedin.png" width="20px"/></lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_linkedin', true) . '" id="team_member_linkedin" name="team_member_linkedin" placeholder="Enter Linkedin URL"/></td></tr>';

            echo '<tr><td><lablel for="team_member_gplus"><img src="' . PC_TEAM_URL . 'inc/img/google.png" width="20px"/></lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_gplus', true) . '" id="team_member_gplus" name="team_member_gplus" placeholder="Enter Google Plus URL"/></td></tr>';

            echo '</table>';
            echo '</div>';

            /**
             * Skills
             */
            echo '<div class="pc_options_table">'
            . '<h2>Skills</h2>';
            echo '<table>';

            echo '<tr><td><lablel for="team_member_skill1">1</lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_skill1', true) . '" id="team_member_skill1" name="team_member_skill1" placeholder="Skill Name"/>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_skill_value1', true) . '" id="team_member_skill_value1" name="team_member_skill_value1" placeholder="Skill Value"/></td></tr>';

            echo '<tr><td><lablel for="team_member_skill2">2</lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_skill2', true) . '" id="team_member_skill2" name="team_member_skill2" placeholder="Skill Name"/>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_skill_value2', true) . '" id="team_member_skill_value2" name="team_member_skill_value2" placeholder="Skill Value"/></td></tr>';

            echo '<tr><td><lablel for="team_member_skill3">3</lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_skill3', true) . '" id="team_member_skill3" name="team_member_skill3" placeholder="Skill Name"/>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_skill_value3', true) . '" id="team_member_skill_value3" name="team_member_skill_value3" placeholder="Skill Value"/></td></tr>';

            echo '<tr><td><label for="team_member_skill4">4</lablel></td>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_skill4', true) . '" id="team_member_skill4" name="team_member_skill4" placeholder="Skill Name"/>';
            echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_skill_value4', true) . '" id="team_member_skill_value4" name="team_member_skill_value4" placeholder="Skill Value"/></td></tr>';

            echo '</table>';
            echo '</div>'
            . '<div class="clear"></div>';
        }

        /**
         * Save Out Team Post Meta Data
         * 
         * @param interger $post_id
         * @return void
         */
        public function team_member_box_save($post_id) {
            $slug = 'team_member';

            if (isset($_POST['post_type'])) :
                if ($slug != $_POST['post_type']) :
                    return;
                endif;
            endif;

            // get var values
            if (get_post_meta($post_id, 'pc_member_order', true) == '' || get_post_meta($post_id, 'pc_member_order', true) === FALSE):
                update_post_meta($post_id, 'pc_member_order', 0);
            endif;

            if (isset($_REQUEST['team_member_title'])) :
                $facebook_url = $_POST['team_member_title'];
                update_post_meta($post_id, 'team_member_title', $facebook_url);
            endif;

            if (isset($_REQUEST['team_member_email'])) :
                $facebook_url = $_POST['team_member_email'];
                update_post_meta($post_id, 'team_member_email', $facebook_url);
            endif;

            if (isset($_REQUEST['team_member_facebook'])) :
                $facebook_url = $_POST['team_member_facebook'];
                update_post_meta($post_id, 'team_member_facebook', $facebook_url);
            endif;

            if (isset($_REQUEST['team_member_twitter'])) :
                $twitter_url = $_POST['team_member_twitter'];
                update_post_meta($post_id, 'team_member_twitter', $twitter_url);
            endif;

            if (isset($_REQUEST['team_member_linkedin'])) :
                $linkedin_url = $_POST['team_member_linkedin'];
                update_post_meta($post_id, 'team_member_linkedin', $linkedin_url);
            endif;

            if (isset($_REQUEST['team_member_gplus'])) :
                $gplus_url = $_POST['team_member_gplus'];
                update_post_meta($post_id, 'team_member_gplus', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill1'])) :
                $gplus_url = $_POST['team_member_skill1'];
                update_post_meta($post_id, 'team_member_skill1', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill2'])) :
                $gplus_url = $_POST['team_member_skill2'];
                update_post_meta($post_id, 'team_member_skill2', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill3'])) :
                $gplus_url = $_POST['team_member_skill3'];
                update_post_meta($post_id, 'team_member_skill3', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill4'])) :
                $gplus_url = $_POST['team_member_skill4'];
                update_post_meta($post_id, 'team_member_skill4', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill5'])) :
                $gplus_url = $_POST['team_member_skill5'];
                update_post_meta($post_id, 'team_member_skill5', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill_value1'])) :
                $gplus_url = $_POST['team_member_skill_value1'];
                update_post_meta($post_id, 'team_member_skill_value1', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill_value2'])) :
                $gplus_url = $_POST['team_member_skill_value2'];
                update_post_meta($post_id, 'team_member_skill_value2', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill_value3'])) :
                $gplus_url = $_POST['team_member_skill_value3'];
                update_post_meta($post_id, 'team_member_skill_value3', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill_value4'])) :
                $gplus_url = $_POST['team_member_skill_value4'];
                update_post_meta($post_id, 'team_member_skill_value4', $gplus_url);
            endif;
            if (isset($_REQUEST['team_member_skill_value5'])) :
                $gplus_url = $_POST['team_member_skill_value5'];
                update_post_meta($post_id, 'team_member_skill_value5', $gplus_url);
            endif;
        }

        /**
         * Load Widget
         */
        public function pc_load_widget() {
            register_widget('pearlcore_team_widget');
        }

        /**
         * Updtae Team Order
         */
        public function pearlcore_team_update_order() {
            $post_id = $_POST['id'];
            $pc_member_order = $_POST['pc_member_order'];
            update_post_meta($post_id, 'pc_member_order', $pc_member_order);
        }

        /**
         * Cutom Style
         */
        public function pc_custom_styles() {
            ?>
            <style>
                #pc_our_team a,
                .pc_our_team_lightbox .name,
                .pc_our_team_lightbox .title,.out_team_title h4 a,.next_team .inner_icon i,.prev_team .inner_icon i,
                #pc_our_team_lightbox .pc_our_team_lightbox .skills-title,
                .pc_our_team_panel .pc-right-panel .pc-title,
                .pc_our_team_panel .pc-right-panel .skills-title{ 
                    color: #<?php echo $this->options['text_color']; ?>; 
                }
                .grid#pc_our_team .pc_team_member .pc_team_member_name,
                .grid#pc_our_team .pc_team_member .pc_team_member_jobtitle,
                .grid_circles#pc_our_team .pc_team_member .pc_team_member_jobtitle,
                .grid_circles#pc_our_team .pc_team_member .pc_team_member_name,
                #pc_our_team_lightbox .progress,.progress,
                .team-list .team-item .team-desc,
                .nav_team .next_team:before,
                .nav_team .prev_team:before,.out_team_title:before,.dv-member-name,.dv-member-zoom,
                .pc_our_team_panel .pc-right-panel .pc-name,.pc_skill_bar_title span{
                    background-color: #<?php echo $this->options['text_color']; ?>;
                }
                .stacked#pc_our_team .pc_team_member,.team-list .team-item,.next_team, .prev_team{ 
                    border-color: #<?php echo $this->options['text_color']; ?>;
                }
                .grid#pc_our_team .pc_team_member_inner{
                    height: <?php echo $this->options['height']; ?>px; 
                }
                .grid#pc_our_team .pc_team_member{
                    padding: <?php echo $this->options['margin']; ?>px;
                }

            </style>

            <div id="pc_our_team_lightbox" class="scrollbar-macosx">
                <div class="pc_our_team_lightbox permanent">
                    <div class="width25 left">
                        <div class="pc_image_wrapper">
                            <?php
                            if (isset($this->options['single_image_style']) && !empty($this->options['single_image_style'])):
                                $pc_image_style = $this->options['single_image_style'];
                            else:
                                $pc_image_style = 'square';
                            endif;
                            ?>
                            <img src="" class="image <?php echo $pc_image_style; ?>"/>
                        </div>
                        <h4 class="title"></h4>
                        <?php
                        if (isset($this->options['single_social']) && ($this->options['single_social'] == 'yes')):
                            $pc_social_hide = 'style="display:block;"';
                        else:
                            $pc_social_hide = 'style="display:none;"';
                        endif;
                        ?>
                        <div class="social " <?php echo $pc_social_hide; ?>></div>
                    </div>

                    <div class="left width75">
                        <h2 class="name"></h2>
                        <div class="pc-content"></div>
                        <?php
                        if (isset($this->options['single_skills']) && ($this->options['single_skills'] == 'yes')):
                            $pc_skill_hide = 'style="display:block;"';
                        else:
                            $pc_skill_hide = 'style="display:none;"';
                        endif;
                        ?>
                        <h2 class="skills-title" <?php echo $pc_skill_hide; ?>>My Skills</h2>
                        <div class="skills" <?php echo $pc_skill_hide; ?>>

                        </div>
                    </div>
                    <div class="pc_our_team_loghtbox_close pc_close_button">
                        <span></span>
                    </div>
                </div>
            </div>
            <div id="pc_our_team_panel" class="scrollbar-macosx"></div>
            <div class="pc_our_team_panel permanent">
                <div class="pc-left-panel" <?php echo $pc_social_hide; ?>>
                    <div class="pc-social " <?php echo $pc_social_hide; ?>></div>
                </div>
                <div class="pc-right-panel">
            <!--                        <span class="pc_team_icon-close pc_close_button"></span>-->
                    <div class="pc_our_team_loghtbox_close pc_close_button">
                        <span></span>
                    </div>
                    <h2 class="pc-name"></h2>
                    <img src="" class="pc-image <?php echo $pc_image_style; ?>">            
                    <h3 class="pc-title"></h3>
                    <div class="pc_personal_quote"></div>
                    <div class="pc-content"></div>
                    <div class="">
                        <h3 class="skills-title" <?php echo $pc_skill_hide; ?>>Skills</h3>
                        <div class="pc-skills" <?php echo $pc_skill_hide; ?>></div>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * Single Post Content
         * 
         * @global array $post
         * @param string $content
         * @return content
         */
        public function pearlcore_set_single_content($content) {
            global $post;
            $pc_job_title = '';

            if (is_single()) :
                if ($post->post_type == 'team_member') :
                    if ('yes' == $this->options['title']) :
                        $pc_job_title .= '<div itemprop="jobtitle" class="pc_team_member_jobtitle">';
                        $pc_job_title .= '<h4>' . get_post_meta(get_the_ID(), 'team_member_title', true) . '</h4>';
                        $pc_job_title .= '</div>';
                    endif;
                    if ($this->options['single_social'] == 'yes'):


                        $content .= '<div class="clear"></div>'
                                . '<div class="pearlcore_team_single_icons icons icons">'
                                . '<h2 class="skills-title">Connect with me on Social Network</h2>';
                        $content .= $this->pearlcore_get_social_content(get_the_ID());
                        $content .= '</div>';

                    endif;

                    if ($this->options['single_skills'] == 'yes'):
                        $content .= '<div class="pc_team_skills">';
                        $content .= '<h2 class="skills-title">My Skills</h2>';
                        $content .= $this->pc_get_skills_html(get_the_ID());
                        $content .= '</div>';
                    endif;
                    $pc_single_member = '<div id="pc_our_team">';
                    $pc_single_member .= '<div class="pc_team_member">';
                    $pc_single_member .= $pc_job_title . $content;
                    $pc_single_member .= '</div>';
                    $pc_single_member .= '</div>';
                endif;

            else:
                $pc_single_member = $content;
            endif;


            return $pc_single_member;
        }

        /**
         * Social Link Function
         * 
         * @param string $facebook
         * @param string $twitter
         * @param string $linkedin
         * @param string $gplus
         * @param string $email
         */
        public function pc_get_social($pc_member_id) {
            $facebook = get_post_meta($pc_member_id, 'team_member_facebook', true);
            $twitter = get_post_meta($pc_member_id, 'team_member_twitter', true);
            $linkedin = get_post_meta($pc_member_id, 'team_member_linkedin', true);
            $gplus = get_post_meta($pc_member_id, 'team_member_gplus', true);
            $email = get_post_meta($pc_member_id, 'team_member_email', true);
            $content = '';
            if ($facebook != ''):
                $content .= '<a href="' . $facebook . '" target="_blank">';
                if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                    $content .= '<span class="pc_team_icon-facebook"></span>';
                else:
                    $content .= '<img src="' . PC_TEAM_URL . 'inc/img/fb.png" class="pc-social"/>';
                endif;
                $content .= '</a>';

            endif;
            if ($twitter != ''):
                $content .= '<a href="' . $twitter . '" target="_blank">';
                if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                    $content .= '<span class="pc_team_icon-twitter"></span>';
                else:
                    $content .= '<img src="' . PC_TEAM_URL . 'inc/img/twitter.png" class="pc-social"/>';
                endif;
                $content .= '</a>';
            endif;
            if ($linkedin != ''):
                $content .= '<a href="' . $linkedin . '" target="_blank">';
                if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                    $content .= '<span class="pc_team_icon-linkedin"></span>';
                else:
                    $content .= '<img src="' . PC_TEAM_URL . 'inc/img/linkedin.png" class="pc-social"/>';
                endif;
                $content .= '</a>';
            endif;
            if ($gplus != ''):
                $content .= '<a href="' . $gplus . '" target="_blank">';
                if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                    $content .= '<span class="pc_team_icon-google-plus"></span>';
                else:
                    $content .= '<img src="' . PC_TEAM_URL . 'inc/img/google.png" class="pc-social"/>';
                endif;
                $content .= '</a>';
            endif;
            if ($email != ''):
                $content .= '<a href="mailto:' . $gplus . '">';
                if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                    $content .= '<span class="pc_team_icon-envelope-o"></span>';
                else:
                    $content .= '<img src="' . PC_TEAM_URL . 'inc/img/email.png" class="pc-social"/>';
                endif;
                $content .= '</a>';
            endif;
            echo $content;
        }

        /**
         * Function to get Social Links
         * 
         * @param string $facebook
         * @param string $twitter
         * @param string $linkedin
         * @param string $gplus
         * @param string $email
         * @return string
         */
        public function pearlcore_get_social_content($pc_member_id) {
            $facebook = get_post_meta($pc_member_id, 'team_member_facebook', true);
            $twitter = get_post_meta($pc_member_id, 'team_member_twitter', true);
            $linkedin = get_post_meta($pc_member_id, 'team_member_linkedin', true);
            $gplus = get_post_meta($pc_member_id, 'team_member_gplus', true);
            $email = get_post_meta($pc_member_id, 'team_member_email', true);
            $content = '';

            if ('yes' == $this->options['social']) :
                if ($facebook != ''):
                    $content .= '<a href="' . $facebook . '" target="_blank">';
                    if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                        $content .= '<span class="pc_team_icon-facebook"></span>';
                    else:
                        $content .= '<img src="' . PC_TEAM_URL . 'inc/img/fb.png" class="pc-social"/>';
                    endif;
                    $content .= '</a>';

                endif;
                if ($twitter != ''):
                    $content .= '<a href="' . $twitter . '" target="_blank">';
                    if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                        $content .= '<span class="pc_team_icon-twitter"></span>';
                    else:
                        $content .= '<img src="' . PC_TEAM_URL . 'inc/img/twitter.png" class="pc-social"/>';
                    endif;
                    $content .= '</a>';
                endif;
                if ($linkedin != ''):
                    $content .= '<a href="' . $linkedin . '" target="_blank">';
                    if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                        $content .= '<span class="pc_team_icon-linkedin"></span>';
                    else:
                        $content .= '<img src="' . PC_TEAM_URL . 'inc/img/linkedin.png" class="pc-social"/>';
                    endif;
                    $content .= '</a>';
                endif;
                if ($gplus != ''):
                    $content .= '<a href="' . $gplus . '" target="_blank">';
                    if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                        $content .= '<span class="pc_team_icon-google-plus"></span>';
                    else:
                        $content .= '<img src="' . PC_TEAM_URL . 'inc/img/google.png" class="pc-social"/>';
                    endif;
                    $content .= '</a>';
                endif;
                if ($email != ''):
                    $content .= '<a href="mailto:' . $gplus . '">';
                    if (isset($this->options['social_style']) && $this->options['social_style'] == 'square'):
                        $content .= '<span class="pc_team_icon-envelope-o"></span>';
                    else:
                        $content .= '<img src="' . PC_TEAM_URL . 'inc/img/email.png" class="pc-social"/>';
                    endif;
                    $content .= '</a>';
                endif;
            endif;
            return $content;
        }

        /**
         * Function to get single Social Link
         * 
         * @param string $social
         */
        public function get_single_social($social) {
            if ('yes' == $this->options['social']) :
                if ($social != ''):
                    echo '<li><a href="' . $social . '"><img src="' . PC_TEAM_URL . 'inc/img/fb.png" class="pc-social"/></a></li>';
                endif;
            endif;
        }

        /**
         * Arrugment to get Team Members
         * 
         * @param type $group
         * @return array
         */
        public function pc_get_args($group) {
            $args = array(
                'post_type' => 'team_member',
                'meta_key' => 'pc_member_order',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'team_member_position' => $group,
                'posts_per_page' => $this->options['member_count'],
            );
            return $args;
        }

        public function pearlcore_team_get_single_template($single_template) {

            global $post;
            return $single_template;
        }

        public function pc_get_skills_html($member_id) {
            $member_skills = '';
            if (get_post_meta($member_id, 'team_member_skill1', true) && get_post_meta($member_id, 'team_member_skill_value1', true)) :
                $member_skills .= '<div class="pc_skill_bar" data-percent="' . get_post_meta($member_id, 'team_member_skill_value1', true) . '%">';
                $member_skills .= '<div class="pc_skill_bar_title">';
                $member_skills .= '<span>' . get_post_meta($member_id, 'team_member_skill1', true) . '</span>';
                $member_skills .= '</div>';
                $member_skills .= '<div class="pc_skill_bar_bar" style="width: ' . get_post_meta($member_id, 'team_member_skill_value1', true) . '%;"></div>';
                $member_skills .= '<div class="pc_skill_bar_bar_percent">' . get_post_meta($member_id, 'team_member_skill_value1', true) . '%</div>';
                $member_skills .= '</div>';
            endif;

            if (get_post_meta($member_id, 'team_member_skill2', true) && get_post_meta($member_id, 'team_member_skill_value2', true)) :
                $member_skills .= '<div class="pc_skill_bar" data-percent="' . get_post_meta($member_id, 'team_member_skill_value2', true) . '%">';
                $member_skills .= '<div class="pc_skill_bar_title">';
                $member_skills .= '<span>' . get_post_meta($member_id, 'team_member_skill2', true) . '</span>';
                $member_skills .= '</div>';
                $member_skills .= '<div class="pc_skill_bar_bar" style="width: ' . get_post_meta($member_id, 'team_member_skill_value2', true) . '%;"></div>';
                $member_skills .= '<div class="pc_skill_bar_bar_percent">' . get_post_meta($member_id, 'team_member_skill_value2', true) . '%</div>';
                $member_skills .= '</div>';
            endif;

            if (get_post_meta($member_id, 'team_member_skill3', true) && get_post_meta($member_id, 'team_member_skill_value3', true)) :
                $member_skills .= '<div class="pc_skill_bar" data-percent="' . get_post_meta($member_id, 'team_member_skill_value3', true) . '%">';
                $member_skills .= '<div class="pc_skill_bar_title">';
                $member_skills .= '<span>' . get_post_meta($member_id, 'team_member_skill3', true) . '</span>';
                $member_skills .= '</div>';
                $member_skills .= '<div class="pc_skill_bar_bar" style="width: ' . get_post_meta($member_id, 'team_member_skill_value3', true) . '%;"></div>';
                $member_skills .= '<div class="pc_skill_bar_bar_percent">' . get_post_meta($member_id, 'team_member_skill_value3', true) . '%</div>';
                $member_skills .= '</div>';
            endif;

            if (get_post_meta($member_id, 'team_member_skill4', true) && get_post_meta($member_id, 'team_member_skill_value4', true)) :
                $member_skills .= '<div class="pc_skill_bar" data-percent="' . get_post_meta($member_id, 'team_member_skill_value4', true) . '%">';
                $member_skills .= '<div class="pc_skill_bar_title">';
                $member_skills .= '<span>' . get_post_meta($member_id, 'team_member_skill4', true) . '</span>';
                $member_skills .= '</div>';
                $member_skills .= '<div class="pc_skill_bar_bar" style="width: ' . get_post_meta($member_id, 'team_member_skill_value4', true) . '%;"></div>';
                $member_skills .= '<div class="pc_skill_bar_bar_percent">' . get_post_meta($member_id, 'team_member_skill_value4', true) . '%</div>';
                $member_skills .= '</div>';
            endif;

            return $member_skills;
        }

    }

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    endif;
