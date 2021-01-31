import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './eccb-step-detail.twig';
import './eccb-step-detail.scss';

Component.register('eccb-step-set-detail', {
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
            configuratorStep: null,
            isLoading: true,
            currentLanguageId: Context.api.languageId,
            sortBy: 'position',
            sortDirection: 'ASC',
        }
    },

    created() {
        this.createdComponent()
    },

    mounted() {
        this.$refs.candyBagSidebar.openContent();
    },

    computed: {
        configuratorStepRepository() {
            return this.repositoryFactory.create('eccb_configurator_step');
        },

        productRepository() {
            return this.repositoryFactory.create('product');
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
        createdComponent() {
            this.getConfiguratorStep();
        },

        getConfiguratorStep() {
            const criteria = new Criteria();
            criteria.addAssociation('media');
            criteria.addAssociation('product');
            criteria.addAssociation('parent');
            criteria.addAssociation('children');


            return this.configuratorStepRepository.get(this.$route.params.id, Context.api, criteria)
                .then((result) => {
                    console.log('getStep')
                    this.configuratorStep = result;
                    this.isLoading = false
                });
        },

        deleteChild(item) {

            this.configuratorStep.children.remove(item.id);
            this.onClickSave();
        },

        editChild(item) {
            this.$router.push({name: 'eccb.module.detail', params: {id: item.id}});
            this.createdComponent();
        },

        onInlineEditChild(item) {
            this.configuratorStepRepository.save(item, Context.api).then(() => {
                this.createdComponent();
            });

        },

        onSortColumn(column) {
            // this.$refs.childrenGrid.loading = true;

            console.log(this.$refs.childrenGrid);
            // return this.configuratorStepRepository.search(this.$refs.childrenGrid.items.criteria, Contect.api)
            //     .then((result) => {
            //         this.configuratorStep.hil
            //     });

        },

        onColumnSort(column) {
            this.$refs.swProductGrid.loading = true;

            const context = Object.assign({}, Shopware.Context.api);
            context.currencyId = column.currencyId;

            return this.$refs.swProductGrid.repository.search(this.$refs.swProductGrid.items.criteria, context)
                .then(this.$refs.swProductGrid.applyResult);
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.componentCreated();
        },

        onCancel() {
            if (this.configuratorStep.parentId) {
                console.log('parentId: ' + this.configuratorStep.parentId);
                this.$router.push({name: 'eccb.module.detail', params: {id: this.configuratorStep.parentId}});
                this.createdComponent();
            } else {
                this.$router.push({name: 'eccb.module.index'});
            }
        },

        onSelectProduct(id, product) {
            this.configuratorStep.productVersionId = product.versionId;
            this.configuratorStep.product = product;
        },

        onMediaSelect() {
            this.onClickSave();
        },

        onClickSave() {
            this.isLoading = true;

            this.configuratorStepRepository
                .save(this.configuratorStep, Context.api)
                .then(() => {
                    this.getConfiguratorStep();
                    this.isLoading = false
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.step-detail.notification.error'),
                    message: exception
                });
            })
        }
    }


})