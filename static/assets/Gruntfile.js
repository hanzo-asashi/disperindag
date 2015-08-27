module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {

      },
      dist: {
        options: {
          outputStyle: 'compressed'
        },
        files: {
          'css/app.min.css': 'src/scss/app.scss'
        }
      },
      dev: {
        options: {
          outputStyle: 'nested'
        },
        files: {
          'css/app.css': 'src/scss/app.scss'
        }
      }
    },

    concat: {
      options: {
        separator: ';',
        banner: '\n',
      },
      vendor: {
        src: [
          '../../assets/bower_components/jquery/dist/jquery.js',
          'libs/js/gsap/main-gsap.js',
          'libs/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js',
          '../../assets/bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
          'libs/js/joinable.js',
          'libs/js/resizeable.js',
          'libs/js/neon-api.js',
          'libs/js/bootstrap-tagsinput.min.js',
          'libs/js/morris.min.js',
          'libs/js/jquery.validate.min.js',
          'libs/js/dropzone/dropzone.js',
          'libs/js/neon-mail.js',
          'libs/js/neon-login.js',
          'libs/js/neon-custom.js',
          'libs/js/neon-demo.js',
          // 'bower_components/sidr/jquery.sidr.min.js',
          // 'libs/parallax/parallax.js',
          'src/js/custom.js',
        ],
          // foundationJsPrefix + 'topbar.js',
        dest: 'js/vendor.js'
      }
    },

    uglify: {
      options: {
        mangle: true
      },
      target: {
        files: {
          'js/vendor.min.js':['js/vendor.js']
        }
      }
    },

    // removelogging: {
    //   js: {
    //     src: "js/app.min.js",
    //     dest: "js/app.min.js"
    //   }
    // },

    watch: {
      grunt: {
        files: ['Gruntfile.js'],
        tasks: ['development-task']
      },
      sass: {
        files: 'src/scss/**/*.scss',
        tasks: ['development-task']
      },
      js: {
        files: 'src/js/**/*.js',
        tasks: ['development-task']
      }
    }
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.registerTask('development-task', ['sass:dev','concat:vendor']);
  grunt.registerTask('production-task', ['sass:dist','uglify']);

  grunt.registerTask('build', ['production-task']);
  grunt.registerTask('default', ['development-task','watch']);
};
