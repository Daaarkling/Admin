<?php
declare(strict_types=1);

namespace Kollarovic\Admin;


interface IAdminControlFactory
{

	/**
	 * @return AdminControl
	 */
	function create(): AdminControl;

}