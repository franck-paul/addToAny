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

use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Network\Http;

if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

if (is_null(dcCore::app()->blog->settings->addToAny->active)) {
    try {
        // Add default settings values if necessary
        dcCore::app()->blog->settings->addToAny->put('active', false, 'boolean', 'Active', false);
        dcCore::app()->blog->settings->addToAny->put('on_post', true, 'boolean', 'Show AddToAny sharing tool on post', false);
        dcCore::app()->blog->settings->addToAny->put('on_page', false, 'boolean', 'Show AddToAny sharing tool on post', false);
        dcCore::app()->blog->settings->addToAny->put('before_content', false, 'boolean', 'Display AddToAny sharing tool before content', false);
        dcCore::app()->blog->settings->addToAny->put('after_content', true, 'boolean', 'Display AddToAny sharing tool after content', false);
        dcCore::app()->blog->settings->addToAny->put('style', '', 'string', 'AddToAny sharing tool style', false);
        dcCore::app()->blog->settings->addToAny->put('prefix', '', 'string', 'AddToAny sharing tool prefix text', false);
        dcCore::app()->blog->settings->addToAny->put('suffix', '', 'string', 'AddToAny sharing tool suffix text', false);

        dcCore::app()->blog->triggerBlog();
        Http::redirect(dcCore::app()->admin->getPageURL());
    } catch (Exception $e) {
        dcCore::app()->error->add($e->getMessage());
    }
}

$ata_active         = (bool) dcCore::app()->blog->settings->addToAny->active;
$ata_on_post        = (bool) dcCore::app()->blog->settings->addToAny->on_post;
$ata_on_page        = (bool) dcCore::app()->blog->settings->addToAny->on_page;
$ata_before_content = (bool) dcCore::app()->blog->settings->addToAny->before_content;
$ata_after_content  = (bool) dcCore::app()->blog->settings->addToAny->after_content;
$ata_style          = dcCore::app()->blog->settings->addToAny->style;
$ata_prefix         = dcCore::app()->blog->settings->addToAny->prefix;
$ata_suffix         = dcCore::app()->blog->settings->addToAny->suffix;

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

        # Everything's fine, save options
        dcCore::app()->blog->settings->addToAny->put('active', $ata_active);
        dcCore::app()->blog->settings->addToAny->put('on_post', $ata_on_post);
        dcCore::app()->blog->settings->addToAny->put('on_page', $ata_on_page);
        dcCore::app()->blog->settings->addToAny->put('before_content', $ata_before_content);
        dcCore::app()->blog->settings->addToAny->put('after_content', $ata_after_content);
        dcCore::app()->blog->settings->addToAny->put('style', $ata_style);
        dcCore::app()->blog->settings->addToAny->put('prefix', $ata_prefix);
        dcCore::app()->blog->settings->addToAny->put('suffix', $ata_suffix);

        dcCore::app()->blog->triggerBlog();

        dcPage::addSuccessNotice(__('Settings have been successfully updated.'));
        Http::redirect(dcCore::app()->admin->getPageURL());
    } catch (Exception $e) {
        dcCore::app()->error->add($e->getMessage());
    }
}

?>
<html>
<head>
  <title><?php echo __('AddToAny'); ?></title>
</head>

<body>
<?php
echo dcPage::breadcrumb(
    [
        Html::escapeHTML(dcCore::app()->blog->name) => '',
        __('AddToAny')                              => '',
    ]
);
echo dcPage::notices();

echo
'<form action="' . dcCore::app()->admin->getPageURL() . '" method="post">' .
'<p>' . form::checkbox('ata_active', 1, $ata_active) . ' ' .
'<label for="ata_active" class="classic">' . __('Active AddToAny') . '</label></p>' .

'<h3>' . __('Options') . '</h3>' .

'<p>' . form::checkbox('ata_on_post', 1, $ata_on_post) . ' ' .
'<label for="ata_on_post" class="classic">' . __('Automatically insert AddToAny sharing tool on posts') . '</label></p>' .
'<p>' . form::checkbox('ata_on_page', 1, $ata_on_page) . ' ' .
'<label for="ata_on_page" class="classic">' . __('Automatically insert AddToAny sharing tool on pages') . '</label></p>' .

'<h3>' . __('Position') . '</h3>' .

'<p>' . form::checkbox('ata_before_content', 1, $ata_before_content) . ' ' .
'<label for="ata_before_content" class="classic">' . __('Insert AddToAny sharing tool before content') . '</label></p>' .
'<p>' . form::checkbox('ata_after_content', 1, $ata_after_content) . ' ' .
'<label for="ata_after_content" class="classic">' . __('Insert AddToAny sharing tool after content') . '</label></p>' .

'<h3>' . __('Advanced options') . '</h3>' .

'<p class="area"><label for="ata_style">' . __('AddToAny sharing tool CSS style:') . '</label> ' .
form::textarea('ata_style', 30, 8, Html::escapeHTML($ata_style)) . '</p>' .

'<p><label for="ata_prefix">' . __('AddToAny sharing tool text prefix:') . '</label> ' .
form::field('ata_prefix', 30, 128, Html::escapeHTML($ata_prefix)) . '</p>' .
'<p class="form-note">' . __('This will be inserted before link.') . '</p>' .

'<p><label for="ata_suffix">' . __('AddToAny sharing tool text suffix:') . '</label> ' .
form::field('ata_suffix', 30, 128, Html::escapeHTML($ata_suffix)) . '</p>' .
'<p class="form-note">' . __('This will be inserted after link.') . '</p>' .

'<p class="form-note">' . __('The link will be inserted as <code>&lt;p class="a2a"&gt;&lt;a …&gt;&lt;img …&gt;&lt;/a&gt;&lt;/p&gt;</code> form.') . '</p>' .

'<p class="form-note">' . __('See <a href="https://www.addtoany.com/">AddToAny web site</a> for more information.') . '</p>' .

'<p>' . dcCore::app()->formNonce() . '<input type="submit" value="' . __('Save') . '" /></p>' .
    '</form>';

?>
</body>
</html>
