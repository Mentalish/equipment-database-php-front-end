<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Advanced Software Engineering</title>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/owl.carousel.css">
<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="assets/css/templatemo-style.css">
</head>
<body>
<body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
     <!-- MENU -->
     <section class="navbar custom-navbar navbar-fixed-top" role="navigation">
          <div class="container">
               <div class="navbar-header">
                    <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                    </button>

                    <!-- lOGO TEXT HERE -->
                    <a href="#" class="navbar-brand">Modify Manufacturer</a>
               </div>
               <!-- MENU LINKS -->
               <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-nav-first">
                         <li><a href="index.php" class="smoothScroll">Home</a></li>
                         <li><a href="search.php" class="smoothScroll">Search Equipment</a></li>
                         <li><a href="add.php" class="smoothScroll">Add Equipment</a></li>
                         <li><a href="add-device-type.php" class="smoothScroll">Add Device Type</a></li>
                         <li><a href="add-manufacturer.php" class="smoothScroll">Add Manufacturer</a></li>
                         <li><a href="modify-device-type.php" class="smoothScroll">Modify Device Type</a></li>
                         <li><a href="modify-manufacturer.php" class="smoothScroll">Modify Manufacturer</a></li>
                    </ul>
               </div>
          </div>
     </section>
 <!-- HOME -->
     <section id="home">
          </div>
     </section>
     <!-- FEATURE -->
      <section id="feature">
         <div class="container">
               <div class="row">
                  <?php
                        include("functions.php");
                        $deviceTypes=array();
                        $statuses=array();
                        $dblink=db_connect("equipment");
                        $sql="Select `device_type_name`,`device_type_id` from `device_types`";
                        $result=$dblink->query($sql) or 
                           die("<p>Something went wrong with $sql<br>".$dblink->error);
                        while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
                           $deviceTypes[$data['device_type_id']]=$data['device_type_name'];
                        }

                     $sql="Select `status_name`,`status_id` from `status`";
                     $result=$dblink->query($sql) or 
                        die("<p>Something went wrong with $sql<br>".$dblink->error);
                     while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
                        $statuses[$data['status_id']]=$data['status_name'];
                     }
                        if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="deviceNameInvalid")
                        {
                            echo '<div class="alert alert-danger" role="alert">device type name is invalid.</div>';
                        }

                        if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="deviceDuplicate")
                        {
                            echo '<div class="alert alert-danger" role="alert">device type name already exists in database!</div>';
                        }
                                            ?>
                  <form method="post" action="">
                    <div class="form-group">
                        <label for="exampleDevice">Device Types:</label>
                        <select class="form-control" name="device_type">
                            <?php
                                foreach($deviceTypes as $key=>$value)
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                            ?>
                        </select>
                    </div>
                        <div class="form-group">
                           <label for="exampleSerial">New Device Type Name:</label>
                           <input type="text" class="form-control" id="serialInput" name="new_device_type_name">
                       </div>

                        <div class="form-group">
                           <label for="exampleStatus">Set Status:</label>
                           <select class="form-control" name="status">
                            <?php
                                   foreach($statuses as $key=>$value)
                                       echo '<option value="'.$key.'">'.$value.'</option>';
                               ?>
                           </select>
                        </div>

                           <button type="submit" class="btn btn-success" name="save" value="submit">Save</button>
                    </form>
               </div>
          </div>
      </section>
</body>
</html>
<?php
    if (isset($_POST['save']))
    {
       $deviceTypeName=$_POST['new_device_type_name'];
       $deviceType = $_POST['device_type'];
       $status = $_POST['status'];
       if($deviceTypeName) 
       {
          if(!preg_match('/^[a-z\s]+$/', $deviceTypeName)) 
          {
             redirect("modify-device-type.php?msg=deviceNameInvalid");
          }

          $sql="Select `device_type_id` from `device_types` where `device_type_name`='$deviceTypeName' and `device_type_id`!='$deviceType'";
          $rst=$dblink->query($sql) or
                die("<p>Something went wrong with $sql<br>".$dblink->error);
          if ($rst->num_rows<=0)//name not previously found
          {
            $sql="UPDATE `device_types` SET `device_type_name`='$deviceTypeName', `status_id`='$status' WHERE `device_type_id`='$deviceType'";
            $dblink->query($sql) or
                 die("<p>Something went wrong with $sql<br>".$dblink->error);
            redirect("index.php?msg=deviceTypeEdited");
          }
          else {
            redirect("modify-device-type.php?msg=deviceDuplicate");
          }
       } else 
       {
       $sql="UPDATE `device_types` SET `status_id`='$status' WHERE `device_type_id`='$deviceType'";
       $dblink->query($sql) or
            die("<p>Something went wrong with $sql<br>".$dblink->error);
       redirect("index.php?msg=ManufacturerEdited");
       }
    }
