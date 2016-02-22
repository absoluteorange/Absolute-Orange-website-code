
module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        compass: {
            options: {
                config: 'config.rb'
            },
            clean: {
                options: {
                clean:true
                }
                },
            dist: {
                options: {

                }
                },
            dev: {
                options: {
                    debugInfo: true,
                    sourcemap:true
                }
            }

        },
        grunticon: {
            icons: {
                files: [{
                    expand: true,
                    cwd: 'svgs/',
                    src: '*.svg',
                    dest: 'app/icon'
                }]
            },
            options: {
                enhanceSVG: true
            }
        },
        copy: {
          scripts: {
            files: [
             
              // makes all src relative to cwd
              {expand: true, cwd: 'scripts/blogs/', src: ['**.js'], dest: 'app/scripts/blogs'},
              {expand: true, cwd: 'scripts/', src: ['**/**'], dest: 'app/scripts'},
              {src: ['scripts/lib/html5shiv.min.js'], dest: 'app/scripts/lib/html5shiv.min.js'},

            ],
          },
        },

        uglify: {
            scripts: {
              files: [{
                  expand: true,
                  cwd: 'app/scripts',
                  src: '**/*.js',
                  dest: 'app/scripts'
              }]
            }
        },

        requirejs: {
          compile: {
            options: {
              baseUrl: "scripts/ao",
              paths:{
                jquery:"empty:"
              },
              out: "app/scripts/main.js",
              name:"main"           

            }
          }
        },             
       
        watch: {
          sass: {
            files: ['sass/**'],
            tasks: ['compass:dev'],
            options: {
              spawn: false,
              livereload: true,
            },

          },
           scripts: {
            files: ['scripts/**'],
            tasks: ['prepare_scripts'],
            options: {
              spawn: false,
            },
          },
          icons: {
            files: ['svgs/**'],
            tasks: ['grunticon'],
            options: {
              spawn: false,
            },
          }
        
        },
    });

  grunt.loadNpmTasks('grunt-contrib-requirejs');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-grunticon');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');

    grunt.registerTask('prepare_scripts', [
        'copy:scripts'
    ]);

    grunt.registerTask('build', [
        'compass:dev',
        'prepare_scripts',
        'grunticon'
    ]);

    grunt.registerTask('dist', [
       
       'compass:clean',
        'compass:dist',
        'prepare_scripts',
        'requirejs:compile',
         'uglify:scripts',
        'grunticon'
       
    ]);
    
};
