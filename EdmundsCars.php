<?php

namespace EdmundsCars;

const VERSION = '0.2.0';

/*
 * This is a PHP Clone of <https://github.com/ConnerMan/edmunds_cars>
 * Very little is changed except for the addition of a `get()` function
 * to replace the original Ruby class' dependency on httparty.
 *
 * @link https://github.com/ConnerMan/edmunds_cars
 *
 * -----------------------------------------------------------------------------
  Copyright (c) 2012 Conner Wingard

  Permission is hereby granted, free of charge, to any person obtaining
  a copy of this software and associated documentation files (the
  "Software"), to deal in the Software without restriction, including
  without limitation the rights to use, copy, modify, merge, publish,
  distribute, sublicense, and/or sell copies of the Software, and to
  permit persons to whom the Software is furnished to do so, subject to
  the following conditions:

  The above copyright notice and this permission notice shall be
  included in all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
  EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
  NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
  LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
  OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
  WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * -----------------------------------------------------------------------------
*/

# Edmunds ids for each make
const AM_GENERAL      = '200347864';
const ACURA           = '200002038';
const ASTON_MARTIN    = '200001769';
const AUDI            = '200000001';
const BMW             = '200000081';
const BENTLEY         = '200005848';
const BUGATTI         = '200030397';
const BUICK           = '200006659';
const CADILLAC        = '200001663';
const CHEVROLET       = '200000404';
const CHRYSLER        = '200003644';
const DAEWOO          = '200312185';
const DODGE           = '200009788';
const EAGLE           = '200347865';
const FIAT            = '200033022';
const FERRARI         = '200006023';
const FISKER          = '200005745';
const FORD            = '200005143';
const GMC             = '200007302';
const GEO             = '200347866';
const HUMMER          = '200004021';
const HONDA           = '200001444';
const HYUNDAI         = '200001398';
const INFINITI        = '200000089';
const ISUZU           = '200110731';
const JAGUAR          = '200003196';
const JEEP            = '200001510';
const KIA             = '200003063';
const LAMBORGHINI     = '200005922';
const LAND_ROVER      = '200006582';
const LEXUS           = '200001623';
const LINCOLN         = '200001777';
const LOTUS           = '200006242';
const MINI            = '200002305';
const MASERATI        = '200028029';
const MAYBACH         = '200043087';
const MAZDA           = '200004100';
const MCLAREN         = '200051397';
const MERCEDES_BENZ   = '200000130';
const MERCURY         = '200007711';
const MITSUBISHI      = '200002915';
const NISSAN          = '200000201';
const OLDSMOBILE      = '200249342';
const PANOZ           = '200194838';
const PLYMOUTH        = '200339126';
const PONTIAC         = '200002634';
const PORSCHE         = '200000886';
const RAM             = '200393150';
const ROLLS_ROYCE     = '200005044';
const SRT             = '200412738';
const SAAB            = '200074504';
const SATURN          = '200004446';
const SCION           = '200006515';
const SPYKER          = '200046567';
const SUBARU          = '200004491';
const SUZUKI          = '200001853';
const TESLA           = '200018920';
const TOYOTA          = '200003381';
const VOLKSWAGEN      = '200000238';
const VOLVO           = '200010382';
const SMART           = '200038885';

/**
 * All the classes below extend this class.
 */
class Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api";
    public $default_params = array('fmt' => 'json');
    static $api_key;

    function __construct($api_key = null) {
        $this->default_params['api_key'] = $api_key ?: self::$api_key;
    }

    function set_api_key($api_key) {
        self::$api_key = $api_key;
        $this->default_params['api_key'] = $api_key;
    }

    function get($path, array $params = array()) {
        $params = array_merge($params, $this->default_params);
        $params = str_replace('&amp;', '&', http_build_query($params, '', '&'));
        $url = $this->base_uri.$path.'?'.$params;
        $c = stream_context_create(array('http' => array('method' => 'GET')));
        $response = file_get_contents($url, null, $c);
        $response = json_decode($response);
        if(JSON_ERROR_NONE !== $err = json_last_error()) {
            throw new \RuntimeException('JSON Error: ['.$err.']');
        }
        return $response;
    }

}

/**
 * Get vehicle Depreciation info
 */
class Depreciation extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/tco/depreciation";

    function used_rates_by_styleid_and_zip($styleid, $zip) {
        return $this->get("/usedratesbystyleidandzip/$styleid/$zip");
    }

    function new_rates_by_styleid_and_zip($styleid, $zip) {
        return $this->get("/newratesbystyleidandzip/$styleid/$zip");
    }

}

/**
 * Get Consumer Reviews data
 */
class ConsumerReviews extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/crrrepository";

    function by_make_model_year($make, $model, $year) {
        return $this->get("/getcrrformakemodelyear", compact('make', 'model', 'year'));
    }

}

/**
 * Decode VIN Numbers
 */
class Vin extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/toolsrepository";

    function decode($vin) {
        return $this->get("/vindecoder", compact('vin'));
    }

}

/**
 * Get TMV (True Market Value)
 */
class TrueMarketValue extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/tmv/tmvservice";

    function typically_equipped($styleid, $zip) {
        return $this->get("/calculatetypicallyequippedusedtmv", compact('styleid', 'zip'));
    }

    function new_base($styleid, $zip) {
        return $this->get("/calculatenewtmv", compact('styleid', 'zip'));
    }

    function used($styleid, $condition, $mileage, $zip) {
        return $this->get("/calculateusedtmv", compact('styleid', 'condition', 'mileage', 'zip'));
    }

    function certified_price_for_style($styleid, $zip) {
        return $this->get("/findcertifiedpriceforstyle", compact('styleid', 'zip'));
    }

    function cpo_years_by_make($makeid) {
        return $this->get("/findcpoyearsbymake", compact('makeid'));
    }

}

/**
 * Get TCC (True Cost to Own)
 */
class TrueCostToOwn extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/tco";

    function new_by_style_id_and_zip($styleid, $zip) {
        return $this->get("/newtruecosttoownbystyleidandzip/$styleid/$zip");
    }

    function used_by_style_id_and_zip($styleid, $zip) {
        return $this->get("/usedtruecosttoownbystyleidandzip/$styleid/$zip");
    }

    function makes_with_data() {
        return $this->get("/getmakeswithtcodata");
    }

    function models_with_data($makeid) {
        return $this->get("/getmodelswithtcodata", compact('makeid'));
    }

    function styles_with_data_by_submodel($make, $model, $year, $submodel) {
        return $this->get("/getstyleswithtcodatabysubmodel", compact('make', 'model', 'year', 'submodel'));
    }

}

/**
 * FROM EDMUNDS DOCUMENTATION
 * The Total Cash Price displayed is the vehicle's True Market Value (TMV)
 * price plus typically equipped options,destination charge, base tax and
 * fees assessed by your state, and, if applicable, gas guzzler tax; less any
 * widely available manufacturer-to-customer cash rebates.
 * (However, we do not account for other types of cash rebates or incentives
 * because of the variability of those offers and their eligibility
 * requirements.)
 */
class TotalCashPrice extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/tco";

    function new_by_styleid_and_zip($styleid, $zip) {
        return $this->get("/newtotalcashpricebystyleidandzip/$styleid/$zip");
    }

    function used_by_styleid_and_zip($styleid, $zip) {
        return $this->get("/usedtotalcashpricebystyleidandzip/$styleid/$zip");
    }

}

/**
 * Get Style information.
 * Look up by make, model and year:
   returns a (typically quite large and detailed) JSON result.
 */
class Styles extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/vehicle/stylerepository";

    function by_id($id) {
        return $this->get("/findbyid", compact('id'));
    }

    function by_make_model_year($make, $model, $year) {
        return $this->get("/findstylesbymakemodelyear", compact('make', 'model', 'year'));
    }

    function by_model_year_id($modelyearid) {
        return $this->get("/findstylebymakemodelyearid", compact('modelyearid'));
    }

}

/**
 * Get Resale value info
 */
class Resale extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/tco";

    function by_styleid_and_zip($styleid, $zip) {
        return $this->get("/resalevaluesbystyleidandzip/$styleid/$zip");
    }

}

/**
 * Get Photo URLs
 */
class Photos extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/vehiclephoto/service";

    function by_style_id($styleId) {
        return $this->get("/findphotosbystyleid", compact('styleId'));
    }

}

/**
 * Get model info
 */
class Models extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/vehicle";

    function by_make_model($make, $model) {
        return $this->get("/vehicle/modelrepository/findmodelbymakemodelname", compact('make', 'model'));
    }

    function by_make_model_year($make, $model, $year = null) {
        if(!$year) $year = date('Y');
        return $this->get("/vehicle/$make/$model/$year");
    }

}

/**
 * Get Make info
 */
class Makes extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/vehicle/makerepository";

    function by_id($id) {
        return $this->get("/findbyid", compact('id'));
    }

    function all() {
        return $this->get("/findall");
    }

}

/**
 * Get Maintenance/recall info
 */
class Maintenance extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/maintenance";

    function service_bulletins_by_model_year_id($modelyearid) {
        return $this->get("/servicebulletinrepository/findbymodelyearid", compact('modelyearid'));
    }

    function service_bulletin_by_id($id) {
        return $this->get("/servicebulletin/$id");
    }

    function style_notes_by_id($id) {
        return $this->get("/stylenotes/$id");
    }

    function labor_rates_by_zip($zip) {
        return $this->get("/ziplaborrate/$zip");
    }

    function recall_by_id($id) {
        return $this->get("/recall/$id");
    }

    function recall_by_model_year_id($modelyearid) {
        return $this->get("/recallrepository", compact('modelyearid'));
    }

}

/**
 * Get current incentive info
 */
class Incentives extends Vehicles {

    public $base_uri = "http://api.edmunds.com/v1/api/incentive/incentiverepository";

    function by_style_id($styleid) {
        return $this->get("/findincentivesbystyleid", compact('styleid'));
    }

}

