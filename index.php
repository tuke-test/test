<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <title>bakalarka</title>
  <link rel="stylesheet" href="style.css">
  <link href="favicon.ico" rel="icon">
  <link rel="stylesheet" href="highlight/styles/default.css">
  <script src="highlight/highlight.pack.js"></script>
  <script>hljs.initHighlightingOnLoad();</script> 
  </head>                                                                           
  <body>  
 
    <div id="obalovaci">
    
      <? 
    require_once("connect.php");
    session_start();
    if(isLogged() == false) {
    require_once("login.php");}
      
      include("header.php");
      ?>               
        
    	  <div id="stred">
                    
          <h1>Nástenka</h1>
          <div id="deadline"> 
          </div>
          
          <div id="news-left">
            <h2>Oznamy</h2>
            
            <div style="<? echo "$hide_admin_elements"; ?>">
            <form method='POST' action='#'>
            <textarea name='news_input' cols='48' rows='2'></textarea><br /><div style="text-align:right"><input type='submit' name='button_news' value='Odoslať'></div></form> <br />
            </div>
             <?
            $news_query = mysql_query("SELECT * FROM news ORDER BY id desc");
            if (mysql_num_rows($news_query)!=0) {                           
              while ($data_news = mysql_fetch_array($news_query, MYSQL_BOTH)){
                $user_id_news = $data_news['user_id'];
                $text_news = $data_news['text'];
                $date_news = $data_news['date'];  
                                         
                echo "
                <table class='news'>
                <tr><td>$text_news</td></tr>
                <tr><td class='news-name'><a class='news-name' href='user-profile.php?profile_user_id=$user_id_news'>".getUserInfo($user_id_news,"first_name")."".getUserInfo($user_id_news,"last_name")."</a></td><td class='news-date'>$date_news</td></tr>
                </table> <br>
                ";
              }
            }
            else {
              echo "Žiadne oznamy.";
            } 
            ?>
            
          </div>
          
          <div id="news-right">
            <h2>Skupina</h2>
            
            <!-- Časť pre administrátovské prostredie -->
            
            <div style="<? echo $hide_admin_elements; ?>">
              <table>
              <?
              $all_groups_query = mysql_query("SELECT * FROM groups ORDER BY group_name");
              if (mysql_num_rows($all_groups_query)!=0) {                           
                while ($data_all_groups = mysql_fetch_array($all_groups_query, MYSQL_BOTH)){
                  $group_name_all = $data_all_groups['group_name'];
                  $id_all_groups = $data_all_groups['id'];  
                                           
                  echo "<form method='POST' action='#'>
                        <input type='hidden' name='all_id_input' value='$id_all_groups'>
                        <input type='submit' name='button_show_group' value='$group_name_all'>
                        </form> ";
                }
              } 
              else {
                echo "Žiadne správy v skupine.";
              }
              echo "</table> <br>";
              
              if(isset($_POST["button_show_group"])) {
                $all_id_input = $_POST["all_id_input"]; 
                $show_group_query = mysql_query("SELECT * FROM users WHERE user_group='$all_id_input'");
                if (mysql_num_rows($show_group_query)!=0) {
                  echo "Členovia skupiny: ";              
                  while ($data_show_group = mysql_fetch_array($show_group_query, MYSQL_BOTH)){             
                    $show_group_user_id = $data_show_group['id'];
                    $show_group_first_name = $data_show_group['first_name'];
                    $show_group_last_name = $data_show_group['last_name'];
                    
                    echo "<a href='user-profile.php?profile_user_id=$show_group_user_id'>$show_group_first_name $show_group_last_name</a>, ";               
                  }
                }
                else {
                  echo "Skupina nemá žiadneho člena.";
                }
                ?>
                <br />
                <form method='POST' action='#'>
                <textarea name='group_news_input' cols='48' rows='2'></textarea><br /><div style="text-align:right"><input type='submit' name='button_group_news' value='Odoslať'></div></form>
                <br>
                <?
                
                if(isset($_POST["button_group_news"])) {
                  $group_news_input = $_POST["group_news_input"];  
                  $group_news_input_date = Date("G:i, d.m.Y"); 
                  
                  $group_news_input_query = mysql_query("INSERT INTO news_group(user_id,text,date,group_id)  VALUES('$id','$group_news_input','$group_news_input_date','$all_id_input')");
      
                  if($group_news_input_query){
                     echo'<meta http-equiv="refresh" content="0">';
                  } else {
                     echo "Správa nebola odoslaná!";
                  }           
                }
                
                
                $group_news_query = mysql_query("SELECT * FROM news_group WHERE group_id='$all_id_input' ORDER BY id desc");
                if (mysql_num_rows($group_news_query)!=0) {                           
                  while ($data_group_news = mysql_fetch_array($group_news_query, MYSQL_BOTH)){
                    $user_id_group_news = $data_group_news['user_id'];
                    $text_group_news = $data_group_news['text'];
                    $date_group_news = $data_group_news['date'];  
                                             
                    echo "
                    <table class='news'>
                    <tr><td>$text_group_news</td></tr>
                    <tr><td class='news-name'><a class='news-name' href='user-profile.php?profile_user_id=$user_id_group_news'>".getUserInfo($user_id_group_news,"first_name")." ".getUserInfo($user_id_group_news,"last_name")."</a></td><td class='news-date'>$date_group_news</td></tr>
                    </table> <br>
                    ";
                  }
                }
                else {
                  echo "Žiadne správy v skupine.";
                }           
              }
              ?>
            </div>
            
            
            <!-- Časť pre študentské prostredie -->
            
            <div style="<? echo "$hide_student_elements"; ?>"> 
              <form method='POST' action='#'>
              <textarea name='group_news_input' cols='48' rows='2'></textarea><br /><div style="text-align:right"><input type='submit' name='button_group_news' value='Odoslať'></div></form>
              <br>
                       
              <?
              $groups_query = mysql_query("SELECT * FROM groups WHERE id=".getUserInfo($id,"user_group")."");
              if (mysql_num_rows($groups_query)!=0) {                           
                $data_groups = mysql_fetch_array($groups_query, MYSQL_BOTH);
                $group_id = $data_groups['id'];
                $group_name = $data_groups['group_name']; 
                               
                echo "<b>Názov skupiny:</b> $group_name <br />
                      <b>Členovia skupiny:</b> Ja";    
                
                $group_users_query = mysql_query("SELECT * FROM users WHERE user_group='$group_id' AND id!='$id'");              
                while ($data_group_users = mysql_fetch_array($group_users_query, MYSQL_BOTH)){             
                  $group_user_id = $data_group_users['id'];
                  $group_first_name = $data_group_users['first_name'];
                  $group_last_name = $data_group_users['last_name'];
   
                  echo ", <a href='user-profile.php?profile_user_id=$group_user_id'>$group_first_name $group_last_name</a>";               
                }
              }
              else {
                echo "Nie ste v žiadnej skupine. <br />";
              } 
              
              echo "<br /><br />";
              
              $group_news_query = mysql_query("SELECT * FROM news_group WHERE group_id='$group_id' ORDER BY id desc");
              if (mysql_num_rows($group_news_query)!=0) {                           
                while ($data_group_news = mysql_fetch_array($group_news_query, MYSQL_BOTH)){
                  $user_id_group_news = $data_group_news['user_id'];
                  $text_group_news = $data_group_news['text'];
                  $date_group_news = $data_group_news['date'];  
                                           
                  echo "
                  <table class='news'>
                  <tr><td>$text_group_news</td></tr>
                  <tr><td class='news-name'><a class='news-name' href='user-profile.php?profile_user_id=$user_id_group_news'>".getUserInfo($user_id_group_news,"first_name")." ".getUserInfo($user_id_group_news,"last_name")."</a></td><td class='news-date'>$date_group_news</td></tr>
                  </table> <br>
                  ";
                }
              }
              else {
                echo "Žiadne správy v skupine.";
              }
              if(isset($_POST["button_news"])) {
                $news_input = $_POST["news_input"];  
                $news_input_date = Date("G:i, d.m.Y"); 
                
                $news_input_query = mysql_query("INSERT INTO news(user_id,text,date)  VALUES('$id','$news_input','$news_input_date')");
    
                if($news_input_query){
                   echo'<meta http-equiv="refresh" content="0">';
                } else {
                   echo "Správa nebola odoslaná!";
                }           
              }
            
              if(isset($_POST["button_group_news"])) {
                  $group_news_input = $_POST["group_news_input"];  
                  $group_news_input_date = Date("G:i, d.m.Y"); 
                  
                  $group_news_input_query = mysql_query("INSERT INTO news_group(user_id,text,date,group_id)  VALUES('$id','$group_news_input','$group_news_input_date','$group_id')");
      
                  if($group_news_input_query){
                     echo'<meta http-equiv="refresh" content="0">';
                  } else {
                     echo "Správa nebola odoslaná!";
                  }           
               } 
              ?>
            </div>
          </div>
          <p style="clear:both"> </p>
      	</div>
        <?php include('pata.php');?>        
    </div>
	</body>
</html>