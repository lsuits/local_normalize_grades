<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Display simple tabular data
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once 'lib.php';

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/normalize_grades/classes/gradelib.php');

global $SITE,$PAGE, $USER;
require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/normalize_grades/test.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_course($SITE);
$PAGE->set_title(get_string('pluginname', 'local_normalize_grades'));
$PAGE->set_heading('Normalize Grades Test');

$lngName = get_string('pluginname', 'local_normalize_grades');
$PAGE->navbar->add($lngName);

if(is_siteadmin($USER)){
    $output = $PAGE->get_renderer('local_normalize_grades');
    echo $output->header();


    $calculatedData = normalize::get_all_calculated_grade_data();
    $calculatedtable = new html_table();
    $calculatedtable->head = array('ID', 'Limiter','Course ID', 'User ID' , 'Grade Item ID', 'Grade Grade ID', 'Original Grade', 'Calculated Grade', 'Time Modified');
    $calculatedtable->data = $calculatedData;
    echo html_writer::table($calculatedtable);

    $generatedData = normalize::get_grade_data();

/*
    $generatedtable = new html_table();
    $generatedtable->head = array('Limiter','Course ID', 'User ID' , 'Grade Item ID', 'Original Grade', 'Time Modified');
    $generatedtable->data = $generatedData;
    echo html_writer::table($generatedtable);
*/

    $n=0;

    foreach ($generatedData as $datum) {
        $n++;
        // Set up the user object for future use.
        $user = $DB->get_record('user', array('id' => $datum->userid));

        // Set up the course object for future use.
        $course = get_course($datum->courseid);

        $grades = ng_grade_formats($course, $user);
        echo html_writer::tag('p', $n . ": " . $grades);
    }

/*
    $table2 = new table_sql('uniqueid');
    $headers = array('Limiter','Course ID', 'User ID' , 'Grade Item ID', 'Original Grade', 'Time Modified');
    $table2->define_headers($headers);
    $columns = array('limiter','courseid', 'userid' , 'itemid', 'originalgrade', 'timemodified');
    $table2->define_columns($columns);
    $table2->define_baseurl("$CFG->wwwroot/local/normalize_grades/test.php");
    $table2->set_sql('CONCAT(gi.courseid, " ", gg.userid, " ", gi.id) AS limiter,
                    gi.courseid AS courseid,
                    gg.userid AS userid,
                    gi.id AS itemid,
                    gg.finalgrade AS originalgrade,
                    gg.timemodified',
                    "{grade_items} gi
                    INNER JOIN {grade_grades} gg ON gg.itemid = gi.id",
                'gi.itemtype = \'course\'');
    $table2->out(10000, false);

    $reportData = new test_report();
    echo $output->render($reportData);
*/

    echo $output->footer();

} else {
    echo'nope';
}
?>
