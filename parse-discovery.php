<?php

class Plus {
  
  private $discovery;
  private $key;
  
  /**
   * Construct the Plus object by parsing the discovery document
   */
  public function __construct($key) {
    $this->discovery = json_decode(file_get_contents("plus.json"), true);
    $this->key = $key;
  }
  
  /** 
   * Retrieve an array of methods and arguments. 
   * 
   * Response is structured:
   * array( "resource.method (arg0, arg1, [arg2])", 
   *        "resource.method2 (arg0, arg1, [arg2])" ); 
   */
  public function getMethods() {
    $discovered = array();
    foreach($this->discovery['resources'] as $res => $methods) {
      // Merge discovered methods with all methods in the current resource
      foreach($methods['methods'] as $body) {
        // Get the list of parameters in appropriate order
        $param_list = $this->getParamList($body);
        $params = array_map(function ($param, $required) {
          return $required ? $param : sprintf("[%s]", $param);
        }, array_keys($param_list), $param_list);

        // Return plus.resource.method (arg0, arg1, [arg2])
        $discovered[] = sprintf("%s (%s)", $body['id'], implode(", ", $params));
      }
    }
    return $discovered;
  }
  
  /**
   * Call a method on the give resource type
   * @param method signature
   * @param arg0
   * @param arg1 etc.
   */
  public function call() {
    $args = func_get_args();
    list($plus, $resource, $method_name) = explode(".", $args[0]);
    
    // Retrieve the method description from the discovery doc
    $method = $this->discovery['resources'][$resource]['methods'][$method_name];
    $url = $this->discovery['baseUrl'] . $method['path'];
    
    // Prepare the arguments
    $arg_index = 1;
    $data = array("key" => $this->key);
    foreach($this->getParamList($method) as $param => $required) {
      if(!isset($args[$arg_index])) {
        break;
      }
      if($method['parameters'][$param]['location'] == 'path') {
        // If the argument lives in path, replace the placeholder
        $url = str_replace("{" . $param . "}", $args[$arg_index], $url);
      } else {
        // If the argument is otherwise, add it to the data array
        $data[$param] = $args[$arg_index];
      }
      $arg_index++;
    }
    
    // Debug output so we can see what's happening
    echo "Calling ", $url, " to ", strtolower($method['description']), "\n";
    
    $opts = array(
      'http'  => array(
        'method' => $method['httpMethod'],
      )
    );
    if($method['httpMethod'] == 'POST') {
      // If it's a POST call, then the data is the contents
      $opts['http']['content'] = http_build_query($data);
    } else {
      // Otherwise, specify arguments on the query string
      $url .= "?" . http_build_query($data);
    }
    
    $ctx = stream_context_create($opts);
    return json_decode(file_get_contents($url, false, $ctx), true);
  }
  
  /** 
   * Retrieve an ordered list of parameters
   */
  private function getParamList($method_body) {
    $params = array();
    foreach($method_body['parameterOrder'] as $param) {
      $params[$param] = true;
    }
    // Put [] round the optional parameters
    foreach($method_body['parameters'] as $param => $desc) {
      if(!isset($desc['required']) || !$desc['required']) {
        $params[$param] = false;
      }
    }
    return $params;
  }
}

$plus = new Plus("AIzaSyAlimP1duVdbwWoJRnb7IEs1mMuKiED52U");
// List the available methods
echo "\t\tLISTING METHODS\n\n";
var_dump($plus->getMethods());
exit;
// Call plus.activities.search "php"
echo "\n\t\tSEARCH FOR ACTIVITIES\n\n";
$recent = $plus->call("plus.activities.search", "whisky");
array_walk($recent['items'], function($a) { echo ">>> ", $a['title'], "\n";});

// Call again, with different ordering
echo "\n\t\tSEARCH FOR ACTIVITIES (ORDERED BY 'BEST')\n\n";
$best = $plus->call("plus.activities.search", "whisky", "en-US", 
10, "best");
array_walk($best['items'], function($a) { echo ">>> ", $a['title'], "\n";});






