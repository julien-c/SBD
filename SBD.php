<?php

class SBD 
{
	protected $filepath;
	protected $paths;
	
	public function __construct($filepath)
	{
		$this->filepath = $filepath;
		$this->paths = array();
	}
	
	public function detect()
	{
		$htmlstr = file_get_contents($this->filepath);
		
		$dom = new DOMDocument();
		// $dom->preserveWhiteSpace = false;
		$dom->loadHTML($htmlstr);
		// Not loadXML, so as to preserve tag names when exporting to XPath.
		
		$body = $dom->getElementsByTagName('body')->item(0);
		
		$this->walkthrough2($body, '');
		
		return $this->paths;
	}
	
	
	public function walkthrough(DOMNode $domNode, $indent)
	{
		foreach ($domNode->childNodes as $node) {
			
			if ($node instanceof DOMText) {
				if (trim($node->textContent) !== '') {
					$xpath = $node->getNodePath();
					if (substr($xpath, -7) == '/text()') {
						$xpath = substr($xpath, 0, -7);
					}
					
					$text = $node->textContent;
					
					
					$sentences = $this->segment($text);
					
					if (count($sentences) == 1) {
						// There was only one sentence in this node:
						$this->paths[] = $xpath;
						// echo "====" . "\n";
						// echo $xpath . "\n";
						// echo $text . "\n";
					}
					else {
						$offsets = array();
						foreach ($sentences as $sentence) {
							$offsets[] = $sentence[1];
						}
						
						for ($i = 0; $i < count($offsets); $i++) { 
							if ($i == count($offsets) - 1) {
								$range = ':' . $offsets[$i] . ',' . strlen($text);
							}
							else {
								$range = ':' . $offsets[$i] . ',' . $offsets[$i+1];
							}
							
							$this->paths[] = $xpath . $range;
							
							// echo "====" . "\n";
							// echo $xpath . $range . "\n";
							// echo $sentences[$i][0] . "\n";
						}
						
					}
				}
			}
			else {
				// Everything else.
			}
			
			if ($node->hasChildNodes()) {
				$this->walkthrough($node, $indent + 1);
			}
		}
	}
	
	
	
	
	public function walkthrough2(DOMNode $domNode, $path)
	{
		if ($domNode->nodeType === XML_TEXT_NODE) {
			if (trim($domNode->textContent) !== '') {
				// echo $path;
				// echo "\n";
				
				$text = $domNode->textContent;
				
				$sentences = $this->segment($text);
				
				$offsets = array();
				foreach ($sentences as $sentence) {
					$offsets[] = $sentence[1];
				}
				
				for ($i = 0; $i < count($offsets); $i++) { 
					if ($i == count($offsets) - 1) {
						$range = array(
							'start' => $offsets[$i],
							'end'   => strlen($text)
						);
					}
					else {
						$range = array(
							'start' => $offsets[$i],
							'end'   => $offsets[$i+1] - 1
						);
					}
					
					$fullPath = $path.':'.$range['start'] . ',' . $path.':'.$range['end'];
					
					$this->paths[] = $fullPath;
					
					echo $fullPath;
					echo "\n";
				}
				
			}
		}
		
		
		if ($domNode->hasChildNodes()) {
			foreach ($domNode->childNodes as $index => $node) {
				$newpath = ($path === '') ? $index : $path.'/'.$index;
				$this->walkthrough2($node, $newpath);
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
		
		$sentences = preg_split($re, $text, -1, PREG_SPLIT_OFFSET_CAPTURE);
		
		return $sentences;
	}
	
}