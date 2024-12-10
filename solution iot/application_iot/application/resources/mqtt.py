import json
import os
import logging
import paho.mqtt.client as mqtt
import configparser
import csv
import logging



# configuration
configuration = configparser.ConfigParser()
configuration.read('resources\\configuration.ini')

# récupération des paramètres
serveurMQTT = configuration.get('MQTT', 'broker')
port = int(configuration.get('MQTT', 'port'))
base_topic = configuration.get('MQTT', 'topic')

logging.basicConfig(level=logging.INFO)
logging.info("MQTT script started.")

# récupération des sujets
sujets = [value for key, value in configuration.items('SUJET')]

# récupération des seuils
seuils = {
    'temperature': float(configuration.get('THRESHOLD', 'temperature')),
    'humidity': float(configuration.get('THRESHOLD', 'humidity')),
    'co2': float(configuration.get('THRESHOLD', 'co2')),
    'tvoc': float(configuration.get('THRESHOLD', 'tvoc')),
    'infrared_and_visible': float(configuration.get('THRESHOLD', 'infrared_and_visible'))
}

# configuration du logging
logging.basicConfig(level=logging.DEBUG)

def clear_csv_files():
    # Vider le fichier global
    data_file = 'resources\\data\\data.csv'
    with open(data_file, 'w', newline='') as csvfile:
        writer = csv.writer(csvfile, delimiter=';')
        writer.writerow(['room', 'temperature', 'humidity', 'co2', 'tvoc', 'infrared_and_visible'])

    # Vider le fichier d'alertes
    alert_file = 'resources\\data\\alert.csv'
    with open(alert_file, 'w', newline='') as csvfile:
        writer = csv.writer(csvfile, delimiter=';')
        writer.writerow(['room', 'dataType', 'threshold', 'measuredValue'])

    # Vider les fichiers de chaque salle
    for sujet in sujets:
        room_file = f'resources\\data\\{sujet}.csv'
        with open(room_file, 'w', newline='') as csvfile:
            writer = csv.writer(csvfile, delimiter=';')
            writer.writerow(['room', 'temperature', 'humidity', 'co2', 'tvoc', 'infrared_and_visible'])

# Vider les fichiers CSV au démarrage
clear_csv_files()

def on_connect(client, userdata, flags, reason_code, properties=None):
    logging.info(f"Connected with result code : {reason_code}")
    # abonnement aux topics
    for sujet in sujets:
        topic = f"{base_topic}{sujet}/#" #retiré pour obtenir les données de toutes les salles REMETTRE POUR FINALISER
        #topic = f"{base_topic}#"
        logging.info(f"Subscribing to topic: {topic}")
        client.subscribe(topic)

def on_message(client, userdata, message):
    logging.info(f"Message received on topic: {message.topic}")
    try:
        data = json.loads(message.payload)
        
        if message.topic.startswith("AM107/by-room"):
            room = data[1]['room']
            temperature = data[0]['temperature']
            humidity = data[0]['humidity']
            co2 = data[0]['co2']
            tvoc = data[0].get('tvoc', 0)
            infrared_and_visible = data[0].get('infrared_and_visible', 0)

            # Affichage des données dans la console
            print(f"Salle : {room}")
            print(f"Température : {temperature}")
            print(f"Humidité : {humidity}")
            print(f"Taux de CO2 : {co2}")
            print(f"TVOC : {tvoc}")
            print(f"Infrarouge et visible : {infrared_and_visible}")

            # Lire les données existantes de data.csv
            data_file = 'resources\\data\\data.csv'
            data_dict = {}
            if os.path.isfile(data_file):
                with open(data_file, 'r', newline='') as csvfile:
                    reader = csv.DictReader(csvfile, delimiter=';')
                    for row in reader:
                        data_dict[row['room']] = row

            # Mettre à jour les données de la salle concernée
            data_dict[room] = {
                'room': room,
                'temperature': temperature,
                'humidity': humidity,
                'co2': co2,
                'tvoc': tvoc,
                'infrared_and_visible': infrared_and_visible
            }

            # Écrire les données mises à jour dans data.csv
            with open(data_file, 'w', newline='') as csvfile:
                fieldnames = ['room', 'temperature', 'humidity', 'co2', 'tvoc', 'infrared_and_visible']
                writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=';')
                writer.writeheader()
                for row in data_dict.values():
                    writer.writerow(row)

            # Mise à jour du fichier de la salle
            room_file = f'resources\\data\\{room}.csv'
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
                with open('resources\\data\\alert.csv', 'a', newline='') as csvfile:
                    writer = csv.writer(csvfile, delimiter=';')
                    for alert in alerts:
                        writer.writerow(alert)
    except (json.JSONDecodeError, KeyError) as e:
        logging.error(f"Erreur dans les données reçues : {e}")

# connexion et souscription
mqttc = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2)
mqttc.connect(serveurMQTT, port=port, keepalive=60)
logging.info(f"Connecting to MQTT broker at {serveurMQTT}:{port}")
mqttc.on_connect = on_connect
mqttc.on_message = on_message
mqttc.loop_forever()