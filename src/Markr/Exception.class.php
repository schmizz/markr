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
 * @see http://snippets.dzone.com/posts/show/1617
 */ 
class SW_Markr_Exception extends Exception {
	/**
	 * The PHP Error Context
	 *
	 * The fifth parameter is optional, errcontext, which is an array that points 
	 * to the active symbol table at the point the error occurred. In other words, 
	 * errcontext  will contain an array of every variable that existed in the scope 
	 * the error was triggered in. User error handler must not modify error context.
	 */
	protected $context;

	public function __construct($message, $code, $file, $line, $context = null) {
		parent::__construct($message, $code);
		
		$this->file = $file;
		$this->line = $line;
		
		$this->context = $context;
	}
}

?>