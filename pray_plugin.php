<?php
/**
 * Plugin Name: Pray Plugin
 * Plugin URI: http://www.mywebsite.com/my-first-plugin
 * Description: A Plugin to send us your pray request.
 * Version: 1.0
 * Author: Nikita Lötkemann
 * Author URI: http://www.nloetkemann.de
 */
include 'content.php';
include "settings.php";
defined('ABSPATH') or die('No script kiddies please!');

add_action('the_content', 'showPlugin');
add_action('wp_enqueue_scripts', 'pluginStyles');
add_action('admin_menu', 'myplugin_register_settings');

function pluginStyles()
{
    wp_register_style('styles', plugins_url('prayer-styles.css', __FILE__));
    wp_enqueue_style('styles');
}






function myplugin_register_settings()
{
    add_menu_page('Pray Plugin Settings', 'Pray Plugin', 'manage_options', 'pray-plugin', 'getSettings');
}



