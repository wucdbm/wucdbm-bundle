$('.filter-date-range').on('click', '.date-range-clear', function(event) {
    var element = $(event.target).parents('.filter-date-range');
    element.find('.min').val('');
    element.find('.max').val('');
    element.find('.placeholder').val('');
    element.parents('form').submit();
    event.stopPropagation();
}).each(function (index, element) {
    var minVal = $(element).find('.min').val();
    var min;
    if (minVal) {
        min = moment(minVal, ['YYYY-MM-DD']);
    } else {
        min = moment().subtract(29, 'days');
    }
    var maxVal = $(element).find('.max').val();
    var max;
    if (maxVal) {
        max = moment(maxVal, ['YYYY-MM-DD']);
    } else {
        max = moment().add(1, 'day');
    }
    if (minVal && maxVal) {
        $(element).find('.placeholder').val(min.format('MMMM D, YYYY') + ' - ' + max.format('MMMM D, YYYY'));
    }
    $(element).daterangepicker({
            opens: 'right',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            showDropdowns: true,
            startDate: min,
            endDate: max,
            maxDate: moment().add(14, 'days')
        }, function (start, end) {
            var element = $(this.element);
            var placeholder = element.find('.placeholder');
            placeholder.val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var min = element.find('.min');
            min.val(start.format('YYYY-MM-DD'));
            var max = element.find('.max');
            max.val(end.format('YYYY-MM-DD'));
            element.parents('form').submit();
        }
    )
});