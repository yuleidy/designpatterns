<?php

//Subscriber interface
interface Subscriber {
    public function update($postID);
}

#region Publisher class
class HealthyMe {
    private $subscribers = array();
    private $post;

    public function registerSubscriber(Subscriber $sub) {
        $this->subscribers[] = $sub;
        echo "Subscriber added! \n";
    }

    public function notifySubscribers() {
        foreach ($this->subscribers as $sub) {
            $sub->update($this->post);
        }
    }

    public function publishPost($post) {
        $this->post = $post;
        $this->notifySubscribers();
    }
}
#endregion

#region Concrete Subscriber
class FoodUpdateSubscriber implements Subscriber {

    public function update($postID)
    {
        echo "New food post: $postID published. \n";
    }
}

class SportsUpdateSubscriber implements Subscriber {

    public function update($postID)
    {
        echo "New sports post: $postID published. \n";
    }
}
#endregion

#region Client Code
$publisher = new HealthyMe();
$subscriber1 = new FoodUpdateSubscriber();
$subscriber2 = new SportsUpdateSubscriber();

$publisher->registerSubscriber($subscriber1);
$publisher->registerSubscriber($subscriber2);
$publisher->publishPost(12);
$publisher->publishPost(13);
#endregion