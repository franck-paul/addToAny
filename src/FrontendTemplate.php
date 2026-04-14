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

namespace Dotclear\Plugin\addToAny;

use ArrayObject;
use Dotclear\App;
use Dotclear\Database\MetaRecord;

class FrontendTemplate
{
    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function tplAddToAny(array|ArrayObject $attr): string
    {
        // Variable data helpers
        $_Str = fn (mixed $var, string $default = ''): string => $var !== null && is_string($val = $var) ? $val : $default;

        $ret      = '';
        $settings = My::settings();

        if ($settings->active && App::frontend()->context()->posts instanceof MetaRecord) {
            $f        = App::frontend()->template()->getFilters($attr);
            $post_url = is_string($post_url = App::frontend()->context()->posts->getURL()) ? $post_url : '';
            if ($post_url !== '') {
                $post_title = is_string($post_title = App::frontend()->context()->posts->post_title) ? $post_title : '';
                $url        = sprintf($f, $post_url);
                $ret        = FrontendBehaviors::addToAny(
                    $url,
                    $post_title,
                    !FrontendBehaviors::$a2a_loaded,
                    $_Str($settings->prefix),
                    $_Str($settings->suffix)
                );
                FrontendBehaviors::$a2a_loaded = true;
            }
        }

        return $ret;
    }
}
