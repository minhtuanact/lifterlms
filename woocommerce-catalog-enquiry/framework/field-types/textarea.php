<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db
if(empty($options[ $name ])){
	$options[ $name ] = '';
}
echo "<textarea id='$id' class='" . ( empty( $class ) ? '' : $class ) . "' name='{$setting_id}[$name]'>" . $options[ $name ] . "</textarea><br>";