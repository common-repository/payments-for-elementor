=== Payments for Elementor ===
Contributors: elegantmodules
Tags: Elementor, Stripe, payments
Tested up to: 5.3
Stable tag: 1.0.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Stripe payment forms from right within Elementor.

== Description ==

Payments for Elementor makes it possible to add simple payment forms anywhere on your website using the Elementor page builder. Payment forms are powered by Stripe. Simply connect your Stripe account to Payments for Elementor, add a payment form wherever you'd like, configure the payment details, and save.

Simply put, not everyone needs WooCommerce. E-commerce plugins for WordPress, such as WooCommerce and Easy Digital Downloads, are fantastic solutions for those who need their features. If you need a shopping cart, advanced product management, or shipping integrations, you may want to consider them.

Payments for Elementor allows for creating simple payment forms, and placing them where you need. That said, Payments for Elementor does include a number of handy features that you also get with larger e-commerce plugins.

Included with Payments for Elementor is a simple dashboard for viewing and managing your payments, including the ability to issue full or partial refunds.

Payments for Elementor is new and will continue to grow in the features it offers. If you’re comfortable with code, though, Payments for Elementor includes plenty of hooks for customizing the behavior, and adding new functionality.

To be able to offer Payments for Elementor for free, and to support all of our customers, we offer two different payment options.

1. 5% per transaction - No upfront costs, no annual renewals. Just a simple, low price of 5% per transaction processed through Payments for Elementor (in addition to normal Stripe fees).
2. Yearly license - By activating a license for Payments for Elementor, you will pay 0 additional fees (just the normal Stripe fees).

Our philosophy is that if you're not making money using Payments for Elementor, we don't charge you anything. These pricing options allow us to fulfill that philosophy, and guarantee the same level of support to all of our customers - no "regular" vs. "priority" support. Our pricing options for Payments for Elementor are designed for what works best for you.

== Installation ==

This section describes how to install the plugin and get it working.

### Automatically

1. Search for Payments for Elementor in the Add New Plugin section of the WordPress admin
2. Install & Activate

### Manually

1. Download the zip file and upload `payments-for-elementor` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I use this plugin? =

First, install and activate the plugin.

Next, configure your global payment settings. These are found in your wp-admin, under Payments --> Settings. This is where you connect your Stripe account, and set things like your currency and statement descriptor.

Finally, open the Elementor panel for any page on your website. Add a Payment Form widget, and configure the settings for your payment form. Here, you'll add a price, description for the payment, redirect URL, and more.

= Do you offer support for this plugin? =

If you have any questions or need any help, please get in touch with us on [our website](https://elegantmodules.com).

= How does Payments for Elementor compare to WooCommerce or Easy Digital Downloads? =

WooCommerce and EDD are great, but they are not "small" plugins. WooCommerce, for example, adds a lot of options to your database, and loads large stylesheets and scripts on the frontend of your website. If you don't need features like shipping, inventory management, etc., WooCommerce may be overkill for your website.

Payments for Elementor is very lightweight. Its purpose is to provide simple, minimal, and flexible e-commerce features that won't impact the performance of your website. You also won't need to add a bunch of add-ons to your website, keeping the number of plugins running to a minimum.

= What happens if I refund my customers? Do you refund your fee? =

Yes, if you refund your customer, we refund our fee proportionally (if you refund 50% of the cost, we refund 50% of our fee).

= How is my data used? =

[Stripe](https://stripe.com/privacy) - Payments for Elementor sends customer data to Stripe to create customer records for users who submit payments through Payments for Elementor. Your customers will have customer records in Stripe, which are attached to your Stripe account.

[Elegant Modules](https://elegantmodules.com/privacy-policy/) - Payments for Elementor submits payment and customer data to an external API owned and maintained by us, Elegant Modules, for the purpose of generating the required payment data with Stripe. This data is **not** saved on the our servers. **We do not store any customer or payment data.** The data we send to Stripe is used by Stripe to process the customer's payment. Customer and payment data is stored only on Stripe and your website.

== Screenshots ==

1. 

== Changelog ==

= 1.0.3 =
* Fixed issue with redirects after payment is completed

= 1.0.2 =
* Fixed incorrect setting key
* Made Javascript selector more specific

= 1.0.1 =
* Fixed issue causing sites to be stuck in test mode

= 1.0 =
* Initial version