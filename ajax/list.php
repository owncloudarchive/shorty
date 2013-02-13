<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2013 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401 
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty
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
 * @file ajax/list.php
 * @brief Ajax method to retrieve a list of existing shortys
 * @return json: success/error state indicator
 * @return number: Total number of shortys in the list
 * @return json: Numeric array of all shortys, associative array of attributes as values for every single shorty contained
 * @author Christian Reiner
 */

// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
OC_Shorty_Tools::ob_control ( TRUE );

//no apps or filesystem
$RUNTIME_NOSETUPFS = TRUE;

// Sanity checks
OCP\JSON::callCheck ( );
OCP\JSON::checkLoggedIn ( );
OCP\JSON::checkAppEnabled ( 'shorty' );

try
{
	// first remove any entries already marked as 'deleted'
	$query = OCP\DB::prepare ( OC_Shorty_Query::URL_REMOVE );
	$result = $query->execute(array(':user'=>OCP\User::getUser()));
	// now comes the real list selection
//   define ('PAGE_SIZE', 100);
//   $p_offset = OC_Shorty_Type::req_argument ( 'page', OC_Shorty_Type::INTEGER, FALSE) * PAGE_SIZE;
	// pre-sort list according to user preferences
	$p_sort = OC_Shorty_Type::$SORTING[OCP\Config::getUserValue(OCP\User::getUser(),'shorty','list-sort-code','cd')];
	$param = array (
		':user'   => OCP\User::getUser ( ),
		':sort'   => $p_sort,
// 		':offset' => $p_offset,
// 		':limit'  => PAGE_SIZE,
	);
	$query = OCP\DB::prepare ( OC_Shorty_Query::URL_LIST );
	$result = $query->execute($param);
	$reply = $result->fetchAll();
	foreach (array_keys($reply) as $key) {
		if (isset($reply[$key]['id']))
		{
			// enhance all entries with the relay url
			$reply[$key]['relay']=OC_Shorty_Tools::relayUrl ( $reply[$key]['id'] );
			// make sure there is _any_ favicon contained, otherwise layout in MS-IE browser is broken...
			if (empty($reply[$key]['favicon']))
				$reply[$key]['favicon'] = OCP\Util::imagePath('shorty', 'blank.png');
		}
	} // foreach

	// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
	OC_Shorty_Tools::ob_control ( FALSE );
	OCP\Util::writeLog( 'shorty', sprintf("Constructed list of defined shortys holding %s entries.",sizeof($reply)), OC_Log::DEBUG );
	OCP\JSON::success ( array ( 'data'    => $reply,
								'count'   => sizeof($reply),
								'message' => OC_Shorty_L10n::t('Number of entries: %s', count($reply)) ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
