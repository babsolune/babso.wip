<?php
/*##################################################
 *                        WikiService.class.php
 *                            -------------------
 *   begin                : May 25, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
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

class WikiService
{
	private static $db_querier;
	private static $categories_manager;
	private static $keywords_manager;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(WikiSetup::$wiki_table, $condition, $parameters);
	}

	public static function add(Document $document)
	{
		$result = self::$db_querier->insert(WikiSetup::$wiki_table, $document->get_properties());
		return $result->get_last_inserted_id();
	}

	public static function update(Document $document)
	{
		self::$db_querier->update(WikiSetup::$wiki_table, $document->get_properties(), 'WHERE id=:id', array('id', $document->get_id()));
	}

	 /**
	 * @desc Update the position of a document.
	 * @param string[] $document_id : id of the document to update
	 * @param string[] $position : new document position
	 */
	public static function update_position($document_id, $position)
	{
		self::$db_querier->update(WikiSetup::$wiki_table, array('order_id' => $position), 'WHERE id=:id', array('id' => $document_id));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(WikiSetup::$wiki_table, $condition, $parameters);
	}

	public static function get_document($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT wiki.*, member.*
		FROM ' . WikiSetup::$wiki_table . ' wiki
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = wiki.author_user_id
		' . $condition, $parameters);

		$document = new Document();
		$document->set_properties($row);
		return $document;
	}

	public static function update_number_view(Document $document)
	{
		self::$db_querier->update(WikiSetup::$wiki_table, array('number_view' => $document->get_number_view()), 'WHERE id=:id', array('id' => $document->get_id()));
	}

	public static function get_authorized_categories($current_id_category)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		if (AppContext::get_current_user()->is_guest())
			$search_category_children_options->set_allow_only_member_level_authorizations(WikiConfig::load()->are_descriptions_displayed_to_guests());

		$categories = self::get_categories_manager()->get_children($current_id_category, $search_category_children_options, true);
		return array_keys($categories);
	}

	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(WikiSetup::$wiki_table);
			self::$categories_manager = new CategoriesManager(WikiCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}

	public static function get_keywords_manager()
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager(WikiKeywordsCache::load());
		}
		return self::$keywords_manager;
	}
}
?>
