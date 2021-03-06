<?php namespace Scheduler\Services;

use League\CommonMark\CommonMarkConverter as Parser;

class MarkdownService {

	protected $parser;

	public function __construct(Parser $parser)
	{
		$this->parser = $parser;
	}

	/**
	* Parse the string from Markdown to HTML.
	*
	* @param	string	$str	The string to parse
	* @return	string
	*/
	public function parse($str)
	{
		return $this->parser->convertToHtml($str);
	}

}