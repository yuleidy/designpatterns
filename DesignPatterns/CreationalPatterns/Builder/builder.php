<?php

#region Enums
abstract class CheeseType {
    const Swiss = 'Swiss';
    const Parmesan ='Parmesan';
    const Mozzarella = 'Mozzarella';
}

abstract class MeatType {
    const Turkey = 'Turkey';
    const Beef = 'Beef';
    const Chicken = 'Chicken';
    const Ham = 'Ham';
}

abstract class BreadType {
    const White = 'White';
    const Wheat = 'Wheat';
}
#endregion

#region THE PRODUCT!
class Sandwich {
    public $hasMayo;
    public $isToasted;
    public $cheeseType;
    public $meatType;
    public $breadType;

    public function Display() {
        echo "Sandwich on " . $this->breadType . " bread \n";
        if ($this->isToasted) echo "Toasted \n";
        if ($this->hasMayo) echo "With Mayo \n";
        echo "Meat: " . $this->meatType . "\n";
        echo "Cheese: " . $this->cheeseType . "\n";
    }
}
#endregion

#region THE BUILDER!
abstract class SandwichBuilder {
    public $sandwich;

    public function GetSandwich() {
        return $this->sandwich;
    }

    public function CreateNewSandwich() {
        $this->sandwich = new Sandwich();
    }

    public abstract function PrepareBread();
    public abstract function ApplyMeatAndCheese();
    public abstract function AddCondiments();
}
#endregion

#region CONCRETE BUILDERS
//these classes are really just data class, that defines what makes each type of sandwich.

class ClubSandwichBuilder extends SandwichBuilder {

    public function PrepareBread()
    {
        $this->sandwich->breadType = BreadType::White;
        $this->sandwich->isToasted = true;
    }

    public function ApplyMeatAndCheese()
    {
        $this->sandwich->cheeseType = CheeseType::Mozzarella;
        $this->sandwich->meatType = MeatType::Turkey;
    }

    public function AddCondiments()
    {
        $this->sandwich->hasMayo = true;
    }
}

class MySandwichBuilder extends SandwichBuilder {

    public function PrepareBread()
    {
        $this->sandwich->breadType = BreadType::Wheat;
        $this->sandwich->isToasted = false;
    }

    public function ApplyMeatAndCheese()
    {
        $this->sandwich->cheeseType = CheeseType::Swiss;
        $this->sandwich->meatType = MeatType::Chicken;
    }

    public function AddCondiments()
    {
        $this->sandwich->hasMayo = true;
    }
}
#endregion

#region THE DIRECTOR
class SandwichMaker {

    private $sandwichBuilder;

    public function __construct(SandwichBuilder $sandwichBuilder)
    {
        $this->sandwichBuilder = $sandwichBuilder;
    }

    public function BuildSandwich() {
        $this->sandwichBuilder->CreateNewSandwich();
        $this->sandwichBuilder->PrepareBread();
        $this->sandwichBuilder->ApplyMeatAndCheese();
        $this->sandwichBuilder->AddCondiments();
    }

    public function GetSandwich() {
        return $this->sandwichBuilder->GetSandwich();
    }
}
#endregion

#region Client Code
$maker1 = new SandwichMaker(new MySandwichBuilder());
$maker1->BuildSandwich();
$sandwich1 = $maker1->GetSandwich();
$sandwich1->Display();

$maker2 = new SandwichMaker(new ClubSandwichBuilder());
$maker2->BuildSandwich();
$sandwich2 = $maker2->GetSandwich();
$sandwich2->Display();
#endregion
