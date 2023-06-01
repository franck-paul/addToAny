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
    '2.0',
    [
        'requires'    => [['core', '2.26']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_ADMIN,
        ]),
        'type'     => 'plugin',
        'settings' => [// Settings
        ],

        'details'    => 'https://open-time.net/?q=addToAny',
        'support'    => 'https://github.com/franck-paul/addToAny',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/addToAny/master/dcstore.xml',
    ]
);
