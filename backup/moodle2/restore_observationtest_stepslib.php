<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Structure step to restore one observationtest activity
 *
 * @package   mod_vat
 * @category  backup
 * @copyright 2019, alterego developer
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_observationtest_activity_structure_step extends restore_activity_structure_step {

    /**
     * Defines structure of path elements to be processed during the restore
     *
     * @return array of {@link restore_path_element}
     */
    protected function define_structure() {

        $paths = array();
        $paths[] = new restore_path_element('observationtest', '/activity/observationtest');
        $paths[] = new restore_path_element('observationtest', '/activity/observationtest/cases');
        $paths[] = new restore_path_element('observationtest', '/activity/observationtest/cases/questions');
        $paths[] = new restore_path_element('observationtest', '/activity/observationtest/cases/questions/answers');

        if ($userinfo) {
            $paths[] = new restore_path_element('observationtest', '/activity/observationtest/results');
        }

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process the given restore path element data
     *
     * @param array $data parsed element data
     */
    protected function process_observationtest($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        if (empty($data->timecreated)) {
            $data->timecreated = time();
        }

        if (empty($data->timemodified)) {
            $data->timemodified = time();
        }

        if ($data->grade < 0) {
            // Scale found, get mapping.
            $data->grade = -($this->get_mappingid('scale', abs($data->grade)));
        }

        // Create the observationtest instance.
        $newitemid = $DB->insert_record('observationtest', $data);
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Post-execution actions
     */
    protected function after_execute() {
        // Add observationtest related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_observationtest', 'intro', null);
    }
}
