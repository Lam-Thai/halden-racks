<?php
/**
 * Script metadata WordPress reads for view.js: run after the 3D rack library, bust cache on edit.
 *
 * @package Halden
 */

return array(
	'dependencies' => array( 'halden-rack3d' ),
	'version'      => (string) filemtime( __DIR__ . '/view.js' ),
);
