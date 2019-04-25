<?php

require_once("lib/tpl.php");
require_once("Items.php");
require_once("SqLiteItemsDAO.php");


$cmd = isset($_GET["cmd"]) ? $_GET["cmd"] : "view";

$sqlItemsDao = new SqLiteItemsDAO();
//session_start();
//$newItems = new Items();

if ($cmd == "view") {

    $getData = $sqlItemsDao->getValues();
    $dataWithItems = ['$items' => $getData];
    print render_template("Main.html", $dataWithItems);
}
if ($cmd == "add") {
    $dataError = [];
    $getIncorrectData = [];

    if (isset($_POST['firstName'], $_POST['lastName'], $_POST['phone'])) {

        $data = $_POST["firstName"] . " " . $_POST["lastName"] . " " . $_POST["phone"] . "\n";


        /** Uncomment to get the PopUp.
            Testing for errors.
         */
//        $errors = errorMessage_validate_todo_item($_POST["firstName"]);
//        $errors1 = errorMessage_validate_todo_item($_POST["lastName"]);
//        $errors2 = errorMessage_validate_todo_item($_POST["phone"]);

//        if (count($errors2) === 0 && count($errors) === 0 && count($errors1) === 0) {

        $newItems = new Items($_POST["firstName"], $_POST["lastName"], $_POST["phone"]);

        if (($_POST['phone1'])) {
            $newItems->addPhonesTogether($_POST['phone1']);
        }
        if (($_POST['phone2'])) {
            $newItems->addPhonesTogether($_POST['phone2']);
        }
        if (($_POST['phone3'])) {
            $newItems->addPhonesTogether($_POST['phone3']);
        }
        $sqlItemsDao->getItemsFromTable($newItems);
        header("Location: ?cmd=view");
        die();

        /** Uncomment to get the PopUp. */
//        } else {
//
//            if (count($errors2) != 0) {
//                $dataError['$errors'] = $errors2;
//            } elseif (count($errors) != 0) {
//                $dataError['$errors'] = $errors;
//            } elseif (count($errors1) != 0) {
//                $dataError['$errors'] = $errors1;
//            }

        /** Uncomment to get the PopUp. */
//            $incorrectDataThatStaysInInputField = new Items($_POST["firstName"], $_POST["lastName"], $_POST["phone"]);
//            $getIncorrectData = ['$contactItems' => $incorrectDataThatStaysInInputField];
//            print render_template('errorMessage/errorMessagesPrinted.html', $dataError);
//        }

    }
//    print render_template("Input.html", $getIncorrectData);
    print render_template("Input.html");

}

/**
 * @param $item string.
 * @return array of error messages.
 */
function errorMessage_validate_todo_item($item)
{

    $errorMessages = [];

    if (empty($item)) {
        $errorMessages[] = "Väli ei või  olla tühi!";
    }
    if (strlen($item) < 1) {
        $errorMessages[] = "Pikkus peab olema vähemalt 3 tähemärki!";
    }
    if (strlen($item) > 14) {
        $errorMessages[] = "Pikkus peab olema väiksem kui 14!";
    }

    return $errorMessages;

}

function getItemsFromTxtFile()
{
    $myFile = file("dataBase/data.txt");

//    $contactItems = [];
    foreach ($myFile as $item) {

        $allItems = explode("/", $item);
        $firstName = $allItems[0];
        $lastName = $allItems[1];
        $phone = $allItems[2];

        $contactItems[] = new Items($firstName, $lastName, $phone);
    }
    return $contactItems;
}
