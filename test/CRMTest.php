<?php
require_once('/../domain/LSOfficeDomain.php');

class CRMTest extends PHPUnit_Framework_TestCase
{
    public function setUp(){ }
    public function tearDown(){ }

    public function testCreateCustomer()
    {
        $cl = new Client(2, 'Alex Mbaka','0727596626', 'alex@qet.co.ke','Suite 602, Marafique Arcade. Kenyatta Avenue', 'Thika, KIAMBU', 'Kenya');
		$this->assertInstanceOf('Client', $cl);
    }

    public function testEditCustomer()
    {
        Client::Update(2, 'Alex Mbaka','0727596600', 'alex@qet.co.ke','Suite 602, Marafique Arcade. Kenyatta Avenue');
		$client = Client::GetClient(2);
		$this->assertInstanceOf('Client', $client);
		$this->assertTrue($client->telephone == '0727596600');
    }

    public function testGetAllClients()
    {
        $clients = Client::GetRegister();
        $this->assertTrue(count($clients) == 2);
    }

    public function testGetAllClientsWithLastEncounter()
    {
        
    }

    public function testLogEnquiry()
    {
        $enq = Enquiry::Create('Alex Mbaka','0727596626', 'Topocadastral Survey, Engineering Survey','In my plot');
		$this->assertInstanceOf('Enquiry', Enquiry::GetEnquiry($enq->stamp));
    }

    public function testCheckEnquiry()
    {
 		$enq = Enquiry::Create('Alex Mbaka','0727596626', 'Topocadastral Survey, Engineering Survey','In my plot');
        Enquiry::Check($enq->stamp);
        $enquiry = Enquiry::GetEnquiry($enq->stamp);
		$this->assertInstanceOf('Enquiry', $enquiry);
		$this->assertTrue($enquiry->status == 1);
    }

    public function testGetPendingEnquiries()
    {
        $enquiries = Enquiry::GetPending();
        $this->assertTrue(count($enquiries) == 2);
    }
}
?>


