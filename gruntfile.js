module.exports = function (grunt) {
  grunt.initConfig({
  sass: {                              // Task
    dist: {                            // Target
      options: {                       // Target options
        style: 'expanded'
      },
      files: {                         // Dictionary of files
        'assets/css/style.css': 'assets/sass/main.scss',       // 'destination': 'source'
      }
    }
  },
  watch: {
    sass: {
      files: 'assets/sass/*.scss',
      tasks: ['sass']
    }
  }
});

grunt.loadNpmTasks('grunt-contrib-sass');
grunt.loadNpmTasks('grunt-contrib-watch');

grunt.registerTask('default', ['sass']);
}