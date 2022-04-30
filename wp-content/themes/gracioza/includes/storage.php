<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage GRACIOZA
 * @since GRACIOZA 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('gracioza_storage_get')) {
	function gracioza_storage_get($var_name, $default='') {
		global $GRACIOZA_STORAGE;
		return isset($GRACIOZA_STORAGE[$var_name]) ? $GRACIOZA_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('gracioza_storage_set')) {
	function gracioza_storage_set($var_name, $value) {
		global $GRACIOZA_STORAGE;
		$GRACIOZA_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('gracioza_storage_empty')) {
	function gracioza_storage_empty($var_name, $key='', $key2='') {
		global $GRACIOZA_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($GRACIOZA_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($GRACIOZA_STORAGE[$var_name][$key]);
		else
			return empty($GRACIOZA_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('gracioza_storage_isset')) {
	function gracioza_storage_isset($var_name, $key='', $key2='') {
		global $GRACIOZA_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($GRACIOZA_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($GRACIOZA_STORAGE[$var_name][$key]);
		else
			return isset($GRACIOZA_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('gracioza_storage_inc')) {
	function gracioza_storage_inc($var_name, $value=1) {
		global $GRACIOZA_STORAGE;
		if (empty($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = 0;
		$GRACIOZA_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('gracioza_storage_concat')) {
	function gracioza_storage_concat($var_name, $value) {
		global $GRACIOZA_STORAGE;
		if (empty($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = '';
		$GRACIOZA_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('gracioza_storage_get_array')) {
	function gracioza_storage_get_array($var_name, $key, $key2='', $default='') {
		global $GRACIOZA_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($GRACIOZA_STORAGE[$var_name][$key]) ? $GRACIOZA_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($GRACIOZA_STORAGE[$var_name][$key][$key2]) ? $GRACIOZA_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('gracioza_storage_set_array')) {
	function gracioza_storage_set_array($var_name, $key, $value) {
		global $GRACIOZA_STORAGE;
		if (!isset($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = array();
		if ($key==='')
			$GRACIOZA_STORAGE[$var_name][] = $value;
		else
			$GRACIOZA_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('gracioza_storage_set_array2')) {
	function gracioza_storage_set_array2($var_name, $key, $key2, $value) {
		global $GRACIOZA_STORAGE;
		if (!isset($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = array();
		if (!isset($GRACIOZA_STORAGE[$var_name][$key])) $GRACIOZA_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$GRACIOZA_STORAGE[$var_name][$key][] = $value;
		else
			$GRACIOZA_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('gracioza_storage_merge_array')) {
	function gracioza_storage_merge_array($var_name, $key, $value) {
		global $GRACIOZA_STORAGE;
		if (!isset($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = array();
		if ($key==='')
			$GRACIOZA_STORAGE[$var_name] = array_merge($GRACIOZA_STORAGE[$var_name], $value);
		else
			$GRACIOZA_STORAGE[$var_name][$key] = array_merge($GRACIOZA_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('gracioza_storage_set_array_after')) {
	function gracioza_storage_set_array_after($var_name, $after, $key, $value='') {
		global $GRACIOZA_STORAGE;
		if (!isset($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = array();
		if (is_array($key))
			gracioza_array_insert_after($GRACIOZA_STORAGE[$var_name], $after, $key);
		else
			gracioza_array_insert_after($GRACIOZA_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('gracioza_storage_set_array_before')) {
	function gracioza_storage_set_array_before($var_name, $before, $key, $value='') {
		global $GRACIOZA_STORAGE;
		if (!isset($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = array();
		if (is_array($key))
			gracioza_array_insert_before($GRACIOZA_STORAGE[$var_name], $before, $key);
		else
			gracioza_array_insert_before($GRACIOZA_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('gracioza_storage_push_array')) {
	function gracioza_storage_push_array($var_name, $key, $value) {
		global $GRACIOZA_STORAGE;
		if (!isset($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($GRACIOZA_STORAGE[$var_name], $value);
		else {
			if (!isset($GRACIOZA_STORAGE[$var_name][$key])) $GRACIOZA_STORAGE[$var_name][$key] = array();
			array_push($GRACIOZA_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('gracioza_storage_pop_array')) {
	function gracioza_storage_pop_array($var_name, $key='', $defa='') {
		global $GRACIOZA_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($GRACIOZA_STORAGE[$var_name]) && is_array($GRACIOZA_STORAGE[$var_name]) && count($GRACIOZA_STORAGE[$var_name]) > 0) 
				$rez = array_pop($GRACIOZA_STORAGE[$var_name]);
		} else {
			if (isset($GRACIOZA_STORAGE[$var_name][$key]) && is_array($GRACIOZA_STORAGE[$var_name][$key]) && count($GRACIOZA_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($GRACIOZA_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('gracioza_storage_inc_array')) {
	function gracioza_storage_inc_array($var_name, $key, $value=1) {
		global $GRACIOZA_STORAGE;
		if (!isset($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = array();
		if (empty($GRACIOZA_STORAGE[$var_name][$key])) $GRACIOZA_STORAGE[$var_name][$key] = 0;
		$GRACIOZA_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('gracioza_storage_concat_array')) {
	function gracioza_storage_concat_array($var_name, $key, $value) {
		global $GRACIOZA_STORAGE;
		if (!isset($GRACIOZA_STORAGE[$var_name])) $GRACIOZA_STORAGE[$var_name] = array();
		if (empty($GRACIOZA_STORAGE[$var_name][$key])) $GRACIOZA_STORAGE[$var_name][$key] = '';
		$GRACIOZA_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('gracioza_storage_call_obj_method')) {
	function gracioza_storage_call_obj_method($var_name, $method, $param=null) {
		global $GRACIOZA_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($GRACIOZA_STORAGE[$var_name]) ? $GRACIOZA_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($GRACIOZA_STORAGE[$var_name]) ? $GRACIOZA_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('gracioza_storage_get_obj_property')) {
	function gracioza_storage_get_obj_property($var_name, $prop, $default='') {
		global $GRACIOZA_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($GRACIOZA_STORAGE[$var_name]->$prop) ? $GRACIOZA_STORAGE[$var_name]->$prop : $default;
	}
}
?>