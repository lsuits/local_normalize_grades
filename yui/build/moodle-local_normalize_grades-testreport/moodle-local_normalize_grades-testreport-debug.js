YUI.add('moodle-local_normalize_grades-testreport', function (Y, NAME) {

M.local_normalize_grades = M.local_normalize_grades || {};
M.local_normalize_grades.testreport = {
  init: function(data) {

    var table = new Y.DataTable({
        columns:    ['limiter','courseid', 'userid','itemid', 'originalgrade', 'timemodified'],
        data:       data,
        sortable:   true,
        scrollable: 'y',
        height:     '600px'
    });

    table.render('#report');
  }
};

}, '@VERSION@', {"requires": ["datatable", "datatable-scroll"]});
