module.exports = function(grunt) {
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.initConfig({

    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          // target.css file: source.less file
          "wp-content/themes/v2w/library/css/style.css": "wp-content/themes/v2w/library/less/style.less"
        }
      }
    },

    watch: {
      styles: {
        files: ['wp-content/themes/v2w/library/less/**/*.less'], // which files to watch
        tasks: [ 'less' ],
        options: {
          nospawn: true
        }
      }
    }
  });

  grunt.registerTask( 'build', [ 'less' ] );
  grunt.registerTask( 'default', [ 'build', 'watch' ] );
};