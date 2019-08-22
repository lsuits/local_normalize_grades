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

// Standard strings.
$string['normalize_grades'] = 'Normalize Grades';
$string['pluginname'] = 'Normalize Grades';
$string['task_name'] = 'Normalize Grades';
$string['task_name_help'] = 'Calculates and stores grades in the DB for easy retreival.';

// Course error message strings.
$string['link'] = 'Please go to <a href = {$a}> to your course grade settings page</a> to fix it.';
$string['consistent'] = 'Please ensure a consistent setting across your reports, otherwise students may be presented with different grades depending on the report they view.';
$string['help'] = 'For more information, please go here.';
$string['orur_reportmismatch'] = '<p><strong>Overview Report\'s</strong> &ldquo;Hide totals if they contain hidden items&rdquo; setting <strong>does not match</strong> the setting value for this course\'s <strong>User Report.</strong></p>';
$string['ur-fcr_reportmismatch'] = '<p><strong>Projected Final Grade Report\'s</strong> &ldquo;Hide totals if they contain hidden items&rdquo; setting <strong>does not match</strong> the setting value for this course\'s <strong>User Report.</strong></p>';

// Logging strings.
$string['cu_verbose'] = 'Grades for {$a->fn} {$a->ln} in {$a->cfn} took {$a->td} seconds to complete.';
$string['ng_average_logs'] = 'Normalize Grades took {$a->tt} seconds to process {$a->ct} final grades.';
$string['ng_total_logs'] = 'Normalize Grades took {$a->av} seconds per user per course to complete.';

// Administrative Settings.
$string['ngc'] = 'Normalized Grade Configuration';
$string['reportkey'] = 'Choose the report you want to key off of for normalization of grades.';
$string['reportkeyhelp'] = '<ul><li>The instructor can choose different settings for including/excluding hidden grades for each of these reports.</li><li>Please choose one to be consistent.</strong></li><li>It\'s also best to instruct your faculty to be consistent on how hidden grades impact a student.</li></ul>';
$string['prefix'] = 'Course prefix';
$string['prefixhelp'] = 'Enter a course prefix to limit the scope of the plugin.';
$string['verbose'] = 'Verbose logging';
$string['verbosehelp'] = 'Verbose logging shows the time each course grade took to process.';
