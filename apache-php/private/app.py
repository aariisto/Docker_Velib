from flask import Flask, render_template, jsonify
from flask_cors import CORS
import requests
import re

app = Flask(__name__)
CORS(app)



@app.route('/stations', methods=['GET'])
def get_stations():
    response = requests.get("https://velib-metropole-opendata.smovengo.cloud/opendata/Velib_Metropole/station_information.json")
    if response.status_code == 200:
        data = response.json()
        formatted_data = []

        # Filtrage et structuration des données
        for station in data.get('data', {}).get('stations', []):
            # Validation des champs et types requis
            if (
                isinstance(station.get('station_id'), int) and
                isinstance(station.get('stationCode'), str) and
                isinstance(station.get('name'), str) and
                isinstance(station.get('lat'), float) and
                isinstance(station.get('lon'), float) and
                isinstance(station.get('capacity'), int)
            ):
                # Ajouter la station avec les champs formatés dans la liste finale
                formatted_station = {
                    "station_id": station['station_id'],
                    "stationCode": station['stationCode'],
                    "name": station['name'],
                    "lat": station['lat'],
                    "lon": station['lon'],
                    "capacity": station['capacity']
                }
                formatted_data.append(formatted_station)

        return jsonify(formatted_data)
    else:
        return jsonify({"error": f"Erreur lors de la requête: {response.status_code}"}), response.status_code


@app.route('/station/<int:station_id>', methods=['GET'])
def get_station_info(station_id):
    
    # Récupérer toutes les stations pour trouver celle qui correspond à station_id
    response = requests.get("https://velib-metropole-opendata.smovengo.cloud/opendata/Velib_Metropole/station_status.json")
    if response.status_code == 200:
        data = response.json()
        # Trouver la station avec le station_id donné
        station_info = next((station for station in data['data']['stations'] if station['station_id'] == station_id), None)
        
        if station_info:
            return jsonify(station_info)
        else:
            return jsonify(None)
    else:
        return jsonify({"error": f"Erreur lors de la requête: {response.status_code}"}), response.status_code


@app.route('/search_station/<string:name>', methods=['GET'])
def search_station(name):

    response = requests.get("https://velib-metropole-opendata.smovengo.cloud/opendata/Velib_Metropole/station_information.json")
    if response.status_code == 200:
        stations_data = response.json()
        for station in stations_data['data']['stations']:
          if (re.sub(r'[\s_-]+', ' ', name).strip().lower() == re.sub(r'[\s_-]+', ' ', station['name']).strip().lower()):
                return jsonify({
                    "station_id": station['station_id'],
                    "lat": station['lat'],
                    "lon": station['lon']
                })
          
  
   # nameGoogle = name.replace(' ', '+')
   # api_key = "AIzaSyDUm17xB5A406D0Z81JeCEaPT7HiWK1Qe4"
    # URL de la requête
   # url = f'https://maps.googleapis.com/maps/api/geocode/json?address={nameGoogle}&key={api_key}'
    
    # Faire la requête GET
  #  response = requests.get(url)
   # data = response.json()
    
    # Vérifier si la réponse contient des résultats
   # if data['status'] == 'OK':
   #     # Extraire la latitude et la longitude
   #     lat = data['results'][0]['geometry']['location']['lat']
   #     lng = data['results'][0]['geometry']['location']['lng']
   #     return jsonify({
   # #                "station_id": None ,
      #              "lat": lat,
    #                "lon": lng
   #             })
  
        
        return jsonify({"error": "Station non trouvée"})
    else:
        return jsonify({"error": "Erreur lors de la requête"}), response.status_code
    
    


if __name__ == '__main__':
    app.run(debug=True)
