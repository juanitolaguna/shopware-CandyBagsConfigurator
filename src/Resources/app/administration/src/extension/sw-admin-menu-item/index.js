import template from './sw-admin-menu-item.html.twig';

const { Component } = Shopware;

Component.override('sw-admin-menu-item', {
    template,

    methods: {
        isEventCandyCandyBagsPath(path) {
            return path === 'eccb.plugin.index';
        }

    },
});