<?php

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @package Markr
 * @author Christian Schmitz <csc@soulworks.de>
 */
class SW_Markr_Parser {
	/*
	 * @var $doc DOMDocument
	 */
	private $doc;
	
	private $namespace;
	
	private $handler;
	
	private function __construct() {
		$this->doc = new DOMDocument();
		$this->doc->preserveWhiteSpace = false;
	}

	public static function createFromXmlFile($file) {
		$clazz = __CLASS__;
		$parser = new $clazz;
		$parser->loadFromXmlFile($file);
		return $parser;
	}
	
	protected function loadFromXmlFile($file) {
		if ($this->doc->load($file) === false) {
			throw new SW_Markr_Exception('document could not be loaded');
		}
	}
	
	public function process() {
		$elements = $this->doc->getElementsByTagNameNS($this->namespace, '*');
		
		// See http://de.php.net/manual/de/domnode.replacechild.php#50500
		$i = $elements->length - 1;
		while ($i > -1) {
			// Pick up the element
			$element = $elements->item($i); 
			
			// Pass the element to the handler
			if ($this->handler->handle($element) === false) {
				// Removes the element from the document
				$element->parentNode->removeChild($element);
			}
			
			$i--; 
		}
		
		// Return the final document
		return $this->doc;
	}
	
	/**
	 * Sets the XML namespace to work on.
	 *
	 * @param string $ns XML Namespace to parse
	 */
	public function setNamespace($ns) {
		$this->namespace = $ns;
	}
	
	public function setElementHandler(SW_Markr_ElementHandler $handler) {
		$this->handler = $handler;
	}
}
