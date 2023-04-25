# Contao MQTT Bundle

**InsertTags für MQTT-Topics**

Die Erweiterung verhält sich als MQTT-Client und unterstützt MQTT-Publish und MQTT-Subscribe für einzelne Topics

Die zu lesenden Daten müssen im MQTT-Broker als "Retained Message" vorliegen, damit der Broker sofort beim Subscribe den letzten Wert "Last known good" senden kann. Die Sensoren, die den Wert zum Broker publishen, müssen dazu "retain" setzen (ist meist einstellbar).


### Publish
`{{sendmqtt::topic::value}}`<br>
Einen Wert oder String an den MQTT-Broker published. Der Topic kann mehrstufig mit `/` getrennt sein.

Beispiel: `{{sendmqtt::foo/bar::5}}` setzt den Topic "Contao/domain.tld/foo/bar" auf den Wert 5


### Subscribe
`{{mqtt::topic::JSON-Variable}}`<br>
Ruft den Wert des angegebenen Topic vom Broker ab. Der Topic kann mehrstufig mit `/` getrennt sein. Teilweise findet man in dem Topic einen String im JSON-Format. Mit der JSON-Variable (optional) kann ein Wert aus einem Rückgabe-Array herausgelöst werden.

Beispiel: `{{mqtt::Tasmota/Abstand/tele/SENSOR::SR04/Distance}}` ruft den Topic "Tasmota/Abstand/tele/SENSOR" ab. Das Ergebnis ist ein Array mit mehreren Werten, daraus soll dann die Distance ausgelesen werden: `arrErgebnis['SR04']['Distance']`<br>
&nbsp;

---

## Installation

---

Installieren Sie die Erweiterung einfach mit dem **Contao Manager** oder auf der Kommandozeile mit dem **Composer**:
```
composer require do-while/contao-mqtt-bundle
```

## Zugangsdaten zum MQTT-Broker eintragen

Die Zugangsdaten für den MQTT-Broker werden in der Datei `config/config.yml` eingetragen:

```
contao:
    localconfig:
        mqtt_host: <IP des MQTT-Brokers>
        mqtt_port: 1883
        mqtt_clientid: 'contao'
        mqtt_user: <Username im MQTT-Broker>
        mqtt_pass: <Passwort im MQTT-Broker>

```
Einrücken jeweils mit 4 Leerzeichen, wenn die Struktur bereits vorhanden ist, können die neuen Einträge einfach ergänzt werden.
**Nach dem Speichern unbedingt den Symfony-Cache neu aufbauen!**<br>
&nbsp;


---

## Version

---

* 1.0.0<br>Freigabedatum: 2023-04-24<br>Version für Contao 4 ab Version 4.13 LTS

## Support

Softleister - Hagen Klemp, info@softleister.de


---
Stand: 2023-04-24
