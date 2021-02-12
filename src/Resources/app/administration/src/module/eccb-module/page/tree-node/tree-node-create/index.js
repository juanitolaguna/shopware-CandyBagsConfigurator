import {Component, Context} from 'src/core/shopware';

Component.extend('eccb-tree-node-create', 'eccb-tree-node-detail', {

    computed: {
        // to create an item and add it to the treeNode object
        itemRepository() {
            return this.repositoryFactory.create('eccb_item');
        },

        parentRoute() {
            if (this.treeNode.parentId) {
                return {name: 'eccb.plugin.tree-node.detail', params: {id: this.treeNode.parentId}}
            } else {
                return {name: 'eccb.plugin.detail', params: {id: this.treeNode.stepSetId}}
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

            this.getEntities();
            this.isLoading = false;
        },

        getEntities() {
            /** Get TreeNode */
            this.treeNode = this.treeNodeRepository.create(Context.api);

            this.treeNode.stepSetId = this.$route.params.stepSetId;
            /** ToDo: change to query */
            if (this.$route.params.parentId !== '#') {
                console.log('set parent treeNode');
                this.treeNode.parentId = this.$route.params.parentId;
            }

            /** Item */
            const item = this.itemRepository.create(Context.api);
            item.type = 'card'
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
        },

        async onSave() {
            if (!this.validate()) return;

            try {
                const type = this.treeNode.item.type;
                if (type === 'card') {
                    this.treeNode.item.itemCardId = this.itemCard.id;
                    await this.itemCardRepository.save(this.itemCard, Context.api);
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