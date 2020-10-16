<?php


class Card
{
    private $suit;
    private $value;

    public function __construct($suit, $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function Suit() {
        return $this->suit;
    }

    public function Value() {
        return $this->value;
    }

    public function ToString() {
        return '' . $this->value . $this->SuitToString();
    }

    private function SuitToString() {
        switch ($this->suit) {
            case Suit::Club:
                return "C";
            case Suit::Diamond:
                return "D";
            case Suit::Spade:
                return "S";
            case Suit::Heart:
                return "H";
            default:
                throw new Exception("Not implemented exception!");
        }
    }
}