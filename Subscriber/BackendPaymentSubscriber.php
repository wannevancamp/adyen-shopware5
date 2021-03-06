<?php declare(strict_types=1);

namespace AdyenPayment\Subscriber;

use Enlight\Event\SubscriberInterface;
use AdyenPayment\AdyenPayment;

/**
 * Class BackendPaymentSubscriber
 * @package AdyenPayment\Subscriber
 */
class BackendPaymentSubscriber implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Payment' => 'afterBackendPayment'
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function afterBackendPayment(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Payment $subject */
        $subject = $args->getSubject();

        if ($subject->Request()->getActionName() === 'getPayments') {
            $this->afterGetPayments($args);
        }
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    private function afterGetPayments(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Payment $subject */
        $subject = $args->getSubject();

        $data = $subject->View()->getAssign('data');

        $data = array_values(array_filter($data, function ($e) {
            return $e['name'] !== AdyenPayment::ADYEN_GENERAL_PAYMENT_METHOD;
        }));

        $subject->View()->assign('data', $data);
    }
}
