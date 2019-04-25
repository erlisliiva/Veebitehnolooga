<?php
/**
 * Created by PhpStorm.
 * User: Erlis
 * Date: 4/12/2019
 * Time: 11:01 AM
 */

class SqLiteItemsDAO
{

    const URL = "sqlite:data.sqlite";

    private $connection;

    function __construct()
    {
        $this->connection = new PDO(self::URL);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createDbTables();
    }

    function createDbTables()
    {
        $sqlTable = "CREATE TABLE IF NOT EXISTS Item_names(
        id INTEGER PRIMARY KEY,
        firstName VARCHAR (255),
        lastName VARCHAR (255)
        );";
        $this->connection->exec($sqlTable);

        $sqlTableForPhones = "Create table if not EXISTS phones (
        item_id INTEGER ,
        phone varchar(255),
        FOREIGN KEY (item_id) REFERENCES Item_names(id)
        );";
        $this->connection->exec($sqlTableForPhones);

    }

    function getItemsFromTable($item){

        $statement = $this->connection->prepare("insert into Item_names(firstName, lastName) VALUES (:firstName, :lastName)");
        $statement->bindValue(':firstName', $item->firstName);
        $statement->bindValue('lastName',$item->lastName);
        $statement->execute();

        $item_id = $this->connection->lastInsertId();
        foreach ($item->phone as $phone){
            $statement = $this->connection->prepare("insert into phones(item_id, phone) VALUES (:item_id, :phone)");
            $statement->bindValue(':item_id', $item_id);
            $statement->bindValue(':phone', $phone);
            $statement->execute();


        }
    }

    function getValues(){
        $statement = $this->connection->prepare("select * from Item_names left join phones on Item_names.id = phones.item_id");
        $statement->execute();
        $items = [];

        foreach ($statement as $row){
            $id = $row['id'];
            if (isset($items[$id])){
                $items[$id]->addPhonesTogether($row['phone']);
            }else{
                $newItem = new Items($row["firstName"],$row["lastName"], $row["phone"]);
                $items[] = $newItem;
            }
        }
        return $items;
    }

}