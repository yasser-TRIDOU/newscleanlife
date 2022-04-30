<?php
/**
 * On-the-fly HTML, CSS & JS compression
 *
 * Text Domain: lovethemes
 *
 * @package WordPress\LoveThemes Auto SEO
 */
class LoveThemes_Minify
{
	// Settings
	protected $compress_css    = true;
	protected $compress_js     = true;
	protected $info_comment    = true;
	protected $remove_comments = true;

	// Variables
	protected $html;

	public function __construct($html)
	{
		if (!empty($html))
		{
			$this->parseHTML($html);
		}
	}

	public function __toString()
	{
		return $this->html;
	}

	protected function bottomComment($raw, $compressed)
	{
		$raw = strlen($raw);
		$compressed = strlen($compressed);

		$savings = ($raw-$compressed) / $raw * 100;

		$savings = round($savings, 2);

		return '
<!--
Dynamic HTML Minification.
Pagesize Before Minification: '.$raw.' bytes.
Pagesize After Minification: '.$compressed.' bytes.
Total Saving: '.$savings.'%.
-->';
	}
	protected function minifyHTML($html)
	{
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';

		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

		$overriding = false;
		$raw_tag = false;

		// Variable reused for output
		$html = '';

		foreach ($matches as $token)
		{
			$tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

			$content = $token[0];

			if (is_null($tag))
			{
				if ( !empty($token['script']) )
				{
					$strip = $this->compress_js;
				}
				else if ( !empty($token['style']) )
				{
					$strip = $this->compress_css;
				}
				else if ($content == '<!--lovethemes-minify no minification-->')
				{
					$overriding = !$overriding;

					// Don't print the comment
					continue;
				}
				else if ($this->remove_comments)
				{
					if (!$overriding && $raw_tag != 'textarea')
					{
						// Remove any HTML comments, except MSIE conditional comments
						$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
					}
				}
			}
			else
			{
				if ($tag == 'pre' || $tag == 'textarea')
				{
					$raw_tag = $tag;
				}
				else if ($tag == '/pre' || $tag == '/textarea')
				{
					$raw_tag = false;
				}
				else
				{
					if ($raw_tag || $overriding)
					{
						$strip = false;
					}
					else
					{
						$strip = true;

						// Remove any empty attributes, except:
						// action, alt, content, src
						// This breaks WooCommerce variations select option
						//$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

						// Remove any space before the end of self-closing XHTML tags
						// JavaScript excluded
						$content = str_replace(' />', '/>', $content);
					}
				}
			}

			if ($strip)
			{
				$content = $this->removeWhiteSpace($content);
			}

			$html .= $content;
		}

		return $html;
	}

	public function parseHTML($html)
	{
		$this->html = $this->minifyHTML($html);

		if ($this->info_comment)
		{
			$this->html .= "\n" . $this->bottomComment($html, $this->html);
		}
	}

	protected function removeWhiteSpace($str)
	{
		$str = str_replace("\t", ' ', $str);
		$str = str_replace("\n",  ' ', $str);
		$str = str_replace("\r",  ' ', $str);

		while (stristr($str, '  '))
		{
			$str = str_replace('  ', ' ', $str);
		}

		return $str;
	}
}

function lovethemes_minification_finish($html)
{
	return new LoveThemes_Minify($html);
}

function lovethemes_minification_start()
{
	ob_start( 'lovethemes_minification_finish' );
}
add_action( 'get_header', 'lovethemes_minification_start' );