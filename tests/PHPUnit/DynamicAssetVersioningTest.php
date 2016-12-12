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

	public function testMaybeVersionAssetReturnsEarlyIfDependencyIsNotRegistered() {
		$src  = uniqid();
		$deps = new \stdClass;

		$this->assertEquals(
			$src,
			maybe_version_asset( $src, 'handle', $deps ),
			'If there are no registered dependencies, maybe_version_asset() should return early.'
		);
	}

	public function testMaybeVersionAssetReturnsEarlyIfDependencyHasVersion() {
		$src    = uniqid();
		$script = new \stdClass;
		$script->ver = 1;
		$deps   = new \stdClass;
		$deps->registered = array( 'handle' => $script );

		$this->assertEquals(
			$src,
			maybe_version_asset( $src, 'handle', $deps ),
			'Assets with explicit versions defined should not be touched!'
		);
	}

	public function testMaybeVersionAssetReturnsEarlyIfInDefaultPath() {
		$src    = 'http://example.com/wp-admin/my-script.js';
		$script = new \stdClass;
		$deps   = new \stdClass;
		$deps->registered   = array( 'handle' => $script );
		$deps->default_dirs = array( '/wp-admin/' );

		$this->assertEquals(
			$src,
			maybe_version_asset( $src, 'handle', $deps ),
			'Un-versioned assets in default paths should not be touched'
		);
	}

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
