module.exports = function(grunt) {

	grunt.initConfig({
		copy: {
			main: {
				src: [
					'languages/**',
					'CHANGELOG.md',
					'dynamic-asset-versioning.php',
					'LICENSE.txt',
					'readme.txt'
				],
				dest: 'dist/'
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: 'languages/',
					mainFile: 'dynamic-asset-versioning.php',
					type: 'wp-plugin',
					updateTimestamp: false,
					updatePoFiles: true
				}
			}
	}
	});

	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-wp-i18n');

	grunt.registerTask('i18n', ['makepot']);
	grunt.registerTask('build', ['i18n', 'copy']);
};
