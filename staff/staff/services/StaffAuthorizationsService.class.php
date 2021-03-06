<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
*/

class StaffAuthorizationsService
{
	public $id_category;

	public static function check_authorizations($id_category = Category::ROOT_CATEGORY)
	{
		$instance = new self();
		$instance->id_category = $id_category;
		return $instance;
	}

	public function read()
	{
		return $this->is_authorized(Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY);
	}

	public function write()
	{
		return $this->is_authorized(Category::WRITE_AUTHORIZATIONS);
	}

	public function contribution()
	{
		return $this->is_authorized(Category::CONTRIBUTION_AUTHORIZATIONS);
	}

	public function moderation()
	{
		return $this->is_authorized(Category::MODERATION_AUTHORIZATIONS);
	}

	public function manage_categories()
	{
		return $this->is_authorized(Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS);
	}

	private function is_authorized($bit, $mode = Authorizations::AUTH_CHILD_PRIORITY)
	{
		$auth = StaffService::get_categories_manager()->get_heritated_authorizations($this->id_category, $bit, $mode);
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
