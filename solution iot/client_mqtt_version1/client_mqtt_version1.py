# -*- utf-8 -*-

# device
import json
import logging
import paho.mqtt.client as mqtt

# configuration
mqttServer      = "mqtt.iut-blagnac.fr"
topic_am107     = "AM107/by-room/#"
topic_solaredge = "solaredge/blagnac/overview"
logging.basicConfig(level=logging.INFO)

# callback de réception des messages
def get_data(mqttc, obj, msg) :
    try :
        pass
    except (json.JSONDecodeError, KeyError) as e :
        logging.error("Erreur dans les données reçues : %s", e)

def on_connect(client, userdata, flags, reason_code, properties) :
    print(f"Connected with result code : {reason_code}")
    # abonnement aux topics
    client.subscribe(topic_am107)
    client.subscribe(topic_solaredge)

def on_message(client, userdata, message) :
    data = json.loads(message.payload)
    # affichage des données dans la console
    if message.topic.startswith("AM107/by-room") :
        print("\nDONNÉES CAPTEUR AM107")
        print("=====================")
        print("Salle", data[1]['room'])
        print("----------")
        print("Température :", data[0]['temperature'])
        print("Humidité :   ", data[0]['humidity'])
        print("Taux de CO2 :", data[0]['co2'])
    elif message.topic.startswith("solaredge/blagnac") :
        print("\nDONNÉES PANNEAUX SOLAIRES")
        print("=========================")
        print("Dernière mise à jour :", data['lastUpdateTime'])
        print("Power (puissance) :   ", data['currentPower']['power'])

# connexion et souscription
mqttc = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2)
mqttc.connect(mqttServer, port=1883, keepalive=60) # seul paramètre 1 obligatoire
mqttc.on_connect = on_connect
mqttc.on_message = on_message
mqttc.loop_forever()