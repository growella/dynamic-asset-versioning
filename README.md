# Dynamic Asset Versioning

[![Build Status](https://travis-ci.org/growella/dynamic-asset-versioning.svg?branch=master)](https://travis-ci.org/growella/dynamic-asset-versioning)
[![Code Climate](https://codeclimate.com/github/growella/dynamic-asset-versioning/badges/gpa.svg)](https://codeclimate.com/github/growella/dynamic-asset-versioning)

WordPress asset versioning can be a double-edged sword: on one-hand, it's extremely effective for cache-busting, ensuring you aren't sharing stale scripts or styles to your visitors. On the other hand, having to manually increment a version number is a pain (even [as a constant](https://10up.github.io/Engineering-Best-Practices/php/#asset-versioning)), often resulting in a version control history full of "bumping the version number" commits.

Dynamic Asset Versioning aims to simplify this process: if an enqueued asset doesn't have an explicit version number, the plugin will get the timestamp of the last time the file was changed and use that as the version number. It's easy: you touch the file, the version number is updated automatically.


## Installation

There are two ways to install Dynamic Asset Versioning in your WordPress site: as a must-use (MU) or a standard plugin.


### As a must-use (MU) plugin (recommended)

1. Download or clone the repository into `wp-content/mu-plugins`.
2. As MU plugins cannot run in a sub-directory, move dynamic-asset-versioning.php directly into the `wp-content/mu-plugins` directory.
	* Alternately, [you may prefer to create a symbolic link ("symlink") in wp-content/mu-plugins](https://stevegrunwell.com/blog/symlink-wordpress-mu-plugin/) that points to dynamic-asset-versioning.php.


### As a standard WordPress plugin

1. Download or clone the repository into `wp-content/plugins`.
2. Activate the plugin through the WordPress plugins screen.


## Usage

Once Dynamic Asset Versioning is active, it will automatically determine version numbers based on file modification time for any [non-core] files that have been enqueued using [`wp_enqueue_style()`](https://developer.wordpress.org/reference/functions/wp_enqueue_style/) or [`wp_enqueue_script()`](https://developer.wordpress.org/reference/functions/wp_enqueue_style/).

### Example

```php
wp_enqueue_style(
	'my-theme-styles',
	get_template_directory_uri() . '/assets/css/my-styles.css',
	array( 'some-other-styles' ),
	false, // Don't worry about it, Dynamic Asset Versioning has you covered!
	'screen'
);
```

## Special thanks

A special thanks goes out to [10up](http://10up.com), who helped inspire the original concept of this plugin.


## License

The MIT License (MIT)
Copyright (c) 2016 Growella

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.