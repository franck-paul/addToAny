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
class dcAddToAny
{
    private static bool $a2a_loaded = false;

    public static function publicEntryBeforeContent()
    {
        if (dcCore::app()->blog->settings->addToAny->active) {
            if ((dcCore::app()->ctx->posts->post_type == 'post' && dcCore::app()->blog->settings->addToAny->on_post) || (dcCore::app()->ctx->posts->post_type == 'page' && dcCore::app()->blog->settings->addToAny->on_page)) {
                if (dcCore::app()->blog->settings->addToAny->before_content) {
                    echo self::addToAny(
                        dcCore::app()->ctx->posts->getURL(),
                        dcCore::app()->ctx->posts->post_title,
                        !self::$a2a_loaded,
                        dcCore::app()->blog->settings->addToAny->prefix,
                        dcCore::app()->blog->settings->addToAny->suffix
                    );
                    self::$a2a_loaded = true;
                }
            }
        }
    }

    public static function publicEntryAfterContent()
    {
        if (dcCore::app()->blog->settings->addToAny->active) {
            if ((dcCore::app()->ctx->posts->post_type == 'post' && dcCore::app()->blog->settings->addToAny->on_post) || (dcCore::app()->ctx->posts->post_type == 'page' && dcCore::app()->blog->settings->addToAny->on_page)) {
                if (dcCore::app()->blog->settings->addToAny->after_content) {
                    echo self::addToAny(
                        dcCore::app()->ctx->posts->getURL(),
                        dcCore::app()->ctx->posts->post_title,
                        !self::$a2a_loaded,
                        dcCore::app()->blog->settings->addToAny->prefix,
                        dcCore::app()->blog->settings->addToAny->suffix
                    );
                    self::$a2a_loaded = true;
                }
            }
        }
    }

    public static function tplAddToAny($attr)
    {
        $ret = '';
        if (dcCore::app()->blog->settings->addToAny->active) {
            $f   = dcCore::app()->tpl->getFilters($attr);
            $url = sprintf($f, dcCore::app()->ctx->posts->getURL());
            $ret = self::addToAny(
                $url,
                dcCore::app()->ctx->posts->post_title,
                !self::$a2a_loaded,
                dcCore::app()->blog->settings->addToAny->prefix,
                dcCore::app()->blog->settings->addToAny->suffix
            );
            self::$a2a_loaded = true;
        }

        return $ret;
    }

    public static function publicHeadContent()
    {
        if ((dcCore::app()->blog->settings->addToAny->active) && (dcCore::app()->blog->settings->addToAny->style !== null)) {
            echo '<style type="text/css">' . "\n" . self::customStyle() . "</style>\n";
        }
    }

    // Helpers

    public static function addToAny($url, $label, $first = true, $prefix = null, $suffix = null)
    {
        $ret = '<p class="a2a">' . ($prefix !== null ? $prefix . ' ' : '') .
        '<a class="a2a_dd" href="https://www.addtoany.com/share_save">' . "\n" .
        '<img src="' . urldecode(dcCore::app()->blog->getPF('addToAny/img/favicon.png')) . '" alt="' . __('Share') . '"/>' . "\n" .
            '</a>' . ($suffix !== null ? ' ' . $suffix : '') . '</p>' . "\n";
        if ($first) {
            $ret .= '<script>' . "\n" .
            'a2a_config = {' . "\n" .
            'linkname: \'' . addslashes($label) . '\',' . "\n" .
                'linkurl: \'' . $url . '\',' . "\n" .
                'onclick: 1,' . "\n" .
                'num_services: 10,' . "\n" .
                'show_title: 1' . "\n" .
                '};' . "\n" .
                '</script>' . "\n" .
                '<script src="https://static.addtoany.com/menu/page.js"></script>' . "\n";
        } else {
            $ret .= '<script>' . "\n" .
            'a2a_config.linkname = \'' . addslashes($label) . '\';' . "\n" .
                'a2a_config.linkurl = \'' . $url . '\';' . "\n" .
                'a2a.init(\'page\');' . "\n" .
                '</script>' . "\n";
        }

        return $ret;
    }

    public static function customStyle()
    {
        $s = dcCore::app()->blog->settings->addToAny->style;
        if ($s === null) {
            return;
        }

        return $s . "\n";
    }
}

dcCore::app()->addBehaviors([
    'publicHeadContent'        => [dcAddToAny::class, 'publicHeadContent'],

    'publicEntryBeforeContent' => [dcAddToAny::class, 'publicEntryBeforeContent'],
    'publicEntryAfterContent'  => [dcAddToAny::class, 'publicEntryAfterContent'],
]);

dcCore::app()->tpl->addValue('AddToAny', [dcAddToAny::class, 'tplAddToAny']);
