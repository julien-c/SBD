<?php

class SBD 
{
	protected $filepath;
	
	public function __construct($filepath)
	{
		$this->filepath = $filepath;
	}
	
	public function detect()
	{
		$htmlstr = file_get_contents($this->filepath);
		
		$dom = new DOMDocument();
		$dom->preserveWhiteSpace = false;
		$dom->loadHTML($htmlstr);
		// Not loadXML, so as to preserve tag names when exporting to XPath.
		
		$body = $dom->getElementsByTagName('body')->item(0);
		
		$this->walkthrough($body, 0);
	}
	
	
	public function walkthrough(DOMNode $domNode, $indent)
	{
		foreach ($domNode->childNodes as $node) {
			
			if ($node instanceof DOMText) {
				if (trim($node->textContent) !== '') {
					$xpath = $node->getNodePath();
					$text  = $node->textContent;
					
					// echo "====" . "\n";
					// echo $xpath . "\n";
					// echo $text . "\n";
					
					$sentences = $this->segment($text);
					var_dump($sentences);
				}
			}
			else {
			}
			
			if ($node->hasChildNodes()) {
				$this->walkthrough($node, $indent + 1);
			}
		}
	}
	
	
	public function segment($text)
	{
		// @see http://stackoverflow.com/questions/5032210/php-sentence-boundaries-detection
		
		$re = '/# Split sentences on whitespace between them.
			(?<=                # Begin positive lookbehind.
			  [.!?]             # Either an end of sentence punct,
			| [.!?][\'"]        # or end of sentence punct and quote.
			)                   # End positive lookbehind.
			(?<!                # Begin negative lookbehind.
			  Mr\.              # Skip either "Mr."
			| Mrs\.             # or "Mrs.",
			| Ms\.              # or "Ms.",
			| Jr\.              # or "Jr.",
			| Dr\.              # or "Dr.",
			| Prof\.            # or "Prof.",
			| Sr\.              # or "Sr.",
			                    # or... (you get the idea).
			)                   # End negative lookbehind.
			\s+                 # Split on whitespace between sentences.
			/ix';
		
		$sentences = preg_split($re, $text, -1, PREG_SPLIT_NO_EMPTY);
		
		return $sentences;
	}
	
}