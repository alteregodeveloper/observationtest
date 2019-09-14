<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/observationtest/backup/moodle2/restore_observationtest_stepslib.php'); // Because it exists (must)

/**
 * observationtest restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */
class restore_observationtest_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // observationtest only has one structure step
        $this->add_step(new restore_observationtest_activity_structure_step('observationtest_structure', 'observationtest.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('observationtest', array('intro'), 'observationtest');
        $contents[] = new restore_decode_content('observationtest_cases', array('intro'), 'observationtest_case');
        $contents[] = new restore_decode_content('observationtest_questions', array('intro'), 'observationtest_question');
        $contents[] = new restore_decode_content('observationtest_answers', array('intro'), 'observationtest_answers');

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules = array();

        $rules[] = new restore_decode_rule('OBSERVATIONTESTINDEX', '/mod/observationtest/index.php?id=$1', 'course');
        $rules[] = new restore_decode_rule('OBSERVATIONTESTVIEWBYID', '/mod/observationtest/view.php?id=$1', 'course_module');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * vat results. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('observationtest', 'add', 'view.php?id={course_module}', '{observationtest}');
        $rules[] = new restore_log_rule('observationtest', 'update', 'view.php?id={course_module}', '{observationtest}');
        $rules[] = new restore_log_rule('observationtest', 'view', 'view.php?id={course_module}', '{observationtest}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('observationtest', 'view all', 'index.php?id={course}', null);

        return $rules;
    }

}