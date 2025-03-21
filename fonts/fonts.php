<?php
function load_custom_fonts() {
    // Define the font styles
    $font_styles = ['Light', 'Regular', 'Medium', 'SemiBold', 'Bold', 'ExtraBold'];

    // Loop through each style and enqueue it
    foreach ($font_styles as $style) {
        $font_slug = strtolower($style); // Convert to lowercase for consistency
        wp_enqueue_style(
            "roboto-{$font_slug}",
            get_template_directory_uri() . "/assets/fonts/roboto-{$font_slug}.woff2",
            [],
            null
        );
    }
}
add_action('wp_enqueue_scripts', 'load_custom_fonts');
?>