<?php

//require_once('Services.php');

class Employee extends Party
{
  	public $creditRating;
  	public $accounts = array();
  	public $balance;
  	public $gender;
  	public $department;
  	public $position;
  	public $salary;
  	//public $stockAccountNumber;
  	function __construct($id, $name, $telephone, $email, $address, $gender, $department, $position, $salary, $bal)
  	{
  		$type = new PartyType('Employee');
  		$this->balance = new Money(floatval($bal), Currency::Get('KES'));
  		$this->salary = new Money(floatval($salary), Currency::Get('KES'));
  		$this->gender = $gender;
  		$this->department = $department;
  		$this->position = $position;
  		parent::__construct($type, $id, $name, $telephone, $email, $address);
  	}

    private function save()
    {
      try {
        $sql = 'SELECT * FROM employees WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
	    // Execute the query and return the results
	    $res =  DatabaseHandler::GetRow($sql);
	    if (!empty($res['id'])) {
	    	return false;
	    }else{
	    	$sql = 'INSERT INTO employees (type, name, telephone, address, email, gender, department, position, salary, balance, status) 
	        VALUES ("'.$this->type->name.'", "'.$this->name.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'", "'.$this->gender.'", "'.$this->department.'", "'.$this->position.'", '.floatval($this->salary).', '.$this->balance->amount.', 1)';
	        DatabaseHandler::Execute($sql);
	    }
        return true;
      } catch (Exception $e) {
        return false;
      }
    }

    public function registerUser($username, $password, $role)
  	{
  		try {
	        $sql = 'UPDATE employees SET identification = "'.$username.'", password = sha1("'.$password.'"), role = "'.$role.'", access = 1 WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        
	        $sql2 = 'SELECT * FROM employees WHERE email = "'.$this->email.'"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
	    } catch (Exception $e) {
	        return false;
	    }
  	}

    private static function initializeEmployee($args)
    {
      	$party = new Employee($args['id'], $args['name'], $args['telephone'], $args['email'], $args['address'], $args['gender'], $args['department'], $args['position'], $args['salary'], $args['balance']);
        return $party;
    }

    public static function Create($name, $telephone, $email, $address, $gender, $department, $position, $salary)
  	{
  		$type = new PartyType('Employee');
		$employee = new Employee($type, $name, $telephone, $email, $address, $gender, $department, $position, $salary, 0);
		
		if ($employee->save()) {
			return $employee;
		}
		return false;
  	}

  	public static function Update($id, $name, $telephone, $email, $address, $gender, $department, $position, $salary)
  	{
  		try {
	        $sql = 'UPDATE employees SET name = "'.$name.'", telephone = "'.$telephone.'", email = "'.$email.'", address = "'.$address.'", gender = "'.$gender.'", department = "'.$department.'", position = "'.$position.'", salary = '.floatval($salary).' WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        
	    }
  	}  	

	public static function GetEmployee($id)
    {
        $sql = 'SELECT * FROM employees WHERE id = '.intval($id).' AND type = "Employee"';
        $res =  DatabaseHandler::GetRow($sql);
        return self::initializeEmployee($res);
    }

    public static function Delete($id)
    {
        try {
        	$sql = 'DELETE FROM employees WHERE id = '.intval($id);
        	$res =  DatabaseHandler::Execute($sql);
        	return true;
        } catch (Exception $e) {
        	return false;
        }       
    }

    public static function GetAllEmployees()
    {
        $sql = 'SELECT * FROM employees WHERE type = "Employee" AND status = 1';
        // Execute the query and return the results
        $res =  DatabaseHandler::GetAll($sql);
        $parties = array();
        foreach ($res as $item) {
          $parties[] = self::initializeEmployee($item);
        }
        return $parties;
    }

    public static function GetExEmployees()
    {
        $sql = 'SELECT * FROM employees WHERE type = "Employee" AND status = 0';
        // Execute the query and return the results
        $res =  DatabaseHandler::GetAll($sql);
        $parties = array();
        foreach ($res as $item) {
          $parties[] = self::initializeEmployee($item);
        }
        return $parties;
    }
}

class Role
{
  public $id;
  public $name;
  //public $views = [];
  public $presentation;
  public $operations = [];

  function __construct($id, $name, $views, $operations)
	{
		$this->id = $id;
		$this->name = $name;		
		//$this->views = View::GetViews(explode(',', $views));
		$this->operations = explode(',', $operations);
		$this->prepareViews($views);
	}

	private function prepareViews($views)
  {
     	$views = View::GetViews(explode(',', $views));
     	$hierarchy = [];//some sort of hierarchical sparse array, thank God it worked.
     	foreach ($views as $view) {
     		$hierarchy[$view->moduleId]['views'][] = $view;
     	}

     	foreach ($hierarchy as $key => $section) {
     		$module = Module::GetModule(intval($key));
     		$hierarchy[$key]['name'] = $module->name;
     		$hierarchy[$key]['logo'] = $module->logo;
     	}

     	$this->presentation = $hierarchy;
  }

  private static function initialize($args)
  {
    $role = new Role($args['id'], $args['name'], $args['views'], $args['operations']);      	
    return $role;
  }

	public static function GetRoles()
  	{      	
  		$sql = 'SELECT * FROM roles';
        $res =  DatabaseHandler::GetAll($sql);
        $users = [];
        foreach ($res as $user) {
          $users[] = self::initialize($user);
        }
        return $users;
  	}

	public static function GetRole($id)
  {      	
  		try {
  			$sql = 'SELECT * FROM roles WHERE id = '.$id;
	      $res =  DatabaseHandler::GetRow($sql);      
	      return self::initialize($res);
  		} catch (Exception $e) {
  			return false;
  		}
  }

  public static function Create($name, $views)
  {
    try {
      $sql = 'INSERT INTO roles (name, views) VALUES ("'.$name.'", "'.implode(',', $views).'")';
			DatabaseHandler::Execute($sql);
	        
	    $sql2 = 'SELECT * FROM roles WHERE name = "'.$name.'" AND views = "'.implode(',', $views).'"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
	  } catch (Exception $e) {
	    return false;
	  }
  }

  public static function Update($id, $name, $views)
	{
		try {
			$sql = 'UPDATE roles SET name = "'.$name.'", views = "'.implode(',', $views).'" WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function Delete($id)
	{
		if ($id != 1) {
			try {
				$sql = 'DELETE FROM roles WHERE id = '.$id;
		        DatabaseHandler::Execute($sql);
				return true;
			} catch (Exception $e) {
				return false;
			}
		}else{
			return false;
		}		
	}
}

class PayrollVoucher
{
	public $id;
	public $type;
	public $transactionId;
	public $party;
	public $date;
	public $description;
	public $amount;
	public $status;
	public $user;
	public $effect;

	function __construct($id, $txid, $type, $partyid, $date, $amount, $descr, $status, $user, $effect)
	{
		$this->id = $id;
		$this->transactionId = $txid;
		$this->type = $type;
		$this->party = Employee::GetEmployee($partyid);
		$this->date = $date;
		$this->amount = floatval($amount);
		$this->description = $descr;
		$this->status = $status;
		$this->user = $user;
		$this->effect = $effect;
		try {
			$sql = 'SELECT * FROM general_ledger_entries WHERE transaction_id = '.intval($this->transactionId).' AND account_no = '.intval($partyid);
			$res =  DatabaseHandler::GetRow($sql);
			$this->party->balance = new Money(floatval($res['balance']), Currency::Get('KES'));
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}		
	}

	private static function initialize($args){
		$payment =  new PayrollVoucher($args['id'], $args['tx_id'], $args['type'], $args['party_id'], $args['datetime'], $args['amount'], $args['description'], $args['status'], $args['user'], $args['effect']);
		return $payment;
	}

	public static function GetVoucher($id)
	{
		try {
			$sql = 'SELECT * FROM payroll_entries WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			return self::initialize($res);
		} catch (Exception $e) {
			Logger::Log('PayrollVoucher', 'Exception', $e->getMessage());
		}		
	}

	public static function GetAdvanceVoucher($id)
	{
		try {
			$sql = 'SELECT * FROM advances_and_loans WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			return self::initialize($res);
		} catch (Exception $e) {
			Logger::Log('PayrollVoucher', 'Exception', $e->getMessage());
		}		
	}

	public static function GetVoucherFromTx($txid)
	{
		try {
			$sql = 'SELECT * FROM payroll_entries WHERE tx_id = '.$txid;
			$res =  DatabaseHandler::GetRow($sql);
			return self::initialize($res);
		} catch (Exception $e) {
			Logger::Log('PayrollVoucher', 'Missing', 'Missing payroll voucher for transaction id:'.$txid);
			Logger::Log('PayrollVoucher', 'Exception', $e->getMessage());
		}			
	}
}

class AdvancesAndLoans extends TransactionType
{
	function __construct($name)
	{
		parent::__construct($name);
	}

	public static function Lend($ledgerId, $name)
	{
		$txtype = new AdvancesAndLoans($name);
		
		$txtype->drAccounts[] = Account::GetAccount('LOANS AND ADVANCES');
		$txtype->drRatios[] = 1;
		$txtype->crAccounts[] = Account::GetLedger($ledgerId);
		$txtype->crRatios[] = 1;

		return $txtype;
	}

	public static function Recover($ledgerId, $name)
	{
		$txtype = new AdvancesAndLoans($name);
		
		$txtype->crAccounts[] = Account::GetAccount('LOANS AND ADVANCES');
		$txtype->crRatios[] = 1;
		$txtype->drAccounts[] = Account::GetLedger($ledgerId);
		$txtype->drRatios[] = 1;

		return $txtype;
	}
}

class EmployeePayment extends TransactionType
{
	function __construct($ledgerId, $partyid, $name)
	{
		parent::__construct($name);
		
		$this->drAccounts[] = Account::GetAccountByNo($partyid, 'employees', 'PAYROLL');
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetLedger($ledgerId);
		$this->crRatios[] = 1;
	}
}

class BenefitAddition extends TransactionType
{
	function __construct($ledgerId, $employeeId, $name)
	{
		parent::__construct("Employee Benefit - ".$name);
		
		$this->crAccounts[] = Account::GetAccountByNo($employeeId, 'employees', 'PAYROLL');
		$this->crRatios[] = 1;
		$this->drAccounts[] = Account::GetLedger($ledgerId);
		$this->drRatios[] = 1;
	}
}

class BenefitDeduction extends TransactionType
{
	function __construct($ledgerId, $employeeId, $type)
	{
		parent::__construct("Employee Benefit - ".$type);
		
		$this->drAccounts[] = Account::GetAccountByNo($employeeId, 'employees', 'PAYROLL');
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetLedger($ledgerId);
		$this->crRatios[] = 1;
	}
}

class PayrollTX extends FinancialTransaction
{
	public $id;
	public $partyId;
	public $month;
	public $type;
	public $ledgerId;
	public $effect;
	public $table;

	function __construct($id, $partyId, $month, $amount, $type, $effect, $ledgerId, $descr, $txtype, $table)
	{
		$this->id = $id;
		$this->partyId = $partyId;
		$this->month = $month;		
		$this->ledgerId = $ledgerId;
		$this->type = $type;
		$this->effect = $effect;
		parent::__construct(new Money(floatval($amount), Currency::Get('KES')), $descr, $txtype);
		$this->table = $table;
		$this->update();
	}

	public function update()
	{
		try {
	        $sql = 'UPDATE '.$this->table.' SET tx_id = '.$this->transactionId.', user = "'.SessionManager::GetUsername().'", datetime = "'.$this->date.'", stamp = '.$this->stamp.' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        return false;
	    }
	}

	public function post()
	{
		if ($this->prepare()) {
			$voucher = TransactionProcessor::ProcessPayrollTx($this);
			
			if ($voucher) {
				$voucher->status = 1;
				$this->status = 1;
				$this->updateStatus();
				return $voucher;
			}else{
				return false;
			}
		}
	}

	private function prepare()
	{
		for ($i=0; $i < count($this->transactionType->drAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->drRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->drAccounts[$i], $amount, $this->date, 'dr'));
		}

		for ($i=0; $i < count($this->transactionType->crAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->crRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->crAccounts[$i], $amount, $this->date, 'cr'));
		}

		return true;
	}

	private function updateStatus()
	{
		try {

			$sql = 'UPDATE '.$this->table.' SET status = '.$this->status.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);	 		

		} catch (Exception $e) {
			return false;
		}
	}

	public static function Initialize($args, $txtype, $table)
	{
		$tx =  new PayrollTX($args['id'], $args['party_id'], $args['month'], $args['amount'], $args['type'], $args['effect'], $args['ledger_id'], $args['description'], $txtype, $table);
		return $tx;
	}
}

class Payroll
{
	public static $_nssfRates;
	public static $_nhifRates;
	public static $_paye;
	public $month;
	public $slips = [];
	public $status;

	function __construct($month)
	{
		$this->month = $month;
	}

	public function addPayslip(PaySlip $slip)
	{
		$this->slips[] = $slip;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public static function PreviewPayroll($month)
	{
		$payroll = new Payroll($month);

		try {
			$sql = 'SELECT * FROM payslips WHERE month = "'.$month.'"';
			$entries =  DatabaseHandler::GetAll($sql);
			
			if (count($entries) > 0) {
				$payroll->setStatus('COMMITED');
			}else{
				$payroll->setStatus('UNPROCESSED');
			}
		} catch (Exception $e) {
			
		}

		$employees = Employee::GetAllEmployees();
		
		foreach ($employees as $employee) {

			$slip = new PaySlip($employee, $month);

			try {
				if ($payroll->status == "UNPROCESSED") {
					$sql = 'SELECT * FROM payroll_entries WHERE party_id = '.$employee->id.' AND status <> 2 AND type <> "Salary Payment" ORDER BY id';
				}else{
					$sql = 'SELECT * FROM payroll_entries WHERE party_id = '.$employee->id.' AND month = "'.$month.'" AND status = 2 ORDER BY id';
				}
				$entries =  DatabaseHandler::GetAll($sql);

				foreach ($entries as $entry) {
					if ($entry['type'] != 'Basic Salary' && $entry['amount'] != 0.00) {
						$slip->includeEntry($entry['type'], $entry['effect'], $entry['amount']);
					}elseif ($payroll->status != "UNPROCESSED" && $entry['type'] == 'Basic Salary') {
						$slip->overrideSalary($entry['amount']);
					}
				}

				$sql2 = 'SELECT * FROM advances_and_loans WHERE party_id = '.$employee->id.' and type = "Salary Advance" ORDER BY id DESC LIMIT 0,1';
				$entry =  DatabaseHandler::GetRow($sql2);

				if (isset($entry) && $entry['balance'] != 0) {
					$slip->includeEntry('Salary Advance', 'dr', $entry['balance']);
				}

			} catch (Exception $e) {
				//Logger::Log('Payroll', 'Failed', 'Allowance for employee id: '.$employee->id.' for '.$month.'could not be posted');
				return false;
			}

			$slip->compile();
			
			$payroll->addPayslip($slip);
		}

		return $payroll;
	}

	public static function CommitPayroll($month)
	{
		$payroll = new Payroll($month);

		$employees = Employee::GetAllEmployees();
		
		foreach ($employees as $employee) {

			$slip = new PaySlip($employee, $month);

			try {
				$d = explode('/', $month);

				$ustamp = $d[1].$d[0].'31239999' + 1;
				$lstamp = $d[1].$d[0].'00999999' + 1;

				self::PostSalary($employee->id, $employee->salary->amount, $month);

				self::RecoverAdvance($employee->id, $month);
	
				//stamp >= '.$lstamp.' AND stamp <= '.$ustamp.'
		 		$sql = 'SELECT * FROM payroll_entries WHERE party_id = '.$employee->id.' AND status = 1 ORDER BY type ASC, id DESC';
				$entries =  DatabaseHandler::GetAll($sql);

				foreach ($entries as $entry) {					
					if ($entry['type'] != 'Basic Salary') {
						$slip->includeEntry($entry['type'], $entry['effect'], $entry['amount']);
					}

					$sql = 'UPDATE payroll_entries SET status = 2, month = "'.$month.'" WHERE id = '.$entry['id'];
					DatabaseHandler::Execute($sql);
				}

			} catch (Exception $e) {
				//Logger::Log('Payroll', 'Failed', 'Allowance for employee id: '.$employee->id.' for '.$month.'could not be posted');
				return false;
			}

			$slip->compile();

			$slip->commit();

			$payroll->addPayslip($slip);
		}

		$payroll->setStatus('COMMITED');

		return $payroll;
	}

	public static function PostSalary($empid, $amount, $month)
	{
		try {
			$employee = Employee::GetEmployee($empid);

			$date = new DateTime();
			$day = $date->format('d/m/Y');
	
			$descr = "Salary posted for ".$employee->name." on ".$day;

		    $ledger = Account::GetAccount('SALARY/WAGES ACCOUNT');

			$sql = 'INSERT INTO payroll_entries (party_id, month, type, effect, amount, ledger_id,description) VALUES 
			('.$employee->id.', "'.$month.'", "Basic Salary", "cr", '.floatval($amount).', '.$ledger->ledgerId.', "'.$descr.'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM payroll_entries WHERE party_id = '.$empid.' ORDER BY id DESC LIMIT 0,1';
			$entry =  DatabaseHandler::GetRow($sql2);

			//return new PayrollTX($payment, 'Salary Payment');
			$txtype = new BenefitAddition($ledger->ledgerId, $employee->id, 'Basic Salary');

			$tx = PayrollTX::Initialize($entry, $txtype, 'payroll_entries');
			$tx->post();
		} catch (Exception $e) {
			Logger::Log('Payroll', 'Failed', 'Basic Salary for employee id: '.$employee->id.' for '.$month.'could not be posted');
		}	
	}

	public static function PostAllowance($empid, $date, $ledger, $amount, $descr)
	{
		try {
			$employee = Employee::GetEmployee($empid);
	
			$descr = "Allowance posted for ".$descr." on ".$date;

			$d = explode('/', $date);
		    $month = $d[1].'/'.$d[2];

		    $ledger = Account::GetAccount('STAFF ALLOWANCES');

			$sql = 'INSERT INTO payroll_entries (party_id, month, type, effect, amount, ledger_id,description) VALUES 
			('.$employee->id.', "'.$month.'", "Allowance", "cr", '.floatval($amount).', '.$ledger->ledgerId.', "'.$descr.'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM payroll_entries WHERE party_id = '.$empid.' ORDER BY id DESC LIMIT 0,1';
			$entry =  DatabaseHandler::GetRow($sql2);

			//return new PayrollTX($payment, 'Salary Payment');
			$txtype = new BenefitAddition($ledger->ledgerId, $employee->id, 'Allowance');

			return PayrollTX::Initialize($entry, $txtype, 'payroll_entries');
		} catch (Exception $e) {
			Logger::Log('Payroll', 'Failed', 'Allowance for employee id: '.$employee->id.' for '.$month.'could not be posted');
			return false;
		}	
	}

	public static function PostOvertime($empid, $date, $rate, $hours, $descr)
	{
		try {
			$employee = Employee::GetEmployee($empid);
	
			$descr = "Overtime posted on ".$date." for ".$descr.". Hours: ".$hours.", Rate: ".$rate;

			$amount = $hours*$rate;

			$d = explode('/', $date);
		    $month = $d[1].'/'.$d[2];

		    $ledger = Account::GetAccount('Overtime');

			$sql = 'INSERT INTO payroll_entries (party_id, month, type, effect, amount, ledger_id,description) VALUES 
			('.$employee->id.', "'.$month.'", "Overtime", "cr", '.floatval($amount).', '.$ledger->ledgerId.', "'.$descr.'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM payroll_entries WHERE party_id = '.$empid.' ORDER BY id DESC LIMIT 0,1';
			$entry =  DatabaseHandler::GetRow($sql2);

			//return new PayrollTX($payment, 'Salary Payment');
			$txtype = new BenefitAddition($ledger->ledgerId, $employee->id, 'Overtime');

			return PayrollTX::Initialize($entry, $txtype, 'payroll_entries');

		} catch (Exception $e) {
			Logger::Log('Payroll', 'Failed', 'Overtime for employee id: '.$employee->id.' for '.$date.'could not be posted');
			return false;
		}
	}

	public static function PaySalary($empid, $slipid, $ledger, $mode, $voucher)
	{
		try {
			$payslip = PaySlip::GetSlip($slipid);
	
			$descr = "Salary remittance for ".$payslip->month;

			$sql = 'INSERT INTO payroll_entries (party_id, month, type, effect, amount, ledger_id, mode, voucher_no, description) VALUES 
			('.$payslip->employee->id.', "'.$payslip->month.'", "Salary Payment", "dr", '.$payslip->netpay.', '.$ledger.', "'.$mode.'", "'.$voucher.'", "'.$descr.'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM payroll_entries WHERE party_id = '.$empid.' ORDER BY id DESC LIMIT 0,1';
			$entry =  DatabaseHandler::GetRow($sql2);

			//return new PayrollTX($payment, 'Salary Payment');
			$payslip->clear();

			$txtype = new EmployeePayment($ledger, $payslip->employee->id, 'Salary Payment');

			return PayrollTX::Initialize($entry, $txtype, 'payroll_entries');

		} catch (Exception $e) {
			Logger::Log('Payroll', 'Failed', 'Salary payment for payslip no: '.$slipid.' could not be committed');
			return false;
		}
	}

	public static function GiveAdvance($empid, $date, $amount, $ledger, $mode, $voucher, $desc)
	{
		try {
			$employee = Employee::GetEmployee($empid);

			$descr = "Salary advanced on ".$date." for ".$desc;

			$d = explode('/', $date);
		    $month = $d[1].'/'.$d[2];

		    $sql2 = 'SELECT * FROM advances_and_loans WHERE party_id = '.$empid.' and type = "Salary Advance" ORDER BY id DESC LIMIT 0,1';
			$entry =  DatabaseHandler::GetRow($sql2);

			if (!empty($entry)) {
				$bal = $entry['balance'];
			}else{
				$bal = 0.00;
			}

			$newbal = $bal + floatval($amount);

			$sql = 'INSERT INTO advances_and_loans (party_id, month, type, effect, amount, ledger_id, mode, voucher, balance, description) VALUES 
			('.$employee->id.', "'.$month.'", "Salary Advance", "dr", '.$amount.', '.$ledger.', "'.$mode.'", "'.$voucher.'", '.$newbal.', "'.$descr.'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM advances_and_loans WHERE party_id = '.$empid.' and type = "Salary Advance" ORDER BY id DESC LIMIT 0,1';
			$entry =  DatabaseHandler::GetRow($sql2);

			//return new PayrollTX($payment, 'Salary Payment');
			$txtype = AdvancesAndLoans::Lend($ledger, 'Salary Advance');

			Logger::Log('Payroll', 'Passed', 'Salary for employee id: '.$employee->id.' for '.$month.' advanced');

			return PayrollTX::Initialize($entry, $txtype, 'advances_and_loans');

		} catch (Exception $e) {
			Logger::Log('Payroll', 'Failed', 'Salary for employee id: '.$employee->id.' for '.$month.' could not be advanced');
			return false;
		}
	}

	public static function RecoverAdvance($empid, $month)
	{
		try {
			$employee = Employee::GetEmployee(intval($empid));

			$descr = "Advance recovered from ".$month." salary.";

			//$d = explode('/', $date);
		    //$month = $d[1].'/'.$d[2];

		    $sql2 = 'SELECT * FROM advances_and_loans WHERE party_id = '.$empid.' and type = "Salary Advance" ORDER BY id DESC LIMIT 0,1';
			$entry =  DatabaseHandler::GetRow($sql2);

			if (!empty($entry)) {
				$amount = $entry['balance'];
			}else{
				$amount = 0.00;
			}

			$amount = floatval($amount);

	 		if ($amount != 0.00) {
	 			$ledger = Account::GetAccount('PAYROLL');

				$sql = 'INSERT INTO advances_and_loans (party_id, month, type, effect, amount, ledger_id, balance, description) VALUES 
				('.$employee->id.', "'.$month.'", "Salary Advance", "cr", '.$amount.', '.$ledger->ledgerId.', 0.00, "Advance recovered from '.$month.' salary.")';
		 		DatabaseHandler::Execute($sql);

		 		$sqlb = 'SELECT * FROM payroll_entries WHERE party_id = '.$empid.' ORDER BY id DESC LIMIT 0,1';
				$latest =  DatabaseHandler::GetRow($sqlb);

		 		$ladgerac = Account::GetAccount('LOANS AND ADVANCES');

	 			$sqla = 'INSERT INTO payroll_entries (party_id, month, type, effect, amount, ledger_id,description) VALUES 
				('.$employee->id.', "'.$month.'", "Salary Advance", "dr", '.$amount.', '.$ladgerac->ledgerId.', "Salary Advance for '.$month.'.")';
		 		DatabaseHandler::Execute($sqla);
		 		
		 		$sql2 = 'SELECT * FROM payroll_entries WHERE party_id = '.$empid.' ORDER BY id DESC LIMIT 0,1';
				$entry =  DatabaseHandler::GetRow($sql2);

				Logger::Log('Payroll', 'Passed', 'Salary advance for employee id: '.$employee->id.' for '.$month.' recovered');
				//return new PayrollTX($payment, 'Salary Payment');
				$txtype = AdvancesAndLoans::Recover($ledger->ledgerId, 'Advance Recovery');

				$tx = PayrollTX::Initialize($entry, $txtype, 'payroll_entries');
				$tx->post();

				$sql = 'UPDATE advances_and_loans SET tx_id = '.$tx->transactionId.', user = "'.SessionManager::GetUsername().'", datetime = "'.$tx->date.'", stamp = '.$tx->stamp.' WHERE id = '.$latest['id'];
	        	DatabaseHandler::Execute($sql);
	 		}else{
	 		}
			

		} catch (Exception $e) {
			Logger::Log('Payroll', 'Failed', 'Salary advance for employee id: '.$employee->id.' for '.$month.' could not be recovered');
		}
	}
}

class PaySlip
{
	public $id;
	public $employee;
	public $salary;
	public $month;
	public $additions = [];
	public $deductions = [];
	public $t_additions = 0.00;
	public $t_deductions = 0.00;
	public $netpay;
	public $date;

	function __construct($employee, $month)
	{
		$this->employee = $employee;
		$this->salary = $employee->salary->amount;
		$this->month = $month;
		$date = new DateTime();
		$this->date = $date->format('d/m/Y');
		//$this->includeEntry('Basic Salary', 'cr', $salary);
	}

	public function overrideSalary($amount)
	{
		$this->salary = $amount;
	}

	public function populate($id)
	{
		$this->id = $id;
		try {
			$sql = 'SELECT * FROM payroll_entries WHERE party_id = '.$this->employee->id.' AND month = "'.$this->month.'" AND status = 2 ORDER BY type ASC, id DESC';
			$entries =  DatabaseHandler::GetAll($sql);
			
			foreach ($entries as $entry) {					
				if ($entry['type'] != 'Basic Salary') {
					$this->includeEntry($entry['type'], $entry['effect'], $entry['amount']);
				}
			}

			$this->compile();

		} catch (Exception $e) {
			
		}
	}

	public function clear()
	{
		try {
			$sql = 'UPDATE payslips SET status = 2 WHERE id = '.$this->id;
			DatabaseHandler::Execute($sql);			
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function includeEntry($type, $effect, $amount)
	{
		if ($effect == 'cr') {
			if (isset($this->additions[$type])) {
				$amount = $this->additions[$type]->amount + $amount;
				$this->additions[$type] = new PaySlipLine($type, $effect, $amount);
			} else {
				$this->additions[$type] = new PaySlipLine($type, $effect, $amount);
			}
		} else {
			if (isset($this->deductions[$type])) {
				$amount = $this->deductions[$type]->amount + $amount;
				$this->deductions[$type] = new PaySlipLine($type, $effect, $amount);
			} else {
				$this->deductions[$type] = new PaySlipLine($type, $effect, $amount);
			}
		}		
	}

	public function compile()
	{
		foreach ($this->additions as $add) {
			$this->t_additions += $add->amount;
		}

		foreach ($this->deductions as $deduc) {
			$this->t_deductions += $deduc->amount;
		}

		$this->netpay = $this->salary + $this->t_additions - $this->t_deductions;		
	}

	public function commit()
	{
		try {
			$date = new DateTime();
			$datetime = $date->format('d/m/Y H:i a');
			$stamp = $date->format('YmdHis');

			$sql = 'INSERT INTO payslips (party_id, datetime, month, netpay, stamp, status) VALUES 
			('.$this->employee->id.', "'.$datetime.'", "'.$this->month.'", '.$this->netpay.', '.$stamp.', 1)';
	 		DatabaseHandler::Execute($sql);
	 		Logger::Log('PaySlip', 'Passed', 'Payslip for employee id: '.$this->employee->id.' for '.$this->month.' successfully commited');
			return true;
		} catch (Exception $e) {
			Logger::Log('PaySlip', 'Failed', 'Payslip for employee id: '.$this->employee->id.' for '.$this->month.' could not be commited');
			return false;
		}	
	}

	public static function GetSlips($month){

	}

	public static function GetSlip($id){
		try {
			$sql = 'SELECT * FROM payslips WHERE id = '.$id;
			$entry =  DatabaseHandler::GetRow($sql);

			$slip = new PaySlip(Employee::GetEmployee($entry['party_id']), $entry['month']);
			$slip->populate($entry['id']);

			return $slip;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetUncleared($empid){
		$results = [];
		try {
			$sql = 'SELECT * FROM payslips WHERE party_id = '.$empid.' AND status = 1';
			$entries =  DatabaseHandler::GetAll($sql);

			foreach ($entries as $entry) {
				$slip = new PaySlip(Employee::GetEmployee($entry['party_id']), $entry['month']);
				$slip->populate($entry['id']);
				$results[] = $slip;
			}

			return $results;
		} catch (Exception $e) {
			return false;
		}
	}
}

class PaySlipLine
{
	public $payslip;
	public $type;
	public $effect;
	public $amount;

	function __construct($type, $effect, $amount)
	{
		//$this->payslip = $payslip;
		$this->type = $type;
		$this->effect = $effect;
		$this->amount = $amount;
	}
}

?>