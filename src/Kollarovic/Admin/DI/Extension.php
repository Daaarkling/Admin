<?php
declare(strict_types=1);

namespace Kollarovic\Admin\DI;

use Nette;

class Extension extends Nette\DI\CompilerExtension {

	private function getDefaultConfig() {

		return [
			'wwwDir' => '%wwwDir%',
			'name' => 'Admin',
			'logo' => null,
			'skin' => 'red',
			'backLinkColor' => 'danger',
			'footer' => '',
			'profile' => 'Profile',
			'signout' => 'Sign Out',
			'search' => 'Search',
			'back' => 'Back',
			'ajax' => false,
			'showSearch' => true,
			'layout' => 'AdminLTE',
			'files' => [],
			'navigation' => 'admin',
			'login' => [
				'pageTitle' => 'Login - Admin',
				'pageName' => 'Admin',
				'pageMsg' => 'Authentication',
				'usernameIcon' => 'envelope',
				'passwordIcon' => 'lock',
				'forgotPass' => 'Forgot Password?',
				'logo' => '',
				'bg' => '',
				'resetPassMsg' => 'On your email was send another instruction.',
			],
		];
	}

	public function loadConfiguration() {
		$config = $this->getConfig($this->getDefaultConfig());
		$builder = $this->getContainerBuilder();

		$loaderFactory = $builder->addDefinition($this->prefix('loaderFactory'))
				->setFactory('Kollarovic\Admin\LoaderFactory', ['wwwDir' => $config['wwwDir']]);

		foreach (array_merge($this->getCoreFiles(), $this->getLayout($config['layout'])['files'], $config['files']) as $file) {
			$loaderFactory->addSetup('addFile', [$file]);
		}

		$builder->addDefinition($this->prefix('formRender'))
				->setFactory('Tomaj\Form\Renderer\BootstrapRenderer')
				->setAutowired(false);
		
		$builder->addDefinition($this->prefix('baseFormFactory'))
				->setFactory('Kollarovic\Admin\Form\BaseFormFactory', [
					'formRender' => $this->prefix('@formRender')
		]);

		$builder->addDefinition($this->prefix('loginFormFactory'))
				->setFactory('Kollarovic\Admin\LoginFormFactory');
		
		$builder->addDefinition($this->prefix('resetFormFactory'))
				->setFactory('Kollarovic\Admin\ResetFormFactory');

		$builder->addDefinition($this->prefix('loginControlFactory'))
				->setImplement('Kollarovic\Admin\ILoginControlFactory')
				->addSetup('setPageTitle', [$config['login']['pageTitle']])
				->addSetup('setPageName', [$config['login']['pageName']])
				->addSetup('setPageMsg', [$config['login']['pageMsg']])
				->addSetup('setUsernameIcon', [$config['login']['usernameIcon']])
				->addSetup('setLayout', [$this->getLayout($config['layout'])])
				->addSetup('setForgotPass', [$config['login']['forgotPass']])
				->addSetup('setResetPassMsg', [$config['login']['resetPassMsg']])
				->addSetup('setLogo', [$config['login']['logo']])
				->addSetup('setBg', [$config['login']['bg']])
				->addSetup('setPasswordIcon', [$config['login']['passwordIcon']]);

		$builder->addDefinition($this->prefix('adminControlFactory'))
				->setImplement('Kollarovic\Admin\IAdminControlFactory')
				->addSetup('setSkin', [$config['skin']])
				->addSetup('setBackLinkColor', [$config['backLinkColor']])
				->addSetup('setAdminName', [$config['name']])
				->addSetup('setBack', [$config['back']])
				->addSetup('setNavigationName', [$config['navigation']])
				->addSetup('setFooter', [$config['footer']])
				->addSetup('setShowSearch', [$config['showSearch']])
				->addSetup('setProfile', [$config['profile']])
				->addSetup('setSignOut', [$config['signout']])
				->addSetup('setLogo', [$config['logo']])
				->addSetup('setSearch', [$config['search']])
				->addSetup('setLayout', [$this->getLayout($config['layout'])])
				->addSetup('setAjaxRequest', [$config['ajax']]);
	}

	
	
	
	public function afterCompile(Nette\PhpGenerator\ClassType $class) {
		parent::afterCompile($class);
		
		$initialize = $class->methods['initialize'];
		$initialize->addBody('RadekDostal\NetteComponents\DateTimePicker\DateTimePicker::register();');
		$initialize->addBody('RadekDostal\NetteComponents\DateTimePicker\DatePicker::register();');
	}
	
	
	
	/**
	 * 
	 * @param string $layout
	 * @return array
	 */
	private function getLayout($layout) {

		$dirA = dirname(__DIR__) . '/assets';
		$dirT = dirname(__DIR__) . '/templates';

		$layouts = [
			'AdminLTE' => [
				'templates' => [
					'admin' => "$dirT/AdminLTE/AdminControl.latte",
					'login' => "$dirT/AdminLTE/LoginControl.latte",
					'navigationDir' => 'AdminLTE',
				],
				'files' => [
					"$dirA/AdminLTE/AdminLTE.min.css",
					"$dirA/AdminLTE/_all-skins.min.css",
					"$dirA/AdminLTE/admin.css",
					"$dirA/AdminLTE/app.min.js",
				]
			],			
		];

		if(!key_exists($layout, $layouts)){			
			$pos = '';
			foreach ($layouts as $key => $l){
				$pos .= $key . ', ';
			}
			$pos = rtrim($pos, ', ');
			throw new Nette\InvalidArgumentException('Layout "' . $layout . '" does not exist. Possible choices are: ' . $pos);
		}
		
		return $layouts[$layout];
	}
	
	
	private function getCoreFiles(){
		
		$dirA = dirname(__DIR__) . '/assets';
		
		return [
				'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
				'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
				'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js',
				'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
				"https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.min.css",
				"https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css",
				"https://code.jquery.com/ui/1.11.4/jquery-ui.min.js",
				"https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js",
				"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js",
				"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css",
				"$dirA/netteForms.js",
				"$dirA/nette.ajax.js",
				"$dirA/confirm.ajax.js",
				"$dirA/nette.init.js",
				"$dirA/dateinput/dateinput.ajax.js",
				"$dirA/dateinput/dateinput.cs.js",
				"$dirA/jasny-fileinput/jasny-fileinput.ajax.js",
				"$dirA/jasny-fileinput/jasny-fileinput.auto.min.js",
				"$dirA/jasny-fileinput/jasny-fileinput.min.js",
				"$dirA/jasny-fileinput/jasny-fileinput.min.css",
				"$dirA/pace/pace.min.css",
				"$dirA/pace/pace.min.js",
				"$dirA/admin.js",
			];
	}

}
