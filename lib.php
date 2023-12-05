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
 * Lib functions for the helper plugin.
 *
 * @package   local_helper
 * @author    Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Serve the new item form as a fragment.
 *
 * @param array $args List of named arguments for the fragment loader.
 * @return string
 */
function local_helper_output_fragment_item_form($args) {
    global $CFG;

    require_once($CFG->dirroot . '/local/helper/item_form.php');

    $mform = new item_form();

    $o = '';
    ob_start();
    $mform->display();
    $o .= ob_get_contents();
    ob_end_clean();
    return $o;
}

/**
 * Plugin hook for loading custom elements into module forms after the data is set.
 *
 * @param \moodleform_mod $modform moodle course module form to set customfields data in.
 * @param \MoodleQuickForm $form the module settings form itself.
 */
function local_helper_coursemodule_definition_after_data(moodleform_mod $modform, \MoodleQuickForm $form) {
    $name = $form->getElementValue('name');

    if ($name == 'Page 1') {
        $element = $form->createElement('static', 'works', '', 'The  callback coursemodule_definition_after_data works!');
        $form->insertElementBefore($element, 'name');
    }
}
