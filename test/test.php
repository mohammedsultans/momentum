<?php
//The aim of a test is to test for the end user use cases, this is their originating principle - BDD, TDD
require_once('/../domain/LSOfficeDomain.php');

        $client = Client::GetParty(2);

    	$project = Project::GetProject(20);

        echo count($project->getActivities());

       
    	//$invoice->post();


?>


