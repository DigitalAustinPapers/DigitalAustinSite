// Expand and collapse all nodes and update expand/collapse button
$('#collapse-expand-all').on('click', function(e) {
    if($(this).attr('data-state') == 'expand') {
        $(this).html('Collapse all').attr('data-state', 'collapse');
        $('.collapse').each(function() {
            $(this).collapse('show');
        });
    } else if($(this).attr('data-state') == 'collapse') {
        $(this).html('Expand all').attr('data-state', 'expand');
        $('.collapse').each(function() {
            $(this).collapse('hide');
        });
    }
});

var categorizedResults = $('#browse-results').find('li');
console.log('categorizedResults = '+categorizedResults);

$(".pagination").paging(1337, { // make 1337 elements navigatable
    format: '[< ncnnn! >]', // define how the navigation should look like and in which order onFormat() get's called
    perpage: 10, // show 10 elements per page
    lapping: 0, // don't overlap pages for the moment
    page: 1, // start at page, can also be "null" or negative
    onSelect: function (page) {
        updatePage(page);
        console.log('this inside paging = '+this);
    },
    onFormat: function (type) {
        switch (type) {
            case 'block': // n and c
                return '<li><a href="#">' + this.value + '</a></li>';
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

function updatePage(newPage) {
    var resultsList = $('#browse-results');
    resultsList.empty();
    resultsList.append(categorizedResults[0]);
    console.log('newPage = '+newPage);
}