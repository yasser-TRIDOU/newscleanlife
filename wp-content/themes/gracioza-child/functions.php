<?php
/**
 * Child-Theme functions and definitions
 */

function gracioza_child_scripts() {
    wp_enqueue_style( 'gracioza-style', get_template_directory_uri(). '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'gracioza_child_scripts' );
?>