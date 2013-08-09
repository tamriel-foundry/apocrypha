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
					files: [ 'apocryphatwo/lib/js/**/*.js' ]
				}
			}
		},
		uglify: {
			dist: {
				files: {
					// TODO: Simplify using wildcards once supported by Uglify.js
					'dist/apocryphatwo/lib/js/default_head_js.js': 'src/apocryphatwo/lib/js/default_head_js.js',
					'dist/apocryphatwo/lib/js/flexslider.min.js': 'src/apocryphatwo/lib/js/flexslider.min.js',
					'dist/apocryphatwo/lib/js/foundry.js': 'src/apocryphatwo/lib/js/foundry.js',
					'dist/apocryphatwo/lib/js/raw/backtotop.js': 'src/apocryphatwo/lib/js/raw/backtotop.js',
					'dist/apocryphatwo/lib/js/raw/comments.js': 'src/apocryphatwo/lib/js/raw/comments.js',
					'dist/apocryphatwo/lib/js/raw/flexslider.js': 'src/apocryphatwo/lib/js/raw/flexslider.js',
					'dist/apocryphatwo/lib/js/raw/login.js': 'src/apocryphatwo/lib/js/raw/login.js',
					'dist/apocryphatwo/lib/js/raw/notifications.js': 'src/apocryphatwo/lib/js/raw/notifications.js',
					'dist/apocryphatwo/lib/js/raw/quotes.js': 'src/apocryphatwo/lib/js/raw/quotes.js'
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
					'dist/apocryphatwo/lib/css/editor-content.css': 'src/apocryphatwo/lib/css/editor-content.css',
					'dist/apocryphatwo/lib/css/login-style.css': 'src/apocryphatwo/lib/css/login-style.css',
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