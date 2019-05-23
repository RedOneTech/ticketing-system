<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <style type="text/css">
      .carousel-rounded{
        border-radius:1rem !important;
      }
    </style>

    <title>Seating Chart</title>
  </head>
  <body>
    <div class="container">

      
    <!-- php get POST data -->
    <?php 
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "ticketing-system";

          // Create connection
          $conn = mysqli_connect($servername, $username, $password, $dbname);
          // Check connection
          if (!$conn) {
              die('
                <div class="alert alert-danger" role="alert">
                Connection failed: '.mysqli_connect_error().'
                </div>
              ');
          }

          $eventname=$_GET['event'];
          $sql = 'SELECT column_number, row_number, order_log_table FROM seating_event_info WHERE event_name="'.$eventname.'"';
          $result = mysqli_query($conn, $sql);
          $rowresult=mysqli_fetch_assoc($result);

          if(mysqli_num_rows($result)!=0){
            echo'
              <br>
              <div class="row justify-content-center">
                <h1>'. $eventname.' </h1>

                <!-- for adding break row using white spaces -->
                <div class="w-100"></div>

                <p class="lead"> Seating Chart </p>
              </div>
            ';
            $column=$rowresult['column_number'];
            $row=$rowresult['row_number'];
            $ordertable=$rowresult['order_log_table'];
          }
          else{
            echo'
               <br><br>
               <div class="alert alert-danger" role="alert">
               Event Not Found
               </div>
            ';
          }
          ?>
    <!-- event name and slog -->
    
    <br>

    <!-- table using php loop -->
    <center>
      <table border="1">
        <?php 
          if(isset($row)){
            $rowname = 'A';
            echo'<tr><td></td>';
            for($x=1;$x<=$column;$x++){
              echo'<td><center><h4><strong>'.$x.'</strong><h4></center></td>';
            }
            echo'</tr>';
            for($y=1;$y<=$row;$y++){
              echo'
              <tr><td><h4><strong>'.$rowname.'</strong></h4></td>';
              for($x=1;$x<=$column;$x++){
                $sqlcheck = 'SELECT seat_number FROM '.$ordertable.' WHERE seat_number LIKE "%'.$rowname.''.$x.'%"';  
               $resultcheck = mysqli_query($conn, $sqlcheck);
                echo'
                <td>';
                if(mysqli_num_rows($resultcheck)==0){
                $onclickfunction="seat('".$rowname."".$x."')";
                echo' <img src="images/seat-available.png" alt="available" height="35" width="35" onclick="'.$onclickfunction.'" id="seat'.$rowname.''.$x.'"> ';
                echo'</td>';
                }

                if(mysqli_num_rows($resultcheck)==1){
                  echo' <img src="images/seat-booked.png" alt="booked" height="35" width="35"> ';
                  echo'</td>';
                }
              }
              $rowname++;
              echo'
              </tr>';
            } 
          }
          mysqli_close($conn); 
        ?>
    
      </table>
      <div class="alert alert-danger invisible" role="alert" id="alert">
        
      </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-bottom">
    <div class="container">
      <div class="col">
     <a class="navbar-brand">Selected Seat :</a>
     <a class="navbar-brand" id="selected-seat"></a>
   </div>
   <div class="col">
      <button type="button" class="btn btn-warning btn-lg" >Checkout</button>
    </div>
</nav>

    <br><br><br><br>
    </div>
    </center>

    <!-- selected seat script -->
    <script>  
    function seat(seatnumberselect){
    document.getElementById("alert").className= "alert alert-danger invisible";
     var seatnumberselectmem= document.getElementById("selected-seat").innerHTML;
       if(seatnumberselectmem.includes(seatnumberselect) && seatnumberselectmem!=seatnumberselect){
        var seatnumberselectmem = seatnumberselectmem.replace(","+seatnumberselect, "");
        var seatnumberselectmem = seatnumberselectmem.replace(seatnumberselect+",", "");
        document.getElementById("selected-seat").innerHTML=seatnumberselectmem;
        document.getElementById("seat"+seatnumberselect).src = "images/seat-available.png";
       }

       else if(seatnumberselectmem==seatnumberselect){
        var seatnumberselectmem = seatnumberselectmem.replace(seatnumberselect, "");
        document.getElementById("selected-seat").innerHTML=seatnumberselectmem;
        document.getElementById("seat"+seatnumberselect).src = "images/seat-available.png";
     }

     else if (seatnumberselectmem.length<=11){
       if(!seatnumberselectmem.includes(seatnumberselect) && seatnumberselectmem!=""){
        var seatnumberselectmem = seatnumberselectmem+","+seatnumberselect;
        document.getElementById("selected-seat").innerHTML=seatnumberselectmem;
        document.getElementById("seat"+seatnumberselect).src = "images/seat-selected.png";
       }

       else if(!seatnumberselectmem.includes(seatnumberselect) && seatnumberselectmem==""){
        var seatnumberselectmem = seatnumberselect;
        document.getElementById("selected-seat").innerHTML=seatnumberselectmem;
        document.getElementById("seat"+seatnumberselect).src = "images/seat-selected.png";
       }
      }
    else if ( seatnumberselectmem.length>=14 && !seatnumberselectmem.includes(seatnumberselect)){
      document.getElementById("alert").innerHTML="Telah mencapai maksimum kursi yang dapat dipilih";
      document.getElementById("alert").className= "alert alert-danger visible";
    }
  }
     
  
    </script>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
