<?php
class ListPage
{
	
	public function __construct($mobile)
	{
		$this->mobileNumber = $mobile;
		$result = mysql_query("select * from customers where mobilenumber like '%".$mobile."%' and status = 1");
		if(mysql_num_rows($result) > 0 )
		{
			$this->CustomerAvailable = true;
			$this->CustomerName = mysql_result($result,0,"name");
			$this->RegistrationNumber = mysql_result($result,0,"registrationNumber");
			$this->CustomerId = mysql_result($result,0,"customer_id");
			$this->customerAudio = mysql_result($result,0,"audioFileName");
		}
	}
}
?>