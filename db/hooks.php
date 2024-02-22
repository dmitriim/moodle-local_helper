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


/**
 * Hooks listeners.
 *
 * @package    local_helper
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2023 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$callbacks = [
    [
        'hook' => \core_course\hook\after_form_definition::class,
        'callback' => local_helper\hook_callbacks::class . '::course_form_extend_definition',
    ],
    [
        'hook' => \core_course\hook\after_form_definition_after_data::class,
        'callback' => local_helper\hook_callbacks::class . '::course_form_extend_definition_after_data',
    ],
    [
        'hook' => \core_course\hook\after_form_validation::class,
        'callback' => local_helper\hook_callbacks::class . '::course_form_extend_validation',
    ],
    [
        'hook' => \core_course\hook\after_form_submission::class,
        'callback' => local_helper\hook_callbacks::class . '::course_form_extend_submission',
    ],
];
