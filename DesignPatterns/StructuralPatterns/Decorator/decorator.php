<?php

// Base component
interface Pizza {
    public function getDescription() : String;

}

#region Concrete components
class Margarita implements Pizza {

    public function getDescription(): String
    {
        return "Margarita ";
    }
}

class VeggieParadise implements Pizza {

    public function getDescription(): String
    {
        return "VeggieParadise ";
    }
}
#endregion

#region Base Decorators
class PizzaToppings implements Pizza {

    protected $pizza;

    public function __construct(Pizza $pizza)
    {
        $this->pizza = $pizza;
    }

    public function getDescription(): String
    {
        return $this->pizza->getDescription();
    }
}
#region

#region Concrete Decorator
class ExtraCheese extends PizzaToppings {

    public function getDescription(): String
    {
        return parent::getDescription() . "Extra cheese ";
    }
}

class Jalapeno extends PizzaToppings {

    public function getDescription(): String
    {
        return parent::getDescription() . "Jalapeno ";
    }
}
#endregion

#region Client code
function makePizza(Pizza $pizza) {
    echo "Your order: " . $pizza->getDescription();
}

$pizza = new Margarita();
$pizza = new ExtraCheese($pizza);
$pizza = new Jalapeno($pizza);

makePizza($pizza);
#endregion