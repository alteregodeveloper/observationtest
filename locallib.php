<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

function get_complexity_ranges(){
    return array(
    1 => get_string('easy','observationtest'),
    2 => get_string('medium','observationtest'),
    3 => get_string('advanced','observationtest'));
}

function get_observationtest_categories() {
    global $USER, $DB;

    $query = 'SELECT id, category FROM mdl_observation_categories';
    return $DB->get_records_sql_menu($query);
}