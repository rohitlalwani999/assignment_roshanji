<?php
$response = new \stdClass();
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli("localhost","root","");
$conn->select_db("assignment");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Assignment</title>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
    <form action="" method="GET">
    For Search Title Here : <input type="text" name="search" id="search">
    <input type="submit" name="searchbtn">
    </form>
    <br>
    <form action="" method="GET">
    <?php 
    if (isset ($_GET['datesearchbtn']) ) { 
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];
    } else {
        $startdate = ""; 
        $enddate = ""; 
    }
        ?>
    For Search by Date Here : &nbsp;
    Start Date : <input type="date" name="startdate" value="<?php echo $startdate;?>">&nbsp;
    End Date : <input type="date" name="enddate" value="<?php echo $enddate;?>">&nbsp;
    <input type="submit" name="datesearchbtn">
    </form>

<br>
    <form>
        <table id="TextBoxArea">
            <tr style="border:1px solid black">
            <th style="width:5%">Image</th>
            <th style="width:20%">Title</th>
            <th style="width:50%">Description</th>
            <th style="width:5%">Quantity</th>
            <th style="width:5%">Price</th>
            <th style="width:10%">Date</th>
            <th style="width:5%">Action</th>
            </tr>
<?php   
  if (isset ($_GET['search']) ) { 
      $qur = $_GET['search'];
      $search = " WHERE title LIKE '%".$qur."%'"; 
  } else {
        $qur = "";
      $search = "";
  }
  if (isset ($_GET['datesearchbtn']) ) { 
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];
    $search = " WHERE date BETWEEN '".$startdate."' AND '".$enddate."'"; 
} else {
      $qur = "";
    $search = "";
}

    $results_per_page = 10;  
  
  //find the total number of results stored in the database  
  $query = "select * from items".$search;  
  $result = mysqli_query($conn, $query);  
  $number_of_result = mysqli_num_rows($result);  

  //determine the total number of pages available  
  $number_of_page = ceil ($number_of_result / $results_per_page);  

  //determine which page number visitor is currently on  
  if (!isset ($_GET['page']) ) {  
      $page = 1;  
  } else {  
      $page = $_GET['page'];  
  }  

  //determine the sql LIMIT starting number for the results on the displaying page  
  $page_first_result = ($page-1) * $results_per_page;  

  //retrieve the selected results from database   
  $query = "SELECT * FROM items".$search." LIMIT " . $page_first_result . ',' . $results_per_page;  
  $result = mysqli_query($conn, $query);  
    
  //display the retrieved result on the webpage  
  while ($row = mysqli_fetch_array($result)) {  ?>
            <tr id="rowid_<?php echo $row['id'];?>">
            <td><img src="<?php echo $row['file'];?>" style="width:100px;height:100px;"></td>
            <td><input type="text" name="title" id="title" value="<?php echo $row['title'];?>" readonly> </td>
            <td><textarea name="desc" id="desc" style="width:100%;" readonly><?php echo $row['desc'];?></textarea></td>
            <td><input type="number" name="qty" id="qty" value="<?php echo $row['qty'];?>" readonly></td>
            <td><input type="number" name="price" id="price" value="<?php echo $row['price'];?>" readonly></td>
            <td><input type="date" name="date" id="date" value="<?php echo $row['date'];?>" readonly></td>
            <td><a class='btn btn-danger btn-xs' data-rid='<?php echo $row['id'];?>' class='removearea' onclick="rremovearea('<?php echo $row['id'];?>')" title='Remove Item "<?php echo $row['id'];?>"' placeholder=''>Remove</a>
            </td>
            </tr>
  <?php }  



?>            
            <tr>
            <td><input type="text" name="file[]" id="file[]" placeholder='Paste url of image'></td>
            <td><input type="text" name="title[]" id="title[]" placeholder='Title'></td>
            <td><textarea name="desc[]" id="desc[]" style="width:100%;" placeholder='Description'></textarea></td>
            <td><input type="number" name="qty[]" id="qty[]" placeholder='Quantity'></td>
            <td><input type="number" name="price[]" id="price[]" placeholder='Price'></td>
            <td><input type="date" name="date[]" id="date[]"></td>
            <td><a class="btn btn-success btn-xs col-md-12" id="AddMore">Add More</a></td>
            </tr>
        </table>
        <a type='submit' id="Submitform"  class="btn btn-primary" id="save" style="float:right;">Save</a>                        
    </form>
<?php 
  //display the link of the pages in URL 
  echo 'Pages : '; 
  for($page = 1; $page<= $number_of_page; $page++) {  
    if (!isset ($_GET['search']) ) { 
        echo '<a href = "assignment.php?page=' . $page . '">' . $page . ' </a>';  
    } else {
        echo '<a href = "assignment.php?search='. $qur .'&&page=' . $page . '">' . $page . ' </a>';  
    }
}  

?>    
    </div>
  </div>
</div>
    
</body>

<script>
var sequeceid = 1;
$('#AddMore').click(function(){
            var rndValue;
            rndValue = "<tr id='row_"+sequeceid+"'>\
            <td><input type='text' id='file[]' name='file[]' placeholder='Paste url of image'></td>\
            <td><input type='text' id='title[]' name='title[]' placeholder='Title'></td>\
            <td><textarea id='desc[]' name='desc[]' style='width:100%;'  placeholder='Description'></textarea></td>\
            <td><input type='number' id='qty[]' name='qty[]' placeholder='Quantity'></td>\
            <td><input type='number' id='price[]' name='price[]' placeholder='Price'></td>\
            <td><input type='date' id='date[]' name='date[]'></td>\
            <td><a class='btn btn-danger btn-xs' data-id='"+sequeceid+"' class='removearea' onclick='removearea("+sequeceid+")' title='Remove Item "+sequeceid+"' placeholder=''>Remove</a></td>\
            </tr>";

            // finally, add the value to the DIV.
            $(rndValue).appendTo('#TextBoxArea');    
            sequeceid++;
});

function removearea(eleid){
$('#row_'+eleid).remove();
            sequeceid--;
}

function rremovearea(eleid){
var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        var response = JSON.parse(this.responseText);
        if (response.code==1){
            alert("Removed Successfully.");
            $('#rowid_'+eleid).remove();
            location.reload();

        } else {
            alert("Have Errors or fill all fields.");
        }
        }
    };
    xhttp.open("POST", "remove_item.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+eleid);

}


$('#Submitform').click(function(){
    var items = { };
    var file = $('input[id="file[]"]').map(function () {
        return this.value; // $(this).val()
        }).get();
        var title = $('input[id="title[]"]').map(function () {
        return this.value; // $(this).val()
        }).get();
        var desc = $('textarea[id="desc[]"]').map(function () {
        return this.value; // $(this).val()
        }).get();
        var qty = $('input[id="qty[]"]').map(function () {
        return this.value; // $(this).val()
        }).get();
        var price = $('input[id="price[]"]').map(function () {
        return this.value; // $(this).val()
        }).get();
        var date = $('input[id="date[]"]').map(function () {
        return this.value; // $(this).val()
        }).get();
  

    for (var i=0;i<=date.length-1;i++){
        if ((file[i]!='') && (title[i]!='') && (desc[i]!='') && (qty[i]!='') && (price[i]!='') && (date[i]!='')){
        items[i] = { 'file': file[i], 'title': title[i], 'desc': desc[i], 'qty': qty[i], 'price': price[i], 'date': date[i]};
        } else {
            alert('Please fill out all details.');
        return false;
        }
    }

    console.log(items);
    add_item(items);
    // location.reload();


});




function add_item(items){
    dbParam = JSON.stringify(items);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        var response = JSON.parse(this.responseText);
        if (response.code==1){
            $("#demand_form").trigger("reset");
            alert("Added Successfully.");
            location.reload();
        } else {
            alert("Have Errors or fill all fields.");
        }
        }
    };
    xhttp.open("POST", "add_item.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("dataarray="+dbParam);
}

</script>
</html>