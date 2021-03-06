<?php
declare(strict_types=1);

namespace Kollarovic\Admin;


interface ILoginControlFactory
{

	/**
	 * @return LoginControl
	 */
	function create(): LoginControl;

}