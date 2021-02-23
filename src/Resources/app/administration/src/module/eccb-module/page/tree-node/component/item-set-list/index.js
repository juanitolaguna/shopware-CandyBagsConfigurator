import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './item-set-list.twig'
import './item-set-list.scss';

Component.register('eccb-item-set-list-component', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    inject: [
        'repositoryFactory'
    ],

    props: {
        treeNode: {
            type: Object,
            required: true,
        }
    },

    data() {
        return {
            itemSetOptions: [],
            joinedTreeNodeItemSetValues: null,
            selectionLoading: false,
            isLoading: false,
            inlineEdit: false,
            currentInlineEditId: null
        }
    },

    created() {
        this.createdComponent();
    },

    computed: {

        itemSetRepository() {
            return this.repositoryFactory.create('eccb_item_set');
        },

        treeNodeRepository() {
            return this.repositoryFactory.create('eccb_tree_node');
        },

        treeNodeItemSetRepository() {
            return this.repositoryFactory.create('eccb_tree_node_item_set');
        },

        joinedTreeNodeItemSetCriteria() {
            const criteria = new Criteria();
            const treeNodeId = this.$route.params.id
            criteria.addFilter(Criteria.equals('treeNodeId', treeNodeId))
            criteria.addAssociation('itemSet');
            criteria.addAssociation('childNode.item');
            criteria.addSorting(Criteria.sort('childNode.item.position', 'desc'));
            return criteria;
        },

        itemSetSelectedValues() {
            if (!this.joinedTreeNodeItemSetValues) {
                return [];
            }
            return this.joinedTreeNodeItemSetValues.map((ti) => {
                return ti.itemSetId;
            });
        },

        columns() {
            return [
                {
                    property: 'childNode.stepDescription',
                    label: 'Next Step',
                    inlineEdit: 'string',
                },

                {
                    property: 'itemSet.internalName',
                    label: 'ItemSet Name',
                    inlineEdit: 'string',
                    primary: true,
                    routerLink: 'eccb.plugin.item-set.detail'
                },

                {
                    property: 'childNode.item.position',
                    label: 'Position',
                    inlineEdit: 'number',
                },

                {
                    property: 'childNode.item.active',
                    label: 'Active',
                    inlineEdit: 'boolean',
                }
            ]
        },
    },

    methods: {
        async createdComponent() {
            if(this.treeNode._isNew) return;
            try {
                this.itemSetOptions = await this.getItemSetsOptions();
                this.joinedTreeNodeItemSetValues = await this.getJoinedTreeNodeItemSetValues();
            } catch (error) {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }
        },

        //Item Set Selection
        getItemSetsOptions() {
            return this.itemSetRepository.search(new Criteria(), Shopware.Context.api);
        },

        getJoinedTreeNodeItemSetValues() {
            return this.treeNodeItemSetRepository.search(this.joinedTreeNodeItemSetCriteria, Context.api);
        },

        async onItemSetAdd(item) {
            this.selectionLoading = true;
            let entity = this.treeNodeItemSetRepository.create(Shopware.Context.api);
            entity.treeNodeId = this.$route.params.id;
            entity.itemSetId = item.id;
            try {
                await this.treeNodeItemSetRepository.save(entity, Shopware.Context.api);
                this.joinedTreeNodeItemSetValues = await this.getJoinedTreeNodeItemSetValues();
                this.selectionLoading = false;
            } catch (error) {
                this.selectionLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }
        },

        async onItemSetRemove(item) {
            console.log(item)
            this.selectionLoading = true;
            const itemSetToRemove = this.joinedTreeNodeItemSetValues.find((cp) => cp.itemSetId === item.id);
            console.log(itemSetToRemove);
            try {
                await this.treeNodeItemSetRepository.delete(itemSetToRemove.id, Shopware.Context.api);
                this.joinedTreeNodeItemSetValues = await this.getJoinedTreeNodeItemSetValues();
                this.selectionLoading = false;
            } catch (error) {
                this.selectionLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }
        },

        async onItemSetRemoveFromGrid(item) {
            try {
                this.selectionLoading = true;
                await this.treeNodeItemSetRepository.delete(item.id, Shopware.Context.api);
                this.joinedTreeNodeItemSetValues = await this.getJoinedTreeNodeItemSetValues();
                this.selectionLoading = false;
            } catch (error) {
                this.selectionLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }
        },

        setInlineEdit(payload, id) {
            this.inlineEdit = true;
            this.currentInlineEditId = id;
        },

        getInlineEdit(item) {
            return this.inlineEdit && this.currentInlineEditId === item['id'];
        },

        async onInlineEdit(item) {
            try {
                await this.itemSetRepository.save(item.itemSet, Context.api);
                if (item.childNode) {
                    await this.treeNodeRepository.save(item.childNode, Context.api);
                }
                await this.createdComponent();
                this.cancelInlineEdit();
            } catch (error) {
                this.createNotificationError({
                    title: this.$tc('eccb.step-set.error'),
                    message: error
                });
            }
        },

        cancelInlineEdit () {
            this.inlineEdit = false;
            this.currentInlineEditId = null;
        }
    }
});