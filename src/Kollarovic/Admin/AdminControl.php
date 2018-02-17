<?php
declare(strict_types=1);

namespace Kollarovic\Admin;

use Kollarovic\Navigation\Item;
use Kollarovic\Navigation\IItemsFactory;
use Kollarovic\Navigation\NavigationControl;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;


class AdminControl extends Control
{
	/** @var array */
	public $onLoggedOut;

	/** @var array */
	public $onSearch;

	/** @var IItemsFactory */
	private $itemsFactory;

	/** @var ILoaderFactory */
	private $loaderFactory;

	/** @var User */
	private $user;

	/** @var string */
	private $pageTitle;

	/** @var string */
	private $skin;

	/** @var string */
	private $backLinkColor;
	
	/** @var string */
	private $logo;

	/** @var string */
	private $adminName;

	/** @var string */
	private $back;

	/** @var string */
	private $userName;

	/** @var string */
	private $userImage;

	/** @var string */
	private $pageName;

	/** @var string */
	private $content;

	/** @var string */
	private $header;

	/** @var string */
	private $footer;
	
	/** @var string */
	private $profile;
	
	/** @var string */
	private $signOut;
	
	/** @var string */
	private $search;

	/** @var string */
	private $navbar;

	/** @var string */
	private $navigationName;

	/** @var string */
	private $profileUrl;

	/** @var boolean */
	private $ajaxRequest = false;
	
	/** @var boolean */
	private $showSearch;
	
	/** @var array */
	private $layout;
	
	


	function __construct(IItemsFactory $itemsFactory, ILoaderFactory $loaderFactory, User $user)
	{
		parent::__construct();
		$this->itemsFactory = $itemsFactory;
		$this->loaderFactory = $loaderFactory;
		$this->user = $user;
	}


	public function render(array $options = [])
	{
		$this->template->setFile($this->getLayout()['templates']['admin']);
		$this->template->pageTitle = $this->pageTitle;
		$this->template->skin = $this->skin;
		$this->template->backLinkColor = $this->backLinkColor;
		$this->template->profileUrl = $this->profileUrl;
		$this->template->userName = $this->userName;
		$this->template->back = $this->back;
		$this->template->userImage = $this->userImage;
		$this->template->adminName = $this->adminName;
		$this->template->pageName = $this->pageName;
		$this->template->content = $this->content;
		$this->template->header = $this->header;
		$this->template->footer = $this->footer;
		$this->template->profile = $this->profile;
		$this->template->signOut = $this->signOut;
		$this->template->search = $this->search;
		$this->template->navbar = $this->navbar;
		$this->template->ajax = $this->ajaxRequest;
		$this->template->showSearch = $this->showSearch;
		$this->template->logo = $this->logo;
		$this->template->rootItem = $this->getRootItem();
		foreach ($options as $key => $value) {
			$this->template->$key = $value;
		}
		$this->template->render();
	}


	public function renderPanel(array $options = [])
	{
		$this['navigation']->renderPanel($options);
	}


	public function handleSignOut()
	{
		$this->user->logout();
		$this->onLoggedOut();
	}


	protected function createTemplate()
	{
		$template = parent::createTemplate();
		if (!array_key_exists('translate', $template->getLatte()->getFilters())) {
			$template->addFilter('translate', function($str){return $str;});
		}
		return $template;
	}


	protected function createComponentSearchForm(): Form
	{
		$form = new Form();
		$form->addText('key');
		$form->onSuccess[] = function($form) {
			$this->onSearch($form->values->key);
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


	protected function createComponentNavigation(): NavigationControl
	{
		return new NavigationControl($this->getRootItem(), $this->layout);
	}


	public function getRootItem(): Item
	{
		return $this->itemsFactory->create($this->navigationName);
	}

	/**
	 * @return IItemsFactory
	 */
	public function getItemsFactory(): IItemsFactory
	{
		return $this->itemsFactory;
	}

	/**
	 * @param IItemsFactory $itemsFactory
	 */
	public function setItemsFactory(IItemsFactory $itemsFactory)
	{
		$this->itemsFactory = $itemsFactory;
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
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
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
	public function getSkin(): string
	{
		return $this->skin;
	}

	/**
	 * @param string $skin
	 */
	public function setSkin(string $skin)
	{
		$this->skin = $skin;
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
	public function getAdminName(): string
	{
		return $this->adminName;
	}

	/**
	 * @param string $adminName
	 */
	public function setAdminName(string $adminName)
	{
		$this->adminName = $adminName;
	}

	/**
	 * @return string
	 */
	public function getUserName(): string
	{
		return $this->userName;
	}

	/**
	 * @param string $userName
	 */
	public function setUserName(string $userName)
	{
		$this->userName = $userName;
	}

	/**
	 * @return string
	 */
	public function getUserImage(): string
	{
		return $this->userImage;
	}

	/**
	 * @param string $userImage
	 */
	public function setUserImage(string $userImage)
	{
		$this->userImage = $userImage;
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
	public function getContent(): string
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent(string $content)
	{
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getHeader(): string
	{
		return $this->header;
	}

	/**
	 * @param string $header
	 */
	public function setHeader(string $header)
	{
		$this->header = $header;
	}

	/**
	 * @return string
	 */
	public function getFooter(): string
	{
		return $this->footer;
	}

	/**
	 * @param string $footer
	 */
	public function setFooter(string $footer)
	{
		$this->footer = $footer;
	}

	/**
	 * @return string
	 */
	public function getProfile(): string
	{
		return $this->profile;
	}

	/**
	 * @param string $profile
	 */
	public function setProfile(string $profile)
	{
		$this->profile = $profile;
	}

	/**
	 * @return string
	 */
	public function getSignOut(): string
	{
		return $this->signOut;
	}

	/**
	 * @param string $signOut
	 */
	public function setSignOut(string $signOut)
	{
		$this->signOut = $signOut;
	}

	/**
	 * @return string
	 */
	public function getSearch(): string
	{
		return $this->search;
	}

	/**
	 * @param string $search
	 */
	public function setSearch(string $search)
	{
		$this->search = $search;
	}

	/**
	 * @return string
	 */
	public function getNavbar(): string
	{
		return $this->navbar;
	}

	/**
	 * @param string $navbar
	 */
	public function setNavbar(string $navbar)
	{
		$this->navbar = $navbar;
	}

	/**
	 * @return string
	 */
	public function getNavigationName(): string
	{
		return $this->navigationName;
	}

	/**
	 * @param string $navigationName
	 */
	public function setNavigationName(string $navigationName)
	{
		$this->navigationName = $navigationName;
	}

	/**
	 * @return string
	 */
	public function getProfileUrl(): string
	{
		return $this->profileUrl;
	}

	/**
	 * @param string $profileUrl
	 */
	public function setProfileUrl(string $profileUrl)
	{
		$this->profileUrl = $profileUrl;
	}

	/**
	 * @return bool
	 */
	public function isAjaxRequest(): bool
	{
		return $this->ajaxRequest;
	}

	/**
	 * @param bool $ajaxRequest
	 */
	public function setAjaxRequest(bool $ajaxRequest)
	{
		$this->ajaxRequest = $ajaxRequest;
	}

	/**
	 * @return bool
	 */
	public function isShowSearch(): bool
	{
		return $this->showSearch;
	}

	/**
	 * @param bool $showSearch
	 */
	public function setShowSearch(bool $showSearch)
	{
		$this->showSearch = $showSearch;
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
	 * @return string
	 */
	public function getBack(): string
	{
		return $this->back;
	}

	/**
	 * @param string $back
	 */
	public function setBack(string $back)
	{
		$this->back = $back;
	}

	/**
	 * @return string
	 */
	public function getBackLinkColor(): string
	{
		return $this->backLinkColor;
	}

	/**
	 * @param string $backLinkColor
	 */
	public function setBackLinkColor(string $backLinkColor)
	{
		$this->backLinkColor = $backLinkColor;
	}
}
