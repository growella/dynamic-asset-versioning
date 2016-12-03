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
	return $src;
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
