// Expand and collapse all nodes and update expand/collapse button
$('#collapse-expand-all').on('click', function(e) {
    if($(this).attr('data-state') == 'expand') {
        console.log('it says expand');
        $(this).html('Collapse all').attr('data-state', 'collapse');
        $('.collapse').each(function() {
            $(this).collapse('show');
        });
    } else if($(this).attr('data-state') == 'collapse') {
        console.log('it says collapse');
        $(this).html('Expand all').attr('data-state', 'expand');
        $('.collapse').each(function() {
            $(this).collapse('hide');
        });
    }
});

