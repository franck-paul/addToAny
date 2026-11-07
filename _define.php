<?php

/**
 * @brief addToAny, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'AddToAny',
        'Add AddToAny sharing tool to your posts and pages',
        'Franck Paul',
        '6.1',
        [
            'date'        => '2026-11-07T16:39:54+0100',
            'requires'    => [['core', '2.39']],
            'permissions' => 'My',
            'type'        => 'plugin',
            'settings'    => [
                'self' => '',
            ],

            'details'    => 'https://open-time.net/?q=addToAny',
            'support'    => 'https://github.com/franck-paul/addToAny',
            'repository' => 'https://raw.githubusercontent.com/franck-paul/addToAny/main/dcstore.xml',
            'license'    => 'gpl2',
        ]
    );
}
