import { Module } from 'src/core/shopware';

/**
 * Snippets
 */
import deDE from '../snippet/de-DE.json';
import enGB from '../snippet/en-GB.json';


/**
 * Step Set
 */
import './page/step-set/step-set-list'
import './page/step-set/step-set-create'
import './page/step-set/step-set-detail'


/**
 * Components
 */
import './component/eccb-media-field'



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
        }
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