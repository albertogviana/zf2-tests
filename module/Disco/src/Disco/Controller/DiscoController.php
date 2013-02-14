<?php
namespace Disco\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Disco\Model\Disco;          // <-- Add this import
use Disco\Form\DiscoForm;       // <-- Add this import



class DiscoController extends AbstractActionController
{

    protected $discoTable;

    public function getDiscoTable()
    {
        if (!$this->discoTable) {
            $sm = $this->getServiceLocator();
            $this->discoTable = $sm->get('Disco\Model\DiscoTable');
        }
        return $this->discoTable;
    }

    public function indexAction()
    {
	return new ViewModel(array(
            'discos' => $this->getDiscoTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new DiscoForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $disco = new Disco();
            $form->setInputFilter($disco->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $disco->exchangeArray($form->getData());
                $this->getDiscoTable()->saveDisco($disco);

                // Redirect to list of discos
                return $this->redirect()->toRoute('disco');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('disco', array(
                'action' => 'add'
            ));
        }
        $disco = $this->getDiscoTable()->getDisco($id);

        $form  = new DiscoForm();
        $form->bind($disco);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($disco->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getDiscoTable()->saveDisco($form->getData());

                // Redirect to list of discos
                return $this->redirect()->toRoute('disco');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
	$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('disco');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getDiscoTable()->deleteDisco($id);
            }

            // Redirect to list of discos
            return $this->redirect()->toRoute('disco');
        }

        return array(
            'id'    => $id,
            'disco' => $this->getDiscoTable()->getDisco($id)
        );
    }
}
