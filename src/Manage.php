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
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Textarea;
use Dotclear\Helper\Html\Html;
use Exception;

class Manage extends Process
{
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
        if (is_null($settings->active)) {
            try {
                // Add default settings values if necessary

                $settings->put('active', false, dcNamespace::NS_BOOL, 'Active', false);
                $settings->put('on_post', true, dcNamespace::NS_BOOL, 'Show AddToAny sharing tool on post', false);
                $settings->put('on_page', false, dcNamespace::NS_BOOL, 'Show AddToAny sharing tool on post', false);
                $settings->put('before_content', false, dcNamespace::NS_BOOL, 'Display AddToAny sharing tool before content', false);
                $settings->put('after_content', true, dcNamespace::NS_BOOL, 'Display AddToAny sharing tool after content', false);
                $settings->put('style', '', dcNamespace::NS_STRING, 'AddToAny sharing tool style', false);
                $settings->put('prefix', '', dcNamespace::NS_STRING, 'AddToAny sharing tool prefix text', false);
                $settings->put('suffix', '', dcNamespace::NS_STRING, 'AddToAny sharing tool suffix text', false);

                dcCore::app()->blog->triggerBlog();
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id());
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        if (!empty($_POST)) {
            try {
                $ata_active         = !empty($_POST['ata_active']);
                $ata_on_post        = !empty($_POST['ata_on_post']);
                $ata_on_page        = !empty($_POST['ata_on_page']);
                $ata_before_content = !empty($_POST['ata_before_content']);
                $ata_after_content  = !empty($_POST['ata_after_content']);
                $ata_style          = trim((string) $_POST['ata_style']);
                $ata_prefix         = trim(Html::escapeHTML($_POST['ata_prefix']));
                $ata_suffix         = trim(Html::escapeHTML($_POST['ata_suffix']));

                // Everything's fine, save options
                $settings->put('active', $ata_active, dcNamespace::NS_BOOL);
                $settings->put('on_post', $ata_on_post, dcNamespace::NS_BOOL);
                $settings->put('on_page', $ata_on_page, dcNamespace::NS_BOOL);
                $settings->put('before_content', $ata_before_content, dcNamespace::NS_BOOL);
                $settings->put('after_content', $ata_after_content, dcNamespace::NS_BOOL);
                $settings->put('style', $ata_style, dcNamespace::NS_STRING);
                $settings->put('prefix', $ata_prefix, dcNamespace::NS_STRING);
                $settings->put('suffix', $ata_suffix, dcNamespace::NS_STRING);

                dcCore::app()->blog->triggerBlog();

                Notices::addSuccessNotice(__('Settings have been successfully updated.'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id());
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
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

        $settings           = My::settings();
        $ata_active         = (bool) $settings->active;
        $ata_on_post        = (bool) $settings->on_post;
        $ata_on_page        = (bool) $settings->on_page;
        $ata_before_content = (bool) $settings->before_content;
        $ata_after_content  = (bool) $settings->after_content;
        $ata_style          = $settings->style;
        $ata_prefix         = $settings->prefix;
        $ata_suffix         = $settings->suffix;

        Page::openModule(My::name());

        echo Page::breadcrumb(
            [
                Html::escapeHTML(dcCore::app()->blog->name) => '',
                __('addToAny')                              => '',
            ]
        );
        echo Notices::getNotices();

        // Form
        echo
        (new Form('addtoany_params'))
            ->action(dcCore::app()->admin->getPageURL())
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

        Page::closeModule();
    }
}
