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
    public function get_moodle_grade_data() {
        global $DB;

        $sql = "SELECT
                    gi.courseid AS courseid,
                    gg.userid AS userid,
                    gi.id AS itemid,
                    gg.finalgrade AS originalgrade,
                    gg.timemodified
                FROM mdl_grade_items gi
                    INNER JOIN mdl_grade_grades gg ON gg.itemid = gi.id
                WHERE gi.itemtype = 'course'";

    $data = new stdCLass;
    $data = $DB->get_records_sql($sql);
    return $data;
    }

    public function get_calculated_grade_data() {
        global $DB;

        $sql = "SELECT *
                FROM mdl_normalized_grades ng";

    $data = new stdCLass;
    $data = $DB->get_records_sql($sql);
    return $data;
    }
}
