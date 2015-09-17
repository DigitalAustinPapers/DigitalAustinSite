'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    config = require('../config').sass;


gulp.task('sass', function() {
  gulp.src(config.src)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
      .pipe(sass({outputStyle: 'compressed'}))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(config.dest));
});