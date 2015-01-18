<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2014 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the license, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.
* If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * @file lib/exception.php
 * Application specific exception class
 * @author Christian Reiner
 */

/**
 * @class OC_Shorty_Exception
 * @brief Application specific exception class
 * @access public
 * @author Christian Reiner
 */
class OC_Shorty_Exception extends Exception
{
	protected $phrase = '';
	protected $param  = array ( );

	/**
	* @method OC_Shorty_Exception::__construct
	* @brief: Constructs an exception based on a phrase and a set of parameters
	* @param string phrase: Human readable message that should be translatable
	* @param array param: Set of parameters to be used as sprintf arguments to fill the phrase
	* @access public
	* @author Christian Reiner
	*/
	public function __construct ( $phrase, $param=array() )
	{
		if ( is_array($param) )
			$this->param = $param;
		else $this->param = array($param);
		$this->phrase  = $phrase;
		$message = vsprintf ( $phrase, $this->param );
		OCP\Util::writeLog( 'shorty', $message, OCP\Util::ERROR );
		Exception::__construct ( $message, 1 );
	}

	/**
	* @method OC_Shorty_Exception::getTranslation
	* @brief: Returns the translated message of the exception
	* @return string: Translated message including the filled in set of arguments
	* @access public
	* @author Christian Reiner
	*/
	public function getTranslation ( )
	{
		return OC_Shorty_L10n::t ( $this->phrase, $this->param );
	}

	/**
	* @method OC_Shorty_Exception::JSONerror
	* @brief Calls OCP\JSON::error with a pretty formated version of an exception
	* @param exception: An exception object holding information
	* @return json: OCP\JSON::error
	* @access public
	* @author Christian Reiner
	*/
	static function JSONerror ( $e )
	{
		$title = OC_Shorty_L10n::t("Exception");
		switch ( get_class($e) )
		{
			case 'OC_Shorty_Exception':
				$message = $e->getTranslation();
				break;

			case 'PDOException':
				$message = sprintf ( OC_Shorty_L10n::t( "%s\nMessage(code): %s (%s)\nFile(line): %s (%s)\nInfo: %%s",
														OC_Shorty_L10n::t("Exception (%s)", get_class($e)),
														htmlspecialchars($e->getMessage()),
														htmlspecialchars($e->getCode()),
														htmlspecialchars($e->getFile()),
														htmlspecialchars($e->getLine()) ),
									(method_exists($e,'errorInfo') ? trim($e->errorInfo()) : '-/-') );
				break;

			default:
				if ( is_a($e,'Exception') )
					$message = OC_Shorty_L10n::t("Unexpected type of exception caught:%s\n%s", get_class($e), $e->getMessage());
				else $message = OC_Shorty_L10n::t("Unexpected thrown object of type caught:\n%s", get_class($e));
		} // switch
		// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
		$output = trim ( OC_Shorty_Tools::ob_control(FALSE) );
		if ( $output )
		{
			$message = "! Swallowing accidential output from ajax routines ! \n"
						."Please fix this ! Here is the first line: \n"
						.substr ( $output, 0, strpos($output,"\n") );
			OCP\Util::writeLog( 'shorty', $message, OCP\Util::WARN );
		} // output
		// return a clean JSON error
		return OCP\JSON::error ( array ('title'   => $title,
										'level'   => 'error',
										'message' => sprintf("%s:\n%s", $title, $message) ) );
	} // function error
} // class OC_Shorty_Exception

/**
 * @class OC_Shorty_HttpException
 * @brief Application specific exception class: protocol layer
 * @access public
 * @author Christian Reiner
 */
class OC_Shorty_HttpException extends OC_Shorty_Exception
{

	/**
	* @method OC_Shorty_HttpException::__construct
	* @brief: Constructs an exception based on a phrase and a set of parameters
	* @param integer status: Http status code
	* @access public
	* @author Christian Reiner
	*/
	public function __construct ( $status )
	{
		if (   is_numeric($status)
			&& array_key_exists($status,OC_Shorty_Type::$HTTPCODE) )
		{
			$status = intval($status);
			$phrase = OC_Shorty_Type::$HTTPCODE[$status];
		}
		else
		{
			$status = 400;
			$phrase = OC_Shorty_Type::$HTTPCODE[400]; // "Bad Request"
		} // else

		// return http status code to client (browser)
		if ( ! headers_sent() )
		{
			header ( sprintf("HTTP/1.0 %s %s",$status,$phrase) );
		}
		$tmpl = new OCP\Template("shorty", "tmpl_http_status", "guest");
		$tmpl->assign("explanation", OC_Shorty_L10n::t($phrase));
		$tmpl->printPage();
		exit;
  } // function __construct

} // class OC_Shorty_HttpException
?>
