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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

// dead but useful code, in order to have translations
__('AddToAny') . __('Add AddToAny sharing tool to your posts and pages');

dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
    __('AddToAny'),
    'plugin.php?p=addToAny',
    urldecode(dcPage::getPF('addToAny/icon.png')),
    preg_match('/plugin.php\?p=addToAny(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
        dcAuth::PERMISSION_ADMIN,
    ]), dcCore::app()->blog->id)
);
