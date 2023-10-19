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

use ArrayObject;
use dcCore;

class FrontendTemplate
{
    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function tplAddToAny(array|ArrayObject $attr): string
    {
        $ret      = '';
        $settings = My::settings();

        if ($settings->active) {
            $f   = dcCore::app()->tpl->getFilters($attr);
            $url = sprintf($f, dcCore::app()->ctx->posts->getURL());
            $ret = FrontendBehaviors::addToAny(
                $url,
                dcCore::app()->ctx->posts->post_title,
                !FrontendBehaviors::$a2a_loaded,
                $settings->prefix,
                $settings->suffix
            );
            FrontendBehaviors::$a2a_loaded = true;
        }

        return $ret;
    }
}
