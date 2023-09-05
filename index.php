<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>7 days weather info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap");

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
      }
      
      body {
        background: aqua;

      }
      
      header {
        max-width: 100%;
        display: flex;
        justify-content: space-between;
        flex: nowrap;

      }
      
      header,
      .h1 {
        font-size: 22px;
        font-weight: 1000;
        color: #7f7870;
        color: #fff;
        text-shadow: 0 0 5px #ff2600;
      }
      
      ul {
        list-style-type: none;
      }

      #container {
        display: flex;
        gap: 20px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 5em;
      }
      
      .container1{
        margin-top: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 370px;
        width: 200px;
        background: #077c53;
        box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;
        border-radius: 15px;
        flex-wrap: wrap;
      }
      .extraData {
        display: flex;
        align-items: center;
        color: #fff;
        gap: 20px;
        margin-top: 15px;
      }
      .day{
        text-transform: capitalize;
        color: #fff;
      }
      .data {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        
      }
      .temp {
        color: #fff;
        font-size: 24px;
      }

      .desc {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        text-transform: capitalize;
      }
      
      .back{
        margin-top: 100px;
        display: flex;
        justify-content: center;
        text-align: center;
      }
      .more{
        padding: 7px 15px;
        border-radius: 1px;
      }
      .more a{
        color: #fff;
        text-decoration: none;
        font-size: 30px;
      }

      .more a:hover{
        box-shadow: inset 10px 0 0 0 #02a2ff;
        text-shadow: 0 0 5px #ffee10;
        border-radius: 30px;
      }
    </style>
  </head>
  <body>
    <header>
      <h1>
        Seven days Weather
      </h1>
    </header>

    <div id="show-default">
    <?php
        // Fetch data from OpenWeatherMap API
        $api_url = "https://api.openweathermap.org/data/2.5/weather?q=Bedford&units=metric&appid=d1a02306b7e6b7ea4e5a0c429ee4f9b5";
        $response = file_get_contents($api_url);
        $data = json_decode($response, true);
            
        // Parse data
        $day = date("l");
        $weatherIcon = $data['weather'][0]['icon'];
        $temperature = $data['main']['temp'];
        $description = $data['weather'][0]['description'];
        $humidity = $data['main']['humidity'];
        $pressure = $data['main']['pressure'];
        $wind = $data['wind']['speed'];
            
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $db = "weather_app";
        $port = "3307";
        //creating connection
        $conn = mysqli_connect($servername,$username,$password,$db,$port);
            
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Insert data into database
        $sql = "UPDATE prototype2 SET weathericon = ?, temperature = ?, description = ?, humidity = ?, pressure = ?, wind = ? WHERE days = ?";
        if ($stmt = $conn->prepare($sql)) {
          $stmt->bind_param("sdsdsss", $weatherIcon, $temperature, $description, $humidity, $pressure, $wind, $day);
        
          if ($stmt->execute()) {
            $result = mysqli_query($conn, "SELECT * FROM prototype2");
            // var_dump($result);
                
            echo "<section id='main'>";
            echo "<section id='container'>";
            while ($row = mysqli_fetch_assoc($result)){
              echo "<div class='container1'>";
              echo "<div class='day'>";
              echo "<h2>" .$row['days']."</h2>" ;
              echo "</div>";
              echo "<div class='data'>";
              echo "<img src='https://openweathermap.org/img/w/".$row['weathericon'].".png' height='85px' width='auto' alt='weather icon 1' class='weatherIcon icon1'>";
              echo "<h3 class='temp temp1'>".$row['temperature']." °C</h3>";
              echo "<h4 class='desc desc1'>".$row['description']."</h4>";
              echo "</div>";
              echo "<div class='extra'>";
              echo "<div class='extraData'>";
              echo "<i class='fa-solid fa-droplet'></i>";
              echo "<h4 class='humid1'>".$row['humidity']." %</h4>";
              echo "</div>";
              echo "<div class='extraData'>";
              echo "<i class='fa-solid fa-stopwatch-20'></i>";
              echo "<h4 class='pres1'>".$row['pressure']." hpa</h4>";
              echo "</div>";
              echo "<div class='extraData'>";
              echo "<i class='fa-solid fa-wind'></i>";
              echo "<h4 class='wind1'>".$row['wind']." km/h</h4>";
              echo "</div>";
              echo "</div>";
              echo "</div>";
            }
            echo '</section>';
            echo "<div class='back'>";
            echo "<div class='more'>";
            echo "<a href='index.html'>";
            echo "Back"; 
            echo "</a>";
            echo "</div>";
            echo "</div>";
            echo '</section>';
          } else {
            echo "Error: " . $sql . "<br>" . $stmt->error;
          }
          $stmt->close();
        }
        else {
          echo "Error preparing statement: " . $conn->error;
        }
        
        $conn->close();
        ?>
    </div>

  </body>
</html>