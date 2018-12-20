<?php

/**
 * Plugin Name: Woocommerce Ok to leave method
 * Description: Addtional for WooCommerce
 * Version: 1.0.0
 * Author: Builtwithdigital
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/*
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    /**
     * Add checkbox field to the checkout
     **/
    add_action('woocommerce_after_order_notes', 'my_custom_checkout_field');

    function my_custom_checkout_field( $checkout ) {
        echo '<div class="ok-to-leave">';
        echo 'Not home? I give authority for the delivery to be left';
        echo '<span style="display: inline-block; margin-left: 10px">';
        woocommerce_form_field( '_ok_to_leave', array(
            'type'          => 'checkbox',
            'class'         => array('input-checkbox'),
            'label'         => __('Yes')
        ), $checkout->get_value( '_ok_to_leave' ));
        echo '</span></div>';
    }

    /**
     * Update the order meta with field value
     **/
    add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');

    function my_custom_checkout_field_update_order_meta( $order_id ) {
        if ( isset( $_POST['_ok_to_leave'] )  )
        {
            update_post_meta( $order_id, '_ok_to_leave',1);
        }
        else
        {
            update_post_meta( $order_id, '_ok_to_leave');
        }
    }

    /**
     * Display field value on the order edit page
     */
    add_action( 'woocommerce_admin_order_data_after_billing_address', 'display_custom_field_on_order_edit_pages', 10, 1 );
    function display_custom_field_on_order_edit_pages( $order ){
        $ok_to_leave = get_post_meta( $order->id, '_ok_to_leave', true );
        if( $ok_to_leave == 1 )
        {
            echo '<p><strong>Ok to leave: </strong> <span style="color:red;">Yes</span></p>';
        }
        else{
            echo '<p><strong>Ok to leave: </strong> <span style="color:red;">No</span></p>';
        }
    }

}