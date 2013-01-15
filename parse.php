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


$book = new EPUB\Book('samples/WWZMB');


foreach ($book->getPackage()->getManifest()->getItems() as $item) {
    echo $item->getId(), ': ', $item->getHref(), PHP_EOL;
    print_r($item->getProperties());
}
foreach ($book->getPackage()->getSpine()->getItemrefs() as $itemref) {
    print_r($itemref->getProperties());
}

