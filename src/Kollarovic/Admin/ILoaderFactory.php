<?php
declare(strict_types=1);

namespace Kollarovic\Admin;

use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;


interface ILoaderFactory
{

	/**
	 * @return CssLoader
	 */
	function createCssLoader(): CssLoader;


	/**
	 * @return JavaScriptLoader
	 */
	function createJavaScriptLoader(): JavaScriptLoader;

}