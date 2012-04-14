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
<div id="vacuum" class="shorty-vacuum"><span class="shorty-label"><?php echo OC_Shorty_L10n::t('List currently empty.') ?></span></div>

<!-- the list of urls -->
<table id="list" class="shorty-list" style="display:none;">
  <thead>
    <tr id="titlebar">
      <!-- a button to open/close the toolbar below -->
      <th id="favicon"><img id="tools" alt="toolbar" title="toggle toolbar"
                            src="<?php echo OC_Helper::imagePath('shorty','actions/plus.png'); ?>"
                            data-plus="<?php echo OC_Helper::imagePath('shorty','actions/plus.png'); ?>"
                            data-minus="<?php echo OC_Helper::imagePath('shorty','actions/minus.png'); ?>"></th>
      <th id="title"  ><?php echo OC_Shorty_L10n::t('Title') ?></th>
      <th id="target" ><?php echo OC_Shorty_L10n::t('Target') ?></th>
      <th id="clicks" ><?php echo OC_Shorty_L10n::t('Clicks') ?></th>
      <th id="until"  ><?php echo OC_Shorty_L10n::t('Until') ?></th>
      <th id="action" ><?php echo OC_Shorty_L10n::t('Actions') ?></th>
    </tr>
    <!-- toolbar opened/closed by the button above -->
    <tr id="toolbar">
      <th id="favicon">
        <div style="display:none;">
          <a id="reload"><img alt="<?php echo $l->t('reload'); ?>" title="<?php echo $l->t('reload list'); ?>" src="<?php echo OC_Helper::imagePath('shorty','actions/reload.png'); ?>"></a>
        </div>
      </th>
      <th id="title">
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
      <th id="target">
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
      <th id="clicks">
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
      <th id="until">
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
      <th id="action">
        <div style="display:none;">
          &nbsp;
        </div>
      </th>
    </tr>
    <!-- the 'dummy' row, a blueprint -->
    <tr id=""
        data-key=""
        data-status=""
        data-source=""
        data-title=""
        data-favicon=""
        data-target=""
        data-clicks=""
        data-until=""
        data-created=""
        data-accessed=""
        data-notes="">
      <td id="favicon"></td>
      <td id="title"  width="200px"></td>
      <td id="target" width="200px"></td>
      <td id="clicks" ></td>
      <td id="until"  ></td>
      <td id="actions">
        <span class="shorty-actions">
          <a id="open"   title="<?php echo $l->t('open');   ?>"   class="">
            <img class="shorty-icon" alt="<?php echo $l->t('open'); ?>"   title="<?php echo $l->t('open target'); ?>"
                 src="<?php echo OC_Helper::imagePath('shorty','actions/open.png'); ?>" />
          </a>
          <a id="show"   title="<?php echo $l->t('show');   ?>"   class="">
            <img class="shorty-icon" alt="<?php echo $l->t('show'); ?>"   title="<?php echo $l->t('show shorty'); ?>"
                 src="<?php echo OC_Helper::imagePath('core','actions/info.png');   ?>" />
          </a>
          <a id="share"  title="<?php echo $l->t('share');  ?>"   class="">
            <img class="shorty-icon" alt="<?php echo $l->t('share'); ?>"  title="<?php echo $l->t('share shorty'); ?>"
                 src="<?php echo OC_Helper::imagePath('core','actions/share.png');  ?>" />
          </a>
          <a id="edit"   title="<?php echo $l->t('edit');   ?>"   class="">
            <img class="shorty-icon" alt="<?php echo $l->t('modify'); ?>"   title="<?php echo $l->t('modify shorty'); ?>"
                 src="<?php echo OC_Helper::imagePath('core','actions/rename.png'); ?>" />
          </a>
          <a id="delete" title="<?php echo $l->t('delete'); ?>" class="">
            <img class="shorty-icon" alt="<?php echo $l->t('delete'); ?>" title="<?php echo $l->t('delete shorty'); ?>"
                 src="<?php echo OC_Helper::imagePath('core','actions/delete.png'); ?>" />
          </a>
        </span>
      </td>
    </tr>
  </thead>
  <!-- the standard body for non-empty lists -->
  <tbody>
  </tbody>
</table>
