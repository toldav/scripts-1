<?php
    
    $hostname = "localhost";
    $username = "username";
    $password = "password";
    //$password = "";

    $databaseName = "hostapp";
    $dbConnected = @mysql_connect($hostname, $username, $password);
    $dbSelected = @mysql_select_db($databaseName, $dbConnected);
    $thisScriptName = "serverRequest.php";

    if($dbConnected){
        if (isset($_POST['save'])){
              $sqlInsert="INSERT INTO new_storage_details (requestor_name, requestor_email, priority, server_name,is_rac_env,node_name, special_ins,is_server_replicated,replicated_server_name,comments,created_date) VALUES ('$_POST[requestorname]','$_POST[requestorEmail]','$_POST[priority]', '$_POST[servername]', '$_POST[hostname]' , '$_POST[racenv]', '$_POST[nodename]', '$_POST[instructions]', '$_POST[serverreplicated]','$_POST[replicatednodename]','$_POST[comments]',NOW())";
              $queryHit = mysql_query($sqlInsert);
              $id = mysql_insert_id();
              //echo $id;
              if ($sqlInsert) {
                //insert file systems data
                $totalnumberfilesystem = $_POST['totalnumberfilesystem'];
                //echo $totalnumberfilesystem;
                for ($x = 1; $x <= $totalnumberfilesystem; $x++) {
                    $drivename = $_POST['drivename_'.$x];
                    $driveaction =  $_POST['drivefile_'.$x];
                    $drivevalue = $_POST['drivevalue_'.$x];
                    $drivedatatype = $_POST['drivedatatype_'.$x];
                    //echo "$drivename. $driveaction. $drivevalue . $drivedatatype<br>";
                    //insert data
                    $sqlInsert="INSERT INTO storage_file_system (storage_id, drive_name, drive_action, drive_value,drive_format,type) ".
                    "VALUES ('$id','$drivename','$driveaction','$drivevalue','$drivedatatype','F')";
                    $queryHit = mysql_query($sqlInsert);
                }

                $totalnumbervolumesystem = $_POST['totalnumbervolumesystem'];
                //echo $totalnumbervolumesystem;
                for ($x = 1; $x <= $totalnumbervolumesystem; $x++) {
                    $drivename = $_POST['volumename_'.$x];
                    $driveaction =  $_POST['volumeaction_'.$x];
                    $drivevalue = $_POST['volumevalue_'.$x];
                    $drivedatatype = $_POST['volumedatatype_'.$x];
                    //echo "$drivename. $driveaction. $drivevalue . $drivedatatype<br>";
                    //insert data
                    $sqlInsert="INSERT INTO storage_file_system (storage_id, drive_name, drive_action, drive_value,drive_format,type) ".
                    "VALUES ('$id','$drivename','$driveaction','$drivevalue','$drivedatatype','V')";
                    $queryHit = mysql_query($sqlInsert);
                }

                  echo "<br/><br/>Form submitted - SUCCESSFUL.<br /><br />";
              }
              else {
                  echo "<br/><br/>Form submitted - FAILED.<br /><br />";
              }
              //echo mysql_errno($dbConnected) . ": " . mysql_error($dbConnected). "\n";
        }
        else {

            $sql="select * from storage_file_system where type = 'F' ";
            $result = mysql_query($sql);

            // Mysql_num_row is counting table row
            $filecount=mysql_num_rows($result);

            $json = array();
            $filedata = "";
            $i=0;
            if($filecount >= 1){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $i = $i+1;
                    $filedata = $filedata."<tr><td>".$i.
                    "</td><td><input type='text' class='form-control' value=".$row['drive_name']." required name='drivename_".$i."'/></td><td><input type='radio' name='drivefile_".$i."' required value='create'/>Create".
                    "<input type='radio'  name='drivefile_".$i."' value='extend'>Extend</td><td>" .
                    "<input type='number' required class='form-control' value=".$row['drive_value']." id='drivevalue' name='drivevalue_".$i."'></td><td>" .
                    "<input type='radio' required name='drivedatatype_".$i."' value='GB'>GB" .
                    "<input type='radio' name='drivedatatype_".$i."' value='TB'>TB</td></tr>";

                }
            }     

            $i=0;

            $sql="select * from storage_file_system where type = 'V' ";

            $result = mysql_query($sql);

            // Mysql_num_row is counting table row
            $volumecount=mysql_num_rows($result);

            $json = array();
            $volumedata = "";
            if($volumecount >= 1){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $i = $i+1;
                    $volumedata= $volumedata."<tr><td>".$i."</td><td>".$row['drive_name']."</td><td>".$row['drive_action']."</td><td>".$row['drive_value']."</td><td>".$row['drive_format']."</td></tr>";
                }
            }
  
        }
              
    }
    
?>
<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        
        <link rel="stylesheet" href="css/bootstrap.css" />
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
        <link rel="stylesheet" href="css/jquery-ui.css">
        <style type="text/css">
        .custom-form .col-md-6 { height: 70px; }
        .panel { margin-top: 4%; }
        .panel-title { font-size: 20px; font-weight: bold; }
        .form-horizontal .control-label { text-align: left; }
        hr { margin: 5px 0 20px; }
        h4 { margin-bottom: 5px; }
        </style>
    </head>
    <body>

        <?php
            include('./service/navigation.php');  
        ?>

        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title"> Server Storage Request</div>
                        </div>  
                        <div class="panel-body" >
                            <form class="form-horizontal custom-form" role="form" method="post">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="submittedBy" class="col-md-3 control-label">Name:<span style="color:red;">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" required class="form-control" id="submittedBy" name="requestorname">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="col-md-3 control-label">Email:<span style="color:red;">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" required class="form-control" id="requestorEmail" name="requestorEmail">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="col-md-3 control-label">Priority:</label>
                                        <div class="col-md-9">
                                            <label class="radio-inline"><input type="radio" name="priority" value="High">High</label>
                                            <label class="radio-inline"><input type="radio" name="priority" value="Medium">Medium</label>
                                            <label class="radio-inline"><input type="radio" name="priority" value="Low">Low</label> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="servername" class="col-md-3 control-label">Server Name</label>
                                        <div class="col-md-9">
                                            <!-- <input type="text" required class="form-control" name="servername" > -->

<?php 

include ('./service/dbOneStop.php'); // Database connection using PDO

$sql="SELECT distinct hostname,id FROM servers order by hostname"; 
$q=mysql_query($sql) or die($sql);

echo "<select name=hostname value='' style='width:100%;'>Server Name</option>"; // list box select command

while($rw=mysql_fetch_array($q))
{ 
    echo "<option value=$rw[hostname]>$rw[hostname]</option>";     
}

 echo "</select>";// Closing of list box
 include ('./service/dbOneStop.php');
 ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="racenv" class="col-md-3 control-label">Is this a RAC environment?<span style="color:red;">*</span></label>
                                        <div class="col-md-9">
                                            <label class="radio-inline"><input type="radio" required name="racenv" value="Yes">Yes</label>
                                            <label class="radio-inline"><input type="radio" name="racenv" value="No">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" style="color:red;" >
                                <b>Note</b>: If the server is a RAC member, storage will be allocated to all nodes in the cluster (i.e. adc176-ldb, adc177-ldb, adc178-ldb, adc179-ldb).
                                        If you want storage for just ONE node, please use the special instructions section below.                          
                                </div>

                                <div class="col-md-12" id="nodesection">
                                    <div class="form-group">
                                        <label for="racenv" class="col-md-3 control-label">Specify All Node Names</label>
                                        <div class="col-md-9">
                                            <input type="text" id="nodename" class="form-control" id="nodename" name="nodename">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" style="color:red;">
                                
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="racenv" class="col-md-3 control-label">Special Instructions</label>
                                        <div class="col-md-9">
                                            <input type="text" required class="form-control" id="instructions" name="instructions"/>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="racenv" class="col-md-3 control-label">Is   this    server  replicated  to  Syracuse?<span style="color:red;">*</span></label>
                                        <div class="col-md-9">
                                            <label class="radio-inline"><input type="radio" required name="serverreplicated" value="Yes">Yes</label>
                                            <label class="radio-inline"><input type="radio" name="serverreplicated" value="No">No</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12" id="replicatednodesection">
                                    <div class="form-group">
                                        <label for="racenv" class="col-md-3 control-label">Replicated servers Name</label>
                                        <div class="col-md-9">
                                            <input type="text"  class="form-control" id="replicatednodename" name="replicatednodename"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h4> File System </h4>
                                    <div class="pull-right">
                                        <input type="button" class="btn btn-primary" value="+" id="addfilesystem"/>
                                    </div>
                                    <hr/>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <td>Item</td>
                                                <td>Filesystem</td>
                                                <td>Action</td>
                                                <td>Total Space</td>
                                                <td>GB/TB</td>
                                            </tr>

                                        </thead>
                                        <tbody id="addfilesystemrbody"></tbody>
                                    </table>
                                    <input type="hidden" value="0" name="totalnumberfilesystem" id="totalnumberfilesystem"/> 
                                </div>

                                <div class="col-md-12">
                                    <b> ASM aliases </b> - i.e. ORA_GRID_1, ORA_ASM_DATAFILE_1, ORA_ASM_REDOLOG_1, ORA_ASM_RECOVERY_1
                                    <div class="pull-right">
                                        <input type="button" class="btn btn-primary" value="+" id="addvolumesystem"/>
                                    </div>
                                    <hr/>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <td>Item</td>
                                                <td>ASM alias</td>
                                                <td>Action</td>
                                                <td>Disk Space</td>
                                                <td>GB</td>
                                            </tr>

                                        </thead>
                                        <tbody id="addvolumesystemrbody"></tbody>
                                    </table>
                                    <input type="hidden" value="0" name="totalnumbervolumesystem" id="totalnumbervolumesystem"/>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="firstname" class="col-md-3 control-label">Comments</label>
                                        <div class="col-md-9">
                                            <textarea style="resize:vertical;" class="form-control" rows="5" name="comments" id="comment"></textarea>   
                                        </div>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" align="center">
                                        <input type="submit" value="Submit" name="save" class="btn btn-primary"/>
                                        <input type="reset" value="Reset" name="reset" class="btn btn-primary"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script src="js/jquery-ui.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            $("#replicatednodename").autocomplete({
                source: 'getNodeNames.php'
            });

            $("#replicatednodesection").hide();
            $("#nodesection").hide();


            var userfullname = "<?php echo $_SESSION['logged_name']; ?>";
            
            $("#requestorname").val(userfullname);

            $('input[type=radio][name=racenv]').change(function() {
                if (this.value === 'Yes') {
                    $("#nodesection").show();
                    $('#nodename').prop('required',true);
                }
                else if (this.value === 'No') {
                    $("#nodesection").hide();
                    $('#nodename').prop('required',false);
                }
            });

            $('input[type=radio][name=serverreplicated]').change(function() {
                if (this.value === 'Yes') {
                    $("#replicatednodesection").show();
                    $('#replicatednodename').prop('required',true);
                }
                else if (this.value === 'No') {
                    $("#replicatednodesection").hide();  
                    $('#replicatednodename').prop('required',false); 
                }
            });

            var i=0;
            $("#addfilesystem").click(function(){
                i++;

                $("#addfilesystemrbody").append("<tr><td>"+i+
                    "</td><td><input type='text' class='form-control' required name='drivename_"+i+"'/></td><td><input type='radio' name='drivefile_"+i+"' required value='create'/>Create"+
                    "<input type='radio'  name='drivefile_"+i+"' value='extend'>Extend</td><td>" +
                    "<input type='number' required class='form-control' id='drivevalue' name='drivevalue_"+i+"'></td><td>" +
                    "<input type='radio' required name='drivedatatype_"+i+"' value='GB'>GB" +
                    "<input type='radio' name='drivedatatype_"+i+"' value='TB'>TB</td></tr>");

                $("#totalnumberfilesystem").val(i);

            });

            var j=0;
            $("#addvolumesystem").click(function(){
                j++;

                $("#addvolumesystemrbody").append("<tr><td>"+j+
                    "</td><td><input type='text' class='form-control' required name='volumename_"+j+"'/></td><td><input type='radio' name='volumeaction_"+j+"' required value='create'/>Create"+
                    /*"<input type='radio' name='volumeaction_"+j+"' value='extend'>Extend</td><td>" +*/ "</td><td>" +
                    "<input type='number' required class='form-control' id='drivevalue' name='volumevalue_"+j+"'></td><td>" +
                    "<input type='radio' required name='volumedatatype_"+j+"' value='GB'>GB"); 
                    //"<input type='radio' name='volumedatatype_"+j+"' value='TB'>TB</td></tr>");
                $("#totalnumbervolumesystem").val(j);

            });

            /*var k=0;
            $("#addothersystem").click(function(){
                k++;

                $("#addothersystemrbody").append("<tr><td>"+k+
                    "</td><td><input type='text' class='form-control' name='otherdrivename_"+k+"'/></td><td><input type='number' required class='form-control' id='otherdrivevalue' name='otherdriveqty_"+k+"'></td>"+
                    "<td>X</td><td>" +
                    "<input type='number' required class='form-control' id='otherdrivevalue' name='otherdrivevalue_"+k+"'></td><td>" +
                    "<input type='radio' name='otherdrivedatatype_"+k+"' value='GB'>GB" +
                    "<input type='radio' name='otherdrivedatatype_"+k+"' value='TB'>TB</td></tr>");

                $("#totalnumberothersystem").val(k);

            });
*/

        }); 

    </script>
</html>
