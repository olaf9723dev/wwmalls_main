=== Invoicing with InvoiceXpress for WooCommerce - Free ===
Contributors: webdados, ptwooplugins
Tags: ecommerce, invoicexpress, woocommerce, invoice, billing, e-commerce, warehouses, invoiceexpress, factura, fatura, invoices, facturas, faturas, invoicing, facturação, faturação, simplified, simplificada, proforma, orçamento, quote, credit note, nota de crédito, guia de transporte, shipping, delivery note, backend, erp, dropshipping
Author URI: https://ptwooplugins.com
Plugin URI: https://invoicewoo.com
Stable tag: 5.6
Tested up to: 6.5

WooCommerce legal invoicing made easy with InvoiceXpress integration.
Facturação legal em Portugal com o InvoiceXpress.

== Description ==

Faturação legal em Portugal com o WooCommerce e com o InvoiceXpress.

“Invoicing with InvoiceXpress for WooCommerce - Free” allows you to easily create legal invoices for your WooCommerce orders using InvoiceXpress, directly on your store dashboard, and send them via email to the client.

InvoiceXpress is an online invoicing software approved by the Portuguese Tax and Customs Authority, which can be accessed from anywhere via a browser. Although it was developed with the Portuguese market in mind, it can also be used by store owners from countries worldwide. You can [sign up for a 30-day free trial or get a 20% discount here](https://invoicexpress.com?fpr=webdados10).

This plugin is not an invoicing system on its own. You’ll need to [subscribe to an InvoiceXpress plan (20% discount with this link)](https://invoicexpress.com?fpr=webdados10) and use this plugin to issue legal invoices directly from WooCommerce.

= Features: =

* Invoices and Simplified invoices;
* Manual issuing of documents;
* Send documents via email;
* Custom emails by document type;
* VAT field on the checkout;
* Issue VAT exempt documents;
* Not suitable for international orders;
* WooCommerce High-Performance Order Storage compatible;
* WooCommerce Checkout Block compatible (in beta);
* Very limited technical support (bug fixing only);

= Pro version features: =

In addition to all you can do with the free plugin, the [paid version](https://invoicewoo.com) offers you a number of additional features.

* Technical support;
* Invoices, Simplified invoices, Receipts, Invoices-receipt, VAT MOSS invoices, Credit notes, Quotes, Proformas, Delivery notes, and Return delivery notes;
* Automatic issuing of documents, on configurable order status;
* Compliance with the new EU VAT for cross-border e-commerce rules;
* Automatic documents scheduling;
* Leave documents as Draft on InvoiceXpress;
* Documents cancelation;
* List documents on My Account - Orders;
* Send documents using the InvoiceXpress API for better performance;
* Issue documents in Portuguese, English or Spanish;
* Choose default documents invoicing sequence;
* Choose order specific documents invoicing sequence;
* Choose default unit of measurement: Product or Service;
* [WooCommerce EU VAT Assistant by Aelia](https://wordpress.org/plugins/woocommerce-eu-vat-assistant/) integration for EU VAT exemption on B2B;
* [EU VAT Number by WooCommerce](https://woocommerce.com/products/eu-vat-number/) (experimental) integration for EU VAT exemption on B2B;
* Default warehouse (load site) address and licence plate for delivery notes;
* Issuance of guides without values;
* Send Delivery notes directly to the warehouse or the dropshipping supplier;
* WooCommerce order item meta on the InvoiceXpress item description;
* Prevent issuing of incorrect documents if non-standard coupons are used;
* [WPML](https://wpml.org/)/[WooCommerce Multilingual](https://wordpress.org/plugins/woocommerce-multilingual/) integration for multilingual stores;
* [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) compatible;
* Developer license for up to 10 WooCommerce stores;
* [Extensions](https://invoicewoo.com/extensions/)

Check out all the Pro features on the [plugin website](https://invoicewoo.com/features-and-pricing/)

== Installation ==

* Use the included automatic install feature on your WordPress admin panel and search for “Invoicing InvoiceXpress WooCommerce”.
* Go to “WooCoomerce > InvoiceXpress Free” and fill in the subdomain and API key provided by InvoiceXpress on their dashboard (Account > Integrations > API).
* Setup the WooCommerce taxes according to your InvoiceXpress taxes settings - [check the documentation](https://invoicewoo.com/documentation/installation-guide/setting-up-woocommerce-taxes/).

== Frequently Asked Questions ==

= Is this plugin an invoicing software? =

No. This is just an integration between WooCommerce and the InvoiceXpress API. You need to subscribe to the InvoiceXpress service.

= Can I use this plugin to comply with the new EU VAT for cross-border e-commerce rules? =

No. The free plugin is not suitable for international orders. You need to use our [Pro version](https://invoicewoo.com).

= Can you provide free support for this plugin? =

Most probably not, unless it’s a very specific issue, under our consideration, and never via email or phone call.

We welcome bug reports, using the [forum](https://wordpress.org/support/plugin/woo-billing-with-invoicexpress/), and will do anything in our power to fix them for you, but it’s impossible to provide free support on such complex software like an invoicing plugin is.

This is also why our [Pro version](https://invoicewoo.com) has a yearly subscription instead of a one-time fee. The Pro plugin will not stop working if you decide not to renew your subscription, but you’ll stop receiving automatic updates and included technical support. That’s the only way we can ensure continued development and keep up to date with WordPress and WooCommerce changes over time.

= Where do I report security vulnerabilities found in this plugin? =  
 
You can report any security bugs found in the source code of this plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/woo-billing-with-invoicexpress). The Patchstack team will assist you with verification, CVE assignment and take care of notifying the developers of this plugin.

= Can I contribute with a translation? =

Sure. Go to [GlotPress](https://translate.wordpress.org/projects/wp-plugins/woo-billing-with-invoicexpress) and help us out.

== Changelog ==

= 5.6 - 2024-04-17 =
* [FIX] Integration with [Shop as Client](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/) on the blocks checkout (Pro version)

= 5.5 - 2024-04-03 =
* [FIX] Issuing of automatic invoices since version 5.4 (Pro version)

= 5.4 - 2024-04-01 =
* [FIX] Deprecated: Creation of dynamic property in PHP 8.3
* [FIX] Avoid fatal error when calling wc_add_notice from inside our namespace
* [TWEAK] Updater improvements (Pro version)
* [DEV] Tested with WordPress 6.5-RC4-57894 and WooCommerce 8.8.0-beta.1

= 5.3 - 2024-03-13 =
* [FIX] Fix some i18n strings, one of them causing a fatal error

= 5.2 - 2024-03-07 =
* [DEV] Add WooCommerce plugin identification on the API calls to InvoiceXpress for filtering on the web app
* [DEV] Add "Requires Plugins" header to the main plugin file
* [TWEAK] Remove outer DIV from field on the frontend blocks checkout to get the correct margins set by default
* [TWEAK] InvoiceXpress logo in SVG
* [DEV] Improve plugin updater - Show translation update notices (Pro version)
* [DEV] Hide License key and API fields from Shop Managers (Pro version)
* [DEV] Tested with WordPress 6.5-beta3-57747 and WooCommerce 8.7.0-rc.1

= 5.1 - 2024-02-18 =
* [DEV] Change main folder name from src to includes to fix javascript translation files generation on Traduttore (Pro version)
* [DEV] Make sure AutomateWoo does not crash when adding a "Trigger Order Action" action on workflows
* [DEV] Simplify block build process and implement SVG accessibility attributes
* [TWEAK] Improve i18n including placeholders and translators comments
* [DEV] Tested with WordPress 6.5-beta1-57650 and WooCommerce 8.6.0

= 5.0 - 2024-02-01 =
* [NEW] Block-based Checkout compatibility (in beta)
* [NEW] Show VAT number on the order received / thank you page
* [TWEAK] Updater improvements
* [DEV] Declare constants for meta fields
* [DEV] Requires WooCommerce 6.0
* [DEV] Tested with WordPress 6.5-alpha-57378 and WooCommerce 8.6.0-beta.1

= 4.5 - 2024-01-23 =
* Better feedback when the license is expired (Pro version)
* Update plugin translations online instead of shipping them with the main plugin (Pro version)
* Tested with WordPress 6.5-alpha-57299 and WooCommerce 8.5.1

= 4.4 - 2023-11-20 =
* Fix documents email string translations when WPML is active (Pro version)

= 4.3 - 2023-11-20 =
* New filter `invoicexpress_woocommerce_order_should_be_vat_exempt` to allow manipulating that the order should be VAT exempt or not
* New action `invoicexpress_woocommerce_before_auto_invoice_check` to allow manipulating the order before checking if automated documents should be issued (Pro version)
* Fix PHP notice when checking for client VAT number on InvoiceXpress (Pro version)
* Requires WordPress 5.6
* Tested with WordPress 6.5-alpha-57127 and WooCommerce 8.3.0

= 4.2 - 2023-10-13 =
* Filename prefix when sending the document email in WooCommerce mode (Pro version)
* New filter `invoicexpress_woocommerce_automatic_invoice_type` to allow changing the automatic document type before issuing (Pro version)
* Tested with WordPress 6.4-beta2-56809 and WooCommerce 8.2.0

= 4.1 - 2023-08-01 =
* Apply default tax exemption for partially exempted orders only if the lines without vat have a value (not zero) (Pro version)
* Better automatic documents workflow by detecting the need to issue the documents sooner (Pro version)
* Get the InvoiceXpress existing customer when trying to create it with the same code  (Pro version)
* Better debug
* Small fix on the Portuguese translation
* Tested with WordPress 6.3-RC2-56328 and WooCommerce 8.0.0-beta.2

= 4.0 - 2023-07-19 =
* High-Performance Order Storage for WooCommerce compatibility (in beta)
* Full CRUD compatibility
* New PO number field on orders (Pro version)
* New invoicexpress_woocommerce_vat_field_priority filter to allow changing the order of the VAT field on the checkout
* Remove VAT MOSS documents
* Unload unnecessary Javascript
* Fix the date-picker on the shipping date filed on orders (Pro version)
* Fix a bug where the default tax exemption reason was not applied for partially exempted orders
* Fix error handling in some edge situations
* Fix a jQuery deprecation
* Requires WooCommerce 5.8
* Tested with WordPress 6.3-beta4-56226 e WooCommerce 7.9.0-rc.3

= 3.7.1 - 2023-04-27 =
* New M26 (Isenção de IVA com direito à dedução no cabaz alimentar - Lei n.º 17/2023, de 14 de abril) exemption code
* Tested with WooCommerce 7.6.0

= 3.7.0 - 2023-04-11 =
* Add experimental support for the WooCommerce European (EU, UK, etc.) VAT / IVA Compliance premium plugin (Pro version)
* Deprecated support for the Aelia WooCommerce EU VAT Assistant free plugin (Pro version)
* Remove vat exemption codes not used in 2023
* Tested with WordPress 6.3-alpha-55644 and WooCommerce 7.6.0-rc.2

= 3.6.0 - 2023-01-04 =
* New exemption motives for 2023
* Translation updates
* Tested with WordPress 6.2-alpha-55027 and WooCommerce 7.3.0-rc.1

= 3.5.0 - 2022-11-06 =
* Avoid issuing incorrect documents when the order currency is not Euros
* Put in place a filter to allow the [Multi-currency extension](https://invoicewoo.com/extensions/) to invoice non-Euros orders (Pro version)
* Fix jQuery deprecations
* Fix &nbsp; in item names
* Declare WooCommerce High-Performance Order Storage incompatibility (for now)
* Tested with WordPress 6.2-alpha-54748 and WooCommerce 7.1

= 3.4.7 - 2022-10-20 =
* Fixed a bug when the order has unknown coupons (Pro version)
* Added WooCommerce Subcriptions coupons to the list of known coupons (Pro version)
* Removed code for legacy WooCommerce support
* Tested with WooCommerce 7.1.0-beta.1 and WordPress 6.1-RC2-54648

= 3.4.6 - 2022-10-03 =
* Update information about the InvoiceXpress discount: [Get 20% discount on any invoicing plan by registering with this link](https://invoicexpress.com?fpr=webdados10)
* Tested with WooCommerce 7.0.0-beta.3 and WordPress 6.1-beta2-54366

= 3.4.5- 2022-09-02 =
* Fix a bug where partial credit notes would not be invoiced (Pro version)

= 3.4.4 - 2022-08-02 =
* Fix a bug where documents with negative fees would be invoiced
* Requires WordPress 5.0 and WooCommerce 4.0
* Tested with WooCommerce 6.8.0-rc.1 and WordPress 6.1-alpha-53810

= 3.4.3 - 2022-06-24 =
* [Get 20% discount on the InvoiceXpress plan by registering with this link](https://invoicexpress.com?fpr=webdados10)
* [Get 20% discount on our Pro Plugin by buying with this link](https://ptwooplugins.com/coupon/upgrade)
* Better debug
* Show order number instead of order ID on automation failure emails
* Tested with WooCommerce 6.7.0-beta.1 and WordPress 6.1-alpha-53556

= 3.4.2 - 2022-06-09 =
* Possibility to save the document QR code URL, as order meta, by passing true to the `invoicexpress_woocommerce_get_document_qr_code` filter (Pro version)
* Save SAFT hash as order meta
* Bugfix on API PUT requests
* Tested with WooCommerce 6.6.0-rc.2 and WordPress 6.1-alpha-53479

= 3.4.1 - 2022-05-25 =
* Fix when changing document status after the changes on the InvoiceXpress of May 24
* Fix a PHP notice after WooCommerce 6.5.0
* Documentation and FAQ links next to the license key (Pro version)
* Tested with WooCommerce 6.6.0-beta.1 and WordPress 6.0

= 3.4.0 - 2022-05-05 =
* New brand: PT Woo Plugins 🥳
* Fix a php deprecation (Pro version)

= 3.3.2 - 2022-05-04 =
* May the 4th be with you
* Fix some countries’ translations
* Bugfix on the WooCommerce EU VAT Number plugin integration (Pro version) - Thanks itemzero.com
* Update routines fix (Pro version)
* Tested with WooCommerce 6.5.0-rc.1 and WordPress 6.0-beta2-53236

= 3.3.1 - 2022-01-27 =
* Fix randomness to the documents file names, retroactively for documents saved before version 2.8.3

= 3.3.0 - 2022-01-27 =
* Issue VAT MOSS invoice even if there’s no VAT in the order, for example, books delivered to Ireland (Pro version)
* New `invoicexpress_woocommerce_eu_vat_countries_for_oss` filter to be able to manipulate the countries the VAT MOSS invoice should be issued to (Pro version)
* Option to delay the sending of the Delivery Notes email, only after AT Code is fetched if it’s set to be done after one minute (Pro version)
* Replace `is_ajax()` with `wp_doing_ajax()` to avoid PHP notices on WooCommerce 6.1.0
* Fix fatal error checking for checkout page in some edge situations
* Replace some problematic characters on the item description
* Tested with WooCommerce 6.2.0-rc.1 and WordPress 6.0-alpha-52640

= 3.2.2 - 2021-07-02 =
* Issue VAT MOSS Credit notes instead of regular Credit notes for VAT MOS invoices (Pro version)

= 3.2.1 - 2021-07-01 =
* Fix Credit notes for VAT MOSS invoices (Pro version)
* Allow issuing of VAT MOSS invoices for non-Portuguese stores (Pro version)

= 3.2.0 - 2021-06-30 =
* Compliance with the new [EU VAT for cross-border e-commerce rules](https://www.webdados.pt/2021/06/configurar-a-integracao-do-woocommerce-com-o-invoicexpress-para-as-novas-regras-do-iva-para-vendas-a-distancia/) (Pro version - The Free version doesn’t have support for VAT MOSS Invoices)
* Make sure only administrators or store managers can redownload PDFs or request the AT Code
* Fix a bug on automatic receipts for Simplified invoices and VAT MOSS invoices (Pro version)
* Better debugging
* Tested with WooCommerce 5.5.0-rc.1 and WordPress 5.8-beta4-51251

= 3.1.0 - 2021-06-07 =
* Invoice shipping costs as “other” instead of “service”
* Add country prefix to the VAT number (Pro version)
* Avoid automatic invoicing for older orders (Pro version)
* Allow using the same InvoiceXpress contact, even if there’s no VAT number associated with it, by returning true to the new `invoicexpress_woocommerce_allow_contact_reuse_without_vat` filter (Pro version)
* Fix email notification when failing to send the document via the InvoiceXpress method (Pro version)
* Several fixes and improvements on the pending errors screen (Pro version)
* Moved the `sendErrorEmail`, `set_email_to_html` and `get_blogname` methods from the Base Controller to the Plugin class for more flexibility
* Settings screen reorganization and technical support terms and conditions link (Pro version)
* Add `Update URI` header (Pro version)
* Complete VAT Controller code refactoring
* Remove legacy code
* Tested with WooCommerce 5.4.0-rc.1 and WordPress 5.8-alpha-51064

= 3.0.4 - 2021-04-14 =
* Fix a bug on the PDF fetching routine introduced in the last version

= 3.0.3 - 2021-04-12 =
* Remove built-in PHP Curl Class and use the WordPress HTTP API exclusively
* Fix PHP notice while getting order item tax name
* Fix a bug on the redownload pdf class
* Remove code to support old Hellodev Woocommerce Inventory Manager plugin
* Sanitize request data

= 3.0.2 - 2021-04-06 =
* Bugfix when issuing Delivery notes and Return delivery notes after an Invoice (Pro version)

= 3.0.1 - 2021-04-05 =
* Better handling of tax names
* Unify the method of getting order items also for Credit notes (Pro version)
* Remove the `invoicexpress_woocommerce_shipping_and_fee_ref_unique` filter
* Small readme.txt adjustments

= 3.0.0 - 2021-04-03 =
* Possibility to fix pending errors, or dissociate them from the order so that they can be issued again, for these document types: Invoice-receipt, Invoice, Simplified invoice, VAT MOSS invoice, Proforma, Quote, Delivery note, and Return delivery note (Pro version)
* New option to get AT Code after 1 minute (using the WordPress cron) instead of immediately (Pro version)
* New action `[invoicexpress_woocommerce_fetch_at_code](https://gist.github.com/webdados/7f61dc21cdbbb9cd750c3a81397ef916)` to be able to programmatically fetch the AT Code for an order (Pro version)
* Add order note with a link to fetch AT Code after a Delivery note is issued (Pro version)
* Better automation error emails (Pro version)
* Added `$order_object` and `$error_message` to the automation error emails subject, heading, and body filters (Pro version)
* Fix negative values for fees and shipping on Credit Notes
* Code refactoring and optimization
* Several minor fixes
* Tested with WordPress 5.8-alpha-50650 and WooCommerce 5.2.0-rc.1

= 2.8.4 - 2021-02-23 =
* Delete sequences and default taxes options when the InvoiceXpress API account is changed
* New `invoicexpress_woocommerce_pro_is_valid_order_type` filter to be able to stop invoicing, without an error, for example for marketplace sub-orders (Pro version)
* Fixed shipping and fee costs being sent as strings and not floats to the InovoiceXpress API
* Tested with  WooCommerce 5.1.0-beta.1 and 5.7-beta3-50388

= 2.8.3 - 2020-12-30 =
* Add randomness to the documents file names
* Remove Great Britain and Isle of Man from EU VAT countries, from 2021 January 1st onwards, for VAT exemption and VAT MOSS issuing proposes (Pro version)
* Tested with  WooCommerce 4.9.0-rc.1 and WordPress 5.7-alpha-49862
* And a Happy New Year!

= 2.8.2 - 2020-12-17 =
* Avoid issuing documents for orders with negative valued items
* Better logic when showing the “Issue Receipt” order action (Pro version)
* Get support link on Updater.php so it can be used by all extensions (Pro version)
* Make sure to not issue a Credit note if an Invoice document does not exist which is odd because we were already looking for a Receipt (Pro version)
* Modules `register_hooks()` now run on `after_setup_theme` to allow the theme functions.php file hook into any filters available
* New `invoicexpress_woocommerce_allow_{$document_type}` filters to be able to deactivate some documents (even if activated on the options) via filters. Caution: It can break automatic documents (on the Pro version) if the `current_user_can()` function is used to determine returning `false` to the filter.
* Fix order date replacements on emails when WPML is active and order language is not the same as the current WordPress language
* Tested with WooCommerce 4.8.0 and WordPress 5.7-alpha-49782

= 2.8.1 - 2020-11-11 =
* Remove manual Receipt order action if “Issue automatic receipt” is set (Pro version)
* Bugfix when loading new customer data on the order edit screen
* Bugfix when adding order item meta to the invoicing title (Pro version)
* Tested with WooCommerce 4.7.0 and WordPress 5.6-beta1-49314

= 2.8.0 - 2020-10-09 =
* Tracking of document finalization and email sending status, and the possibility to fix it, when the InvoiceXpress API times out, on a new “Pending errors” screen (Pro version - experimental, only for Invoice-receipts)
* Choose automatic document errors email address instead of always using the WordPress admin email (Pro version)
* Fully discontinue the bulk invoices functionality as announced on 2019-10-11 (Pro version)
* Fully discontinue the “Update order status” functionality as announced on 2019-11-06 (Pro version)
* Soft discontinue the “Document cancelation” functionality (Pro version)
* Option to remove license key, if `WP_DEBUG` is set to true and for technical support proposes only (Pro version)
* Slightly change the document observations field position on the order edit screen
* Fix fatal error on the WCFM Marketplace frontend order edit screen
* Fix invoicing entity when the “General” tab options are not yet saved
* Better debugging
* PHP required version raised to 7.0
* Tested with WooCommerce 4.6.0-rc.1 and WordPress 5.6-alpha-49064

= 2.7.1.1 - 2020-06-12 =
* Better PDF naming - Fix file extension

= 2.7.1 - 2020-06-12 =
* Better PDF naming

= 2.7.0 - 2020-05-28 =
* Several changes on the “Invoices and Credit notes” settings to better clarify that Invoice-receipts are recommended in most scenarios
* New exemption motive for COVID-19 related sales to the Portuguese State, other public organisms and non-profits
* Allow issuing of partial Credit notes for order shipping and fees (Pro version)
* Fix Credit notes shipping and fees references (Pro version)
* Show exemption reason field on orders with partial exemptions (Pro version)
* Add `$order_object` to the `get_due_date` function and `invoicexpress_woocommerce_{$type}_validity` filter
* Tested with WooCommerce 4.2.0-beta.1 and WordPress 5.5-alpha-47861

= 2.6.2.2 =
* Fix static `VatController` `get_exemption_reasons()` method

= 2.6.2 =
* Exemption reasons code refactor
* New `invoicexpress_woocommerce_after_update_order_meta_frontend` action after updating order meta in the checkout (Pro version)
* EU B2B tax exemption motive setting (Pro version)
* Tested with WooCommerce 4.1.0 and WordPress 5.5-alpha-47783

= 2.6.1 =
* Partial Receipts on Invoice-receipts (Pro version)
* Better customer search on InvoiceXpress to avoid duplicates (Pro version)
* Tested with WooCommerce 4.1.0-rc.1 and WordPress 5.5-alpha-47609

= 2.6.0.1 =
* Bugfix when products included as order items are deleted before a document is issued

= 2.6.0 =
* It’s now possible to issue VAT MOSS Invoices (Pro version - experimental)
* Fix `get_eu_vat_countries` after WooCommerce 4.0.0 (Pro version)
* Tested with WooCommerce 4.1.0-beta.1 and WordPress 5.5-alpha-47547

= 2.5.3.1 =
* Tested with WooCommerce 4.0.0

= 2.5.3 =
* WooCommerce Subscriptions integration bugfix: VAT code must be copied to renewals (Pro version)
* Unique references for Shipping and Order Fees (can be reverted by returning false to `invoicexpress_woocommerce_shipping_and_fee_ref_unique`)
* New filters to allow developers do add other items to the document based on the last added one: `invoicexpress_woocommerce_items_after_document_item_add`, `invoicexpress_woocommerce_items_after_document_shipping_add`, `invoicexpress_woocommerce_items_after_document_fee_add`

= 2.5.2.1 =
* Fix invoicing when WooCommerce Admin is active (Pro version)

= 2.5.2 =
* WooCommerce Subscriptions compatibility (Pro version)
* Check if the order is valid to avoid issuing documents for special types of orders like Subscriptions, for example (Pro version)

= 2.5.1.1 =
* Change plugin website

= 2.5.1 =
* Bugfix for WordPress versions below 5.0

= 2.5 =
* Move VAT Field options to the “Taxes” tab
* Code refactoring: WooCommerce EU VAT Assistant and EU VAT Number integration (Pro version)
* Prevent automatic invoices for orders with tax exemption (Pro version)
* Check for table and create it if needed on the scheduled documents cron
* Bugfix: Credit notes were being created even if the order didn’t had a Receipt (Pro version)
* Tested with WooCommerce 3.9.0-rc.2

= 2.4.10.2 =
* Spanish translation corrections - thanks [Fernando Tellado](https://tellado.es/)
* Better translators descriptions

= 2.4.10 =
* Get delivery guide AT Code after getting the PDF instead of before (Pro version)
* Show our notices when WooCommerce Admin 0.23.2 (or above) is active
* Spanish translation - thanks [Caribay Camacho](https://caribaycamacho.com/)
* Tested with WooCommerce 3.9.0-beta.2 and WordPress 5.3.3-alpha-46995

= 2.4.9.1 =
* Small tweaks on the version upgrade routine
* Tested with WooCommerce 3.9.0-beta.1 and WordPress 5.3.1-alpha-46771

= 2.4.9 =
* Show updates for extensions on the API settings page (Pro version)
* Use Select2 on settings select fields
* Fix multiselect settings field and allow for `optgroup` on settings select fields

= 2.4.8 =
* Fix multiselect settings fields
* Pass manual or automatic mode to the preventDocumentIssuing method thus allowing preventing the issuing of documents based on that
* Add the reason the document issuing was prevented as an order note
* Tested with WordPress 5.3.1-alpha-46771

= 2.4.7
* Get delivery guide AT Code (Pro version)
* Change ReDownload PDF behavior
* Tested with WordPress 5.3.0

= 2.4.6 =
* Fix a bug where Receipts would not be sent by email on InvoiceXpress PDF file mode (Pro version)
* Move “Automatic document errors email” to the General settings (Pro version)
* Soft discontinue “Update order status” (Pro version)
* Better email sending debug
* Tested with WooCommerce 3.8.0 and WordPress 5.2.5-alpha

= 2.4.5 =
* Automatic receipts can now be issued on a different status (Pro version)

= 2.4.4 =
* Automatic documents tweaks and better debug (Pro version)
* Allow developers to silently prevent document issuing
* Code clean-up and reuse

= 2.4.3 =
* New `invoicexpress_woocommerce_before_document_email` action
* When the automatic receipts are active, do them before sending the Invoice email, so that the “Paid” badge of the new templates is shown on the PDF file (Pro version)
* Small Updater fix (Pro version)
* Soft discontinue bulk Invoices
* Check for updates on the API settings screen (Pro version)
* Use version constant instead of getting it from the database each time it’s needed
* Fix setting the receipt id and permalink on the order meta (Pro version)
* Fix PHP notice when sending email using the new hybrid mode (Pro version)
* Fix PHP notice when issuing a receipt (Pro version)
* Tested with WooCommerce 3.8.0-beta.1 and WordPress 5.2.4-alpha-46074

= 2.4.2 =
* Fix CSS loading on the settings page and improve field sizes
* Small fix when downloading the PDF file from InvoiceXpress
* Feedback when WordPress email sending fails (from WooCommerce 3.8.0 onwards)
* New hybrid Email/PDF mode (Pro version)
* Better explanation of the new Email/PDF methods (Pro version)
* Fix document type names on My Account (Pro version)
* Fix Automatic receipt issuing for Invoices and Simplified invoices (Pro version)

= 2.4.1 =
* Fix delivery guide email recipient (Pro version)
* Fix endpoint and global logo parameter when using the new InvoiceXpress email sending method (Pro version)
* Create an order note when the email is sent with the new InvoiceXpress email sending method (Pro version)
* Add a div id on the My Account documents list so that store owners can style it with CSS (Pro version)

= 2.4.0 =
* Better feedback when the document is issued (and finalized) on InvoiceXpress but the PDF could nout be downloaded (and sent to the client)
* Ability to repeat the download (and sending to the client) process when it fails the first time
* PDF fetching and sending code refactor
* Send documents using InvoiceXpress instead of WordPress (Pro version)
* List documents on My Account - Orders (Pro version)
* Load order.js, cancel.js and credit.js only on the order edit screen
* UX fix on the settings fields
* Load the default refund motive into the Reason for refund field (Pro version)
* Reason for refund on the Credit notes observations (Pro version)
* Warning when not using WP-Admin interface on the same language as the default WPML language, because translatable strings may get mixed up (Pro version)

= 2.3.1 =
* Fix bug where invoicing documents could be repeatedly issued after the order had a credit note
* Show our notices when WooCommerce Admin is active
* Fix bug issuing Quotes and Proformas (Pro version)

= 2.3.0 =
* Performance improvements on the settings pages
* New developer hooks
* Better version upgrade routines
* Better debugging
* Automatic documents scheduling (Pro version)
* Better licensing control (Pro version)

= 2.2.1 =
* Plugin version on scripts and styles
* New `invoicexpress_woocommerce_document_item`, `invoicexpress_woocommerce_document_shipping` and `invoicexpress_woocommerce_document_fee` developer filters to allow manipulation or exclusion of each document line
* Better feedback when the VAT number is missing and is required

= 2.2.0 =
* New option to set the default unit of measurement: Product or Service (Pro version)
* New `invoicexpress_woocommerce_document_item_unit`, `invoicexpress_woocommerce_document_shipping_unit` and `invoicexpress_woocommerce_document_fee_unit` filters to allow developers to change each document item unit of measurement
* WordPress 4.9 minimum requirement
* PHP 5.6 minimum requirement
* Translations update
* Tested with WooCommerce 3.7.0-beta.1

= 2.1.9 =
* Several changes on the licensing so that extensions can use it (Pro version)
* Third parties can now prevent the issuing of documents when using the `invoicexpress_woocommerce_{$type}_data` filters
* Fix on the plugin version update on the database
* Translations update
* Tested with WordPress 5.2.3-alpha-45552

= 2.1.8 =
* Try to avoid issuing duplicate automatic documents when PayPal has both IPN and PDT notifications active (Pro version)
* Fix PHP notice when creating new orders on wp-admin and automatic invoices or transport guides are activated (Pro version)
* Better tax exemption descriptions
* Better debugging
* Tested with WooCommerce 3.6.4 and WordPress 5.2.2-RC1

= 2.1.7 =
* Experimental partial VAT exemption
* Prevent automatic invoicing of orders without values, unless explicitly activated on the options (Pro version)
* Better error handling and feedback from the InvoiceXpress API
* Fix VAT exemption error for documents and items with no value
* Initial implementation of generic handling actions so that they can be logged by an external debug plugin
* Composer update and plugin initialization refactoring for better PHP 7.3 compatibility
* Tested with WooCommerce 3.6.3 and WordPress 5.2.1

= 2.1.6 =
* Bugfix on Bulk Invoices
* Bugfix on VAT on Shipping and Fees
* Tested with WooCommerce 3.6.2

= 2.1.5 =
* Use `wp_remote_get` instead of `fopen`
* Small bugfix on a string
* EU VAT Number by WooCommerce support (Pro version) for EU VAT exemption on B2B
* Tested with WooCommerce 3.6.0 (RC 1)

= 2.1.4.6 =
* Fix product description on some multilingual configurations
* Shipping and fee name now have a prefix
* Tested with WooCommerce 3.6.0 (beta 1) and WordPress 5.1.1

= 2.1.4.5 =
* Shot VAT field on the order admin screen even when “YITH WooCommerce Checkout Manager” is in use
* Do not show tax exemption field on the order admin screen when the order total is zero
* Code cleanup

= 2.1.4.4 =
* Bugfix when editing the VAT number on My Account - Billing Address
* Tested with WooCommerce 3.5.5 and WordPress 5.1

= 2.1.4.3 =
* Code cleanup

= 2.1.4.2 =
* Use `get_order_number()` instead of `get_id()` for documents reference, for compatibility with plugins that manipulate the order number
* New function to set the item description for all the document types
* New `invoicexpress_woocommerce_document_item_title` filter

= 2.1.4.1 =
* InvoiceXpress links fix

= 2.1.4 =
* New method and `invoicexpress_woocommerce_prevent_document_issuing` filter that allows developers to prevent the document issuing based on the order details

= 2.1.3 =
* Small tweaks
* Tested with WooCommerce 3.5.4

= 2.1.2 =
* Bugfix on the default tax exemption motive for Portuguese businesses exempt from taxes

= 2.1.1 =
* New filter on the documents filename: `invoicexpress_woocommerce_document_filename`
* Small adjustment on default documents filename
* Bugfix on Invoices-receipt email (Pro version)
* Tested with WooCommerce 3.5.3 and WordPress 5.0.3

= 2.1.0 =
* Future-proofing by using InvoiceXpress API v2 in JSON
* Document language now based on an option instead of client country (on the Pro version, the Free version always issues documents in Portuguese)
* Caching document sequences for a small boost in performance
* Complete refactoring of ClientChecker class and the way the client is identified on InvoiceXpress
* Code review and elimination of duplicate code on all the controllers
* Fix VAT number validation on the checkout and when issuing documents (both with our field and WooCommerce EU VAT Assistant on the Pro version)
* Translations update
* Tested with WordPress 5.0.2

= 2.0.8 =
* Use WooCommerce CRUD functions only to get and set metadata from and to orders
* Fix the VAT number, document sequence and delivery note fields on the order edit screen
* Bulk invoice tax exemption bugfix
* Fix on the JavaScript enqueues
* Code review
* New filter to disable WooCommerce EU VAT Assistant field status feedback on the checkout (Pro version)
* Fix sending Delivery notes directly to the warehouse or the dropshipping supplier (Pro version)
* Translations update

= 2.0.7.2 =
* Bugfix when upgrading database version could cause a fatal error on some situations

= 2.0.7.1 =
* Version number fix

= 2.0.7 =
* WPML initial support (Pro version)
* Tweak on the document emails’ placeholder replacements
* Store InvoiceXpress document sequence number on order meta and show it on notifications and order notes
* Fix Invoice-receipts and Quotes meta when storing document id and pdf link (Pro version)
* Fix Receipts notifications (Pro version)
* Fix Delivery and Return delivery notes translations (Pro version)
* Translations update

= 2.0.6 =
* Update translations
* Send Delivery notes directly to the warehouse or the dropshipping supplier (Pro version)
* Bugfix on error emails for Automatic invoices (Pro version)

= 2.0.5 =
* Add plugin version to the settings page title
* Propagate the `invoicexpress_woocommerce_after_document_issue` and `invoicexpress_woocommerce_after_document_finish` developer filters to all documents
* Fix some translations

= 2.0.4 =
* Code review
* Associate same order Invoices to Guides and vice versa on InvoiceXpress
* Translations update
* Refactor possible status and not recommended status for automatic documents (Pro version)
* WooCommerce EU VAT Assistant link update (Pro version)
* Fix automatic documents when a custom order status exist (Pro version)
* Fix Transport guide loaded time for automatic documents (Pro version)
* Fix automatic receipts notifications for shop admins (Pro version)

= 2.0.3 =
* Update translations
* Fix automatic documents notifications for shop admins (Pro version)
* Several Transport guides fixes (Pro version)
* Fix translation loading (Pro version)
* Tested with WordPress 5.0.1

= 2.0.2 =
* Fix textdomain so that the Free version uses the wordpress.org GlotPress

= 2.0.1 =
* Fix fatal error on specific situations when updating plugin database version

= 2.0.0 =
* Under new management.
* Changed the plugin name.
* Extensive code overhaul and bug fixing (can contain breaking changes).
* WooCommerce 3.x and PHP 7.x compatibility reviewed.
* Removed WooCommerce backward compatibility (below 3.x).
* Applied WordPress Coding Standards.
* Refactored the settings screen.
* Added email placeholders.
* Refactored email invoice fields.
* Option not to change order status when issuing documents (by default).
* Removed check for WooCommerce deprecated functions.
* Requirements check for `allow_url_fopen` and `curl`.
* Developer hooks (actions and filters).
* And much more...

= 1.2.8 =
* NEW Added document observations field.
* NEW Now able to select customer or company as documents entity.

= 1.2.6 =
* NEW FEATURE added option to use product ID or SKU on document code section.
* Email attachment bug fixed.

= 1.2.5 =
* VAT is now sent via order emails.
* Bug-fixes.

= 1.2.4 =
* Variations have different product description os documents.
* Products with SKU show their SKU instead of ID.
* Bug-fixes and tweaks.

= 1.2.3 =
* Added option to download again created documents.
* Small bug fixes.
* Moved uploads folder to WordPress default.
* Changed error messages.

= 1.2.2 =
* Added compatibility with WooCommerce EU VAT Assistant plugin.

= 1.2.1 =
* Product variations now show up on invoice item description.
* Small bug fixes.

= 1.2 =
* Fixed: it is now possible to create documents with different tax rate items.
* Small bug fixes.

= 1.1 =
* Fixed: it is now possible to create documents without VAT number
* Fixed: VAT format is validated in front-end to avoid errors.
* Bugfix of some features.

= 1.0 =
* First release
