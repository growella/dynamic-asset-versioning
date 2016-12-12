=== Dynamic Asset Versioning ===
Contributors: growella, stevegrunwell
Tags: cache, assets, versioning
Requires at least: 4.7
Tested up to: 4.7
Stable tag: 0.1.0
License: MIT
License URI: https://opensource.org/licenses/MIT

Dynamically set asset version numbers based on file modification times, preventing stale caches.

== Description ==

WordPress asset versioning can be a double-edged sword: on one-hand, it's extremely effective for cache-busting, ensuring you aren't sharing stale scripts or styles to your visitors. On the other hand, having to manually increment a version number is a pain (even [as a constant](https://10up.github.io/Engineering-Best-Practices/php/#asset-versioning)), often resulting in a version control history full of "bumping the version number" commits.

Dynamic Asset Versioning aims to simplify this process: if an enqueued asset doesn't have an explicit version number, the plugin will get the timestamp of the last time the file was changed and use that as the version number. It's easy: you touch the file, the version number is updated automatically.

= Usage =

Once Dynamic Asset Versioning is active, it will automatically determine version numbers based on file modification time for any [non-core] files that have been enqueued using [`wp_enqueue_style()`](https://developer.wordpress.org/reference/functions/wp_enqueue_style/) or [`wp_enqueue_script()`](https://developer.wordpress.org/reference/functions/wp_enqueue_style/).

**Example**

	wp_enqueue_style(
		'my-theme-styles',
		get_template_directory_uri() . '/assets/css/my-styles.css',
		array( 'some-other-styles' ),
		false, // Don't worry about it, Dynamic Asset Versioning has you covered!
		'screen'
	);


= Special thanks =

A special thanks goes out to [10up](http://10up.com), who helped inspire the original concept of this plugin.


== Installation ==

There are two ways to install Dynamic Asset Versioning in your WordPress site: as a must-use (MU) or a standard plugin.


= As a must-use (MU) plugin (recommended) =

1. Download or clone the repository into `wp-content/mu-plugins`.
2. As MU plugins cannot run in a sub-directory, move dynamic-asset-versioning.php directly into the `wp-content/mu-plugins` directory.
	* Alternately, [you may prefer to create a symbolic link ("symlink") in wp-content/mu-plugins](https://stevegrunwell.com/blog/symlink-wordpress-mu-plugin/) that points to dynamic-asset-versioning.php.


= As a standard WordPress plugin =

1. Download or clone the repository into `wp-content/plugins`.
2. Activate the plugin through the WordPress plugins screen.


== Changelog ==

= 0.1.0 =

Initial public release.
