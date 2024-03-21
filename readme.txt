=== WPSPX ===
Contributors: beardeddev
Donate link: https://www.paypal.me/martingreenwood
Tags: spektrix, tickets, api, booking, theatre, wp-spektrix, wpspx
Requires at least: 4.3
Tested up to: 5.5
Requires PHP: 7.0
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author: Martin Greenwood

This plugin help connect your WordPress website to the Spektrix API

== Description ==

WPSPX allows you to import your [Spektrix](https://www.spektrix.com/) performaces into WordPress utiliing the Spektrix API. It will auitomatically fetch and cache your data from the Spextrix API, allowing you to display your data and sell tickets to your shows. You will need a Spektrix customer account to use this plugin, please read and understand the [Spektrix Terms & Conditions](https://www.spektrix.com/static/terms/).

Check out the [Demo](https://demo.wpspx.io).

== Features out of the Box ==

- Automatically creates a set of pages with ou for Upcoming Shows, Memberships, Gift Vouchers, Basket, Checkout, Book Online (processing seats / ticket choices) & my account
- Gives you access to a range of shortcodes to show a grid of shows, membershiop(s), gift voucher form & donate form
- Displays show information (image, show name, duration, dates, short description and list of bookable instances) via a custom post types
- Displays the Spektrix basket, checkout and my account via iframes

== Things you NEED to do ==

- create a [custom domain](https://integrate.spektrix.com/docs/customdomains) to avoid cross site scripting issues

== Installation ==

- Upload wpspx to the /wp-content/plugins/ directory and activate
- Activate the plugin through the 'Plugins' menu in WordPress
- Visit the settings page under plugin actions link or use the WPSPX meuu
- Enter your account name & custom domain
- Visit the Data Sync tab and sync your Spektrix shows
- Visit the Cache tab and choose how long you want to cache your data

== Screenshots ==

1. WPSPX Settings
2. WPSPX Settings - Data sync
3. WPSPX Settings - Data syncing
4. WPSPX Settings - Data synced
5. WPSPX Settings - Cache
6. WPSPX Settings - Basket (addon)
7. WPSPX Settings - Login (addon)
8. WPSPX Settings - Support
9. WPSPX Settings - Suppoert information
10. WPSPX Shows
11. WPSPX Show Edit
12. WPSPX Upcomming Shows Template
13. WPSPX Show Template
14. WPSPX Booking Process (iframe)
15. WPSPX Gift Card
16. WPSPX Donation Form
17. WPSPX Membership (shortcode)
18. WPSPX Membership (added to basket)
19. WPSPX Basket (addon)
20. WPSPX Basket (iframe)

== Changelog ==

= 1.0.2 =

updated logos

= 1.0.1 =

- WordPress 5.5 compatability
- Fixed bug with javascvript cache not returning data

= 1.0.0 =

- Complete rebuild
- New caching system
- New data sync