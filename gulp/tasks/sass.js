'use strict';

// Using stream-combiner2 to catch errors per gulp at:
// https://github.com/gulpjs/gulp/blob/master/docs/recipes/combining-streams-to-handle-errors.md#combining-streams-to-handle-errors

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    combiner = require('stream-combiner2'),
    config = require('../config').sass;


gulp.task('sass', function() {
  var combined = combiner.obj([
    gulp.src(config.src),
        sourcemaps.init(),
        sass().on('error', sass.logError),
        sass({outputStyle: 'compressed'}),
        sourcemaps.write('./'),
        gulp.dest(config.dest)
  ]);

  combined.on('error', console.error.bind(console));

  return combined;
});

gulp.task('sass:watch', function() {
  gulp.watch(config.srcdir + '*.scss', ['sass']);
});