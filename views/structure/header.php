<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2137</title>
    <style>
* {box-sizing: border-box;}

body { 
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.header {
  overflow: hidden;
  background-color: #f1f1f1;
  padding: 10px 10px;
}

.header a {
  float: left;
  color: black;
  text-align: center;
  padding: 12px;
  text-decoration: none;
  font-size: 18px; 
  line-height: 15px;
  border-radius: 4px;
}

.header a.logo {
  font-size: 25px;
  font-weight: bold;
}

.header a:hover {
  background-color: #ddd;
  color: black;
}

.header a.active {
  background-color: dodgerblue;
  color: white;
}

.header-right {
  float: right;
}

@media screen and (max-width: 500px) {
  .header a {
    float: none;
    display: block;
    text-align: left;
  }
  
  .header-right {
    float: none;
  }
}
</style>
    
</head>
<body>
<div class="header">
  <a href="#default" class="logo">Dekor-Stone</a>
  <div class="header-right">
    <a class="active" href="/index.php">Zamówienia</a>
    <a href="/views/archive_view.php">Archiwum</a>
    <a href="/views/calculator_view.php">Kalkulator</a>
    <a href="/controllers/api_connect.php">Zamówienia Allegro</a>
    <a href="/views/get_order_id_for_label_creation.php">Stwórz etykiety</a>
    <a href="/controllers/logout.php">Wyloguj</a>
  </div>
</div>