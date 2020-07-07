<?php

function tkt_add_reusable_blocks_menu(){
   
    //$page = add_submenu_page( 'tkt-main', 'Layouts', 'TukuToi Layouts', 'manage_options', 'edit.php?post_type=tkt_layout', NULL, 99 );
    $page = add_submenu_page( 'tkt-main', 'Reusable Blocks', 'Reusable Blocks', 'manage_options', 'edit.php?post_type=wp_block', NULL, 2 );
}
function tkt_add_layouts_menu(){
   
    $page = add_submenu_page( 'tkt-main', 'Layouts', 'TukuToi Layouts', 'manage_options', 'edit.php?post_type=tkt_layout', NULL, 1 );
}

function tkt_layouts_filter_columns( $columns ) {
    // this will add the column to the end of the array
    $columns['tkt_layout_area'] = 'Layout Area';
    $columns['tkt_assigned_to'] = 'Assigned To';
    //add more columns as needed

    // as with all filters, we need to return the passed content/variable
    return $columns;
}

function tkt_custom_columns_content ( $column_id, $post_id ) {
    //run a switch statement for all of the custom columns created
    switch( $column_id ) { 
        case 'tkt_layout_area':
            echo ($value = get_post_meta($post_id, '_tkt_layout_part', true ) ) ? $value : 'No Layout Part Defined ';
        break;
        case 'tkt_assigned_to':
            $value = get_post_meta($post_id, '_tkt_assigned_to', true ) ? '<strong>Post Type:</strong> '.get_post_meta($post_id, '_tkt_assigned_to', true ).'; ' : 'Not Assigned To Any Post Type; ';
            $value .= get_post_meta($post_id, '_tkt_used_on_archive', true ) ?  '<strong>Archive:</strong> '.get_post_meta($post_id, '_tkt_used_on_archive', true ).'; ' : 'Not Assigned To Any Archive; ';
            echo ($value ? $value : 'No Layout Part Defined');
        break;

        //add more items here as needed, just make sure to use the column_id in the filter for each new item.

   }
}

/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */
function tkt_duplicate_layouts_as_draft(){
    global $wpdb;
    if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'tkt_duplicate_layouts_as_draft' == $_REQUEST['action'] ) ) ) {
        wp_die('No post to duplicate has been supplied!');
    }
 
    /*
     * Nonce verification
     */
    if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
        return;
 
    /*
     * get the original post id
     */
    $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
    /*
     * and all the original post data then
     */
    $post = get_post( $post_id );
 
    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;
 
    /*
     * if post data exists, create the post duplicate
     */
    if (isset( $post ) && $post != null) {
 
        /*
         * new post data array
         */
        $args = array(
            'ping_status'    => $post->ping_status,
            'post_content'   => $post->post_content,
            'post_name'      => $post->post_name,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
        );
 
        /*
         * insert the post by wp_insert_post() function
         */
        $new_post_id = wp_insert_post( $args );
 
        /*
         * duplicate all post meta just in two SQL queries
         */
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos)!=0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                if( $meta_key == '_wp_old_slug' ) continue;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }
 
 
        /*
         * finally, redirect to the edit post screen for the new draft
         */
        wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
    } else {
        wp_die('Post creation failed, could not find original post: ' . $post_id);
    }
}

/*
 * Add the duplicate link to action list for post_row_actions
 */
function tkt_duplicate_layouts_link( $actions, $post ) {
    if (current_user_can('edit_posts') && $post->post_type=='tkt_layout') {
        $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=tkt_duplicate_layouts_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
    }
    return $actions;
}