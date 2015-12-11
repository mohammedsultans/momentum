 <?php
require_once 'Accounting.php';

//Subclass into single transaction and batch transaction


class FinancialStatements extends Artifact
{
	public static function GetPLStatament($date, $period)
	{	    
	    $ledgers = Ledger::GetPLLedgers();
	    $today = date('d/m/Y');


	    if ($date) {
	    	if ($date == $today) {
		    	foreach ($ledgers as &$ledger) {
			    	$ledger->amount = $ledger->balance->amount;
			    }
			    return $ledgers;
		    }else{
			    $d1 = explode('/', $date);
			    $stamp = $d1[2].$d1[0].$d1[1].'000000' + 0;
			    foreach ($ledgers as &$ledger) {
			    	try {
			    		$sql = 'SELECT * FROM general_ledger_entries WHERE ledger_id = '.$ledger->id.' AND stamp <= '.$stamp.' ORDER BY stamp DESC LIMIT 0,1';
				    	$res =  DatabaseHandler::GetRow($sql);
				    	if ($res) {
				    		$amount = $res['ledger_bal'];
				    	}else{
				    		$amount = 0;
				    	}
				    	$ledger->amount = $amount;
			    	} catch (Exception $e) {
			    		
			    	}		    	
			    }
			    return $ledgers;
			}
	    }else{
	    	$split = explode(' - ', $period);
		    $d1 = explode('/', $split[0]);
		    $d2 = explode('/', $split[1]);
		    $lower = $d1[2].$d1[1].$d1[0].'000000' + 0;
		    $upper = $d2[2].$d2[1].$d2[0].'999999' + 0;
		    $sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($cid).' AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY id ASC';
			foreach ($ledgers as &$ledger) {
		    	try {
		    		$sql = 'SELECT * FROM general_ledger_entries WHERE ledger_id = '.$ledger->id.' AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY stamp ASC';
			    	$res =  DatabaseHandler::GetAll($sql);
			    	
			    	$amount = 0.00;

			    	foreach ($res as $tx) {
			    		$amount += $tx['amount'];
			    	}

			    	$ledger->amount = $amount;
		    	} catch (Exception $e) {
		    		
		    	}		    	
		    }
		    return $ledgers;
	    }
	}
}



?>