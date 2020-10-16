<?php

#region Product interface
//this interface declares the operations for all concrete products
interface Transport {
    public function ready() : void;
    public function dispatch() : void;
    public function deliver() : void;
}
#endregion

#region Concrete products
class PlainTransport implements Transport {

    public function ready(): void
    {
        echo "Courier is ready to be sent to the plane." . "\n";
    }

    public function dispatch(): void
    {
        echo "Courier is on your way on the plane." . "\n";
    }

    public function deliver(): void
    {
        echo "Courier from the plane is delivered to you." . "\n";
    }
}

class TruckTransport implements Transport {

    public function ready(): void
    {
        echo "Courier is ready to be sent to the truck." . "\n";
    }

    public function dispatch(): void
    {
        echo "Courier is on your way on the truck." . "\n";
    }

    public function deliver(): void
    {
        echo "Courier from the truck is delivered to you." . "\n";
    }
}
#endregion

#region Creator class
//this class declares the factory method
abstract class Courier {
    //Factory Method
    abstract function getCourierTransport() : Transport;

    public function sendCourier() {
        $transport = $this->getCourierTransport();
        $transport->ready();
        $transport->dispatch();
        $transport->deliver();
    }
}

//this is a concrete creator class, overrides the factory method and change the type of object created
class AirCourier extends Courier {

    function getCourierTransport(): Transport
    {
        return new PlainTransport();
    }
}

class GroundCourier extends Courier {

    function getCourierTransport(): Transport
    {
        return new TruckTransport();
    }
}
#endregion

#region Client Code
function deliverCourier(Courier $courier) {
    $courier->sendCourier();
}

echo "Test courier \n";
deliverCourier(new GroundCourier());

echo "Test courier \n";
deliverCourier(new AirCourier());
#endregion