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

class FrontendBehaviors
{
    public static bool $a2a_loaded = false;

    public static function publicEntryBeforeContent(): string
    {
        $settings = My::settings();
        if ($settings->active && ((App::frontend()->context()->posts->post_type == 'post' && $settings->on_post) || (App::frontend()->context()->posts->post_type == 'page' && $settings->on_page))) {
            if ($settings->before_content) {
                echo self::addToAny(
                    App::frontend()->context()->posts->getURL(),
                    App::frontend()->context()->posts->post_title,
                    !self::$a2a_loaded,
                    $settings->prefix,
                    $settings->suffix
                );
                self::$a2a_loaded = true;
            }
        }

        return '';
    }

    public static function publicEntryAfterContent(): string
    {
        $settings = My::settings();
        if ($settings->active && ((App::frontend()->context()->posts->post_type == 'post' && $settings->on_post) || (App::frontend()->context()->posts->post_type == 'page' && $settings->on_page))) {
            if ($settings->after_content) {
                echo self::addToAny(
                    App::frontend()->context()->posts->getURL(),
                    App::frontend()->context()->posts->post_title,
                    !self::$a2a_loaded,
                    $settings->prefix,
                    $settings->suffix
                );
                self::$a2a_loaded = true;
            }
        }

        return '';
    }

    public static function publicHeadContent(): string
    {
        $settings = My::settings();
        if (($settings->active) && ($settings->style !== null)) {
            echo '<style type="text/css">' . "\n" . self::customStyle() . "</style>\n";
        }

        return '';
    }

    // Helpers

    public static function addToAny(string $url, string $label, bool $first = true, ?string $prefix = null, ?string $suffix = null): string
    {
        $ret = '<p class="a2a">' . ($prefix !== null ? $prefix . ' ' : '') .
        '<a class="a2a_dd" href="https://www.addtoany.com/share_save">' . "\n" .
        '<img src="' . urldecode(App::blog()->getPF('addToAny/img/favicon.png')) . '" alt="' . __('Share') . '">' . "\n" .
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

    public static function customStyle(): string
    {
        $s = My::settings()->style;
        if ($s === null) {
            return '';
        }

        return $s . "\n";
    }
}
