<?php

/**
 * @copyright  Softleister 2023
 * @author     Hagen Klemp <info@softleister.de>
 * @package    contao-mqtt-bundle
 * @license    LGPL3
 */

namespace Softleister\MqttBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Softleister\MqttBundle\SoftleisterMqttBundle;

/**
 * Plugin for the Contao Manager.
 *
 * @author Hagen Klemp
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(SoftleisterMqttBundle::class)
                ->setLoadAfter( ['Contao\CoreBundle\ContaoCoreBundle'] )
            ];
    }
}
