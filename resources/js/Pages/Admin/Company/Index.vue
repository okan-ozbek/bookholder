<template>
    <AdminContainer form-title="Company settings">
        <Link :href="route('companies.find')" method="get" class="link-button"> Create Company </Link>
        <TableContainer>
            <template #thead>
                <tr>
                    <th> Name </th>
                    <th> Location </th>
                    <th> Delete </th>
                </tr>
            </template>
            <template #tbody>
                <tr v-for="company in companies">
                    <td> {{ company.name }} </td>
                    <td> {{ company.street_address }}, {{ company.city }} </td>
                    <td class="table-item table-item-link"> <Link :href="route('companies.destroy', company.id)" method="delete"> Delete </Link> </td>
                </tr>
            </template>
        </TableContainer>
    </AdminContainer>
</template>
<script setup lang="ts">
import {defineProps} from "vue";
import { Link } from '@inertiajs/vue3'
import TableContainer from "../../Partials/Tables/TableContainer.vue";
import AdminContainer from "../Partials/AdminContainer.vue";

interface Props {
    companies: object,
}

const props = defineProps<Props>();
</script>
<style scoped>
.header-title {
    @apply font-bold text-2xl uppercase mb-8
}

.link-button {
    @apply inline-block bg-black text-white py-3 px-5 rounded-lg hover:bg-gray-800 hover:text-slate-200 mb-4
}

.table {
    @apply border-collapse w-full
}

.table-header {
    @apply bg-slate-100
}

.table-header-item {
    @apply text-left py-[15px]
}

.table-item-first {
    @apply pl-[15px]
}

.table-item {
    @apply py-[15px]
}

.table-item-link {
    @apply text-blue-600 hover:text-blue-900 select-none
}
</style>
