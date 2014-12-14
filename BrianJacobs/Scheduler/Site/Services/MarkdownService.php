<?php namespace Scheduler\Services;

use ParsedownExtra;

class MarkdownService {

	protected $markdown;

	public function __construct(ParsedownExtra $markdown)
	{
		$this->markdown = $markdown;
	}

	/**
	 * Parse the string from Markdown to HTML.
	 *
	 * @param	string	$str	The string to parse
	 * @return	string
	 */
	public function parse($str)
	{
		return $this->markdown->text($str);
	}
	
}