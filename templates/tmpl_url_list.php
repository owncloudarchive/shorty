<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information 
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
?>

<?php
/**
 * @file templates/tmpl_url_list.php
 * A table to visualize the list of existing shortys.
 * @access public
 * @author Christian Reiner
 */
?>

<div id="hourglass" class="shorty-hourglass"><img src="<?php echo OC_Helper::imagePath('shorty', 'loading-disk.gif'); ?>"></div>
<div id="emptylist" class="shorty-emptylist"><span class="shorty-label"><?php echo OC_Shorty_L10n::t('List currently empty.') ?></span></div>

<!-- the list of urls -->
<table id="list" class="shorty-list" style="display:none;">
  <thead>
    <tr id="titlebar">
      <!-- a button to open/close the toolbar below -->
      <th id="headerFavicon"><img id="tools" alt="toolbar" title="toggle toolbar"
                                  src="<?php echo OC_Helper::imagePath('shorty','actions/plus.png'); ?>"
                                  data-plus="<?php echo OC_Helper::imagePath('shorty','actions/plus.png'); ?>"
                                  data-minus="<?php echo OC_Helper::imagePath('shorty','actions/minus.png'); ?>"></th>
      <th id="headerTitle"  ><?php echo OC_Shorty_L10n::t('Title') ?></th>
      <th id="headerTarget" ><?php echo OC_Shorty_L10n::t('Target') ?></th>
      <th id="headerClicks" ><?php echo OC_Shorty_L10n::t('Clicks') ?></th>
      <th id="headerUntil"  ><?php echo OC_Shorty_L10n::t('Until') ?></th>
      <th id="headerAction" ><?php echo OC_Shorty_L10n::t('Actions') ?></th>
    </tr>
    <!-- toolbar opened/closed by the button above -->
    <tr id="toolbar">
      <th id="headerFavicon">
        <div style="display:none;">
          <a id="reload"><img alt="<?php echo $l->t('reload'); ?>" title="<?php echo $l->t('reload list'); ?>" src="<?php echo OC_Helper::imagePath('shorty','actions/reload.png'); ?>"></a>
        </div>
      </th>
      <th id="headerTitle">
        <div style="display:none;">
          <a id="sort-up"   class="shorty-sorter" data-sort-code="ta" data-sort-type="string">
            <img alt="<?php echo $l->t('up');   ?>" title="<?php echo $l->t('sort ascending');  ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/up.png');   ?>">
          </a>
          <a id="sort-down" class="shorty-sorter" data-sort-code="td" data-sort-type="string">
            <img alt="<?php echo $l->t('down'); ?>" title="<?php echo $l->t('sort descending'); ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/down.png'); ?>">
            </a>
          <input id='filter' type="text" value="">
        </div>
      </th>
      <th id="headerTarget">
        <div style="display:none;">
          <a id="sort-up"   class="shorty-sorter" data-sort-code="ua" data-sort-type="string">
            <img alt="<?php echo $l->t('up');   ?>" title="<?php echo $l->t('sort ascending');  ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/up.png');   ?>">
          </a>
          <a id="sort-down" class="shorty-sorter" data-sort-code="ua" data-sort-type="string">
            <img alt="<?php echo $l->t('down'); ?>" title="<?php echo $l->t('sort descending'); ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/down.png'); ?>">
          </a>
          <input id='filter' type="text" value="">
        </div>
      </th>
      <th id="headerClicks">
        <div style="display:none;">
          <a id="sort-up"   class="shorty-sorter" data-sort-code="ha" data-sort-type="int">
            <img alt="<?php echo $l->t('up');   ?>" title="<?php echo $l->t('sort ascending');  ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/up.png');   ?>">
          </a>
          <a id="sort-down" class="shorty-sorter" data-sort-code="hd" data-sort-type="int">
            <img alt="<?php echo $l->t('down'); ?>" title="<?php echo $l->t('sort descending'); ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/down.png'); ?>">
          </a>
        </div>
      </th>
      <th id="headerUntil">
        <div style="display:none;">
          <a id="sort-up"   class="shorty-sorter" data-sort-code="da" data-sort-type="date">
            <img alt="<?php echo $l->t('up');   ?>" title="<?php echo $l->t('sort ascending');  ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/up.png');   ?>">
          </a>
          <a id="sort-down" class="shorty-sorter" data-sort-code="dd" data-sort-type="date">
            <img alt="<?php echo $l->t('down'); ?>" title="<?php echo $l->t('sort descending'); ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/down.png'); ?>">
          </a>
        </div>
      </th>
      <th id="headerAction">
        <div style="display:none;">
          &nbsp;
        </div>
      </th>
    </tr>
  </thead>
  <!-- the standard body for non-empty lists -->
  <tbody>
    <!-- the 'dummy' row, a blueprint -->
    <tr id=""
        data-key=""
        data-source=""
        data-title=""
        data-favicon=""
        data-target=""
        data-clicks=""
        data-until=""
        data-created=""
        data-accessed=""
        data-notes=""
        style="display:hidden;" >
      <td id="favicon"></td>
      <td id="title"  width="200px"></td>
      <td id="target" width="200px"></td>
      <td id="clicks" ></td>
      <td id="until"  ></td>
      <td id="actions">
        <span class="shorty-actions">
          <a href="" title="Open"   class=""><img class="svg" alt="Download" title="Open target url" src="/owncloud/core/img/actions/download.svg" /></a>
          <a href="" title="Share"  class=""><img class="svg" alt="Share"    title="Share shorty"    src="/owncloud/core/img/actions/share.svg"    /></a>
          <a href="" title="Edit"   class=""><img class="svg" alt="Edit"     title="Edit shorty"     src="/owncloud/core/img/actions/edit.svg"     /></a>
          <a href="" title="Delete" class=""><img class="svg" alt="Delete"   title="Delete shorty"   src="/owncloud/core/img/actions/delete.svg"   /></a>
        </span>
      </td>
    </tr>
  </tbody>
</table>
