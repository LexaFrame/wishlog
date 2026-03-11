    <header>
      <!-- TODO menu burger : <a href="#"></a> Penser à mettre le mot Menu, une personne handicapée ne verra pas le caractère spécial pour indiquer la présence du menu. Si ce n'est pas possible, mettre un aria-label (aria-label="Menu") -->

      <!-- Conteneur du header -->
      <div class="headerBox">

        <!--Logo du site-->
        <a href="index.php">
        <img src="images/logowishlog.png" title="WishLog" alt="Logo de Wishlog" class="logo"/>
        </a>

        <div class="headerCentralUnit">

          <!-- Choix de la langue -->
          <div class="languageBox">
            <button class="buttonLanguage" aria-haspopup="true" aria-expanded="false">
              <span class="tagLanguage">FR</span>
              <span class="triangle" aria-hidden="true"></span>
            </button>

            <ul class="language-menu" hidden>
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
          <a href="login.php" class="createAccount">Connexion</a>

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
      <a href="signup.php" class="CTA1">Créer ma liste</a>
    </div>
    </header>