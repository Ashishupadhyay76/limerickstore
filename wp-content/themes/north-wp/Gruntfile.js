'use strict';
module.exports = function(grunt) {
	const sass = require('node-sass');
  grunt.initConfig({
			theme_slugname: 'north',
      // let us know if our JS is sound
      jshint: {
	      options: {
          "bitwise": true,
          "browser": true,
          "curly": true,
          "eqeqeq": true,
          "eqnull": true,
          "immed": true,
          "jquery": true,
          "latedef": true,
          "newcap": true,
          "noarg": true,
          "node": true,
          "strict": false,
          "undef": false,
					"esversion": 8
	      },
	      all: [
	        'Gruntfile.js',
	        'assets/js/plugins/app.js',
	        'assets/js/plugins/admin-meta.js',
	        'assets/js/plugins/admin-vc.js',
          'assets/js/vendor/jquery.hotspot.js'
	      ]
      },

      // concatenation and minification all in one
      uglify: {
	      dist: {
          files: {
            'assets/js/admin-vc.min.js': [
	        		'assets/js/plugins/admin-vc.js'
	        	],
      			'assets/js/admin-meta.min.js': [
      				'assets/js/plugins/admin-meta.js'
      			],
      			'assets/js/vendor.min.js': [
      				'assets/js/vendor/!(jquery.fullPage.min).js'
      			],
						'assets/js/app.min.js': [
							'assets/js/plugins/app.js'
						]
          }
	      }
      },

			concat: {
				options: {
					stripBanners: true
				},
				dist: {
					src: 'assets/js/vendor/*.js',
					dest: 'assets/js/vendor.min.js',
				},
			},

			// style (Sass) compilation via Compass
			sass: {
				dist: {
					options: {
						implementation: sass,
						includePaths: [
							'node_modules/compass-mixins/lib'
						],
						outputStyle: 'compressed'
					},
					files: {
						'assets/css/app.css': 'assets/sass/app.scss',
						'assets/css/admin.css': 'assets/sass/admin.scss',
						'assets/css/admin_vc.css': 'assets/sass/admin_vc.scss',
						'assets/css/vc_extra.css': 'assets/sass/vc_extra.scss'
					},
				},
				dev: {
					options: {
						implementation: sass,
						includePaths: [
							'node_modules/compass-mixins/lib'
						],
						outputStyle: 'expanded'
					},
					files: {
						'assets/css/app.css': 'assets/sass/app.scss',
						'assets/css/admin.css': 'assets/sass/admin.scss',
						'assets/css/admin_vc.css': 'assets/sass/admin_vc.scss',
						'assets/css/vc_extra.css': 'assets/sass/vc_extra.scss'
					},
				}
			},

      // watch our project for changes
      watch: {
	      sass: {
          files: [
            'assets/sass/*',
            'assets/sass/*/*'
          ],
          tasks: ['sass:dist']
	      },
	      js: {
          files: [
            '<%= jshint.all %>'
          ],
          tasks: ['uglify']
	      },
				docs: {
					files: [
						'**/*.php',
						'!inc/framework/visualcomposer/templates/*.php',
					],
					options: {
						nospawn: true,
						livereload: true
					}
				}
      },

      // copy folder
      copy: {
        main: {
          expand: true,
          src: ['**', '!**/node_modules/**'],
          dest: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp',
        },
      },

      // clean folder
      clean: {
      	options: {
    	    'force': true
    	  },
				dest: [
					'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/**/*',
				],
        build: [
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/**/.git',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/**/.gitignore',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/**/.sass-cache',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/<%= theme_slugname %>-wp.esproj',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/**/.DS_Store',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/node_modules',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/assets/demo',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/admin/assets/theme-mode',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/inc/plugins/<%= theme_slugname %>-plugin',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/inc/admin/imports/one-click-demo-import/docs',
        	'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/inc/admin/imports/one-click-demo-import/tests',
					'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/vendor',
					'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/package-lock.json',
					'/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/composer.lock'
        ],
      },
      // Strip Code
      strip_code: {
	      strip_theme_switcher: {
	        options: {
	          blocks: [{
	            start_block: "<!-- start theme switcher -->",
	            end_block: "<!-- end theme switcher -->"
	          }]
	        },
	        src: ['/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/footer.php', '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/footer.php']
	      }
      },

      // Check textdomain errors.
  		checktextdomain: {
  			options:{
  				text_domain: 'north',
  				keywords: [
  					'__:1,2d',
  					'_e:1,2d',
  					'_x:1,2c,3d',
  					'esc_html__:1,2d',
  					'esc_html_e:1,2d',
  					'esc_html_x:1,2c,3d',
  					'esc_attr__:1,2d',
  					'esc_attr_e:1,2d',
  					'esc_attr_x:1,2c,3d',
  					'_ex:1,2c,3d',
  					'_n:1,2,4d',
  					'_nx:1,2,4c,5d',
  					'_n_noop:1,2,3d',
  					'_nx_noop:1,2,3c,4d'
  				]
  			},
  			theme: {
  				src: [
  					'**/*.php',
  					'!node_modules/**',
            '!inc/admin/plugins/class-tgm-plugin-activation.php',
            '!woocommerce/**',
						'!dokan/**'
  				],
  				expand: true
  			}
  		},

			// Compress
			compress: {
        plugin: {
				  options: {
				    archive: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/inc/plugins/<%= theme_slugname %>-plugin.zip'
				  },
				  files: [
				    {
				    	expand: true,
				    	cwd: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp/inc/plugins/',
				    	src: ['<%= theme_slugname %>-plugin/**/*']
				    }
				  ]
				},
			  theme: {
			    options: {
			      archive: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-wp.zip'
			    },
			    files: [
			      {
			      	expand: true,
			      	cwd: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/',
			      	src: ['<%= theme_slugname %>-wp/**/*']
			      }
			    ]
			  },
			  all_files: {
			    options: {
			      archive: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/<%= theme_slugname %>-all.zip'
			    },
			    files: [
			      {
			      	expand: true,
			      	cwd: '/Users/anteksiler/Desktop/themeforest/<%= theme_slugname %>/',
			        src: [
			          '<%= theme_slugname %>-wp.zip',
			          '<%= theme_slugname %>-wp-child.zip',
			          'PSD.zip',
                'Plugins.zip',
			          'icon-reference.zip',
			          'Documentation.zip'
			        ]
			      },
			    ]
			  }
			}
  });

  // load tasks
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-strip-code');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-checktextdomain');

  // register task
  grunt.registerTask('default', [
    'jshint',
    'sass:dev',
    'watch'
  ]);

	grunt.registerTask('release', [
    'jshint',
    'sass:dist',
    'uglify',
    'watch'
  ]);

  grunt.registerTask('pack', [
    'checktextdomain:theme',
		'clean:dest',
  	'copy',
    'strip_code',
    'compress:plugin',
  	'clean:build',
  	'compress:theme',
  	'compress:all_files'
  ]);
};