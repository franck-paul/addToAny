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
    'AddToAny',
    'Add AddToAny sharing tool to your posts and pages',
    'Franck Paul',
    '4.2',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'settings'    => [
            'self' => '',
        ],

        'details'    => 'https://open-time.net/?q=addToAny',
        'support'    => 'https://github.com/franck-paul/addToAny',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/addToAny/main/dcstore.xml',
    ]
);
