
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
       
        watch: {
          sass: {
            files: ['sass/**'],
            tasks: ['compass:dev'],
            options: {
              spawn: false,
            },
          },
    },
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-grunticon');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('build', [
        'grunticon',
        'compass:dist'
    ]);
    
};
