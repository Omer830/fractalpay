<template>
  <div class="contributor-item" v-for="(contributor, index) in contributors" :key="index">
    <!-- Show initials if there is no avatar URL -->
    <div v-if="!contributor.avatarUrl" class="avatar-initials" :style="{ backgroundColor: getColor(index) }">
      {{ getInitials(contributor.name) }}
    </div>
    <!-- Show avatar image if there is a URL -->
    <img v-else :src="contributor.avatarUrl" alt="Contributor Avatar" class="avatar rounded-circle" />
  </div>
</template>

<script>
export default {
  props: {
    contributors: {
      type: Array,
      required: true,
    },
  },
  methods: {
    getInitials(name) {
      if (!name) return '';
      const nameParts = name.split(' ');
      const initials = nameParts.map(part => part[0]).join('').toUpperCase();
      return initials.slice(0, 2); // Display up to two initials
    },
    getColor(index) {
      // Define a set of colors to rotate through
      const colors = ['#FF8A80', '#FFD180', '#CFD8DC', '#B2DFDB', '#FFAB91', '#FFCC80'];
      return colors[index % colors.length];
    },
  },
};
</script>

<style scoped>
.contributor-item {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: -16px;
  border: 5px solid white;
  border-radius: 50%;
  cursor: pointer;
  width: 60px;
  height: 55px;
}

.avatar {
  width: 60px;
  height: 55px;
  border-radius: 50%;
  object-fit: cover;
}

.avatar-initials {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  font-weight: bold;
  color: #ffffff;
  background-color: #ccc;
}
</style>
