<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of AddToAny, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) { return; }

$core->blog->settings->addNamespace('addToAny');
if (is_null($core->blog->settings->addToAny->active)) {
	try {
		// Add default settings values if necessary
		$core->blog->settings->addToAny->put('active',false,'boolean','Active',false);
		$core->blog->settings->addToAny->put('on_post',true,'boolean','Show AddToAny sharing tool on post',false);
		$core->blog->settings->addToAny->put('on_page',false,'boolean','Show AddToAny sharing tool on post',false);
		$core->blog->settings->addToAny->put('before_content',false,'boolean','Display AddToAny sharing tool before content',false);
		$core->blog->settings->addToAny->put('after_content',true,'boolean','Display AddToAny sharing tool after content',false);
		$core->blog->settings->addToAny->put('style','','string','AddToAny sharing tool style',false);
		$core->blog->settings->addToAny->put('prefix','','string','AddToAny sharing tool prefix text',false);
		$core->blog->settings->addToAny->put('suffix','','string','AddToAny sharing tool suffix text',false);

		$core->blog->triggerBlog();
		http::redirect($p_url);
	}
	catch (Exception $e) {
		$core->error->add($e->getMessage());
	}
}

$ata_active = (boolean) $core->blog->settings->addToAny->active;
$ata_on_post = (boolean) $core->blog->settings->addToAny->on_post;
$ata_on_page = (boolean) $core->blog->settings->addToAny->on_page;
$ata_before_content = (boolean) $core->blog->settings->addToAny->before_content;
$ata_after_content = (boolean) $core->blog->settings->addToAny->after_content;
$ata_style = $core->blog->settings->addToAny->style;
$ata_prefix = $core->blog->settings->addToAny->prefix;
$ata_suffix = $core->blog->settings->addToAny->suffix;

if (!empty($_POST))
{
	try
	{
		$ata_active = !empty($_POST['ata_active']);
		$ata_on_post = !empty($_POST['ata_on_post']);
		$ata_on_page = !empty($_POST['ata_on_page']);
		$ata_before_content = !empty($_POST['ata_before_content']);
		$ata_after_content = !empty($_POST['ata_after_content']);
		$ata_style = trim($_POST['ata_style']);
		$ata_prefix = trim(html::escapeHTML($_POST['ata_prefix']));
		$ata_suffix = trim(html::escapeHTML($_POST['ata_suffix']));

		# Everything's fine, save options
		$core->blog->settings->addNamespace('addToAny');
		$core->blog->settings->addToAny->put('active',$ata_active);
		$core->blog->settings->addToAny->put('on_post',$ata_on_post);
		$core->blog->settings->addToAny->put('on_page',$ata_on_page);
		$core->blog->settings->addToAny->put('before_content',$ata_before_content);
		$core->blog->settings->addToAny->put('after_content',$ata_after_content);
		$core->blog->settings->addToAny->put('style',$ata_style);
		$core->blog->settings->addToAny->put('prefix',$ata_prefix);
		$core->blog->settings->addToAny->put('suffix',$ata_suffix);

		$core->blog->triggerBlog();

		dcPage::addSuccessNotice(__('Settings have been successfully updated.'));
		http::redirect($p_url);
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
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
	array(
		html::escapeHTML($core->blog->name) => '',
		__('AddToAny') => ''
	));
echo dcPage::notices();

echo
'<form action="'.$p_url.'" method="post">'.
'<p>'.form::checkbox('ata_active',1,$ata_active).' '.
'<label for="ata_active" class="classic">'.__('Active AddToAny').'</label></p>'.

'<h3>'.__('Options').'</h3>'.

'<p>'.form::checkbox('ata_on_post',1,$ata_on_post).' '.
'<label for="ata_on_post" class="classic">'.__('Automatically insert AddToAny sharing tool on posts').'</label></p>'.
'<p>'.form::checkbox('ata_on_page',1,$ata_on_page).' '.
'<label for="ata_on_page" class="classic">'.__('Automatically insert AddToAny sharing tool on pages').'</label></p>'.

'<h3>'.__('Position').'</h3>'.

'<p>'.form::checkbox('ata_before_content',1,$ata_before_content).' '.
'<label for="ata_before_content" class="classic">'.__('Insert AddToAny sharing tool before content').'</label></p>'.
'<p>'.form::checkbox('ata_after_content',1,$ata_after_content).' '.
'<label for="ata_after_content" class="classic">'.__('Insert AddToAny sharing tool after content').'</label></p>'.

'<h3>'.__('Advanced options').'</h3>'.

'<p class="area"><label for="ata_style">'.__('AddToAny sharing tool CSS style:').'</label> '.
form::textarea('ata_style',30,8,html::escapeHTML($ata_style)).'</p>'.

'<p><label for="ata_prefix">'.__('AddToAny sharing tool text prefix:').'</label> '.
form::field('ata_prefix',30,128,html::escapeHTML($ata_prefix)).'</p>'.
'<p class="form-note">'.__('This will be inserted before link.').'</p>'.

'<p><label for="ata_suffix">'.__('AddToAny sharing tool text suffix:').'</label> '.
form::field('ata_suffix',30,128,html::escapeHTML($ata_suffix)).'</p>'.
'<p class="form-note">'.__('This will be inserted after link.').'</p>'.

'<p class="form-note">'.__('The link will be inserted as <code>&lt;p class="a2a"&gt;&lt;a …&gt;&lt;img …&gt;&lt;/a&gt;&lt;/p&gt;</code> form.').'</p>'.

'<p class="form-note">'.__('See <a href="https://www.addtoany.com/">AddToAny web site</a> for more information.').'</p>'.

'<p>'.$core->formNonce().'<input type="submit" value="'.__('Save').'" /></p>'.
'</form>';

?>
</body>
</html>
