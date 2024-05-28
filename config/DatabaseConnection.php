<?php
class DatabaseConnection {

    private static $connection;
  
    public static function getConnection() {
      if (self::$connection === null) {
        self::$connection = new mysqli("localhost", "filip", "Qwerty123!", "blaty");
        if (self::$connection->connect_error) {
          die("Connection failed: " . self::$connection->connect_error);
        }
      }
      return self::$connection;
    }
  
    public static function closeConnection() {
      if (self::$connection !== null) {
        self::$connection->close();
      }
    }
  }
  