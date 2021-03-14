import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './tree-node-detail.twig'
import './tree-node-detail.scss';

Component.register('eccb-tree-node-detail', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    inject: ['repositoryFactory'],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        'SYSTEMKEY+B': 'onClickCancel',
        'SYSTEMKEY+N': 'addChildNode'
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            treeNode: null,
            isLoading: true,
            itemCard: null,
            itemCardList: null,
            products: [],
            children: null,
            mediaFolderName: 'Candy Bags',
            currentLanguageId: Context.api.languageId,
            treeNodeItemSet: null,
            inlineEdit: false,
            currentInlineEditId: null,
            page: 1,
            limit: 10,
            total: 0
        }
    },

    async created() {
        await this.createdComponent()
    },

    async mounted() {
        if (this.$route.name !== 'eccb.plugin.tree-node.detail') return;
        //is loaded 2 times..
        this.treeNode = await this.treeNodeRepository.get(this.$route.params.id, Context.api, this.treeNodeCriteria);
        if (this.treeNode.item.type === 'card') {
            this.$refs.sidebar.openContent();
        }
    },

    computed: {

        createChildRoute() {
            return {
                name: 'eccb.plugin.tree-node.create',
                query: {stepSetId: this.treeNode.stepSetId, parentId: this.treeNode.id}
            }
        },

        parentRoute() {
            if (this.treeNode.treeNodeItemSet && this.treeNode.treeNodeItemSet['treeNodeId']) {
                const id = this.treeNode.treeNodeItemSet['treeNodeId']
                return {name: 'eccb.plugin.tree-node.detail', params: {id: id}};
            }

            if (this.treeNode.parentId) {
                return {name: 'eccb.plugin.tree-node.detail', params: {id: this.treeNode.parentId}};
            } else {
                return {name: 'eccb.plugin.detail', params: {id: this.treeNode.stepSetId}};
            }
        },

        treeNodeRepository() {
            return this.repositoryFactory.create('eccb_tree_node');
        },

        treeNodeCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('item');
            return criteria
        },

        treeNodeChildrenCriteria() {
            const criteria = new Criteria();
            criteria.limit = this.limit;
            criteria.setPage(this.page);
            criteria.addAssociation('item');
            criteria.addFilter(Criteria.equals('parentId', this.$route.params.id));
            criteria.addSorting(Criteria.sort('item.position', 'desc'));
            return criteria;
        },

        itemCardRepository() {
            return this.repositoryFactory.create('eccb_item_card');
        },

        itemCardCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('media');
            criteria.addAssociation('product');
            return criteria;
        },

        itemCardListCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.not(
                'AND',
                [Criteria.equals('internalName', null)]
            ));
            return criteria
        },

        itemCardOptions() {
            return this.itemCardList.map((itemCard) => {
                return {
                    value: itemCard.id,
                    label: itemCard.internalName,
                }
            });
        },

        productRepository() {
            return this.repositoryFactory.create('product');
        },

        productOptions() {
            return this.products.map((product) => {
                return {
                    value: product.id,
                    label: product.name
                }
            });
        },

        productCriteria() {
            return new Criteria
        },


        childColumns() {
            return [
                {
                    property: 'stepDescription',
                    label: 'eccb.column.stepDescription',
                    inlineEdit: 'string',
                    routerLink: 'eccb.plugin.tree-node.detail',
                    primary: true
                },

                {
                    property: 'item.position',
                    label: 'eccb.column.position',
                    inlineEdit: 'number',
                },

                {
                    property: 'item.active',
                    label: 'eccb.column.active',
                    inlineEdit: 'boolean',
                },

                {
                    property: 'item.purchasable',
                    label: 'eccb.column.purchasable',
                    inlineEdit: 'boolean',
                },

                {
                    property: 'item.terminal',
                    label: 'eccb.column.terminal',
                    inlineEdit: 'boolean',
                },
            ]
        },
    },

    methods: {
        createNewItemCard() {
            this.itemCard = this.itemCardRepository.create(Context.api);
            this.treeNode.item.itemCardId = null;
        },

        async createdComponent() {
            this.isLoading = true;

            /** load itemCardList in parallel */
            this.itemCardRepository.search(this.itemCardListCriteria, Context.api).then((result) => {
                this.itemCardList = result;
            });

            /** load children in parallel */
            this.treeNodeRepository.search(this.treeNodeChildrenCriteria, Context.api).then((result) => {
                this.children = result;
                this.total = result.total;
            });

            this.treeNode = await this.treeNodeRepository.get(this.$route.params.id, Context.api, this.treeNodeCriteria);

            if (this.treeNode.item.itemCardId) {
                this.itemCard = await this.itemCardRepository.get(this.treeNode.item.itemCardId, Context.api, this.itemCardCriteria);
            } else {
                this.itemCard = this.itemCardRepository.create(Context.api);
            }

            await this.getProductList();

            this.isLoading = false;
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.createdComponent();
        },

        onClickCancel() {
            this.$router.push(this.parentRoute);
        },

        onChangeMedia(payload) {
            if (payload) {
                this.onSave();
            }
        },

        async onSave() {
            if (!this.validate()) return;
            this.isLoading = true;

            try {
                const type = this.treeNode.item.type;

                if (type === 'card') {
                    this.treeNode.item.itemCardId = this.itemCard.id;
                    await this.itemCardRepository.save(this.itemCard, Context.api);
                }

                await this.treeNodeRepository.save(this.treeNode, Context.api);
                await this.createdComponent();

            } catch (error) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }

        },

        validate() {
            let validated = true;

            if (!this.treeNode.stepDescription) {
                this.createNotificationError({
                    message: "o_0.. Missing required Fields:<br>" + this.$tc('eccb.field.stepDescription')
                });
                validated = false;
            }
            const type = this.treeNode.item.type;
            if (type === 'card') {
                if (!this.itemCard.name) {
                    this.createNotificationError({
                        message: "o_0.. Missing required Fields:<br>" + this.$tc('eccb.field.name')
                    });
                    validated = false;
                }

                if (!this.itemCard.internalName) {
                    this.createNotificationError({
                        message: "o_0.. Missing required Fields:<br>" + this.$tc('eccb.field.internalName')
                    });
                    validated = false;
                }
            }
            return validated;
        },

        createErrorMessage(exception) {
            let error = '';
            exception.response.data.errors.forEach(e => {
                error += `Error.. o_0: ${e.detail}\n ${e.source.pointer} \n\n`
            })
            return error;
        },

        async changeCard(id) {
            if (id) {
                this.itemCard = this.itemCardList.filter((e) => e.id === id)[0];
                this.treeNode.item.itemCardId = this.itemCard.id;
            }
        },

        async searchCard(payload) {
            const criteria = new Criteria();
            if (payload !== '') {
                criteria.addFilter(Criteria.contains('internalName', payload));
            }
            this.itemCardRepository.search(criteria, Context.api)
                .then((result) => {
                    this.itemCardList = result;
                    if (!result.length) {
                        const noCards = {
                            id: '000000',
                            name: 'No results found'
                        }
                        this.itemCardList.push(noCards);
                    }
                })
        },

        deleteItemCard(payload) {
            console.log(payload.value);
            const id = payload.value;
            this.itemCardRepository.delete(id, Context.api).then(() => {
                this.createdComponent();
            }).catch((error) => {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            })

        },

        deleteTreeNode(item) {
            this.children.remove(item.id);
            this.treeNodeRepository.delete(item.id, Context.api).then(() => {
                this.createNotificationSuccess({
                    title: this.$tc('eccb.save-success.title'),
                    message: this.$tc('eccb.save-success.text')
                });
            }).catch((exception) => {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: exception
                });
                this.createdComponent();
            })
        },

        setInlineEdit(payload, id) {
            this.inlineEdit = true;
            this.currentInlineEditId = id;
        },

        getInlineEdit(item) {
            return this.inlineEdit && this.currentInlineEditId === item['id'];
        },

        cancelInlineEdit() {
            this.inlineEdit = false;
            this.currentInlineEditId = null;
        },

        async onInlineEdit(item) {
            try {
                this.isLoading = true;
                await this.treeNodeRepository.save(item, Context.api);
                this.cancelInlineEdit();
                await this.createdComponent();
            } catch (error) {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }
        },

        addChildNode() {
            this.$router.push(this.createChildRoute);
        },

        onPageChange({page = 1, limit = 10}) {
            this.page = page;
            this.limit = limit;
            this.isLoading = true;
            this.createdComponent();
        },

        // Product
        async getProductList() {
            const result = await this.productRepository.search(this.productCriteria, Context.api)
            this.products = []
            result.forEach((product) => {
                this.products.push(product);
            })

            if (this.itemCard && (this.itemCard.productId !== null)) {
                const productInResult = this.products.filter((product) => product.id === this.itemCard.productId);
                if (!productInResult.length) {
                    this.products.push(this.itemCard.product);
                }
            }

            return Promise.resolve();
        },

        searchProduct(payload) {
            const criteria = new Criteria()
            if (payload !== '') {
                criteria.addFilter(Criteria.contains('name', payload));
            }
            this.productRepository.search(this.productCriteria, Context.api)
                .then((result) => {
                    this.products = result;
                    if (!result.length) {
                        const noProducts = {
                            id: '000000',
                            name: 'No results found'
                        }
                        this.products.push(noProducts);
                    }
                });
        },

        changeProduct(payload) {
            this.itemCard.productId = payload;
        }
    }

});