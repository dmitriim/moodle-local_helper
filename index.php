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
 * A test page.
 *
 * @package   local_helper
 * @author    Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$url = new moodle_url('/local/helper/index.php', []);
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');
$PAGE->set_context(context_system::instance());
$heading = 'Helper';

$PAGE->navbar->add($heading);
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_helper/index', ['courseid' => 1, 'contextid' => 1]);

echo $OUTPUT->footer();





