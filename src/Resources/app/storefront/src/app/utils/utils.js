function translate(item, property) {
    if (item[property] === null) {
        return item['translated'][property];
    }
    return item[property];
}

function price(card) {
    if (card.showPrice && card.product && card.product.purchasePrice) {
        return card.product.purchasePrice;
    }
    return false;
}

export {translate, price};