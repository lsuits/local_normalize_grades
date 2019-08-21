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

require_once($CFG->dirroot . '/local/normalize_grades/lib.php');

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_normalize_grades', get_string('pluginname', 'local_normalize_grades'));
    
    $settings->add(
            new admin_setting_heading('normalize_grade_config', get_string('ngc', 'local_normalize_grades'), null));

    $options['user'] = get_string('pluginname', 'gradereport_user');
    $options['overview'] = get_string('pluginname', 'gradereport_overview');

    $settings->add(new admin_setting_configselect('normalize_grades_reportkey', get_string('reportkey', 'local_normalize_grades'),
                       get_string('reportkeyhelp', 'local_normalize_grades'), 'user', $options));

    $settings->add(new admin_setting_configtext('normalize_grades_prefix', 'Course Prefix to limit by', 'Enter a course prefix here', ''));

    $settings->add(new admin_setting_configcheckbox('normalize_grades_verbose', 'Verbose output logging?', 'Enable verbose output logging.', '0'));

    $ADMIN->add('localplugins', $settings);
}
