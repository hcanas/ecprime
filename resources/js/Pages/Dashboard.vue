<script setup>
import {onBeforeMount} from "vue";
import {useFormatter} from "@/Composables/formatter.js";
import {LineChart} from "vue-chart-3";
import {Chart, registerables} from 'chart.js'
import {forEach, sumBy} from "lodash";
import {router} from "@inertiajs/vue3";
import PageHead from "@/Components/PageHead.vue";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import OnlyFilter from "@/Components/OnlyFilter.vue";

const props = defineProps({
    purchase_orders: {
        type: Object,
        required: true,
    },
    trending_products: {
        type: Object,
        required: true,
    },
});

Chart.register(...registerables);

const {formatCurrency, formatNumber} = useFormatter();

const chartData = {
    labels: getChartLabels(),
    datasets: [
        {
            label: 'Revenue From Delivered Purchase Orders',
            backgroundColor: '#13dc4d',
            borderColor: '#13dc4d',
            borderWidth: 2,
            data: getChartData(),
        }
    ],
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        tooltip: {
            callbacks: {
                label: function (context) {
                    if (context.parsed.y !== null) {
                        return formatCurrency(context.parsed.y);
                    }
                },
            },
        }
    },
};

const monthOptions = [
    {value: '1', label: 'January'},
    {value: '2', label: 'February'},
    {value: '3', label: 'March'},
    {value: '4', label: 'April'},
    {value: '5', label: 'May'},
    {value: '6', label: 'June'},
    {value: '7', label: 'July'},
    {value: '8', label: 'August'},
    {value: '9', label: 'September'},
    {value: '0', label: 'October'},
    {value: '11', label: 'November'},
    {value: '12', label: 'December'},
];

const yearOptions = [];

for (let i = 2024; i <= 2050; i++) {
    yearOptions.push({
        value: i.toString(),
        label: i.toString(),
    });
}

function getDaysInMonth(year, month) {
    return new Date(year, month, 0).getDate();
}

function getChartLabels() {
    if (route().params['month']) {
        return Array.from({length: getDaysInMonth(route().params['year'], route().params[['month']])}, (_, i) => i + 1);
    } else {
        return [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
    }
}

function getChartData() {
    const tempData = Array.from({length: getChartLabels().length}, (_, i) => 0.00)

    forEach(props.purchase_orders, purchase_order => {
        const index = (route().params['month'] ? purchase_order.day : purchase_order.month) - 1;
        tempData[index] = parseFloat(purchase_order.delivered_amount);
    });

    return tempData;
}

onBeforeMount(() => {
    if (!route().params['year']) {
        router.visit(route('dashboard', {
            year: new Date().toLocaleString('en-PH', {
                timeZone: 'Asia/Manila',
                year: 'numeric',
            })
        }));
    }
});
</script>

<template>
    <BaseLayout>
        <PageHead title="Dashboard">
            <div class="flex items-center space-x-3 px-6 md:px-0">
                <OnlyFilter :options="monthOptions"
                            field="month"
                            label="Month" />
                <OnlyFilter :options="yearOptions"
                            field="year"
                            label="Year"
                            :includeAll="false" />
            </div>
        </PageHead>

        <div class="mt-6 md:mt-12 flex flex-col space-y-6 md:space-y-12 px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-1 md:gap-y-0 md:gap-x-6">
                <div class="flex flex-col items-end rounded p-3 bg-cyan-500">
                    <h3 class="text-white">{{ formatNumber(sumBy(purchase_orders, 'pending_delivery_count')) }} Pending Delivery Orders</h3>
                    <p class="text-lg text-white font-medium">{{ formatCurrency(sumBy(purchase_orders, x => parseFloat(x.pending_delivery_amount))) }}</p>
                </div>
                <div class="flex flex-col items-end rounded p-3 bg-green-500">
                    <h3 class="text-white">{{ formatNumber(sumBy(purchase_orders, 'delivered_count')) }} Delivered Orders</h3>
                    <p class="text-lg text-white font-medium">{{ formatCurrency(sumBy(purchase_orders, x => parseFloat(x.delivered_amount))) }}</p>
                </div>
                <div class="flex flex-col items-end rounded p-3 bg-red-500">
                    <h3 class="text-white">{{ formatNumber(sumBy(purchase_orders, 'cancelled_count')) }} Delivered Orders</h3>
                    <p class="text-lg text-white font-medium">{{ formatCurrency(sumBy(purchase_orders, x => parseFloat(x.cancelled_amount))) }}</p>
                </div>
            </div>

            <LineChart :chartData="chartData" :options="chartOptions" />

            <div class="flex flex-col space-y-6">
                <div class="col-span-2 flex flex-col space-y-3">
                    <h3 class="font-medium">Trending Products</h3>
                    <h5 class="text-neutral-500 font-medium">Last 30 days</h5>
                    <div class="grid grid-cols-2 md:grid-cols-8 gap-6 justify-center">
                        <div v-for="product in trending_products"
                              class="hover:text-primary-500 border hover:border-primary-500 rounded-md shadow transition ease-in-out">
                            <div class="w-full p-1 rounded-t">
                                <img :src="product.image ? `/storage/images/${product.image}` : '/images/placeholder.png'"
                                     class="mx-auto rounded w-[100px] h-[100px]" />
                            </div>
                            <div class="flex flex-col items-center space-y-1 px-4 py-2">
                                <p :title="product.name"
                                   class="w-full text-center">{{ product.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>
