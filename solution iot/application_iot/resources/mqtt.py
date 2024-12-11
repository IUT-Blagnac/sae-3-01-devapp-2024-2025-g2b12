import json
import os
import logging
import paho.mqtt.client as mqtt
import configparser
import csv
import logging
import sys


if os.name == 'posix' :
    # sous mac os / linux
    config_file_path =  "resources/configuration.ini"
    data_files_path =   "resources/data/"
else :
    # sous windows
    config_file_path =  "resources\\configuration.ini"
    data_files_path =   "resources\\data\\"

# configuration
configuration = configparser.ConfigParser()
configuration.read(config_file_path)

# récupération des paramètres
serveurMQTT = configuration.get('MQTT', 'broker')
port = int(configuration.get('MQTT', 'port'))
base_topic = configuration.get('MQTT', 'topic')

logging.basicConfig(level=logging.INFO)
logging.info("MQTT script started.")

# récupération des sujets
sujets = [value for key, value in configuration.items('SUBJECTS')]

# récupération des types de données
data_types = [value for key, value in configuration.items('DATA_TYPE')]

# récupération des seuils
seuils = {key: float(value) for key, value in configuration.items('THRESHOLD')}

# configuration du logging
logging.basicConfig(level=logging.DEBUG)

def clear_csv_files():
    try:
        # Vider le fichier global
        data_file = data_files_path + 'data.csv'
        with open(data_file, 'w', newline='') as csvfile:
            writer = csv.writer(csvfile, delimiter=';')
            writer.writerow(['room'] + data_types)
        logging.info(f"Cleared {data_file}")

        # Vider le fichier d'alertes
        alert_file = data_files_path + 'alert.csv'
        with open(alert_file, 'w', newline='') as csvfile:
            writer = csv.writer(csvfile, delimiter=';')
            writer.writerow(['room', 'dataType', 'threshold', 'measuredValue'])
        logging.info(f"Cleared {alert_file}")

        # Vider les fichiers de chaque salle
        for sujet in sujets:
            room_file = f'{data_files_path}{sujet}.csv'
            with open(room_file, 'w', newline='') as csvfile:
                writer = csv.writer(csvfile, delimiter=';')
                writer.writerow(['room'] + data_types)
            logging.info(f"Cleared {room_file}")

    except Exception as e:
        logging.error(f"Error clearing CSV files: {e}")

#clear_csv_files()

def read_and_filter_csv(file_path, filter_rooms):
    if file_path == data_files_path + 'data.csv':
        # Vider le fichier data.csv au début
        with open(file_path, 'w', newline='') as csvfile:
            writer = csv.writer(csvfile, delimiter=';')
            writer.writerow(['room'] + data_types)
        
        # Ajouter la dernière ligne de chaque {sujet}.csv
        for sujet in filter_rooms:
            room_file = f'{data_files_path}{sujet}.csv'
            if os.path.isfile(room_file):
                with open(room_file, 'r', newline='') as csvfile:
                    reader = csv.DictReader(csvfile, delimiter=';')
                    rows = list(reader)
                    if rows:
                        with open(file_path, 'a', newline='') as data_csvfile:
                            writer = csv.writer(data_csvfile, delimiter=';')
                            writer.writerow(rows[-1].values())
    
    elif file_path == data_files_path + 'alert.csv':
        alert1_file = data_files_path + 'alert1.csv'
        if not os.path.isfile(alert1_file):
            if os.path.isfile(file_path):
                os.rename(file_path, alert1_file)
        
        with open(file_path, 'w', newline='') as csvfile:
            writer = csv.writer(csvfile, delimiter=';')
            writer.writerow(['room', 'dataType', 'threshold', 'measuredValue'])
        
        if os.path.isfile(alert1_file):
            with open(alert1_file, 'r', newline='') as csvfile:
                reader = csv.DictReader(csvfile, delimiter=';')
                for row in reader:
                    if row['room'] in filter_rooms:
                        with open(file_path, 'a', newline='') as alert_csvfile:
                            writer = csv.writer(alert_csvfile, delimiter=';')
                            writer.writerow(row.values())

read_and_filter_csv(data_files_path+'data.csv', sujets)
read_and_filter_csv(data_files_path+'alert.csv', sujets)

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
            if room not in sujets:
                return  # Ignore messages from rooms not in the selected subjects

            data_values = {data_type: data[0].get(data_type, 0) for data_type in data_types}

            # Affichage des données dans la console
            print(f"Salle : {room}")
            for data_type, value in data_values.items():
                print(f"{data_type} : {value}")

            # Lire et filtrer les données existantes de data.csv
            data_file = data_files_path + 'data.csv'
            read_and_filter_csv(data_file, sujets)

            # Mettre à jour les données de la salle concernée
            data_dict = {}
            if os.path.isfile(data_file):
                with open(data_file, 'r', newline='') as csvfile:
                    reader = csv.DictReader(csvfile, delimiter=';')
                    data_dict = {row['room']: row for row in reader}

            data_dict[room] = {'room': room}
            data_dict[room].update(data_values)

            # Écrire les données mises à jour dans data.csv
            with open(data_file, 'w', newline='') as csvfile:
                fieldnames = ['room'] + data_types
                writer = csv.DictWriter(csvfile, fieldnames=fieldnames, delimiter=';')
                writer.writeheader()
                for row in data_dict.values():
                    writer.writerow(row)

            # Mise à jour du fichier de la salle
            room_file = f'{data_files_path}{room}.csv'
            file_exists = os.path.isfile(room_file)
            with open(room_file, 'a', newline='') as csvfile:
                writer = csv.writer(csvfile, delimiter=';')
                if not file_exists:
                    writer.writerow(['room'] + data_types)
                writer.writerow([room] + [data_values[data_type] for data_type in data_types])

            # Lire et filtrer les alertes existantes de alert.csv
            alert_file = data_files_path + 'alert.csv'
            read_and_filter_csv(alert_file, sujets)

            # Vérification des seuils et mise à jour du fichier d'alertes
            alerts = []
            for data_type in data_types:
                if data_values[data_type] > seuils.get(data_type, float('inf')):
                    alerts.append([room, data_type, seuils[data_type], data_values[data_type]])

            if alerts:
                with open(alert_file, 'a', newline='') as csvfile:
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