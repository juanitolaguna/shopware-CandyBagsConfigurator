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

function referenceUnitPrice(item) {
    if (!price(item) || !_referenceUnitPriceAvailable(item.itemCard.product)) {
        return false;
    }
    const product = item.itemCard.product;
    return {
        'referenceUnitPrice': ((price(item) / product.purchaseUnit) * product.referenceUnit).toFixed(2),
        'referenceUnit': product.referenceUnit,
        'purchaseUnit': product.purchaseUnit,
        'unitName': translate(product.unit, 'name'),
        'unitShortCode': translate(product.unit, 'shortCode'),
        'test': _referenceUnitPriceAvailable(item.itemCard.product)
    };
}

function _referenceUnitPriceAvailable(product) {
    const result = product.purchaseUnit !== null
        && product.referenceUnit !== null
        && product.unit !== null
        && product.unit.name !== null;
    return result;
}

export {translate, price, referenceUnitPrice};