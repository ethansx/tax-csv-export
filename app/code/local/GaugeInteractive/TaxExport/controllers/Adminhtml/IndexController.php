<?php

class GaugeInteractive_TaxExport_Adminhtml_IndexController
    extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function exportAction()
    {

        $variables = Mage::app()->getRequest()->getParams();

        $data = array(
            'start_date' => $variables['start_date'],
            'end_date' => $variables['end_date'],
            'state' => $variables['state']
        );

        Mage::getModel('gaugeinteractive_taxexport/export')->run($data);
    }
}