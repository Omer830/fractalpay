<template>
  <table class="table">
    <thead>
    <tr>
      <th v-for="column in columns" :key="column.field">
        {{ column.title }}
      </th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="row in data" :key="row.id">
      <td v-for="column in columns" :key="column.field">
        {{ row[column.field] }}
      </td>
    </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  name: 'TableComponent',
  props: {
    columns: Array, // expects an array of objects defining each column
    data: Array // expects an array of data objects
  },
  data() {
    return {
      sortKey: '',
      sortOrders: {}
    };
  },
  created() {
    this.columns.forEach(key => {
      this.sortOrders[key.field] = 1;
    });
  },
  methods: {
    sortBy(key) {
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
    }
  }
}
</script>

<style scoped>
.table {
  width: 100%;
  border-collapse: collapse;
}
.table th, .table td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: left;
}
</style>
