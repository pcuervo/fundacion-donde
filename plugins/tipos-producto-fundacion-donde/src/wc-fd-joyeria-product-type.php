<?php

//Joyería
class WC_FD_Joya_Product_Type extends WC_Product {
	public function __construct( $product ) {
		$this->product_type = 'simple_joya';
		parent::__construct( $product );
	}
}