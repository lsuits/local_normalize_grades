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
 * Renderer for local_normalize_grades
 *
 * @package    local_normalize_grades
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
require_once $CFG->libdir.'/tablelib.php';
require_once 'lib.php';

/**
 * normalize_grades verification status local rendrer
 *
 * @package    local_normalize_grades
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_normalize_grades_renderer extends plugin_renderer_base {

    public function render_test_report(test_report $report) {
        $this->page->requires->yui_module(
            'moodle-local_normalize_grades-testreport',
            'M.local_normalize_grades.testreport.init',
            array(array_values($report->data)));
        $out  = $this->output->heading("Normalize Grades Test Page");
        $out .= html_writer::tag('div', '', array("id"=>"report"));
        return $this->output->container($out);
    }
}

class test_report implements renderable {
    public $data;
    public function __construct() {
        $this->data = normalize::get_grade_data();
    }
}
?>
