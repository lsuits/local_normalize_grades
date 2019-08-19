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
 * @copyright  2019 Robert Russo
 * @copyright  2019 LSUOnline
 */

defined('MOODLE_INTERNAL') or die();

require_once($CFG->dirroot . '/local/normalize_grades/classes/normalize.php');
require_once($CFG->dirroot . '/local/normalize_grades/classes/gradelib.php');

// Building the class for the task to be run during scheduled tasks
abstract class local_normalize {

    /**
     * Master function for calculating and storing normalized STUDENT grades.
     *
     * @return boolean
     */
    public static function run_normalize_grades() {
        global $DB;
        $generateddata = normalize::get_stored_grade_data();
        foreach ($generateddata as $datum) {
            $user = $DB->get_record('user', array('id' => $datum->userid));
            $course = get_course($datum->courseid);
            if (normalize::check_grade_new_updated($datum->limiter, $datum->originalgrade)) {
                ng_grade_formats($course, $user);
            }
        }
    }
}
