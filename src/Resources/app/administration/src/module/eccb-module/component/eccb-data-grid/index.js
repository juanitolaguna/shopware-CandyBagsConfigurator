const { Component, Context, Utils } = Shopware;

Component.extend('eccb-data-grid','sw-data-grid', {
    methods: {
        onDbClickCell(record) {
            if (!this.allowInlineEdit || !this.isRecordEditable(record)) {
                return;
            }
            this.$emit('is-inline-edit', true, record[this.itemIdentifierProperty]);
            this.enableInlineEdit();
            this.currentInlineEditId = record[this.itemIdentifierProperty];
        },
    }
});