<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Page Paramètres de Compte</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="page/css/account.css?v=2">
   
  
    
  </head>
  <body>

  <div id="overlay"></div> 
  
  <div id="Popup"> <!--  Pop up erreur -->
    <span class="close-btn" onclick="removePopup()">X</span>
    <img id="QRimage"/>
  </div>

  <div id="loading-backdrop">
    <div>
      <!-- Image GIF pour le chargement -->
      <img src="page/images/spiner.gif" alt="Chargement..." width="100">
    </div>
  </div>

    <div class="container mt-5">
      <div class="row">
        <!-- Colonne de gauche - Informations utilisateur -->
        <div class="col-lg-4 col-md-12 mb-4">
          <div class="card card-custom">
            <div class="card-body">
              <h3 class="section-title">Informations du Compte</h3>
              <p><strong>Username:</strong> <?php  echo $_SESSION["nom"]; ?></p>
              <p><strong>Email:</strong> <?php  echo $_SESSION["email"]; ?></p>
              <p><strong>Date d'inscription:</strong> <?php  echo $_SESSION["createTime"];?></p>
              <p><strong>Rôle:</strong> Utilisateur</p>
            </div>
          </div>
        </div>

        <!-- Colonne de droite - Liste de boutons -->
        <div class="col-lg-8 col-md-12">
          <div class="card card-custom">
            <div class="card-body">
              <h3 class="section-title" style="margin-bottom: 20px">
                Historique Personnel
              </h3>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <button class="btn btn-customHR" onclick='affichageCadre("getSearch")'>
                    Historique de Recherche
                  </button>
                </div>
                <div class="col-md-6 mb-3">
                  <button class="btn btn-customHR" onclick='affichageCadre("getRes")'>
                    Historique de Réservation
                  </button>
                </div>
              </div>

              <!-- Ligne de séparation -->
              <hr style="border: 1px solid #ccc; margin: 20px 0;display:none" />

              <!-- Nouveau div après les boutons -->
              <div class="col-12" id="cadreHR" style="display:none">
                <div class="card card-custom">
                  <div class="card-body" id="card">
                   
                 

                </div>
                </div>

                    </div>

                    </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS (optionnel pour l'interactivité) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       
     
       var searchContent = "";
       var reservationContent = "";
       var couleurSearch="";
       var typeSearch="";
       var Resa_cadre=false;
       var Serach_cadre=false;
       function getSearch(){
       
      fetch('requete/post_get', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'csrf-token': '<?php echo $_SESSION["token"]; ?>',
    'methode' : 'get_search'
  },
})
  .then(response => response.json())
  .then(data => {

    document.getElementById("card").innerHTML = data.resultat;

  })
  .catch(error => {
    console.error('Error:', error);
  });
}

  function removeSearch(button) {
    
    let searchContentVide="";
  const card = button.closest(".historique-item");
  card.remove();
  if(document.getElementsByClassName('historique-item').length === 0){
    searchContentVide=`<h3 class="section-title" style="margin-bottom: 20px;">Historique de recherche</h3><p style="text-align: center;">Aucun historique de recherche disponible.</p>`;
    document.getElementById("card").innerHTML = searchContentVide;
  }

  const dataId = button.getAttribute("data-id");
  if (dataId) {
        // Créer un objet contenant les données à envoyer
        const data = {
            id_search: dataId
        };

        // Envoyer les données via fetch (POST)
        fetch("requete/post_get", {
            method: "POST", 
            headers: {
                "Content-Type": "application/json",
                'csrf-token': '<?php echo $_SESSION["token"]; ?>',
                'methode' : 'remove_search'
            },
            body: JSON.stringify(data)
        })
        .catch(error => {
            console.error("Erreur : ", error);
        });
    } else {
        console.error("data-id non trouvé !");
    }
}

function affichageCadre(fonctionName) {

document.getElementById("card").innerHTML = " <img src='page/images/spiner.gif' alt='Chargement...' width='100' style='display: block; margin: 0 auto;'>";

  var cadre = document.getElementById("cadreHR");
  var hr = document.querySelector('hr');

  
  if (cadre.style.display === "none" ) {
    cadre.style.display = "block"; // Affiche la div si elle est masquée
    hr.style.display = "block";
    Serach_cadre=true;
  } 
  window[fonctionName]();
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getRes(){
 
fetch('requete/post_get', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'csrf-token': '<?php echo $_SESSION["token"]; ?>',
    'methode' : 'get_reservation'
  },

})
  .then(response => response.json())
  .then(data => {
    
document.getElementById("card").innerHTML =  data.resultat;
   
  })
  .catch(error => {
    console.error('Error:', error);
  });

}

  function showQR(button) {
    showhowLoading();
    const confirmationID = button.getAttribute("confirmationID");
    fetch(`https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${confirmationID}`, {
  method: 'GET',
})
.then(response => response.blob())  // Convertit la réponse en un Blob
    .then(blob => {
      // Créer un URL pour le Blob et l'afficher dans l'élément img
      const imageUrl = URL.createObjectURL(blob);
      removehowLoading();
      showPopup(imageUrl);
    })
    .catch(error => {
      removehowLoading();
      console.error('Error:', error);
    });


  }

  function removePopup(){
    document.getElementById('Popup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
    
}
function showPopup(imageURL) {
            document.getElementById('QRimage').src  = imageURL; // Mettre à jour le message
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('Popup').style.display = 'block'; // Afficher le pop-up
            

            // Utiliser setTimeout pour faire disparaître le pop-up après un certain temps
}

function showhowLoading() {
     document.getElementById('loading-backdrop').style.display = 'flex';

    
}

function removehowLoading() {
     document.getElementById('loading-backdrop').style.display = 'none';
    
    }
   

    </script>
  </body>
</html>
