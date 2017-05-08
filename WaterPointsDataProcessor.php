<?php 
class WaterPointsDataProcessor{
	var $dataUrl;
	var $totalFunctionalWaterPointsAll;//Total for all water points functional
	var $dataResults;
	
	function __construct($url=NULL){
		$this->dataUrl = $url;
		$this->totalFunctionalWaterPointsAll = 0;
		$this->dataResults = array();
	}
	
	function getDataUrl(){
		return $this->dataUrl;
	}
	
	function fetchData(){
		$json = file_get_contents($this->dataUrl);
		return json_decode($json);
	}
	
	function calculate($url){
		$this->dataUrl = $url;	
		$data = $this->fetchData();

		foreach($data as $dataItem){
			if(property_exists($dataItem,'communities_villages') && property_exists($dataItem,'water_functioning')){
				$resultObject;
				if(array_key_exists($dataItem->communities_villages,$this->dataResults)){
					$resultObject = $this->dataResults[$dataItem->communities_villages];
				}else{
					$resultObject = new \stdClass; //individual result data structure
					$resultObject->community_name = $dataItem->communities_villages;
					$resultObject->totalFunctionalWaterPoints = 0; //Total Functional waterpoints for this community
					$resultObject->totalWaterPoints = 0; //Total Waterpoints for this community
					$resultObject->percentageBroken = 0; //Percentage of broken Waterpoints for this community
					$resultObject->communityRanking = 0; //Ranking by percentage of broken Waterpoints for this community
				}
				
				 $resultObject->totalWaterPoints += 1; //Increment total waterpoints for this community
				  
				if($dataItem->water_functioning === "yes"){
					$this->totalFunctionalWaterPointsAll++; //Increment the functional water points
					$resultObject->totalFunctionalWaterPoints += 1;
				}else{
					$resultObject->percentageBroken = round((($resultObject->totalWaterPoints - $resultObject->totalFunctionalWaterPoints)/$resultObject->totalWaterPoints)*100);
				}
				 
				
				$this->dataResults[$dataItem->communities_villages] = $resultObject; //Set the new Object
			}
		}
		
		
		//Rank the community by broken waterpoints
		
		usort($this->dataResults, array($this, "sortHelper"));
		
		$this->dataResults = $this->rankHelper($this->dataResults);
		
		//Prepare Result
		
		$finalObject = new \stdClass; //result wrapper
		$finalObject->totalFunctionalWaterPoints = $this->totalFunctionalWaterPointsAll; //all waterpoints count
		$finalObject->data = $this->dataResults; //all waterpoints data
		return $finalObject;
	}
	
	private function sortHelper($waterpointA,$waterpointB){
		return ($waterpointA->percentageBroken > $waterpointB->percentageBroken) ? -1 : 1;//we sort in reverse
		
	}
	
	private function rankHelper($waterpointsArray){
		// Our ranking system is such that communities with the same percentage of broken waterpoints are of equal rank 
		for($i=0; $i<sizeof($waterpointsArray); $i++){
			
			//check if first element
			if($i==0){
				$waterpointsArray[$i]->communityRanking = $i+1;
			}else{
				if($waterpointsArray[$i]->percentageBroken != $waterpointsArray[$i-1]->percentageBroken){
					$waterpointsArray[$i]->communityRanking = $i+1;
				}else{
					$waterpointsArray[$i]->communityRanking = $waterpointsArray[$i - 1]->communityRanking;
				}
			}
		}
		return $waterpointsArray;
		
	}
	
	public function setDataUrl($url){		//Useful for testing, we can set our own file/data instead of hitting actual URL
		$this->dataUrl = $url;
	}
}



?>