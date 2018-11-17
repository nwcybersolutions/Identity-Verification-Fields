<?php

/*
Plugin Name: Identity Verification Fields
Plugin URI: https://github.com/nwcybersolutions/Identity-Verification-Fields/
Description: Add Identity Verification Fields to User Profile, Registration, and Backend Users Page
Author: Northwest Cyber Solutions
Author URI: https://nwcybersolutions.com
Version: 1.0.0
License: MIT
License URI: https://opensource.org/licenses/MIT
Text Domain: Add Identity Verification Fields to User Profile, Registration, and Backend Users Page
Domain Path: /languages
*/
/*
 * To display additional field at My Account page 
 */
add_action( 'woocommerce_edit_account_form', 'my_woocommerce_edit_account_form' );
 
function my_woocommerce_edit_account_form() {
 
	$user_id = get_current_user_id();
	$user = get_userdata( $user_id );
 
	if ( !$user )
		return;
 
	$dateofbirth = get_user_meta( $user_id, 'dateofbirth', true );
	$issuingstate= get_user_meta( $user_id, 'issuingstate', true );
	$identificationnumber= get_user_meta( $user_id, 'identificationnumber', true );
	$expirydate= get_user_meta( $user_id, 'expirydate', true );

?>
	<fieldset>
		<legend>Identity Verification</legend>
  
		<p class="form-row form-row-thirds">
			<label for="dateofbirth">Date of Birth:</label>
			<input type="text" name="dateofbirth" value="<?php echo esc_attr( $dateofbirth ); ?>" class="input-text" />
			<br />
			<span style="font-size: 12px;">Please enter your Date of Birth. MM/DD/YYYY</span>
		</p>
		<p class="form-row form-row-thirds">
			<label for="issuingstate">ID/DL Issuing State:</label>
			<input type="text" name="issuingstate" value="<?php echo esc_attr( $issuingstate); ?>" class="input-text" />
			<br />
			<span style="font-size: 12px;">State Abbreviation Only. (Ex. 'OR')</span>
		</p>
		<p class="form-row form-row-thirds">
			<label for="identificationnumber">ID/DL Number:</label>
			<input type="text" name="identificationnumber" value="<?php echo esc_attr( $identificationnumber); ?>" class="input-text" />
			<br />
			<span style="font-size: 12px;">Accurately Enter Your ID Number (Verified @ Delivery)</span>
		</p>
		<p class="form-row form-row-thirds">
			<label for="expirydate">ID/DL Expiration Date:</label>
			<input type="text" name="expirydate" value="<?php echo esc_attr( $expirydate); ?>" class="input-text" />
			<br />
			<span style="font-size: 12px;">As Displayed on Card. MM/DD/YYYY or MM/YY</span>
		</p>

	</fieldset>
 
<?php
 
}
/*
 * This is to save user input into database
 */
add_action( 'woocommerce_save_account_details', 'my_woocommerce_save_account_details' );
 
function my_woocommerce_save_account_details( $user_id ) {

	update_user_meta( $user_id, 'dateofbirth', htmlentities( $_POST[ 'dateofbirth' ] ) ); 
	update_user_meta( $user_id, 'issuingstate', htmlentities( $_POST[ 'issuingstate' ] ) ); 
	update_user_meta( $user_id, 'identificationnumber', htmlentities( $_POST[ 'identificationnumber' ] ) ); 
	update_user_meta( $user_id, 'expirydate', htmlentities( $_POST[ 'expirydate' ] ) ); 

} 

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
 
function my_show_extra_profile_fields( $user ) { ?>
 
    <h3>Identity Verification</h3>
    <table class="form-table">

        <tr>
            <th><label for="dateofbirth">Date of Birth:</label></th>
            <td>
                <input type="text" name="dateofbirth" id="dateofbirth" value="<?php echo esc_attr( get_the_author_meta( 'dateofbirth', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your Date of Birth. MM/DD/YYYY</span>
            </td>
        </tr>
        <tr>
            <th><label for="issuingstate">ID/DL Issuing State:</label></th>
            <td>
                <input type="text" name="issuingstate" id="issuingstate" value="<?php echo esc_attr( get_the_author_meta( 'issuingstate', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">State Abbreviation Only. (Ex. 'OR')</span>
            </td>
        </tr>
        <tr>
            <th><label for="identificationnumber">ID/DL Number:</label></th>
            <td>
                <input type="text" name="identificationnumber" id="identificationnumber" value="<?php echo esc_attr( get_the_author_meta( 'identificationnumber', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Accurately Enter Your ID Number (Verified @ Delivery)</span>
            </td>
        </tr>
        <tr>
            <th><label for="expirydate">ID/DL Expiration Date:</label></th>
            <td>
                <input type="text" name="expirydate" id="expirydate" value="<?php echo esc_attr( get_the_author_meta( 'expirydate', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">As Displayed on Card. MM/DD/YYYY or MM/YY</span>
            </td>
        </tr>
    </table>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );
 
function my_save_extra_profile_fields( $user_id ) {
 
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
 

    update_usermeta( absint( $user_id ), 'dateofbirth', wp_kses_post( $_POST['dateofbirth'] ) );
    update_usermeta( absint( $user_id ), 'issuingstate', wp_kses_post( $_POST['issuingstate'] ) );
    update_usermeta( absint( $user_id ), 'identificationnumber', wp_kses_post( $_POST['identificationnumber'] ) );
    update_usermeta( absint( $user_id ), 'expirydate', wp_kses_post( $_POST['expirydate'] ) );
}
/*
 * Add new register fields for WooCommerce registration.
 */
function wooc_extra_register_fields() {
    ?>

    <p class="form-row form-row-first">
    <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
    </p>

    <p class="form-row form-row-last">
    <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
    </p>

    <div class="clear"></div>

    <p class="form-row form-row-wide">
    <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
    </p>
    
    <fieldset>
	<legend>Identity Verification</legend>
      
    <p class="form-row form-row-first">
    <label for="dateofbirth"><?php _e( 'Date of Birth:', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="dateofbirth" id="dateofbirth" value="<?php if ( ! empty( $_POST['dateofbirth'] ) ) esc_attr_e( $_POST['dateofbirth'] ); ?>" />
    </p>
    
    <p class="form-row form-row-last">
    <label for="issuingstate"><?php _e( 'ID/DL Issuing State:', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="issuingstate" id="issuingstate" value="<?php if ( ! empty( $_POST['issuingstate'] ) ) esc_attr_e( $_POST['issuingstate'] ); ?>" />
    </p>
    
    <p class="form-row form-row-first">
    <label for="identificationnumber"><?php _e( 'ID/DL Number:', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="identificationnumber" id="identificationnumber" value="<?php if ( ! empty( $_POST['identificationnumber'] ) ) esc_attr_e( $_POST['identificationnumber'] ); ?>" />
    </p>
    
    <p class="form-row form-row-last">
    <label for="expirydate"><?php _e( 'Expiration Date:', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="expirydate" id="expirydate" value="<?php if ( ! empty( $_POST['expirydate'] ) ) esc_attr_e( $_POST['expirydate'] ); ?>" />
    </p>
    </fieldset>
    <?php
}
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );


/**
 * Validate the extra register fields.
 *
 * @param WP_Error $validation_errors Errors.
 * @param string   $username          Current username.
 * @param string   $email             Current email.
 *
 * @return WP_Error
 */
function wooc_validate_extra_register_fields( $errors, $username, $email ) {
    if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
        $errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
    }
    if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
        $errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
    }
    if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
        $errors->add( 'billing_phone_error', __( '<strong>Error</strong>: Phone is required!.', 'woocommerce' ) );
    }
    
    if ( isset( $_POST['dateofbirth'] ) && empty( $_POST['dateofbirth'] ) ) {
        $errors->add( 'dateofbirth', __( '<strong>Error</strong>: Date of Birth is required!.', 'woocommerce' ) );
    }
    if ( isset( $_POST['issuingstate'] ) && empty( $_POST['issuingstate'] ) ) {
        $errors->add( 'issuingstate', __( '<strong>Error</strong>: State is required!.', 'woocommerce' ) );
    }
    if ( isset( $_POST['identificationnumber'] ) && empty( $_POST['identificationnumber'] ) ) {
        $errors->add( 'identificationnumber', __( '<strong>Error</strong>: ID Number is required!.', 'woocommerce' ) );
    }
    if ( isset( $_POST['expirydate'] ) && empty( $_POST['expirydate'] ) ) {
        $errors->add( 'expirydate', __( '<strong>Error</strong>: Expiration Date is required!.', 'woocommerce' ) );
    }
    
    return $errors;
}
add_filter( 'woocommerce_registration_errors', 'wooc_validate_extra_register_fields', 10, 3 );



/**
 * Save the extra register fields.
 *
 * @param int $customer_id Current customer ID.
 */
function wooc_save_extra_register_fields( $user_id) {
    if ( isset( $_POST['billing_first_name'] ) ) {
        // WordPress default first name field.
        update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        // WooCommerce billing first name.
        update_user_meta( $user_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
    }
    if ( isset( $_POST['billing_last_name'] ) ) {
        // WordPress default last name field.
        update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        // WooCommerce billing last name.
        update_user_meta( $user_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
    }
    if ( isset( $_POST['billing_phone'] ) ) {
        // WooCommerce billing phone
        update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
   
    if ( isset( $_POST['dateofbirth'] ) ) {
        // WooCommerce dateofbirth
        update_user_meta( $user_id, 'dateofbirth', sanitize_text_field( $_POST['dateofbirth'] ) );
    }
    if ( isset( $_POST['issuingstate'] ) ) {
        update_user_meta( $user_id, 'issuingstate', sanitize_text_field( $_POST['issuingstate'] ) );
    }
    if ( isset( $_POST['identificationnumber'] ) ) {
        update_user_meta( $user_id, 'identificationnumber', sanitize_text_field( $_POST['identificationnumber'] ) );
    }
    if ( isset( $_POST['expirydate'] ) ) {
        update_user_meta( $user_id, 'expirydate', sanitize_text_field( $_POST['expirydate'] ) );
    }

}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );
