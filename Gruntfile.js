module.exports = function(grunt) {

  // 1. All configuration goes here
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      dist: {
        options: {
          noCache: true
        },
        files: {
          'css/styles.css': 'scss/styles.scss',
        }
      }
    },
    watch: {
      grunt: {
        files: ['Gruntfile.js']
      },
      css: {
        files: 'scss/*.scss',
        tasks: ['sass']
      },
    },
    browser_sync: {
      files: {
          src : ['css/*.css', '*.html', 'js/*.js']
      },
      options: {
          watchTask: true,
          proxy: {
            host: 'spca',
            port: '8888'
          }
        }
      }
  });

  // 3. Where we tell Grunt we plan to use this plug-in
  //grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-browser-sync');
  //grunt.loadNpmTasks('grunt-jekyll');
  //grunt.loadNpmTasks('grunt-autoprefixer');
  //grunt.loadNpmTasks('grunt-contrib-concat');
  //grunt.loadNpmTasks('grunt-contrib-imagemin');
  //grunt.loadNpmTasks('grunt-contrib-cssmin');

  // 4. Where we tell Grunt what to do when we type "grunt" into the terminal
  grunt.registerTask('build', ['sass']);
  grunt.registerTask('default', ['build','browser_sync', 'watch']);
};