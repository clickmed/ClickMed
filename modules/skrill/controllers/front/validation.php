<?php
/**
* 2015 Skrill
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
*  @author    Skrill <contact@skrill.com>
*  @copyright 2015 Skrill
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Skrill
*/

require_once(dirname(__FILE__).'/../../core/core.php');
require_once(dirname(__FILE__).'/../../core/versiontracker.php');

class SkrillValidationModuleFrontController extends ModuleFrontController
{
    protected $orderConfirmationUrl = 'index.php?controller=order-confirmation';

    public function postProcess()
    {
        PrestaShopLogger::addLog('process return url', 1, null, null, null, true);
        $cartId = (int)Tools::getValue('cart_id');

        sleep(5);
        $orderId = Order::getOrderByCartId($cartId);

        if ($orderId) {
            PrestaShopLogger::addLog('validate order', 1, null, null, null, true);
            $transactionId = Tools::getValue('transaction_id');
            $this->validateOrder($cartId, $transactionId);
        } else {
            PrestaShopLogger::addLog('prestashop order not found', 1, null, null, null, true);
            $this->redirectPaymentReturn();
        }
    }

    protected function validateOrder($cartId, $transactionId)
    {
        $order = $this->module->getOrderByTransactionId($transactionId);

        PrestaShopLogger::addLog('transaction log order : '.print_r($order, true), 1, null, null, null, true);

        if (empty($order) || empty($order['order_status'])) {
            PrestaShopLogger::addLog('transaction order not found', 1, null, null, null, true);
            $this->redirectPaymentReturn();
        }

        $orderStatus = $order['order_status'];

        if ($orderStatus == $this->module->processedStatus || $orderStatus == $this->module->pendingStatus) {
            $this->redirectSuccess($cartId);
        } else {
            if ($orderStatus == $this->module->refundedStatus || $orderStatus == $this->module->refundFailedStatus) {
                $errorStatus = 'ERROR_GENERAL_FRAUD_DETECTION';
            } elseif ($orderStatus == $this->module->failedStatus) {
                $paymentResponse = unserialize($order['payment_response']);
                $errorStatus = SkrillPaymentCore::getSkrillErrorMapping($paymentResponse['failed_reason_code']);
            } else {
                $errorStatus = 'SKRILL_ERROR_99_GENERAL';
            }
            $this->redirectError($errorStatus);
        }
    }

    protected function redirectError($returnMessage)
    {
        $this->errors[] = $this->module->getLocaleErrorMapping($returnMessage);
        $this->redirectWithNotifications($this->context->link->getPageLink('order', true, null, array(
            'step' => '3')));
    }

    protected function redirectPaymentReturn()
    {
        $url = $this->context->link->getModuleLink('skrill', 'paymentReturn', array(
            'secure_key' => $this->context->customer->secure_key), true);
        PrestaShopLogger::addLog('rediret to payment return : '.$url, 1, null, null, null, true);
        Tools::redirect($url);
        exit;
    }

    protected function redirectSuccess($cartId)
    {
        Tools::redirect(
            $this->orderConfirmationUrl.
            '&id_cart='.$cartId.
            '&id_module='.(int)$this->module->id.
            '&key='.$this->context->customer->secure_key
        );
    }
}
