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
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Textarea;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Process\TraitProcess;
use Exception;

class Manage
{
    use TraitProcess;

    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $settings = My::settings();
        if (is_null($settings->getBool('active'))) {
            try {
                // Add default settings values if necessary

                $settings->put('active', false, App::blogWorkspace()::NS_BOOL, 'Active', false);
                $settings->put('on_post', true, App::blogWorkspace()::NS_BOOL, 'Show AddToAny sharing tool on post', false);
                $settings->put('on_page', false, App::blogWorkspace()::NS_BOOL, 'Show AddToAny sharing tool on post', false);
                $settings->put('before_content', false, App::blogWorkspace()::NS_BOOL, 'Display AddToAny sharing tool before content', false);
                $settings->put('after_content', true, App::blogWorkspace()::NS_BOOL, 'Display AddToAny sharing tool after content', false);
                $settings->put('style', '', App::blogWorkspace()::NS_STRING, 'AddToAny sharing tool style', false);
                $settings->put('prefix', '', App::blogWorkspace()::NS_STRING, 'AddToAny sharing tool prefix text', false);
                $settings->put('suffix', '', App::blogWorkspace()::NS_STRING, 'AddToAny sharing tool suffix text', false);

                App::blog()->triggerBlog();
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        if ($_POST !== []) {
            // Post data helpers
            $_Bool = fn (string $name): bool => !empty($_POST[$name]);
            $_Str  = fn (string $name, string $default = ''): string => isset($_POST[$name]) && is_string($val = $_POST[$name]) ? $val : $default;

            try {
                $ata_active         = $_Bool('ata_active');
                $ata_on_post        = $_Bool('ata_on_post');
                $ata_on_page        = $_Bool('ata_on_page');
                $ata_before_content = $_Bool('ata_before_content');
                $ata_after_content  = $_Bool('ata_after_content');
                $ata_style          = trim($_Str('ata_style'));
                $ata_prefix         = trim(Html::escapeHTML($_Str('ata_prefix')));
                $ata_suffix         = trim(Html::escapeHTML($_Str('ata_suffix')));

                // Everything's fine, save options
                $settings->put('active', $ata_active, App::blogWorkspace()::NS_BOOL);
                $settings->put('on_post', $ata_on_post, App::blogWorkspace()::NS_BOOL);
                $settings->put('on_page', $ata_on_page, App::blogWorkspace()::NS_BOOL);
                $settings->put('before_content', $ata_before_content, App::blogWorkspace()::NS_BOOL);
                $settings->put('after_content', $ata_after_content, App::blogWorkspace()::NS_BOOL);
                $settings->put('style', $ata_style, App::blogWorkspace()::NS_STRING);
                $settings->put('prefix', $ata_prefix, App::blogWorkspace()::NS_STRING);
                $settings->put('suffix', $ata_suffix, App::blogWorkspace()::NS_STRING);

                App::blog()->triggerBlog();

                App::backend()->notices()->addSuccessNotice(__('Settings have been successfully updated.'));
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        $settings = My::settings();

        $ata_active         = $settings->getBool('active', false);
        $ata_on_post        = $settings->getBool('on_post', false);
        $ata_on_page        = $settings->getBool('on_page', false);
        $ata_before_content = $settings->getBool('before_content', false);
        $ata_after_content  = $settings->getBool('after_content', false);
        $ata_style          = $settings->getStr('style', false);
        $ata_prefix         = $settings->getStr('prefix', false);
        $ata_suffix         = $settings->getStr('suffix', false);

        App::backend()->page()->openModule(My::name());

        echo App::backend()->page()->breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('addToAny')                        => '',
            ]
        );
        echo App::backend()->notices()->getNotices();

        // Form
        echo
        (new Form('addtoany_params'))
            ->action(App::backend()->getPageURL())
            ->method('post')
            ->fields([
                (new Para())->items([
                    (new Checkbox('ata_active', $ata_active))
                        ->value(1)
                        ->label((new Label(__('Activate AddToAny'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Text('h3', __('Options'))),
                (new Para())->items([
                    (new Checkbox('ata_on_post', $ata_on_post))
                        ->value(1)
                        ->label((new Label(__('Automatically insert AddToAny sharing tool on posts'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ata_on_page', $ata_on_page))
                        ->value(1)
                        ->label((new Label(__('Automatically insert AddToAny sharing tool on pages'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Text('h3', __('Position'))),
                (new Para())->items([
                    (new Checkbox('ata_before_content', $ata_before_content))
                        ->value(1)
                        ->label((new Label(__('Insert AddToAny sharing tool before content'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ata_after_content', $ata_after_content))
                        ->value(1)
                        ->label((new Label(__('Insert AddToAny sharing tool after content'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Text('h3', __('Advanced options'))),
                (new Para())->class('area')->items([
                    (new Textarea('ata_style'))
                        ->cols(30)
                        ->rows(8)
                        ->value(Html::escapeHTML($ata_style))
                        ->label((new Label(__('AddToAny sharing tool CSS style:'), Label::OUTSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->items([
                    (new Input('ata_prefix'))
                        ->size(30)
                        ->maxlength(128)
                        ->value(Html::escapeHTML($ata_prefix))
                        ->required(true)
                        ->label((new Label(__('AddToAny sharing tool text prefix:'), Label::OUTSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('This will be inserted before link.'))),
                ]),
                (new Para())->items([
                    (new Input('ata_suffix'))
                        ->size(30)
                        ->maxlength(128)
                        ->value(Html::escapeHTML($ata_suffix))
                        ->required(true)
                        ->label((new Label(__('AddToAny sharing tool text suffix:'), Label::OUTSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('This will be inserted after link.'))),
                ]),
                (new Para())->class('info')->items([
                    (new Text(null, __('The link will be inserted as <code>&lt;p class="a2a"&gt;&lt;a …&gt;&lt;img …&gt;&lt;/a&gt;&lt;/p&gt;</code> form.') . ' ' . __('See <a href="https://www.addtoany.com/">AddToAny web site</a> for more information.'))),
                ]),
                // Submit
                (new Para())->items([
                    (new Submit(['frmsubmit']))
                        ->value(__('Save')),
                    ... My::hiddenFields(),
                ]),
            ])
        ->render();

        App::backend()->page()->closeModule();
    }
}
