function translate(item, property) {
    if (item[property] === null) {
        return item['translated'][property];
    }
    return item[property];
}

function price(item) {
    if (item.itemCard.showPrice && item.itemCard.product && item.currencyPrice) {
        return item.currencyPrice.gross;
    }
    return false;
}

export {translate, price};