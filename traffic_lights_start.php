<?php 

/**
 * @package    Mapanese (https://github.com/danielrhodeswarp/PHP-debug-traffic-lights)
 * @copyright  Copyright (c) 2012 Warp Asylum Ltd (UK).
 * @license    see LICENCE file in source code root folder     New BSD License
 */

//Put this file somewhere and set it as your PHP dev server's "auto_prepend_file"

//only really needed for non-xdebug timing
$traffic_lights_start_microtime = microtime(true);	//float

ob_start();