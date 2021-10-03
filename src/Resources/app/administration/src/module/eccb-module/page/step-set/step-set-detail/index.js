import {Component, Mixin, Context} from 'src/core/shopware';
const {Criteria} = Shopware.Data;

import template from './step-set-detail.twig';
import './step-set-detail.scss'

Component.register('eccb-step-set-detail', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        'SYSTEMKEY+B': 'onClickCancel',
        'SYSTEMKEY+N': 'createTreeNode'
    },

    inject: ['repositoryFactory'],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            stepSet: null,
            taxes: null,
            steps: null,
            isLoading: true,
            mediaFolderName: 'Candy Bags',
            currentLanguageId: Context.api.languageId,
            inlineEdit: false,
            currentInlineEditId: null,
            currencies: null
        }
    },

    created() {
        this.createdComponent()
    },

    mounted() {
        this.$refs.sidebar.openContent();
    },

    computed: {
        treeNodeColumns() {
            return [
                {
                    property: 'stepDescription',
                    label: 'eccb.column.sequence.stepDescription',
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
                }
            ]
        },

        stepSetRepository() {
            return this.repositoryFactory.create('eccb_step_set');
        },

        treeNodeRepository() {
            return this.repositoryFactory.create('eccb_tree_node');
        },

        itemRepository() {
            return this.repositoryFactory.create('eccb_item');
        },

        currencyRepository() {
            return this.repositoryFactory.create('currency');
        },

        taxRepository() {
            return this.repositoryFactory.create('tax');
        },

        /** ToDo: not used? */
        treeNodeCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('item');
            criteria.addFilter(Criteria.equals('stepSetId', this.$route.params.id));
            return criteria;
        },

        defaultCurrency() {
            if (!this.currencies) {
                return {};
            }

            const defaultCurrency = this.currencies.find((currency) => currency.isSystemDefault);
            return defaultCurrency || {};
        }
    },

    methods: {
        async createdComponent() {
            this.isLoading = true;

            await Promise.all([
                this.getStepSet(),
                this.getSteps()
            ]);

            this.isLoading = false;
        },

        async loadCurrencies() {
            return this.currencyRepository.search(new Criteria(1, 500), Context.api).then((res) => {
                this.currencies = res;
            })
        },

        async loadTaxes() {
            return this.taxRepository.search(new Criteria(1, 500), Context.api).then((res) => {
               this.taxes = res;
            });
        },


        async getStepSet() {
            const criteria = new Criteria();
            criteria.addAssociation('media');
            criteria.addAssociation('selectionBaseImage');
            this.stepSet = await this.stepSetRepository.get(this.$route.params.id, Context.api, criteria);
            await this.loadTaxes();
            await this.loadCurrencies()
            if (this.stepSet.price == null) {
                this.stepSet.price = [{
                    currencyId: this.defaultCurrency.id,
                    net: null,
                    linked: true,
                    gross: null
                }];
            }
        },

        async getSteps() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('stepSetId', this.$route.params.id));
            criteria.addFilter(Criteria.equals('parentId', null));
            criteria.addFilter(Criteria.equals('treeNodeItemSetId', null));

            criteria.addAssociation('item');
            criteria.addSorting(Criteria.sort('item.position', 'desc'));
            return this.treeNodeRepository.search(criteria, Context.api).then((result) => {
                this.steps = result;
                return Promise.resolve();
            })
        },


        onMediaSelect() {
            this.onSave();
        },

        validate() {
            let validated = true;
            if (!this.stepSet.name) {
                this.createNotificationError({
                    message: "o_0.. Missing required Fields:<br>" + this.$tc('eccb.field.name')
                });
                validated = false;
            }
            return validated;
        },

        async onSave() {
            if (!this.validate()) return;
            this.isLoading = true;
            try {
                await this.stepSetRepository.save(this.stepSet, Context.api);
                await this.createdComponent();
            } catch (error) {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.createdComponent();
        },

        onClickCancel() {
            this.$router.push({name: 'eccb.plugin.index'});
        },

        async onInlineEditTreeNodes(item) {
            await Promise.all([
                this.treeNodeRepository.save(item, Context.api)
            ]).then(() => {
                this.inlineEdit = false;
                this.getSteps();
                this.createNotificationSuccess({
                    title: this.$tc('eccb.save-success.title'),
                    message: this.$tc('eccb.save-success.text')
                });
            }).catch((error) => {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            })
        },

        deleteTreeNode(item) {
            this.steps.remove(item.id);
            this.treeNodeRepository.delete(item.id, Context.api).then(() => {
                this.createNotificationSuccess({
                    title: this.$tc('eccb.save-success.title'),
                    message: this.$tc('eccb.save-success.text')
                });
            }).catch((error) => {
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: this.createErrorMessage(error)
                });
                this.createdComponent();
            })
        },

        editTreeNode(item) {
            this.$router.push({name: 'eccb.plugin.tree-node.detail', params: {id: item.id}})
        },

        setInlineEdit(payload, id) {
            this.inlineEdit = true;
            this.currentInlineEditId = id;
        },

        getInlineEdit(item) {
            return this.inlineEdit && this.currentInlineEditId === item['id'];
        },

        createTreeNode() {
            this.$router.push({name: 'eccb.plugin.tree-node.create', query: {stepSetId: this.stepSet.id}});
        },

        createErrorMessage(exception) {
            let error = '';
            exception.response.data.errors.forEach(e => {
                error += `Error.. o_0: <br> ${e.detail}<br>`
            })
            return error;
        },

        updateCurrentTaxRate(payload) {
            this.stepSet.taxId = payload;
            this.onSave();
        }

    },


});
