var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('../config').images;

gulp.task('images', function () {
	return gulp.src(config.src)
		.pipe($.changed(config.dest))
		.pipe($.imagemin({
			progressive: true,
			interlaced: true
		}))
		.pipe(gulp.dest(config.dest))
		.pipe($.size({
			title: 'images'
		}))
    ;
});
