<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Add observationtest instance.
 *
 * @param stdClass $data
 * @param stdClass $mform
 * @return int new observationtest instance id
 */
function observationtest_add_instance($data, $mform) {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = $data->timecreated;
    if (!isset($data->customtitles)) {
        $data->customtitles = 0;
    }

    $id = $DB->insert_record('observationtest', $data);

    $completiontimeexpected = !empty($data->completionexpected) ? $data->completionexpected : null;
    \core_completion\api::update_completion_date_event($data->coursemodule, 'observationtest', $id, $completiontimeexpected);

    return $id;
}

/**
 * Update observationtest instance.
 *
 * @param stdClass $data
 * @param stdClass $mform
 * @return bool true
 */
function observationtest_update_instance($data, $mform) {
    global $DB;

    $data->timemodified = time();
    $data->id = $data->instance;

    $result = $DB->update_record('observationtest', $data);

    return true;
}

/**
 * Delete observationtest instance.
 * @param int $id
 * @return bool true
 */
function observationtest_delete_instance($id) {
    global $DB;

    if (!$observationtest = $DB->get_record('observationtest', array('id'=>$id))) {
        return false;
    }

    $cm = get_coursemodule_from_instance('observationtest', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'observationtest', $id, null);

    // note: all context files are deleted automatically

    $DB->delete_records('observationtest', array('id'=>$observationtest->id));

    return true;
}