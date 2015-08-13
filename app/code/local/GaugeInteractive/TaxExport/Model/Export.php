<?php
ini_set('memory_limit', '-1');

class GaugeInteractive_TaxExport_Model_Export extends Mage_Core_Model_Abstract
{
    public function run($data)
    {

        $fromDate = date("Y-m-d", strtotime($data['start_date']));
        $toDate = date("Y-m-d", strtotime($data['end_date']));
        $state = $data['state'];

        $filename = Mage::getBaseDir('var') . '/sales_tax' . '_' . $fromDate . '_' . $toDate . '_' . $state . '.csv';

        /* Get the collection */
        $orders = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate));

        if ($orders) {
            $fp = fopen($filename, "w");

            foreach ($orders as $order) {
                $orderArray = array();

                array_push($orderArray, $order->getIncrementId());
                array_push($orderArray, $order->getStatus());
                array_push($orderArray, $order->getCreatedAt());
                array_push($orderArray, $order->getTaxAmount());
                $orderState = $order->getBillingAddress()->getRegionCode();
                array_push($orderArray, $state);
                $zip = $order->getBillingAddress()->getPostcode();
                array_push($orderArray, $zip);

                if ($orderState == $state) {
                    fputcsv($fp, $orderArray);
                }
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=export.csv');
            ob_clean();
            flush();
            readfile($filename);
            fclose($fp);

        } else {
            echo 'no order';
        }
    }
}