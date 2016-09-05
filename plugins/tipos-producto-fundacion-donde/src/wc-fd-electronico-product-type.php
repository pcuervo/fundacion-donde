<?php

//Electrónicos
class WC_FD_Electronico_Product_Type extends WC_Product {
	public function __construct( $product ) {
		$this->product_type = 'simple_electronico';
		parent::__construct( $product );
	}
}