<?php
/*##################################################
 *                        RadioCategoriesCache.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class RadioCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return RadioSetup::$radio_cats_table;
	}
	
	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}
	
	public function get_module_identifier()
	{
		return 'radio';
	}
	
	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(RadioConfig::load()->get_authorizations());
		$root->set_description(
			StringVars::replace_vars(LangLoader::get_message('radio.seo.description.root', 'common', 'radio'), 
			array('site' => GeneralConfig::load()->get_site_name()
		)));
		return $root;
	}
}
?>