YUI.add("moodle-local_normalize_grades-testreport",function(e,t){M.local_normalize_grades=M.local_normalize_grades||{},M.local_normalize_grades.testreport={init:function(t){var n=new e.DataTable({columns:["limiter","courseid","userid","itemid","originalgrade","timemodified"],data:t,sortable:!0,scrollable:"y",height:"600px"});n.render("#report")}}},"@VERSION@",{requires:["datatable","datatable-scroll"]});