<?php
//The aim of a test is to test for the end user use cases, this is their originating principle - BDD, TDD
require_once('/../domain/LSOfficeDomain.php');

class OperationsTest extends PHPUnit_Framework_TestCase
{	
    public $qid;

    public function setUp(){ }
    public function tearDown(){ }

    public function testCreateQuotation()
    {
        $client = Client::GetClient(2);

        //Creation
        $quotation = Quotation::CreateQuotation($client);
        $service = SurveyOfficeService::Create('Topocadastral Survey', 'Survey of the topocadastral kind', floatval(12000));
        $ql = QuotationLine::Create($quotation->id, $service->name, 'Task Description', 2, $service->rate, 16);
        $quotation->addToQuote($ql);
        $ql = QuotationLine::Create($quotation->id, $service->name, 'Another Task Description', 1, $service->rate, 16);
        $quotation->addToQuote($ql);
        //$quotation->addTax(floatval(16));

        $quotation->generate();

        $this->assertInstanceOf('Quotation', $quotation);
		$this->assertTrue($quotation->items == 3);
		$this->assertTrue($quotation->amount == floatval(36000));
		$this->assertTrue($quotation->total == floatval(41760));
    }

    public function testDiscardQuotation()
    {
    	sleep(1);
        $client = Client::GetClient(2);
        //Creation
        $quotation = Quotation::CreateQuotation($client);
        $service = SurveyOfficeService::Create('Engineering Survey', 'Survey of the engineering kind', floatval(17500));
        $ql = QuotationLine::Create($quotation->id, $service->name, 'Task Description', 2, $service->rate, 16);
        $quotation->addToQuote($ql);

        $this->assertInstanceOf('Quotation', $quotation);
		//discarding
        $quotation->discard();
        //should fail
        $quotation = Quotation::GetQuotation($quotation->id);
        $this->assertTrue($quotation == null);
        //discarding
        //Quotation::Delete($quotation->id);
    }

    public function testCreateProject()
    {
        $client = Client::GetClient(2);

        $project = Project::Create('Gatuanyaga Phase II', 'Gatu, Kiambu', '24/08/2015', 'Description', $client->id);
        //create project account, a sub account of client (debtor account)
        
        $qid1 = '252';
        $qid2 = '254';
        
        $project->importQuote($qid1);
        $project->importQuote($qid2);

        $project->authorize();

        $this->assertInstanceOf('Project', $project);
        $this->assertTrue(count($project->quotations) == 2);
    }

    public function testManageProject()
    {
    	$client = Client::GetClient(2);

    	$project = Project::GetProject(20);

    	$this->assertInstanceOf('Project', $project);

        $this->assertTrue(count($project->getActivities()) == 17);

        $qid3 = '278';
        $project->importQuote($qid3);

        $pactivs = $project->getActivities();
        $this->assertTrue(count($pactivs) == 19);

        $pactiv = $pactivs[3];
        $project->removeActivity($pactiv->id);

        $this->assertTrue(count($project->getActivities()) == 18);

        $project = Project::Update($project->id, 'Gatuanya Phase II', 'Gatu, Kiambu', 3, 'Description X');

        $this->assertInstanceOf('Project', $project);
    }

    public function testRaiseInvoice()
    {
        $client = Client::GetClient(2);

    	$project = Project::GetProject(4);

    	$quotes = $project->quotations;
    	$quote = $quotes[0];
    	
    	$discount = 5;

    	$invoice = Invoice::RaiseQuotationInvoice($quote->id, $discount);//in session

    	$invoice->post();

    	$this->assertInstanceOf('Invoice', $invoice);

    }

    public function testReceivePayment()
    {
        $client = Client::GetClient(2);

    	$projects = Project::GetAll($client->id);

    	$project = $projects[0];

    	$amount = new Money('10000.00', Currency::Get('KES'));//new Quantity('number', 'unit')
		//$signature = new Signature('Name/email','Password/Identification/SessionID');
		$payment = new Payment('X Collection Account', 'Voucher No', $amount);

		$category = $project->id;//or general invoiceing [code: 0]

		$descr = 'Lojack Charges';

		$balbf = $client->balance;

		$balbd = $balbf + floatval($amount->amount);

		$receipt = $client->makePayment($payment, $category, $descr);

		$this->assertTrue(floatval($client->balance) == floatval($balbd));

		$this->assertInstanceOf('Receipt', $receipt);
    }

    public function testFileReport()
    {
        $client = Client::GetClient(2);

    	$projects = Project::GetAll($client->id);

    	$project = $projects[0];

    	$pactivs = $project->getActivities();

        $pactiv = $pactivs[0];
        $tasks = $pactiv->id;
        $pactiv = $pactivs[1];
        $tasks += ', '.$pactiv->id;

        $emp1 = Employee::GetClient(3);
        $personell = $emp1->id;
        $emp2 = Employee::GetClient(4);
        $personell += $emp2->id;

        $expvoucher = ExpenseVoucher::CreateEmpty();

        $expvoucher->addExpense('descr', 'expense account id', 'amount');
        $expvoucher->addExpense('descr', 'expense account id', 'amount');

        $project->fileReport($tasks, $personell, 'action report', $expvoucher->id);
    }
}


?>


