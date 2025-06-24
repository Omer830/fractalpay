<template>
  <auth-layout>
    <div class="container-fluid mb-4">
      <div class="row gy-4">
        <div class="col-lg-12">
          <title-action-bar
              textWeight="600"
              textVariant="text-38"
              :title="header.title"
              :isSubTitle="false"
              :isActionButtons="true"
              :isUserName="false"
              :isDivider="true"
              :isSecondaryBtn="true"
              :primaryButtonText="header.primaryButtonText"
              :secondaryButtonText="header.secondaryButtonText"
              @tertiary-click="handleFriendsAndFamily"
              :isTertiaryBtn="true"
              tertiaryButtonText="Friends & Family"
              :isPrimaryBtn="true"
              @secondary-click="handleViewDetail"
              @primary-click="handleInvite"
              btnRound="md"
          />
        </div>

        <div class="col-lg-12">
          <div class="text-center main-tree">
            <div class="tree">
              <img src="@/assets/images/tree.png" alt="tree_img" class="img-fluid m-auto" />
            </div>

            <div class="d-flex justify-content-center">
              <div class="you">You</div>
            </div>

            <div class="leaves-wrapper d-flex flex-wrap">
              <div
                  v-for="(leaf, index) in leaves"
                  :key="leaf.name"
                  class="leaves"
                  :class="'l' + (index + 1)"
              >
                {{ leaf.name }}
              </div>
            </div>

            <div class="roots d-flex">
              <div
                  v-for="(root, index) in roots"
                  :key="root.name"
                  class="root-badge"
                  :class="'r' + (index + 1)"
              >
                {{ root.name }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </auth-layout>
</template>

<script>
import { TitleActionBar } from "@/components/index";
import AuthLayout from "@/layout/authLayout.vue";

export default {
  components: {
    AuthLayout,
    TitleActionBar
  },
  data() {
    return {
      header: {
        title: "Contribution Tree",
        primaryButtonText: "Invite",
        secondaryButtonText: "View Details",
        startIcon: 'plus-icon'
      },
      leaves: [
        {name: 'Mav'},
        {name: 'Anw'},
        {name: 'Muz'},
        {name: 'Are'},
        {name: 'Usa'},
        {name: 'Shk'},
        {name: 'Fat'}
      ],
      roots: [
        {name: 'Ani'},
        {name: 'Abr'},
        {name: 'She'},
        {name: 'sa'},
        {name: 'asd'},
      ]
    };
  },
  methods: {
    handleInvite() {
      this.$inertia.visit('/contribution-invitation');
    },
    handleViewDetail() {
      this.$inertia.visit('/contribution-detail');
    },
    handleFriendsAndFamily() {
      this.$inertia.visit('/friend-and-family');
    }
  }
};
</script>

<style scoped>
.main-wrapper {
  padding: 20px 25px;
}

.main-tree {
  position: relative;
}

.root-badge {
  background-color: #903F3F;
  border: 5px solid #ffffff;
  justify-content: center;
  border-radius: 50px;
  align-items: center;
  height: 70px;
  width: 70px;
  color: #ffffff;
  display: flex;
}

.leaves {
  background-color: #378D3B;
  border: 5px solid #ffffff;
  justify-content: center;
  border-radius: 50px;
  align-items: center;
  height: 70px;
  width: 70px;
  color: #ffffff;
  display: flex;
}

.you {
  background: linear-gradient(180deg, #FF4848 0%, #72FB67 100%);
  border: 5px solid #ffffff;
  justify-content: center;
  border-radius: 50px;
  align-items: center;
  position: absolute;
  color: #ffffff;
  font-size: 32px;
  height: 90px;
  width: 90px;
  display: flex;
  right: auto;
  top: 55%;
  left: auto;
}

.roots {
  max-width: 500px;
  margin: auto;
}

.leaves-wrapper {
  max-width: 500px;
  margin: auto;
}

/* Positioning Classes */
.r1 {
  position: absolute;
  bottom: 10%;
  left: 24%;
}

.r2 {
  position: absolute;
  bottom: 2%;
  left: 47%;
}

.r3 {
  position: absolute;
  bottom: 7%;
  right: 23%;
}

.l1 {
  position: absolute;
  left: 29%;
  top: 25%;
}

.l2 {
  position: absolute;
  left: 50%;
  top: 23%;
}

.l3 {
  position: absolute;
  left: 65%;
  top: 16%;
}

.l4 {
  position: absolute;
  right: 23%;
  top: 32%;
}

.l5 {
  position: absolute;
  left: 28%;
  top: 40%;
}

.l6 {
  position: absolute;
  left: 37%;
  top: 43%;
}

.l7 {
  position: absolute;
  bottom: 36%;
  right: 27%;
}

@media screen and (max-width: 800px) {
  .leaves,
  .root-badge {
    height: 35px;
    width: 35px;
    font-size: 12px;
    border: 2px solid #ffffff;
  }

  .you {
    height: 50px;
    width: 50px;
    font-size: 12px;
    border: 2px solid #ffffff;
  }
}
</style>
