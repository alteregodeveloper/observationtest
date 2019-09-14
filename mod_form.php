<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
 
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/observationtext/lib.php');
require_once($CFG->dirroot.'/mod/observationtext/locallib.php');
 
class mod_observationtext_mod_form extends moodleform_mod {
 
    function definition() {
        global $CFG, $DB, $OUTPUT;
 
        $mform =& $this->_form;
 
        $categories = get_categories();
        $mform->addElement('select', 'category', get_string('category', 'observationtext'), $categories);
        $mform->setDefault('category', 1);

        $mform->addElement('select', 'complexity', get_string('complexity', 'observationtext'), $complexityranges);
        $mform->setDefault('complexity', 1);

        $this->standard_intro_elements();
        $element = $mform->getElement('introeditor');
        $attributes = $element->getAttributes();
        $attributes['rows'] = 2;
        $element->setAttributes($attributes);   
 
        $this->standard_coursemodule_elements();
 
        $this->add_action_buttons();
    }
}