import { Module } from 'src/core/shopware';

/**
 * Snippets
 */
import deDE from '../snippet/de-DE.json';
import enGB from '../snippet/en-GB.json';

/**
 * Candy Bags
 */
import './page/eccb-candybag-list'
import './page/eccb-candybag-create'
import './page/eccb-candybag-detail'

/**
 * Step
 */
import './page/eccb-step-list'
import './page/eccb-step-create'
import './page/eccb-step-detail'


/**
 * Components
 */
import './component/eccb-media-field'



Module.register('eccb-module', {
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
            component: 'eccb-step-list',
            path: 'index'
        },

        create: {
            component: 'eccb-step-create',
            path: 'create',
            meta: {
                parentPath: 'eccb.module.index'
            }
        },

        detail: {
            component: 'eccb-step-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'eccb.module.index'
            }
        }

        // index: {
        //     component: 'eccb-candybag-list',
        //     path: 'index'
        // },

        // create: {
        //     component: 'eccb-candybag-create',
        //     path: 'create',
        //     meta: {
        //         parentPath: 'eccb.module.index'
        //     }
        // },

        // detail: {
        //     component: 'eccb-candybag-detail',
        //     path: 'detail/:id',
        //     meta: {
        //         parentPath: 'eccb.module.index'
        //     }
        // }
    },

    navigation: [
        {
            id: 'eccb-step',
            label: 'eccb.general.mainMenuItemGeneral',
            path: 'eccb.module.index',
            icon: 'default-object-lab-flask',
            color: '#ed1c24',
        }
    ]
})