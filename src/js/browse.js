/*
 * Toggle collapsing elements in results
 */

function expandAll(button) {
  button.html('Collapse all').attr('data-state', 'expanded');
  $('#browse-results .collapse').each(function() {
    $(this).collapse('show');
    $('#browse-results .glyphicon-plus').addClass('glyphicon-minus').removeClass('glyphicon-plus');
  });
}

function collapseAll(button) {
  button.html('Expand all').attr('data-state', 'collapsed');
  $('#browse-results .collapse').each(function() {
    $(this).collapse('hide');
    $('#browse-results .glyphicon-minus').addClass('glyphicon-plus').removeClass('glyphicon-minus');
  });
}

// Expand and collapse all nodes and update expand/collapse button
$('#collapse-expand-all').on('click', function(e) {
  if($(this).attr('data-state') == 'collapsed') {
    expandAll($(this));
  } else if($(this).attr('data-state') == 'expanded') {
    collapseAll($(this));
  }
});

$(document).on('click', '.browse-list__category-header', function() {
  if($(this).find('.glyphicon').hasClass('glyphicon-plus')) {
    $(this).find('.glyphicon').addClass('glyphicon-minus').removeClass('glyphicon-plus');
  } else if($(this).find('.glyphicon').hasClass('glyphicon-minus')) {
    $(this).find('.glyphicon').addClass('glyphicon-plus').removeClass('glyphicon-minus');
  }
});

/*
 * Pagination
 */

// Selector for list to be populated with paginated results
var listId = $('#browse-results');
// Selector for list content to be paginated
var categorizedResults = $('#browse-results > li');

$(".pagination").paging(totalHeadings, { // # elements navigatable
  format: '[< nncnn! >]', // define how the navigation should look like and in which order onFormat() get's called
  perpage: 10, // show 10 elements per page
  lapping: 0, // don't overlap pages for the moment
  page: 1, // start at page, can also be "null" or negative
  onSelect: function (page) {
    updatePage(listId, categorizedResults, this.slice);
    var $pagination = $('.pagination');
    if (this.pages > 1) {
      $pagination.removeClass('hidden');
    } else {
      if (!$pagination.hasClass('hidden')) {
        $pagination.addClass('hidden');
      }
    }
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
        return '<li><a href="#" aria-label="Next">' +
          '<span aria-hidden="true">&raquo;</span>' +
          '</a></li>';
      case 'prev': // <
        return '<li><a href="#" aria-label="Previous">' +
          '<span aria-hidden="true">&laquo;</span>' +
          '</a></li>';
      case 'first': // [
        return '<li><a href="#">first</a></li>';
      case 'last': // ]
        return '<li><a href="#">last</a></li>';
    }
  }
});

function updatePage(listId, pagedElements, pageSlice) {
  /* Updates the current page when a page button is clicked
   * @param {object} listId A jQuery object of the list to paginate
   * @param {object} pagedElements A jQuery object of the items to paginate within listId
   * @param {array} pageSlice This is an array with 2 values:
   *     The start and end values to slice the page
   */
  var newPage = pagedElements.slice(pageSlice[0], pageSlice[1]);
  collapseAll($('#collapse-expand-all'));

  listId.empty();
  listId.append(newPage);
}