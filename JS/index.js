// Création des cards produit :

// Création de ma fonction asynchrone que je nomme "loadProducts" destinée à utiliser mon fichier .JSON. Rien entre parenthèses car je n'ai pas de paramètres à appeler :
async function loadProducts() {
    // Création de ma variable response dans laquelle sera stocké le résultat du fetch qui lui-même renvoie la promesse qui est un objet qui contiendra la réponse quand elle sera prête.
    const response = await fetch('wishlist.json');
    // Création de la variable products dans laquelle on stocke le tableau d'objets du .JSON
    const products = await response.json();

    // Recherche et sélectionne la div (dans le DOM) dans laquelle je veux créer les cartes. Création de la variable cardContainer pour pouvoir ensuite s'y référer et y mettre les cards : 
    const cardContainer = document.querySelector('.wishlistProductsSectionCardBox');

    products.forEach((product, index) => {
        // Création de la div de la carte produit:

            // Création de la variable card dont la valeur sera toujours cette div nouvellement créée. Ensuite utilisation de la variable card possible pour ajouter des classes, du contenu, etc.  : 
            const card = document.createElement('div');
            // Ajout de la classe .productCard à la variable card, cela permet au style CSS de .productCard de s'appliquer à cette div une fois qu'elle sera dans le DOM:
            card.classList.add('productCard');

            // Création de la div contenant l'image de la carte produit  selon la même méthode:
            const imageDiv = document.createElement('div');
            imageDiv.classList.add('productCardImage');

            // Création de la div contenant les détails du produit selon la même méthode:
            const detailsDiv = document.createElement('div');
            detailsDiv.classList.add('productCardDetails');

            // Création de la div contenant les quantités et prix du produit selon la même méthode:
            const numbersDiv = document.createElement('div');
            numbersDiv.classList.add('productCardNumbers');

            // Création de la div contenant la poignée de drag de la card selon la même méthode:
            const dragDiv = document.createElement('div');
            dragDiv.classList.add('productCardDrag');

            // Création des div contenues dans detailsDiv :
                // Création de la div contenant les deux premiers éléments de la div detailsDiv selon la même méthode:
            const nameAndCategoryDiv = document.createElement('div');
            nameAndCategoryDiv.classList.add('productCardDetailsNameAndCat');

                // Création de la div contenant les deux éléments suivants de la div detailsDiv selon la même méthode:
            const shopAndDetailsDiv = document.createElement('div');
            shopAndDetailsDiv.classList.add('productCardDetailsShopAndDetails');

                // Création de la div concernant les deux derniers éléments de la DetailsDiv selon la même méthode:
            const modifyAndMoveDiv = document.createElement('div');
            modifyAndMoveDiv.classList.add('productCardDetailsModifyAndMove');

            // Création des div contenues dans numbersDiv:

                // Création de la div contenant l'élément de suppression de numbersDiv:
            const deleteProduct = document.createElement('div');
            deleteProduct.classList.add('productCardNumbersDelete');

                // Création de la div contenant la priorité et le prix dans numbersDiv:
            const priorityAndPrice = document.createElement('div');
            priorityAndPrice.classList.add('productCardNumbersPriorityAndPrice');

                // Création de la div contenant le nombre d'articles souhaité et le bouton indiquant le choix d'achat :
            const numberAndBuy = document.createElement('div');
            numberAndBuy.classList.add('productCardNumbersNumberAndBuy');


            // Créer un élément img pour mettre l'image du produit : 
                // Créer une variable dont la valeur est un nouvel élément img vide :
            const productImage = document.createElement('img');
                // Donne l'url de l'image du premier produit :
            productImage.src = product.imageURL;   
                // Définit le texte alternatif de l'image du premier produit avec comme texte de référence le nom du produit, et un autre texte alternatif si le nom du produit n'est pas disponible.
            productImage.alt = product.nameProduct ? product.nameProduct : "Image du produit";

            // // Ajout de l'image à la div de la carte :
            // card.appendChild(productImage);

            // Ajout de l'image produit à la div qui contient l'image et qui correspond à la classe .productCardImage :
            imageDiv.appendChild(productImage);

            // Ajout du nom du produit à la div DetailsDiv par son enfant
            const productName = document.createElement('a');
            productName.classList.add('productCardDetailsName');
            productName.textContent = product.nameProduct ? product.nameProduct : "Nom du produit indisponible";
            productName.href = product.url;
            productName.target = "_blank";
            productName.rel = "noopener noreferrer";


            // Ajout du nom du produit à la div qui contient le nom du produit et qui correspond à la classe .productCardDetailsNameAndCat :
            nameAndCategoryDiv.appendChild(productName);

            // Ajout catégorie du produit à la div DetailsDiv par son enfant
            const productCategory = document.createElement('p');
            productCategory.classList.add('productCardDetailsCategory');
            productCategory.textContent = product.category ? product.category : "Catégorie indisponible";

            // Ajout de la catégorie du produit à la div qui contient la catégorie et qui correspond à la classe .productCardDetailsNameAndCat :
            nameAndCategoryDiv.appendChild(productCategory);

            // Ajout du nom du magasin à la div DetailsDiv par son enfant
            const shopName = document.createElement('p');
            shopName.classList.add('productCardDetailsShop');
            shopName.textContent = product.nameShop ? product.nameShop : "Nom du magasin indisponible";

            // Ajout du nom du magasin à la div qui contient le nom du magasin et qui correspond à la classe .productCardDetailsShopAndDetails :
            shopAndDetailsDiv.appendChild(shopName);

            // Ajout de la description détaillée du produit à la div DetailsDiv par son enfant
            const productDetails = document.createElement('p');
            productDetails.classList.add('productCardDetailsDescription');
            productDetails.textContent = product.detail ? product.detail : "Description du produit indisponible";

            // Ajout de la description détaillée du produit à la div qui contient la description et qui correspond à la classe .productCardDetailsShopAndDetails :
            shopAndDetailsDiv.appendChild(productDetails);

            // Ajout du bouton modifier :
            const editButton = document.createElement('button');
            editButton.classList.add('productCardDetailModifyButton');
            editButton.type = 'button';
            editButton.setAttribute('aria-label', `Modifier le produit ${product.nameProduct}`); // Ajout d'un label pour accessibilité : indique aux personnes utilisant un lecteur d'écran l'action effectuée et sur quel produit elle sera effectuée
            editButton.textContent = 'Modifier';

            // Ajout du bouton modifier dans la div .productCardDetailsModifyAndMove :
            modifyAndMoveDiv.appendChild(editButton);

            // Ajout du bouton déplacer : 
            const moveButton = document.createElement('button');
            moveButton.classList.add('productCardDetailMoveButton');
            moveButton.type = 'button';
            moveButton.setAttribute('aria-label', `Déplacer le produit ${product.nameProduct}`); // Ajout d'un label pour accessibilité : indique aux personnes utilisant un lecteur d'écran l'action effectuée et sur quel produit elle sera effectuée
            moveButton.textContent ='Déplacer';

            // Ajout du bouton déplacer dans la div .productCardDetailsModifyAndMove :
            modifyAndMoveDiv.appendChild(moveButton);

            // Ajout du bouton supprimer dans la div .productCardNumbersDelete :
            const deleteButton = document.createElement('button');
            deleteButton.classList.add('productCardNumbersDeleteButton');
            deleteButton.type = 'button';
            deleteButton.setAttribute('aria-label', `Supprimer le produit ${product.nameProduct}`); // Ajout d'un label pour accessibilité : indique aux personnes utilisant un lecteur d'écran l'action effectuée et sur quel produit elle sera effectuée
            deleteButton.textContent = 'Supprimer';

            // Ajout du bouton supprimer dans la div .productCardNumbersDelete
            deleteProduct.appendChild(deleteButton);

            // Ajout du menu déroulant de Priorité dans la div .productCardNumbersPriorityAndPrice :
            const priorityLabel = document.createElement('label'); // Création du label
            priorityLabel.textContent = "Priorité";
            // Création des id pour utilisation pour le nombre d'articles :
            const selectId = `priority-${index}`;
            priorityLabel.setAttribute('for', selectId);
            priorityLabel.classList.add('productCardNumbersPriorityLabel'); // Cacher le label visuellement
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

            // Ajout de l'affichage du prix de l'article dans la div .productCardNumbersPriorityAndPrice
            const productPrice = document.createElement('p');
            productPrice.classList.add('productCardNumbersPrice');
            productPrice.textContent = product.price ? product.price : "Prix indisponible";

            // Ajout du prix du produit à la div qui contient la priorité et le prix;
            priorityAndPrice.appendChild(productPrice);

            // TODO Insérer ci-dessous le code pour indiquer le nombre d'articles souhaités
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
            // TODO : quand j'aurai le temps, créer une indication visible de nombre mais intégré à l'input.

            // Ajout du nombre de produits et du label à la div qui contient le nombre et la décision d'achat
            numberAndBuy.appendChild(productNumber);
            numberAndBuy.appendChild(numberLabel);

            // TODO Ajout d'un bouton pour indiquer qu'on souhaite offrir cet article

            // TODO Ajout des flèches pour indiquer qu'on peut faire un drag&drop dans la div .productCardDrag



        // Chargement des grandes div dans la carte :

            // Ajout de la div qui contient l'image (.productCardImage) à la carte (.productCard)
            card.appendChild(imageDiv);

            // Ajout de la div qui contient les détails du produit à la carte
            card.appendChild(detailsDiv);

            // Ajout de la div qui contient les prix et quantités du produit à la carte
            card.appendChild(numbersDiv);

            // Ajout de la div qui contient la poignée de drag de la card à la carte
            card.appendChild(dragDiv);

            // Ajout de la div qui contient le nom et la catégorie du produit à detailsDiv
            detailsDiv.appendChild(nameAndCategoryDiv);

            // Ajout de la div qui contient le nom du magasin en ligne et la description détaillée du produit à detailsDiv
            detailsDiv.appendChild(shopAndDetailsDiv);

            // Ajout de la div qui contient les boutons modifier et déplacer du produit à detailsDiv
            detailsDiv.appendChild(modifyAndMoveDiv);

            // Ajout de la div qui contient le bouton supprimer à numbersDiv
            numbersDiv.appendChild(deleteProduct);

            //Ajout de la div qui contient l'élément Priorité à numbersDiv
            numbersDiv.appendChild(priorityAndPrice);

            // Ajout de la div qui contient l'élément nombre et décision d'achat
            numbersDiv.appendChild(numberAndBuy);

            // Ajout de la div card à son parent la div cardContainer (qui correspond à .wishlistProductsSectionCardBox, sélectionnée dans le DOM) :
            cardContainer.appendChild(card);
});
}

// Affichage des produits dans la console :
loadProducts();