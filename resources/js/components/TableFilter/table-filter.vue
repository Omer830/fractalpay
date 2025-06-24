<template>
  <div>
    <el-dropdown trigger="click">
      <span class="el-dropdown-link">
        <app-typography fontWeight="500" variant="body1" color="#36454F">{{ column.label }}</app-typography>
        <ArrowDownBold />
      </span>
      <template #dropdown>
        <div class="filter-container">
          <div class="search-bar">
            <el-input v-model="searchQuery" placeholder="Search Amounts" prefix-icon="el-icon-search" />
          </div>
          <div class="filter-options">
            <el-radio-group v-model="selectedOption">
              <el-radio v-for="option in filteredOptions" :label="option" :key="option">{{ option }}</el-radio>
            </el-radio-group>
          </div>
          <div class="filter-actions">
            <el-button @click="clearFilter">Cancel</el-button>
            <el-button type="primary" @click="applyFilter">Apply</el-button>
          </div>
        </div>
      </template>
    </el-dropdown>
  </div>
</template>
<script>
import { ref, computed } from 'vue';
import { ElInput, ElRadio, ElRadioGroup, ElButton, ElDropdown } from 'element-plus';
import { ArrowDownBold } from '@element-plus/icons-vue';

export default {
  components: {
    ElInput,
    ElRadio,
    ElRadioGroup,
    ElButton,
    ElDropdown,
    ArrowDownBold,
  },
  props: {
    column: Object,
  },
  setup() {
    const searchQuery = ref('');
    const selectedOption = ref(null);
    const options = ref(['Payable amount', 'Less than $500', 'Greater than $', 'Unpaid bills']);

    const filteredOptions = computed(() => {
      if (!searchQuery.value) {
        return options.value;
      }
      return options.value.filter((option) => option.toLowerCase().includes(searchQuery.value.toLowerCase()));
    });

    const clearFilter = () => {
      selectedOption.value = null;
      searchQuery.value = '';
    };

    const applyFilter = () => {
      console.log('Filter applied:', selectedOption.value);
      // Handle filter logic here
    };

    return {
      searchQuery,
      selectedOption,
      filteredOptions,
      clearFilter,
      applyFilter,
    };
  },
};
</script>
<style scoped>
.filter-container {
  padding: 16px;
  border-radius: 8px;
  background-color: white;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
}

.search-bar {
  margin-bottom: 12px;
}

.filter-options {
  max-height: 200px;
  overflow-y: auto;
  padding-bottom: 12px;
}

.filter-actions {
  display: flex;
  justify-content: space-between;
}

.el-radio-group {
  display: flex;
  flex-direction: column;
}
</style>
