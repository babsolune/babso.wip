<?php
/*##################################################
 *                          AgendaModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : November 22, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AgendaModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-agenda';
	}
	
	public function get_menu_title()
	{
		return LangLoader::get_message('module_title', 'common', 'agenda');
	}
	
	public function is_displayed()
	{
		return !Url::is_current_url('/agenda/') && AgendaAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		$tpl = new FileTemplate('agenda/AgendaModuleMiniMenu.tpl');
		$tpl->add_lang(LangLoader::get('common', 'agenda'));
		
		$tpl->put('AGENDA', AgendaAjaxAgendaController::get_view(true));
		
		return $tpl->render();
	}
}
?>
