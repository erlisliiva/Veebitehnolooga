<?php
/**
 * Created by PhpStorm.
 * User: Erlis
 * Date: 3/25/2019
 * Time: 11:08 PM
 */

class Items
{
    public $firstName;
    public $lastName;
    public $phone;

    /**
     * Items constructor.
     * @param $firstName
     * @param $lastName
     * @param $phone
     */
    public function __construct($firstName, $lastName, $phone){
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->addPhonesTogether($phone);
    }
    public function addPhonesTogether($phone){
        $this->phone[] = $phone;
    }

    function getAllPhonesTogether(){
        return implode(", ", $this->phone);
    }
}