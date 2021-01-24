import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './eccb-candybag-detail.twig';
import './eccb-candybag-detail.scss';

Component.register('eccb-candybag-detail', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    inject: ['repositoryFactory'],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            candyBag: null,
            isLoading: true,
            currentLanguageId: Context.api.languageId
        }
    },

    created() {
        this.createdComponent()
    },

    mounted() {
        this.$refs.candyBagSidebar.openContent();
    },

    computed: {
        candyBagRepository() {
            return this.repositoryFactory.create('eccb_candy_bag');
        }
    },

    methods: {
        createdComponent() {
            this.getCandyBag();
        },

        getCandyBag() {
            const criteria = new Criteria();
            criteria.addAssociation('media.thumbnails')

            return this.candyBagRepository.get(this.$route.params.id, Context.api, criteria)
                .then((result) => {
                    this.candyBag = result;
                    this.isLoading = false
                })
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.componentCreated();
        },

        onCancel() {
            this.$router.push({name: 'eccb.module.index'});
        },

        onMediaSelect() {
            this.onClickSave();
        },

        onClickSave() {
            this.isLoading = true;

            this.candyBagRepository
                .save(this.candyBag, Context.api)
                .then(() => {
                    this.getCandyBag();
                    this.isLoading = false
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.candybag-detail.notification.error'),
                    message: exception
                });
            })
        }
    }


})