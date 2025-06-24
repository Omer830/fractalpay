<template>
  <div class="card">
    <div class="header d-flex flex-wrap align-items-center justify-content-between border-bottom pb-3">
      <app-typography variant="h3">{{ cardHeader }}</app-typography>
      <div class="search">
        <input type="search" placeholder="Search" v-model="searchQuery" @input="filterContributors">
      </div>
      <div class="close pointer" @click="handleCancel">
        <image-svg width="31px" height="31px" name="cross-icon" />
      </div>
    </div>
    <div class="body pl-3">
      <div class="d-flex gap-2 my-3">
        <input class="form-check-input py-2" type="checkbox" id="selectAllCheckbox" v-model="selectAll"
               @change="toggleSelectAll">
        <label class="form-check-label" for="selectAllCheckbox">
          Select All Contributors
        </label>
      </div>
      <div class="details-wrapper d-flex flex-wrap align-items-center justify-content-between py-2 pe-2"
           v-for="(item, index) in filteredContributors" :key="index">
        <div class="user d-flex align-items-center gap-3">
          <input class="form-check-input" type="checkbox" v-model="item.selected">
          <image-svg width="36px" height="36px" :name="item.avatar" />
          <app-typography variant="body1" color="#76848D">{{ item.name }}</app-typography>
        </div>
        <app-typography variant="body1" color="#76848D">{{ item?.email }}</app-typography>
      </div>
    </div>
  </div>
</template>

<script>
import AppTypography from "../Typography/appTypography.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
  components: {
    AppTypography,
    ImageSvg
  },
  props: {
    cardHeader: {
      type: String,
      default: "Add Multiple Contributors"
    },
    contributorsArray: {
      type: Array,
      default: () => [

      ]
    }
  },
  data() {
    return {
      selectAll: false,
      searchQuery: "",
      filteredContributors: this.contributorsArray.map(contributor => ({...contributor}))
    };
  },
  methods: {
    handleCancel() {
      this.$emit('cancel');
    },
    toggleSelectAll() {
      this.filteredContributors.forEach(contributor => contributor.selected = this.selectAll);
    },
    filterContributors() {
      if (this.searchQuery.trim() === "") {
        this.filteredContributors = this.contributorsArray;
      } else {
        const query = this.searchQuery.toLowerCase();
        this.filteredContributors = this.contributorsArray.filter(contributor =>
            contributor.name.toLowerCase().includes(query) ||
            contributor.email.toLowerCase().includes(query)
        );
      }
      this.updateSelectAllStatus();
    },
    updateSelectAllStatus() {
      this.selectAll = this.filteredContributors.every(contributor => contributor.selected);
    },
    getSelectedContributors() {
      return this.filteredContributors.filter(contributor => contributor.selected);
    }

  },
  watch: {
    filteredContributors: {
      deep: true,
      handler() {
        this.updateSelectAllStatus();
      }
    }
  }
}
</script>

<style scoped>
.card {
  box-shadow: 0px 0px 4px 0px #00000026;
  padding: 64px 32px 24px 32px;
  border-radius: 10px;
}

.search {
  box-shadow: 0px 8px 16px 0px #00000014;
  box-shadow: 0px 0px 4px 0px #0000000A;
  border-radius: 12px;
  padding: 14px 16px;
}

.body {
  max-height: 400px;
  min-height: 400px;
  overflow-y: scroll;
}

/* Scroll bar stylings */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

/* Track */
::-webkit-scrollbar-track {
  background: transparent;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #D9D9D9;
  border-radius: 5px;
}

.body input {
  border-radius: 50%;
}
</style>
