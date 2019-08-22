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
 *
 * Class for building the scheduled task to store normalized STUDENT grades.
 *
 * @package    local_normalize_grades
 * @copyright  2019 onwards Louisiana State University, LSUOnline
 * @copyright  2019 onwards Robert Russo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') or die();

require_once($CFG->dirroot . '/local/normalize_grades/classes/normalize.php');
require_once($CFG->dirroot . '/local/normalize_grades/classes/gradelib.php');

// Building the class for the task to be run during scheduled tasks.
abstract class local_normalize {

    /**
     * Master function for calculating and storing normalized STUDENT grades.
     *
     * Course grade visibility settings are as follow
     * 0 is HIDE the course total if the course contains hidden items.
     * 1 is EXCLUDE hidden items in the total if the course contains hidden items.
     * 2 is INCLUDE hidden items in the total if the course contains hidden items.
     *
     * @return boolean
     */
    public static function run_normalize_grades() {
        global $CFG, $DB;

        // Get all grades that should be processed.
        $generateddata = normalize::get_all_stored_grade_data();

        // Set this up for later.
        $count = 0;
        $starttotaltime = microtime(true);

        // Loop through all grades from above.
        foreach ($generateddata as $datum) {

            // Get the user object for later.
            $user = $DB->get_record('user', array('id' => $datum->userid));

            // Get the course for later.
            $course = get_course($datum->courseid);

            // Get the course grade settings for the designated report.
            $coursegradesetting = grade_report_normalize_grades::get_course_setting($course->id);
            $coursestoredsetting = $DB->get_record('normalize_grades', array('limiter' => $datum->limiter), '*');
            $storedsetting = !empty($coursestoredsetting) ? $coursestoredsetting->storedsetting : $coursegradesetting;

            // Check to see if the grade changed at all.
            $process = normalize::check_grade_new_updated(
                           $datum->limiter,
                           $datum->originalgrade,
                           $coursegradesetting,
                           $storedsetting,
                           $datum->timemodified);

            // If the grade has changed, process it.
            if ($process == true) {

                // Increment the count and grab the time.
                $count++;
                $starttime = microtime(true);

                // Actually process the grade for the user / course combo.
                ng_grade_formats($course, $user);

                // Verbose logging.
                if ($CFG->normalize_grades_verbose == 1) {
                    $endtime = microtime(true);
                    $timediff = $endtime - $starttime;
                    $verboselog = get_string('cu_verbose', 'local_normalize_grades', [
                                                                                      'fn' => $user->firstname,
                                                                                      'ln' => $user->lastname,
                                                                                      'cfn' => $course->fullname,
                                                                                      'td' => round($timediff, 3)
                                                                                     ]);
                    echo($verboselog . "\n");
                }
            }
        }

        // Time for the process with averages.
        $endtotaltime = microtime(true);
        $totaltime = $endtotaltime - $starttotaltime;
        $average = $count > 0 ? $totaltime / $count : 0;

        // Get the log strings.
        $averagelog = get_string('ng_average_logs', 'local_normalize_grades', ['tt' => round($totaltime, 0), 'ct' => $count]);
        $totallog = get_string('ng_total_logs', 'local_normalize_grades', ['av' => round($average, 3)]);

        // Output the logs.
        echo($averagelog . "\n" . $totallog . "\n\n");
    }
}
