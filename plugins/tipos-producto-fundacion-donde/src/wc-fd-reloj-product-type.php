<?php

//Relojes
class WC_FD_Reloj_Product_Type extends WC_Product {
	public function __construct( $product ) {
		$this->product_type = 'simple_reloj';
		parent::__construct( $product );
	}
}