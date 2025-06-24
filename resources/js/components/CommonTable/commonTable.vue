<template>
  <div>
    <div class="actions d-flex justify-content-between align-items-center" v-if="isActionHeader">
      <app-typography v-if="isTableTitle" :variant="headingVariant" fontWeight="500" :color="headingColor">
        {{ tableHeading }}
      </app-typography>


      <div class="mb-2" v-if="filterButtonArray.length>0">
        <div class="toggle-container" :style="{ width: containerWidth + 'px' }">
          <!-- The sliding toggle button -->
          <div
              class="toggle-button"
              :style="{ left: activeButton * buttonWidth + 'px' }"
          ></div>

          <!-- Labels for buttons -->
          <div class="role-labels">
      <span
          v-for="(button, index) in filterButtonArray"
          :key="index"
          :class="{ active: activeButton === index }"
          @click="handleToggle(index,button)"
      >
        {{ button.label }}
      </span>
          </div>
        </div>
      </div>

      <slot name="actions"></slot>
    </div>
    <div class="card table-fixed">
      <div class="table-responsive" :style="{ height: tableHeight }">
        <table class="table">
          <thead>
          <tr>
            <th v-if="isSelectable" class="p-3">
              <input
                  class="form-check-input "
                  type="checkbox"
                  :checked="allSelected"
                  @change="toggleSelectAll"
              />
            </th>
            <th
                :style="{ backgroundColor: currentFilters[column.field] ? activeFilterBgColor : headerBgColor, color: headerTextColor }"
                :class="{ 'active-filter': currentFilters[column.field] }"
                class="p-3 "
                v-for="(column, index) in header"
                :key="index"
            >
              <div v-if="column.filter">

                <el-dropdown
                    ref="dropdownRef"
                    trigger="click"

                >
                  <div class="d-flex align-items-center border-remove  gap-1 pointer"
                  >
                    <app-typography fontWeight="500" variant="body1" :color="headerTextColor">{{
                        column.label
                      }}
                    </app-typography>
                    <image-svg width="12px" height="12px"
                               name="filter-icon" :fill="headerTextColor"/>

                  </div>
                  <template #dropdown>
                    <div class="filter-dropdown-container">
                      <div class="search-bar">
                        <div class="search-icon">
                          <image-svg width="12px" height="12px" name="search"/>
                        </div>
                        <input
                            v-model="searchQuery[column.field]"
                            :placeholder="'Search ' + column.label"
                            class="custom-input"
                            type="text"
                        />
                      </div>
                      <hr class="divider m-0"/>
                      <div class="radio-group-scroll">
                        <el-radio-group class="radio-group" v-model="selectedFilters[column.field]">
                          <el-radio class="radio-option" v-for="option in getFilteredOptions(column.label)"
                                    :label="option" :key="option">
                            {{ option }}
                          </el-radio>
                        </el-radio-group>
                      </div>

                      <hr class="divider m-0"/>
                      <div class="filter-actions">
                        <el-button @click="clearFilter(column.field , index)" class="cancel-button">Cancel</el-button>
                        <el-button type="primary" @click="applyFilter(column.field ,  index)" class="apply-button">
                          Apply
                        </el-button>
                      </div>
                    </div>
                  </template>
                </el-dropdown>
              </div>
              <div v-else class="d-flex align-items-center gap-1 pointer">
                <app-typography fontWeight="500" variant="body1" :color="headerTextColor">{{
                    column.label
                  }}
                </app-typography>
              </div>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-if="paginatedData.length === 0">
            <td :colspan="header.length + (isSelectable ? 1 : 0)" class="text-center">
              <app-typography variant="body1" fontWeight="500" color="#76848D">
                No Data Found
              </app-typography>
            </td>
          </tr>
          <tr v-for="(row, rowIndex) in paginatedData" :key="rowIndex">
            <td v-if="isSelectable" class="p-3">


              <input class="form-check-input "
                     id="selectAllCheckbox"
                     type="checkbox"
                     :value="row.id"
                     v-model="selectedItems">
            </td>

            <td class="p-3" v-for="(column, colIndex) in header" :key="colIndex">
              <template v-if="column.field === 'payableAmount' && typeof row[column.field] === 'object'">
                <div class="progress-container">
                  <div
                      class="progress-bar"
                      :style="{ width: row[column.field].percentage + '%', backgroundColor: row[column.field].progressClass }">
                  </div>
                  <span class="amount-text">
                  {{ "$" + row[column.field].amount }}
                </span>
                </div>
              </template>

              <template v-if="column.field === 'contributors' && row[column.field].type === 'contributors'">
                <div class="contributors-container d-flex align-items-center">
                  <span class="contributor-count" v-if="row[column.field].count>0">{{ row[column.field].count }}</span>
                  <button v-if="is_contributor" class="add-contributor-btn" @click="addContributor(row)">
                  <span class="pb-1">+</span>
                  </button>
                </div>
              </template>

              <template v-if="typeof row[column.field] === 'object' && row[column.field].type === 'avatar'">
                <div class="d-flex align-items-center">
                  <image-svg width="30px" height="30px" :name="row[column.field].src" alt="avatar"/>
                  <app-typography :style="{ color: row[column.field].textColor }" variant="body1" class="ml-2">
                    {{ row[column.field].name }}
                  </app-typography>
                </div>
              </template>
              <template v-else-if="typeof row[column.field] === 'object'">
                <app-typography :color="row[column.field].color" variant="body1">
                  {{ row[column.field].text }}
                </app-typography>
              </template>
              <template v-else>
                <app-typography variant="body1">
                  {{ row[column.field] }}
                </app-typography>
              </template>
              <template v-if="column.field === 'action'">
                <slot name="action" :row="row" :rowIndex="rowIndex"></slot>
              </template>

            </td>
          </tr>


          </tbody>
        </table>
      </div>
    </div>

    <!-- Total Footer -->
    <div v-if="isTotalFooter" class="container">
      <div class="total-amount d-flex gap-5 justify-content-end border-bottom">
        <app-typography variant="h5" fontWeight="700" color="#4682BE">
          {{ totalText }}:
        </app-typography>
        <app-typography variant="h5" color="#C90C0C">
          {{ "$" + isPriceTotalFooter }}
        </app-typography>
      </div>
    </div>

    <div class="pagination-controls mt-3" v-if="paginationStatus">
      <el-pagination
          v-model:current-page="currentPage"
          :page-size="rowsPerPage"
          :total="totalRows"
          layout="prev, pager, next"
          background
          class="justify-content-center"
      ></el-pagination>
    </div>
  </div>
</template>


<script>
import {AppTypography, ReuseableButton, ImageSvg, Dropdown} from "@/components/index";
import {ElPagination, ElInput, ElRadio, ElRadioGroup, ElButton} from 'element-plus';
import {ArrowDownBold} from "@element-plus/icons-vue";

export default {
  components: {
    ArrowDownBold,
    ElPagination,
    ReuseableButton,
    AppTypography,
    ImageSvg,
    Dropdown,
    ElInput,
    ElRadio,
    ElRadioGroup,
    ElButton,
  },
  props: {
    isActionHeader: {
      type: Boolean,
      default: true
    },
    isTableTitle: {
      type: Boolean,
      default: true
    },
    tableHeading: {
      type: String
    },
    headingColor: {
      type: String,
      default: "#36454F"
    },
    headingVariant: {
      type: String,
      default: "h4"
    },
    filterIcon: {
      type: Boolean,
      default: true
    },
    data: {
      type: Array,
      required: true
    },
    header: {
      type: Array,
      required: true
    },
    isTotalFooter: {
      type: Boolean,
      default: false
    },
    is_contributor: {
      type: Boolean,
      default: false
    },
    totalText: {
      type: String,
      default: "Total Payable"
    },
    isPriceTotalFooter: {
      type: String,
      default: "500"
    },
    headerBgColor: {
      type: String,
      default: "#4682BE"
    },
    headerTextColor: {
      type: String,
      default: "#ffff"
    },
    rowsPerPage: {
      type: Number,
      default: 10
    },
    paginationStatus: {
      type: Boolean,
      default: true
    },
    tableHeight: {
      type: String,
      default: 'calc(100vh - 300px)'
    },
    activeFilterBgColor: {
      type: String,
      default: "lightgray"
    },
    isSelectable: {
      type: Boolean,
      default: false
    },
    noDataMessage: {
      type: String,
      default: "No data found.",
    },
    filterButtonArray: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      currentPage: 1,
      activeButton: 0,
      currentFilters: {},
      searchQuery: {},
      selectedFilters: {},
      selectedItems: [],
      allSelected: false
    };
  },

  watch: {
    selectedItems(newVal) {
      this.allSelected = newVal.length === this.paginatedData.length;
      this.$emit('selection-changed', this.selectedItems);
    }
  },

  computed: {
    totalRows() {
      let filteredData = this.data;

      // Apply all active filters to the data
      for (let columnName in this.currentFilters) {
        const filterValue = this.currentFilters[columnName];
        if (filterValue) {
          filteredData = filteredData.filter(item => item[columnName] === filterValue);
        }
      }

      return filteredData.length;  // Total rows based on filtered data
    },
    paginatedData() {
      let filteredData = this.data;

      for (let columnName in this.currentFilters) {
        const filterValue = this.currentFilters[columnName];

        if (filterValue) {
          filteredData = filteredData.filter(item => {
            return item[columnName] === filterValue;
          });
        }
      }

      if (this.paginationStatus) {

        const start = (this.currentPage - 1) * this.rowsPerPage;
        const end = start + this.rowsPerPage;
        return filteredData.slice(start, end);
      } else {
        return filteredData;
      }
    },
    containerWidth() {
      return this.filterButtonArray?.length * 90; // 100px for each button width
    },
    buttonWidth() {
      return 90; // Each button will take up 100px of space
    },
  },
  methods: {
    getFilteredOptions(columnLabel) {
      const columnField = this.header.find(col => col.label === columnLabel).field;

      const allOptions = [...new Set(this.data.map(item => item[columnField]))];

      return allOptions.filter(option => {
        const query = this.searchQuery[columnField]?.toLowerCase() || '';
        return option && option.toLowerCase().includes(query);
      });
    },
    handleFilterSelection(columnName, selectedOption) {
      this.selectedFilters[columnName] = selectedOption;
    },
    clearFilter(columnName, index) {

      delete this.selectedFilters[columnName];
      delete this.currentFilters[columnName];
      this.searchQuery[columnName] = '';


    },

    applyFilter(columnName, index) {


      if (this.selectedFilters[columnName]) {
        this.currentFilters[columnName] = this.selectedFilters[columnName];
      } else {
        delete this.currentFilters[columnName];
      }

      this.$emit('FILTERED_LIST', this.currentFilters);
    },
    addContributor(row) {
      this.$emit('contributor-click', row);
    },
    handleToggle(index, button) {
      console.log(index, JSON.stringify(button))
      this.activeButton = index;
      this.$emit('TOGGLE_BUTTON_FILTER', button)
    },
    toggleSelectAll(event) {
      this.allSelected = event.target.checked;
      if (this.allSelected) {
        // Add all row IDs to selectedItems
        this.selectedItems = this.paginatedData.map(row => row.id);
      } else {
        // Clear selectedItems
        this.selectedItems = [];
      }
    },
    toggleItemSelection() {
      // Check if all items are selected
      this.allSelected = this.selectedItems.length === this.paginatedData.length;
    }

  }
};
</script>


<style scoped>
.card {
  box-shadow: 0px 0px 4px 0px #00000040;
  border-radius: 10px;
}

.table-fixed {
  max-width: 100%;
  margin-bottom: 20px;
}

.table-responsive {
  display: block;
  width: 100%;
  height: calc(100vh - 300px);
  overflow-y: auto;
  border-radius: 10px;
}

thead th {
  position: sticky;
  top: 0;
  z-index: 10;
  background-color: #4682BE;
  color: #ffff;
  text-align: left;

}

thead th:first-child {
  border-top-left-radius: 10px;
}

thead th:last-child {
  border-top-right-radius: 10px;
}

.border-remove:focus-visible {
  border: none !important;
}

.action-btn {
  height: 34px !important;
  border: 1px solid #3A84C6;
  font-size: 16px !important;
}

.pagination-controls {
  display: flex;
  justify-content: start;
  margin-top: 20px;
}

.total-amount {
  padding: 15px 120px 10px 0px;
}

.is-selected {
  background-color: #f0f0f0;
  color: #333;
}

.filter-dropdown-container {
  border-radius: 10px;
  background-color: white;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
  width: 250px; /* Adjust size as needed */
}


.custom-search-input input {
  border-radius: 50px;
  padding-left: 10px;
  height: 40px;
  border: 1px solid #d9d9d9;
}

.radio-group-scroll {
  max-height: 200px;
  overflow-y: auto;
}

.radio-group {
  display: flex;
  flex-direction: column;
  align-items: start !important;
  width: 100%;
  padding: 10px 16px;
}

.radio-group-scroll::-webkit-scrollbar {
  height: 8px;
}

.radio-group-scroll::-webkit-scrollbar {
  width: 4px;
}

.radio-group-scroll::-webkit-scrollbar-track {
  background: #F2F2F2;
}

.radio-group-scroll::-webkit-scrollbar-thumb {
  border-radius: 5px;
  background: #cdcdcd;
}

.radio-option {
  padding: 8px 0;
  border-bottom: 1px solid #e0e0e0;
  width: 100%;
}

.radio-option:last-of-type {
  border-bottom: none;
}

.divider {
  border: none;
  border-top: 2px solid #e0e0e0;

}

.filter-actions {
  display: flex;
  justify-content: end;
  padding: 16px 8px;
  gap: 4px;

}

.cancel-button {
  color: #3A84C6;
  border: 1px solid #3A84C6;
  background-color: transparent;
  border-radius: 20px;
  padding: 4px 16px;
  width: 90px;
}

.apply-button {
  background-color: #3A84C6;
  color: white;
  border-radius: 20px;
  padding: 4px 16px;
  width: 90px;
}

.search-bar {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
  border-radius: 10px;
  padding: 16px;
}

.custom-input {
  padding-left: 25px;
  width: 100%;
  height: 40px;
  border-radius: 10px;
  font-size: 12px;
  box-sizing: border-box;
  box-shadow: 0px 0px 10px 0px #71717126;

}

.custom-input::placeholder {
  color: #3A84C6;
  opacity: 1;
}

.search-icon {
  position: absolute;
  left: 25px;
  top: 50%;
  transform: translateY(-50%);
}

.active-filter {
  font-weight: bold;
  /* add any other styles you'd like for the active filter */
}
.progress-container {
  position: relative;
  width: 105px;
  height: 30px;
  border: 1px solid #D8D8D8;
  border-radius: 5px;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  border-radius: -1px 0 0 5px;
}

.amount-text {
  position: absolute;
  top: 5px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 12px;
  font-weight: bold;
  color: #76848D;
}
.contributors-container {
  display: flex;
  align-items: center;
  gap: 8px;
}

.contributor-count, .add-contributor-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  font-size: 16px;
  font-weight: bold;
  color: #4682BE;
  border: 2px solid #4682BE;
  border-radius: 50%;
  line-height: 1;
}

.contributor-count {
  background-color: #fff;
}

.add-contributor-btn {
  background-color: #fff;
  cursor: pointer;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.add-contributor-btn:hover {
  background-color: #4682BE;
  color: #fff;
}

.add-contributor-btn:active {
  transform: scale(0.9);
}
td .form-check-input, th .form-check-input {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
  appearance: none; /* Remove default appearance */
  background-color: #f0f0f0;
  border: 2px solid #dcdcdc;
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.2s ease;
}
td .form-check-input:checked, th .form-check-input:checked {
  background-color: #007bff;
  border-color: #007bff;
}

.toggle-container {
  display: flex;
  align-items: center;
  border-radius: 40px;
  border: 1px solid #d1d1d1;
  position: relative;
  height: 45px;
  background-color: #f8f9fa;
  overflow: hidden;
}

.toggle-button {
  width: 90px; /* Set width to match the button size */
  height: 39px;
  background-color: #4682BE;
  border-radius: 18px;
  position: absolute;
  top: 2px;
  left: 2px;
  transition: left 0.3s ease;
}

.role-labels {
  width: 100%;
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  font-weight: bold;
  color: #4682BE;
  z-index: 1;
  position: relative;
}

.role-labels span {
  cursor: pointer;
  flex: 1;
  text-align: center;
  z-index: 2;
  transition: color 0.3s ease-in-out;
}

.role-labels span.active {
  color: #fff;
}
</style>


