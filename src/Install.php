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
declare(strict_types=1);

namespace Dotclear\Plugin\addToAny;

use dcCore;
use dcNamespace;
use dcNsProcess;
use Exception;

class Install extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = My::checkContext(My::INSTALL);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        try {
            // Init
            $settings = dcCore::app()->blog->settings->get(My::id());
            $settings->put('active', false, dcNamespace::NS_BOOL, 'Active', false, true);
            $settings->put('on_post', true, dcNamespace::NS_BOOL, 'Show AddToAny sharing tool on post', false, true);
            $settings->put('on_page', false, dcNamespace::NS_BOOL, 'Show AddToAny sharing tool on post', false, true);
            $settings->put('before_content', false, dcNamespace::NS_BOOL, 'Display AddToAny sharing tool before content', false, true);
            $settings->put('after_content', true, dcNamespace::NS_BOOL, 'Display AddToAny sharing tool after content', false, true);
            $settings->put('style', '', dcNamespace::NS_STRING, 'AddToAny sharing tool style', false, true);
            $settings->put('prefix', '', dcNamespace::NS_STRING, 'AddToAny sharing tool prefix text', false, true);
            $settings->put('suffix', '', dcNamespace::NS_STRING, 'AddToAny sharing tool suffix text', false, true);
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
