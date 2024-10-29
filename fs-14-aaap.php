<?php
/*
Plugin Name: FS-14 Automatic Amazon Affiliate Plugin
Plugin URI: http://www.wordpress-affiliate-plugins.com/
Description: A simple plugin to automatically insert Amazon affiliate links into your sidebar widget according to your specific articles.
Version: 1.1
Author: FS 14
Author URI: http://www.wordpress-affiliate-plugins.com/
Licence: GPLv2 or later
*/



define('LIST_AMAZON_FILE', __FILE__);
define('LIST_AMAZON_PATH', plugin_dir_path(__FILE__));

require LIST_AMAZON_PATH . 'lib/AmazonECS.class.php';
require LIST_AMAZON_PATH . 'includes/AmazonSettings.php';
require LIST_AMAZON_PATH . 'includes/AmazonSidebar.php';
require LIST_AMAZON_PATH . 'includes/AmazonShortcode.php';
require LIST_AMAZON_PATH . 'includes/AmazonBestList.php';

new amazonSettings();
new amazonShortcode();
new amazonBestseller();

$options = get_option('amazonlist');
if (strcmp($options['csslayout'],'bootstrap') == 0) 
	wp_enqueue_style( 'fs14', plugins_url('css/bootstrap.css', __FILE__));
else if (strcmp($options['csslayout'],'plain') == 0) 
	wp_enqueue_style( 'fs14', plugins_url('css/plain.css', __FILE__));
else if (strcmp($options['csslayout'],'nolayout') == 0) 
	wp_enqueue_style( 'fs14', plugins_url('css/nolayout.css', __FILE__));
else if (strcmp($options['csslayout'],'fs14') == 0) 
    wp_enqueue_style( 'fs14', plugins_url('css/fs14.css', __FILE__));
                               




register_activation_hook (LIST_AMAZON_PATH, 'fs14_activated');

add_action( 'widgets_init', create_function('', 'return register_widget("HD_Amazon_Sidebar");'));   


//===========================================================================
function fs14_activated ()
{

	$data = array(
		'country' => 'DE',
        'AWS_API_KEY' => '',
        'AWS_API_SECRET_KEY' => '',
        'BUY_BUTTON' => 'Buy at Amazon',
        'AWS_ASSOCIATE_TAG' => '',
        'csslayout' => 'fs14'
	);

    update_option($this->option_amazon, $this->data);
}
//===========================================================================
