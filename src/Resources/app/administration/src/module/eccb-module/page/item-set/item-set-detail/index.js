import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './item-set-detail.twig';

Component.register('eccb-item-set-detail', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    inject: ['repositoryFactory'],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        ESCAPE: 'onClickCancel'
    },

    data() {
        return {
            itemSet: null,
            isLoading: true,
            currentLanguageId: Context.api.languageId,
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
                console.log('treeNodeId')
                return {name: 'eccb.plugin.tree-node.detail', params: {id: treeNodeId}}
            } else {
                console.log('no treeNodeId')
                return {name: 'eccb.plugin.item-set.list'}
            }
        },
    },

    methods: {
        async createdComponent() {
            try {
                this.itemSet = await this.itemSetRepository.get(this.$route.params.id, Context.api, new Criteria());
            } catch (error) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: this.createErrorMessage(error)
                });
            }
            this.isLoading = false;
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.createdComponent();
        },

        async onSave() {
            if (!this.validate()) return;
            this.isLoading = true;
            try {
                await this.itemSetRepository.save(this.itemSet, Context.api)
                await this.createdComponent();
            } catch (error) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: this.createErrorMessage(error)
                });
            }
        },

        createErrorMessage(exception) {
            let error = '';
            exception.response.data.errors.forEach(e => {
                error += `Error.. o_0: ${e.detail}\n ${e.source.pointer} \n\n`
            })
            return error;
        },

        validate() {
            let validated = true;

            if (!this.itemSet.internalName) {
                this.createNotificationError({
                    message: "o_0.. Missing required Fields:<br>" + this.$tc('eccb.internalName')
                });
                validated = false;
            }
            return validated;
        },

        onClickListingButton() {
            this.$router.push({name: 'eccb.plugin.item-set.list', query: {treeNodeId: this.$route.query.treeNodeId}})
        }
    }
});