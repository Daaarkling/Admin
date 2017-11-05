<?php
declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Application\UI\Form;


interface IResetFormFactory
{

	/**
	 * @return Form
	 */
	function create(): Form;

}