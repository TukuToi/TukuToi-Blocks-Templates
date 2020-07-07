<?php

function tkt_get_main_layout_parts($key = '_tkt_assigned_to', $object){
	$args = array(
		'post_type' => 'tkt_layout',
	   	'meta_query' => array(
	       	array(
	           	'key' => '_tkt_layout_part',
	           	'value' => 'main',
	           	'compare' => '=',
	       	),
	       	array(
	           	'key' => $key,
	           	'value' => $object,
	           	'compare' => '=',
	       	)

	   )
	);
	$query = new WP_Query($args);
	return $query;
}

function tkt_the_assigned_layout($key, $object){
    if (is_numeric($object)){
    	$object_type = $object+1;
    }
	else{ 
		$object_type = is_object($object) ? $object->post_type : str_replace('single-', '', $object);
	}

	$assigned_layout_id  = tkt_get_main_layout_parts($key, $object_type)->post->ID;
	error_log(print_r($assigned_layout_id, true));
	return $assigned_layout_id;
}

function tkt_the_layout_filter( $content, $template_selected, $id, $kind ) {
	$layout_id = tkt_the_assigned_layout('_tkt_assigned_to',$kind);

    //Store current CT content in var
    $content_main = $content;
    //Prepend our Header Layout
    $content = get_post(get_post_meta($layout_id,'_tkt_header',true))->post_content;
    //Replace the %%POST_CONTENT%% inside our Main Layout with the original CT content
    $content .= str_replace('%%POST_CONTENT%%', $content_main, get_post($layout_id)->post_content);
    
    //Now append our footer template        
    $content .= get_post(get_post_meta($layout_id,'_tkt_footer',true))->post_content;

    //Return it to Toolset
    return $content;
}

add_filter( 'wpv_filter_content_template_output', 'tkt_the_layout_filter', 99, 4 );

function tkt_the_layout_renderer($template, $part = 'main'){//header or footer
    $out = '';
    $out = apply_filters('the_content',get_post(get_post_meta($template,'_tkt_'.$part,true))->post_content);
    return $out;
}

function tkt_the_archive_layout($part){
	global $WPV_view_archive_loop;
   if ( isset( $WPV_view_archive_loop ) ) {
		//$archive_query = $WPV_view_archive_loop->get_archive_loop_query();
			}
    //error_log(print_r($WPV_view_archive_loop->wpa_settings['view_id'], true)); 
                    //$main = apply_filters('the_content',get_post(79)->post_content);
                //$main_layout_array = explode('%%POST_CONTENT%%',$main);
                //echo $main_layout_array[0];

    $layout = tkt_the_assigned_layout('_tkt_used_on_archive',$WPV_view_archive_loop->wpa_settings['view_id']);
    $template =  apply_filters('the_content',get_post($layout)->post_content);
    $main = explode('%%POST_CONTENT%%',$template);
    return $main[$part];
}



//Display the search results page using a WordPress Archive with ID 4 on Tuesday, Thursday and Saturday, and with ID 8 the oher days:
// add_filter( 'wpv_filter_force_wordpress_archive', 'prefix_different_search_results', 30, 2 );
 
// function prefix_different_search_results( $wpa_assigned, $wpa_loop ) {
// 	//view_taxonomy_loop_category, view_cpt_product, view_home-blog-page
	
// 	 $arr = array(
//         'posts_per_page' => -1, # This param ensures only 1 post returned in the array
//         'post_parent'    => $wpa_assigned,
//         'post_type'      => 'wpa-helper',
//     );
  
//     $archive_arr = array_key_first(get_children($arr, 'ARRAY_A')); # Return an array with an item

//     return $wpa_assigned;
// }