import { Module } from 'src/core/shopware';

/**
 * Overrides
 */
import './component/eccb-data-grid'
/**
 * Snippets
 */
import deDE from '../snippet/de-DE.json';
import enGB from '../snippet/en-GB.json';


/**
 * Step Set
 */
import './page/step-set/step-set-list';
import './page/step-set/step-set-create';
import './page/step-set/step-set-detail';

/**
 * Tree Node
 */
import './page/tree-node/tree-node-create';
import './page/tree-node/tree-node-detail';

/**
 * Item Set
 */
import './page/item-set/item-set-create';
import './page/item-set/item-set-detail';
import './page/item-set/item-set-list';


/**
 * Components
 */
import './component/eccb-media-field';
import './page/tree-node/component/item-set-list';



Module.register('eccb-plugin', {
    type: 'plugin',
    name: 'Candy Bags',
    title: 'eccb.general.mainMenuItemGeneral',
    description: 'eccb.general.descriptionTextModule',
    color: '#ed1c24',
    icon: 'default-object-lab-flask',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            component: 'eccb-step-set-list',
            path: 'index'
        },

        create: {
            component: 'eccb-step-set-create',
            path: 'create',
            meta: {
                parentPath: 'eccb.plugin.index'
            }
        },

        detail: {
            component: 'eccb-step-set-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'eccb.plugin.index'
            }
        },

        'tree-node.create': {
            component: 'eccb-tree-node-create',
            path:'tree-node/create',
            meta: {
                parentPath: 'eccb.plugin.index'
            }
        },

        'tree-node.detail': {
            component: 'eccb-tree-node-detail',
            path:'tree-node/detail/:id',
            meta: {
                parentPath: 'eccb.plugin.index'
            }
        },

        'item-set.create': {
            component: 'eccb-item-set-create',
            path:'item-set/create',
            meta: {
                parentPath: 'eccb.plugin.index'
            }
        },

        'item-set.detail': {
            component: 'eccb-item-set-detail',
            path:'item-set/detail/:id',
            meta: {
                parentPath: 'eccb.plugin.index'

            }
        },

        'item-set.list': {
            component: 'eccb-item-set-list',
            path:'item-set/list',
            meta: {
                parentPath: 'eccb.plugin.index'
            }
        },

        'item-set.item.create': {
            component: 'eccb-item-set-item-create',
            path:'item-set/item/create',
            meta: {
                parentPath: 'eccb.plugin.item-set.detail'
            }
        },

        'item-set.item.detail': {
            component: 'eccb-item-set-item-detail',
            path:'item-set/item/detail',
            meta: {
                parentPath: 'eccb.plugin.item-set.detail'
            }
        },

    },

    navigation: [
        {
            id: 'eccb-step-set',
            label: 'eccb.general.mainMenuItemGeneral',
            path: 'eccb.plugin.index',
            icon: 'default-object-lab-flask',
            color: '#ed1c24',
        }
    ]
});