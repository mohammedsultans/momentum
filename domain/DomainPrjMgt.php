<?php

class Service
{
}

class OfficeService extends Service
{
	public $name;
	public $descr;
	public $unit;
	public $rate;

	function __construct($name, $task, $unit, $rate)
 	{
 		$this->name = $name;
 		$this->descr = $task;
 		$this->unit = $unit;
 		$this->rate = $rate;
 	}

	public static function Create($name, $descr, $rate)
	{
		//Called and stored in a session object
		try {

			$unit = WorkService::Get('SLP');
			//start here
			$sql = 'INSERT IGNORE INTO office_services (name, descr, unit_id, rate) VALUES ("'.$name.'", "'.$descr.'", '.$unit->unitId.', '.$rate.')';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM office_services WHERE name = "'.$name.'" ';
			$res =  DatabaseHandler::GetRow($sql);

			return new OfficeService($res['name'], $res['descr'], $unit, $res['rate']);

		} catch (Exception $e) {
			
		}


	}

	public static function Get($name)
	{
		//Called and stored in a session object
		try {
	 		
	 		$sql = 'SELECT * FROM office_services WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);
			$unit = WorkService::Get('SLP');//GetBySymbol -> $res['unit_id']

			return new OfficeService($res['name'], $res['descr'], $unit, $res['rate']);

		} catch (Exception $e) {
			
		}


	}

	public static function Delete()
    {
      try {
        $sql = 'DELETE FROM office_services WHERE name = "'.$name.'"';			
		DatabaseHandler::Execute($sql);
		return true;
      } catch (Exception $e) {
        return false;
      }

    }
}

class BillableService extends Service
{
	public $name;
	public $unit;

	function __construct($name, $unit)
 	{
 		$this->name = $name;
 		$this->unit = $unit;
 	}

	public static function Create($name)
	{
		//Called and stored in a session object
		try {

			$unit = WorkService::Get('BSV');
			//start here
			$sql = 'INSERT IGNORE INTO services (name, unit_id) VALUES ("'.$name.'", '.$unit->unitId.')';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM services WHERE name = "'.$name.'" ';
			$res =  DatabaseHandler::GetRow($sql);

			return new BillableService($res['name'], $unit);

		} catch (Exception $e) {
			return false;
		}


	}

	public static function Get($name)
	{
		try {
	 		
	 		$sql = 'SELECT * FROM services WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);
			$unit = WorkService::Get('BSV');//GetBySymbol -> $res['unit_id']

			return new BillableService($res['name'], $unit);

		} catch (Exception $e) {
			
		}
	}

	public static function GetAll()
	{
		try {
	 		
	 		$sql = 'SELECT * FROM services';
			$res =  DatabaseHandler::GetAll($sql);
			$unit = WorkService::Get('BSV');//GetBySymbol -> $res['unit_id']
			$services = [];
			foreach ($res as $service) {
				$services[] = new BillableService($service['name'], $unit);
			}
			return $services;

		} catch (Exception $e) {
			return false;
		}
	}

	public static function Delete($name)
    {
      try {
        $sql = 'DELETE FROM services WHERE name = "'.$name.'"';			
		DatabaseHandler::Execute($sql);
		return true;
      } catch (Exception $e) {
        return false;
      }

    }
}

class ProjectActivity
{
	public $id;
  	public $projectId;
  	public $service;
  	public $task;
  	public $instances;	
	public $requestDate;
	public $executionDate;
	public $stamp;
	public $status;

  	function __construct($id, $projectId, $service, $task, $instances, $requestDate, $executionDate = 0, $stamp, $status = 0)
	{
		$this->id = $id;
		$this->projectId = $projectId;		
		$this->service = $service;
		$this->task = $task;
		$this->instances = $instances;		
		$this->requestDate = $requestDate;
		$this->executionDate = $executionDate;
		$this->stamp = $stamp;
		$this->status = $status;
	}

	public function complete()
    {
      	try {
      		$datetime = new DateTime();
      		$sql = 'UPDATE project_activities SET date_executed = "'.$datetime->format('d/m/Y').'", status = 3 WHERE id = '.$this->id;
        	DatabaseHandler::Execute($sql);
        	$this->executionDate = $datetime->format('d/m/Y');
        	$this->status = 3;
      	} catch (Exception $e) {
        
      	}
    }

	public static function Create($projectId, $service, $task, $instances, $qlid)
    {
      try {
      	$datetime = new DateTime();
		$sql = 'INSERT IGNORE INTO project_activities (id, project_id, service, task, instances, date_requested, stamp, status) 
		VALUES ('.$qlid.', '.$projectId.', "'.$service.'", "'.$task.'", '.$instances.', "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM project_activities WHERE id = '.$qlid;
		$res =  DatabaseHandler::GetRow($sql);        
        return self::initialize($res);
      } catch (Exception $e) {
        
      }

    }

    public static function GetActivities($prid)
    {
      	try {
        	$sql = 'SELECT * FROM project_activities WHERE project_id = '.$prid;
			$res =  DatabaseHandler::GetAll($sql);
			$activities = array();
        	foreach ($res as $act) {
        		$activities[] = self::initialize($act);
        	}                
        	return $activities;

      	} catch (Exception $e) {
        
      	}

    }

    public static function GetActivity($id)
    {
      	try {
        	$sql = 'SELECT * FROM project_activities WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);             
        	return self::initialize($res);
      	} catch (Exception $e) {
        
      	}
    }

    private static function initialize($args)
  	{
     	$activity = new ProjectActivity($args['id'], $args['project_id'], $args['service'], $args['task'], $args['instances'], $args['date_requested'], $args['date_executed'], $args['stamp'], $args['status']);
      	return $activity;
  	}

  	public static function DiscardActivity($id)
    {
      	try {
      		$sql = 'UPDATE project_activities SET status = 7 WHERE id = '.$id;
        	DatabaseHandler::Execute($sql);
      	} catch (Exception $e) {
        
      	}
    }

    public static function UpdateActivityStatus($id, $status)
    {
      	try {
      		$sql = 'UPDATE project_activities SET status = '.$status.' WHERE id = '.$id;
        	DatabaseHandler::Execute($sql);
      	} catch (Exception $e) {
        
      	}
    }
}

class Project
{
  	public $id;
  	public $name;
  	public $location;
  	public $descr;
  	public $date;
	public $stamp;
	public $status;
	public $bal;
	public $client;
	public $quotations = [];
	public $activities = [];

  	function __construct($id, $name, $location, $descr, $date, $client, $stamp, $status, $bal = 0)
	{
		$this->id = $id;
		$this->name = $name;		
		$this->location = $location;
		$this->descr = $descr;
		$this->date = $date;
		$this->client = $client;
		$this->stamp = $stamp;
		$this->status = $status;
		$this->bal = floatval($bal);
	}

    public function importQuote($quoteId)
	{
		$quote = Quotation::GetQuotation($quoteId);

		$this->quotations[] = $quote;
	}

	public function credit($amount)
	{
		try {
			$newbal = $this->bal - $amount->amount;
	      	$sql = 'UPDATE projects SET balance = '.floatval($newbal).' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        $this->bal = $newbal;
	        return true;
	    } catch (Exception $e) {
	    	return false;	        
	    }
	}

	public function debit($amount)
	{
		try {
			$newbal = $this->bal + $amount->amount;
	      	$sql = 'UPDATE projects SET balance = '.floatval($newbal).' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        $this->bal = $newbal;
	        return true;
	    } catch (Exception $e) {
	        return false;
	    }
	}

	public function getActivities()
	{
		return $this->activities;
	}

	public function removeActivity($id)
	{
		foreach ($this->activities as $key => $activity) {
			if ($activity->id == $id) {
				QuotationLine::DiscardLine($activity->qlid);
				ProjectActivity::DiscardActivity($id);
				unset($this->activities[$key]);
				break;
			}
		}
	}

	public function authorize()
	{
		$this->activities = [];
		foreach ($this->quotations as $quote) {
			$quote->setProject($this->id);

			foreach ($quote->lineItems as $item) {
				$this->activities[] = ProjectActivity::Create($this->id, $item->itemName, $item->itemDesc, $item->quantity, $item->lineId);
			}
		}
		return true;
	}

	public static function GetProject($id)
  	{      	
  		try {
  			$sql = 'SELECT * FROM projects WHERE id = '.$id;
	        $res =  DatabaseHandler::GetRow($sql);        
	        return self::initialize($res);
  		} catch (Exception $e) {
  			return false;
  		}
  	}

  	public static function GetClientProjects($clientId)
  	{      	
  		$sql = 'SELECT * FROM projects WHERE client_id = '.$clientId;
        $res =  DatabaseHandler::GetAll($sql);
        $projects = [];
        foreach ($res as $project) {
          $projects[] = self::initialize($project);
        }
        return $projects;
  	}

  	public static function GetAllProjects()
  	{      	
  		$sql = 'SELECT * FROM projects';
        $res =  DatabaseHandler::GetAll($sql);
        $projects = [];
        foreach ($res as $project) {
          $projects[] = self::initialize($project);
        }
        return $projects;
  	}

  	public static function GetProjectExpenses()
  	{      	
  		$sql = 'SELECT * FROM projects';
        $res =  DatabaseHandler::GetAll($sql);
        $projects = [];
        foreach ($res as $project) {
          $projects[] = self::initialize($project);
        }
        return $projects;
  	}

  	public static function CurrentProjects()
  	{      	
  		$sql = 'SELECT * FROM projects WHERE status = 1';
        // Execute the query and return the results
        $res =  DatabaseHandler::GetAll($sql);
        $projects = array();
        foreach ($res as $project) {
          $projects[] = self::initialize($project);
        }
        return $projects;
  	}

  	private static function initialize($args)
  	{
  		$client = Client::GetClient($args['client_id']);
     	$project = new Project($args['id'], $args['name'], $args['location'], $args['descr'], $args['date'], $client, $args['stamp'], $args['status'], $args['balance']);

      	$project->quotations = Quotation::GetProjectQuotations($args['id'], $client);
      	$project->activities = ProjectActivity::GetActivities($args['id']);
      	
      	return $project;
  	}

  	public static function Create($name, $location, $descr, $clientId)
    {
      try {
      	$datetime = new DateTime();
        $sql = 'INSERT IGNORE INTO projects (name, location, date, descr, client_id, stamp, modified, status) 
        VALUES ("'.$name.'", "'.$location.'", "'.$datetime->format('d/m/Y').'", "'.$descr.'", '.$clientId.', '.$datetime->format('YmdHis').','.$datetime->format('YmdHis').', 0)';
        DatabaseHandler::Execute($sql);

        $sql = 'SELECT * FROM projects WHERE stamp = '.$datetime->format('YmdHis');
        $res =  DatabaseHandler::GetRow($sql);        
        return self::initialize($res);
      } catch (Exception $e) {
        return false;
      }
    }

	public static function Update($id, $name, $location, $status, $desc)
	{
		try {
			$datetime = new DateTime();
	      	$sql = 'UPDATE projects SET name = "'.$name.'", location = "'.$location.'", status = '.$status.', descr = "'.$desc.'", modified = '.$datetime->format('YmdHis').' WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        return self::GetProject($id);
	    } catch (Exception $e) {
	        
	    }
	}

	public static function Delete($id)
	{
		try {
			$sql = 'DELETE FROM projects WHERE id = '.$id;			
			DatabaseHandler::Execute($sql);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}

class WorkReport
{
	public $id;
  	public $projectId;
  	public $activities = [];
  	public $location;
  	public $personell = [];
  	public $report;	
	public $expense_voucher;
	public $date;
	public $stamp;
	public $status;

  	function __construct($id, $projectId, $location, $report, $date, $stamp, $status = 0)
	{
		$this->id = $id;
		$this->projectId = $projectId;
		$this->location = $location;
		$this->report = $report;
		$this->date = $date;
		$this->stamp = $stamp;
		$this->status = $status;
	}

	public function setExpensesVoucher($voucher){
		try {
	        $sql = 'UPDATE work_reports SET voucher_id = '.$voucher->id.' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        $this->expense_voucher = $voucher;
	        return true;
	    } catch (Exception $e) {
	        
	    }
	}

	public function load($activities, $personell){
		$activities = explode(',', $activities);
		$personell = explode(',', $personell);
		foreach ($activities as $actId) {
			$this->activities[] = ProjectActivity::GetActivity($actId);
		}

		/*foreach ($personell as $employee) {
			$this->personell[] = Employee::GetEmployee($employee);
		}*/
	}

	public function markActivities(){
		foreach ($this->activities as $activity) {
			$activity->complete();
		}
	}

	public static function Create($projectId, $activities, $status, $location, $personell, $report, $charges)
    {
	    try {
	      	$datetime = new DateTime();
			$sql = 'INSERT IGNORE INTO work_reports (project_id, activities, location, personell, report, date, stamp, status) 
			VALUES ('.$projectId.', "'.implode(",", $activities).'", "'.$location.'", "'.implode(",", $personell).'", "'.$report.'", "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
			DatabaseHandler::Execute($sql);

			foreach ($activities as $actid) {
				ProjectActivity::UpdateActivityStatus($actid, $status);
			}

			$sql = 'SELECT * FROM work_reports WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);
			$report = self::initialize($res);
			$report->markActivities();
			$voucher = ExpenseVoucher::Create($projectId, $report, $charges); 
	        $report->setExpensesVoucher($voucher);
	        return $report;
	    } catch (Exception $e) {
	        return false;
	    }

    }

    public static function GetReport($rid)
    {
      try {
        $sql = 'SELECT * FROM work_reports WHERE id = '.$rid;
		$res =  DatabaseHandler::GetRow($sql);               
        return self::initialize($res);

      } catch (Exception $e) {
        return false;
      }

    }

    public static function GetReports($prid)
    {
      try {
        $sql = 'SELECT * FROM work_reports WHERE project_id = '.$prid;
		$res =  DatabaseHandler::GetAll($sql);
		$activities = array();
        foreach ($res as $act) {
        	$activities[] = self::initialize($act);
        }                
        return $activities;

      } catch (Exception $e) {
        
      }

    }

    private static function initialize($args)
  	{
     	$activity = new WorkReport($args['id'], $args['project_id'], $args['location'], $args['report'], $args['date'], $args['stamp'], $args['status']);
      	$activity->load($args['activities'], $args['personell']);
      	return $activity;
  	}

  	public static function DeleteReport($id)
    {
      try {
      	$sql = 'DELETE FROM work_reports WHERE id = '.$id;
        DatabaseHandler::Execute($sql);
      } catch (Exception $e) {
        
      }
    }
}

class ExpenseItem
{
	//Refactor to ClaimItem
	public $id;
  	public $voucherId;
  	public $claimant;
  	public $description;
	public $ledger;
	public $claimed;
	public $adjusted;

  	function __construct($id, $voucherId, $claimantId, $description, $ledgerId, $claimed, $adjusted)
	{
		$this->id = $id;
		$this->claimant = Employee::GetEmployee($claimantId);
		$this->voucherId = $voucherId;
		$this->description = $description;
		$this->ledger = Ledger::GetLedger($ledgerId);
		$this->claimed = $claimed;
		$this->adjusted = $adjusted;
	}

	public function revoke(){
		//revoke
	}

	public static function Adjust($items){
		foreach ($items as $item) {
			try {
		        $sql = 'UPDATE expense_items SET adjusted = '.floatval($item['amount']).' WHERE id = '.intval($item['viid']);
		        DatabaseHandler::Execute($sql);
		    } catch (Exception $e) {
		        return false;
		        break;
		    }
		}
		return true;
	}

	public static function Create($vid, $claimantId, $descr, $ledgerId, $claimed)
    {
      try {
		$sql = 'INSERT INTO expense_items (voucher_id, claimant_id, descr, ledger_id, claimed, adjusted) 
		VALUES ('.$vid.', '.intval($claimantId).', "'.$descr.'", '.intval($ledgerId).', '.floatval($claimed).', '.floatval($claimed).')';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM expense_items WHERE voucher_id = '.$vid.' AND descr = "'.$descr.'"';
		$res =  DatabaseHandler::GetRow($sql); 
        return self::initialize($res);
        
      } catch (Exception $e) {
        
      }

    }

    public static function GetItems($vid)
    {
      try {
        $sql = 'SELECT * FROM expense_items WHERE voucher_id = '.$vid;
		$res =  DatabaseHandler::GetAll($sql);
		$vitems = array();
        foreach ($res as $item) {
        	$vitems[] = self::initialize($item);
        }                
        return $vitems;

      } catch (Exception $e) {
        
      }

    }

    private static function initialize($args)
  	{
     	$item = new ExpenseItem($args['id'], $args['voucher_id'], $args['claimant_id'], $args['descr'], $args['ledger_id'], $args['claimed'], $args['adjusted']);
      	return $item;
  	}

  	public static function DeleteItems($vid)
    {
      try {
      	$sql = 'DELETE FROM expense_items WHERE voucher_id = '.$vid;
        DatabaseHandler::Execute($sql);
      } catch (Exception $e) {
        
      }
    }
}

class ExpenseVoucher
{
	//Refactor to ClaimVoucher
	public $id;
  	public $projectId;
  	public $reportId;
  	public $voucherNo;
  	public $transactionId;
	public $date;
	public $stamp;
	public $status;
	public $total;
	public $items = [];
	//IMPLEMENTATION
  	function __construct($id, $projectId, $reportId, $voucher, $transactionId, $date, $stamp, $status = 0)
	{
		$this->id = $id;
		$this->projectId = $projectId;
		$this->reportId = $reportId;
		$this->voucherNo = $voucher;
		$this->transactionId = $transactionId;
		$this->date = $date;
		$this->stamp = $stamp;
		$this->status = $status;
	}

	public function loadItems(){
		$this->items = ExpenseItem::GetItems($this->id);
		$this->calculate();
	}

	public function addItem($vitem){
		$this->items[] = $vitem;
		$this->calculate();
	}

	public function calculate(){
		$total = 0;
		foreach ($this->items as $item) {
			$total = $total + $item->adjusted;
		}
		$this->total = $total;
	}

	public function authorize($txid){
		try {
		    $sql = 'UPDATE expense_vouchers SET status = 1, transaction_id = '.intval($txid).' WHERE id = '.$this->id;
		    DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
		    return false;
		}
	}
	//INTERFACE
	private static function initialize($args)
  	{
     	$voucher = new ExpenseVoucher($args['id'], $args['project_id'], $args['report_id'], $args['voucher_no'], $args['transaction_id'], $args['date'], $args['stamp'], $args['status']);
      	$voucher->loadItems();
      	return $voucher;
  	}

	public static function Create($projectId, $report, $charges)
    {
      try {
      	$datetime = new DateTime();
		$sql = 'INSERT INTO expense_vouchers (project_id, report_id, date, stamp, status) 
		VALUES ('.$projectId.', '.$report->id.', "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM expense_vouchers WHERE stamp = '.$datetime->format('YmdHis');
		$res =  DatabaseHandler::GetRow($sql);        
        $voucher = new ExpenseVoucher($res['id'], $res['project_id'], $res['report_id'], $res['voucher_no'], 0, $res['date'], $res['stamp'], $res['status']);
        foreach ($charges as $charge) {
        	$voucher->addItem(ExpenseItem::Create($res['id'], $charge['claimant'], $charge['description'], $charge['category'], $charge['amount']));
        }
        return $voucher;
        
      } catch (Exception $e) {
        
      }

    }

    public static function CreatePartyExpense($party, $amount, $account, $voucher, $description)
    {
      try {
      	$datetime = new DateTime();
		$sql = 'INSERT INTO expense_vouchers (project_id, party_id, voucher_no, date, stamp, status) 
		VALUES (0, '.$party.', "'.$voucher.'", "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM expense_vouchers WHERE stamp = '.$datetime->format('YmdHis');
		$res =  DatabaseHandler::GetRow($sql);

        $voucher = new ExpenseVoucher($res['id'], $res['project_id'], $res['report_id'], $res['voucher_no'], 0, $res['date'], $res['stamp'], $res['status']);
        $voucher->addItem(ExpenseItem::Create($res['id'], 0, $description, $account, $amount));

        return $voucher;
      } catch (Exception $e) {
        return false;
      }

    }

    public static function CreateSupplierProjectExpense($party, $scope, $amount, $account, $voucher, $description)
    {
      try {
      	$datetime = new DateTime();
		$sql = 'INSERT INTO expense_vouchers (project_id, party_id, voucher_no, date, stamp, status) 
		VALUES ('.$scope.', '.$party.', "'.$voucher.'", "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM expense_vouchers WHERE stamp = '.$datetime->format('YmdHis');
		$res =  DatabaseHandler::GetRow($sql);

        $voucher = new ExpenseVoucher($res['id'], $res['project_id'], $res['report_id'], $res['voucher_no'], 0, $res['date'], $res['stamp'], $res['status']);
        $voucher->addItem(ExpenseItem::Create($res['id'], $scope, $description, $account, $amount));

        return $voucher;
      } catch (Exception $e) {
        return false;
      }

    }

    public static function GetVoucher($vid)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE id = '.$vid;
		$res =  DatabaseHandler::GetRow($sql);               
        return self::initialize($res);

      } catch (Exception $e) {
        return false;
      }

    }

    public static function GetProjectVouchers($prid)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE project_id = '.$prid;
		$res =  DatabaseHandler::GetAll($sql);
		$vouchers = array();
        foreach ($res as $act) {
        	$vouchers[] = self::initialize($act);
        }                
        return $vouchers;

      } catch (Exception $e) {
        
      }

    }

    public static function GetPartyVouchers($party_id)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE party_id = '.$party_id;
		$res =  DatabaseHandler::GetAll($sql);
		$vouchers = array();
        foreach ($res as $act) {
        	$vouchers[] = self::initialize($act);
        }                
        return $vouchers;

      } catch (Exception $e) {
        
      }

    }

    public static function GetProjectClaims($prid)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE status = 0 AND project_id = '.$prid;
		$res =  DatabaseHandler::GetAll($sql);
		$vouchers = array();
        foreach ($res as $act) {
        	$vouchers[] = self::initialize($act);
        }                
        return $vouchers;

      } catch (Exception $e) {
        
      }

    }

    public static function GetUnsettledVouchers($prid)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE status = 0';
		$res =  DatabaseHandler::GetAll($sql);
		$vouchers = array();
        foreach ($res as $act) {
        	$vouchers[] = self::initialize($act);
        }                
        return $vouchers;

      } catch (Exception $e) {
        
      }

    }

  	public static function DeleteVoucher($id)
    {
      try {
      	$sql = 'DELETE FROM expense_vouchers WHERE id = '.$id;
        DatabaseHandler::Execute($sql);
      } catch (Exception $e) {
        
      }
    }
}

class DocumentType
{
	public $name;

	function __construct($name)
 	{
 		$this->name = $name;
 	}

	public static function Create($name)
	{
		//Called and stored in a session object
		try {
			$sql = 'INSERT IGNORE INTO doctypes (name) VALUES ("'.$name.'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM doctypes WHERE name = "'.$name.'" ';
			$res =  DatabaseHandler::GetRow($sql);

			return new DocumentType($res['name']);

		} catch (Exception $e) {
			return false;
		}


	}

	public static function Get($name)
	{
		try {
	 		
	 		$sql = 'SELECT * FROM doctypes WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);

			return new DocumentType($res['name']);

		} catch (Exception $e) {
			
		}
	}

	public static function GetAll()
	{
		try {
	 		
	 		$sql = 'SELECT * FROM doctypes';
			$res =  DatabaseHandler::GetAll($sql);
			$doctypes = [];
			foreach ($res as $dtype) {
				$doctypes[] = new DocumentType($dtype['name']);
			}
			return $doctypes;

		} catch (Exception $e) {
			return false;
		}
	}

	public static function Delete($name)
    {
      try {
        $sql = 'DELETE FROM doctypes WHERE name = "'.$name.'"';			
		DatabaseHandler::Execute($sql);
		return true;
      } catch (Exception $e) {
        return false;
      }

    }
}

class LandsDocument
{
	public $id;
	public $client;
	public $name;
	public $type;
	public $serial;
	public $parcel;
	public $details;
	public $status;
	public $lastUpdated;
	public $timestamp;
	public $file;
	public $thumbnail;

	function __construct($id, $clientid, $name, $type, $serial, $parcel, $details, $status, $lastUpdated, $timestamp, $file, $thumbnail)
 	{
 		$this->id = $id;
 		$this->client = Client::GetClient($clientid);
 		$this->name = $name;
 		$this->type = DocumentType::Get($type);
 		$this->serial = $serial;
 		$this->parcel = $parcel;
 		$this->details = $details;
 		$this->status = $status;
 		$this->lastUpdated = $lastUpdated;
 		$this->timestamp = $timestamp;
 		$this->file = $file;
 		$this->thumbnail = $thumbnail;
 	}

 	private static function initialize($args)
    {
    	return new LandsDocument($args['id'], $args['client_id'], $args['name'], $args['type'], $args['serial'], $args['parcel'], $args['details'], $args['status'], $args['last_updated'], $args['stamp'], $args['file'], $args['thumbnail']);
    }

	public static function Create($clientid, $name, $type, $serial, $parcel, $details, $status)
	{
		//Called and stored in a session object
		try {
			$sql = 'SELECT * FROM land_docs WHERE serial = "'.$serial.'" AND type = "'.$type.'"';
	  	    // Execute the query and return the results
	  	    $res =  DatabaseHandler::GetRow($sql);
	  	    if (!empty($res['id'])) {
	  	    	Logger::Log('LandsDocument', 'Exists', 'A document with the serial: '.$serial.' and of type:'.$type.' already exists');
	  	    	return false;
	  	    }else{
	  	    	$datetime = new DateTime();
				$sql = 'INSERT INTO land_docs (client_id, name, type, serial, parcel, details, status, last_updated, stamp, thumbnail) VALUES ('.$clientid.', "'.$name.'", "'.$type.'", "'.$serial.'", "'.$parcel.'", "'.$details.'", "'.$status.'", "'.$datetime->format('d/m/Y H:ia').'", '.$datetime->format('YmdHis').', "dragdrop.png")';
		 		DatabaseHandler::Execute($sql);
				return true;
			}
		} catch (Exception $e) {
			return false;
		}


	}

	public static function Update($id, $client, $name, $type, $serial, $parcel, $details, $status, $file, $thumbnail)
  	{      	
  		try {
  			$datetime = new DateTime();
	        $sql = 'UPDATE land_docs SET client_id = '.$client.', name = "'.$name.'", type = "'.$type.'", serial = "'.$serial.'", parcel = "'.$parcel.'", details = "'.$details.'", status = "'.$status.'", last_updated = "'.$datetime->format('d/m/Y H:ia').'", stamp = '.$datetime->format('YmdHis').', file = "'.$file.'", thumbnail = "'.$thumbnail.'" WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        return false;
	    }
  	}

	public static function Get($id)
	{
		try {	 		
	 		$sql = 'SELECT * FROM land_docs WHERE id = '.$id.'';
			$res =  DatabaseHandler::GetRow($sql);

			return self::initialize($res);

		} catch (Exception $e) {
			
		}
	}

	public static function GetAll()
	{
		try {
	 		
	 		$sql = 'SELECT * FROM land_docs';
			$res =  DatabaseHandler::GetAll($sql);
			$land_docs = [];
			foreach ($res as $document) {
				$land_docs[] = self::initialize($document);
			}
			return $land_docs;

		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetClientDocuments($cid)
	{
		try {
	 		
	 		$sql = 'SELECT * FROM land_docs WHERE client_id ='.intval($cid);
			$res =  DatabaseHandler::GetAll($sql);
			$land_docs = [];
			foreach ($res as $document) {
				$land_docs[] = self::initialize($document);
			}
			return $land_docs;

		} catch (Exception $e) {
			return false;
		}
	}

	public static function Delete($id)
    {
      try {
        $sql = 'DELETE FROM land_docs WHERE id = "'.$id.'"';			
		DatabaseHandler::Execute($sql);
		return true;
      } catch (Exception $e) {
        return false;
      }

    }
}
?>