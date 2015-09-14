<?php

require_once 'Accounting.php';

class FinancialTransaction extends Transaction
{
	function __construct(Money $amount, $description, TransactionType $txtype)
	{		
		//$ttype === posting protocol/rule
		$this->transactionType = $txtype;
		parent::__construct($amount, $description);
	}
}

class Paymentx extends FinancialTransaction
{
	public $reference;
	public $isCleared;
	public $invoicePercent;
	public $method;

	function __construct(Money $amount, $description, TransactionType $paymentMethod)
	{		
		//$ttype === posting protocol/rule
		$this->method = $paymentMethod;
		parent::__construct($amount, $description, $paymentMethod);
	}
}

class Invoicing extends FinancialTransaction
{
	function __construct(Money $amount, $description, TransactionType $invoicingMethod)
	{		
		//$ttype === posting protocol/rule
		parent::__construct($amount, $description, $invoicingMethod);
	}
}



//means: [ money, credit, debit, cheque, bank transfer, stock ] asset ::: payer and payee.
//before: invoice, after: receipt

class PaymentMethod extends TransactionType
{
	function __construct($name)
	{
		parent::__construct($amount, $description);
	}
}

class InvoicingMethod extends TransactionType
{
	function __construct($name)
	{
		parent::__construct($name);
	}
}

/*class CreditPayment extends PaymentMethod
{
	
	function __construct()
	{
		# code...
	}
}

class BankDeposit extends PaymentMethod
{
	
	function __construct()
	{
		# code...
	}
}


class Cash extends PaymentMethod
{
	
	function __construct()
	{
		# code...
	}
}

class Cheque extends PaymentMethod
{
	public $isCleared;

	function __construct()
	{
		# code...
	}
}*/

class ElectronicPayment extends PaymentMethod
{
	protected $provider;

	protected static $payments = array();

	function __construct($provider)
	{
		//First check whether the provider is valid from the authorized payment methods
		parent::__construct('Electronic Payment - '.$provider);
		$this->provider = $provider;
	}

	protected static function checkValidity($provider)
	{

	}

	
}

class MobileTransfer extends ElectronicPayment
{
	
	function __construct($provider)
	{
		parent::__construct($provider);	
	}
}

class EmailPayment extends ElectronicPayment
{
	
	function __construct($provider)
	{
		parent::__construct($provider);		
		//e.g paypal
	}
}

class PayPal extends EmailPayment
{//A kind of process type or protocol - 5d - a kind of event????

	function __construct()
	{
		parent::__construct('PayPal');//provider
		//this info should originate from the database, insert ignore protocol included
		//$this->code = self::GetCode('PayPal');
		//parent::__construct();
	}
}



/*class CreditCard extends ElectronicPayment
{
	
	function __construct()
	{
		# code...
	}
}

class DebitCard extends ElectronicPayment
{
	
	function __construct()
	{
		# code...
	}
}

class TaxablePayment extends TransactionType
{//A kind of process type or protocol - 5d - a kind of event????

	function __construct()
	{
		parent::__construct();//provider
		//name - electronic payment - paypal
		$this->drAccounts[] = Account::GetAccount('PayPal Bank', 'ledgers');
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetAccount('Sales Revenue', 'ledgers');
		$this->crRatios[] = 1;
		//this info should originate from the database, insert ignore protocol included
		//$this->code = self::GetCode('PayPal');
		//parent::__construct();
	}
}

class TaxableCreditSale extends TransactionType
{//A kind of process type or protocol - 5d - a kind of event????

	function __construct()
	{
		parent::__construct();//provider
		//name - electronic payment - paypal
		$this->drAccounts[] = Account::GetAccount('Accounts Receivable', 'ledgers');
		$this->drAccounts[] = Account::GetAccount('Taxes Collectable', 'ledgers');
		//$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetAccount('Sales Revenue', 'ledgers');
		//$this->crRatios[] = 1;
		//this info should originate from the database, insert ignore protocol included
		//$this->code = self::GetCode('PayPal');
		//parent::__construct();
	}
}

class PaypalPayment extends Invoicing
{//A payment is a financial transaction and thus a transaction
	public $sufficiency;//payment - invoice amount ratio
	public $email;
	public $reference;

	function __construct($email, $ref, Money $amount)
	{		
		$this->reference = $ref;
		$this->email = $email;
		$description = 'Payment for goods bought by '.$email;
		$paymentMethod = new PayPalFullPayment();
		parent::__construct($amount, $description, $paymentMethod);
	}

	public function processPayment()
	{
		//Communicate with paypal via API to confim payment then
		$this->commit();
	}

	public function setSufficiency($ratio)
	{
		$this->sufficiency = $ratio;
	}

	public function prepare(Invoice $invoice)
	{		
		//singleton processor for all payments with an static instance of a transaction processor
		//Transaction == $payment
		//payment is a subclass of transaction
		//since it processes payments, it generates receipts
		//sum(cr) + sum(dr) = 0

		//Evaluate sufficiency of payment
		if ($invoice->balance == $invoice->amount && $invoice->amount == $this->amount->amount) {
			$this->setSufficiency(1.00);			
			//This is the first payment
			//this is a single cash sale
			//post to income revenue and paypal/mpesa/cash
		}else if ($invoice->balance == $invoice->amount && $invoice->amount < $this->amount->amount) {
			$this->setSufficiency(1.00);
			//this is a first over this
			//post transaction to income revenue & paypal + client account(overthis)
		}else if ($invoice->balance == $invoice->amount && $invoice->amount > $this->amount->amount) {
			$this->setSufficiency(floatval($this->amount->amount/$invoice->amount));
			//this is the first partial this
			//post transaction to income revenue & paypal + accounts receivable
		}else if ($invoice->balance < $invoice->amount && $invoice->balance == $this->amount->amount) {
			$this->setSufficiency(floatval($this->amount->amount/$invoice->amount));
			//this is the last partial payment
			//post transaction to ac receivable + paypal
		}else if ($invoice->balance < $invoice->amount && $invoice->balance < $this->amount->amount) {
			$this->setSufficiency(floatval($this->amount->amount/$invoice->amount));
			//this is the last partial over payment
			//post transaction to income revenue & paypal + client account(overthis)
			//post transaction to ac receivable + paypal
		}else if ($invoice->balance < $invoice->amount && $invoice->balance > $this->amount->amount) {
			$this->setSufficiency(floatval($this->amount->amount/$invoice->amount));
			//this is a subsequent partial payment
			//post transaction to ac receivable + paypal
		}

		for ($i=0; $i < count($this->paymentMethod->drAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->paymentMethod->drRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->paymentMethod->drAccounts[$i], $amount, $this->date, 'dr'));
		}

		for ($i=0; $i < count($this->paymentMethod->crAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->paymentMethod->crRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->paymentMethod->crAccounts[$i], $amount, $this->date, 'cr'));
		}

		return true;

	}
}*/
?>