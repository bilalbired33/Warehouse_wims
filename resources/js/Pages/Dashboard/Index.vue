<template>
  <admin-layout :title="$t('Dashboard')">
    <div class="px-4 md:px-0">
      <!-- <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Roles') }}</template>
        <template #description>{{ $t('Please review the data in the table below') }}</template>
      </tec-section-title> -->

      <section class="-mt-4 mb-4 mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="p-4 rounded-md shadow bg-white">
            <div class="flex items-start justify-between">
              <h2 class="mb-2 text-xl font-semibold leading-none text-gray-900 truncate">{{ data.checkins }}</h2>
              <span
                v-if="data.checkins != 0 && data.previous_checkins != 0"
                class="flex items-center space-x-1 text-sm font-medium leading-none"
                :class="data.checkins > data.previous_checkins ? 'text-green-500' : 'text-red-500'"
              >
                <icons v-if="data.checkins > data.previous_checkins" name="up" />
                <icons v-else name="down" />
                <span> {{ $number((data.checkins / data.previous_checkins) * 100 - 100) }}% </span>
              </span>
            </div>
            <p class="leading-none text-gray-600">{{ $t('Checkins') }}</p>
          </div>
          <div class="p-4 rounded-md shadow bg-white">
            <div class="flex items-start justify-between">
              <h2 class="mb-2 text-xl font-semibold leading-none text-gray-900 truncate">{{ data.checkouts }}</h2>
              <span
                v-if="data.checkouts != 0 && data.previous_checkouts != 0"
                class="flex items-center space-x-1 text-sm font-medium leading-none"
                :class="data.checkouts > data.previous_checkouts ? 'text-green-500' : 'text-red-500'"
              >
                <icons v-if="data.checkouts > data.previous_checkouts" name="up" />
                <icons v-else name="down" />
                <span> {{ $number((data.checkouts / data.previous_checkouts) * 100 - 100) }}% </span>
              </span>
            </div>
            <p class="leading-none text-gray-600">{{ $t('Checkouts') }}</p>
          </div>
          <div class="p-4 rounded-md shadow bg-white">
            <div class="flex items-start justify-between">
              <h2 class="mb-2 text-xl font-semibold leading-none text-gray-900 truncate">{{ data.items }}</h2>
            </div>
            <p class="leading-none text-gray-600">{{ $t('Items') }}</p>
          </div>
          <div class="p-4 rounded-md shadow bg-white">
            <div class="flex items-start justify-between">
              <h2 class="mb-2 text-xl font-semibold leading-none text-gray-900 truncate">{{ data.contacts }}</h2>
            </div>
            <p class="leading-none text-gray-600">{{ $t('Contacts') }}</p>
          </div>
        </div>
      </section>

      <div class="flex items-center gap-4 mb-2">
        <auto-complete v-model="month" :suggestions="months" class="w-1/2" @update:modelValue="reload" />
        <auto-complete v-model="year" :suggestions="years" class="w-1/2" @update:modelValue="reload" />
      </div>

      <div class="bg-white rounded-md shadow overflow-x-auto">
        <vue-highcharts
          type="chart"
          :options="barChartData"
          :redrawOnUpdate="true"
          :oneToOneUpdate="false"
          :animateOnUpdate="true"
          style="min-width: 550px"
        />
      </div>
      <div class="mt-4 flex items-start flex-col md:flex-row gap-4">
        <div class="w-full md:w-1/2 bg-white rounded-md shadow overflow-x-auto">
          <vue-highcharts type="chart" :options="pieChartData" :redrawOnUpdate="true" :oneToOneUpdate="false" :animateOnUpdate="true" />
        </div>
        <div class="w-full md:w-1/2 bg-white rounded-md shadow overflow-x-auto">
          <vue-highcharts
            type="chart"
            :options="radialChartData"
            :redrawOnUpdate="true"
            :oneToOneUpdate="false"
            :animateOnUpdate="true"
            style="min-width: 550px"
          />
        </div>
      </div>
    </div>
  </admin-layout>
</template>

<script>
import VueHighcharts from 'vue3-highcharts';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AutoComplete from '@/Shared/AutoComplete.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  props: ['data', 'chart', 'top_products'],

  components: { AdminLayout, AutoComplete, VueHighcharts, TecSectionTitle },

  data() {
    return {
      year: new Date().getFullYear(),
      month: new Date().getMonth() + 1,
      months: [
        { label: this.$monthName('Jan 2021'), value: 1 },
        { label: this.$monthName('Feb 2021'), value: 2 },
        { label: this.$monthName('Mar 2021'), value: 3 },
        { label: this.$monthName('Apr 2021'), value: 4 },
        { label: this.$monthName('May 2021'), value: 5 },
        { label: this.$monthName('Jun 2021'), value: 6 },
        { label: this.$monthName('Jul 2021'), value: 7 },
        { label: this.$monthName('Aug 2021'), value: 8 },
        { label: this.$monthName('Sep 2021'), value: 9 },
        { label: this.$monthName('Oct 2021'), value: 10 },
        { label: this.$monthName('Nov 2021'), value: 11 },
        { label: this.$monthName('Dec 2021'), value: 12 },
      ],
    };
  },

  computed: {
    years() {
      let years = [];
      let date = new Date();
      let year = date.getFullYear();
      for (let y = 2020; y <= year; y++) {
        years.push({ label: y + '', value: y + '' });
      }
      return years;
    },
    barChartData() {
      return this.barChartOptions();
    },
    pieChartData() {
      return this.pieChartOptions();
    },
    radialChartData() {
      return this.radialChartOptions();
    },
  },

  // watch: {
  //   month: function (m) {
  //     // let month = this.months.find(mn => mn.value == m).label;
  //     this.$inertia.visit(route('dashboard', { month: +m, year: +this.year }), { preserveState: false, preserveScroll: false });
  //   },
  //   year: function (y) {
  //     this.$inertia.visit(route('dashboard', { month: +this.month, year: +y }), { preserveState: false, preserveScroll: false });
  //   },
  // },

  created() {
    let params = new URLSearchParams(this.$page.url.substring(1));
    if (params.get('month') && params.get('year')) {
      this.year = params.get('year');
      this.month = params.get('month');
    }

    // if (params.get('year') && this.year != params.get('year')) {
    //   this.year = params.get('year');
    // }
    // if (params.get('month') && this.month != params.get('month')) {
    //   this.month = params.get('month');
    // }
  },

  methods: {
    reload() {
      this.$inertia.visit(route('dashboard', { month: +this.month, year: +this.year }), { preserveState: false, preserveScroll: false });
    },
    sortValues(data) {
      return Object.values(
        Object.keys(data)
          .sort()
          .reduce((obj, key) => {
            obj[key] = data[key];
            return obj;
          }, {})
      );
    },
    onRender() {
      console.log('Chart rendered');
    },
    onUpdate() {
      console.log('Chart updated');
    },
    onDestroy() {
      console.log('Chart destroyed');
    },

    radialChartOptions() {
      return {
        chart: {
          zoomType: 'xy',
          spacingTop: 20,
          style: { fontFamily: 'Nunito' },
        },
        credits: {
          enabled: false,
        },
        title: {
          text: this.$t('Month Overview'),
          style: { fontWeight: 'bold', paddingTop: '10px' },
        },
        subtitle: {
          text: this.$t('Please review the chart below'),
        },
        colors: ['#059669', '#D97706', '#4F46E5', '#DC2626'],
        xAxis: [
          {
            // categories: this.$dateDay(Object.keys(this.chart.month.checkin)),
            categories: Object.keys(this.chart.month.checkin)
              .sort()
              .map(d => this.$date(d)),
            crosshair: true,
          },
        ],
        yAxis: {
          min: 0,
          title: {
            text: '',
          },
        },
        tooltip: {
          shared: true,
          shadow: false,
          useHTML: true,
          borderRadius: '5',
          borderColor: '#1F2937',
          style: { color: '#fff' },
          backgroundColor: '#1F2937',
        },
        series: [
          {
            type: 'spline',
            name: this.$t('Checkins'),
            data: this.sortValues(this.chart.month.checkin),
          },
          {
            type: 'spline',
            name: this.$t('Checkouts'),
            data: this.sortValues(this.chart.month.checkout),
          },
          {
            type: 'spline',
            name: this.$t('Transfers'),
            data: this.sortValues(this.chart.month.transfer),
          },
          {
            type: 'spline',
            name: this.$t('Adjustments'),
            data: this.sortValues(this.chart.month.adjustment),
          },
        ],
      };
    },
    pieChartOptions() {
      return {
        chart: {
          type: 'pie',
          spacingTop: 20,
          plotShadow: false,
          plotBorderWidth: null,
          plotBackgroundColor: null,
          style: { fontFamily: 'Nunito' },
        },
        credits: {
          enabled: false,
        },
        colors: ['#059669', '#D97706', '#4F46E5', '#DC2626', '#3182CE', '#DB2777', '#4B5563', '#805AD5', '#38B2AC', '#ECC94B'],
        title: {
          text: this.$t('Top Products'),
          style: { fontWeight: 'bold', paddingTop: '10px' },
        },
        subtitle: {
          text: this.$t('Top 10 products for the month'),
        },
        tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
          shared: true,
          shadow: false,
          useHTML: true,
          borderRadius: '5',
          borderColor: '#1F2937',
          style: { color: '#fff' },
          backgroundColor: '#1F2937',
        },
        accessibility: {
          point: {
            valueSuffix: '%',
          },
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: false,
            },
            showInLegend: true,
          },
        },
        series: [
          {
            colorByPoint: true,
            name: this.$t('Movement'),
            data: this.top_products
              .sort((a, b) => (a.y < b.y ? 1 : b.y < a.y ? -1 : 0))
              .map((i, ii) => (ii ? i : { ...i, sliced: true, selected: true })),
          },
        ],
      };
    },
    barChartOptions() {
      return {
        chart: {
          type: 'column',
          spacingTop: 20,
          style: { fontFamily: 'Nunito' },
        },
        credits: {
          enabled: false,
        },
        title: {
          text: this.$t('Year Overview'),
          style: { fontWeight: 'bold', paddingTop: '10px' },
        },
        subtitle: {
          text: this.$t('Please review the chart below'),
        },
        colors: ['#059669', '#D97706', '#4F46E5', '#DC2626'],
        xAxis: {
          // categories: this.months,
          categories: Object.keys(this.chart.year.checkin)
            .sort()
            .map(d => this.$month(d)),
          crosshair: true,
        },
        yAxis: {
          min: 0,
          title: {
            text: '',
          },
        },
        tooltip: {
          // headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          // pointFormat:
          //   '<tr><td style="color:{series.color};padding:3px;">{series.name}: </td>' +
          //   '<td style="text-align:right;padding:3px 3px 3px 10px;"><b>{point.y:.2f} mm</b></td></tr>',
          // footerFormat: '</table>',
          shared: true,
          shadow: false,
          useHTML: true,
          borderRadius: '5',
          borderColor: '#1F2937',
          style: { color: '#fff' },
          backgroundColor: '#1F2937',
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0,
          },
        },
        series: [
          {
            name: this.$t('Checkins'),
            data: this.sortValues(this.chart.year.checkin),
          },
          {
            name: this.$t('Checkouts'),
            data: this.sortValues(this.chart.year.checkout),
          },
          {
            name: this.$t('Transfers'),
            data: this.sortValues(this.chart.year.transfer),
          },
          {
            name: this.$t('Adjustments'),
            data: this.sortValues(this.chart.year.adjustment),
          },
        ],
      };
    },
  },
};
</script>
