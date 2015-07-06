<?php

/*
 * Plugin Name: Our Team
 * Plugin URI: http://pearlcore.com/downloads/our-team/
 * Description: Display your team members in a very attractive way as a widget or page with a shortcode
 * Version: 1.0
 * Author: Pearlcore
 * Author URI: http://pearlcore.com
 * Requires at least: 4.0
 * Tested up to: 4.2
 */


// Exit if accessed directly
if (!defined('ABSPATH')) {
    die;
}
if (!defined('PC_TEAM_PATH'))
    define('PC_TEAM_PATH', plugin_dir_path(__FILE__));
if (!defined('PC_TEAM_URL'))
    define('PC_TEAM_URL', plugin_dir_url(__FILE__));


require_once ( PC_TEAM_PATH . 'inc/class/class-pearlcore-team.php' );


// activation and de-activation hooks
register_activation_hook(__FILE__, array('Pearlcore_Team_Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('Pearlcore_Team_Plugin', 'deactivate'));

Pearlcore_Team_Plugin::instance();



