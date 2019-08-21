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
 * @package    block_quickmail
 * @copyright  2019 onwards Louisiana State University, LSUOnline
 * @copyright  2019 onwards Robert Russo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local//normalize_grades/classes/gradelib.php');

/**
 * Creates an error banner on any page where hidden grade calculation settings differ among reports.
 *
 * @return Standard moodle notification.
 */
function local_normalize_grades_reportmismatch() {
    global $CFG, $USER, $PAGE;
    // Get the courseid for the course.
    if($PAGE->course->id == SITEID || !isset($PAGE->course->id)) {
        return;
    }

    $courseid = $PAGE->course->id;
    // Get the context for this course.
    $coursecontext = $PAGE->context;

    // Check to see if the user is a teacher or not.
    $isteacher = (has_capability('moodle/grade:edit', $coursecontext));

    // If this is a teaching course and the user is able to modify grades, do stuff.
    if ($courseid <> SITEID && $isteacher) {

        // Get the value for the Overview Report.
        $overviewrpt = grade_get_setting($courseid, 'report_overview_showtotalsifcontainhidden', $CFG->grade_report_overview_showtotalsifcontainhidden);

        // Get the value for the User Report.
        $userrpt = grade_get_setting($courseid, 'report_user_showtotalsifcontainhidden', $CFG->grade_report_user_showtotalsifcontainhidden);

        // Get the value for the Forecast Report, if it exists.
        if ($CFG->grade_report_forecast_showtotalsifcontainhidden) {
            $forecastrpt = grade_get_setting($courseid, 'report_forecast_showtotalsifcontainhidden', $CFG->grade_report_forecast_showtotalsifcontainhidden);
        } else {

            // Otherwise, use the value of the User report.
            $forecastrpt = $userrpt;
        }

        // Create the link to the settings page so the user can fix the problem.
        $coursesettingslink = $CFG->wwwroot . '/grade/edit/settings/index.php?id=' . $courseid;

        // If anything is wrong, figure out what is wrong.
        if ($overviewrpt <> $userrpt || $userrpt <> $forecastrpt) {

            // If the overview report and user report do not match.
            if ($overviewrpt <> $userrpt) {

                // Set the error string appropriately.
                $errorreport = get_string('orur_reportmismatch', 'local_normalize_grades');

            // If the forecast report and user report do not match.
            } else {

                // Set the error string appropriately.
                $errorreport = get_string('ur-fcr_reportmismatch', 'local_normalize_grades');
            }

            // Build the link and fetch the built string.
            $errorlink = get_string('link', 'local_normalize_grades', $coursesettingslink);

            // Fetch the consistency string.
            $errorconsistency = get_string('consistent', 'local_normalize_grades');

            // Build the errors.
            $errors = $errorreport . '<br>' . $errorlink . '<br>' . $errorconsistency;

            // Return the Moodle notification as an error.
            return \core\notification::error($errors);
        }
    }
}

/**
 * Adds links to the given navigation node if caps are met.
 *
 * @param navigation_node $navigationnode The navigation node to add the question branch to
 * @param object $context
 * @return navigation_node Returns the question branch that was added
 */
function local_normalize_grades_extend_navigation(global_navigation $navigationnode) {
    global $USER, $PAGE;

    // Only build the navigation out for site administrators.
    if (!is_siteadmin($USER)) {
        return;
    }

    // Set up the navigation node.
    $normalizenode = $navigationnode->add('Normalize', null, navigation_node::TYPE_CONTAINER, null);

    // Add the node.
    $normalizenode->add('test page', new moodle_url('/local/normalize_grades/test.php'), navigation_node::TYPE_SETTING, null);

    // Return the node.
    return $normalizenode;
}

// Run the mismatch checker and build the error if needed.
local_normalize_grades_reportmismatch();
