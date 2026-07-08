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

use Dotclear\App;
use Dotclear\Database\MetaRecord;

class FrontendBehaviors
{
    public static bool $a2a_loaded = false;

    public static function publicEntryBeforeContent(): string
    {
        $settings = My::settings();

        if ($settings->getBool('active') && App::frontend()->context()->posts instanceof MetaRecord) {
            $post_type = App::frontend()->context()->posts->strField('post_type');
            if (($post_type   === 'post' && $settings->getBool('on_post')
                || $post_type === 'page' && $settings->getBool('on_page'))
                && $settings->getBool('before_content')
            ) {
                $post_url = is_string($post_url = App::frontend()->context()->posts->getURL()) ? $post_url : '';
                if ($post_url !== '') {
                    $post_title = App::frontend()->context()->posts->strField('post_title');
                    echo self::addToAny(
                        $post_url,
                        $post_title,
                        !self::$a2a_loaded,
                        $settings->getStr('prefix', false),
                        $settings->getStr('suffix', false)
                    );
                    self::$a2a_loaded = true;
                }
            }
        }

        return '';
    }

    public static function publicEntryAfterContent(): string
    {
        $settings = My::settings();

        if ($settings->getBool('active') && App::frontend()->context()->posts instanceof MetaRecord) {
            $post_type = App::frontend()->context()->posts->strField('post_type');
            if (($post_type   === 'post' && $settings->getBool('on_post')
                || $post_type === 'page' && $settings->getBool('on_page'))
                && $settings->getBool('after_content')
            ) {
                $post_url = is_string($post_url = App::frontend()->context()->posts->getURL()) ? $post_url : '';
                if ($post_url !== '') {
                    $post_title = App::frontend()->context()->posts->strField('post_title');
                    echo self::addToAny(
                        $post_url,
                        $post_title,
                        !self::$a2a_loaded,
                        $settings->getStr('prefix', false),
                        $settings->getStr('suffix', false)
                    );
                    self::$a2a_loaded = true;
                }
            }
        }

        return '';
    }

    public static function publicHeadContent(): string
    {
        $settings = My::settings();

        if ($settings->getBool('active')) {
            $style = $settings->getStr('style', false);
            if ($style !== '') {
                echo '<style type="text/css">' . "\n" . $style . "\n</style>\n";
            }
        }

        return '';
    }

    // Helpers

    public static function addToAny(string $url, string $label, bool $first = true, ?string $prefix = null, ?string $suffix = null): string
    {
        $ret = '<p class="a2a">' . ($prefix !== null ? $prefix . ' ' : '') .
        '<a class="a2a_dd" href="https://www.addtoany.com/share_save">' . "\n" .
        '<img src="' . urldecode((string) App::blog()->getPF('addToAny/img/favicon.png')) . '" alt="' . __('Share') . '">' . "\n" .
            '</a>' . ($suffix !== null ? ' ' . $suffix : '') . '</p>' . "\n";
        if ($first) {
            $ret .= '<script>' . "\n" .
            'var a2a_config = a2a_config || {};' . "\n" .
                'a2a_config.linkname = \'' . addslashes($label) . '\',' . "\n" .
                'a2a_config.linkurl = \'' . $url . '\',' . "\n" .
                'a2a_config.onclick = 1,' . "\n" .
                'a2a_config.num_services = 10,' . "\n" .
                'a2a_config.show_title = 1' . "\n" .
            '</script>' . "\n" .
            '<script async src="https://static.addtoany.com/menu/page.js"></script>' . "\n";
        } else {
            $ret .= '<script>' . "\n" .
            'a2a_config.linkname = \'' . addslashes($label) . '\';' . "\n" .
            'a2a_config.linkurl = \'' . $url . '\';' . "\n" .
            '</script>' . "\n";
        }

        return $ret;
    }
}
