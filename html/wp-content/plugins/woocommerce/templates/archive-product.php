<?php

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper — 10 (выводит открывающие элементы div для содержимого)
 * @hooked woocommerce_breadcrumb — 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 * 
 *  ***
 * 
 * //remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
 * 
 * function wplb_say_hello() {
 *     echo '<div style="margin-bottom:10px; color: red"><em>👋 Привет!</em></div>';
 * }
 * // Добавление события
 * add_action( 'woocommerce_before_main_content', 'wplb_say_hello', 10 );
 */

do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>

<?php

/**
 * Должен ли отображаться цикл WooCommerce? Это вернет true, если у нас есть сообщения (продукты) или если у нас есть подкатегории для отображения.
 */
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
     * 
     * Запускайте стандартные перехватчики цикла магазина при разбивке результатов на страницы, чтобы мы могли показывать количество результатов и так далее.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

    /**
     * wc_get_loop_prop() Получает свойство из глобального файла woocommerce_loop.
     */
	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
    // remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
     
    //  function wplb_say_hello() {
    //      echo '<div style="margin-bottom:10px; color: red"><em>👋 Привет!</em></div>';
    //  }
     // Добавление события
    //  add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 5 );

	do_action( 'woocommerce_after_shop_loop' );

} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// do_action( 'woocommerce_sidebar' );


get_footer( 'shop' );
