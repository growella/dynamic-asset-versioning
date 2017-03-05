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

	public function testMaybeVersionAsset() {
		$src    = uniqid();
		$script = new \stdClass;
		$deps   = new \stdClass;
		$deps->registered = array( 'handle' => $script );
		$deps->default_dirs = array( '/wp-admin/' );

		M::wpFunction( __NAMESPACE__ . '\get_file_version', array(
			'return' => 123,
		) );

		M::wpFunction( 'add_query_arg', array(
			'args'   => array( 'ver', 123, $src ),
			'return' => $src . '?ver=123',
		) );

		$this->assertEquals( $src . '?ver=123', maybe_version_asset( $src, 'handle', $deps ) );
	}

	/**
	 * @runTestInSeparateProcess
	 */
	public function testMaybeVersionAssetReturnsEarlyIfScriptDebugIsTrue() {
		$src    = uniqid();
		$script = new \stdClass;
		$deps   = new \stdClass;
		$deps->registered = array( 'handle' => $script );

		define( 'SCRIPT_DEBUG', true );

		$this->assertEquals(
			$src,
			maybe_version_asset( $src, 'handle', $deps ),
			'Never dynamically version an asset if SCRIPT_DEBUG is true'
		);
	}

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

	public function testGetFileVersion() {
		M::wpFunction( 'content_url', array(
			'return' => 'http://example.com/some/path',
		) );

		M::wpFunction( __NAMESPACE__ . '\file_exists', array(
			'args'   => array( WP_CONTENT_DIR . '/style.css' ),
			'return' => true,
		) );

		M::wpFunction( __NAMESPACE__ . '\filemtime', array(
			'return' => 123,
		) );

		$version = get_file_version( 'http://example.com/some/path/style.css' );

		$this->assertEquals( '123', $version );
		$this->assertInternalType( 'string', $version );
	}

	public function testGetFileVersionStripsQueryStringFromFilePath() {
		M::wpFunction( 'content_url', array(
			'return' => 'http://example.com/some/path',
		) );

		M::wpFunction( __NAMESPACE__ . '\file_exists', array(
			'times'  => 1,
			'args'   => array( WP_CONTENT_DIR . '/style.css' ),
			'return' => false,
		) );

		get_file_version( 'http://example.com/some/path/style.css?foo=bar' );
	}

	public function testGetFileVersionChecksThatFileExistsBeforeFilemtime() {
		M::wpFunction( 'content_url', array(
			'return' => 'http://example.com/some/path',
		) );

		$this->assertNull( get_file_version( 'http://example.com/some/path/style.css' ) );
	}

	public function testGetFileVersionHandlesFilemtimeExceptions() {
		M::wpFunction( 'content_url', array(
			'return' => 'http://example.com/some/path',
		) );

		M::wpFunction( __NAMESPACE__ . '\file_exists', array(
			'return' => true,
		) );

		M::wpFunction( __NAMESPACE__ . '\filemtime', array(
			'return' => function () {
				throw new \InvalidArgumentException( 'ruh roh!' );
			},
		) );

		$this->assertNull( get_file_version( 'http://example.com/some/path/style.css' ) );
	}
}
