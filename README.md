EdmundsCars
===========

A clone of ConnerMan/edmunds_cars in PHP

I used this with success. It's pretty much a direct translation of
[ConnerMan/edmunds_cars](https://github.com/ConnerMan/edmunds_cars),
which is written in Ruby, to PHP, so that I could use it on a project for work.
The main difference is the inclusion of a custom "get" method,
in lieu of the original's use of
[jnunemaker/httparty](https://github.com/jnunemaker/httparty), in
the main `EdmundsCars\Vehicles` class.

It is written with a target of PHP 5.3 or above, and the code is pretty straightforward,
if you need to use this, it should be easy to get the hang of.

Here's a small example, in the form of a function I am actually using:

```php
include 'EdmundsCars.php';

function get_modeL_results($car) {
  $api = new EdmundsCars\Styles;
  $api->set_api_key('YOUR-API-KEY-HERE');
  $res = $api->by_make_model_year($car['make'], $car['model'], $car['year']);
  $hash = sha1($json = json_encode($res));
  if(!is_dir('edmunds_json')) mkdir('edmunds_json');
  file_put_contents("edmunds_json/$hash.json", $json);
  return $json;
}

```

Note that, at least for this case, I used the "Vehicle" API key.
