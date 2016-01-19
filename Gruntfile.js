module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        compass: {
            options: {
                config: 'config.rb'
            },
            dist: {},
            server: {
                options: {
                    debugInfo: true
                }
            }
        },
        cssmin: {
            target: {
                files: {
                    'app/styles/main.css': [ '.tmp/styles/*.css' ]
                }
            }
        },
        grunticon: {
            icons: {
                files: [{
                    expand: true,
                    cwd: 'app/styles/icons/svgs/',
                    src: '*.svg',
                    dest: 'app/styles/icons'
                }]
            },
            options: {
                pngfolder:'app/images/icons/',
                enhanceSVG: true
            }
        },
        copy: {
            dest: {
                files: [{
                    src: '.tmp/styles/blog-specific.css',
                    dest: 'app/styles/blog-specific.css'
                }]
            }
        }
    });

    grunt.loadNpmTasks('grunt-grunticon');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('build', [
        'grunticon',
        'compass:dist',
        'cssmin'
    ]);
    
    grunt.registerTask('watch', [
        'compass:server',
        'copy'
    ]);
};
