<?php
declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Security\User;
use Nette\Security\AuthenticationException;
use Kollarovic\Admin\Form\IBaseFormFactory;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;

class LoginFormFactory implements ILoginFormFactory {

	use SmartObject;

	/** @var User */
	private $user;

	/** @var IBaseFormFactory */
	private $baseFormFactory;

	/** @var ITranslator */
	private $translator;

	function __construct(User $user, IBaseFormFactory $baseFormFactory) {

		$this->user = $user;
		$this->baseFormFactory = $baseFormFactory;
		$this->translator = $baseFormFactory->getTranslator();
	}

	public function create(): Form
	{
		$form = $this->baseFormFactory->create();

		if ($this->translator) {

			$form->addText('email', 'backend.login.form.email')
					->setAttribute('placeholder', 'backend.login.form.email')
					->setRequired('backend.login.form.errEmail')
					->addRule(Form::EMAIL, 'backend.login.form.validEmail');


			$form->addPassword('password', 'backend.login.form.password')
					->setAttribute('placeholder', 'backend.login.form.password')
					->setRequired('backend.login.form.errPassword');

			$form->addCheckbox('remember', 'backend.login.form.remember');
			$form->addSubmit('submit', 'backend.login.form.signin');
		} else {

			$form->addText('email', 'Email')
					->setAttribute('placeholder', 'Email')
					->setRequired('Please enter your email.')
					->addRule(Form::EMAIL, 'Please enter a valid email address.');


			$form->addPassword('password', 'Password')
					->setAttribute('placeholder', 'Password')
					->setRequired('Please enter your password.');

			$form->addCheckbox('remember', 'Remember Me');
			$form->addSubmit('submit', 'Sign In');
		}
		$form->onSuccess[] = [$this, 'process'];
		return $form;
	}

	public function process(Form $form, ArrayHash $values)
	{
		try {
			if ($values->remember) {
				$this->user->setExpiration('14 days', false);
			} else {
				$this->user->setExpiration('30 minutes', true);
			}
			$this->user->login($values->email, $values->password);
		} catch (AuthenticationException $e) {
			if ($this->translator) {
				$form->addError($this->translator->translate('backend.login.form.errLogin'));
			} else {
				$form->addError($e->getMessage());
			}
		}
	}

}
