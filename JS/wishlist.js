const url = window.location.origin + '/wishlist.json'
console.log(url)


// Création de ma fonction asynchrone que je nomme "loadProducts" chargée de récupérer les produits depuis le fichier JSON et de gérer les cartes dans le DOM :
async function loadProducts() {
    // Récupération des données du JSON.
    const response = await fetch(url);
    const products = await response.json();

    // Création du menu déroulant de filtre "Catégories":

    // Création d'un Set pour stocker les catégories uniques présentes dans le JSON (pas de doublons) :
    const categoriesSet = new Set(); 
    products.forEach(product => {
        // On vérifie que le produit possède bien une catégorie (évite les valeurs undefined ou vides):
        if (product.category) {
            // Ajoute les catégories à CategoriesSet sauf si elle existe déjà :
            categoriesSet.add(product.category); 
        }
        });
        // Transformation du Set en tableau classique (utile si on veut utiliser des méthodes de tableaux) :
        const categories = Array.from(categoriesSet);

        // Récupération du menu déroulant select déjà présent dans le HTML en accédant au DOM :
        const selectCategory = document.getElementById('filterCategory');

        // Parcourir le set et récupérer chaque catégorie unique pour créer les options du menu : 
        categoriesSet.forEach(category => {

            // Création d'une balise option pour le menu déroulant, en accédant au DOM :
            const categoryOptions = document.createElement('option');

            // Ajoute une classe CSS à la balise option :
            categoryOptions.classList.add('filterCategoryOptions');

            // La valeur envoyée lors de la sélection (lors de la sélection, donne la valeur de category à categoryOptions.value) :
            categoryOptions.value = category; 
            // Affiche le texte pour l'utilisateur dans le menu déroulant :
            categoryOptions.textContent = category;

            // Ajout de l'option categoryOptions dans le menu select :
            selectCategory.appendChild(categoryOptions);
        })


    // Création des cards produit (conteneur principal de toutes les infos produit) en plusieurs étapes:

    // 1- Recherche et sélectionne la div (dans le DOM) dans laquelle je veux créer toutes les cartes produit :
    const cardContainer = document.querySelector('.wishlistProductsSectionCardBox');

    //2- Création d'une carte par produit  : 
    products.forEach((product, index) => {

        // 3- Création de la div de la carte produit individuelle:

            // Création de la card dans le DOM.
            const card = document.createElement('div');

            // Ajout de la classe .productCard :
            card.classList.add('productCard');

            // Ajout d'un attribut catégorie pour servir au filtre par catégories ensuite :
            card.dataset.category = product.category 

            // 4- Création des grandes div internes à la card :
            
                // Création de la div contenant l'image de la carte produit :
                const imageDiv = document.createElement('div');
                imageDiv.classList.add('productCardImage');

                // Création de la div contenant les détails du produit :
                const detailsDiv = document.createElement('div');
                detailsDiv.classList.add('productCardDetails');

                // Création de la div contenant les quantités et prix du produit :
                const numbersDiv = document.createElement('div');
                numbersDiv.classList.add('productCardNumbers');

                // Création de la div contenant la poignée de drag de la card :
                const dragDiv = document.createElement('div');
                dragDiv.classList.add('productCardDrag');

            // 5- Création des petites div internes aux grandes div :

                // *Création des mini-div contenues dans detailsDiv :
                    // Création de la div contenant les deux premiers éléments de la div detailsDiv :
                    const nameAndCategoryDiv = document.createElement('div');
                    nameAndCategoryDiv.classList.add('productCardDetailsNameAndCat');

                    // Création de la mini-div contenant les deux éléments suivants de DetailsDiv :
                    const shopAndDetailsDiv = document.createElement('div');
                    shopAndDetailsDiv.classList.add('productCardDetailsShopAndDetails');

                    // Création de la mini-div concernant les deux derniers éléments de la DetailsDiv :
                    const modifyAndMoveDiv = document.createElement('div');
                    modifyAndMoveDiv.classList.add('productCardDetailsModifyAndMove');

                // *Création des div contenues dans numbersDiv:

                    // Création de la mini-div contenant l'élément de suppression de numbersDiv :
                    const deleteProduct = document.createElement('div');
                    deleteProduct.classList.add('productCardNumbersDelete');

                    // Création de la mini-div contenant la priorité et le prix dans numbersDiv :
                    const priorityAndPrice = document.createElement('div');
                    priorityAndPrice.classList.add('productCardNumbersPriorityAndPrice');

                    // Création de la mini-div contenant le nombre d'articles souhaité et le bouton indiquant le choix d'achat :
                    const numberAndBuy = document.createElement('div');
                    numberAndBuy.classList.add('productCardNumbersNumberAndBuy');

            // 6- Insérer les données dans les cards :

                // *Créer un élément img pour insérer l'image du produit dans la card : 
                    // Créer l'img dans le DOM :
                    const productImage = document.createElement('img');
                    // Donne l'url de l'image du premier produit :
                    productImage.src = product.imageURL;   
                    // Définit le texte alternatif de l'image du premier produit avec comme texte de référence le nom du produit, et un autre texte alternatif si le nom du produit n'est pas disponible.
                    productImage.alt = product.nameProduct ? product.nameProduct : "Image du produit indisponible";

                    // Ajout de l'image produit à la grande div .productCardImage :
                    imageDiv.appendChild(productImage);

                // *Insertion du nom du produit à la div DetailsDiv par son enfant :
                    const productName = document.createElement('a');
                    productName.classList.add('productCardDetailsName');
                    productName.textContent = product.nameProduct ? product.nameProduct : "Nom du produit indisponible";
                    productName.href = product.url;
                    productName.target = "_blank";
                    productName.rel = "noopener noreferrer";

                    // Ajout du nom du produit à la div .productCardDetailsNameAndCat :
                    nameAndCategoryDiv.appendChild(productName);

                // *Insertion de la catégorie du produit à la div DetailsDiv par son enfant
                    const productCategory = document.createElement('p');
                    productCategory.classList.add('productCardDetailsCategory');
                    productCategory.textContent = product.category ? product.category : "Catégorie indisponible";

                    // Ajout de la catégorie du produit à la div .productCardDetailsNameAndCat :
                    nameAndCategoryDiv.appendChild(productCategory);

                // *Insertion du nom du magasin à la div DetailsDiv par son enfant
                    const shopName = document.createElement('p');
                    shopName.classList.add('productCardDetailsShop');
                    shopName.textContent = product.nameShop ? product.nameShop : "Nom du magasin indisponible";

                    // Ajout du nom du magasin à la div .productCardDetailsShopAndDetails :
                    shopAndDetailsDiv.appendChild(shopName);

                // *Insertion de la description détaillée du produit à la div DetailsDiv par son enfant
                    const productDetails = document.createElement('p');
                    productDetails.classList.add('productCardDetailsDescription');
                    productDetails.textContent = product.detail ? product.detail : "Description du produit indisponible";

                    // Ajout de la description détaillée du produit à la div .productCardDetailsShopAndDetails :
                    shopAndDetailsDiv.appendChild(productDetails);

                // *Insertion du bouton modifier à la div .modifyAndMove :
                    const editButton = document.createElement('button');
                    editButton.classList.add('productCardDetailModifyButton');
                    editButton.type = 'button';
                    // Ajout d'un label pour accessibilité : indique aux personnes utilisant un lecteur d'écran l'action effectuée et sur quel produit elle sera effectuée ;
                    editButton.setAttribute('aria-label', `Modifier le produit ${product.nameProduct}`);
                    editButton.textContent = 'Modifier';

                    // Ajout du bouton modifier dans la div .productCardDetailsModifyAndMove :
                    modifyAndMoveDiv.appendChild(editButton);

                // *Insertion du bouton déplacer à la div .modifyAndMove : 
                    const moveButton = document.createElement('button');
                    moveButton.classList.add('productCardDetailMoveButton');
                    moveButton.type = 'button';
                    // Ajout d'un label pour accessibilité : indique aux personnes utilisant un lecteur d'écran l'action effectuée et sur quel produit elle sera effectuée :
                    moveButton.setAttribute('aria-label', `Déplacer le produit ${product.nameProduct}`); 
                    moveButton.textContent ='Déplacer';

                    // Ajout du bouton déplacer dans la div .productCardDetailsModifyAndMove :
                    modifyAndMoveDiv.appendChild(moveButton);

                // *Insertion du bouton supprimer dans la div .productCardNumbersDelete :
                    const deleteButton = document.createElement('button');
                    deleteButton.classList.add('productCardNumbersDeleteButton');
                    deleteButton.type = 'button';

                    // Ajout d'un label pour accessibilité : indique aux personnes utilisant un lecteur d'écran l'action effectuée et sur quel produit elle sera effectuée :
                    deleteButton.setAttribute('aria-label', `Supprimer le produit ${product.nameProduct}`); 
                    deleteButton.textContent = 'Supprimer';

                    // Ajout du bouton supprimer dans la div .productCardNumbersDelete
                    deleteProduct.appendChild(deleteButton);

            // *Insertion du menu déroulant de Priorité dans la div .productCardNumbersPriorityAndPrice :
                // Création du label :
                const priorityLabel = document.createElement('label');
                priorityLabel.textContent = "Priorité";

                // Création des id pour utilisation pour le nombre d'articles :
                const selectId = `priority-${index}`;
                priorityLabel.setAttribute('for', selectId);

                // Cacher le label visuellement :
                priorityLabel.classList.add('productCardNumbersPriorityLabel');

                // Création du select :
                const prioritySelect = document.createElement('select');
                prioritySelect.classList.add('productCardNumbersPriority');
                prioritySelect.id = selectId;
                prioritySelect.name = 'priority';

                    // Créer le choix de niveaux de priorité :
                    const options = [
                    { value: "", text: "-- Choisir la priorité --", disabled: true, selected: true },
                    { value: "very high", text: "Priorité : Très haute" },
                    { value: "high", text: "Priorité : Haute"},
                    { value: "medium", text: "Priorité : Moyenne"},
                    { value: "low", text: "Priorité : Faible"},
                    { value: "very low", text: "Priorité : Très Faible"}
                    ];

                options.forEach( optionData => {
                    const optionElement = document.createElement('option');
                    optionElement.value = optionData.value;
                    optionElement.textContent = optionData.text;
                    if (optionData.disabled) optionElement.disabled = true ;
                    if (optionData.selected) optionElement.selected = true;
                    prioritySelect.appendChild(optionElement);
                });

                // Insertion du label et du select dans la div .productCardNumbersPriorityAndPrice :
                priorityAndPrice.appendChild(priorityLabel);
                priorityAndPrice.appendChild(prioritySelect);

            // *Insertion du prix de l'article dans la div .productCardNumbersPriorityAndPrice :
                const productPrice = document.createElement('p');
                productPrice.classList.add('productCardNumbersPrice');
                productPrice.textContent = product.price ? product.price : "Prix indisponible";
                productPrice.setAttribute('aria-label', `Prix du produit ${product.nameProduct}`); 

                // Ajout du prix du produit à la div qui contient la priorité et le prix :
                priorityAndPrice.appendChild(productPrice);

            // *Permettre d'indiquer le nombre d'articles souhaités :
                const productNumber = document.createElement('input');
                productNumber.setAttribute('type', 'number');
                productNumber.setAttribute('min','1');
                productNumber.addEventListener('input',()=>{
                    if (productNumber.value<1) productNumber.value = 1;
                })
                productNumber.classList.add('productCardNumbersNumber');
                const numberId = `number-${index}`;
                productNumber.id = numberId;


                const numberLabel = document.createElement('label');
                numberLabel.classList.add('productCardNumbersNumberLabel');
                numberLabel.setAttribute('for',numberId);
                numberLabel.textContent = "Nombre";
                // TODO : Quand j'aurai le temps, créer une indication visible de nombre mais intégré à l'input.

                // Ajout du nombre de produits et du label à la div numberAndBuy :
                numberAndBuy.appendChild(productNumber);
                numberAndBuy.appendChild(numberLabel);

            // *Insertion d'un bouton pour indiquer qu'on souhaite offrir cet article
                const buyProduct = document.createElement('button');
                buyProduct.classList.add('productCardNumbersBuy');
                buyProduct.type='button';
                buyProduct.textContent="Je l'offre !";

                numberAndBuy.appendChild(buyProduct);

                // TODO : Gérer dynamiquement le fait que l'article n'est plus affiché pour les utilisateurs qui consultent la liste

            // *Insertion des flèches pour indiquer qu'on peut faire un drag&drop dans la div .productCardDrag
                dragDiv.innerHTML=`<svg class="dragIcons arrowUp" fill="#1E293B" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"> <g id="SVGRepo_bgCarrier" stroke-width="0"/> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/> <g id="SVGRepo_iconCarrier"> <path d="M0.256 23.481c0 0.269 0.106 0.544 0.313 0.75 0.412 0.413 1.087 0.413 1.5 0l14.119-14.119 13.913 13.912c0.413 0.413 1.087 0.413 1.5 0s0.413-1.087 0-1.5l-14.663-14.669c-0.413-0.412-1.088-0.412-1.5 0l-14.869 14.869c-0.213 0.212-0.313 0.481-0.313 0.756z"/> </g></svg>
                <svg class="dragIcons arrowDown"fill="#1E293B" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" transform="matrix(1, 0, 0, -1, 0, 0)"> <g id="SVGRepo_bgCarrier" stroke-width="0"/> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/> <g id="SVGRepo_iconCarrier"> <path d="M0.256 23.481c0 0.269 0.106 0.544 0.313 0.75 0.412 0.413 1.087 0.413 1.5 0l14.119-14.119 13.913 13.912c0.413 0.413 1.087 0.413 1.5 0s0.413-1.087 0-1.5l-14.663-14.669c-0.413-0.412-1.088-0.412-1.5 0l-14.869 14.869c-0.213 0.212-0.313 0.481-0.313 0.756z"/> </g> </svg>`

                dragDiv.setAttribute('aria-label', 'Déplacer le produit');
            


        // 7- Chargement des grandes div dans la carte :

            // Ajout de la div qui contient l'image (.productCardImage) à la carte (.productCard) :
            card.appendChild(imageDiv);

            // Ajout de la div qui contient les détails du produit à la carte :
            card.appendChild(detailsDiv);

            // Ajout de la div qui contient les prix et quantités du produit à la carte :
            card.appendChild(numbersDiv);

            // Ajout de la div qui contient la poignée de drag de la card à la carte :
            card.appendChild(dragDiv);
            
            // 8- Chargement des petites div internes dans les grandes div : 

                // Ajout de la div qui contient le nom et la catégorie du produit à detailsDiv :
                detailsDiv.appendChild(nameAndCategoryDiv);

                // Ajout de la div qui contient le nom du magasin en ligne et la description détaillée du produit à detailsDiv :
                detailsDiv.appendChild(shopAndDetailsDiv);

                // Ajout de la div qui contient les boutons modifier et déplacer du produit à detailsDiv :
                detailsDiv.appendChild(modifyAndMoveDiv);

                // Ajout de la div qui contient le bouton supprimer à numbersDiv :
                numbersDiv.appendChild(deleteProduct);

                //Ajout de la div qui contient l'élément Priorité à numbersDiv :
                numbersDiv.appendChild(priorityAndPrice);

                // Ajout de la div qui contient l'élément nombre et décision d'achat :
                numbersDiv.appendChild(numberAndBuy);

        // Ajout de la div card à son parent la div cardContainer (qui correspond à .wishlistProductsSectionCardBox, sélectionnée dans le DOM) :
        cardContainer.appendChild(card);
    });

    // Ajout d'un eventListener sur le menu déroulant selectCategory (se déclenche à chaque changement) :
    selectCategory.addEventListener("change", () => {

        // Récupération de la valeur sélectionnée dans le menu :
        const selectedCategory = selectCategory.value;      

        // Récupérer toutes les cartes produit présentes dans le DOM :
        const allCards = document.querySelectorAll('.productCard');

        // Parcourir toutes les cartes produit récupérées pour décider si elle doit être affichée ou non :
        allCards.forEach(card => {

            // Si l'utilisateur choisit "all", on affiche toutes les cartes :
            if (selectedCategory === "all") {
                card.style.display = "flex";

            // Sinon, on affiche uniquement les cartes dont la catégorie correspond à la sélection :
            } else if (selectedCategory === card.dataset.category) {
                card.style.display = "flex";

            // Toutes les autres cartes sont cachées :
            } else { 
                card.style.display = "none";
            }
        });
        
    });
}

// Affichage des produits dans la console :
loadProducts();