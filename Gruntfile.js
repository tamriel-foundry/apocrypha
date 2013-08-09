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
			tasks: [ 'jshint', 'uglify' ]
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
		},
		banner: '/* <%= pkg.name %> - version <%= pkg.version %> - ' +
						'<%= grunt.template.today("mm-dd-yyyy") %>\n' +
						'<%= pkg.description %>\n ' +
						'&#169 <%= grunt.template.today("yyyy") %> <%= pkg.author.name %> ' +
						'- <%= pkg.author.email %> */\n',
		usebanner: {
			dist: {
				options: {
					position: 'top',
					banner: '<%= banner %>'
				},
				files: {
					src: [
						'src/apocryphatwo/**/*.js',
						'src/apocryphatwo/**/*.css',
						'src/apocryphatwo/**/*.php'
					]
				}
			}
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
	grunt.loadNpmTasks( 'grunt-banner' );

	// Register tasks
	grunt.registerTask( 'default', [] );
	grunt.registerTask( 'rel:major', [ 'release:major' ] );
	grunt.registerTask( 'rel:minor', [ 'release:minor' ] );
	grunt.registerTask( 'rel:patch', [ 'release:patch' ] );
	grunt.registerTask( 'rel:prerelease', [ 'release:prerelease' ] );
};