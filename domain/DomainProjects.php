<?php

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
      		$sql = 'UPDATE project_activities SET date_executed = "'.$datetime->format('d/m/Y').'", status = 1 WHERE id = '.$this->id;
        	DatabaseHandler::Execute($sql);
        	$this->executionDate = $datetime->format('d/m/Y');
        	$this->status = 1;
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
      		$sql = 'UPDATE project_activities SET status = 2 WHERE id = '.$id;
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

  	function __construct($id, $name, $location, $descr, $date, $client, $stamp, $status = 0, $bal = 0)
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

	public static function Create($projectId, $activities, $location, $personell, $report, $charges)
    {
	    try {
	      	$datetime = new DateTime();
			$sql = 'INSERT IGNORE INTO work_reports (project_id, activities, location, personell, report, date, stamp, status) 
			VALUES ('.$projectId.', "'.implode(",", $activities).'", "'.$location.'", "'.implode(",", $personell).'", "'.$report.'", "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
			DatabaseHandler::Execute($sql);

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

?>