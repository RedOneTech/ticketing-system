<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <script defer src="fontawesome/js/all.js"></script>
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
      </center>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-bottom">
    <div class="container">
      <div class="col-4">
     <a class="navbar-brand">Selected Seat :</a>
     <a class="navbar-brand" id="selected-seat"></a>
   </div>
   <div class="col-6">
   </div>
   <div class="col-2">
      <button type="button" class="btn btn-warning btn-lg" onclick="submitform()">Checkout</button>
    </div>
  </div>
</nav>

    <br><br><br><br>

    <form action="checkout" method="post" id="checkout">
    <input type="hidden" name="seat1" value="" id="seat1"></input>
    <input type="hidden" name="seat2" value="" id="seat2"></input>
    <input type="hidden" name="seat3" value="" id="seat3"></input>
    <input type="hidden" name="seat4" value="" id="seat4"></input>
    <input type="hidden" name="seat5" value="" id="seat5"></input>
  </form>
    </div>
    

    <!-- selected seat script -->
    <script>  
    var seatnumbercount = 0 ;
    var seatnumberselectmem = [];
    for ( var i = 1; i<=5;i++){
      document.getElementById("seat"+i).value="";
      document.getElementById("seat"+i).removeAttribute("disabled");
    }

    function seat(seatnumberselect){
    document.getElementById("alert").className= "alert alert-danger invisible";
       if(this.seatnumberselectmem.includes(seatnumberselect)){
        for( var i = 0; i < this.seatnumberselectmem.length; i++){ 
           if ( this.seatnumberselectmem[i] == seatnumberselect) {
            var seatnumberselecttmp = this.seatnumberselectmem;
            seatnumberselecttmp.splice(i, 1);
             seatnumberselectmem= seatnumberselecttmp; 
           }
        }
        seatnumbercount = this.seatnumbercount-1;
        document.getElementById("selected-seat").innerHTML=this.seatnumberselectmem.join();
        document.getElementById("seat"+seatnumberselect).src = "images/seat-available.png";
       }

     else if (this.seatnumbercount<5){
       if(!this.seatnumberselectmem.includes(seatnumberselect)){
        seatnumbercount = this.seatnumbercount+1;
        seatnumberselectmem.push(seatnumberselect);
        document.getElementById("selected-seat").innerHTML=this.seatnumberselectmem.join();
        document.getElementById("seat"+seatnumberselect).src = "images/seat-selected.png";
       }
      }
    else if ( seatnumbercount==5 && !this.seatnumberselectmem.includes(seatnumberselect)){
      document.getElementById("alert").innerHTML="Telah mencapai maksimum kursi yang dapat dipilih";
      document.getElementById("alert").className= "alert alert-danger visible";
    }
  }

  function submitform(){
    for(var i = 1 ; i<= this.seatnumberselectmem.length;i++){
      document.getElementById("seat"+i).value=this.seatnumberselectmem[i-1];
      document.getElementById("seat"+i).removeAttribute("disabled");
     }
     for(var i = this.seatnumberselectmem.length+1 ; i<= 5;i++){
      document.getElementById("seat"+i).setAttribute("disabled","");
      document.getElementById("seat"+i).value="";
     }
    document.getElementById("checkout").submit();
    
    }
  
    </script>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
