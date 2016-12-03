<?php
/**
 * Tests for the main plugin functionality.
 *
 * @package Growella\DynamicAssetVersioning
 * @author  Growella
 */

namespace Growella\DynamicAssetVersioning;

use WP_Mock as M;

class DynamicAssetVersioningTest extends TestCase {

	protected $testFiles = array(
		'dynamic-asset-versioning.php',
	);

	public function testMaybeVersionScript() {
		global $wp_scripts;

		$wp_scripts = uniqid();

		M::wpFunction( __NAMESPACE__ . '\maybe_version_asset', array(
			'times'  => 1,
			'args'   => array( 'src', 'foo', $wp_scripts ),
			'return' => 'newsrc',
		) );

		$this->assertEquals( 'newsrc', maybe_version_script( 'src', 'foo' ) );

		$wp_scripts = null;
	}

	public function testMaybeVersionStyle() {
		global $wp_styles;

		$wp_styles = uniqid();

		M::wpFunction( __NAMESPACE__ . '\maybe_version_asset', array(
			'times'  => 1,
			'args'   => array( 'src', 'foo', $wp_styles ),
			'return' => 'newsrc',
		) );

		$this->assertEquals( 'newsrc', maybe_version_style( 'src', 'foo' ) );

		$wp_styles = null;
	}
}
