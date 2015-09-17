var dest = './public',
    src = './src',
    vendor = './node_modules';

module.exports = {
  sass: {
    src: src + '/scss/style.scss',
    dest: dest + '/css/'
  },
  uncss: {
    src: dest + '/css/style.css',
    dest: dest + '/css/'
  },
  scripts: {
    src: src + '/js/*.js',
    dest: dest + '/js',
    vendor_src: dest + '/js/vendor',
    common_libs: {
      jquery: vendor + '/jquery/jquery.min.js',
      bootstrap: vendor + '/bootstrap-sass/assets/javascripts/bootstrap.min.js',
      d3: vendor + '/d3/d3.min.js',
      jquery_paging: vendor + '/jquery-paging/jquery.paging.min.js',
      html5shiv: vendor + '/html5shiv/dist/html5shiv.min.js',
      respondjs: vendor + '/respond.js/dest/respond.min.js',
    }
  },
  browserify: {
    // A separate bundle will be generated for each
    // bundle config in the list below
    bundleConfigs: [{
      entries: src + '/js/browse.js',
      dest: dest + '/js',
      outputName: 'browse.js',
      // list of externally available modules to exclude from the bundle
      external: ['jquery']
    },{
      entries: src + '/js/results.js',
      dest: dest + '/js',
      outputName: 'results.js',
      // list of externally available modules to exclude from the bundle
      external: ['jquery']
    }
    ]
  }
};