<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title> 
    </head>
    <body >
        <div style="width:500px ;height: 200px;left: 50%;position: absolute;height: 100%;text-align: center;margin: -250 auto;background-color:#7f7f7f">
           <div style="margin-top: 90px">
            <form  action="/index.php/setting/addrequest" method="post">
               
                <label style="color:#ffffff">Setting String</label><input name="setting_string" />
                <input type="submit" id="Go" value="Submit"  />
              
            </form>
               
          <?php 
          if(isset($message)){
              echo "<h2 style='color:#fff'> Last Request Inserted With This ID: $message </h2>";
          }
          ?>
                 </div>
        </div> 
    </body>
</html>
