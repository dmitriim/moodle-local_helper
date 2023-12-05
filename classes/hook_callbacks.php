<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace local_helper;

use core\hook\course\form_extend_definition;
use core\hook\course\form_extend_definition_after_data;
use core\hook\course\form_extend_submission;
use core\hook\course\form_extend_validation;

/**
 * Hook callbacks.
 *
 * @package    local_helper
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2023 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {

    /**
     * Extending a course form.
     *
     * @param \core\hook\course\form_extend_definition $hook Hook.
     */
    public static function course_form_extend_definition(form_extend_definition $hook): void {
        global $OUTPUT;

        $mform = $hook->get_mform();

        if (empty($hook->get_formwrapper()->get_course()->id)) {
            $text = 'New course is being created';
        } else {
            $text = 'A course is being updated';
        }

        $html = $OUTPUT->notification($text, 'info', false);
        $element1 = $mform->createElement('static', 'local_helper', '', $html);
        $mform->insertElementBefore($element1, 'fullname');

        $element2 = $mform->createElement('text', 'local_helper_text', 'Save to Course ID number');
        $mform->insertElementBefore($element2, 'fullname');
        $mform->setType('local_helper_text', PARAM_TEXT);
    }

    /**
     * Extending a course form after data is set.
     *
     * @param \core\hook\course\form_extend_definition_after_data $hook Hook.
     */
    public static function course_form_extend_definition_after_data(form_extend_definition_after_data $hook): void {
        global $OUTPUT;

        $mform = $hook->get_mform();

        $name = $mform->getElementValue('fullname');
        $html = $OUTPUT->notification('The hook form_extend_definition_after_data works!', 'info', false);

        if ($name == 'Course 1') {
            $element = $mform->createElement('static', 'works', '', $html);
            $mform->insertElementBefore($element, 'fullname');
        }
    }

    /**
     * Extending a course form validation.
     *
     * @param \core\hook\course\form_extend_validation $hook Hook.
     */
    public static function course_form_extend_validation(form_extend_validation $hook): void {
        $data = $hook->get_data();

        if (empty($data['local_helper_text'])) {
            $hook->add_errors(['local_helper_text' => 'This value can not be empty']);
        }
    }

    /**
     * Extending a course form submission.
     *
     * @param \core\hook\course\form_extend_submission $hook Hook.
     */
    public static function course_form_extend_submission(form_extend_submission $hook): void {
        global $DB;

        $data = $hook->get_data();

        if (!empty($data->local_helper_text)) {
            $data->idnumber = $data->local_helper_text;

            if ($hook->is_new_course() && !empty($data->id)) {
                $DB->update_record('course', $data);
                rebuild_course_cache($data->id);
            }
        }
    }
}
