<?php

namespace App\Forms\Base;

use Doctrine\ORM\EntityNotFoundException;
use Nette\Application\UI\Form;

abstract class BaseModalForm extends BaseEntityForm
{
	/**
	 * Vykreslit otevøený modal?
	 * @var bool
	 * @persistent
	 */
	public $renderModal = false;

	/**
	 * @var int
	 * @persistent
	 */
	public $id;


	/**
	 * @var string Soubor s šablonou
	 */
	protected $templateFile = 'templates/form.latte';



	/**
	 * Pri pripojeni do presenteru
	 * @param $presenter
	 *
	 * @throws EntityNotFoundException
	 */
	protected function attached($presenter)
	{
		parent::attached($presenter);

		if($this->id)
			$this->load($this->id);

		/** @var Form $frm */
		$frm = $this->getComponent('form');

		$frm->onSubmit[] = function()
		{
			$this->redrawControl();
		};

		$frm->onSuccess[] = function()
		{
			$this->presenter->payload->modalClose = true;
			$this->renderModal = false;
		};
	}



	/**
	 * Nacte entitu z DB
	 * @param $id
	 *
	 * @throws EntityNotFoundException
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\TransactionRequiredException
	 */
	public function load($id)
	{
		$this->entity = $this->em->find($this->entityClass, $id);
		if(!$this->entity)
			throw new EntityNotFoundException;
	}


	/**
	 * Vykreslení formu do modalu
	 */
	public function render()
	{
		$this->template->setFile( __DIR__.'/../'.$this->templateFile);
		$this->template->renderModal = $this->renderModal;
		$this->template->entity = $this->entity;
		$this->template->render();
	}

	/**
	 * Otevøe modal a do nìj entitu podle ID
	 * @param $id
	 * @throws EntityNotFoundException
	 */
	public function handleOpen($id = null)
	{
		if ($id)
		{
			$this->load($id);
		}

		$this->renderModal = true;
		$this->redrawControl();
	}
}