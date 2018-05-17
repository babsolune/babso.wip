<?php
/*##################################################
 *                   TsmDivisionsAuthService.class.php
 *                            -------------------
 *   begin                : February 13, 2018
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

class TsmDivisionsAuthService
{
	const READ_SEASON_AUTH = 1;
	const WRITE_SEASON_AUTH = 2;
	const CONTRIBUTION_SEASON_AUTH = 4;
	const MODERATION_SEASON_AUTH = 8;

	public static function check_division_auth()
	{
		$instance = new self();
		return $instance;
	}

	public function read_division()
	{
		return $this->is_authorized(self::READ_SEASON_AUTH);
	}

	public function write_division()
	{
		return $this->is_authorized(self::WRITE_SEASON_AUTH);
	}

	public function contribution_division()
	{
		return $this->is_authorized(self::CONTRIBUTION_SEASON_AUTH);
	}

	public function moderation_division()
	{
		return $this->is_authorized(self::MODERATION_SEASON_AUTH);
	}

	private function is_authorized($bit)
	{
		$auth = TsmConfig::load()->get_division_auth();
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}

}
?>