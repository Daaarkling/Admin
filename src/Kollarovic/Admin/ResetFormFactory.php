<?php
declare(strict_types=1);

namespace Kollarovic\Admin;


use Nette\Security\User;
use Kollarovic\Admin\Form\IBaseFormFactory;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\SmartObject;

class ResetFormFactory implements IResetFormFactory {

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

			$form->addText('email', 'backend.login.formReset.email')
					->setAttribute('placeholder', 'backend.login.formReset.emailMsg')
					->setRequired('backend.login.formReset.errEmail')
					->addRule(Form::EMAIL, 'backend.login.formReset.validEmail');

			$form->addButton('back', 'backend.login.formReset.back');
			$form->addSubmit('submit', 'backend.login.formReset.submit');
		} else {

			$form->addText('username', 'Email')
					->setAttribute('placeholder', 'Enter your e-mail address below to reset your password.')
					->setRequired('Please enter your email.')
					->addRule(Form::EMAIL, 'Please enter a valid email address.');

			$form->addButton('back', 'Back');
			$form->addSubmit('submit', 'Sign In');
		}
		return $form;
	}

}
