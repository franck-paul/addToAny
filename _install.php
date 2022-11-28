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

$new_version = dcCore::app()->plugins->moduleInfo('addToAny', 'version');
$old_version = dcCore::app()->getVersion('addToAny');

if (version_compare((string) $old_version, $new_version, '>=')) {
    return;
}

try {
    dcCore::app()->blog->settings->addNamespace('addToAny');
    dcCore::app()->blog->settings->addToAny->put('active', false, 'boolean', 'Active', false, true);
    dcCore::app()->blog->settings->addToAny->put('on_post', true, 'boolean', 'Show AddToAny sharing tool on post', false, true);
    dcCore::app()->blog->settings->addToAny->put('on_page', false, 'boolean', 'Show AddToAny sharing tool on post', false, true);
    dcCore::app()->blog->settings->addToAny->put('before_content', false, 'boolean', 'Display AddToAny sharing tool before content', false, true);
    dcCore::app()->blog->settings->addToAny->put('after_content', true, 'boolean', 'Display AddToAny sharing tool after content', false, true);
    dcCore::app()->blog->settings->addToAny->put('style', '', 'string', 'AddToAny sharing tool style', false, true);
    dcCore::app()->blog->settings->addToAny->put('prefix', '', 'string', 'AddToAny sharing tool prefix text', false, true);
    dcCore::app()->blog->settings->addToAny->put('suffix', '', 'string', 'AddToAny sharing tool suffix text', false, true);

    dcCore::app()->setVersion('addToAny', $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
