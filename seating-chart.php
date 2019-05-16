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
          $sql = 'SELECT column_number, row_number FROM seating_event_info WHERE event_name="'.$eventname.'"';
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
          }
          else{
            echo'
               <br><br>
               <div class="alert alert-danger" role="alert">
               Event Not Found
               </div>
            ';
          }

          mysqli_close($conn); 
          ?>
    <!-- event name and slog -->
    
    <br>

    <!-- table using php loop -->
    <center>
      <table border="1">
        <?php 
          if(isset($row)){
            for($i=1;$i<=$row;$i++){
              echo'<tr>';
              for($x=1;$x<=$column;$x++){
                echo'<td>';
                echo' <img src="images/seat-available.png" alt="available" height="42" width="42"> ';
                echo'</td>';
              }
              echo'</tr>';
            } 
          }
        ?>
    
      </table>
    </div>
    </center>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
