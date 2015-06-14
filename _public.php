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

if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('publicHeadContent', array('dcAddToAny','publicHeadContent'));

$core->addBehavior('publicEntryBeforeContent', array('dcAddToAny','publicEntryBeforeContent'));
$core->addBehavior('publicEntryAfterContent', array('dcAddToAny','publicEntryAfterContent'));

$core->tpl->addValue('AddToAny',array('dcAddToAny','tplAddToAny'));

global $a2a_loaded;

class dcAddToAny
{
	public static function publicEntryBeforeContent($core,$_ctx)
	{
		global $a2a_loaded;

		$ret = '';
		if ($core->blog->settings->addToAny->active) {
			if (($_ctx->posts->post_type == 'post' && $core->blog->settings->addToAny->on_post) ||
				($_ctx->posts->post_type == 'page' && $core->blog->settings->addToAny->on_page))
			{
				if ($core->blog->settings->addToAny->before_content) {
					echo self::addToAny(
						$_ctx->posts->getURL(),$_ctx->posts->post_title,!$a2a_loaded,
						$core->blog->settings->addToAny->prefix,
						$core->blog->settings->addToAny->suffix);
					$a2a_loaded = true;
				}
			}
		}
	}

	public static function publicEntryAfterContent($core,$_ctx)
	{
		global $a2a_loaded;

		$ret = '';
		if ($core->blog->settings->addToAny->active) {
			if (($_ctx->posts->post_type == 'post' && $core->blog->settings->addToAny->on_post) ||
				($_ctx->posts->post_type == 'page' && $core->blog->settings->addToAny->on_page))
			{
				if ($core->blog->settings->addToAny->after_content) {
					echo self::addToAny(
						$_ctx->posts->getURL(),$_ctx->posts->post_title,!$a2a_loaded,
						$core->blog->settings->addToAny->prefix,
						$core->blog->settings->addToAny->suffix);
					$a2a_loaded = true;
				}
			}
		}
	}

	public static function tplAddToAny($attr)
	{
		global $core,$_ctx;
		global $a2a_loaded;

		$ret = '';
		if ($core->blog->settings->addToAny->active) {
			$f = $core->tpl->getFilters($attr);
			$url = sprintf($f,$_ctx->posts->getURL());
			$ret = self::addToAny(
				$url,$_ctx->posts->post_title,!$a2a_loaded,
				$core->blog->settings->addToAny->prefix,
				$core->blog->settings->addToAny->suffix);
			$a2a_loaded = true;
		}
		return $ret;
	}

	public static function publicHeadContent()
	{
		global $core;

		$core->blog->settings->addNamespace('addToAny');
		if (($core->blog->settings->addToAny->active) && ($core->blog->settings->addToAny->style !== null))
		{
			echo '<style type="text/css">'."\n".self::customStyle()."</style>\n";
		}
	}

	// Helpers

	public static function addToAny($url,$label,$first=true,$prefix,$suffix)
	{
		$ret =
			'<p class="a2a">'.($prefix !== null ? $prefix.' ' : '').'<a class="a2a_dd" href="http://www.addtoany.com/share_save">'."\n".
				'<img src="'.html::stripHostURL($GLOBALS['core']->blog->getQmarkURL().
					'pf=addToAny/img/favicon.png').'" alt="'.__('Share').'"/>'."\n".
			'</a>'.($suffix !== null ? ' '.$suffix : '').'</p>'."\n";
		if ($first) {
			$ret .=
				'<script type="text/javascript">'."\n".
					'a2a_config = {'."\n".
						'linkname: \''.addslashes($label).'\','."\n".
						'linkurl: \''.$url.'\','."\n".
						'onclick: 1,'."\n".
						'num_services: 10,'."\n".
						'show_title: 1'."\n".
					'};'."\n".
				'</script>'."\n".
				'<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>'."\n";
		} else {
			$ret .=
				'<script type="text/javascript">'."\n".
					'a2a_config.linkname = \''.addslashes($label).'\';'."\n".
					'a2a_config.linkurl = \''.$url.'\';'."\n".
					'a2a.init(\'page\');'."\n".
				'</script>'."\n";
		}
		return $ret;
	}

	public static function customStyle()
	{
		$s = $GLOBALS['core']->blog->settings->addToAny->style;
		if ($s === null) {
			return;
		}
		return $s."\n";
	}
}
