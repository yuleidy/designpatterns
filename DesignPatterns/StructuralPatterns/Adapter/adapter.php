<?php

#region Target/Client Interface
interface Share {
    // Request
    public function shareData();
}
#endregion

#region Adaptee/Service
class WhatsAppShare {
    // Special request
    public function waShare(String $string) {
        echo "Share data via WhatsApp: " . $string . "\n";
    }
}

class FacebookShare {
    // Special request
    public function fbShare(String $string) {
        echo "Share data via Facebook: " . $string . "\n";
    }
}
#endregion

#region Adapters
class WhatsAppShareAdapter implements Share {

    private $whatsapp;
    private $data;

    public function __construct(WhatsAppShare $whatsapp, String $data)
    {
        $this->whatsapp = $whatsapp;
        $this->data = $data;
    }

    public function shareData()
    {
        $this->whatsapp->waShare($this->data);
    }
}

class FacebookAdapter implements Share {

    private $facebook;
    private $data;

    public function __construct(FacebookShare $facebook, String $data)
    {
        $this->facebook = $facebook;
        $this->data = $data;
    }

    public function shareData()
    {
        $this->facebook->fbShare($this->data);
    }
}
#endregion

#region Client Code and the use of the adapter classes
// Client code to use our business objects
function clientCode(Share $share) {
    $share->shareData();
}

$wa = new WhatsAppShare();
$waShare = new WhatsAppShareAdapter($wa, 'Hello WhatsApp!');
clientCode($waShare); //the client only deals with the adapter!

$fb = new FacebookShare();
$fbShare = new FacebookAdapter($fb, 'Hello Facebook!');
clientCode($fbShare);
#endregion
