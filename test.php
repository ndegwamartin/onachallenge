<?php require_once("WaterPointsDataProcessor.php"); 

//Testing the class
$wp = new WaterPointsDataProcessor();

//testing
echo "Testing Output <br/><br/>";
assertNull($wp->dataUrl,"Data URL is unset/null before Calling Calculate");echo "<br/>";
assertTrue($wp->totalFunctionalWaterPointsAll == 0,"Total functional waterpoints is 0 before Calling Calculate");echo "<br/>";
assertTrue(empty($wp->dataResults),"Data Results empty before Calling Calculate");echo "<br/>";
assertNull($wp->dataUrl,"Data URL is NULL before Calling Calculate");echo "<br/>";

$result = $wp->calculate("https://raw.githubusercontent.com/onaio/ona-tech/master/data/water_points.json");

assertNotNull($wp->dataUrl,"Data URL is NOT NULL after Calling Calculate");echo "<br/>";
assertTrue($wp->totalFunctionalWaterPointsAll > 0,"Total functional waterpoints is greater than 0 after Calling Calculate");echo "<br/>";
assertTrue(!empty($wp->dataResults),"Data Results is NOT empty after Calling Calculate");echo "<br/>";
assertNotNull($wp->dataUrl,"Data URL is NOT NULL after Calling Calculate");echo "<br/>";
assertTrue($wp->dataUrl=="https://raw.githubusercontent.com/onaio/ona-tech/master/data/water_points.json","Data URL Set equals provided URL https://raw.githubusercontent.com/onaio/ona-tech/master/data/water_points.json");

//Testing using mocked data
$wp2 = new WaterPointsDataProcessor();
$result2 = $wp2->calculate("testdata.json");

echo "<br/><br/>Test Output using mocked data (file: testdata.json)<br/>";echo "<br/>";
assertTrue($result2->totalFunctionalWaterPoints == 17,"Total Functional Waterpoints Should equal 17");echo "<br/>";
assertTrue(sizeof($result2->data) == 3,"Total Waterpoints Should equal 3");echo "<br/>";
assertTrue($result2->data[0]->community_name == "Nabulugu","First ranked element community name Should equal 'Nabulugu'");echo "<br/>";
assertTrue($result2->data[0]->communityRanking == 1,"First ranked element community ranking Should equal 1");echo "<br/>";
assertTrue($result2->data[0]->totalWaterPoints == 5,"First ranked element total water points Should equal '5'");echo "<br/>";
assertTrue($result2->data[0]->totalFunctionalWaterPoints == 4,"First ranked element total functional water points Should equal '4'");echo "<br/>";


assertTrue($result2->data[1]->community_name == "Selinvoya","Second ranked element community name Should equal 'Selinvoya'");echo "<br/>";
assertTrue($result2->data[1]->communityRanking == 2,"Second ranked element community ranking Should equal 2");echo "<br/>";
assertTrue($result2->data[1]->totalWaterPoints == 13,"Second ranked element total water points Should equal '13'");echo "<br/>";
assertTrue($result2->data[1]->totalFunctionalWaterPoints == 12,"Second ranked element total functional water points Should equal '12'"); echo "<br/>";

echo "<br/>";echo "<br/>";
echo "Dump output of result object after running calculate on main url 'https://raw.githubusercontent.com/onaio/ona-tech/master/data/water_points.json' <br/>";
var_dump($result); //dump result of main data url after running calculate
 

function assertTrue($value,$title){
	if($value){
		echo $title." - Succeded";
	}else{
		echo $title." - Failed";
	}
}

function assertNull($value,$title){
	if(!isset($value)){
		echo $title." - Succeded";
	}else{
		echo $title." - Failed";
	}
}

function assertNotNull($value,$title){
	if(isset($value)){
		echo $title." - Succeded";
	}else{
		echo $title." - Failed";
	}
}

?>