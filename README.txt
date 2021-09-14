=== Woocommerce Multi Warehouses - Location Based Inventory Management ===
Contributors: joeleem0n
Tags: Woocommerce, Inventory, Warehouse Inventory, Woocommerce warehouses, Multiple warehouses, Currency Conversion, Currency, Geo location, Pop-ups, Venby, Multi-site
Requires at least: 4.0.1
Tested up to: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add new warehouses with a unique inventory, currency, pricing, & payment gateway with a single WC site install.

== Description ==

Offer your visitors location based, multi-shop inventory from a single Wordpress + Woocommerce installation. Sync your inventory between different warehouses, set your regional currency based on your warehouse locations, & redirect customers to multiple payment gateways between different warehouses. 

You or your customer can select a product's availability & price based on a warehouse location.

Tired of managing multiple sites? Easily set your default currency & payment gateway according to each warehouse without the need to manage and install multiple Wordpress sites and sync inventory across each site.

The Woocommerce Warehouse Pop-ups plugin is simple to use & install:

* Create additional warehouses for a single Wordpress + Woocommerce site in minutes.
* Add & manage each warehouse inventory separately or keep all warehouses inventory in-sync with your default Woocommerce warehouse inventory.
* Choose a unique currency & price for each additional warehouse.
* Connect different payment gateways based on each additional warehouse location.
* Automate geolocation by country or zip code based on the visitor’s IP address.
* Redirect site visitors to a warehouse location automatically using GEO IP. Then display warehouse inventory, currency, and gateway based on the warehouse location.(Geotargeting must be allowed by your host in order for this feature to work)

This plugin offers some wonderful benefits:

*   Do you have different affiliates shipping and fulfilling across multiple different locations? Keep your website’s inventory in-sync across all locations / warehouses using this plugin.
*   Set additional location based warehouses for special retail events.
*   Partner with international distributors who can safely and effectively manage inventory separately from your own default woocommerce inventory.
*   Connect a different payment gateway for each separate warehouse location and pay your affiliates directly.
*   Offer site visitors prices in local currency. Get paid in local currency.
*   Give your site visitors inventory availability in other warehouse locations.
*   New: Get real time exchange rates. Set product prices based on a multitude of factors with real-time currency exchange rates. Or manually adjust product pricing based on each warehouse location.


Sometimes it’s helpful to understand the limitations of this plugin.

* You **cannot** add multiple warehouses and manage inventory for only a subset of products within your catalog. Once a warehouse is added, inventory management for every product is applied across your entire product catalog.
* You **cannot** connect multiple payment gateway accounts on the same platform to different warehouses. For example: You cannot connect Stripe account 1 to Warehouse Location A and connect Stripe account 2 to location B.
* You **cannot** set different product prices with different currencies.

Let us know what you’d like to see next!

Plugin Demo: <a href="https://humanoidwake.com">humanoidwake.com</a>

**Note: This is a free plugin. The plugin includes a connection for only 1 additional warehouse in Woocommerce. <a href="https://venby.io/wordpress-plugin-woocommerce-warehouses-pro/">Create unlimited warehouses by purchasing a license key from our website. We offer 1:1 chat & email support.</a>**

== Installation ==

= FROM WITHIN WORDPRESS =
1. Visit 'Plugins > Add New'
2. Search for 'Woocommerce multi warehouses'
3. Activate 'Woocommerce multi warehouses' from your Plugins page.
4. Go to "after activation" below.

= MANUALLY =
1. Upload the Woocommerce Warehouse Pop ups folder to the /wp-content/plugins/directory
2. Activate the 'Woocommerce Warehouse Pop ups' plugin through the 'Plugins' menu in WordPress
3. Go to “after activation” below.

= AFTER ACTIVATION =
1. You should see a new tab called 'Warehouses' in Woocommerce settings.
2. Read the instructions and set up the additional warehouse.
3. Embed the shortcode below for the warehouse dropdown to appear on your website.

To display the warehouses flybox on your website - use `[wh_popups_warehouses_flybox]` shortcode.
You can place the code anywhere into a page using WP Admin editor using copy-paste,
or insert as PHP code into your theme files: `<?php echo do_shortcode('[wh_popups_warehouses_flybox]']); ?>`

== Screenshots ==

1. Create new warehouses & inventory using a single WP site.
2. Customize Warehouse selector for your site visitors
3. Each warehouse can display unique inventory, currency & payment gateways 
4. Keep separate inventory for each warehouse & product
5. Restrict warehouses by site IP addresses during entry & checkout
5. Add unlimited warehouses with our Pro version

== Changelog ==

2.0.3 - Country & shipping zone fix for site entry/checkout

2.0.2 - Fix more GEO IP bugs

2.0.1 - Fix GEO IP latency bugs

2.0.0 - Refactoring, bug fixes

1.5.1 - Fix bugs on admin page and shop page

1.5.0 - Update admin page  and bugs fixing

1.4.9.2 - Bugs fixing

1.4.9.1 - Bug fixing

1.4.9 - Default warehouse defined automatically by customer's region

1.4.8 - Adaptive to PHP 7.4

1.4.7 - Fix bugs with admin page

1.4.6 - Fix bugs with admin page

1.4.5 - Fix bugs with admin page

1.4.4 - Update admin page

1.4.3 - Update admin page

1.4.2 - Adding different emails for different warehouses

1.4.1 - Adding correct address in admin-panel

1.4.0 - Optimization, defining nearest warehouse by Google Map API

1.3.9 - Optimization, defining country by API

1.3.8 - Warehouse defined automatically by customer's region

1.3.7 - Woocommerce shipping zone functionality extended to include additional warehouses and Squash some bugs

1.3.6 - fix several bugs and make translation

1.3.5 - change currency converter api and fix minor bug

1.3.4 - fix geolocation bug

1.3.3 - fix minor bugs(cart inventory issue)

1.3.2 - fix minor bugs(cart total price issue)

1.3.1 - cancel wp engine geoip dependency

1.3.0 - fix minor bugs(cart && price filter)

1.2.9 - auto currency change function by geo location, minor bugfixes(dependency issue)

1.2.8 - added currency switcher function, minor bugfixes

1.2.5 - minor bugfixes to match latest PHP and Wordpress requirements

1.2.0 - shipping zones, uploaded to wordpress storage

1.1.5 - individual backorder settings for alt warehouses

1.1.2 - use default warehouses (use default inventory/variations stock qty counters)

1.1.0 - editable warehouses

1.0.5 - added currencies

1.0.0 - gateways added

0.9.5 - first live version run

0.9.0 - basic functions with stock inventory hooks and multiple warehouses