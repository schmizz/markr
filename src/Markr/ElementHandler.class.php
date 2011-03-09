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
 * Abstract implementation of an element handler which needs to be
 * extended through an own implementation.
 *
 * @package Markr
 * @author Christian Schmitz <csc@soulworks.de>
 */
abstract class SW_Markr_ElementHandler {
	private $useDefaultHandler = false;
	
	/**
	 * Constructs an new element handler.
	 *
	 * @param array $options Options for the element handler
	 */
	function __construct(array $options = array()) {
		$this->useDefaultHandler = isset($options['use_default_handler']) && $options['use_default_handler'] === true;
	}

	/**
	 * Default element handler if an appropriate one isn't found.
	 *
	 * @param DOMElement $element Element to handle
	 */
	protected function handleDefault(DOMElement $element) {
		$attrOut = '';
		foreach($element->attributes as $attribute) { /* @var $attribute DOMAttr */
			$attrOut .= sprintf('<dt>%s</dt><dd>%s</dd>', $attribute->name, $attribute->value);
		}
		$newElement = $element->ownerDocument->createDocumentFragment();
		$newElement->appendXML(sprintf('<div style="border:3px solid red; padding: 10px; font-family:Courier;"><h1>NO HANDLER FOR "%s"</h1><h2>Attributes</h2><dl>%s</dl></div>', $element->localName, $attrOut));
		return $newElement;
	}
	
	/**
	 * Determines and returns the name of the method which should
	 * handle the <code>DOMElement</code>. This method should be
	 * overwritten for the implementation of an own naming scheme.
	 *
	 * @return string Name of the method
	 */
	protected function getHandlerName(DOMElement $element) {
		$name = 'handle';
		if (strpos($element->localName, '_') !== false) {
			foreach(explode('_', $element->localName) as $token) {
				$name .= ucfirst($token);
			}
		} else {
			$name .= ucfirst($element->localName);
		}
		return $name;
	}
	
	/**
	 * Returns innerHTML of a DOMNode.
	 *
	 * @return string innerHTML
	 */
	protected function getInnerHtml(DOMNode $e) {
		$innerHTML= '';
		foreach ($e->childNodes as $child) {
			$innerHTML .= $child->ownerDocument->saveXML($child);
		}
		return $innerHTML;
	}
	
	/**
	 * Handles the given <code>$element</code> and excutes its
	 * handler if available.
	 *
	 * @param DOMElement $element Element to be handled
	 */
	public function handle(DOMElement $element) {
		$methodName = $this->getHandlerName($element);
		
		// Checks wether a handler exists or not
		if (!method_exists($this, $methodName)) {
			if ($this->useDefaultHandler) {
				// Redirect to default handler
				$methodName = 'handleDefault';
			} else {
				// There is no appropriate handler, so we'll return
				return false;
			}
		}

		// Call the handler
		$newElement = call_user_func(array($this, $methodName), $element);
		
		// If the handler didn't return a DOMNode we'll return false
		if (!($newElement instanceOf DOMNode)) {
			return false;
		} 
		
		// Replaces the element at the document and return the old one
		return $element->parentNode->replaceChild($newElement, $element);
	}
	
	/**
	 * Shortcut for creating document fragments.
	 *
	 * @param DOMElement $element 
	 */
	protected function createFragment(DOMElement $e) {
		return $e->ownerDocument->createDocumentFragment();
	}
}
