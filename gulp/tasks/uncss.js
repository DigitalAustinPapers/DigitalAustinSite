// This needs some work to ignore relevant selectors before it can be used

'use strict';

var gulp = require('gulp'),
    uncss = require('gulp-uncss'),
    config = require('../config').uncss;


gulp.task('uncss', function() {
  gulp.src(config.src)
      .pipe(uncss({
        html: [
            'https://ui.library.unt.edu/digitalaustinpapers/index',
            'https://ui.library.unt.edu/digitalaustinpapers/search',
            'https://ui.library.unt.edu/digitalaustinpapers/browse',
            'https://ui.library.unt.edu/digitalaustinpapers/about',
            'https://ui.library.unt.edu/digitalaustinpapers/contact'
        ],
        ignore: [
            '#browse-results',
            '.collapse',
            '.glyphicon-plus',
            '.glyphicon-minus'
        ]
      }))
      .pipe(gulp.dest(config.dest));
});