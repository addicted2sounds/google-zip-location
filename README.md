google-zip-location
===================

Library connected with google.api. Helps easily get location by zip

Usage
===================

90001 - example of zip

classic:
1) $obj = new ZipLocation(90001);
$location = $obj->getLocation();

factory:
2) ZipLocation::setZip(90001)->getLocation();

