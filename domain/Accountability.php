<?php

/**
* Accountabilities define agreements/contracts which parties/agents get into
*
*/
require_once 'SeventhDimension.php';
require_once 'Party.php';

class AccountabilityType extends SeventhDimension
{
 	public $name;
 	public $hierarchic = false;
 	
 	function __construct($name)
 	{
 		$this->name = $name;
 		$sql = 'INSERT IGNORE INTO accountability_types (name) VALUES ("'.$this->name.'")';
 		DatabaseHandler::Execute($sql);
 	}

 	public function beHierarchic()
  {
  	$this->hierarchic = true;
  }
}

class ConnectionRule
{
  public $allowedParent;
  public $allowedhild;
  
  function __construct(PartyType $allowedParent, PartyType $allowedChild)
  {
    $this->allowedParent = $allowedParent;
    $this->allowedChild = $allowedChild;
  }

  public function isValid(Party $parent, Party $child)
  {
      return ($parent->type == $this->allowedParent && $child->type == $this->allowedChild);
  }
}

class ConnectedAccountabilityType extends AccountabilityType
{
  public $connectionRules = [];
  
  function __construct($name)
  {
    parent::__construct($name);
  }

  public function addConnectionRule(PartyType $parentType, PartyType $childType)
  {
    array_push($this->connectionRules, new ConnectionRule($parentType, $childType));
    //store connection rule in database for this particular accounability type
  }

  public function areValidPartyTypes(Party $parent, Party $child)
  {
    foreach ($this->connectionRules as $rule) {
      if($rule->isValid($parent, $child)) return true;
    }
    return false;
  }

  public function canCreateAccountability(Party $parent, Party $child)
  {
    if ($this->hierarchic == true && $child->parents($this).size != 0) return false;
      return this.areValidPartyTypes(parent, child);
  }
}

class Accountability extends SeventhDimension
{
 	public $id;
  public $parent;
 	public $child;
 	public $type;
  public $datetime;
  public $startstamp;
  public $closestamp;
  public $status;
 	
 	function __construct(Party $parent, Party $child, AccountabilityType $accountabilityType)
 	{
 		$this->parent = $parent;
 		$this->child = $child;
 		$this->type = $accountabilityType;
    $parent->friendAddChildAccountability($this);
    $child->friendAddParentAccountability($this);
 	}

 	public static function create(Party $parent, Party $child, AccountabilityType $accountabilityType)
  	{
  		if (!self::canCreate($parent, $child, $accountabilityType)) {
  			return false;
  		} else {
  			return new $this($parent, $child, $accountabilityType);
  		}
  	}

  	public static function canCreate(Party $parent, Party $child, AccountabilityType $accountabilityType)
  	{
  		if ($parent == $child) {
  			return false;
  		} elseif ($parent->ancestorsInclude($child, $accountabilityType)) {
  			return false;
  		}else {
  			return $accountabilityType->canCreateAccountability($parent, $child);
  		}
  	}

  	public function parent()
  	{
  		return $this->parent;
  	}

  	public function child()
  	{
  		return $this->child;
  	}

  	public function type()
  	{
  		return $this->type;
  	}
}

?>