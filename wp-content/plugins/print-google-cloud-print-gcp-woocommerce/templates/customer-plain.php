<?php namespace Zprint;
/* @var $order \WC_Order */
/* @var $location_data */
?>
<?= Document::centerLine(get_appearance_setting('Check Header')); ?>
<?= Document::centerLine(get_appearance_setting('Company Name')); ?>
<?= Document::centerLine(get_appearance_setting('Company Info')); ?>
<?= Document::emptyLine(); ?>
<?= Document::centerLine(get_appearance_setting('Order Details Header')); ?>
<?= Document::symbolsAlign(__('Order Number', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $order->get_id()); ?>
<?= Document::symbolsAlign(__('Date', 'Print-Google-Cloud-Print-GCP-WooCommerce'), date_i18n(\get_option('date_format', 'm/d/Y'), $order->get_date_created())); ?>
<?php if ($location_data['total']['cost']) { ?>
	<?= Document::symbolsAlign(__('Total', 'Print-Google-Cloud-Print-GCP-WooCommerce'), wc_price($order->get_total(), array('currency' => $order->get_currency()))); ?>
	<?= Document::symbolsAlign(__('Payment Method', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $order->get_payment_method_title()); ?>
<?php } ?>
<?= Document::symbolsAlign(__('Time Ordered', 'Print-Google-Cloud-Print-GCP-WooCommerce'), date_i18n(\get_option('time_format', 'H:i'), $order->get_date_created())); ?>
<?php if ($location_data['shipping']['delivery_pickup_type']) { ?>
		<?= Document::centerLine(get_shipping_details($order)); ?>
<?php } ?>
<?php do_action('Zprint\templates\customer-plain\afterShippingDetails', $order->get_id(), $order); ?>
<?= Document::emptyLine(); ?>
<?= Document::centerLine(__('Order Information', 'Print-Google-Cloud-Print-GCP-WooCommerce')); ?>
<?= Document::emptyLine(); ?>
<?php foreach ($order->get_items() as $item) {
	/* @var $item \WC_Order_item */
	$meta = apply_filters('Zprint\templates\customer-plain\orderItemRawMeta', $item->get_formatted_meta_data(), $item, $order);
	$meta = array_filter($meta, function ($meta_item) {
		return !in_array($meta_item->key, Order::getHiddenKeys());
	});
	$meta = apply_filters('Zprint\templates\customer-plain\orderItemMeta', $meta);
	?>
	<?= Document::symbolsAlign($item['name'] . ' &times; ' . $item['qty'], apply_filters(
		'Zprint\templates\itemTotal',
		wc_price($item->get_data()['total'], array('currency' => $order->get_currency())),
		$item,
		$location_data,
		$order->get_currency()
	)); ?>
	<?php $meta = array_map(function ($meta_item) {
		return Document::symbolsAlign(' ' . $meta_item->key, $meta_item->value . ' ');
	}, $meta);
	echo implode('', $meta);
	?>
<?php } ?>
<?php foreach ($order->get_fees() as $fee) {
		echo Document::symbolsAlign($fee->get_name(), wc_price($fee->get_total(), array('currency' => $order->get_currency())));
} ?>
<?= Document::emptyLine(); ?>
<?php if ($location_data['total']['cost']) { ?>
	<?= Document::symbolsAlign(__('Subtotal', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $order->get_subtotal_to_display()); ?>
<?php } ?>
<?php if ($location_data['shipping']['cost']) { ?>
	<?= Document::symbolsAlign(__('Shipping', 'Print-Google-Cloud-Print-GCP-WooCommerce'), wc_price($order->get_shipping_total(), array('currency' => $order->get_currency()))); ?>
<?php } ?>
<?php if ($location_data['total']['cost']) { ?>
	<?= Document::symbolsAlign(__('Tax', 'Print-Google-Cloud-Print-GCP-WooCommerce'), wc_price($order->get_total_tax(), array('currency' => $order->get_currency()))); ?>
	<?= Document::symbolsAlign(__('Payment Method', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $order->get_payment_method_title()); ?>
	<?= Document::symbolsAlign(__('Total', 'Print-Google-Cloud-Print-GCP-WooCommerce'), wc_price($order->get_total(), array('currency' => $order->get_currency()))); ?>
<?php } ?>
<?= Document::emptyLine(); ?>
<?php if ($location_data['shipping']['customer_details']) { ?>
	<?= Document::centerLine(__('Customer Details', 'Print-Google-Cloud-Print-GCP-WooCommerce')); ?>
	<?= Document::emptyLine(); ?>
	<?php do_action('Zprint\templates\customer-plain\beforeCustomerDetails', $order->get_id(), $order); ?>
	<?= Document::symbolsAlign(__('Name', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $order->get_formatted_billing_full_name()); ?>
	<?= Document::symbolsAlign(__('Telephone', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $order->get_billing_phone()); ?>
	<?= Document::symbolsAlign(__('Email', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $order->get_billing_email()); ?>
	<?php do_action('Zprint\templates\customer-plain\afterBaseCustomerDetails', $order->get_id(), $order); ?>
<?php } ?>
<?php if (!empty($order->get_customer_note())) { ?>
	<?= Document::symbolsAlign(__('Order Notes', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $order->get_customer_note()); ?>
<?php } ?>
<?php if ($location_data['shipping']['method'] && $shipping_method = $order->get_shipping_method()) { ?>
	<?= Document::symbolsAlign(__('Shipping method', 'Print-Google-Cloud-Print-GCP-WooCommerce'), $shipping_method); ?>
<?php } ?>
<?php do_action('Zprint\templates\customer-plain\afterCustomerDetails', $order->get_id(), $order); ?>
<?= Document::emptyLine(); ?>
<?php do_action('Zprint\templates\customer-plain\beforeFooter', $order->get_id(), $order); ?>
<?= Document::centerLine(get_appearance_setting('Footer Information #1')); ?>
<?= Document::centerLine(get_appearance_setting('Footer Information #2')); ?>
