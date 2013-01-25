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

$path = $argv[1];

$book = new EPUB\Book($path);

$components = array();

foreach ($book->getPackage()->getSpine()->getItemrefs() as $itemref) {
	$item = $itemref->getItem();
	$components[] = $item->getHref();
}

$metadata = $book->getPackage()->getMetadata();

file_put_contents($path.'.json', json_encode(array(
	'components' => $components,
	'metadata'   => array(
		'title' => $metadata->getTitle(),
		'creator' => $metadata->getCreator(),
		'description' => $metadata->getDescription(),
		'subjects' => $metadata->getSubjects(),
	)
)));

