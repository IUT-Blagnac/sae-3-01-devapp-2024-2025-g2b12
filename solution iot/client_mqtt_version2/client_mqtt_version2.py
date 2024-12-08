import json
import os
import logging
import paho.mqtt.client as mqtt
import configparser
import csv

# configuration
configuration = configparser.ConfigParser()
configuration.read('client_mqtt_version2/configuration.ini')

# récupération des paramètres
serveurMQTT = configuration.get('MQTT', 'broker')
port = int(configuration.get('MQTT', 'port'))
base_topic = configuration.get('MQTT', 'topic')

# récupération des sujets
sujets = [value for key, value in configuration.items('SUJET')]

# récupération des seuils
seuils = {
    'temperature': float(configuration.get('SEUILS', 'temperature')),
    'humidity': float(configuration.get('SEUILS', 'humidity')),
    'co2': float(configuration.get('SEUILS', 'co2')),
    'tvoc': float(configuration.get('SEUILS', 'tvoc')),
    'infrared_and_visible': float(configuration.get('SEUILS', 'infrared_and_visible'))
}

# callback de réception des messages
def get_data(mqttc, obj, msg):
    try:
        pass
    except (json.JSONDecodeError, KeyError) as e:
        logging.error("Erreur dans les données reçues : %s", e)

def on_connect(client, userdata, flags, reason_code, properties):
    print(f"Connected with result code : {reason_code}")
    # abonnement aux topics
    for sujet in sujets:
        client.subscribe(f"{base_topic}{sujet}")

def on_message(client, userdata, message):
    data = json.loads(message.payload)
    if message.topic.startswith("AM107/byroom"):
        room = data[1]['room']
        temperature = data[0]['temperature']
        humidity = data[0]['humidity']
        co2 = data[0]['co2']
        tvoc = data[0].get('tvoc', 0)
        infrared_and_visible = data[0].get('infrared_and_visible', 0)

        # Mise à jour du fichier global
        with open('data.csv', 'w', newline='') as csvfile:
            writer = csv.writer(csvfile, delimiter=';')
            writer.writerow(['room', 'temperature', 'humidity', 'co2', 'tvoc', 'infrared_and_visible'])
            writer.writerow([room, temperature, humidity, co2, tvoc, infrared_and_visible])

        # Mise à jour du fichier de la salle
        room_file = f'{room}.csv'
        file_exists = os.path.isfile(room_file)
        with open(room_file, 'a', newline='') as csvfile:
            writer = csv.writer(csvfile, delimiter=';')
            if not file_exists:
                writer.writerow(['room', 'temperature', 'humidity', 'co2', 'tvoc', 'infrared_and_visible'])
            writer.writerow([room, temperature, humidity, co2, tvoc, infrared_and_visible])

        # Vérification des seuils et mise à jour du fichier d'alertes
        alerts = []
        if temperature > seuils['temperature']:
            alerts.append([room, 'temperature', seuils['temperature'], temperature])
        if humidity > seuils['humidity']:
            alerts.append([room, 'humidity', seuils['humidity'], humidity])
        if co2 > seuils['co2']:
            alerts.append([room, 'co2', seuils['co2'], co2])
        if tvoc > seuils['tvoc']:
            alerts.append([room, 'tvoc', seuils['tvoc'], tvoc])
        if infrared_and_visible > seuils['infrared_and_visible']:
            alerts.append([room, 'infrared_and_visible', seuils['infrared_and_visible'], infrared_and_visible])

        if alerts:
            with open('alerts.csv', 'a', newline='') as csvfile:
                writer = csv.writer(csvfile, delimiter=';')
                for alert in alerts:
                    writer.writerow(alert)

    elif message.topic.startswith("solaredge/blagnac"):
        print("\nDONNÉES PANNEAUX SOLAIRES")
        print("=========================")
        print("Dernière mise à jour :", data['lastUpdateTime'])
        print("Power (puissance) :   ", data['currentPower']['power'])

# connexion et souscription
mqttc = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2)
mqttc.connect(serveurMQTT, port=port, keepalive=60) # seul paramètre 1 obligatoire
mqttc.on_connect = on_connect
mqttc.on_message = on_message
mqttc.loop_forever()