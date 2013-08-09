'use strict';

module.exports = function( grunt ){
	var reloadPort = 35729, files;

	grunt.initConfig({
		pkg: grunt.file.readJSON( 'package.json' ),
		release: {
			options: {
				files: [ 'package.json' ],
				npm: false,
				tagMessage: '<%= version %>'
			}
		},
		watch: {
			gruntfile: {
				files: 'Gruntfile.js',
				tasks: [ 'jshint' ]
			},
			src: {
				files: [ '**/*' ],
				tasks: [ 'jshint', 'uglify' ],
				options: {
					livereload: true
				}
			}
		},
		uglify: {
			my_target: {
				files: {
					'dist/apocryphatwo/lib/js/**/*.js': [ 'src/apocryphatwo/libs/js/**/*.js' ],
				}
			}
		}
	});

	// Register task related packages
	grunt.loadNpmTasks( 'grunt-release' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );

	// Register tasks
	grunt.registerTask( 'default', [] );
	grunt.registerTask( 'watch', [ 'watch' ] );
	grunt.registerTask( 'rel:major', [ 'simplemocha', 'release:major' ] );
	grunt.registerTask( 'rel:minor', [ 'simplemocha', 'release:minor' ] );
	grunt.registerTask( 'rel:patch', [ 'simplemocha', 'release:patch' ] );
	grunt.registerTask( 'rel:prerelease', [ 'simplemocha', 'release:prerelease' ] );
};