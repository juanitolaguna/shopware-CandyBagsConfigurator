import {Component, Context, Mixin} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

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
            const criteria =  new Criteria();
            return criteria;
        },

        itemCardCriteria() {
            const criteria =  new Criteria();
            criteria.addAssociation('media');
            return criteria;
        },

        parentRoute() {
            if (this.item.itemSetId) {
                return {name: 'eccb.plugin.item-set.detail', params: {id: this.item.itemSetId}}
            } else {
                return {name: 'eccb.plugin.tree-node.detail'}
            }
        }
    },

    methods: {
        async createdComponent() {
            this.item = await this.itemRepository.get(this.$route.params.id, Context.api, this.itemCriteria);
            this.itemCard = await this.itemCardRepository.get(this.item.itemCardId, Context.api, this.itemCardCriteria);
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
                    message: "o_0.. Missing required Fields:<br>" + this.$tc('eccb.name')
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
        }
    },


});