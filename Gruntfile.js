
module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        compass: {
            options: {
                config: 'config.rb'
            },
            dist: {},
            dev: {
                options: {
                    debugInfo: true
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
                {expand: true, cwd: 'scripts/libs/', src: ['**.js'], dest: 'app/scripts/libs'},

            ],
          },
        },

        concat: {
            options: {
              separator: ';',
            },
            scripts: {
              src: ['scripts/Mobile.js'],
              dest: 'app/scripts/main.js',
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
             
       
        watch: {
          sass: {
            files: ['sass/**'],
            tasks: ['compass:dev'],
            options: {
              spawn: false,
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

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-grunticon');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');

    grunt.registerTask('prepare_scripts', [
        'copy:scripts',
        'concat:scripts'
    ]);

    grunt.registerTask('build', [
        'compass:dev',
        'prepare_scripts',
        'grunticon'
    ]);

    grunt.registerTask('dist', [
       
        'compass:dist',
        'prepare_scripts',
        'grunticon',
        'uglify:scripts'
    ]);
    
};
