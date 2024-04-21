<template>
  <div class="container mt-5">
    <h1>1 {{ baseCurrency }} to {{ selectedCurrency }} Exchange Rate</h1>
    <h5>Last updated: {{ lastUpdate }}</h5>
    <label for="currencyFilter">Filter by Currency:</label>
    <select id="currencyFilter" class="form-control mb-3" v-model="selectedCurrency" @change="fetchRates">
      <option v-for="currency in currencies" :value="currency">{{ currency }}</option>
    </select>

    <pagination
        :current-page="currentPage"
        :total-pages="totalPages"
        @page-change="changePage"
    ></pagination>

    <div class="table-responsive mt-3">
      <table v-if="filteredRates.length > 0" class="table table-bordered">
        <thead class="thead-dark">
        <tr>
          <th @click="sortByColumn('date')" :class="getColumnSortClasses('date')" class="text-center">
            Date
            <span class="sort-icon" v-if="sortBy === 'date'">
              <i class="fas fa-sort-up" v-if="!sortDesc"></i>
              <i class="fas fa-sort-down" v-if="sortDesc"></i>
            </span>
          </th>
          <th @click="sortByColumn('rate')" :class="getColumnSortClasses('rate')" class="text-center">
            {{ baseCurrency }} to {{ selectedCurrency }}
            <span class="sort-icon" v-if="sortBy === 'rate'">
              <i class="fas fa-sort-up" v-if="!sortDesc"></i>
              <i class="fas fa-sort-down" v-if="sortDesc"></i>
            </span>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="data in filteredRates">
          <td class="text-center">{{ data.date }}</td>
          <td class="text-center">{{ parseFloat(data.rate) }}</td>
        </tr>
        </tbody>
      </table>
      <p v-else>No data available for the selected currency.</p>
    </div>
    <div class="d-flex justify-content-between">
      <div>
        <div class="d-flex justify-content-between">
          <div class="mr-2">Minimum:</div>
          <div>{{ parseFloat(footer.min_rate)}} {{ selectedCurrency }}</div>
        </div>
        <div class="d-flex justify-content-between">
          <div class="mr-2">Maximum:</div>
          <div>{{ parseFloat(footer.max_rate)}} {{ selectedCurrency }}</div>
        </div>
        <div class="d-flex justify-content-between">
          <div class="mr-2">Average:</div>
          <div>{{ parseFloat(footer.avg_rate)}} {{ selectedCurrency }}</div>
        </div>
      </div>

      <div>
        <pagination
            :current-page="currentPage"
            :total-pages="totalPages"
            @page-change="changePage"
        ></pagination>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
import Pagination from './Pagination.vue'; // Adjust the path as needed
export default {
  components: {
    Pagination
  },
  data() {
    return {
      currencies: JSON.parse(window.currencies),
      rates: [],
      sortBy: 'date',
      sortDesc: true,
      currentPage: 1,
      totalPages: 1,
      lastUpdate: '',
      baseCurrency: window.baseCurrency,
      selectedCurrency: window.baseCurrency,
    }
  },
  mounted() {
    this.fetchRates();
  },
  methods: {
    fetchRates() {
      axios.get('/get-rates', {
        params: {
          base: this.baseCurrency,
          to: this.selectedCurrency,
          sortBy: this.sortBy,
          sortOrder: this.sortDesc ? 'DESC' : 'ASC',
          page: this.currentPage,
        }
      })
      .then(response => {
        this.rates = response.data.items;
        this.totalPages = response.data.total_pages;
        this.footer = response.data.footer;
        this.lastUpdate = moment(String(response.data.last_update.date)).format('DD.MM.YYYY');
      })
      .catch(error => {
        console.error('Error fetching rates:', error);
      });
    },
    sortByColumn(column) {
      if (this.sortBy === column) {
        this.sortDesc = !this.sortDesc;
      } else {
        this.sortBy = column;
        this.sortDesc = false; // Reset to ascending order when sorting by a new column
      }

      this.fetchRates()
    },
    changePage(page) {
      this.currentPage = page;
      this.fetchRates();
    },
    getColumnSortClasses(columnName) {
      return {
        'asc': this.sortBy === columnName && !this.sortDesc,
        'desc': this.sortBy === columnName && this.sortDesc
      };
    }
  },
  computed: {
    filteredRates: function() {
      return this.rates.map(rate => {
        return {
          date: moment(String(rate.date.date)).format('DD.MM.YYYY'),
          rate: rate.rate,
        };
      });
    }
  }
}
</script>