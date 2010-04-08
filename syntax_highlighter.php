<?php
/*
Plugin Name: Syntax Highlighter for WordPress
Plugin URI: http://wppluginsj.sourceforge.jp/syntax-highlighter/
Description: 100% JavaScript syntax highlighter This plugin makes using the <a href="http://alexgorbatchev.com/wiki/SyntaxHighlighter">Syntax highlighter 2.1</a> to highlight code snippets within WordPress simple. Supports Bash, C++, C#, CSS, Delphi, Java, JavaScript, PHP, Python, Ruby, SQL, VB, VB.NET, XML, and (X)HTML.
Version: 2.1.364.1
Author: wokamoto
Author URI: http://dogmap.jp/
Text Domain: syntax-highlighter
Domain Path: /languages/

License:
 Released under the GPL license
  http://www.gnu.org/copyleft/gpl.html

  Copyright 2008 - 2010 wokamoto (email : wokamoto1973@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Includes:
 SyntaxHighlighter Ver.2.1.364
  http://alexgorbatchev.com/
  Copyright (C) 2004-2009 Alex Gorbatchev.
  Licensed under The GNU LESSER GENERAL PUBLIC LICENSE

*/
if (is_admin())
	return false;

if (!class_exists('wokController') || !class_exists('wokScriptManager'))
	require(dirname(__FILE__).'/includes/common-controller.php');

class SyntaxHighlighter extends wokController {	/* Start Class */
	var $plugin_ver = '2.1.364';

	var $theme = 'ThemeDefault';
	var $default_atts = array(
		 'num' => 1
		,'lang' => 'plain'
		,'lang_name' => 'false'
		,'highlight_lines' => ''
		,'collapse' => 'false'
		,'gutter' => 'true'
		,'ruler' => 'false'
		,'toolbar' => 'true'
		,'smart_tabs' => 'true'
		,'tab_size' => '4'
		,'light' => 'false'
		,'auto_link' => 'false'
		,'font_size' => '100%'
		,'encode' => 'false'
	);

	var $target = array(
		'AS3' ,
		'Bash' ,
		'ColdFusion' ,
		'CSharp' ,
		'Cpp' ,
		'JavaScript' ,
		'JavaFX' ,
		'JAVA' ,
		'Delphi' ,
		'Diff' ,
		'Erlang' ,
		'Groovy' ,
		'Patch' ,
		'Pascal' ,
		'Perl' ,
		'PHP' ,
		'Python' ,
		'Plain' ,
		'PowerShell' ,
		'Ruby' ,
		'Scala' ,
		'Shell' ,
		'Text' ,
		'vbnet' ,
		'VB' ,
		'SQL' ,
		'CSS' ,
		'XHTML' ,
		'XML' ,
		'XSLT' ,
		'HTML' ,
		'C' ,
	);
	var $options;

	/*
	* Constructor
	*/
	function SyntaxHighlighter() {
		$this->__construct();
	}
	function __construct() {
		$this->init(__FILE__);

		$this->options = array(
			"as3"  => array(false, 'AS3') ,
			"bash" => array(false, 'Bash') ,
			"c" => array(false, 'C') ,
			"cpp" => array(false, 'C++') ,
			"c-sharp" => array(false, 'C#') ,
			"coldfusion" => array(false, 'ColdFusion') ,
			"jscript" => array(false, 'Java Script') ,
			"java" => array(false, 'JAVA') ,
			"javafx" => array(false, 'JavaFX') ,
			"delphi" => array(false, 'Delphi') ,
			"diff" => array(false, 'Diff') ,
			"erlang" => array(false, 'Erlang') ,
			"groovy" => array(false, 'Groovy') ,
			"patch" => array(false, 'Patch') ,
			"pascal" => array(false, 'Pascal') ,
			"perl" => array(false, 'Perl') ,
			"php" => array(false, 'PHP') ,
			"plain" => array(false, 'Plain Text') ,
			"powershell" => array(false, 'PowerShell') ,
			"python" => array(false, 'Python') ,
			"ruby" => array(false, 'Ruby') ,
			"scala" => array(false, 'Scala') ,
			"shell" => array(false, 'Shell') ,
			"vb" => array(false, 'VB') ,
			"vb.net" => array(false, 'VB.Net') ,
			"sql" => array(false, 'SQL') ,
			"css" => array(false, 'CSS') ,
			"xml" => array(false, 'XML') ,
			"html" => array(false, 'HTML') ,
			"xhtml" => array(false, 'XHTML') ,
			"xslt" => array(false, 'XSLT') ,
			);
	}

	function add_head() {
		$found = $this->haveShortCode();
		if ($found !== FALSE) {
			echo "<link href=\"{$this->plugin_url}css/shCore.css?ver={$this->plugin_ver}\" type=\"text/css\" rel=\"stylesheet\" media=\"all\" />\n";
			echo "<link href=\"{$this->plugin_url}css/sh{$this->theme}.css?ver={$this->plugin_ver}\" type=\"text/css\" rel=\"stylesheet\" media=\"all\" />\n";
			add_filter('the_content', array(&$this, 'parse_shortcodes'), 7);
			add_action('wp_footer', array(&$this, 'add_footer'));
		}
	}

	function add_footer(){
		$enabled = false;
		foreach ($this->options as $key => $val) {
			if ($val[0]) {$enabled = true; break;}
		}
		if (!$enabled) return;

		$scripts  = "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shCore.js?ver={$this->plugin_ver}\"></script>\n";

		// AS3
		if (isset($this->options["as3"]) && $this->options["as3"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushAS3.js?ver={$this->plugin_ver}\"></script>\n";

		// Bash / shell
		if (isset($this->options["bash"]) && $this->options["bash"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushBash.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["shell"]) && $this->options["shell"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushBash.js?ver={$this->plugin_ver}\"></script>\n";

		// C / C++
		if (isset($this->options["c"]) && $this->options["c"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushCpp.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["cpp"]) && $this->options["cpp"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushCpp.js?ver={$this->plugin_ver}\"></script>\n";

		// C#
		if (isset($this->options["c-sharp"]) && $this->options["c-sharp"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushCSharp.js?ver={$this->plugin_ver}\"></script>\n";

		// ColdFusion
		if (isset($this->options["coldfusion"]) && $this->options["coldfusion"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushColdFusion.js?ver={$this->plugin_ver}\"></script>\n";

		// Diff
		if (isset($this->options["diff"]) && $this->options["diff"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushDiff.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["patch"]) && $this->options["patch"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushDiff.js?ver={$this->plugin_ver}\"></script>\n";

		// Groovy
		if (isset($this->options["groovy"]) && $this->options["groovy"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushGroovy.js?ver={$this->plugin_ver}\"></script>\n";

		// Java
		if (isset($this->options["java"]) && $this->options["java"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushJava.js?ver={$this->plugin_ver}\"></script>\n";

		// JavaScript
		if (isset($this->options["jscript"]) && $this->options["jscript"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushJScript.js?ver={$this->plugin_ver}\"></script>\n";

		// JavaFX
		if (isset($this->options["javafx"]) && $this->options["javafx"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushJavaFX.js?ver={$this->plugin_ver}\"></script>\n";

		// Delphi
		if (isset($this->options["delphi"]) && $this->options["delphi"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushDelphi.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["pascal"]) && $this->options["pascal"][0])
			 $scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushDelphi.js?ver={$this->plugin_ver}\"></script>\n";

		// Erlang
		if (isset($this->options["erlang"]) && $this->options["erlang"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushErlang.js?ver={$this->plugin_ver}\"></script>\n";

		// Perl
		if (isset($this->options["perl"]) && $this->options["perl"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushPerl.js?ver={$this->plugin_ver}\"></script>\n";

		// PHP
		if (isset($this->options["php"]) && $this->options["php"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushPhp.js?ver={$this->plugin_ver}\"></script>\n";

		// Python
		if (isset($this->options["python"]) && $this->options["python"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushPython.js?ver={$this->plugin_ver}\"></script>\n";

		// Plain Text
		if (isset($this->options["plain"]) && $this->options["plain"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushPlain.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["text"]) && $this->options["text"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushPlain.js?ver={$this->plugin_ver}\"></script>\n";

		// PowerShell
		if (isset($this->options["powershell"]) && $this->options["powershell"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushPowerShell.js?ver={$this->plugin_ver}\"></script>\n";

		// Ruby
		if (isset($this->options["ruby"]) && $this->options["ruby"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushRuby.js?ver={$this->plugin_ver}\"></script>\n";

		// Scala
		if (isset($this->options["scala"]) && $this->options["scala"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushScala.js?ver={$this->plugin_ver}\"></script>\n";

		// SQL
		if (isset($this->options["sql"]) && $this->options["sql"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushSql.js?ver={$this->plugin_ver}\"></script>\n";

		// Visual Basic
		if (isset($this->options["vb"]) && $this->options["vb"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushVb.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["vb.net"]) && $this->options["vb.net"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushVb.js?ver={$this->plugin_ver}\"></script>\n";

		// CSS
		if (isset($this->options["css"]) && $this->options["css"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushCss.js?ver={$this->plugin_ver}\"></script>\n";

		// XML / (X)HTML
		if (isset($this->options["xml"]) && $this->options["xml"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushXml.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["html"]) && $this->options["html"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushXml.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["xhtml"]) && $this->options["xhtml"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushXml.js?ver={$this->plugin_ver}\"></script>\n";
		elseif (isset($this->options["xslt"]) && $this->options["xslt"][0])
			$scripts .= "<script type=\"text/javascript\" src=\"{$this->plugin_url}js/shBrushXml.js?ver={$this->plugin_ver}\"></script>\n";

		echo $scripts;

//		-- for SyntaxHighlighter 1.5.x
//		$js_out  = "dp.SyntaxHighlighter.Toolbar.Commands.About.label='" . __('?', $this->textdomain_name) . "';";
//		$js_out .= "dp.SyntaxHighlighter.Toolbar.Commands.CopyToClipboard.label='" . __('copy to clipboard', $this->textdomain_name) . "';";
//		$js_out .= "dp.SyntaxHighlighter.Toolbar.Commands.CopyToClipboard.func=function(B,A){var D=A.originalCode;var w=window,d=document;if(w.clipboardData){w.clipboardData.setData('text',D)}else{if(dp.sh.ClipboardSwf!=null){var C=A.flashCopier;if(C==null){C=d.createElement('div');A.flashCopier=C;A.div.appendChild(C)}C.innerHTML='<embed src=\"'+dp.sh.ClipboardSwf+'\" FlashVars=\"clipboard='+encodeURIComponent(D)+'\" width=\"0\" height=\"0\" type=\"application/x-shockwave-flash\"></embed>'}}alert(\"" . __('The code is in your clipboard now', $this->textdomain_name) . "\")};";
//		$js_out .= "dp.SyntaxHighlighter.Toolbar.Commands.ExpandSource.label='" . __('+ expand source', $this->textdomain_name) . "';";
//		$js_out .= "dp.SyntaxHighlighter.Toolbar.Commands.PrintSource.label='" . __('print', $this->textdomain_name) . "';";
//		$js_out .= "dp.SyntaxHighlighter.Toolbar.Commands.ViewSource.label='" . __('view plain', $this->textdomain_name) . "';";
//		$js_out .= "dp.SyntaxHighlighter.ClipboardSwf = '{$this->plugin_url}js/clipboard.swf';\n";
//		$js_out .= "dp.SyntaxHighlighter.HighlightAll('code');\n";

//		-- for SyntaxHighlighter 2.0.x
		$js_out .= 'with(SyntaxHighlighter.config.strings){';
		$js_out .= 'expandSource="' . __('+ expand source', $this->textdomain_name) . '";';
		$js_out .= 'viewSource="' . __('view plain', $this->textdomain_name) . '";';
		$js_out .= 'copyToClipboard="' . __('copy to clipboard', $this->textdomain_name) . '";';
		$js_out .= 'copyToClipboardConfirmation="' . __('The code is in your clipboard now', $this->textdomain_name) . '";';
		$js_out .= 'print="' . __('print', $this->textdomain_name) . '";';
		$js_out .= 'help="' . __('?', $this->textdomain_name) . '";';
		$js_out .= 'noBrush="' . __("Can't find brush for: ", $this->textdomain_name) . '";';
		$js_out .= 'brushNotHtmlScript="' . __("Brush wasn't made for html-script option: ", $this->textdomain_name) . '";';
		$js_out .= '}';
		$js_out .= "SyntaxHighlighter.config.clipboardSwf=\"{$this->plugin_url}js/clipboard.swf\";\n";
		$js_out .= "SyntaxHighlighter.all();\n";

		$this->writeScript($js_out, 'footer');
	}

	function Shortcode_Handler($atts, $content = null, $startTag) {
		extract(shortcode_atts($this->default_atts, $atts));

		if (strtolower($encode) === 'true')
			$encode = true;
		elseif ($content != strip_tags($content))
			$encode = true;
		else
			$encode = false;

		$lang_name = (strtolower($lang_name) == 'true');

		if (strtolower($startTag) === 'code')
			$startTag = strtolower($lang);
		$pVal = (int) $num;				// get the starting line number

		$outTxt = '';

		$inTxt = ( $encode
			? htmlentities($content, ENT_QUOTES, get_settings('blog_charset'))
			: $content );
		if (isset($this->options[$startTag]))
			$this->options[$startTag][0] = true;

		if ($lang_name) {
			$outTxt = "\n\n"
				. '<p class="lang-name">'
				. $this->options[$startTag][1]
				. '</p>'
				. "\n"
				;
		}

		$outTxt .= '<pre'
//		-- for SyntaxHighlighter 1.5.x
//			. ' name="code"'
//			. ' class="'.$startTag.($pVal > 1 ? ":firstLine[{$pVal}]" : '').'"'

//		-- for SyntaxHighlighter 2.0.x
			. ' class="'
				. "brush: {$startTag};"
				. ($pVal > 1 ? " first-line: {$pVal};" : '')
				. (!empty($highlight_lines) ? " highlight: [{$highlight_lines}];" : '')
				. (strtolower($collapse) == 'true' ? ' collapse: true;' : '')
				. (strtolower($gutter) == 'false' ? ' gutter: false;' : '')
				. (strtolower($ruler) == 'true' ? ' ruler: true;' : '')
				. (strtolower($toolbar) == 'false' ? ' toolbar: false;' : '')
				. (strtolower($smart_tabs) == 'false' ? ' smart-tabs: false;' : '')
				. (strtolower($tab_size) != '4' ? ' tab-size: ' . (int)$tab_size . ';' : '')
				. (strtolower($auto_link) == 'false' ? ' auto-links: false;' : '')
				. (strtolower($light) == 'true' ? ' light: true;' : '')
				. ($font_size != '100%' ? " font-size: {$font_size};" : '')
				. '"'
			. '>'
			. $inTxt
			. '</pre>'
			. "\n\n"
			;

		return $outTxt;
	}

	/*
	* have short code
	*/
	function haveShortCode() {
		if (is_admin())
			return FALSE;

		global $wp_query;

		$pattern = '/\[(code';
		foreach ($this->target as $val) {
			$pattern .= '|' . strtolower($val);
		}
		$pattern .= ')[^\]]*\]/im';
		$found = array();
		$hasTeaser = !( is_single() || is_page() );
		foreach($wp_query->posts as $key => $post) {
			$post_content = isset($post->post_content) ? $post->post_content : '';
			if ( $hasTeaser && preg_match('/<!--more(.*?)?-->/', $post_content, $matches) ) {
				$content = explode($matches[0], $post_content, 2);
				$post_content = $content[0];
			}

			if (!empty($post_content) && preg_match_all($pattern, $post_content, $matches, PREG_SET_ORDER)) {
				foreach ((array) $matches as $match) {
					$found[$match[1]] = true;
				}
				unset($match);
			}
			unset($matches);
		}

		return (count($found) > 0 ? $found : FALSE);
	}

	function Shortcode_code($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'code');}

	function Shortcode_c($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'c');}
	function Shortcode_cpp($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'cpp');}
	function Shortcode_csharp($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'c-sharp');}
	function Shortcode_java($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'java');}
	function Shortcode_javascript($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'jscript');}
	function Shortcode_delphi($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'delphi');}
	function Shortcode_pascal($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'pascal');}
	function Shortcode_perl($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'perl');}
	function Shortcode_php($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'php');}
	function Shortcode_python($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'python');}
	function Shortcode_ruby($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'ruby');}
	function Shortcode_vb($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'vb');}
	function Shortcode_vbnet($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'vb.net');}
	function Shortcode_scala($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'scala');}
	function Shortcode_sql($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'sql');}
	function Shortcode_css($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'css');}
	function Shortcode_xml($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'xml');}
	function Shortcode_html($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'html');}
	function Shortcode_xhtml($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'xhtml');}
	function Shortcode_xslt($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'xslt');}

	function Shortcode_bash($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'bash');}
	function Shortcode_diff($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'diff');}
	function Shortcode_groovy($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'groovy');}
	function Shortcode_patch($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'patch');}
	function Shortcode_plain($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'plain');}
	function Shortcode_shell($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'shell');}
	function Shortcode_text($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'plain');}

	function Shortcode_as3($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'as3');}
	function Shortcode_coldfusion($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'coldfusion');}
	function Shortcode_javafx($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'javafx');}
	function Shortcode_erlang($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'erlang');}
	function Shortcode_powershell($atts, $content = null) {return $this->Shortcode_Handler($atts, $content, 'powershell');}

	/*
	* parse shortcodes
	*/
	function parse_shortcodes( $content ) {
		global $shortcode_tags;

		$shortcode_tags_org = $shortcode_tags;
		remove_all_shortcodes();

		add_shortcode('code', array(&$this, 'Shortcode_code'));
		foreach ($this->target as $val) {
			add_shortcode($val, array(&$this, 'Shortcode_' . strtolower($val)));
			if (strtolower($val) !== $val)
				add_shortcode(strtolower($val), array(&$this, 'Shortcode_' . strtolower($val)));
			if (strtoupper($val) !== $val)
				add_shortcode(strtoupper($val), array(&$this, 'Shortcode_' . strtolower($val)));
		}
		$content = do_shortcode( $content );

		$shortcode_tags = $shortcode_tags_org;

		return $content;
	}
}

$sh = new SyntaxHighlighter();

add_action('wp_head', array(&$sh, 'add_head'));

unset($sh);
?>