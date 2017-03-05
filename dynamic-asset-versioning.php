<?php
/**
 * Plugin Name: Dynamic Asset Versioning
 * Description: Dynamically set asset version numbers based on file modification times, preventing stale caches.
 * Version:     0.1.0
 * Author:      Growella
 * Author URI:  https://growella.com
 * License:     MIT
 *
 * @package Growella\DynamicAssetVersioning
 * @author  Growella
 */

namespace Growella\DynamicAssetVersioning;

/**
 * Detect any currently-enqueued assets that don't have explicit version numbers assigned to them,
 * then create a version number based on the file modification time.
 *
 * @param string          $src    The asset URL.
 * @param string          $handle The handle used to register the script or style.
 * @param WP_Dependencies $deps   The corresponding script or style's WP_Dependencies object,
 *                                passed by reference. This will usually be the $wp_scripts or
 *                                $wp_styles globals.
 */
function maybe_version_asset( $src, $handle, &$deps ) {

	// Short-circuit the process if SCRIPT_DEBUG is true.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		return $src;
	}

	// Return early if we don't have a matching handle.
	if ( ! isset( $deps->registered[ $handle ] ) ) {
		return $src;
	}

	// If the file already has a version, return early.
	if ( ! empty( $deps->registered[ $handle ]->ver ) ) {
		return $src;
	}

	// Don't mess with files in the default directories, as the WP version (default behavior) works.
	$path = parse_url( $src, PHP_URL_PATH );

	foreach ( $deps->default_dirs as $dir ) {
		if ( 0 === strpos( $path, $dir ) ) {
			return $src;
		}
	}

	// If we've gotten this far, it's time to get a version.
	$version = get_file_version( $src );
	$deps->registered[ $handle ]->ver = $version;

	return add_query_arg( 'ver', $version, $src );
}

/**
 * Apply dynamic versioning to scripts, if needed.
 *
 * @global $wp_scripts
 *
 * @param string $src    The script URL.
 * @param string $handle The handle used to register the script.
 */
function maybe_version_script( $src, $handle ) {
	global $wp_scripts;

	return maybe_version_asset( $src, $handle, $wp_scripts );
}
add_filter( 'script_loader_src', __NAMESPACE__ . '\maybe_version_script', 10, 2 );

/**
 * Apply dynamic versioning to styles, if needed.
 *
 * @global $wp_styles
 *
 * @param string $src    The style URL.
 * @param string $handle The handle used to register the style.
 */
function maybe_version_style( $src, $handle ) {
	global $wp_styles;

	return maybe_version_asset( $src, $handle, $wp_styles );
}
add_filter( 'style_loader_src', __NAMESPACE__ . '\maybe_version_style', 10, 2 );

/**
 * Get the file modification time for a given file.
 *
 * @param string $url  The URL for the file.
 * @return string|null A version string for the file or NULL.
 */
function get_file_version( $url ) {
	$content_url = content_url();
	$filepath    = str_replace( $content_url, WP_CONTENT_DIR, $url );
	$filepath    = explode( '?', $filepath );
	$filepath    = array_shift( $filepath );

	// Ensure the file actually exists.
	if ( ! file_exists( $filepath ) ) {
		return;
	}

	// Attempt to read the file timestamp.
	try {
		$timestamp = filemtime( $filepath );
	} catch ( \Exception $e ) {
		return;
	}

	return $timestamp ? (string) $timestamp : null;
}
