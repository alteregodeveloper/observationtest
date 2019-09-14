<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/*
 * Defines complexity ranges
 * 
 * @param stdClass $complexity
 * 
 */
$complexityranges = array(
    1 => get_string('easy','observationtext'),
    2 => get_string('medium','observationtext'),
    3 => get_string('advanced','observationtext'));

function get_categories() {
    return array(
        array('id' => '1', 'category' => 'Test 1'),
        array('id' => '2', 'category' => 'Test 2'),
        array('id' => '3', 'category' => 'Test 3')
    );
}