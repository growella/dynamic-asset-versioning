# Dynamic Asset Versioning: Change Log

All notable changes to this project will be documented in this file, according to [the Keep a Changelog standards](http://keepachangelog.com/).

This project adheres to [Semantic Versioning](http://semver.org/).


## [Unreleased]

* Introduced this CHANGELOG.md file. Hopefully this will be the most meta entry in this file.
* Dynamic Asset Versioning now checks for [the `SCRIPT_DEBUG` constant](https://codex.wordpress.org/Debugging_in_WordPress#SCRIPT_DEBUG) and, if set and true, file modification timestamps will not be applied (#1). Props to @petenelson for the suggestion!


## [0.1.0] - 2016-12-14

Initial public release.


[Unreleased]: https://github.com/growella/dynamic-asset-versioning/compare/master...develop
[0.1.0]: https://github.com/growella/dynamic-asset-versioning/releases/tag/v0.1.0
[#1]: https://github.com/growella/dynamic-asset-versioning/issues/1