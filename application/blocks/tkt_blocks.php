<?php
function tkt_enqueue_block_editor_assets() {
    if( get_current_screen()->post_type !== POST_TYPE OR !get_current_screen()->is_block_editor)
        return;
    wp_enqueue_script( 'tkt-layouts-sidebar-script', TKT_LAYOUTS_MAIN_URL . 'application/admin/js/sidebar.js', array( 'wp-i18n', 'wp-edit-post', 'wp-element', 'wp-editor','wp-blocks', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-compose'), '2.0' ); 
}

function tkt_register_blocks() {

    wp_register_script('tkt-layouts-post-body-block-script', TKT_LAYOUTS_MAIN_URL . 'application/admin/js/post-body-block.js', array( 'wp-i18n', 'wp-edit-post', 'wp-element', 'wp-editor','wp-blocks', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-compose'), '3.0');
    wp_register_script('tkt-layouts-fancy-container-block-script', TKT_LAYOUTS_MAIN_URL . 'application/admin/js/fancy-container-block.js', array( 'wp-i18n', 'wp-edit-post', 'wp-element', 'wp-editor','wp-blocks', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-compose'), '2.0');

    wp_register_script('tkt-layouts-base', TKT_LAYOUTS_MAIN_URL . 'application/blocks/js/base.js', array( 'wp-i18n', 'wp-edit-post', 'wp-element', 'wp-editor','wp-blocks', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-compose'), '3.0');

    wp_register_style(
        'tkt-layouts-blocks-editor',
        TKT_LAYOUTS_MAIN_URL . 'application/admin/css/editor.css',
        array( 'wp-edit-blocks' )
    );
 
    wp_register_style(
        'tkt-layouts-blocks',
        TKT_LAYOUTS_MAIN_URL . 'application/frontend/css/style.css',
        array( )
    );

    register_block_type( 'tkt-gutenberg-blocks/layout-part-body', array(
        'editor_script' => 'tkt-layouts-post-body-block-script',
        'style' => 'tkt-layouts-blocks',
        'editor_style' => 'tkt-layouts-blocks-editor',
    ) );

    register_block_type( 'tkt-gutenberg-blocks/fancy-container-block', array(
        'editor_script' => 'tkt-layouts-fancy-container-block-script'
    ) );

    register_block_type( 'tkt-gutenberg-blocks/base', array(
        'editor_script' => 'tkt-layouts-base'
    ) );


 
}
add_action( 'init', 'tkt_register_blocks' );

function tkt_register_meta_fields(){

    //Define fields to register
    //Hide fields because of bug in unnhidden fields when custom field editor is open
    $meta_field_array   = array('_tkt_layout_part' => 'string','_tkt_header_type' => 'string', '_tkt_header' => 'string', '_tkt_footer' => 'string', '_tkt_footer_type' => 'string', '_tkt_assigned_to' => 'string', '_tkt_used_on_archive' => 'string'); 

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

function tkt_rest_query( $args, $request ){
    if ( $meta_key = $request->get_param( 'metaKey' ) ) {
        $args['meta_key'] = $meta_key;
        $args['meta_value'] = $request->get_param( 'metaValue' );
    }
    return $args;
}