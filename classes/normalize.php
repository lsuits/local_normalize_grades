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

// Building the class for the task to be run during scheduled tasks
class normalize {

    /**
     * Returns the appropriate data for use in checking and creating normalized grades.
     *
     * @return mixed $data
     */
    public static function get_stored_grade_data() {
        global $CFG, $DB;

        $gradebookroles = $CFG->gradebookroles;
        // SQL for course level grades for every student in the system.
        $sql = "SELECT
                    CONCAT(gi.courseid, ' ', gg.userid, ' ', gi.id) AS limiter,
                    gi.courseid AS courseid,
                    gg.userid AS userid,
                    gi.id AS itemid,
                    gg.finalgrade AS originalgrade,
                    gg.timemodified
                FROM {grade_items} gi
                    INNER JOIN {grade_grades} gg ON gg.itemid = gi.id
                    INNER JOIN {context} cx ON gi.courseid = cx.instanceid
                        AND cx.contextlevel = '50'
                    INNER JOIN {role_assignments} ra ON cx.id = ra.contextid AND gg.userid = ra.userid
                    INNER JOIN {role} r ON ra.roleid = r.id
                WHERE gi.itemtype = 'course' AND gg.userid > 1 AND r.id IN ($gradebookroles)
                GROUP BY limiter";

        $lsusql = "SELECT
                    CONCAT(gi.courseid, ' ', gg.userid, ' ', gi.id) AS limiter,
                    gi.courseid AS courseid,
                    gg.userid AS userid,
                    gi.id AS itemid,
                    gg.finalgrade AS originalgrade,
                    gg.timemodified
                FROM {grade_items} gi
                    INNER JOIN {grade_grades} gg ON gg.itemid = gi.id
                    INNER JOIN {enrol_ues_students} stu ON gg.userid = stu.userid
                    INNER JOIN {course} c ON gi.courseid = c.id
                    INNER JOIN {enrol_ues_sections} sec ON sec.idnumber = c.idnumber
                    INNER JOIN {enrol_ues_semesters} sem ON sem.id = sec.semesterid
                WHERE gi.itemtype = 'course'
                    AND gg.userid > 1
                    AND c.idnumber IS NOT NULL
                    AND c.idnumber <> ''
                    AND sem.classes_start <= UNIX_TIMESTAMP()
                    AND sem.grades_due >= UNIX_TIMESTAMP()
                GROUP BY limiter";

    $data = new stdCLass;
    $dbman = $DB->get_manager();
    // Get the data as defined in the SQL.
    if (!$dbman->table_exists('enrol_ues_courses')) {
        $data = $DB->get_records_sql($lsusql);
    } else {
        $data = $DB->get_records_sql($sql);
    }

    // Return this ginourmous bit of data to loop through.
    return $data;
    }

    /**
     * Returns the normalized grade record that matches the limiter.
     *
     * @param string $limiter
     * @return mixed $data (single record)
     */
    public static function get_calculated_grade_data($limiter) {
        global $DB;

        $data = new stdCLass;
        $data = $DB->get_record('normalize_grades', array('limiter' => $limiter), '*', $strictness=IGNORE_MISSING);
        return $data;
    }

    /**
     * Returns all precalculated normalized grade records.
     *
     * @param string $limiter
     * @return mixed $data (single record)
     */
    public static function get_all_precalculated_grade_data() {
        global $DB;

    $data = new stdCLass;
    $data = $DB->get_records('normalize_grades');
    return $data;
    }

    /**
     * Checks to see if the original grade data matches the normalized original grade.
     * Deletes the normalized item if it exsits, but does not match.
     *
     * @param string $limiter
     * @param string $timemodified
     * @param string $originalgrade
     * @return bool
     */
    public static function check_grade_new_updated($limiter, $originalgrade) {
        global $DB;
        $calculated = !empty($originalgrade) ? null : self::get_calculated_grade_data($limiter);
        if (!empty($calculated)) {
            // This is a sanity check to ensure we did not return an outdated or modified grade/item.
            if ($originalgrade != $calculated->originalgrade) {
                // It looks like the record has been updated since the last time we checked.
                return true;
            } else {
               // We found the record and nothing changed.
               return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Adds the new grade item based on the data provided.
     *
     * @param string $data
     * @return int (normalize_grades.id)
     */
    public function add_grade_new($data) {
        // Add the grade item to the normalize_grades table and return the id for use.
        return $DB->insert_record('normalize_grades', $data, $returnid=true, $bulk=false);
    }
}
