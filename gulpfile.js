var gulp = require('gulp');
var watch = require('gulp-watch');
var rename = require('gulp-rename');
var plumber = require('gulp-plumber');

gulp.task('css', function () {
  var stylus = require('gulp-stylus');
  var nano = require('gulp-cssnano');
  var postcss    = require('gulp-postcss');
  var sourcemaps = require('gulp-sourcemaps');

  return gulp.src('./assets-src/*.styl')
    .pipe(plumber())
    .pipe( stylus())
    .pipe( sourcemaps.init() )
    .pipe( postcss([ require('autoprefixer'), require('precss') ]) )
    .pipe( gulp.dest('./assets/css') )
    .pipe( nano())
    .pipe( rename({ extname: '.min.css' }))
    .pipe( sourcemaps.write('.') )
    .pipe( gulp.dest('./assets/css') );
});

gulp.task("watch", function() {
  watch("assets-src/**/*.styl", function() {
    gulp.start("css");
  });
});
