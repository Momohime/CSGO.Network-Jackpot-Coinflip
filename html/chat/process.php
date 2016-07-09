<?php
session_start();

    $function = $_POST['function'];
    
    $log = array();
    
    switch($function) {
    
    	 case('getState'):
        	 if(file_exists('chat.txt')){
               $lines = file('chat.txt');
        	 }
             $log['state'] = count($lines); 
        	 break;	
    	
    	 case('update'):
        	$state = $_POST['state'];
        	if(file_exists('chat.txt')){
        	   $lines = file('chat.txt');
        	 }
        	 $count =  count($lines);
        	 if($state == $count){
        		 $log['state'] = $state;
        		 $log['text'] = false;
        		 
        		 }
        		 else{
        			 $text= array();
        			 $log['state'] = $state + count($lines) - $state;
        			 foreach ($lines as $line_num => $line)
                       {
        				   if($line_num >= $state){
                         $text[] =  $line = str_replace("\n", "", $line);
        				   }
         
                        }
        			 $log['text'] = $text; 
        		 }
        	  
             break;
    	 
			case('send'):
			
			if(isset($_SESSION["steamid"]))
			{
			
				$nickname = $_POST['nickname'];
                $ava = $_POST['ava'];
                $steamid = $_POST['id'];
                $admin = $_POST['admin'];
                $premium = $_POST['premium'];
                


                include_once(dirname(__FILE__) . '/../link.php');
				$admin = fetchinfo("admin","users","steamid",$_SESSION["steamid"]);
				$premium = fetchinfo("premium","users","steamid",$_SESSION["steamid"]);
				$dbname = fetchinfo("name","users","steamid",$_SESSION["steamid"]);
				$dbava = fetchinfo("avatar","users","steamid",$_SESSION["steamid"]);
				$lastmsg = fetchinfo("lastmsg","users","steamid",$_SESSION["steamid"]);
				$cban = fetchinfo("cban","users","steamid",$_SESSION["steamid"]);
				$bnmsg = fetchinfo("value","info","name","nmsg");	
				$bpmsg = fetchinfo("value","info","name","pmsg");
				$time=time();
				$nmsg=$time+$bnmsg;
				$pmsg=$time+$bpmsg;
				$nickname = htmlentities(strip_tags($nickname));
				$message = htmlentities(strip_tags($_POST['message']));
				
				$cens=array(
                  'you-can-add','censored-words-here',
                 );

					foreach($cens as $c)
				 
					$message=str_ireplace($cens, '****', $message);
					
    				if(($message) != "\n" && $message != "" && $message != " " && $message != "  " && $message != "   " && $message != "    ")
					{

                        if($admin == "1" || $admin == 1)
						{
 
								$color='ff0000';
                                fwrite(fopen('chat.txt', 'a+'), "<a href='profile.php?action=view&id=".$steamid."'><img src='".$ava."' height='50' width='50'></img></a><p style='float: right;width: 210px;overflow-wrap: break-word;padding: 3px;padding-left: 13px;'><a class='nameclick' style='color:#".$color."'  target='_blank' href='profile.php?action=view&id=".$steamid."'>".$nickname."</a><br><a style='display: block;margin-top: 5px;'>".$message = str_replace("\n", " ", $message)."</a></p><a href='index.php?action=cban&id=$steamid' class='ban' style='display:none'></a><script>document.getElementById('chat-wrap').scrollTop = document.getElementById('chat-wrap').scrollHeight;</script> \n"); 
								mysql_query("UPDATE `users` SET `lastmsg`='$nmsg' WHERE `steamid`='$steamid'");
								
                            
                        }
                        else if($premium == "1")
						{
							if($lastmsg<=$time && $cban==0)
							{
								if($dbava==$ava && $dbname==$nickname)
								{
									$color = "FFD700";
									fwrite(fopen('chat.txt', 'a+'), "<a href='profile.php?action=view&id=".$steamid."'><img src='".$ava."' height='50' width='50'></img></a><p style='float: right;width: 210px;overflow-wrap: break-word;padding: 3px;padding-left: 13px;'><a class='nameclick' style='color:#".$color."' target='_blank' href='profile.php?action=view&id=".$steamid."'>".$dbname."</a><br><a style='display: block;margin-top: 5px;'>".$message = str_replace("\n", " ", $message)."</a></p><a href='index.php?action=cban&id=$steamid' class='ban' style='display:none'></a><script>document.getElementById('chat-wrap').scrollTop = document.getElementById('chat-wrap').scrollHeight;</script> \n"); 
									mysql_query("UPDATE `users` SET `lastmsg`='$pmsg' WHERE `steamid`='$steamid'");
								}
							}	
						}
                        else
						{
							if($lastmsg<=$time && $cban==0)
							{
								if($dbava==$ava && $dbname==$nickname)
								{
									$color = "337ab7";
									fwrite(fopen('chat.txt', 'a+'), "<a href='profile.php?action=view&id=".$steamid."'><img src='".$ava."' height='50' width='50'></img></a><p style='float: right;width: 210px;overflow-wrap: break-word;padding: 3px;padding-left: 13px;'><a class='nameclick' style='color:#".$color."'  target='_blank' href='profile.php?action=view&id=".$steamid."'>".$dbname."</a><br><a style='display: block;margin-top: 5px;'>".$message = str_replace("\n", " ", $message)."</a></p><a href='index.php?action=cban&id=$steamid' class='ban' style='display:none'></a><script>document.getElementById('chat-wrap').scrollTop = document.getElementById('chat-wrap').scrollHeight;</script> \n"); 
									mysql_query("UPDATE `users` SET `lastmsg`='$nmsg' WHERE `steamid`='$steamid'");
								}
							}
						}
					}
					
				
			}
			break;
    }
    
    echo json_encode($log);

?>