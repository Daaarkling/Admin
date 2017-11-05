<?php
declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;
use Nette\Forms\Form;
use Nette\Localization\ITranslator;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;


class LoginControl extends Control
{
	/** @var array */
	public $onLoggedIn;
	
	/** @var array */
	public $onResetPassword;

	/** @var ILoginFormFactory */
	private $loginFormFactory;
	
	/** @var IResetFormFactory */
	private $resetFormFactory;

	/** @var ILoaderFactory */
	private $loaderFactory;

	/** @var string */
	private $pageTitle;

	/** @var string */
	private $pageName;

	/** @var string */
	private $pageMsg;

	/** @var string */
	private $usernameIcon;

	/** @var string */
	private $passwordIcon;
	
	/** @var string */
	private $forgotPass;
	
	/** @var string */
	private $logo;
	
	/** @var string */
	private $bg;
	
	/** @var string */
	private $resetPassMsg;
	
	/** @var array */
	private $layout;
	
	/** @var ITranslator */
	private $translator;


	function __construct(ILoginFormFactory $loginFormFactory, IResetFormFactory $resetFormFactory, ILoaderFactory $loaderFactory, ITranslator $translator = null)
	{
		parent::__construct();
		$this->loginFormFactory = $loginFormFactory;
		$this->resetFormFactory = $resetFormFactory;
		$this->loaderFactory = $loaderFactory;
		$this->translator = $translator;
	}


	public function render(array $options = [])
	{
		$this->template->setFile($this->layout['templates']['login']);
		$this->template->pageTitle = $this->pageTitle;
		$this->template->pageName = $this->pageName;
		$this->template->pageMsg = $this->pageMsg;
		$this->template->usernameIcon = $this->usernameIcon;
		$this->template->passwordIcon = $this->passwordIcon;
		$this->template->forgotPass = $this->forgotPass;
		$this->template->logo = $this->logo;
		$this->template->bg = $this->bg;
		foreach ($options as $key => $value) {
			$this->template->$key = $value;
		}
		$this->template->render();
	}


	protected function createTemplate()
	{
		$template = parent::createTemplate();
		if (!array_key_exists('translate', $template->getLatte()->getFilters())) {
			$template->addFilter('translate', function($str){return $str;});
		}
		return $template;
	}


	protected function createComponentForm(): Form
	{
		$form = $this->loginFormFactory->create();
		$form->onSuccess[] = function($form) {
			$this->onLoggedIn($form);
		};
		return $form;
	}
	
	
	protected function createComponentFormReset(): Form
	{
		$form = $this->resetFormFactory->create();
		$form->onSuccess[] = function($form) {
			$this->onResetPassword($form);
		};
		return $form;
	}


	protected function createComponentCss(): CssLoader
	{
		return $this->loaderFactory->createCssLoader();
	}


	protected function createComponentJs(): JavaScriptLoader
	{
		return $this->loaderFactory->createJavaScriptLoader();
	}

	/**
	 * @return ILoginFormFactory
	 */
	public function getLoginFormFactory(): ILoginFormFactory
	{
		return $this->loginFormFactory;
	}

	/**
	 * @param ILoginFormFactory $loginFormFactory
	 */
	public function setLoginFormFactory(ILoginFormFactory $loginFormFactory)
	{
		$this->loginFormFactory = $loginFormFactory;
	}

	/**
	 * @return IResetFormFactory
	 */
	public function getResetFormFactory(): IResetFormFactory
	{
		return $this->resetFormFactory;
	}

	/**
	 * @param IResetFormFactory $resetFormFactory
	 */
	public function setResetFormFactory(IResetFormFactory $resetFormFactory)
	{
		$this->resetFormFactory = $resetFormFactory;
	}

	/**
	 * @return ILoaderFactory
	 */
	public function getLoaderFactory(): ILoaderFactory
	{
		return $this->loaderFactory;
	}

	/**
	 * @param ILoaderFactory $loaderFactory
	 */
	public function setLoaderFactory(ILoaderFactory $loaderFactory)
	{
		$this->loaderFactory = $loaderFactory;
	}

	/**
	 * @return string
	 */
	public function getPageTitle(): string
	{
		return $this->pageTitle;
	}

	/**
	 * @param string $pageTitle
	 */
	public function setPageTitle(string $pageTitle)
	{
		$this->pageTitle = $pageTitle;
	}

	/**
	 * @return string
	 */
	public function getPageName(): string
	{
		return $this->pageName;
	}

	/**
	 * @param string $pageName
	 */
	public function setPageName(string $pageName)
	{
		$this->pageName = $pageName;
	}

	/**
	 * @return string
	 */
	public function getPageMsg(): string
	{
		return $this->pageMsg;
	}

	/**
	 * @param string $pageMsg
	 */
	public function setPageMsg(string $pageMsg)
	{
		$this->pageMsg = $pageMsg;
	}

	/**
	 * @return string
	 */
	public function getUsernameIcon(): string
	{
		return $this->usernameIcon;
	}

	/**
	 * @param string $usernameIcon
	 */
	public function setUsernameIcon(string $usernameIcon)
	{
		$this->usernameIcon = $usernameIcon;
	}

	/**
	 * @return string
	 */
	public function getPasswordIcon(): string
	{
		return $this->passwordIcon;
	}

	/**
	 * @param string $passwordIcon
	 */
	public function setPasswordIcon(string $passwordIcon)
	{
		$this->passwordIcon = $passwordIcon;
	}

	/**
	 * @return string
	 */
	public function getForgotPass(): string
	{
		return $this->forgotPass;
	}

	/**
	 * @param string $forgotPass
	 */
	public function setForgotPass(string $forgotPass)
	{
		$this->forgotPass = $forgotPass;
	}

	/**
	 * @return string
	 */
	public function getLogo(): string
	{
		return $this->logo;
	}

	/**
	 * @param string $logo
	 */
	public function setLogo(string $logo)
	{
		$this->logo = $logo;
	}

	/**
	 * @return string
	 */
	public function getBg(): string
	{
		return $this->bg;
	}

	/**
	 * @param string $bg
	 */
	public function setBg(string $bg)
	{
		$this->bg = $bg;
	}

	/**
	 * @return string
	 */
	public function getResetPassMsg(): string
	{
		return $this->resetPassMsg;
	}

	/**
	 * @param string $resetPassMsg
	 */
	public function setResetPassMsg(string $resetPassMsg)
	{
		$this->resetPassMsg = $resetPassMsg;
	}

	/**
	 * @return array
	 */
	public function getLayout(): array
	{
		return $this->layout;
	}

	/**
	 * @param array $layout
	 */
	public function setLayout(array $layout)
	{
		$this->layout = $layout;
	}

	/**
	 * @return ITranslator
	 */
	public function getTranslator(): ITranslator
	{
		return $this->translator;
	}

	/**
	 * @param ITranslator $translator
	 */
	public function setTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
	}
}