var gulp = require('gulp');
var path = require('path');
var config = require('../config').less;
var $ = require('gulp-load-plugins')();

gulp.task('less', function () {
  return gulp.src(config.src)
    .pipe($.changed(config.dest))
    .pipe($.less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest(config.dest));
});
