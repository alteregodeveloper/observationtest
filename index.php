<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
 
$id = required_param('id', PARAM_INT);


if (!$course = $DB->get_record('course', array('id'=> $id))) {
    print_error(get_string('courseid_incorrect', 'observationtest'));
}