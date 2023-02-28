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
 * Local functions.
 *
 * @package    tool_courserequeststomanagers
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$classfiles = new DirectoryIterator($CFG->dirroot . '/admin/tool/courserequeststomanagers/view/');
foreach ($classfiles as $classfile) {
    if ($classfile->isDot()) {
        continue;
    }
    if ($classfile->isLink()) {
        throw new coding_exception('Unexpected symlink in admin/tool/courserequeststomanagers/view/');
    }
    if ($classfile->isFile() and substr($classfile->getFilename(), -4) === '.php') {
        require_once($classfile->getPathname());
    }
}

$classfiles = new DirectoryIterator($CFG->dirroot . '/admin/tool/courserequeststomanagers/classes/');
foreach ($classfiles as $classfile) {
    if ($classfile->isDot()) {
        continue;
    }
    if ($classfile->isLink()) {
        throw new coding_exception('Unexpected symlink in admin/tool/courserequeststomanagers/core/');
    }
    if ($classfile->isFile() and substr($classfile->getFilename(), -4) === '.php') {
        require_once($classfile->getPathname());
    }
}

$classfiles = new DirectoryIterator($CFG->dirroot . '/admin/tool/courserequeststomanagers/core/handle_export');
foreach ($classfiles as $classfile) {
    if ($classfile->isDot()) {
        continue;
    }
    if ($classfile->isLink()) {
        throw new coding_exception('Unexpected symlink in admin/tool/courserequeststomanagers/core/handle_export');
    }
    if ($classfile->isFile() and substr($classfile->getFilename(), -4) === '.php') {
        require_once($classfile->getPathname());
    }
}