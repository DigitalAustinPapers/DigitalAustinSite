/*
 * Toggle collapsing elements in results
 */

function expandAll(button) {
    button.html('Collapse all').attr('data-state', 'expanded');
    $('#browse-results').find('.collapse').each(function() {
        $(this).collapse('show');
        $('#browse-results').find('.fa-plus').addClass('fa-minus').removeClass('fa-plus');
    });
}

function collapseAll(button) {
    button.html('Expand all').attr('data-state', 'collapsed');
    $('#browse-results').find('.collapse').each(function() {
        $(this).collapse('hide');
        $('#browse-results').find('.fa-minus').addClass('fa-plus').removeClass('fa-minus');
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
    if($(this).find('.fa').hasClass('fa-plus')) {
        $(this).find('.fa').addClass('fa-minus').removeClass('fa-plus');
    } else if($(this).find('.fa').hasClass('fa-minus')) {
        $(this).find('.fa').addClass('fa-plus').removeClass('fa-minus');
    }
});


/*
 * Jumplist
 */

//
var jumpMap = {},
    count = 0,
    $prev,
    $curr;

$('.browse-list__category').each(function() {
    $curr = $(this).attr('data-sort-letter');
    count += 1;
    if ($curr != $prev) {
        jumpMap[$curr] = count;
    }
    $prev = $curr;
});

$('.jumplist__item').each(function() {
    if (!($(this).attr('data-sort-letter') in jumpMap)) {
        $(this).css('display', 'none');
    }
}).on('click', function() {
    $paging.setPage(Math.ceil(jumpMap[$(this).attr('data-sort-letter')] / $('.browse-list__category').length));
    $('.jumplist').find('.active').removeClass('active');
    $(this).addClass('active');
    return false;
});

$('.jumplist').slideDown(500);

/*
 * Pagination
 */

// Selector for list to be populated with paginated results
var $listId = $('#browse-results');
// Selector for list content to be paginated
var $categorizedResults = $listId.find('> li');

pagingOpts['onSelect'] = function(page) {
    updatePage($listId, $categorizedResults, this.slice);
    showPager(this.pages);
};

var $paging = $(".pagination").paging(totalHeadings, pagingOpts);
$paging.setOptions({onSelect: function(page) {
    updatePage($listId, $categorizedResults, this.slice);
    showPager(this.pages);
}});

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