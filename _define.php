<?php
/**
 * @brief addToAny, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

$this->registerModule(
    "AddToAny",                                          // Name
    "Add AddToAny sharing tool to your posts and pages", // Description
    "Franck Paul",                                       // Author
    '0.3.1',                                             // Version
    array(
        'permissions' => 'admin',  // Permissions
        'type'        => 'plugin' // Type
    )
);
