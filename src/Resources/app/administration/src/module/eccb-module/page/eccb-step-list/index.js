import {Component} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './eccb-step-list.twig'
import './eccb-step-list.scss';

Component.register('eccb-step-list', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            configuratorSteps: null,
            isLoading: true,
            currentLanguageId: Shopware.Context.api.languageId
        }
    },

    created() {
        this.componentCreated();
    },

    computed: {
        configuratorStepRepository() {
            return this.repositoryFactory.create('eccb_configurator_step');
        },

        configuratorStepCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('parentId', null));
            return criteria;
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
            return this.configuratorStepRepository
                .search(this.configuratorStepCriteria, Shopware.Context.api)
                .then((result) => {
                    this.configuratorSteps = result;
                    this.isLoading = false;
                })
        }

    }




})