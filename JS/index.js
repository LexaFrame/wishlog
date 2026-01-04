// Création des cards produit :

// Création de ma fonction asynchrone que je nomme "loadProducts" destinée à utiliser mon fichier .JSON. Rien entre parenthèses car je n'ai pas de paramètres à appeler :
async function loadProducts() {
    // Création de ma variable response dans laquelle sera stocké le résultat du fetch qui lui-même renvoie la promesse qui est un objet qui contiendra la réponse quand elle sera prête.
    const response = await fetch('wishlist.json');
    // Création de la variable products dans laquelle on stocke le tableau d'objets du .JSON
    const products = await response.json();

    // Recherche et sélectionne la div (dans le DOM) dans laquelle je veux créer les cartes. Création de la variable cardContainer pour pouvoir ensuite s'y référer et y mettre les cards : 
    const cardContainer = document.querySelector('.wishlistProductsSectionCardBox');

    products.forEach(product => {
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

    // Ajout du nom du produit à la div DetailsDiv
    const productName = document.createElement('p');
    productName.classList.add('productCardDetailsName');
    productName.textContent = product.nameProduct ? product.nameProduct : "Nom non disponible";


    // Ajout du nom du produit à la div qui contient le nom du produit et qui correspond à la classe .productCardDetailsNameAndCat :
    nameAndCategoryDiv.appendChild(productName);

    // Ajout catégorie du produit à la div DetailsDiv
    const productCategory = document.createElement('p');
    productCategory.classList.add('productCardDetailsCategory');
    productCategory.textContent = product.category ? product.category : "Catégorie non disponible";

    // Ajout de la catégorie du produit à la div qui contient la catégorie et qui correspond à la classe .productCardDetailsNameAndCat :
    nameAndCategoryDiv.appendChild(productCategory);

    // Ajout de la div qui contient l'image (.productCardImage) à la carte (.productCard)
    card.appendChild(imageDiv);

    // Ajout de la div qui contient les détails du produit à la carte
    card.appendChild(detailsDiv);

    // Ajout de la div qui contient les prix et quantités du produit à la carte
    card.appendChild(numbersDiv);

    // Ajout de la div qui contient la poignée de drag de la card à la carte
    card.appendChild(dragDiv);

    // Ajout de la div qui contient le nom et la catégorie du produit à DetailsDiv
    detailsDiv.appendChild(nameAndCategoryDiv);

    // Ajout de la div qui contient le nom du magasin en ligne et la description détaillée du produit
    detailsDiv.appendChild(shopAndDetailsDiv);

    // Ajout de la div qui contient les boutons modifier et déplacer du produit
    detailsDiv.appendChild(modifyAndMoveDiv);



    // Ajout de la div card à son parent la div cardContainer (qui correspond à .wishlistProductsSectionCardBox, sélectionnée dans le DOM) :
    cardContainer.appendChild(card);
});
}

// Affichage des produits dans la console :
loadProducts();