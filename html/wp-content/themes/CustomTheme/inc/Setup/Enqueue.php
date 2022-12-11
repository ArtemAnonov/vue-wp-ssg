<?php

namespace Awps\Setup;

/**
 * Enqueue.
 */
class Enqueue
{
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register()
	{
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		// add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

	}

	/**
	 * Notice the mix() function in wp_enqueue_...
	 * It provides the path to a versioned asset by Laravel Mix using querystring-based 
	 * cache-busting (This means, the file name won't change, but the md5. Look here for 
	 * more information: https://github.com/JeffreyWay/laravel-mix/issues/920 )
	 */
	public function enqueue_scripts()
	{
		$version = date('i.s');
		// $resourcePath = "/dist/assets/";
		// $enqueuePath = get_template_directory_uri() . $resourcePath;
		// $dirIterator = new \DirectoryIterator(get_stylesheet_directory() . $resourcePath);

		// // CSS
		// foreach ($dirIterator as $fileInfo) {
		// 	if (pathinfo($fileInfo, PATHINFO_EXTENSION) === 'css') {
		// 		wp_enqueue_style($fileInfo->getFilename(), $enqueuePath . $fileInfo->getFilename(), array(), $version, 'all');
		// 	}
		// }

		// // JS
		// foreach ($dirIterator as $fileInfo) {
		// 	if (pathinfo($fileInfo->getFilename(), PATHINFO_EXTENSION) === 'js') {
		// 		wp_enqueue_script($fileInfo->getFilename(), $enqueuePath . $fileInfo->getFilename(), array(), $version, true);
		// 	}
		// }
		// define('SCRIPT_JS_LOADED', true);


		// // Activate browser-sync on development environment
		// if ( getenv( 'APP_ENV' ) === 'development' ) :
		// wp_enqueue_script( '__bs_script__', getenv('WP_SITEURL') . ':3000/browser-sync/browser-sync-client.js', array(), null, true );
		// endif;

		// // Extra
		// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		// 	wp_enqueue_script( 'comment-reply' );
		// }
	}

	public function enqueue_admin_scripts()
	{
		wp_localize_script('wpApiSettings', 'index.bundle.js', [
			'root' => esc_url_raw(rest_url()),
			'nonce' => wp_create_nonce('wp_rest')
		]);
	}
}
