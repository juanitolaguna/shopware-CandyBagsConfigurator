import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './item-set-list.twig';

Component.register('eccb-item-set-list', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    inject: ['repositoryFactory'],

    data() {
        return {
            itemSets: null,
            isLoading: true,
            currentLanguageId: Shopware.Context.api.languageId
        }
    },

    created() {
        this.createdComponent();
    },

    computed: {
        itemSetRepository() {
            return this.repositoryFactory.create('eccb_item_set');
        },

        parentRoute() {
            const treeNodeId = this.$route.query.treeNodeId
            if (treeNodeId) {
                return {name: 'eccb.plugin.tree-node.detail', params: {id: this.$route.query.treeNodeId}}
            } else {
                return {name: 'eccb.plugin.index'}
            }
        },

        columns() {
            return [
                {
                    property: 'internalName',
                    dataIndex: 'internalName',
                    label: 'Name',
                    inlineEdit: 'string',
                    routerLink: 'eccb.plugin.item-set.detail',
                    primary: true
                }
            ]
        }
    },

    methods: {
        async createdComponent() {
            try {
                this.itemSets = await this.itemSetRepository.search(new Criteria(), Context.api);
            } catch (error) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: this.createErrorMessage(error)
                });
            }
            this.isLoading = false;
        },

        async changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            await this.createdComponent();
        },

        createErrorMessage(exception) {
            let error = '';
            exception.response.data.errors.forEach(e => {
                error += `Error.. o_0: ${e.detail}\n ${e.source.pointer} \n\n`
            })
            return error;
        },
    }
});