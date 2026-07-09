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
        $ret      = '';
        $settings = My::settings();

        if ($settings->getBool('active') && App::frontend()->context()->posts instanceof MetaRecord) {
            $f        = App::frontend()->template()->getFilters($attr);
            $post_url = App::frontend()->context()->posts->getURL();
            if ($post_url !== '') {
                $post_title = App::frontend()->context()->posts->strField('post_title');
                $url        = sprintf($f, $post_url);
                $ret        = FrontendBehaviors::addToAny(
                    $url,
                    $post_title,
                    !FrontendBehaviors::$a2a_loaded,
                    $settings->getStr('prefix', false),
                    $settings->getStr('suffix', false)
                );
                FrontendBehaviors::$a2a_loaded = true;
            }
        }

        return $ret;
    }
}
