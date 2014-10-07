<?php
namespace inklabs\kommerce\Entity;

class Product
{
	use Accessors;

	public $id;
	public $sku;
	public $name;
	public $price;
	public $quantity;
	public $product_group_id;
	public $require_inventory;
	public $show_price;
	public $active;
	public $visible;
	public $taxable;
	public $shipping;
	public $shipping_weight;
	public $description;
	public $rating;
	public $default_image;
	public $created;
	public $updated;

	public $tags = [];

	public function in_stock() {
		if (($this->require_inventory AND $this->quantity > 0) OR ( ! $this->require_inventory)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function get_rating() {
		return ($this->rating / 100);
	}
}