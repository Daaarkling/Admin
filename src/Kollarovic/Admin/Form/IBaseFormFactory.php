<?php
declare(strict_types=1);

namespace Kollarovic\Admin\Form;

use Nette\Application\UI\Form;
use Nette\Forms\IFormRenderer;
use Nette\Localization\ITranslator;


interface IBaseFormFactory
{
	public function create(): Form;

	public function getFormRender(): IFormRenderer;

	public function getTranslator(): ITranslator;
}
