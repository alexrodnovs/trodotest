'use strict';

import Vue from 'vue';
import RatesTable from "../../assets/components/ratesTable.vue";

const RatesListTable = {
    init: function () {
        var app = new Vue({
            el: '#app',
            components: {
                'rates-table': RatesTable
            },
        })
    }
};

$(document).ready(function () {
    RatesListTable.init()
});