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

/*
 * The main invertclass configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod
 * @subpackage invertclass
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_invertclass_mod_form extends moodleform_mod {

    public function definition() {

        $mform = $this->_form;

        $mform->addElement('header', 'general', 'Criar novo invertclass');

        $mform->addElement('textarea', 'name', 'T&iacute;tulo do invertclass:', 'wrap="virtual" rows="3" cols="80"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');

        $this->add_intro_editor();
        $mform->addRule('introeditor', null, 'required', null, 'client');

        $mform->addElement('textarea', 'knowledge_area', '&Aacute;reas de conhecimento:', 'wrap="virtual" rows="5" cols="80"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('knowledge_area', PARAM_TEXT);
        } else {
            $mform->setType('knowledge_area', PARAM_CLEAN);
        }
        $mform->addRule('knowledge_area', null, 'required', null, 'client');


        $mform->addElement('textarea', 'not_related_words', 'Palavras n&atilde;o relacionadas ao invertclass:', 'wrap="virtual" rows="3" cols="80"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('not_related_words', PARAM_TEXT);
        } else {
            $mform->setType('not_related_words', PARAM_CLEAN);
        }

        $mform->addElement('textarea', 'product_format', 'Formato do produto final:', 'wrap="virtual" rows="4" cols="80"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('product_format', PARAM_TEXT);
        } else {
            $mform->setType('product_format', PARAM_CLEAN);
        }
		  
        $this->standard_hidden_coursemodule_elements();
        $mform->addElement('hidden', 'groupmode', 1);
        $mform->addElement('hidden', 'visible', 1);
        $mform->addElement('hidden', 'visibleold', 1);
        $this->add_action_buttons();
    }
}
*/

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
 * The main problem configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod
 * @subpackage problem
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_invertclass_mod_form extends moodleform_mod {
    public function definition() {
        
        $mform = $this->_form;

        $mform->addElement('header', 'general', 'CRIAR NOVA AULA');

        $mform->addElement('textarea', 'name', 'T&Iacute;TULO:', 'wrap="virtual" rows="3" cols="80"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');

        /*$this->add_intro_editor();
        $mform->addRule('introeditor', null, 'required', null, 'client');*/

        $mform->addElement('textarea', 'descricao', 'Descrição:', 'wrap="virtual" rows="5" cols="80"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('descricao', PARAM_TEXT);
        } else {
            $mform->setType('descricao', PARAM_CLEAN);
        }
        $mform->addRule('descricao', null, 'required', null, 'client');
    
        $this->standard_hidden_coursemodule_elements();
        $mform->addElement('hidden', 'groupmode', 1);
        $mform->addElement('hidden', 'visible', 1);
        $mform->addElement('hidden', 'visibleold', 1);
        $this->add_action_buttons();
    }
/*
    public function definition() {

        $mform = $this->_form;

        $mform->addElement('header', 'general', 'CRIAR NOVA AULA');

        $mform->addElement('textarea', 'name', 'T&Iacute;TULO DA AULA:', 'wrap="virtual" rows="3" cols="80"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');

        //$this->add_intro_editor();
        //$mform->addRule('introeditor', null, 'required', null, 'client');

        $mform->addElement('textarea', 'knowledge_area', 'OBJETIVOS:', 'wrap="virtual" rows="5" cols="80"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('knowledge_area', PARAM_TEXT);
        } else {
            $mform->setType('knowledge_area', PARAM_CLEAN);
        }
        $mform->addRule('knowledge_area', null, 'required', null, 'client');

    
        $this->standard_hidden_coursemodule_elements();
        $mform->addElement('hidden', 'groupmode', 1);
        $mform->addElement('hidden', 'visible', 1);
        $mform->addElement('hidden', 'visibleold', 1);
        $this->add_action_buttons();
    }*/
}
