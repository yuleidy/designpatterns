<?php

#region Implementor
interface IFormatter {
    public function format($key, $value) : String;
}
#endregion

#region Concrete Implementor
class StandardFormatter implements IFormatter{

    public function format($key, $value): String
    {
        return $key . ": " . $value;
    }
}

class BackwardsFormatter implements IFormatter {

    public function format($key, $value): String
    {
        return $key . ": " . strrev($value);
    }
}

class FancyFormatter implements IFormatter {

    public function format($key, $value): String
    {
        return "-- " . $key . " --> " . $value;
    }
}
#endregion

#region Main Abstraction
abstract class Manuscript {
    protected $formatter;

    public function __construct($formatter)
    {
        $this->formatter = $formatter;
    }

    public abstract function print();
}
#endregion

#region Refined Abstractions
class Book extends Manuscript {
    public $tittle;

    public function __construct(IFormatter $formatter, $tittle)
    {
        $this->tittle = $tittle;
        $this->formatter = $formatter;
    }

    public function print() {
        echo $this->formatter->format("Title", $this->tittle). "\n";
    }
}

class TermPaper extends Manuscript {
    public $class;

    public function __construct(IFormatter $formatter, $class)
    {
        $this->class = $class;
        $this->formatter = $formatter;
    }

    public function print() {
        echo $this->formatter->format("Class", $this->class). "\n";
    }
}
#endregion

#region Client code
$manuscripts = array();

//using standard formatter
$formatter = new StandardFormatter();
$book1 = new Book($formatter, "Red Riding Hood");
$manuscripts[] = $book1;
$paper1 = new TermPaper($formatter, "Design Patters");
$manuscripts[] = $paper1;

//using backward formatter
$formatter2 = new BackwardsFormatter();
$book2 = new Book($formatter2, "Princes and the Pea");
$manuscripts[] = $book2;
$paper2 = new TermPaper($formatter2, "OOP principles");
$manuscripts[] = $paper2;

//using the fancy formatter
$formatter3 = new FancyFormatter();
$book3 = new Book($formatter3, "Princes and the Frog");
$manuscripts[] = $book3;
$paper3 = new TermPaper($formatter3, "Programming Languages");
$manuscripts[] = $paper3;

foreach ($manuscripts as $manuscript) {
    $manuscript->print();
}
#endregion
