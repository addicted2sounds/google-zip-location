<?php
/**
 * Created by JetBrains PhpStorm.
 * User: astolovitsky
 * Date: 8/6/14
 * Time: 2:57 PM
 * To change this template use File | Settings | File Templates.
 */

class ZipLocation {

  const GOOGLE_API_URL = 'http://maps.googleapis.com/maps/api/geocode/json';
  const GOOGLE_API_ZIP_PARAM = 'address';

  /**
   * @var null|int
   */
  private $_zip = null;

  /**
   * @var null|stdClass
   */
  private $_data = null;

  /**
   * @param $zip int
   * @throws Exception
   */
  public function __construct($zip) {
    if(! (int)$zip == $zip || ! is_numeric($zip))
      throw new Exception('ZIP must be integer');

    $this->_zip = $zip;
  }

  /**
   * @param $zip int
   * @return ZipLocation
   */
  public static function setZip($zip) {
    $class = new ZipLocation($zip);
    return $class;
  }

  /**
   * @return array
   */
  private function getData() {
    if(! $this->_data)
      $this->sendRequest();

    return $this->_data;
  }

  /**
   * @return array
   */
  private function sendRequest() {
    $getString = http_build_query(array(self::GOOGLE_API_ZIP_PARAM => $this->_zip));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::GOOGLE_API_URL.'?'.$getString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    $this->_data = $this->validateData($data);
  }

  /**
   * @param $data stdClass
   * @throws Exception
   */
  private function validateData($data) {
    $dataObject = json_decode($data);

    foreach($dataObject->results as $result)
      foreach($result->address_components as $addressComponent)
        if(in_array('postal_code', $addressComponent->types) && $addressComponent->short_name == $this->_zip)
          return $result;

    throw new Exception('Cant find location with zip '.$this->_zip);
  }

  /**
   * @return array
   */
  public function getLocation() {
    return $this->getData();
  }

}