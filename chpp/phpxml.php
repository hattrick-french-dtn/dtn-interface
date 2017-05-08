<?php

// http://www.devdump.com/phpxml.php

function printa($obj) {
  global $__level_deep;
  if (!isset($__level_deep)) $__level_deep = array();

  if (is_object($obj))
    print '[obj]';
  elseif (is_array($obj)) {
    foreach(array_keys($obj) as $keys) {
      array_push($__level_deep, "[".$keys."]");
      printa($obj[$keys]);
      array_pop($__level_deep);
    }
  }
  else print implode(" ",$__level_deep)." = $obj";
}

function GetChildren($vals, &$i) 
{ 
  $children = array();     // Contains node data
  
  /* Node has CDATA before it's children */
  if (isset($vals[$i]['value'])) 
    $children['VALUE'] = $vals[$i]['value']; 
  
  /* Loop through children */
  while (++$i < count($vals))
  { 
    switch ($vals[$i]['type']) 
    { 
      /* Node has CDATA after one of it's children 
        (Add to cdata found before if this is the case) */
      case 'cdata': 
        if (isset($children['VALUE']))
          $children['VALUE'] .= $vals[$i]['value']; 
        else
          $children['VALUE'] = $vals[$i]['value']; 
        break;
      /* At end of current branch */ 
      case 'complete': 
        if (isset($vals[$i]['attributes'])) {
          $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
          $index = count($children[$vals[$i]['tag']])-1;

          if (isset($vals[$i]['value'])) 
            $children[$vals[$i]['tag']][$index]['VALUE'] = $vals[$i]['value']; 
          else
            $children[$vals[$i]['tag']][$index]['VALUE'] = ''; 
        } else {
          if (isset($vals[$i]['value'])) 
            $children[$vals[$i]['tag']][]['VALUE'] = $vals[$i]['value']; 
          else
            $children[$vals[$i]['tag']][]['VALUE'] = ''; 
		}
        break; 
      /* Node has more children */
      case 'open': 
        if (isset($vals[$i]['attributes'])) {
          $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
          $index = count($children[$vals[$i]['tag']])-1;
          $children[$vals[$i]['tag']][$index] = array_merge($children[$vals[$i]['tag']][$index],GetChildren($vals, $i));
        } else {
          $children[$vals[$i]['tag']][] = GetChildren($vals, $i);
        }
        break; 
      /* End of node, return collected data */
      case 'close': 
        return $children; 
    } 
  } 
} 

/* Function to transform an XML file into a tree (array) */
function GetXMLTree($data) 
{ 
  
  $parser = xml_parser_create();
  xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
  xml_parse_into_struct($parser, $data, $vals, $index); 
  xml_parser_free($parser); 

  $tree = array(); 
  $i = 0; 
  
  if (isset($vals[$i]['attributes'])) {
	$tree[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes']; 
	$index = count($tree[$vals[$i]['tag']])-1;
	$tree[$vals[$i]['tag']][$index] =  array_merge($tree[$vals[$i]['tag']][$index], GetChildren($vals, $i));
  }
  else
    $tree[$vals[$i]['tag']][] = GetChildren($vals, $i); 
  
  return $tree; 
} 



// fin http://www.devdump.com/phpxml.php
?>