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

class Markr {

	/**
	 * @var string
	 */
	protected static $path = '';

	/**
	 * Array of all classes of the Base package. It is used by the autoloader.
	 * @var array
	 */
	protected static $autoloads = array(
		'SW_Markr_Parser'         => 'Markr/Parser.class.php',
		'SW_Markr_ElementHandler' => 'Markr/ElementHandler.class.php',
		'SW_Markr_Exception'      => 'Markr/Exception.class.php'
	);

	/**
	 * This method serves as a class loader for all classes of the
	 * Base package.
	 * 
	 * @param <type> $className 
	 */
	public static function autoload($className) {
		if(isset(self::$autoloads[$className])) {
			require(self::$path . '/' . self::$autoloads[$className]);
		}
	}

	/**
	 * This methods bootstraps the Base class. Therefore it will register the
	 * autoloader and a default error handler.
	 *
	 * @return void
	 */
	public static function bootstrap() {
		self::$path = dirname(__FILE__);
		spl_autoload_register(array('Markr', 'autoload'));

		// Set default error handler so we'll get exceptions
		set_error_handler(array('Markr', 'handleError'), E_WARNING);
	}

	/**
	 * This methods is used as default error handler, which throws
	 * nice to handle exceptions.
	 *
	 * @return void
	 **/
	public static function handleError($code, $string, $file, $line, $context) {
		throw new SW_Markr_Exception($string, $code, $file, $line, $context);
	}

}
