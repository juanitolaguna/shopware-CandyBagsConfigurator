function translate(item, property) {
    if (item[property] === null) {
        return item['translated'][property];
    }
    return item[property];
}

export {translate};