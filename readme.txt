=== WPSPX ===
Contributors: beardeddev
Tags: spektrix, tickets, api, booking, theatre
Requires at least: 4.3
Tested up to: 5.3.2
Stable tag: 2.0.0
License: GPL v2 or later
Author: Martin Greenwood

This plugin help connect your WordPress website to the Spektrix API

== Description ==

This plugin allows you to quickly integrate your Spektrix API data into your WordPress website.

== Features out of the Box ==

- Display a list of all upcoming shows
- Display show information (image, show name, duration, dates, short description and list of bookable instances)
- Display the Spektrix basket, checkout and my account via iframes

== Things you NEED to do ==

- Add a folder to your server and host your Spektrix signed SSL certificate
- Populate your spektrix show information
- create a custom domain to avoid cross site scripting issues

== Frequently Asked Questions ==

= I have an error when trying to add a show "Oops, no XML received from Spektrix". =
Double check your API Key, account name and CRT/Key locations

= My data is out of date =
Visit the Settings page and delete the cache or do it manually by emptying the cache folder within the plugin folder

== Installation ==

- Add via the plugin screen by searching for WPSPX or
- Upload WPSPX to the /wp-content/plugins/ directory
- Upload your Signed SSL certificates from Spektrix to the server outside of the root folder (eg: before /public_html)
- Activate the plugin through the 'Plugins' menu in WordPress
- Visit the settings page under Settings > WPSPX
- Enter your API Key, account name, and the path to your signed Spektrix certificates.
- Add your first show

== Changelog ==

= 2.0.0 =

- complete overhaul
- removed outdated functionality
- removed unnecessary code
- cleaned up css
- removed broken shortcodes
- added page templates

= 1.5.0 =

fixed a few errors

= 1.0.0 =

initial release
