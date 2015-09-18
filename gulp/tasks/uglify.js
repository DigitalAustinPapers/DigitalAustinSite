'use strict';

var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    config = require('../config').scripts;

gulp.task('uglify', function() {
  return gulp.src(config.src)
    .pipe(uglify())
    .pipe(gulp.dest(config.dest));
});