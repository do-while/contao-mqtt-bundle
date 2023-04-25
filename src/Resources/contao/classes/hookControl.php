<?php

/**
 * @copyright  Softleister 2023
 * @author     Hagen Klemp <info@softleister.de>
 * @package    contao-mqtt-bundle
 * @license    LGPL3
 */

namespace Softleister\MqttBundle;

use Contao\Frontend;
use Contao\Config;
use Contao\Environment;
use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

class hookControl extends Frontend
{
    protected $result;

    //-----------------------------------------------------------------
    //  InsertTags abarbeiten
    //
    //  {{sendmqtt::topic/subtopic::value}}
    //  {{mqtt::topic/subtopic::json-variable}}
    //-----------------------------------------------------------------
    public function myReplaceInsertTags( $strTag )
    {
        $tag = explode( '::', $strTag );
        if( strtolower( $tag[0] ) === 'sendmqtt' ) {                            // IF( MQTT-Tag senden ? )

            if( isset( $tag[1] ) && !empty( $tag[1] ) && isset( $tag[2] ) ) {
                $mqtt = new MqttClient( Config::get('mqtt_host'), Config::get('mqtt_port'), Config::get('mqtt_clientid') );

                $connectionSettings = ( new ConnectionSettings )
                                            ->setUsername( Config::get('mqtt_user') ?? null )
                                            ->setPassword( Config::get('mqtt_pass') ?? null )
                                            ->setConnectTimeout( 3 );
                
                $mqtt->connect( $connectionSettings, true );
                $mqtt->publish( Config::get('mqtt_clientid') . '/' . Environment::get('host') . '/' . $tag[1], $tag[2] );
                $mqtt->disconnect( );

                return '';                                                      //   leere Ersetzung
            }

        }
        else if( strtolower( $tag[0] ) !== 'mqtt' ) return false;               // ELSE IF( kein MQTT-lesen ) nicht zuständig für fremde InsertTags

        // MQTT-Tag lesen
        if( isset( $tag[1] ) ) {

            $mqtt = new MqttClient( Config::get('mqtt_host'), Config::get('mqtt_port'), Config::get('mqtt_clientid') );

            $connectionSettings = ( new ConnectionSettings )
                                        ->setUsername( Config::get('mqtt_user') ?? null )
                                        ->setPassword( Config::get('mqtt_pass') ?? null )
                                        ->setConnectTimeout( 3 );
            
            $mqtt->connect( $connectionSettings, true );

            $mqtt->subscribe( $tag[1], 
                              function ( $topic, $message ) {
                                $this->result = [ 'topic' => $topic, 'value' => $message ];
                              }
                            );

            $loopStartedAt = microtime( true );
            while( (microtime( true ) - $loopStartedAt) < 0.1 ) {                           // max. 100 ms
                $mqtt->loopOnce( $loopStartedAt );
            }
            $mqtt->disconnect( );

            if( $this->result['value'][0] === '{' ) {                                       // IF( JSON-Inhalt )
                $this->result['value'] = json_decode( $this->result['value'], true );       //   JSON-Inhalt in Array umformen

                if( isset( $tag[2] ) ) {                                                    //   IF( JSON-Variable angegeben )
                    $vari = explode( '/', $tag[2] );                                        //     in Pfade zerlegen

                    $notfound = false;
                    $t = $this->result['value'];                                            //     (Teil-)Array
                    foreach( $vari AS $p ) {                                                //     FOREACH In das Array eintauchen
                        if( !array_key_exists( $p, $t ) ) {                                 //       IF Teilpfad nicht vorhanden
                            $notfound = true;                                               //         nicht gefunden
                            break;                                                          //         BREAK
                        }                                                                   //       ENDIF
                            
                        $t = $t[$p];                                                        //       in der Struktur tiefer gehen
                    }                                                                       //     ENDFOREACH
                    $this->result['value'] = $notfound ? '' : $t;                           //     Ergebnis speichern
                }                                                                           //   ENDIF
            }                                                                               // ENDIF

            return is_array( $this->result['value'] ) ? print_r( $this->result['value'], 1 ) : $this->result['value'];
        }

        return false;                                                           // kein bekannter InsertTag => nicht zuständig!
    }


    //-------------------------------------------------------------------------
}

