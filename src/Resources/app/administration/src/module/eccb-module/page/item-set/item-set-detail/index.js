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
        'SYSTEMKEY+B': 'onClickCancel',
        'SYSTEMKEY+N': 'createItem',
    },


    data() {
        return {
            itemSet: null,
            items: null,
            isLoading: true,
            currentLanguageId: Context.api.languageId,

            page: 1,
            limit: 10,
            total: 0
        }
    },

    created() {
        this.createdComponent();
    },

    computed: {
        itemSetRepository() {
            return this.repositoryFactory.create('eccb_item_set');
        },

        itemRepository() {
            return this.repositoryFactory.create('eccb_item')
        },

        itemCriteria() {
            const criteria = new Criteria();
            criteria.limit = this.limit;
            criteria.setPage(this.page);
            criteria.addFilter(Criteria.equals('itemSetId', this.$route.params.id));
            criteria.addSorting(Criteria.sort('position', 'desc'));
            return criteria;
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

        columns() {
            return [
                {
                    property: 'internalName',
                    dataIndex: 'internalName',
                    label: 'eccb.itemSet.internalName',
                    inlineEdit: 'string',
                    routerLink: 'eccb.plugin.item-set.item.detail',
                    primary: true
                },

                {
                    property: 'position',
                    label: 'eccb.column.position',
                    inlineEdit: 'number',
                },

                {
                    property: 'active',
                    label: 'eccb.column.active',
                    inlineEdit: 'boolean',
                },

                {
                    property: 'purchasable',
                    label: 'eccb.column.purchasable',
                    inlineEdit: 'boolean',
                },

                {
                    property: 'terminal',
                    label: 'eccb.column.terminal',
                    inlineEdit: 'boolean',
                }
            ]
        }
    },

    methods: {
        async createdComponent() {
            try {
                this.itemSet = await this.itemSetRepository.get(this.$route.params.id, Context.api, new Criteria());
                this.items = await this.itemRepository.search(this.itemCriteria, Context.api)
                this.total = this.items.total;

                setTimeout(() => {
                    document.getElementById('sw-field--itemSet-internalName').focus();
                }, 100)
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
        },

        async onInlineEdit(item) {
            try {
                this.isLoading = true;
                await this.itemRepository.save(item, Context.api);
                await this.createdComponent();
            } catch (error) {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }
        },

        onPageChange({page = 1, limit = 25}) {
            this.page = page;
            this.limit = limit;
            this.isLoading = true;
            this.createdComponent();
        },

        onClickCancel() {
            this.$router.push(this.parentRoute)
        },

        removeItem(item) {
            this.items.remove(item.id);
            this.itemRepository.delete(item.id, Context.api).then(() => {
                this.createNotificationSuccess({
                    title: this.$tc('eccb.save-success.title'),
                    message: this.$tc('eccb.save-success.text')
                });
            }).catch((error) => {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
                this.createdComponent();
            })
        },

        createItem() {
            this.$router.push({name: 'eccb.plugin.item-set.item.create', query: {itemSetId: this.itemSet.id}});
        }
    }
});