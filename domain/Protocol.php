<?php

require_once 'FifthDimension.php';

/*A protocol refers to a type of event*/

class ProtocolReference
{
	public $referredProtocol;

	function __construct(Protocol $referredProtocol)
	{
		$this->referredProtocol = $referredProtocol->protocolId;
	}
}

class ProtocolDependency
{
	public $masterProtocolId;
	public $dependent;
	public $consequent;
	function __construct(Protocol $protocol, ProtocolReference $dependent, ProtocolReference $consequent)
	{
		//Constraint: dependent & consequent are steps in the same protocol. i.e both are in $protocol->steps();
		$this->masterProtocolId = $protocol->protocolId;
		$this->dependent = $dependent;
		$this->consequent = $consequent;
	}
}

class Protocol extends FifthDimension
{
	public $steps = array();//protocol references
	public $protocolId;
	public $name;


	function __construct()
	{
		# code...
	}
}

class MeasurementProtocol extends Protocol
{
	
	function __construct()
	{
		# code...
	}
}

class SourceMeasurementProtocol extends MeasurementProtocol
{
	
	function __construct()
	{
		# code...
	}
}

class CalculatedMeasurementProtocol extends MeasurementProtocol
{
	
	function __construct()
	{
		# code...
	}
}

class ComparativeCalculation extends CalculatedMeasurementProtocol
{
	
	function __construct()
	{
		# code...
	}
}

class CausalCalculation extends CalculatedMeasurementProtocol
{
	
	function __construct()
	{
		# code...
	}
}
?>