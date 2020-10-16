<?php

//strategy interface
interface PaymentGateway {
    public function pay($amount);
}

#region Concrete Strategy
class PayByDebitOrCredit implements PaymentGateway {

    public function pay($amount)
    {
        echo "Paid " . $amount . " via Credit/Debit card. \n";
    }
}

class PayByPayPall implements PaymentGateway {

    public function pay($amount)
    {
        echo "Paid " . $amount . " via Paypal. \n";
    }
}
#endregion

#region Context class
class Order {
    private $paymentGateway;

    public function setPaymentGateWay(PaymentGateway $paymentGateway) {
        $this->paymentGateway = $paymentGateway;
    }

    public function pay($amount) {
        $this->paymentGateway->pay($amount);
    }
}
#endregion

#region Client Code
$order = new Order();
$order->setPaymentGateWay(new PayByDebitOrCredit());
$order->pay(87);

$order = new Order();
$order->setPaymentGateWay(new PayByPayPall());
$order->pay(155);
#endregion