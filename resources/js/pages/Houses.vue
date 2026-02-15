<script setup lang="ts">
import { Search } from '@element-plus/icons-vue'
import { useDebounceFn } from '@vueuse/core';
import axios from 'axios';
import { ref, reactive } from 'vue';

const loading = ref(false);
const error = ref('');
const houses = ref([]);
const total = ref(0);
const currentPage = ref(1);

const filters = reactive({
    name: '',
    bedrooms: null as number | null,
    bathrooms: null as number | null,
    storeys: null as number | null,
    garages: null as number | null,
    price_min: null as number | null,
    price_max: null as number | null,
});

async function search(page = 1) {
    loading.value = true;
    currentPage.value = page;

    try {
        error.value = '';
        const params: Record<string, any> = { page };

        for (const [key, value] of Object.entries(filters)) {
            if (value !== null && value !== '') {
                params[key] = value;
            }
        }

        const response = await axios.get('/api/houses', { params });
        houses.value = response.data.data;
        total.value = response.data.total;
    } catch (e) {
        houses.value = [];
        total.value = 0;

        if (axios.isAxiosError(e) && e.response?.status === 422) {
            const messages = Object.values(e.response.data.errors).flat();
            error.value = messages.join(', ');
        } else {
            error.value = 'Failed to load data. Please try again.';
        }
    } finally {
        loading.value = false;
    }
}

function resetFilters() {
    filters.name = '';
    filters.bedrooms = null;
    filters.bathrooms = null;
    filters.storeys = null;
    filters.garages = null;
    filters.price_min = null;
    filters.price_max = null;
    search();
}

const debouncedSearch = useDebounceFn(() => search(1), 300);

search();
</script>

<template>
    <el-container>
        <el-header class="flex justify-center items-center">
            <h1 class="font-bold">Test tusk - House Search</h1>
        </el-header>
			<el-container>
			<el-aside width="260px" style="padding: 20px;">
				<el-form label-position="top" @submit.prevent="search(1)">
					<el-form-item label="Bedrooms">
						<el-input-number v-model="filters.bedrooms" :min="1" :max="10" controls-position="right" style="width: 100%;" />
					</el-form-item>
					<el-form-item label="Bathrooms">
						<el-input-number v-model="filters.bathrooms" :min="1" :max="10" controls-position="right" style="width: 100%;" />
					</el-form-item>
					<el-form-item label="Storeys">
						<el-input-number v-model="filters.storeys" :min="1" :max="5" controls-position="right" style="width: 100%;" />
					</el-form-item>
					<el-form-item label="Garages">
						<el-input-number v-model="filters.garages" :min="0" :max="5" controls-position="right" style="width: 100%;" />
					</el-form-item>
					<el-form-item label="Price Min ($)">
						<el-input-number v-model="filters.price_min" :min="0" :step="10000" controls-position="right" style="width: 100%;" />
					</el-form-item>
					<el-form-item label="Price Max ($)">
						<el-input-number v-model="filters.price_max" :min="0" :step="10000" controls-position="right" style="width: 100%;" />
					</el-form-item>
					<div class="flex flex-col">
						<div>
							<el-button type="primary" @click="search(1)" style="width: 100%;">Search</el-button>
						</div>
						<div>
							<el-button @click="resetFilters" style="width: 100%;">Reset</el-button>
						</div>
					</div>
				</el-form>
			</el-aside>
			<el-main>

				<el-form :inline="true" @submit.prevent="search(1)" class="w-full">
					<el-input v-model="filters.name" placeholder="Search by name" clearable @input="debouncedSearch" >
						<template #prefix>
							<el-icon size="small" ><Search /></el-icon>
						</template>
					</el-input>
				</el-form>

				<el-table :data="houses" v-loading="loading" stripe>
					<el-table-column prop="name" label="Name" />
					<el-table-column prop="price" label="Price" />
					<el-table-column prop="bedrooms" label="Bedrooms" />
					<el-table-column prop="bathrooms" label="Bathrooms" />
					<el-table-column prop="storeys" label="Storeys" />
					<el-table-column prop="garages" label="Garages" />
				</el-table>

				<el-alert v-if="error" type="error" :title="error" show-icon :closable="false" style="margin-top: 20px;" />
				<el-empty v-else-if="!loading && houses.length === 0" description="No results found" />

				<el-pagination
					v-if="total > 10"
					layout="prev, pager, next"
					:total="total"
					:current-page="currentPage"
					@current-change="search"
					style="margin-top: 20px; justify-content: center;"
				/>
			</el-main>
		</el-container>
    </el-container>
</template>

<style scoped>
.el-form-item {
	margin-bottom: 6px;
}
</style>