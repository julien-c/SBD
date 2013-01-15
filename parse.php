<?php

require 'PHP-EPUBParser/src/EPUBParser/EPUB/Book.php';

require 'PHP-EPUBParser/src/EPUBParser/EPUB/OCF/Container.php';

require 'PHP-EPUBParser/src/EPUBParser/EPUB/Publication/Package.php';
require 'PHP-EPUBParser/src/EPUBParser/EPUB/Publication/Package/Manifest.php';
require 'PHP-EPUBParser/src/EPUBParser/EPUB/Publication/Package/Manifest/Item.php';
require 'PHP-EPUBParser/src/EPUBParser/EPUB/Publication/Package/Spine.php';
require 'PHP-EPUBParser/src/EPUBParser/EPUB/Publication/Package/Spine/Itemref.php';
require 'PHP-EPUBParser/src/EPUBParser/EPUB/Publication/Package/Metadata.php';
require 'PHP-EPUBParser/src/EPUBParser/EPUB/Publication/Package/Guide.php';
require 'PHP-EPUBParser/src/EPUBParser/EPUB/Publication/Package/Guide/Reference.php';

use EPUBParser\EPUB;

require 'SBD.php';



$path = 'samples/WWZMB';


$book = new EPUB\Book($path);
$sentences = array();

foreach ($book->getPackage()->getSpine()->getItemrefs() as $itemref) {
	$item = $itemref->getItem();
	
	$filepath = $path . '/' . $item->getHref();
	
	$sbd = new SBD($filepath);
	$sentences[$item->getId()] = $sbd->detect();
}

file_put_contents('sentences.json', json_encode($sentences));

