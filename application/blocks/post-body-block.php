<?php
function gutenberg_examples_01_register_block() {
 
    // automatically load dependencies and version
    $asset_file = include( TKT_LAYOUTS_MAIN_DIR . 'build/index.asset.php');
 
    wp_register_script(
        'gutenberg-examples-01-esnext',
        plugins_url( 'build/index.js', __FILE__ ),
        $asset_file['dependencies'],
        $asset_file['version']
    );
 
    register_block_type( 'gutenberg-examples/example-01-basic-esnext', array(
        'editor_script' => 'gutenberg-examples-01-esnext',
    ) );
 
}
add_action( 'init', 'gutenberg_examples_01_register_block' );