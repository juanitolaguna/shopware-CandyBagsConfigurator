import {Component} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './eccb-candybag-list.twig'
import './eccb-candybag-list.scss';

Component.register('eccb-candybag-list', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            candyBags: null,
            isLoading: true,
            currentLanguageId: Shopware.Context.api.languageId
        }
    },

    created() {
        this.componentCreated();
    },

    computed: {
        candyBagsRepository() {
            return this.repositoryFactory.create('eccb_candy_bag');
        },

        candyBagsCriteria() {
            return new Criteria();
        },

        columns() {
            return [
                {
                    property: 'name',
                    dataIndex: 'name',
                    label: 'Name',
                    inlineEdit: 'string',
                    routerLink: 'eccb.module.detail',
                    primary: true
                },

                {
                    property: 'description',
                    label: 'Description',
                    inlineEdit: 'string',
                },

                {
                    property: 'minSteps',
                    label: 'Min. Steps',
                    inlineEdit: 'number',
                },

                {
                    property: 'position',
                    label: 'Position',
                    inlineEdit: 'number',
                },

                {
                    property: 'active',
                    label: 'Active',
                    inlineEdit: 'boolean',
                }
            ]
        }
    },

    methods: {
        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.componentCreated();
        },

        componentCreated() {
            this.getCandyBags();
        },

        getCandyBags() {
            this.isLoading = true;
            return this.candyBagsRepository
                .search(this.candyBagsCriteria, Shopware.Context.api)
                .then((result) => {
                    this.candyBags = result;
                    this.isLoading = false;
                })
        }

    }




})