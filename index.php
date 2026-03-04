<!--
  Author: Sarah Segui Bilger
  Project: WishLog
  Context: Educational project – public repository required by training
-->

<!DOCTYPE html>
<html lang="fr">

<!-- Utilisation de require_once pour inclure le head (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
<?php require_once 'includes/head.php';?> 

  <body>
    <!--Début du code du contenu de la page-->

    <!-- Utilisation de require_once pour inclure le header (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/header.php';?>

    <main>

      <!-- Section d'accroche-->
      <section class="hookSection">

        <!-- H1 descriptif concis du contenu du site -->
        <h1>
          La liste de cadeaux universelle & gratuite !
        </h1>
        <!-- Barre de séparation -->
        <hr/>
      
        <!-- Accroche et CTA incitant à créer un compte et une liste -->
        <p class="slogan"> Créez, choisissez, partagez !</p>

        <!-- CTA2 -->
        <a href="#" class="CTA2">Commencez maintenant</a>
      </section>

      <!-- Section descriptive  détaillée de ce que propose le site  -->
      <section class="descriptionSection">
        <p class="descriptionParagraph1">Anniversaires, fêtes, naissances, mariages...</p>
        <p class="descriptionParagraph2">WishLog est LE partenaire de tous vos évènements !</p>

        <!-- Cards présentant les options d'utilisation disponibles -->
        <div class="optionsList">
          <div class="cardsOptions">
            <p>Tous les magasins</p>
          </div>
          <div class="cardsOptions">
            <p>Personnalisé</p>            
          </div>
          <div class="cardsOptions">
            <p>Nomade</p>           
          </div>
          <div class="cardsOptions"> 
            <p>Fonction : ne pas gâcher la surprise</p>                     
          </div>
          <div class="cardsOptions">
            <p>Gratuit</p>                      
          </div>        
          <div class="cardsOptions">
            <p>Sans publicité</p>                       
          </div>          
        </div>

      </section>
    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>

  </body>
  </html>