<?php
/**
 * Update URLs
 *
 * @package Update URLs
 *
 * Plugin Name: Update URLs
 * Plugin URI: https://wordpress.org/plugins/update-urls/
 * Description: This plugin <strong>updates all urls in your website</strong> by replacing old urls with new urls. To get started: 1) Click the "Activate" link to the left of this description, and 2) Go to your <a href="tools.php?page=update-urls.php">Update URLs</a> page to use it.
 * Author: KaizenCoders
 * Author URI: https://kaizencoders.com
 * Author Email: hello@kaizencoders.com
 * Version: 1.0.4
 * Requires at least: 5.0.0
 * Tested up to: 6.0
 * License: GPLv2 or later
 * Text Domain: update-urls
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

function kc_uu_add_management_page() {
	add_management_page( 'Update URLs', 'Update URLs', 'manage_options', basename( __FILE__ ), 'kc_uu_management_page' );
}
function kc_uu_load_textdomain() {
	load_plugin_textdomain( 'update-urls', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
function kc_uu_management_page() {
	if ( ! function_exists( 'kc_update_urls' ) ) {
		function kc_update_urls( $options, $oldurl, $newurl ) {

			global $wpdb;
			$results = array();
			$queries = array(
				'content'     => array( "UPDATE $wpdb->posts SET post_content = replace(post_content, %s, %s)", __( 'Content Items (Posts, Pages, Custom Post Types, Revisions)', 'update-urls' ) ),
				'excerpts'    => array( "UPDATE $wpdb->posts SET post_excerpt = replace(post_excerpt, %s, %s)", __( 'Excerpts', 'update-urls' ) ),
				'attachments' => array( "UPDATE $wpdb->posts SET guid = replace(guid, %s, %s) WHERE post_type = 'attachment'", __( 'Attachments', 'update-urls' ) ),
				'links'       => array( "UPDATE $wpdb->links SET link_url = replace(link_url, %s, %s)", __( 'Links', 'update-urls' ) ),
				'custom'      => array( "UPDATE $wpdb->postmeta SET meta_value = replace(meta_value, %s, %s)", __( 'Custom Fields', 'update-urls' ) ),
				'guids'       => array( "UPDATE $wpdb->posts SET guid = replace(guid, %s, %s)", __( 'GUIDs', 'update-urls' ) ),
			);

			foreach ( $options as $option ) {
				if ( 'custom' === $option ) {
					$n         = 0;
					$row_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta" );
					$page_size = 10000;
					$pages     = ceil( $row_count / $page_size );

					for ( $page = 0; $page < $pages; $page++ ) {
						$current_row = 0;
						$start       = $page * $page_size;
						$end         = $start + $page_size;
						$pmquery     = "SELECT * FROM $wpdb->postmeta WHERE meta_value <> ''";
						$items       = $wpdb->get_results( $pmquery );
						foreach ( $items as $item ) {
							$value = $item->meta_value;
							if ( trim( $value ) == '' ) {
								continue;
							}

							$edited = kc_uu_unserialize_replace( $oldurl, $newurl, $value );

							if ( $edited != $value ) {
								$fix = $wpdb->query( "UPDATE $wpdb->postmeta SET meta_value = '" . $edited . "' WHERE meta_id = " . $item->meta_id );
								if ( $fix ) {
									$n++;
								}
							}
						}
					}
					$results[ $option ] = array( $n, $queries[ $option ][1] );
				} else {
					$result             = $wpdb->query( $wpdb->prepare( $queries[ $option ][0], $oldurl, $newurl ) );
					$results[ $option ] = array( $result, $queries[ $option ][1] );
				}
			}
			return $results;
		}
	}
	if ( ! function_exists( 'kc_uu_unserialize_replace' ) ) {
		function kc_uu_unserialize_replace( $from = '', $to = '', $data = '', $serialised = false ) {
			try {
				if ( false !== is_serialized( $data ) ) {
					$unserialized = maybe_unserialize( $data );
					$data         = kc_uu_unserialize_replace( $from, $to, $unserialized, true );
				} elseif ( is_array( $data ) ) {
					$_tmp = array();
					foreach ( $data as $key => $value ) {
						$_tmp[ $key ] = kc_uu_unserialize_replace( $from, $to, $value, false );
					}
					$data = $_tmp;
					unset( $_tmp );
				} else {
					if ( is_string( $data ) ) {
						$data = str_replace( $from, $to, $data );
					}
				}
				if ( $serialised ) {
					return maybe_serialize( $data );
				}
			} catch ( Exception $error ) {

			}
			return $data;
		}
	}
	if ( isset( $_POST['kc_uu_settings_submit'] ) && ! check_admin_referer( 'kc_uu_submit', 'kc_uu_nonce' ) ) {
		if ( isset( $_POST['kc_uu_oldurl'] ) && isset( $_POST['kc_uu_newurl'] ) ) {
			$kc_uu_oldurl = esc_url_raw( wp_unslash( $_POST['kc_uu_oldurl'] ) );
			$kc_uu_newurl = esc_url_raw( wp_unslash( $_POST['kc_uu_newurl'] ) );
		}
		echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'ERROR', 'update-urls' ) . ' - ' . esc_html__( 'Please try again.', 'update-urls' ) . '</strong></p></div>';
	} elseif ( isset( $_POST['kc_uu_settings_submit'] ) && ! isset( $_POST['kc_uu_update_links'] ) ) {
		if ( isset( $_POST['kc_uu_oldurl'] ) && isset( $_POST['kc_uu_newurl'] ) )     {
			$kc_uu_oldurl = esc_url_raw(wp_unslash( $_POST['kc_uu_oldurl'] ) );
			$kc_uu_newurl = esc_url_raw(wp_unslash( $_POST['kc_uu_newurl'] ) );
		}
		echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'ERROR', 'update-urls' ) . ' - ' . esc_html__( 'Your URLs have not been updated.', 'update-urls' ) . '</p></strong><p>' . esc_html__( 'Please select at least one checkbox.', 'update-urls' ) . '</p></div>';
	} elseif ( isset( $_POST['kc_uu_settings_submit'] ) ) {

        $kc_uu_update_links = isset( $_POST['kc_uu_update_links'] ) ? (array) $_POST['kc_uu_update_links'] : array();

        $kc_uu_update_links = array_map( 'esc_attr', $kc_uu_update_links );

        if ( isset( $_POST['kc_uu_oldurl'] ) && isset( $_POST['kc_uu_newurl'] ) ) {
				$kc_uu_oldurl = esc_url_raw(wp_unslash( $_POST['kc_uu_oldurl'] ) );
				$kc_uu_newurl = esc_url_raw(wp_unslash( $_POST['kc_uu_newurl'] ) );
		}
		if ( ( $kc_uu_oldurl && $kc_uu_oldurl != 'http://www.oldurl.com' && trim( $kc_uu_oldurl ) != '' ) && ( $kc_uu_newurl && $kc_uu_newurl != 'http://www.newurl.com' && trim( $kc_uu_newurl ) != '' ) ) {
			$results     = kc_update_urls( $kc_uu_update_links, $kc_uu_oldurl, $kc_uu_newurl );

			$empty       = true;
			$emptystring = '<strong>' . __( 'Why do the results show 0 URLs updated?', 'update-urls' ) . '</strong><br/>' . __( 'This happens if a URL is incorrect OR if it is not found in the content. Check your URLs and try again.', 'update-urls' );

			$resultstring = '';
			foreach ( $results as $result ) {
				$empty         = ( $result[0] != 0 || $empty == false ) ? false : true;
				$resultstring .= '<br/><strong>' . $result[0] . '</strong> ' . $result[1];
			}

			if ( $empty ) :
				?>
<div id="message" class="error fade">
<table>
<tr>
	<td><p><strong>
				<?php _e( 'ERROR: Something may have gone wrong.', 'update-urls' ); ?>
			</strong><br/>
				<?php _e( 'Your URLs have not been updated.', 'update-urls' ); ?>
		</p>
				<?php
			else :
				?>
		<div id="message" class="updated fade">
			<table>
				<tr>
					<td><p><strong>
							<?php _e( 'Success! Your URLs have been updated.', 'update-urls' ); ?>
							</strong></p>
						<?php
			endif;
			?>
						<p><u>
							<?php _e( 'Results', 'update-urls' ); ?>
							</u><?php echo $resultstring; ?></p>
						<?php echo ( $empty ) ? '<p>' . $emptystring . '</p>' : ''; ?></td>
					<td width="60"></td>
					<td align="center"><?php if ( ! $empty ) : ?>
						<p>
							<?php // You can now uninstall this plugin.<br/> ?>
						<?php endif; ?></td>
				</tr>
			</table>
		</div>
			<?php
		} else {
			echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'ERROR', 'update-urls' ) . ' - ' . esc_html__( 'Your URLs have not been updated.', 'update-urls' ) . '</p></strong><p>' . esc_html_e( 'Please enter values for both the old url and the new url.', 'update-urls' ) . '</p></div>';
		}
	}
	?>
		<div class="wrap">
		<h2>Update URLs</h2>
		<form method="post" action="tools.php?page=<?php echo esc_html(basename( __FILE__ )); ?>">
			<?php wp_nonce_field( 'kc_uu_submit', 'kc_uu_nonce' ); ?>
			<p><?php printf( esc_html__( 'After moving a website, %s lets you fix old URLs in content, excerpts, links, and custom fields.', 'update-urls' ), '<strong>Update URLs</strong>' ); ?></p>
			<p><strong>
				<?php esc_html_e( 'WE RECOMMEND THAT YOU BACKUP YOUR WEBSITE.', 'update-urls' ); ?>
				</strong><br/>
				<?php esc_html_e( 'You may need to restore it if incorrect URLs are entered in the fields below.', 'update-urls' ); ?>
			</p>
			<h3 style="margin-bottom:5px;">
				<?php esc_html_e( 'Step' ); ?>
				1:
				<?php esc_html_e( 'Enter your URLs in the fields below', 'update-urls' ); ?>
			</h3>
			<table class="form-table">
				<tr valign="middle">
					<th scope="row" width="140" style="width:140px"><strong>
						<?php esc_html_e( 'Old URL', 'update-urls' ); ?>
						</strong><br/>
						<span class="description">
						<?php esc_html_e( 'Old Site Address', 'update-urls' ); ?>
						</span></th>
					<td><input name="kc_uu_oldurl" type="text" id="kc_uu_oldurl" value="<?php echo ( isset( $kc_uu_oldurl ) && trim( $kc_uu_oldurl ) != '' ) ? esc_url_raw($kc_uu_oldurl) : 'http://www.oldurl.com'; ?>" style="width:300px;font-size:20px;" onfocus="if(this.value=='http://www.oldurl.com') this.value='';" onblur="if(this.value=='') this.value='http://www.oldurl.com';" /></td>
				</tr>
				<tr valign="middle">
					<th scope="row" width="140" style="width:140px"><strong>
						<?php esc_html_e( 'New URL', 'update-urls' ); ?>
						</strong><br/>
						<span class="description">
						<?php esc_html_e( 'New Site Address', 'update-urls' ); ?>
						</span></th>
					<td><input name="kc_uu_newurl" type="text" id="kc_uu_newurl" value="<?php echo ( isset( $kc_uu_newurl ) && trim( $kc_uu_newurl ) != '' ) ? esc_url_raw($kc_uu_newurl) : 'http://www.newurl.com'; ?>" style="width:300px;font-size:20px;" onfocus="if(this.value=='http://www.newurl.com') this.value='';" onblur="if(this.value=='') this.value='http://www.newurl.com';" /></td>
				</tr>
			</table>
			<br/>
			<h3 style="margin-bottom:5px;">
				<?php esc_html_e( 'Step' ); ?>
				2:
				<?php esc_html_e( 'Choose which URLs should be updated', 'update-urls' ); ?>
			</h3>
			<table class="form-table">
				<tr>
					<td><p style="line-height:20px;">
							<input name="kc_uu_update_links[]" type="checkbox" id="kc_uu_update_true" value="content" checked="checked" />
							<label for="kc_uu_update_true"><strong>
								<?php esc_html_e( 'URLs in page content', 'update-urls' ); ?>
								</strong> (
								<?php esc_html_e( 'posts, pages, custom post types, revisions', 'update-urls' ); ?>
								)</label>
							<br/>
							<input name="kc_uu_update_links[]" type="checkbox" id="kc_uu_update_true1" value="excerpts" />
							<label for="kc_uu_update_true1"><strong>
								<?php esc_html_e( 'URLs in excerpts', 'update-urls' ); ?>
								</strong></label>
							<br/>
							<input name="kc_uu_update_links[]" type="checkbox" id="kc_uu_update_true2" value="links" />
							<label for="kc_uu_update_true2"><strong>
								<?php esc_html_e( 'URLs in links', 'update-urls' ); ?>
								</strong></label>
							<br/>
							<input name="kc_uu_update_links[]" type="checkbox" id="kc_uu_update_true3" value="attachments" />
							<label for="kc_uu_update_true3"><strong>
								<?php esc_html_e( 'URLs for attachments', 'update-urls' ); ?>
								</strong> (
								<?php esc_html_e( 'images, documents, general media', 'update-urls' ); ?>
								)</label>
							<br/>
							<input name="kc_uu_update_links[]" type="checkbox" id="kc_uu_update_true4" value="custom" />
							<label for="kc_uu_update_true4"><strong>
								<?php esc_html_e( 'URLs in custom fields and meta boxes', 'update-urls' ); ?>
								</strong></label>
							<br/>
							<input name="kc_uu_update_links[]" type="checkbox" id="kc_uu_update_true5" value="guids" />
							<label for="kc_uu_update_true5"><strong>
								<?php esc_html_e( 'Update ALL GUIDs', 'update-urls' ); ?>
								</strong> <span class="description" style="color:#f00;">
								<?php esc_html_e( 'GUIDs for posts should only be changed on development sites.', 'update-urls' ); ?>
								</span> <a href="http://codex.wordpress.org/Changing_The_Site_URL#Important_GUID_Note" target="_blank">
								<?php esc_html_e( 'Learn More.', 'update-urls' ); ?>
								</a></label>
						</p></td>
				</tr>
			</table>
			<p>
				<input class="button-primary" name="kc_uu_settings_submit" value="<?php esc_attr_e( 'Update URLs NOW', 'update-urls' ); ?>" type="submit" />
			</p>
		</form>
		<?php
}
add_action( 'admin_menu', 'kc_uu_add_management_page' );
add_action( 'admin_init', 'kc_uu_load_textdomain' );
?>
