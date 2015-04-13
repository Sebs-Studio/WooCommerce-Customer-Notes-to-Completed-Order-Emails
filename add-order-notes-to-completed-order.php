<?php
/**
 * Plugin Name: WooCommerce Customer Notes to Completed Order Emails
 * Description: Simply adds the customers order notes to be added to the completed order emails.
 * Author:      Sebastien Dumont
 * Author URI:  http://www.sebastiendumont.com
 * Version:     1.0.0
 * Text Domain: woocommerce-customer-order-notes-completed-order-emails
 *
 * Copyright: (c) 2015 Sebastien Dumont. (mailme@sebastiendumont.com)
 *
 * License:     GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'woocommerce_email_order_meta', 'sd_add_customer_order_notes_to_completed_order_emails', 10 );

function sd_add_customer_order_notes_to_completed_order_emails() {
	global $woocommerce, $post;

	// If the order is not completed then don't continue.
	if ( get_post_status( $post->ID ) != 'wc-completed' ) return false;

	$args = array(
		'post_id' => $post->ID,
		'status'  => 'approve',
		'type'    => 'order_note'
	);

	// Fetch comments
	$notes = get_comments( $args );

	echo '<h2>' . __( 'Order Notes', 'woocommerce-customer-order-notes-completed-order-emails' ) . '</h2>';

	echo '<ul class="order_notes" style="list-style:none; padding-left:0px;">';

	// Check that there are order notes
	if ( $notes ) {

		// Display each order note
		foreach( $notes as $note ) {

			$note_classes = get_comment_meta( $note->comment_ID, 'is_customer_note', true ) ? 'yes' : 'no';
			// Only display order notes made by the customer
			if ( $note_classes == 'yes' ) {
			?>
			<li style="padding:0px -10px;">
				<div class="note_content" style="background:#d7cad2; padding:10px;">
					<?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
				</div>

				<p class="meta">
					<abbr class="exact-date" title="<?php echo $note->comment_date; ?>"><?php printf( __( 'added on %1$s at %2$s', 'woocommerce-customer-order-notes-completed-order-emails' ), date_i18n( wc_date_format(), strtotime( $note->comment_date ) ), date_i18n( wc_time_format(), strtotime( $note->comment_date ) ) ); ?></abbr>
						<?php if ( $note->comment_author !== __( 'WooCommerce', 'woocommerce-customer-order-notes-completed-order-emails' ) ) printf( ' ' . __( 'by %s', 'woocommerce-customer-order-notes-completed-order-emails' ), $note->comment_author ); ?>
				</p>
			</li>
			<?php
			}

		} // END foreach()

	} // END if()

	echo '</ul>';

} // END sd_add_customer_order_notes_to_completed_order_emails()

?>