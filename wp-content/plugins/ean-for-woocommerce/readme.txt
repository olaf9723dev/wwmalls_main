=== EAN for WooCommerce ===
Contributors: wpcodefactory, algoritmika, anbinder, karzin, omardabbas, kousikmukherjeeli
Tags: woocommerce, ean, gtin, barcode, upc
Requires at least: 4.4
Tested up to: 6.5
Stable tag: 4.9.4
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Manage product GTIN (EAN, UPC, ISBN, etc.) in WooCommerce. Beautifully.

== Description ==

**EAN for WooCommerce** plugin lets you manage product GTIN (EAN, UPC, ISBN, etc.) in WooCommerce.

Currently supported standards: EAN-13, UPC-A, EAN-8, ISBN-13, JAN, EAN-14, Custom.

### &#9989; Main Features ###

* **Save product's EAN** in backend.
* For **variable products** set EAN for each variation individually or set single EAN for all variations at once.
* **Search by EAN** in backend (including AJAX search) and in frontend.
* Add sortable EAN column to **admin products list**.
* Optionally **show EAN** on the **single product page**, **shop pages**, **cart page** or **checkout page** on the frontend.
* Add EAN to **product structured data**, e.g., for Google Search Console.
* Add EAN to **WooCommerce REST API** order and product responses; search orders and products by EAN.
* Show EAN in **order items table**, including emails, "thank you" page, etc.
* **Export** and **import** EAN.
* Use product **quick** and **bulk edit** to manage EAN.
* Output EAN with a **shortcode**.
* And more...

### &#129520; Tools ###

Plugin has tools that will help you generate, copy, assign and delete EANs in bulk, automatically or periodically.

* **Generate** EANs automatically with customizable EAN type, country prefix(es) and seed.
* **Copy** EANs **from** product **SKU**, product **ID** or product **meta**.
* **Assign** EANs from the predefined **list**.
* **Delete** EANs for all products at once.
* **Copy** EANs **to** product **SKU** or product **attribute**.
* **Add** or **delete** EANs for all **orders** at once.
* **Search orders** by EAN.
* And more...

### &#129309; Compatibility ###

* [Google Listings & Ads](https://wordpress.org/plugins/google-listings-and-ads/) plugin.
* [Point of Sale for WooCommerce](https://woocommerce.com/products/point-of-sale-for-woocommerce/) plugin.
* [Woocommerce OpenPos](https://codecanyon.net/item/openpos-a-complete-pos-plugins-for-woocomerce/22613341) plugin.
* [Dokan marketplace](https://wordpress.org/plugins/dokan-lite/) plugin.
* [WCFM](https://wordpress.org/plugins/wc-frontend-manager/) and [WCFM Marketplace](https://wordpress.org/plugins/wc-multivendor-marketplace/) plugins.
* [MultiVendorX](https://wordpress.org/plugins/dc-woocommerce-multi-vendor/) plugin.
* [Print Invoice & Delivery Notes for WooCommerce](https://wordpress.org/plugins/woocommerce-delivery-notes/) plugin.
* [WooCommerce PDF Invoices & Packing Slips](https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/) plugin.
* [WooCommerce PDF Invoices, Packing Slips, Delivery Notes and Shipping Labels](https://wordpress.org/plugins/print-invoices-packing-slip-labels-for-woocommerce/) plugin.
* [WooCommerce Google Product Feed](https://woocommerce.com/products/google-product-feed/) plugin.
* [Rank Math SEO](https://wordpress.org/plugins/seo-by-rank-math/) plugin.
* [WooCommerce Customer / Order / Coupon Export](https://woocommerce.com/products/ordercustomer-csv-export/) plugin.
* And more...

### &#127942; Premium Version ###

With [premium plugin version](https://wpfactory.com/item/ean-for-woocommerce/) you can:

* Generate and display **barcode image** for your product EAN (frontend, backend, order items table (including emails), REST API, etc.).
* Barcodes can be **one-dimensional** (1D barcodes) or **two-dimensional** (2D barcodes, QR codes).
* Additionally you can **print** multiple EANs and barcodes to **PDF** file.
* Add multiple **extra fields**, e.g., single product can have **EAN** and **MPN** fields **simultaneously**.

### &#128472; Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/ean-for-woocommerce/).

### &#8505; More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > EAN".

== Screenshots ==

1. EAN for WooCommerce - Backend - Simple product
2. EAN for WooCommerce - Backend - Variable product
3. EAN for WooCommerce - Frontend - Variable product
4. EAN for WooCommerce - Admin settings
5. EAN for WooCommerce - Tools

== Changelog ==

= 4.9.4 - 26/04/2024 =
* Dev - Compatibility - "Google Product Feed" option added (defaults to `yes`).
* Dev - Compatibility - "Woocommerce OpenPos" option added (defaults to `yes`).
* WC tested up to: 8.8.

= 4.9.3 - 15/04/2024 =
* Security - Shortcodes -  Sanitizing shortcodes output (`wp_kses_post()`).
* Security - Shortcodes - `[alg_wc_ean_product_meta]` - Ensuring that only *product* meta is retrieved (`get_post_meta()` replaced with `$product->get_meta()`).
* Tested up to: 6.5.
* Readme.txt - Changelog - Truncated (full changelog moved to the `changelog.txt` file).

= 4.9.2 - 29/03/2024 =
* Dev - Admin products list column - "Show duplicates" option added (defaults to `yes`).
* WC tested up to: 8.7.
* Readme.txt - Tags updated.

= 4.9.1 - 01/02/2024 =
* Dev - Order Tools - "Search" tool added.
* Dev - Admin settings - Tools - Section split: "Order Tools" section added.

= 4.9.0 - 30/01/2024 =
* Dev - Advanced - Export/Import/Reset Plugin Settings - Import - Better data validation.
* WC tested up to: 8.5.

= 4.8.9 - 06/01/2024 =
* Dev - Orders - Now using "General > Title" for order item meta labels.

= 4.8.8 - 22/12/2023 =
* Dev - Display - Single product page - Variable products: Position in variation - Description - Now checking if EAN is not empty.
* WC tested up to: 8.4.

= 4.8.7 - 12/12/2023 =
* Dev – PHP 8.2 compatibility – "Creation of dynamic property is deprecated" notice fixed.

= 4.8.6 - 29/11/2023 =
* Dev - General - "Checkout" option added (defaults to `no`).

= 4.8.5 - 28/11/2023 =
* Dev - General - Cart - "Template" option added.

= 4.8.4 - 27/11/2023 =
* Fix - Pro - Print - Print buttons: Single order - HPOS compatibility.
* WC tested up to: 8.3.

= 4.8.3 - 09/11/2023 =
* Dev - Tools - Product Tools - Copy to product meta - "Meta sub key (optional)" option added.
* Tested up to: 6.4.

= 4.8.2 - 20/10/2023 =
* Dev - REST API - Orders - Add EAN to each order object in REST API responses - Extra checks added to prevent possible PHP warning.

= 4.8.1 - 19/10/2023 =
* Dev - Admin settings - General - Admin product edit page - Add pattern - Description updated.
* WC tested up to: 8.2.

= 4.8.0 - 05/10/2023 =
* Dev - General - Admin product edit page - Add pattern - Default value changed to `no`.

= 4.7.9 - 25/09/2023 =
* Plugin icon, banner updated.

= 4.7.8 - 25/09/2023 =
* Fix - Admin settings - Compatibility - Google Listings & Ads - Typo fixed.

= 4.7.7 - 19/09/2023 =
* Dev - Compatibility - Google Listings & Ads - Different approach implemented.

= 4.7.6 - 15/09/2023 =
* Dev - Search - Code refactoring.
* Dev - Pro - Extra Fields - "Admin product search" options added (default to `no`).
* Dev - Pro - Extra Fields - "Search" (frontend) options added (default to `no`).
* WC tested up to: 8.1.

= 4.7.5 - 05/09/2023 =
* Dev - Compatibility - "Google Listings & Ads" option added.
* Dev - Developers - `alg_wc_ean_product_structured_data_value` filter added.
* Dev - Developers - `alg_wc_ean_product_structured_data_markup_value` filter added.
* Dev - Developers - `alg_wc_ean_product_structured_data_allow_empty_value` filter added.

= 4.7.4 - 30/08/2023 =
* Dev - Pro - Print - Cell - "Cell top/left/right/bottom margin" options added (all default to `0`).
* Dev - Pro - Print - Cell - "Cell content alignment" option added.
* Dev - Pro - Print - Admin settings descriptions updated.

= 4.7.3 - 23/08/2023 =
* Fix - Declaring HPOS compatibility for the free plugin version, even if the Pro version is activated.
* Dev - Compatibility - "MultiVendorX" options added.
* Dev - Admin settings - Advanced - Meta key - Description updated.

= 4.7.2 - 09/08/2023 =
* Fix - Tools - Product Tools - Assign from the list - Product categories - Variations - Checking for the `variable` product type.
* Dev - Tools - Product Tools - Assign from the list - Product categories - Variations - Ensuring that products are always sorted by ID (ASC).
* Tested up to: 6.3.
* WC tested up to: 8.0.

= 4.7.1 - 15/07/2023 =
* Fix - Search / Admin product search / Admin products list column (sorting) - Handling cases when `query['post_type']` is an array.

= 4.7.0 - 13/07/2023 =
* Dev - "EAN-14" type added.
* Dev - Code refactoring.

= 4.6.0 - 23/06/2023 =
* Dev - Display - Shortcodes are now processed in the "Single product page" and "Shop pages" options.
* Dev - Display - Shop pages - "Template" option added. Defaults to `EAN: %ean%`.
* Dev - Tools - Product Tools - Copy from product attribute - "Custom product attribute" option added.
* Dev - Shortcodes - `[alg_wc_ean_if]` shortcode added.
* Dev - Shortcodes - `[alg_wc_ean_if_product_cat]` shortcode added.
* Dev - Shortcodes - `[alg_wc_ean_if_product_tag]` shortcode added.
* Dev - Shortcodes - `[alg_wc_ean_product_terms]` shortcode added.

= 4.5.1 - 18/06/2023 =
* WC tested up to: 7.8.

= 4.5.0 - 07/06/2023 =
* Dev – "High-Performance Order Storage (HPOS)" compatibility.
* Dev - Admin Settings - Option descriptions updated.
* Dev - Code refactoring.
* WC tested up to: 7.7.

= 4.4.6 - 02/05/2023 =
* Dev - Shortcodes - `[alg_wc_ean_is_valid]` shortcode added.
* Dev - Shortcodes - `[alg_wc_ean_is_unique]` shortcode added.
* Dev - Compatibility - Dokan - "Description" option added.
* Dev - Compatibility - Dokan - "Required HTML" option added.
* WC tested up to: 7.6.

= 4.4.5 - 06/04/2023 =
* Fix - Display - Frontend hooks (including barcodes) now are loaded on AJAX as well.
* Dev - Developers - REST API - `alg_wc_ean_rest_api_product_ean_key` filter added.
* Dev - Developers - REST API - `alg_wc_ean_rest_api_order_ean_key` filter added.
* Dev - Admin Settings - General - Option descriptions updated.
* Tested up to: 6.2.
* WC tested up to: 7.5.

= 4.4.4 - 02/02/2023 =
* Dev - Developers - Admin product search - `alg_wc_ean_search_backend` filter added.
* Dev - Developers - Search - `alg_wc_ean_search` filter added.
* WC tested up to: 7.3.

= 4.4.3 - 10/01/2023 =
* Dev - Shortcodes - `[alg_wc_ean_product_image]` - Security - `height` and `width` attributes are escaped now.
* WC tested up to: 7.2.

= 4.4.2 - 26/11/2022 =
* Dev - Tools - Product Tools - "Copy from product attribute" tool added.

= 4.4.1 - 25/11/2022 =
* Dev - Compatibility - Dokan - "Required" option added (defaults to `no`).
* WC tested up to: 7.1.
* Tested up to: 6.1.

= 4.4.0 - 20/10/2022 =
* Dev - General - Admin product edit page - "Require" option added. Defaults to `no`.
* Dev - General - Admin product edit page - "Add pattern" option added. Defaults to `yes`.
* Dev - Advanced - JavaScript Variation Options - "Variations form" option added. Defaults to `.variations_form`.
* WC tested up to: 7.0.

= 4.3.4 - 28/09/2022 =
* Dev - General - Admin product edit page - "Position (variation product)" option added. Defaults to "Variations: After pricing".
* WC tested up to: 6.9.

= 4.3.3 - 08/09/2022 =
* Fix - Tools - Product Tools - Assign from the list - Product categories - Variations category filtering fixed.

= 4.3.2 - 29/08/2022 =
* Dev - REST API - Products - Add EAN to each product object in REST API responses - EAN added to variation responses as well.

= 4.3.1 - 15/08/2022 =
* Dev - Pro - Print/Barcode Generator - Now checking if classes exist before including the TCPDF library.
* WC tested up to: 6.8.

= 4.3.0 - 03/08/2022 =
* Dev - REST API - Products - Search by EAN - Now including product variations as well.
* Dev - Pro - Print - Print Tools - "Products List" tool added.
* Dev - Pro - Print - Admin Options - Print buttons - Quantity input - "Products > Bulk actions > Each product" option added.
* Dev - Pro - Extra Fields - Admin settings section description updated.

= 4.2.0 - 27/07/2022 =
* Dev - Compatibility - "Woocommerce OpenPos" compatibility added ("EAN" field is now available in "POS > Setting > Barcode Label > Barcode Meta Key").
* Dev - Compatibility - WooCommerce PDF Invoices & Packing Slips - "Content" options added. Includes barcodes.
* Dev - Pro - Code refactoring:
    * Barcode Generator.
    * Shortcodes - `[alg_wc_ean_barcode]` and `[alg_wc_ean_barcode_2d]`.
* WC tested up to: 6.7.

= 4.1.2 - 17/06/2022 =
* Dev - Tools - Product Actions - Bulk actions - "Require confirmation?" option added (defaults to "Delete EAN").
* Fix - Pro - Print - Print Tools - Quantity - Quantity input was ignored when products had identical EANs.
* Dev - Pro - Print - Admin Options - Print buttons - "Quantity input" option added.

= 4.1.1 - 16/06/2022 =
* Fix - Admin product search - Including all post statuses now (e.g., drafts).
* Dev - Tools - Product Tools - Copy to product meta - Meta key - Comma-separated list of keys is now accepted.

= 4.1.0 - 16/06/2022 =
* Dev - Tools - Product Tools - "Copy to product meta" tool added.
* Dev - Shortcodes - `[alg_wc_ean_product_sku]` - Optional `max_length` attribute added.
* Dev - Pro - Print - Print Tools - "Print" tool added.
* Dev - Pro - Developers - Print - `alg_wc_ean_print_get_products` filter added.
* WC tested up to: 6.6.

= 4.0.0 - 10/06/2022 =
* Dev - General - Admin product edit page - 'Add "Generate" button' option added.
* Dev - Compatibility - WCFM - Variations are supported now.
* Dev - Compatibility - WCFM - 'Add "Generate" button' option added.
* Dev - Advanced - Export/Import Plugin Settings - "Reset" tool added.
* Dev - Advanced - Export/Import Plugin Settings - Code refactoring.
* Dev - Shortcodes - `[alg_wc_ean_product_author_id]` shortcode added.
* Dev - Developers - Tools - Product Tools - Generate:
    * `alg_wc_ean_product_tools_generate_ean_country_prefix` filter added.
    * `alg_wc_ean_product_tools_generate_ean_seed_prefix` filter added.
    * `alg_wc_ean_product_tools_generate_ean_seed` filter added.
* Dev - Pro - "Extra Fields" sections added.
* Dev - Pro - Shortcodes - Barcodes - `img_w` and `img_h` attributes added (both defaults to `false`).

[See changelog for all versions](https://plugins.svn.wordpress.org/ean-for-woocommerce/trunk/changelog.txt).

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
