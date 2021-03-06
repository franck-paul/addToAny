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
    '0.4',                                               // Version
    [
        'requires'    => [['core', '2.13']],                        // Dependencies
        'permissions' => 'admin',                                   // Permissions
        'type'        => 'plugin',                                  // Type
        'details'     => 'https://open-time.net/?q=addToAny',       // Details URL
        'support'     => 'https://github.com/franck-paul/addToAny', // Support URL
        'settings'    => [                                          // Settings
        ]
    ]
);
