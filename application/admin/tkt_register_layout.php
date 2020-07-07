<?php

function tkt_blocks_layout() {

    $labels = array(
        'name'                  => _x( 'Layouts', 'Post Type General Name', 'tkt-blocks-template' ),
        'singular_name'         => _x( 'Layout', 'Post Type Singular Name', 'tkt-blocks-template' ),
        'menu_name'             => __( 'Layouts', 'tkt-blocks-template' ),
        'name_admin_bar'        => __( 'Layouts', 'tkt-blocks-template' ),
        'archives'              => __( '', 'tkt-blocks-template' ),
        'attributes'            => __( '', 'tkt-blocks-template' ),
        'parent_item_colon'     => __( '', 'tkt-blocks-template' ),
        'all_items'             => __( 'All Layouts', 'tkt-blocks-template' ),
        'add_new_item'          => __( 'Create New Layout', 'tkt-blocks-template' ),
        'add_new'               => __( 'Create New', 'tkt-blocks-template' ),
        'new_item'              => __( 'Create Layout', 'tkt-blocks-template' ),
        'edit_item'             => __( 'Edit Layout', 'tkt-blocks-template' ),
        'update_item'           => __( 'Update Layout', 'tkt-blocks-template' ),
        'view_item'             => __( 'View Layout', 'tkt-blocks-template' ),
        'view_items'            => __( 'View Layouts', 'tkt-blocks-template' ),
        'search_items'          => __( 'Search Layout', 'tkt-blocks-template' ),
        'not_found'             => __( 'Not found', 'tkt-blocks-template' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'tkt-blocks-template' ),
        'featured_image'        => __( '', 'tkt-blocks-template' ),
        'set_featured_image'    => __( '', 'tkt-blocks-template' ),
        'remove_featured_image' => __( '', 'tkt-blocks-template' ),
        'use_featured_image'    => __( '', 'tkt-blocks-template' ),
        'insert_into_item'      => __( '', 'tkt-blocks-template' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Layout', 'tkt-blocks-template' ),
        'items_list'            => __( 'Layouts list', 'tkt-blocks-template' ),
        'items_list_navigation' => __( 'Layouts list navigation', 'tkt-blocks-template' ),
        'filter_items_list'     => __( 'Filter Layouts list', 'tkt-blocks-template' ),
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
        'label'                 => __( 'Layout', 'tkt-blocks-template' ),
        'description'           => __( 'Layouts built with Blocks', 'tkt-blocks-template' ),
        'labels'                => $labels,
        'supports'              => array( 'editor', 'revisions', 'custom-fields', 'title' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => false,
        'menu_position'         => 99,
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
    register_post_type( 'tkt_layout', $args );

}