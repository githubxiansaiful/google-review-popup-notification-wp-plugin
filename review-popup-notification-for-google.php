<?php
/**
 * Review Popup Notification for Google
 *
 * @package           ReviewPopupNotificationForGoogle
 * @author            Xian Saiful
 * @copyright         2025 Xian Saiful
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Review Popup Notification for Google
 * Plugin URI:        https://wordpress.org/plugins/review-popup-notification-for/ 
 * Description:       A lightweight plugin to show Google reviews as timed pop-up notifications.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Xian Saiful
 * Author URI:        https://xiansaiful.com
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH'))
    exit;

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'grp_enqueue_scripts');
function grp_enqueue_scripts()
{
    wp_enqueue_style('grp-style', plugin_dir_url(__FILE__) . 'css/grp-style.css');
    wp_enqueue_script('grp-script', plugin_dir_url(__FILE__) . 'js/grp-script.js', array('jquery'), null, true);
    wp_localize_script('grp-script', 'grp_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'popup_delay' => get_option('grp_popup_delay', 5000),
        'hover_pause' => get_option('grp_hover_pause', 5000),
        'animation_type' => get_option('grp_animation_type', 'fadeIn')
    ));
}

// Enqueue scripts and styles for admin
add_action('admin_enqueue_scripts', 'grp_admin_enqueue_scripts');
function grp_admin_enqueue_scripts()
{
    wp_enqueue_style('grp-style', plugin_dir_url(__FILE__) . 'css/grp-style.css');
    wp_enqueue_script('grp-script', plugin_dir_url(__FILE__) . 'js/grp-script.js', array('jquery'), null, true);
    wp_localize_script('grp-script', 'grp_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'popup_delay' => get_option('grp_popup_delay', 5000),
        'hover_pause' => get_option('grp_hover_pause', 5000),
        'animation_type' => get_option('grp_animation_type', 'fadeIn'),
        'is_admin' => true
    ));
}

// Admin menu
add_action('admin_menu', 'grp_admin_menu');
function grp_admin_menu()
{
    add_options_page('Review Popup for Google', 'Review Popup for Google', 'manage_options', 'grp-settings', 'grp_settings_page');
}

function grp_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Review Popup for Google Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('grp_settings_group');
            do_settings_sections('grp-settings');
            submit_button();
            ?>
        </form>
        <div class="grp-preview-section">
            <h2>Preview</h2>
            <p>Click the button below to see a live preview of the popup with current settings.</p>
            <button id="grp-preview-button" class="button">Show Preview</button>
            <div id="grp-preview-container"></div>
        </div>
    </div>
    <?php
}

add_action('admin_init', 'grp_register_settings');
function grp_register_settings()
{
    register_setting('grp_settings_group', 'grp_api_key', array(
        'sanitize_callback' => 'sanitize_text_field'
    ));
    register_setting('grp_settings_group', 'grp_place_id', array(
        'sanitize_callback' => 'sanitize_text_field'
    ));
    register_setting('grp_settings_group', 'grp_delay', array(
        'sanitize_callback' => 'absint'
    ));
    register_setting('grp_settings_group', 'grp_popup_delay', array(
        'sanitize_callback' => 'absint'
    ));
    register_setting('grp_settings_group', 'grp_hover_pause', array(
        'sanitize_callback' => 'absint'
    ));
    register_setting('grp_settings_group', 'grp_animation_type', array(
        'sanitize_callback' => 'sanitize_text_field'
    ));
    register_setting('grp_settings_group', 'grp_manual_reviews', array( // New setting for manual reviews
        'sanitize_callback' => 'sanitize_textarea_field'
    ));

    add_settings_section('grp_main_section', 'Main Settings', null, 'grp-settings');

    add_settings_field('grp_api_key', 'Google Maps API Key', 'grp_api_key_field', 'grp-settings', 'grp_main_section');
    add_settings_field('grp_place_id', 'Google Place ID', 'grp_place_id_field', 'grp-settings', 'grp_main_section');
    add_settings_field('grp_delay', 'API Cache Delay (in ms)', 'grp_delay_field', 'grp-settings', 'grp_main_section');
    add_settings_field('grp_popup_delay', 'Popup Display Delay (in ms)', 'grp_popup_delay_field', 'grp-settings', 'grp_main_section');
    add_settings_field('grp_hover_pause', 'Hover Pause Duration (in ms)', 'grp_hover_pause_field', 'grp-settings', 'grp_main_section');
    add_settings_field('grp_animation_type', 'Popup Animation', 'grp_animation_type_field', 'grp-settings', 'grp_main_section');
    add_settings_field('grp_manual_reviews', 'Additional Reviews (JSON)', 'grp_manual_reviews_field', 'grp-settings', 'grp_main_section');
}

function grp_api_key_field()
{
    $value = esc_attr(get_option('grp_api_key'));
    echo "<input type='text' name='grp_api_key' value='" . esc_attr($value) . "' size='50' />";
}

function grp_place_id_field()
{
    $value = esc_attr(get_option('grp_place_id'));
    echo "<input type='text' name='grp_place_id' value='" . esc_attr($value) . "' size='50' />";
}

function grp_delay_field()
{
    $value = esc_attr(get_option('grp_delay', 5000));
    echo "<input type='number' name='grp_delay' value='" . esc_attr($value) . "' />";
}

function grp_popup_delay_field()
{
    $value = esc_attr(get_option('grp_popup_delay', 5000));
    echo "<input type='number' name='grp_popup_delay' value='" . esc_attr($value) . "' />";
}

function grp_hover_pause_field()
{
    $value = esc_attr(get_option('grp_hover_pause', 5000));
    echo "<input type='number' name='grp_hover_pause' value='" . esc_attr($value) . "' />";
}

function grp_animation_type_field()
{
    $value = esc_attr(get_option('grp_animation_type', 'fadeIn'));
    $animations = array(
        'fadeIn' => 'Fade In',
        'slideInLeft' => 'Slide In from Left',
        'slideInRight' => 'Slide In from Right',
        'zoomIn' => 'Zoom In'
    );
    echo "<select name='grp_animation_type'>";
    foreach ($animations as $key => $label) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr($key),
            selected($value, $key, false),
            esc_html($label)
        );
    }
    echo "</select>";
}

function grp_manual_reviews_field()
{
    $value = esc_textarea(get_option('grp_manual_reviews'));
    echo "<textarea name='grp_manual_reviews' rows='5' cols='50'>" . esc_textarea($value) . "</textarea>";
    echo "<p>Enter additional reviews in JSON format. Example: [{\"author_name\": \"John Doe\", \"rating\": 4, \"text\": \"Great place!\", \"profile_photo_url\": \"\", \"author_url\": \"\"}]</p>";
}

// AJAX review fetch
add_action('wp_ajax_nopriv_grp_get_reviews', 'grp_get_reviews');
add_action('wp_ajax_grp_get_reviews', 'grp_get_reviews');
function grp_get_reviews()
{
    $api_key = get_option('grp_api_key');
    $place_id = get_option('grp_place_id');
    $delay = get_option('grp_delay');

    if (!$api_key || !$place_id) {
        wp_send_json_error('API key or Place ID missing');
    }

    $transient_key = 'grp_reviews_cache';
    $cached = get_transient($transient_key);

    if ($cached) {
        // Combine cached API reviews with manual reviews
        $manual_reviews = json_decode(get_option('grp_manual_reviews'), true);
        $manual_reviews = is_array($manual_reviews) ? $manual_reviews : [];
        $combined_reviews = array_merge($cached, $manual_reviews);
        $combined_reviews = array_slice($combined_reviews, 0, 10); // Limit to 10 reviews
        wp_send_json_success(['reviews' => $combined_reviews, 'delay' => $delay]);
    }

    $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$place_id}&fields=reviews&key={$api_key}";
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        wp_send_json_error('Failed to fetch reviews');
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $reviews = $body['result']['reviews'] ?? [];

    // Combine API reviews with manual reviews
    $manual_reviews = json_decode(get_option('grp_manual_reviews'), true);
    $manual_reviews = is_array($manual_reviews) ? $manual_reviews : [];
    $combined_reviews = array_merge($reviews, $manual_reviews);
    $combined_reviews = array_slice($combined_reviews, 0, 10); // Limit to 10 reviews

    set_transient($transient_key, $reviews, 12 * HOUR_IN_SECONDS);

    wp_send_json_success(['reviews' => $combined_reviews, 'delay' => $delay]);
}