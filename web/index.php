<?php

// Enable strict error reporting
error_reporting(E_ALL | E_STRICT);

ini_set('display_errors', 1);

require('Markr.class.php');

// Bootstrap Chainr
Markr::bootstrap();

/**
 * @author Christian Schmitz <csc@soulworks.de>
 */
class MyElementHandler extends SW_Markr_ElementHandler {
	function __construct(array $options = array()) {
		parent::__construct($options);
	}
	
	protected function handleServer(DOMElement $e) {
		$ne = $this->createFragment($e);
		$ne->appendXML('<p>'.$_SERVER['SERVER_NAME'].'</p>');
		return $ne;
	}

	protected function handleEcho(DOMElement $e) {
		$ne = $this->createFragment($e);
		$ne->appendXML('<h1>Echo: '.$e->textContent.'</h1>');
		return $ne;
	}

	protected function handleHeadline(DOMElement $e) {
		$ne = $this->createFragment($e);
		$ne->appendXML('<h1>'.$e->textContent.'</h1>');
		return $ne;
	}
	
	protected function handleCode(DOMElement $e) {
		$ne = $this->createFragment($e);
		$ne->appendXML('<pre><code>'.$e->textContent.'</code></pre>');
		return $ne;
	}
	
	protected function handleHtml(DOMElement $e) {
		$ne = $this->createFragment($e);
		$ne->appendXML('<pre><code>'.$this->getInnerHtml($e).'</code></pre>');
		return $ne;
	}
	
	protected function handleBody(DOMElement $e) {
		$ne = $this->createFragment($e);
		$ne->appendXML('<div class="container">'.$this->getInnerHtml($e).'</div>');
		return $ne;
	}
}

$filename =& $_GET['q'];

if (empty($filename)) {
	$filename = 'index.swml';
}

try {
	// Create a markup parser from a well-formed XML file
	$p = SW_Markr_Parser::createFromXmlFile($filename);
	
	// Set the custom namespace we'll work on
	$p->setNamespace('http://www.soulworks.com/swml/1.0');
	
	// Attach an element handler to the parser
	$p->setElementHandler(new MyElementHandler(array(
		'use_default_handler' => false
	)));
	
	// Process the document
	$doc = $p->process();
	
	// Output the document
	echo $doc->saveXML();
} catch(Exception $e) {
	var_dump($e->getMessage());
}
