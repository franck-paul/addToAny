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

use Dotclear\App;
use Dotclear\Core\Process;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            // Init
            $settings = My::settings();
            $settings->put('active', false, App::blogWorkspace()::NS_BOOL, 'Active', false, true);
            $settings->put('on_post', true, App::blogWorkspace()::NS_BOOL, 'Show AddToAny sharing tool on post', false, true);
            $settings->put('on_page', false, App::blogWorkspace()::NS_BOOL, 'Show AddToAny sharing tool on post', false, true);
            $settings->put('before_content', false, App::blogWorkspace()::NS_BOOL, 'Display AddToAny sharing tool before content', false, true);
            $settings->put('after_content', true, App::blogWorkspace()::NS_BOOL, 'Display AddToAny sharing tool after content', false, true);
            $settings->put('style', '', App::blogWorkspace()::NS_STRING, 'AddToAny sharing tool style', false, true);
            $settings->put('prefix', '', App::blogWorkspace()::NS_STRING, 'AddToAny sharing tool prefix text', false, true);
            $settings->put('suffix', '', App::blogWorkspace()::NS_STRING, 'AddToAny sharing tool suffix text', false, true);
        } catch (Exception $e) {
            App::error()->add($e->getMessage());
        }

        return true;
    }
}
