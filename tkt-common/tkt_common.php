<?php

define('TKT_COMMON_LOADED', true);

define('TKT_ADMIN_MENU_ICON', 'data:image/svg+xml;base64,'. base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1008.52 835.08"><path fill="black" d="M1800.48,797.83Q1685.31,683,1570.31,568.06c-9.69-9.69-19-19.79-27.89-30.26C1385.26,352,1100.6,339.05,928.62,511.43c-94.12,94.34-134.82,209.23-121.35,342,9.92,97.68,50.77,181.69,120.65,250.32,87.31,85.75,193.25,126.3,315.91,120.67,102.35-4.69,191.18-42.88,265.63-112.87,26.71-25.1,50.16-53.61,76-79.65q107.16-107.84,215.16-214.84c2.86-2.84,6.6-4.81,12.63-9.1C1806.87,802.93,1803.36,800.7,1800.48,797.83ZM1165.29,1052.3c-113.09-27.12-191.44-125.58-193.76-242.73-2.21-111.42,77.09-214.53,187.27-243.2,112-29.15,218.38,22.64,268.84,99.6l-142.16,141.7,142.86,142.19C1381.17,1024.15,1276.15,1078.88,1165.29,1052.3Zm381.14-171.21c-40.56.18-74.45-33.65-74.41-74.3a74.38,74.38,0,0,1,74.43-74.07c40.86.09,74.06,33.66,73.83,74.64C1620.05,847.6,1586.68,880.91,1546.43,881.09Z" transform="translate(-804.76 -389.88)"/><path d="M1540.45,772.44c-18.93.47-34.43,16.22-34.47,35a35.14,35.14,0,0,0,35.41,35,35,35,0,1,0-.94-70.06Z" transform="translate(-804.76 -389.88)"/></svg>'));

function tkt_main_menu_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    else{

            // add error/update messages
            // check if the user have submitted the settings
            // wordpress will add the "settings-updated" $_GET parameter to the url
            if ( isset( $_GET['settings-updated'] ) ) {
                // add settings saved message with the class of "updated"
                add_settings_error( 'tkt_global_messages', 'tkt_global_message', __( 'Settings Saved', 'tktglobal' ), 'updated' );
            }
        }
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "wporg_options"
        settings_fields( 'tktglobal' );
        // output setting sections and their fields
        // (sections are registered for "wporg", each field is registered to a specific section)
        do_settings_sections( 'tktglobal' );
        // output save settings button
        submit_button( __( 'Save Settings', 'textdomain' ) );
        ?>
      </form>
    </div>
    <?php
}

add_action( 'admin_menu', 'tkt_add_main_menu' );

function tkt_add_main_menu(){
    //$pages[]  = array();
    $pages[]    = add_menu_page( 'TukuToi', 'TukuToi', 'manage_options', 'tkt-main', 'tkt_main_menu_page', TKT_ADMIN_MENU_ICON);
    $pages[]    = add_submenu_page( 'tkt-main', 'TukuToi', 'Dashboard', 'manage_options', 'tkt-main', 'tkt_main_menu_page' );
    foreach ($pages as $page) {
        add_action( "admin_print_styles-{$page}", 'tkt_enqueue_styles' );
    }
    
    
}

function tkt_global_register_styles_and_scripts() {
    //Main style sheet
    wp_register_style( 'yqbStyle', TKT_LAYOUTS_MAIN_DIR.'assets/style.css' );
    
}
/**
 * Function to enque style when needed
 */
function tkt_enqueue_styles() {

    wp_enqueue_style( 'yqbStyle' );

}

add_action( 'admin_init', 'tkt_global_register_styles_and_scripts' );


add_action( 'admin_init', 'tkt_global_settings_init' );
//Initiate Settings options
function tkt_global_settings_init() {

    // register a new setting
    register_setting( 'tktglobal', 'tkt_global_options' );
 
    // register a new section
    add_settings_section(
        'tkt_global_section_developers',
        '',//__( 'The Matrix has you.', 'wporg' )
        '',//wporg_section_developers_cb
        'tktglobal'
    );

    //Array of options/settings
    $settings_array = array(
        "tkt_global_delete_options" => "Delete Options on Uninstall"
        //Add meta box by post to exclude specific scriptts or stypes onthere
    );

    //Why create as many functions as there are options? Just use foreach($settings_array) to create each settings field
    foreach ($settings_array as $option => $name) {
        add_settings_field(
            $option, // as of WP 4.6 this value is used only internally
             // use $args' label_for to populate the id inside the callback
            __( $name, 'tktglobal' ),
            $option . '_cb',
            'tktglobal',
            'tkt_global_section_developers',
            [
                'label_for' => $option,
                'class' => 'tkt_global_row',
                'tkt_global_custom_data' => 'custom',
            ]
        );

    }

}

//Register callbacks for our options - I am sure there should be a way to NOT register EACH callback manually, something like a foreach above, however, not yet clear how to do this best.

function tkt_global_section_developers_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'tktglobal' ); ?></p>
    <?php
}

function tkt_global_delete_options_cb( $args ) {
    $options = get_option( 'tkt_global_options' );
    
    // output the field
    ?><span class="description"><?php esc_html_e( 'If checked, TukuToi Plugins will delete their saved options on Uninstall.', 'tktglobal' ); ?>
        </span>
        <input 
        type="checkbox" 
        class="tkt-option-input" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        data-custom="<?php echo esc_attr( $args['tkt_global_custom_data'] ); ?>" 
        name="tkt_global_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        <?php echo isset($options['tkt_global_delete_options']) ? 'value="1"' :'value="0"'; ?> 
        <?php echo isset($options['tkt_global_delete_options']) ? 'checked="checked"' : ''; ?> 
        />
    <?php
}

//Gather settings for usage in query engines
//possible options @see $settings_array

function tkt_all_global_options($option){
    $option_set = get_option( 'tkt_global_options' )[$option];
    return $option_set;
}

//Add main menu help tab
function tkt_global_admin_active_add_help_tab () {
    // $screen = get_current_screen();
    // // Add my_help_tab if current screen is My Admin Page
    // $screen->add_help_tab( array(
    //     'id' => 'yt-query-builder-menus-help-tab',
    //     'title'  => __('Menus'),
    //     'content'    => '<div class="main-dashboard-inner"><li><strong>YT Query Builder</strong>, or <strong>Main Dashboard</strong> can be used to alter Plugin Settings.</li>
                //      <li><strong>Feedback Requested</strong> produces a list of all Employees who\'s feedback is requested in 6 or more YT tickets.</li>
                //      <li><strong>Bugs</strong> produces a list of known Bugs for WPML and Toolset, and quickly see which related escalated Ticket, if resolved, would resolve the most issues, or quickly see potential Duplicates</li>
                //      <li><strong>Custom Queries</strong> shows the Custom Queries you created using  <strong>Main Dashboard</strong></li></div>',
    // ) );
    // $screen->add_help_tab( array(
    //     'id' => 'yt-query-builder-slow-help-tab',
    //     'title'  => __('Performance'),
    //     'content'    => '<div class="main-dashboard-inner"><p>Scanning processes take time. When visiting YouTrack Query Builder Menus let the program time to finish loading. It may require several minutes.</p></div>',
    // ) );
}