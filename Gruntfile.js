module.exports = function( grunt ){
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
			files: [ 'src/apocryphatwo/**/*.php', 'src/apocryphatwo/**/.*css', 'src/apocryphatwo/**/*.js' ],
			tasks: [ 'jshint', 'uglify', 'csslint', 'cssmin', 'phplint' ]
		},
		jshint: {
			options: {
				"curly": true,
				"eqnull": true,
				"eqeqeq": true,
				"undef": true,
				"globals": {
					"jQuery": true
				}
			},
			files: {
				src: {
					files: [ 'apocryphatwo/library/js/**/*.js' ]
				}
			}
		},
		uglify: {
			dist: {
				files: {
					// TODO: Simplify using wildcards once supported by Uglify.js
					'dist/apocryphatwo/library/js/default_head_js.js': 'src/apocryphatwo/library/js/default_head_js.js',
					'dist/apocryphatwo/library/js/flexslider.min.js': 'src/apocryphatwo/library/js/flexslider.min.js',
					'dist/apocryphatwo/library/js/foundry.js': 'src/apocryphatwo/library/js/foundry.js',
					'dist/apocryphatwo/library/js/raw/backtotop.js': 'src/apocryphatwo/library/js/raw/backtotop.js',
					'dist/apocryphatwo/library/js/raw/comments.js': 'src/apocryphatwo/library/js/raw/comments.js',
					'dist/apocryphatwo/library/js/raw/flexslider.js': 'src/apocryphatwo/library/js/raw/flexslider.js',
					'dist/apocryphatwo/library/js/raw/login.js': 'src/apocryphatwo/library/js/raw/login.js',
					'dist/apocryphatwo/library/js/raw/notifications.js': 'src/apocryphatwo/library/js/raw/notifications.js',
					'dist/apocryphatwo/library/js/raw/quotes.js': 'src/apocryphatwo/library/js/raw/quotes.js'
				}
			}
		},
		csslint: {
			strict: {
				options: {
					import: 2
				},
				src: [ 'src/apocryphatwo/**/*.css' ]
			},
		},
		cssmin: {
			minify: {
				files: {
					// TODO: Simplify using wildcards once supported by CSSMin
					'dist/apocryphatwo/style.css': 'src/apocryphatwo/style.css',
					'dist/apocryphatwo/library/css/editor-content.css': 'src/apocryphatwo/library/css/editor-content.css',
					'dist/apocryphatwo/library/css/login-style.css': 'src/apocryphatwo/library/css/login-style.css',
				}
			}
		},
		phplint: {
			options: {
					swapPath: '/tmp'
				},
			all: [
				'src/apocryphatwo/**/*.php'
			]
		}
	});

	// Register task related packages
	grunt.loadNpmTasks( 'grunt-release' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-csslint' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-phplint' );

	// Register tasks
	grunt.registerTask( 'default', [] );
	grunt.registerTask( 'build', [ 'jshint', 'uglify', 'csslint', 'cssmin', 'phplint' ] );
	grunt.registerTask( 'rel:major', [ 'build', 'release:major' ] );
	grunt.registerTask( 'rel:minor', [ 'build', 'release:minor' ] );
	grunt.registerTask( 'rel:patch', [ 'build', 'release:patch' ] );
	grunt.registerTask( 'rel:prerelease', [ 'build', 'release:prerelease' ] );
};