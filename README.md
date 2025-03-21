# 2DC



WordPress Debugging and Implementation Guide
Part A: Debugging a Client Website
Scenario:
One of the client websites is down both on the front-end (FE) and back-end (BE) due to an issue with a plugin or theme. You only have access to the database and files.
Steps to Resolve:
Check the Error Logs:

Access the wp-content/debug.log file if WP_DEBUG is enabled in wp-config.php.
If debugging is not enabled, add the following to wp-config.php:

define('WP_DEBUG', true);

define('WP_DEBUG_LOG', true);

define('WP_DEBUG_DISPLAY', false);

@ini_set('display_errors', 0);

Reload the site and check the logs in wp-content/debug.log.

Disable Plugins via Database:

Access the database using phpMyAdmin or a MySQL client.
Run the following SQL command to disable all plugins:

UPDATE wp_options SET option_value = 'a:0:{}' WHERE option_name = 'active_plugins';

Check if the site is accessible.
Reactivate plugins one by one via the WordPress admin panel or database (wp_options table) to identify the faulty plugin.

Disable the Active Theme:

Rename the active theme’s folder in wp-content/themes/ (e.g., theme-name to theme-name-disabled).
WordPress will fall back to a default theme like twentytwentythree.
If the site loads, the issue is with the theme.

Manually Fix the Faulty Plugin/Theme:

If logs indicate a specific plugin or theme file causing the error, open and inspect it.
Comment out problematic code or replace it with a working version.
If a plugin update caused the issue, restore a previous working version from backups or WordPress plugin repository.


Part B: Implementation Tasks
1. Create theme.json from Figma Design System
Extract colors, typography, and spacing from the “Design System” page in Figma.
Structure theme.json accordingly:

{

  "version": 2,

  "settings": {

    "color": {

      "palette": [

        { "name": "Black", "slug": "black", "color": "#000000" },

        { "name": "Dark", "slug": "dark", "color": "#222222" },

        { "name": "Yellow", "slug": "yellow", "color": "#EDE236" },

        { "name": "White", "slug": "white", "color": "#FFFFFF" },

        { "name": "Ash", "slug": "ash", "color": "#F2F2F2" },

        { "name": "Maroon", "slug": "maroon", "color": "#913D2E" }

      ]

    },

    "typography": {

      "fontFamilies": [

        { "name": "Roboto", "slug": "roboto", "fontFamily": "Roboto, sans-serif" }

      ]

    }

  }

}
2. PHP Function to Load Roboto Fonts
function load_custom_fonts() {

    echo '<style>

        @font-face {

            font-family: "Roboto";

            src: url("' . get_template_directory_uri() . '/assets/fonts/Roboto-Regular.woff2") format("woff2"),

                 url("' . get_template_directory_uri() . '/assets/fonts/Roboto-Regular.woff") format("woff");

            font-weight: normal;

            font-style: normal;

        }

    </style>';

}

add_action('wp_head', 'load_custom_fonts');
3. Gutenberg Custom Block: "Team Member" (No jQuery/ACF)
const { registerBlockType } = wp.blocks;

const { RichText, MediaUpload } = wp.blockEditor;

const { Button } = wp.components;

const { useState } = wp.element;

registerBlockType('custom/team-member', {

    title: 'Team Member',

    icon: 'admin-users',

    category: 'common',

    attributes: {

        name: { type: 'string', source: 'text', selector: 'h3' },

        position: { type: 'string', source: 'text', selector: 'h4' },

        imageUrl: { type: 'string', source: 'attribute', selector: 'img', attribute: 'src' },

        description: { type: 'string', source: 'text', selector: 'p' }

    },

    

    edit: ({ attributes, setAttributes }) => {

        const [isVisible, setIsVisible] = useState(false);

        return (

            <div className="team-member">

                <MediaUpload

                    onSelect={(media) => setAttributes({ imageUrl: media.url })}

                    type="image"

                    render={({ open }) => (

                        <Button onClick={open} className="button button-large">

                            {attributes.imageUrl ? <img src={attributes.imageUrl} alt="Profile" /> : 'Upload Image'}

                        </Button>

                    )}

                />

                <RichText tagName="h3" value={attributes.name} onChange={(value) => setAttributes({ name: value })} placeholder="Enter name" />

                <RichText tagName="h4" value={attributes.position} onChange={(value) => setAttributes({ position: value })} placeholder="Enter position" />

                <Button onClick={() => setIsVisible(!isVisible)} className="button">

                    {isVisible ? 'Hide Details' : 'View Details'}

                </Button>

                {isVisible && <RichText tagName="p" value={attributes.description} onChange={(value) => setAttributes({ description: value })} placeholder="Enter description" />}

            </div>

        );

    },

    

    save: ({ attributes }) => (

        <div className="team-member">

            {attributes.imageUrl && <img src={attributes.imageUrl} alt="Profile" />}

            <h3>{attributes.name}</h3>

            <h4>{attributes.position}</h4>

            <button className="view-details" onClick={(e) => {

                const desc = e.target.nextElementSibling;

                desc.style.display = desc.style.display === 'block' ? 'none' : 'block';

                e.target.textContent = desc.style.display === 'block' ? 'Hide Details' : 'View Details';

            }}>

                View Details

            </button>

            <p style={{ display: 'none' }}>{attributes.description}</p>

        </div>

    )

});



This guide provides step-by-step debugging for a broken WordPress site and implementation tasks based on a Figma design system.

