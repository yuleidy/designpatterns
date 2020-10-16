<?php

/* This other example is about the hand of a poker game.
*/

#region Enum
abstract class Suit {
    const Club = 'Club';
    const Heart ='Heart';
    const Diamond = 'Diamond';
    const Spade = 'Spade';
}

abstract class Value {
    const Two = '2';
    const Three ='3';
    const Four = '4';
    const Five = '5';
    const Six = '6';
    const Seven = '7';
    const Eight = '8';
    const Nine = '9';
    const Ten = '10';
    const Jack = 'J';
    const Queen = 'Q';
    const King = 'K';
    const Ace = 'A';
}

abstract class HandRanking {
    const HighCard = '1';
    const Pair ='2';
    const TwoPair ='3';
    const ThreeOFAKind = '4';
    const Straight = '5';
    const Flush = '6';
    const FullHouse = '7';
    const FourOfAKind = '8';
    const StraightFlush = '9';
    const RoyalFlush = '10';
}
#endregion

#region Utils or Products
class Card {

    private $suit; //Suit
    private $value; //Value

    public function __construct(Suit $suit, Value $value)
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
#endregion

abstract class HandCatagorizer {

    protected $nextCatagorizer; //HandCatagorizer

    public function RegisterNext(HandCatagorizer $next)
    {
        $this->nextCatagorizer = $next;
        return $this->nextCatagorizer;
    }

    public function Next() {
        return $this->nextCatagorizer;
    }

    public abstract function Catagorize(Hand $hand);

    public static function HasFlush(Hand $hand) {
        $firstSuit = $hand->Cards()[0]->Suit();
        foreach ($hand->Cards() as $card) {
            if ($firstSuit != $card->Suit())
                return false;
        }
        return true;
    }

    public static function HasStraight(Hand $hand) {
        $values = $hand->CardValuesOrSuits(true);
        sort($values);

        for ($i = 1; $i < count($values); $i++) {
            $prevValue = $values[$i-1];
            if ($values[$i] != $prevValue + 1)
                return false;
        }
        return true;
    }

    public static function HasNofKind($n, Hand $hand, $previousFoundValue = null) {
        if ($n <= 1 || $n > 4) return 0; //means false!
        $values = $hand->CardValuesOrSuits(true);
        sort($values);

        $foundSoFar = 1;
        $foundValue = 0;

        for ($i = 1; $i < count($values) || $foundSoFar == $n; $i++) {
            $prevValue = $values[$i-1];
            if ($values[$i] == $prevValue && $prevValue != $previousFoundValue) {
                $foundSoFar++;
                $foundValue = $prevValue;
            }
            else {
                $foundSoFar = 1;
                $foundValue = 0;
            }
        }
        return $foundValue; //($foundSoFar == $n);
    }
}

#region Concrete Catagorizers
class HighCardCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        return HandRanking::HighCard;
    }
}

class PairCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        if (self::HasNofKind(2, $hand)) {
            return HandRanking::Pair;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}

class TwoPairCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        $firstPair = self::HasNofKind(2, $hand);
        $secondPair = self::HasNofKind(2, $hand, $firstPair);
        if ($firstPair && $secondPair) {
            return HandRanking::TwoPair;
        }
        if (self::HasNofKind(2, $hand)) {
            return HandRanking::TwoPair;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}

class ThreeKindCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        if (self::HasNofKind(3, $hand)) {
            return HandRanking::ThreeOFAKind;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}

class StraightCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        //todo: define this function -> 5 consecutive numbers!
        if (self::HasFlush($hand)) {
            return HandRanking::Flush;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}

class FlushCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        if (self::HasFlush($hand)) {
            return HandRanking::Flush;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}

class FourKindCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        if (self::HasNofKind(4, $hand)) {
            return HandRanking::ThreeOFAKind;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}

class FullHouseCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        if (self::HasNofKind(3, $hand) && self::HasNofKind(2, $hand)) {
            return HandRanking::FullHouse;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}

class StraightFlushCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        if (self::HasFlush($hand) && self::HasStraight($hand)) {
            return HandRanking::StraightFlush;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}

class RoyalFlushCatagorizer extends HandCatagorizer {

    public function Catagorize(Hand $hand)
    {
        if (self::HasFlush($hand) && self::HasStraight($hand) && $hand->HighCard() == Value::Ace) {
            return HandRanking::RoyalFlush;
        }
        return $this->nextCatagorizer->Catagorize($hand);
    }
}
#endregion

class HandCatagorizingChain {

    private $head; //HandCatagorizer
    private static $instance; //HandCatagorizingChain

    private function __construct()
    {
        //Note: here is where we're creating the chain of responsibility!
        $this->head = new RoyalFlushCatagorizer();
        $this->head->RegisterNext(new StraightFlushCatagorizer());
        $this->head->RegisterNext(new FourKindCatagorizer());
        $this->head->RegisterNext(new FullHouseCatagorizer());
        $this->head->RegisterNext(new FlushCatagorizer());
        $this->head->RegisterNext(new StraightCatagorizer());
        $this->head->RegisterNext(new ThreeKindCatagorizer());
        $this->head->RegisterNext(new TwoPairCatagorizer());
        $this->head->RegisterNext(new PairCatagorizer());
        $this->head->RegisterNext(new HighCardCatagorizer());
    }

    public static function GetRank(Hand $hand) {
        return self::$instance->Head()->Catagorize($hand);
    }

    public function Head(HandCatagorizer $head = null) {
        if (!empty($head)) {
            $this->head = $head;
        }
        return $this->head;
    }
}

class Hand {

    private $cards;
    private HandRanking $rank;

    public function Add(Card $card) { //this is dealing = add card to hand.
        if (count($this->cards) == 5) {
            throw new Exception("Cannot add more than 5 cards to hand.");
        }
        $this->cards[] = $card;
        if (count($this->cards) == 5) {
            $this->rank = HandCatagorizer::GetRank($this);
        }
    }

    public function HighCard() {
        if (count($this->cards) == 0) {
            throw new Exception("Invalid operation exception.");
        }
        return $this->cards[count($this->cards) - 1];
    }

    public function Cards() {
        return $this->cards;
    }

    public function CardValuesOrSuits($values = true) {
        $values = array();
        foreach ($this->cards as $card) {
            $values[] = ($values) ? $card->Value() : $card->Suit();
        }
        return $values;
    }

    public function Rank() {
        return $this->rank;
    }

    public function ToString() {
        $result = '';
        foreach ($this->cards as $card) {
            $result .= $card->ToString();
        }
        return $result;
    }
}

#region Client Code
//Straight hand!
$hand = new Hand();
$hand->Add(new Card(Suit::Heart, Value::Five));
$hand->Add(new Card(Suit::Spade, Value::Six));
$hand->Add(new Card(Suit::Diamond, Value::Seven));
$hand->Add(new Card(Suit::Club, Value::Eight));
$hand->Add(new Card(Suit::Heart, Value::Nine));

echo "This hand is: " . $hand->Rank() . $hand->ToString() . "\n";
#endregion
