<?php
/**
 * Plugin Name: TukuToi Gutenberg Layouts
 * Description: Build entire Layout Layouts with Gutenberg Blocks. Design the Header, Content, Sidebars, Footer and more, all in the WordPress admin. 
 * Plugin URI: https://www.tukutoi.com
 * Author: TukuToi
 * Author URI: https://www.tukutoi.com
 * Version: 2.0.0
 * License: GPL2
 * Text Domain: tkt-gb-layouts
 * Domain Path: domain/path
 */

//Security check
if ( ! defined( 'WPINC' ) ) {
	die;
} 

define('POST_TYPE', 'tkt_layout');
define('TKT_LAYOUTS_MAIN_DIR', dirname(__FILE__).'/');
define('TKT_LAYOUTS_MAIN_URL', plugin_dir_url( __FILE__ ));

//If no other TukuToi Plugin is active
if (!defined('TKT_COMMON_LOADED'))
	require_once(TKT_LAYOUTS_MAIN_DIR.'tkt-common/tkt_common.php');

require_once TKT_LAYOUTS_MAIN_DIR.'application/admin/tkt_register_layout.php';
require_once TKT_LAYOUTS_MAIN_DIR.'application/admin/tkt_gb_layouts_menus.php';
require_once TKT_LAYOUTS_MAIN_DIR.'application/blocks/tkt_blocks.php';

require_once TKT_LAYOUTS_MAIN_DIR.'application/frontend/tkt_render_layouts.php';

add_action( 'enqueue_block_editor_assets', 'tkt_enqueue_block_editor_assets');

add_action( 'init', 'tkt_blocks_layout', 0 );
add_action( 'admin_menu' , 'tkt_add_reusable_blocks_menu' );
add_action( 'admin_menu' , 'tkt_add_layouts_menu' );

add_filter('manage_tkt_layout_posts_columns','tkt_layouts_filter_columns');
add_action( 'manage_posts_custom_column','tkt_custom_columns_content', 10, 2 );

add_action( 'init', 'tkt_register_meta_fields'); 
add_filter( 'rest_tkt_layout_query', 'tkt_rest_query', 10, 2);

add_action( 'admin_action_tkt_duplicate_layouts_as_draft', 'tkt_duplicate_layouts_as_draft' );
add_filter( 'post_row_actions', 'tkt_duplicate_layouts_link', 10, 2 );

//include TukuToi Update mechanism
include_once(TKT_LAYOUTS_MAIN_DIR.'update.php');