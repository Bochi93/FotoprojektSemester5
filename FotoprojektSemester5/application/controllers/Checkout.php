<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
include_once (dirname ( __FILE__ ) . "/Product.php");
class Checkout extends CI_Controller {
	public function __construct() {
		parent::__construct ();
	}
	public function index() {
		$this->load->model ( 'shoppingcart_model' );
		
		$user_id = $this->session->userdata ( 'user_id' );
		$cart = $this->shoppingcart_model->getShoppingCart ( $user_id );
		if (! isset ( $cart )) {
			
			$shopping_cart = array (
					'shca_id' => 0,
					// 'shca_commission' => 0,
					// 'shca_sum' => 0,
					// 'shca_delivery_charge' => 0,
					'shca_user_id' => $user_id 
			);
			$shca_id = $this->shoppingcart_model->insert_shopping_cart ( $shopping_cart );
			$cart->shoppingcart_positions = array ();
		} else
			$shca_id = $cart->shca_id;
		
		$shoppingcart_positions = $this->shoppingcart_model->getShoppingCartPositions ( $shca_id );
		
		foreach ( $shoppingcart_positions as $shoppingcart_position ) {
			$prod_id = $shoppingcart_position->scpo_prod_id;
			$prty_id = $shoppingcart_position->scpo_prty_id;
			
			$shoppingcart_position->product_variant = Product::getProductVariant ( $prod_id, $prty_id );
		}
		$cart->shoppingcart_positions = $shoppingcart_positions;
		$data ['userid'] = $user_id;
		$data ['shcaid'] = $shca_id;
		$data ['cart'] = $cart;
		$this->load->template ( 'checkout/checkout_view', $data );
	}
	public function overview() {
		$this->load->model ( 'shoppingcart_model' );
		$this->load->model ( 'adress_model' );
		
		$user_id = $this->session->userdata ( 'user_id' );
		$cart = $this->shoppingcart_model->getShoppingCart ( $user_id );
		
		$shca_id = $cart->shca_id;
		
		$shoppingcart_positions = $this->shoppingcart_model->getShoppingCartPositions ( $shca_id );
		
		foreach ( $shoppingcart_positions as $shoppingcart_position ) {
			$prod_id = $shoppingcart_position->scpo_prod_id;
			$prty_id = $shoppingcart_position->scpo_prty_id;
			
			$shoppingcart_position->product_variant = Product::getProductVariant ( $prod_id, $prty_id );
		}
		$cart->shoppingcart_positions = $shoppingcart_positions;
		$data ['userid'] = $user_id;
		$data ['shcaid'] = $shca_id;
		$data ['cart'] = $cart;
		$data ['adresses'] = $this->adress_model->getAdressesForUser ( $user_id );
		$this->load->template ( 'checkout/checkout_overview', $data );
	}
	public function guest() {
		$this->load->template ( 'checkout/checkout_guest.php' );
	}
	public function payment() {
		$this->load->template ( 'checkout/checkout_payment.php' );
	}
	public function customer() {
		$this->load->model ( 'user_model' );
		$this->load->model ( 'adress_model' );
		
		$user_id = $this->session->userdata ( 'user_id' );
		
		$user = $this->user_model->get_user_by_id ( $user_id );
		
		$data ['user_title'] = $user [0]->user_title;
		$data ['user_name'] = $user [0]->user_name;
		$data ['user_firstname'] = $user [0]->user_firstname;
		
		$data ['adre_name'] = $address [0]->adre_name;
		$data ['adre_street'] = $address [0]->adre_street;
		$data ['adre_zip'] = $address [0]->adre_zip;
		$data ['adre_city'] = $address [0]->adre_city;
		
		$this->load->template ( 'checkout/checkout_customer.php', $data );
	}
}
?>