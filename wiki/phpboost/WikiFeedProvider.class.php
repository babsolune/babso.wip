<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return WikiService::get_categories_manager()->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		if (WikiService::get_categories_manager()->get_categories_cache()->category_exists($idcat))
		{
			$querier = PersistenceContext::get_querier();

			$category = WikiService::get_categories_manager()->get_categories_cache()->get_category($idcat);

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$feed_module_name = LangLoader::get_message('wiki.feed_name', 'common', 'wiki');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('wiki', $idcat));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = WikiService::get_categories_manager()->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);

			$now = new Date();
			$results = $querier->select('SELECT wiki.id, wiki.id_category, wiki.title, wiki.rewrited_title, wiki.thumbnail_url,
			wiki.contents, wiki.description, wiki.date_created, cat.rewrited_name AS rewrited_name_cat
			FROM ' . WikiSetup::$wiki_table . ' wiki
			LEFT JOIN '. WikiSetup::$wiki_cats_table .' cat ON cat.id = wiki.id_category
			WHERE wiki.id_category IN :cats_ids
			AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
			ORDER BY wiki.date_created DESC',
			array(
				'cats_ids' => $ids_categories,
				'timestamp_now' => $now->get_timestamp()
			));

			foreach ($results as $row)
			{
				$row['rewrited_name_cat'] = !empty($row['id_category']) ? $row['rewrited_name_cat'] : 'root';
				$link = WikiUrlBuilder::display_item($row['id_category'], $row['rewrited_name_cat'], $row['id'], $row['rewrited_title']);
				$item = new FeedItem();
				$item->set_title($row['title']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse($row['contents']));
				$item->set_date(new Date($row['date_created'], Timezone::SERVER_TIMEZONE));
				$item->set_image_url($row['thumbnail_url']);
				$item->set_auth(WikiService::get_categories_manager()->get_heritated_authorizations($row['id_category'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				$data->add_item($item);
			}
			$results->dispose();

			return $data;
		}
	}
}
?>
