<?php
/**
 * Plugin Name: TukuToi Blocks Templates
 * Description: Build entire Template Layouts with Gutenberg Blocks. Design the Header, Content, Sidebars, Footer and more, all in the WordPress backend. 
 * Plugin URI: https://tukutoi.com
 * Author: TukuToi
 * Author URI: https://tukutoi.com
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: tkt-tw-blocks-templates
 * Domain Path: domain/path
 */

//Do not access directly
if (!defined('ABSPATH')) exit;

add_action( 'admin_menu' , 'tkt_add_reusable_blocks_menu' );
function tkt_add_reusable_blocks_menu(){
    $page = add_menu_page( '', 'Reusable Blocks', 'manage_options', 'edit.php?post_type=wp_block', '','dashicons-controls-repeat');
}

define('POST_TYPE', 'tkt_template');

function tkt_blocks_template() {

    $labels = array(
        'name'                  => _x( 'Templates', 'Post Type General Name', 'tkt-blocks-template' ),
        'singular_name'         => _x( 'Template', 'Post Type Singular Name', 'tkt-blocks-template' ),
        'menu_name'             => __( 'Templates', 'tkt-blocks-template' ),
        'name_admin_bar'        => __( 'Templates', 'tkt-blocks-template' ),
        'archives'              => __( '', 'tkt-blocks-template' ),
        'attributes'            => __( '', 'tkt-blocks-template' ),
        'parent_item_colon'     => __( '', 'tkt-blocks-template' ),
        'all_items'             => __( 'All Templates', 'tkt-blocks-template' ),
        'add_new_item'          => __( 'Create New Template', 'tkt-blocks-template' ),
        'add_new'               => __( 'Create New', 'tkt-blocks-template' ),
        'new_item'              => __( 'Create Template', 'tkt-blocks-template' ),
        'edit_item'             => __( 'Edit Template', 'tkt-blocks-template' ),
        'update_item'           => __( 'Update Template', 'tkt-blocks-template' ),
        'view_item'             => __( 'View Template', 'tkt-blocks-template' ),
        'view_items'            => __( 'View Templates', 'tkt-blocks-template' ),
        'search_items'          => __( 'Search Template', 'tkt-blocks-template' ),
        'not_found'             => __( 'Not found', 'tkt-blocks-template' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'tkt-blocks-template' ),
        'featured_image'        => __( '', 'tkt-blocks-template' ),
        'set_featured_image'    => __( '', 'tkt-blocks-template' ),
        'remove_featured_image' => __( '', 'tkt-blocks-template' ),
        'use_featured_image'    => __( '', 'tkt-blocks-template' ),
        'insert_into_item'      => __( '', 'tkt-blocks-template' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Template', 'tkt-blocks-template' ),
        'items_list'            => __( 'Templates list', 'tkt-blocks-template' ),
        'items_list_navigation' => __( 'Templates list navigation', 'tkt-blocks-template' ),
        'filter_items_list'     => __( 'Filter Templates list', 'tkt-blocks-template' ),
    );
    $rewrite = array(
        'slug'                  => 'template',
        'with_front'            => true,
        'pages'                 => false,
        'feeds'                 => false,
    );
    $capabilities = array(
        'edit_post'             => 'manage_options',
        'read_post'             => 'manage_options',
        'delete_post'           => 'manage_options',
        'edit_posts'            => 'manage_options',
        'edit_others_posts'     => 'manage_options',
        'publish_posts'         => 'manage_options',
        'read_private_posts'    => 'manage_options',
    );
    $args = array(
        'label'                 => __( 'Template', 'tkt-blocks-template' ),
        'description'           => __( 'Templates built with Blocks', 'tkt-blocks-template' ),
        'labels'                => $labels,
        'supports'              => array( 'editor', 'revisions', 'custom-fields', 'title' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-text',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'rewrite'               => $rewrite,
        'capabilities'          => $capabilities,
        'show_in_rest'          => true,
    );
    register_post_type( 'tkt_template', $args );

}
add_action( 'init', 'tkt_blocks_template', 0 );

function tkt_register_meta_fields(){

    //Define fields to register
    //Hide fields because of bug in unnhidden fields when custom field editor is open
    $meta_field_array   = array('_tkt_template_part' => 'string','_tkt_header_type' => 'string', '_tkt_header' => 'string', '_tkt_footer' => 'string', '_tkt_footer_type' => 'string'); 

    //register fields
    foreach ($meta_field_array as $field => $type) {
        register_meta( 'post', $field, array(
            'object_subtype' => POST_TYPE,
            'type'      => $type,
            'single'    => true,
            'show_in_rest'  => true,
            'auth_callback' => function(){
                return current_user_can('manage_options');
            }
        ) );
    }
}
add_action( 'init', 'tkt_register_meta_fields'); 

add_filter( 'rest_tkt_template_query', function( $args, $request ){
    if ( $meta_key = $request->get_param( 'metaKey' ) ) {
        $args['meta_key'] = $meta_key;
        $args['meta_value'] = $request->get_param( 'metaValue' );
    }
    return $args;
}, 10, 2 );

function tkt_enqueue_block_editor_assets() {
	
	if( get_current_screen()->post_type !== POST_TYPE OR !get_current_screen()->is_block_editor)
        return;
    //if anywher else thn intended abort
	wp_enqueue_script( 'tkt-templates-sidebar-script', plugin_dir_url( __FILE__ ) . 'sidebar.js', array( 'wp-i18n', 'wp-edit-post', 'wp-element', 'wp-editor','wp-blocks', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-compose'), '2.0' );


	
}

add_action( 'enqueue_block_editor_assets', 'tkt_enqueue_block_editor_assets');

function tkt_the_template($template){
        $out = get_post(get_post_meta($template,'_tkt_header',true))->post_content;
        $out .= get_post($template)->post_content;
        $out .= get_post(get_post_meta($template,'_tkt_footer',true))->post_content;
        return apply_filters( 'the_content', $out );
}
