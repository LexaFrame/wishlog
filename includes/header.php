    <header>
      <!-- Conteneur du header -->
      <div class="headerBox">

        <!--Logo du site-->
        <a href="index.php">
        <img src="images/logowishlog.png" title="WishLog" alt="Logo de Wishlog" class="logo"/>
        </a>

        <div class="headerCentralUnit">

          <!-- Choix de la langue -->
          <div class="languageBox">
            <!-- aria-haspopup pour indiquer aux lecteurs d'écran l'ouverture d'un menu interactif et aria-expanded pour indiquer l'état actuel (fermé) du menu, qui passera à true avec JS lorsqu'il sera ouvert -->
            <button class="buttonLanguage" aria-haspopup="true" aria-expanded="false">
              <span class="tagLanguage">FR</span>
              <!-- aria-hidden pour cacher l'élément décoratif aux lecteurs d'écran pour ne pas annoncer l'icône inutilement -->
              <span class="triangle" aria-hidden="true"></span>
            </button>

            <!-- Menu de choix de langue avec une liste non-ordonnée, hidden cache le menu au chargement de la page -->
            <ul class="language-menu" hidden>
              <!-- lang indique aux navigateurs et lecteurs d'écran que le contenu du lien est en anglais -->
              <li><a href="/en/" lang="en">EN</a></li>
            </ul>
          </div>

          <!-- Bloc recherche -->
          <div class="searchBlock">

            <!--Insertion de la barre de recherche-->
            <form action="#" class="searchForm" role="search">
              <label for="searchInput" class="visually-hidden">Rechercher</label>
              <input type="search" name="q" id="searchInput" class="searchBar" placeholder="Rechercher" />
            <!-- Bouton recherche -->
            <button type="submit" class="searchButton" aria-label="Lancer la recherche">
              <img src="images/magnifyingglass.png" class="searchIcon" alt="" aria-hidden= "true"/>
            </button>
            </form>
          </div>

          <!-- Bouton de connexion -->
          <!-- Si user_id existe dans $_SESSION affiche le lien Déconnexion -->
          <?php if (isset($_SESSION['user_id'])) : ?>
          <a href="logout.php" class="createAccount">Déconnexion</a>
          <!-- Sinon, affiche le lien Connexion : -->
          <?php else : ?>
          <a href="login.php" class="createAccount">Connexion</a>
          <?php endif; ?>

          <!-- Menu de navigation avec des puces non ordonnées -->
          <nav class="menu">
            <ul>
              <li> <a href="createwishlist.php" class="menuLink">Créer</a><span class="separation">|</span></li>
              <li> <a href="addproduct.php" class="menuLink">Modifier</a><span class="separation">|</span></li>
              <li> <a href="wishlist.php" class="menuLink">Partager</a><span class="separation">|</span></li>
              <li> <a href="contact.php" class="menuLink">Contact</a></li>        
            </ul>
          </nav>
      </div>
      <!-- BoutonCTA1 -->
       <!-- Si user_id existe dans $_SESSION affiche Voir ma liste, sinon affiche Créer ma liste -->
      <?php if (isset($_SESSION['user_id'])) : ?>
      <a href="wishlist.php" class="CTA1">Voir ma liste</a>
      <?php else : ?>
      <a href="signup.php" class="CTA1">Créer ma liste</a>
      <?php endif; ?>
    </div>
    </header>