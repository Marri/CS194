<?php
class SubForum {
	private
		$name, $id, $order;
	public function setSubForum($subforum_id, $subforum_name, $subforum_order){
		$this->name = $subforum_name;
		$this->order = $subforum_order;
		$this->id = $subforum_id;		
	}
	public function getName() { return $this->name; }
		
	public function displayForum() {
		echo "<table border='1'><tr><th>".$this->name."</th> <th>Last Post </th></tr>";
		for ($i=1; $i<=5; $i++)
		  {
		  	echo "<tr><td>row $i, cell 1</td><td>row $i, cell 2</td></tr>";
		  }
	}
}
class Forum{
	private 
		$subforum_list;
	public function __construct() {
       		//parent::__construct();
		$this->subforum_list = array();
   	}
	
	public function loadSubForums(){
		$query = "SELECT * FROM `subforums` ORDER BY subforum_order;";
		$result = runDBQuery($query);
		while($subforums = mysql_fetch_assoc($result)) {
			$currSBFrm = new SubForum();
			$currSBFrm->setSubForum($subforums['subforum_id'], $subforums['subforum_name'],$subforums['subforum_order']);
			array_push($this->subforum_list, $currSBFrm);
		}
	}
	public function displaySubForums(){
		$list_size = count($this->subforum_list);		
		for($i=0;$i<$list_size; $i++){
			$curr_subforum = $this->subforum_list[$i];
			$curr_subforum->displayForum();
		}
	}

}
?>
