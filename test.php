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
require_once($CFG->dirroot . '/local/normalize_grades/classes/normalize.php');
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

$lngname = get_string('pluginname', 'local_normalize_grades');
$PAGE->navbar->add($lngname);

if(is_siteadmin($USER)){
    $output = $PAGE->get_renderer('local_normalize_grades');
    echo $output->header();


    $calculateddata = normalize::get_all_precalculated_grade_data();
    $calculatedtable = new html_table();
    $calculatedtable->head = array('ID', 'Limiter','Course ID', 'User ID' , 'Grade Item ID', 'Grade Grade ID', 'Original Grade', 'Calculated Grade', 'Time Modified');
    $calculatedtable->data = $calculateddata;
echo'<br /><br /><br /><br />PRE-CALCULATED<br />';
    echo html_writer::table($calculatedtable);

    $generateddata = normalize::get_stored_grade_data();


    $generatedtable = new html_table();
    $generatedtable->head = array('Limiter','Course ID', 'User ID' , 'Grade Item ID', 'Original Grade', 'Time Modified');
    $generatedtable->data = $generateddata;
echo'<br /><br /><br /><br />GENERATED<br />';
    echo html_writer::table($generatedtable);


    $n=0;
echo'<br /><br /><br /><br />GRADES<br />';
    foreach ($generateddata as $datum) {
        $n++;
        // Set up the user object for future use.
        $user = $DB->get_record('user', array('id' => $datum->userid));

        // Set up the course object for future use.
        $course = get_course($datum->courseid);

        $grades = ng_grade_formats($course, $user);
        echo html_writer::tag('p', $n . ": " . $grades);
    }

    echo $output->footer();

} else {
    echo'nope';
}
?>
