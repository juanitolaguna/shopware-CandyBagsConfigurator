import {Component, Context, Mixin} from 'src/core/shopware';
const {Criteria} = Shopware.Data;

import template from '../item-set-item-detail/item-set-item-detail.twig';
import './item-set-item-detail.scss';

Component.register('eccb-item-set-item-detail', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    inject: ['repositoryFactory'],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        'SYSTEMKEY+B': 'onClickCancel',
    },

    data() {
        return {
            item: null,
            itemCard: null,
            isLoading: true,
            products: []
        }
    },

    created() {
        this.createdComponent();
    },

    mounted() {
        this.$refs.sidebar.openContent();
    },

    computed: {

        itemRepository() {
            return this.repositoryFactory.create('eccb_item');
        },

        itemCardRepository() {
            return this.repositoryFactory.create('eccb_item_card');
        },

        itemCriteria() {
            return new Criteria();
        },

        itemCardCriteria() {
            const criteria =  new Criteria();
            criteria.addAssociation('media');
            criteria.addAssociation('product');
            return criteria;
        },

        parentRoute() {
            if (this.item.itemSetId) {
                return {name: 'eccb.plugin.item-set.detail', params: {id: this.item.itemSetId}}
            } else {
                return {name: 'eccb.plugin.tree-node.detail'}
            }
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
            return new Criteria();
        }
    },

    methods: {
        async createdComponent() {
            this.item = await this.itemRepository.get(this.$route.params.id, Context.api, this.itemCriteria);
            this.itemCard = await this.itemCardRepository.get(this.item.itemCardId, Context.api, this.itemCardCriteria);
            await this.getProductList();
            this.isLoading = false;
        },

        async onSave() {
            if (!this.validate()) return;
            this.isLoading = true;

            try {
                const type = this.item.type;

                if (type === 'card') {
                    this.item.itemCardId = this.itemCard.id;
                    await this.itemCardRepository.save(this.itemCard, Context.api);
                }

                await this.itemRepository.save(this.item, Context.api);
                await this.createdComponent();

            } catch (error) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }

        },

        onChangeMedia(payload) {
            if (payload) {
                this.onSave();
            }
        },


        validate() {
            let validated = true;

            if (!this.item.internalName) {
                this.createNotificationError({
                    message: "o_0.. Missing required Fields:<br>" + this.$tc('eccb.field.name')
                });
                validated = false;
            }
            return validated;
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.createdComponent();
        },

        onCardNameInput(input) {
            this.item.internalName = input;
        },

        onClickCancel() {
            this.$router.push(this.parentRoute);
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
            const criteria = new Criteria();
            if (payload !== '') {
                criteria.addFilter(Criteria.contains('name', payload));
            }
            this.productRepository.search(criteria, Context.api)
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
    },


});