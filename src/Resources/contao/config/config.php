<?php

/**
 * @copyright  Softleister 2023
 * @author     Hagen Klemp <info@softleister.de>
 * @package    contao-mqtt-bundle
 * @license    LGPL3
 */


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('Softleister\MqttBundle\hookControl', 'myReplaceInsertTags');

