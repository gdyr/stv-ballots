<?php

  use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
  use Mike42\Escpos\Printer;

  require __DIR__ . '/vendor/autoload.php';

  $names = [];

  $role = readline("Position: ");

  do {
    $name = readline("Name: ");
    if($name) { $names[] = $name; }
  } while ($name);

  $ballots = (int) readline("Num ballots:");

  try {
    $connector = new CupsPrintConnector("ReceiptPrinter");
    $printer = new Printer($connector);

    for($i = 0; $i < $ballots; $i++) {

      $printer->feed(2);

      $printer->text(
        "VOTING BALLOT\n" . 
        "For the role of " . $role . "\n\n" .
        "Rank the candidates in order of preference:\n" .
        "  1 being your first preference, and \n" .
        "  " . count($names) . " being your last preference.\n\n"
      );


      foreach($names as $name) {
        $printer->text(
          str_repeat(" ", 35) . " ----\n" .
          str_repeat(" ", 5) . $name . str_repeat(" ", 30 - strlen($name)) . "|    |\n" .
          str_repeat(" ", 35) . " ----\n"
        );
        echo $name;
      }

      $printer->feed(5);

      $printer->cut();

    }

    $printer->close();

  } catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
  }
