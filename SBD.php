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
				echo "====" . "\n";
				echo $node->getNodePath() . "\n";
				echo $node->textContent . "\n";
			}
			else {
				// echo sprintf(" [%s]", $node->getNodePath());
				// var_dump($node->ownerDocument);
			}
			echo "\n";
			
			if ($node->hasChildNodes()) {
				$this->walkthrough($node, $indent + 1);
			}
		}
}
}