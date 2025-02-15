import requests
import mysql.connector

# Connexion à la base de données
conn = mysql.connector.connect(
    host="mysql",  # Remplace par ton hôte
    user="test",  # Remplace par ton utilisateur de base de données
    password="yannel",  # Remplace par ton mot de passe
    database="user"  # Remplace par le nom de ta base de données
)
cursor = conn.cursor(dictionary=True)

# URL de l'API
url_api = "http://flask-app:5000/stations"

# Requête à l'API pour récupérer les données JSON
response = requests.get(url_api)
stations = response.json()

nbInsertion=0
nbUpdate=0

for station in stations:
    station_id = station['station_id']
    lat = station['lat']
    lon = station['lon']
    name = station['name']

    # Vérifier si la clé primaire existe déjà
    cursor.execute("SELECT COUNT(*) FROM stations WHERE station_id = %s", (station_id,))
    result = cursor.fetchone()

    if result['COUNT(*)'] == 0 :
        # Si la station n'existe pas, insérer une nouvelle entrée
        try:
            cursor.execute(
                "INSERT INTO stations (station_id, lat, lon, station) VALUES (%s, %s, %s, %s)",
                (station_id, lat, lon, name)
            )
            
            nbInsertion += 1
        except mysql.connector.IntegrityError as e:
            print(f"Erreur d'insertion pour la station ID {station_id}: {e}")
    else:
        cursor.execute("SELECT lat, lon, station FROM stations WHERE station_id = %s", (station_id,))
        donner = cursor.fetchone()

        current_lat = donner['lat']
        current_lon = donner['lon']
        current_name = donner['station']
        if current_lat != lat or current_lon != lon or current_name != name:
            try:
                cursor.execute(
                "UPDATE stations SET lat = %s, lon = %s, station = %s WHERE station_id = %s",
                (lat, lon, name, station_id)
                )
                print("current info :",current_lat,"/// new info : ",lat)
                nbUpdate += 1
            except mysql.connector.Error as e:
                    print(f"Erreur de mise à jour pour la station ID {station_id}: {e}")
        
   

# Validation de la transaction
conn.commit()

print("Insertion des stations terminée.\n")
print("Nombre d'insertion: ", nbInsertion, "\n")
print("Nombre de changement: ", nbUpdate)


# Fermer la connexion à la base de données
cursor.close()
conn.close()
