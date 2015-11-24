<?php
include dirname(__FILE__) . '/../../../autoload.php';
include 'credentials.php';

/**
 * Get all invoice queue items from our Reseller account
 * The result is limited to the last 500 items
 */
$tigron_invoice_queue = Tigron\CP\Invoice\Queue::get_all();

foreach ($tigron_invoice_queue as $tigron_invoice_queue_item) {
	if ($tigron_invoice_queue_item->order_item_id == 0) {
		/**
		 * There is something in the invoice queue that is not a result of an order
		 * This is probably manual added by Tigron.
		 */
		echo 'Ignoring ' . $tigron_invoice_queue_item->id . ': ' . $tigron_invoice_queue_item->name . ' (no Tigron order)';
		continue;
	}

	try {
		$order_item = Tigron\CP\Order\Item::get_by_id($tigron_invoice_queue_item->order_item_id);
	} catch (Exception $e) {
		echo 'Ignoring ' . $tigron_invoice_queue_item->id . ': ' . $tigron_invoice_queue_item->name . ' (no Tigron order)';
		continue;
	}

	try {
		$tigron_product_type = Tigron\CP\Product\Type::get_by_id($order_item->product_type_id);
	} catch (Exception $e) {
		// if product type cannot be retrieved, ignore it
		echo 'Ignoring ' . $tigron_invoice_queue_item->id . ': ' . $tigron_invoice_queue_item->name . ' (Unknown product type)';
		continue;
	}


	$order_item = Tigron\CP\Order\Item::get_by_id($tigron_invoice_queue_item->order_item_id);

	$product = null;
	try {
		$product = \Tigron\CP\Product::get_by_id($order_item->product_id);
	} catch (Exception $e) {
		// Product doesn't exist, it is probably already deleted
	}

	$order = Tigron\CP\Order::get_by_id($order_item->order_id);
	$tigron_invoice_contact = Tigron\CP\Invoice\Contact::get_by_id($order->invoice_contact_id);

	if ($product !== null) {
		echo $order_item->id . ";" . $product->name . ";" . $order_item->name . ';' . $tigron_invoice_queue_item->price . "\n";
	} else {
		echo $order_item->id . ";" . ' ' . ';' . $order_item->name . ';' . $tigron_invoice_queue->price . "\n";
	}


}
