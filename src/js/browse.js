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

$(document).on('click', '.browse-list__category-header > a', function() {
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

pagingOpts['onSelect'] = function(page) {
  updatePage(listId, categorizedResults, this.slice);
  showPager(this.pages);
};

$(".pagination").paging(totalHeadings, pagingOpts);

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