/* This is the custom pagination settings shared throughout the site.
 * This script should be loaded before page-specific code that
 * overrides settings further.
 * https://github.com/infusion/jQuery-Paging
 */

// Default paging options to be overridden later
var pagingOpts = {
  format: '[< nncnn >]', // define how the navigation should look like and in which order onFormat() gets called
  perpage: 10, // elements per page
  lapping: 0, // don't overlap pages for the moment
  page: 1, // start at page, can also be "null" or negative
  onSelect: function (page) {
    showPager();
  },
  onFormat: function (type) {
    switch (type) {
      case 'block': // n and c
        if (this.page === this.value)
          return '<li class="active">' +
              '<a href="#">' + this.value + '</a>' +
              '</li>';
        return '<li>' +
            '<a href="#">' + this.value + '</a>' +
            '</li>';
      case 'next': // >
        if (this.active) {
          return '<li><a href="#" aria-label="Next">' +
              '<span aria-hidden="true">&raquo;</span>' +
              '</a></li>';
        }
        return '<li class="disabled"><a href="#" aria-label="Next">' +
            '<span aria-hidden="true">&raquo;</span>' +
            '</a></li>';
      case 'prev': // <
        if (this.active) {
          return '<li><a href="#" aria-label="Previous">' +
              '<span aria-hidden="true">&laquo;</span>' +
              '</a></li>';
        }
        return '<li class="disabled"><a href="#" aria-label="Previous">' +
            '<span aria-hidden="true">&laquo;</span>' +
            '</a></li>';
      case 'first': // [
        if (this.active) {
          return '<li><a href="#">first</a></li>';
        }
        return '<li class="disabled"><a href="#">first</a></li>';
      case 'last': // ]
        if (this.active) {
          return '<li><a href="#">last</a></li>';
        }
        return '<li class="disabled"><a href="#">last</a></li>';
    }
  }
};

// Function used in onSelect to reveal pager
function showPager(pages) {
  var $pagination = $('.pagination');
  if (pages > 1) {
    $pagination.removeClass('hidden');
  } else {
    if (!$pagination.hasClass('hidden')) {
      $pagination.addClass('hidden');
    }
  }
}