import {Component, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

Component.extend('eccb-tree-node-create', 'eccb-tree-node-detail', {

    data() {
        return {
            treeNodeItemSetId: null,
        }
    },

    computed: {
        // to create an item and add it to the treeNode object
        itemRepository() {
            return this.repositoryFactory.create('eccb_item');
        },

        treeNodeItemSetRepository() {
            return this.repositoryFactory.create('eccb_tree_node_item_set');
        },

        parentRoute() {
            if (this.$route.query.parentId) {
                return {name: 'eccb.plugin.tree-node.detail', params: {id: this.$route.query.parentId}};
            }

            if (this.$route.query.stepSetId) {
                return {name: 'eccb.plugin.tree-node.detail', params: {id: this.$route.query.stepSetId}};
            } else {
                return {name: 'eccb.plugin.detail', params: {id: this.$route.query.stepSetId}};
            }
        },
    },

    methods: {
        createdComponent() {
            if (Shopware.Context.api.languageId !== Shopware.Context.api.systemLanguageId) {
                Shopware.State.commit('context/setApiLanguageId', Shopware.Context.api.languageId)
            }

            if (!this.stepSet) {
                if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                    Shopware.State.commit('context/resetLanguageToDefault');
                }
            }

            if (this.$route.query.treeNodeItemSetId) {
                this.treeNodeItemSetId = this.$route.query.treeNodeItemSetId;
            }

            this.getEntities();
            this.isLoading = false;
        },

        async getEntities() {

            /** Get TreeNode */
            this.treeNode = this.treeNodeRepository.create(Context.api);

            if (this.$route.query.stepSetId) {
                this.treeNode.stepSetId = this.$route.query.stepSetId;
            }

            if (this.$route.query.parentId && !this.treeNodeItemSetId) {
                this.treeNode.parentId = this.$route.query.parentId;
            }

            /** Item */
            const item = this.itemRepository.create(Context.api);
            if (this.treeNode.parentId) {
                item.type = 'card';
            } else {
                item.type = 'step';
            }

            item.active = true;
            this.treeNode.item = item;

            /**
             * Add FK on save, to prevent saving empty Entities
             * @type {Context.api}
             */
            this.itemCard = this.itemCardRepository.create(Context.api);

            this.itemCardRepository.search(this.itemCardListCriteria, Context.api).then((result) => {
                this.itemCardList = result;
            });

            /** Belongs to ParentTreeNode-ItemSet Relation? */
            if (this.treeNodeItemSetId) {
                this.treeNodeItemSet = await this.treeNodeItemSetRepository.get(this.treeNodeItemSetId, Context.api, new Criteria())
            }
        },

        async onSave() {
            if (!this.validate()) return;

            try {
                const type = this.treeNode.item.type;
                if (type === 'card') {
                    this.treeNode.item.itemCardId = this.itemCard.id;
                    await this.itemCardRepository.save(this.itemCard, Context.api);
                }

                /** ManyToMany */
                if (this.treeNodeItemSetId) {
                    this.treeNode.treeNodeItemSetId = this.treeNodeItemSetId;
                }

                /** Save treeNode */
                await this.treeNodeRepository.save(this.treeNode, Context.api);


                this.isLoading = false;
                this.$router.push({name: 'eccb.plugin.tree-node.detail', params: {id: this.treeNode.id}});

                this.createNotificationSuccess({
                    title: this.$tc('eccb.tree-node.save-success.title'),
                    message: this.$tc('eccb.tree-node.save-success.text')
                });

            } catch (error) {
                this.createNotificationError({
                    title: this.$tc('eccb.tree-node.error'),
                    message: error
                });
            }
        }
    }
});