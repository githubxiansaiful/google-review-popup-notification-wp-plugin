=== Google Review Popup ===
Contributors: developersaiful
Tags: google reviews, popup, notifications, wordpress plugin
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight WordPress plugin to display Google reviews as timed pop-up notifications on your website.

== Description ==
The Google Review Popup plugin allows you to showcase Google reviews from a specified Place ID as attractive pop-up notifications. Admins can customize the popup delay, hover pause duration, and animation type (Fade In, Slide In from Left, Slide In from Right, or Zoom In) via the WordPress dashboard. The plugin fetches reviews using the Google Maps API and caches them for performance.

= Features =
* Display Google reviews as timed pop-ups.
* Customizable popup delay and hover pause duration.
* Choose from multiple animation styles.
* Live preview in the admin settings.
* Lightweight and easy to configure.

== Installation ==
1. Upload the `google-review-popup` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to `Settings > Google Review Popup` to configure the plugin.
4. Enter your Google Maps API Key and Place ID.
5. Adjust the popup settings (delay, pause, animation) as desired.
6. Save changes and test the preview.

== Frequently Asked Questions ==
= Where do I get a Google Maps API Key? =
You can obtain a Google Maps API Key from the [Google Cloud Console](https://console.cloud.google.com/).

= How do I find my Google Place ID? =
Use the [Google Places API Place ID Finder](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder) to locate your Place ID.

= Why aren't reviews showing up? =
Ensure your API Key and Place ID are correct, and that the Google Maps API is enabled in your Google Cloud Console.

== Screenshots ==
1. Admin Settings Page
2. Popup Preview
3. Front-end Popup Example

== Changelog ==
= 1.0 =
* Initial release with basic functionality, customizable delays, and animation options.

== Upgrade Notice ==
= 1.0 =
Initial release. No upgrades available yet.