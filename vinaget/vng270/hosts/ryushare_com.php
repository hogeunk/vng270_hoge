<?php

class dl_ryushare_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://ryushare.com/premium.python", "lang=english;{$cookie}", "");
		if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<b>Premium account expire:</b><br>','<br><br>'));
		else if(stristr($data, '<a href="http://ryushare.com/premium.python">Upgrade to premium</a>')) return array(false, "accfree");
		else return array(false, "accinvalid" );
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://ryushare.com/","lang=english","op=login&redirect=http%3A%2F%2Fryushare.com%2F&login={$user}&password={$pass}&loginFormSubmit=Login");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,"{$this->lib->cookie};lang=english;","");
		if(preg_match('/ocation: *(.*)/i', $data, $redir)) return str_replace(" ","%20",trim($redir[1]));
		elseif (stristr($data,'403 Forbidden')) 
				$this->error("<font color=red><b>Your IP is banned!</b></font>", true, false);
		elseif (stristr($data,'<div class="err">You have reached the download-limit: 88888 Mb for last 1 days</div>'))
				$this->error("<font color=red>You have reached the download-limit: 88888 Mb for last 1 days</b></font>", true, false);
		elseif (stristr($data,'This server is in maintenance mode. Refresh this page in some minutes.')) 
				$this->error("<font color=red>This server is in maintenance mode. Refresh this page in some minutes.</b></font>", true, false);
		elseif(stristr($data, "Create Download Link")){
                 $post = $this->parseForm($this->lib->cut_str($data, '<form name="F1" method="POST"', '</form>'));
                 $data = $this->lib->curl($url, $this->lib->cookie, $post);
				 $data = $this->lib->cut_str($data, '<center><span style="background:#f9f9f9;border:1px dotted #bbb;padding:7px;">', '</span></center>');
                 $link = $this->lib->cut_str($data, '<a href="', '">Click here to download</a>');
                 return trim($link);
          }
		elseif (stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
    }

}
  
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Ryushare Download Plugin 
* Downloader Class By [FZ]
* Check account, fixed small error by giaythuytinh176 [18.7.2013]
*/
?>