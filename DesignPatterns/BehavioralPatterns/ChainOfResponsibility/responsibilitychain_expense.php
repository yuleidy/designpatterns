<?php

/*Traditional usage:
1. the client
2. the message handler interface
3. the concrete handlers

The client and handler link together to form the chain of responsibility.

Expense Report example and demo.
*/

#region Enum
abstract class ApprovalResponse {
    const Denied = 'Denied';
    const Approved ='Approved';
    const BeyondApprovalLimit = 'BeyondApprovalLimit';
}
#endregion

interface IExpenseReport {
    public function GetTotal();
}

interface IExpenseApprover {
    public function ApproveExpense(IExpenseReport $expReport);
}

interface IExpenseHandler {
    public function Approve(IExpenseReport $expReport);
    public function RegisterNext(IExpenseHandler $next);
}

class ExpenseHandler implements IExpenseHandler {

    private $expenseApprover; //IExpenseApprover
    private $nextExpenseHandler; //IExpenseHandler

    public function __construct($expenseApprover)
    {
        $this->expenseApprover = $expenseApprover;
        $this->nextExpenseHandler = EndOfChainHandler::Instance();
    }

    public function Approve(IExpenseReport $expReport)
    {
        $response = $this->expenseApprover->ApproveExpense($expReport);
        if ($response == ApprovalResponse::BeyondApprovalLimit) {
            return $this->nextExpenseHandler->Approve($expReport);
        }
        return $response;
    }

    public function RegisterNext(IExpenseHandler $nextExpenseHandler)
    {
        $this->nextExpenseHandler = $nextExpenseHandler;
    }
}

class EndOfChainHandler implements IExpenseHandler {

    private static $instance; //EndOfChainHandler

    private function __construct() {}

    public static function Instance() {
        self::$instance = new EndOfChainHandler();
        return self::$instance;
    }

    public function Approve(IExpenseReport $expReport)
    {
        return ApprovalResponse::Denied;
    }

    public function RegisterNext(IExpenseHandler $next)
    {
        throw new Exception("End of chain handler must be the end of the chain!");
    }
}

class ExpenseReport implements IExpenseReport {

    protected $total;

    public function __construct($total)
    {
        $this->total = $total;
    }

    public function GetTotal()
    {
        return $this->total;
    }
}

class Employee implements IExpenseApprover {

    public $name;
    private $approvalLimit;

    public function __construct($name, $approvalLimit)
    {
        $this->name = $name;
        $this->approvalLimit = $approvalLimit;
    }

    public function ApproveExpense(IExpenseReport $expReport)
    {
        return ($expReport->GetTotal() > $this->approvalLimit)
                ? ApprovalResponse::BeyondApprovalLimit
                : ApprovalResponse::Approved;
    }
}

#region Client Code
$managers = array();

$william = new ExpenseHandler(new Employee("William Worker", 0));
$mary = new ExpenseHandler(new Employee("Mary Manager", 1000));
$victor = new ExpenseHandler(new Employee("Victor Vicepres", 5000));
$paula = new ExpenseHandler(new Employee("Paula President", 20000));

$william->RegisterNext($mary);
$mary->RegisterNext($victor);
$victor->RegisterNext($paula);

$expense = new ExpenseReport(5000);
$response = $william->Approve($expense);
echo 'The response was: ' . $response;

#endregion