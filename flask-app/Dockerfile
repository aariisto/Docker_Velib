# Utiliser l'image officielle Python
FROM python:3.9

# Définir le dossier de travail dans le conteneur
WORKDIR /app

# Copier les fichiers dans le conteneur
COPY requirements.txt requirements.txt
COPY app.py app.py

# Installer les dépendances
RUN pip install --no-cache-dir -r requirements.txt

# Exposer le port 5000 (port par défaut de Flask)
EXPOSE 5000

# Lancer l'application Flask
CMD ["python", "app.py"]
