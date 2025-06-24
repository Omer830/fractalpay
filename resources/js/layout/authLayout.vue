<template>
  <div class="auth-layout">
    <div :class="['sidebar', { collapsed: collapsed }]">
      <div class="icon-brand">
        <div class="hide-responsiveness">
          <!-- Your logo image or SVG -->
          <image-svg v-if="!collapsed" width="138px" height="36px" name="logo" />
        </div>
      </div>

      <div class="menu">
        <!-- Menu items with images/icons -->
        <div>
          <div @click="toggleCollapse"
               :class="['pointer my-2 menu-item hide-responsiveness', { 'center-aligned': collapsed }]">
            <image-svg v-if="collapsed" width="14px" height="14px" name="collapse" />
            <image-svg v-if="!collapsed" width="14px" height="14px" name="uncollapse" />
          </div>
          <div :class="['menu-item', { 'center-aligned': collapsed , 'active': activeRoute === '/dashboard' }]" @click="navigateTo('dashboard')">
              <image-svg width="15px" height="16px" name="dashboard" :fill="activeRoute === '/dashboard' ? '#FFFFFF' : '#4682BE'" />
            &nbsp;
            <span v-if="!collapsed" class="menu-title hide-responsiveness">Dashboard</span>
          </div>
          <div :class="['menu-item', { 'center-aligned': collapsed , 'active': activeRoute === '/expense-tracker' }]" @click="navigateTo('expense-tracker')">
            <image-svg width="15px" height="16px" name="expense" :fill="activeRoute === '/expense-tracker' ? '#FFFFFF' : '#4682BE'"  />
            &nbsp;
            <span v-if="!collapsed" class="menu-title hide-responsiveness">Expense Tracker</span>
          </div>

          <div :class="['menu-item', { 'center-aligned': collapsed , 'active': activeRoute === '/expense-tracker' && this.$store.getters.getExpenseType === 'Share with me' }]" @click="navigateTo('expense-tracker' , 'Share with me')">
            <image-svg
                width="15px"
                height="16px"
                name="expense"
                :fill="(activeRoute === '/expense-tracker' &&
                this.$store.getters.getExpenseType === 'Share with me' ) ? '#FFFFFF' : '#4682BE'"
            />
            &nbsp;
            <span v-if="!collapsed" class="menu-title hide-responsiveness">Share with me</span>
          </div>

          <div :class="['menu-item', { 'center-aligned': collapsed , 'active': activeRoute === '/investment-dashboard' }]" @click="navigateTo('investment-dashboard')">
            <image-svg width="15px" height="16px" name="invest" :fill="activeRoute === '/investment-dashboard' ? '#FFFFFF' : '#4682BE'"  />
            &nbsp;
            <span v-if="!collapsed" class="menu-title hide-responsiveness">Investment</span>
          </div>
          <div :class="['menu-item', { 'center-aligned': collapsed , 'active': activeRoute === '/transactions-history' }]" @click="navigateTo('transactions-history')">
            <image-svg width="15px" height="16px" name="history" :fill="activeRoute === '/transactions-history' ? '#FFFFFF' : '#4682BE'"  />
            &nbsp;
            <span v-if="!collapsed" class="menu-title hide-responsiveness">Transaction History</span>
          </div>
          <div :class="['menu-item', { 'center-aligned': collapsed , 'active': activeRoute === '/contribution-tree' }]" @click="navigateTo('contribution-tree')">
            <image-svg width="15px" height="16px" name="contribution" :fill="activeRoute === '/contribution-tree' ? '#FFFFFF' : '#4682BE'"  />
            &nbsp;
            <span v-if="!collapsed" class="menu-title hide-responsiveness">Contribution Tree</span>
          </div>
<!--          <div :class="['menu-item', { 'center-aligned': collapsed }]" @click="navigateTo('friend-and-family')">-->
<!--            <image-svg width="15px" height="16px" name="Friends&Family-icon" fill="#4682BE" />-->
<!--            &nbsp;-->
<!--            <span v-if="!collapsed" class="menu-title hide-responsiveness">Friends & Family</span>-->
<!--          </div>-->
          <div :class="['menu-item', { 'center-aligned': collapsed , 'active': activeRoute === '/edit-profile' }]" @click="navigateTo('edit-profile')">
            <image-svg width="15px"  height="16px" name="setting" :fill="activeRoute === '/edit-profile' ? '#FFFFFF' : '#4682BE'" />
            &nbsp;
            <span v-if="!collapsed" class="menu-title hide-responsiveness">Profile Settings</span>
          </div>
        </div>
        <reuseable-button @click="logout" class="logout-button" outline width="100" startIcon="logout" :imageHeight="19"
                          :imageWidth="19" btnHeight="48px" textColor="#4682BE" fontSize="14px" round="sm">
          <span v-if="!collapsed" class="hide-responsiveness">Logout</span>
        </reuseable-button>
      </div>
    </div>

    <div class="main-content">
      <div class="nav">
<!--        <div class="search-bar d-flex">-->
<!--          <image-svg class="ml-3" :width="16" :height="16" name="search" /> &nbsp;-->
<!--          <input type="text" class="inner-input" placeholder="Search">-->
<!--        </div>-->
        <div></div>

        <div class="right-section gap-3">
          <div class="notification-container">
            <div @click="toggleNotifications">
            <image-svg
                :width="25"
                :height="25"
                name="notification"
                class="notification-icon"
            />
            </div>
            <!-- Notification Badge -->
            <span v-if="notifications.length" class="notification-badge">
              {{ notifications.length }}
            </span>

            <!-- Notification Panel -->
            <div v-if="showNotifications" class="notification-panel">
              <h4>Notifications</h4>
              <ul v-if="notifications.length">
                <li v-for="(notification, index) in notifications" :key="index">
                  {{ notification.message }}
                </li>
              </ul>

              <p v-else class="text-center">No notifications</p>
            </div>
          </div>



          <img :src="profileImg" alt="Profile Picture" class="img-nav" />

          <!--          <dropdown tag="li" title="Usama Saeed" :title-classes="['custom-class-1', 'custom-class-2']"-->
          <!--            @change="handleDropDownChange">-->
          <!--            <li>Profile</li>-->
          <!--            <li>Logout</li>-->
          <!--          </dropdown>-->
        </div>
      </div>
      <div class="inner-page">
        <slot></slot>
      </div>
    </div>
  </div>
</template>


<script>
import { ReuseableButton, ImageSvg,  } from "../components/index.js";
import PROFILE_AVATAR from "../assets/images/owner-profile.png";
import Swal from "sweetalert2";
import axios from 'axios';
import {usePage} from "@inertiajs/vue3";
const userData = usePage();

export default {
  components: {
    ImageSvg,
    ReuseableButton,

  },
  data() {
    return {
      collapsed: false,
      activeRoute: this.$page.url,
      notifications: [],
      showNotifications: false,
      user:userData.props.auth.user,
    }
  },
  props: {
    avatar: {
      type: String,
      default: PROFILE_AVATAR,
    },

  },
  mounted() {
    console.log('Here is the user ID:');
    console.log(this.user.id);
    
    this.$watch('user', function (newUser) {
    if (newUser?.id) {
      Echo.private(`notification.${newUser.id}`)
        .listen('.MessageSent', (e) => {
          this.$toast(e.message, 'success');
          console.log('📢 Private message received:', e.message);
        });
    }
  }, { immediate: true });

    this.getNotification()
  },

  computed: {
    profileImg() {
      return   this.$store.getters.getProfileIMG || this.avatar;
    }
  },

  methods: {
    handleMenuItemSelected(item) {
      console.log('Selected item:', item);
    },
    // toggleNotifications() {
    //   this.showNotifications = !this.showNotifications;
    // },
    async toggleNotifications() {
      this.showNotifications = !this.showNotifications;

      if (this.showNotifications) {
        try {
          const res = await axios.get('/getUserNotification');
          this.notifications = res?.data?.notifications || [];
          console.log(JSON.stringify(this.notifications));
        } catch (err) {
          console.error('Failed to fetch notifications:', err);
        }
      }
    },
    async getNotification() {
      try {
        const res = await axios.get('/getUserNotification');
        this.notifications = res?.data?.notifications || [];
   
      } catch (err) {
        console.error('Failed to fetch notifications:', err);
      }
    },

    toggleCollapse() {
      this.collapsed = !this.collapsed; // Toggle the collapsed state
    },

    comingSoon() {
      Swal.fire({
        title: "Coming Soon!",
        text: "This feature is not available yet.",
        icon: "info",
        confirmButtonText: "OK",
        confirmButtonColor: '#4682BE',
        iconColor: '#4682BE',
      });
    },

    navigateTo(route,tab) {
      this.activeRoute = route;
      // Use Vue Router to navigate to the specified route
      this.$inertia.visit( route );
      if(tab === 'Share with me'){
        this.$store.commit('SET_EXPENSE_TAB', tab);
      }else {
        this.$store.commit('SET_EXPENSE_TAB', null);
      }
    },


    handleDropDownChange(isOpen) {
      console.log('Dropdown is now:', isOpen ? 'open' : 'closed');
      // Handle dropdown change event if needed
    },

    logout() {

      Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to logout this account?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4682BE',
        cancelButtonColor: '#bcbcbc',
        confirmButtonText: 'Yes, Logout!'
      }).then((result) => {
        if (result.isConfirmed) {
          localStorage.clear();

          this.$toast('Successfully logged out', 'success');

          this.$inertia.visit('/login');
          // this.$inertia.post(`/logout`, {
          //   preserveScroll: true,
          //   preserveState: true,
          //   onSuccess: (response) => {
          //     this.$toast('Successfully logged out', 'success');
          //     this.$inertia.visit('/login')
          //   },
          //   onError: (errors) => {
          //     console.error('Error deleting document:', errors);
          //     this.$toast('Failed to logout document', 'error');
          //   }
          // });
        }
      });
    },
  }
};
</script>

<style scoped>
.auth-layout {
  display: flex;
  height: 100vh;
}

.main-content {
  flex: 1;
  background-color: #F9FAFF;
  overflow-y: auto;
  /* Allow vertical scrolling */
}

.sidebar {
  width: 250px;
  border-right: 1px solid #EEEEEE;
  height: 100vh;
  transition: width 0.3s;
}

.sidebar.collapsed {
  width: 80px;
}

.icon-brand {
  width: 100%;
  justify-content: center;
  margin-top: 20px;
  display: flex;
  height: 36px;
}

.menu {
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: calc(100vh - 56px);
}
.menu-item.active {
  background-color: #4682BE; /* Blue background */
  color: white; /* White text */
  border-radius: 6px; /* Optional for better UI */
  padding: 10px;
}

.menu-item.active .menu-title {
  color: white;
}

.menu-item {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  padding-left: 10px;
  cursor: pointer;
  transition: justify-content 0.3s;
}

.menu-item.center-aligned {
  justify-content: center;
}

.menu-title {
  color: #76848D;
  font-weight: 400;
  font-size: 14px;
}

.nav {
  height: 65px;
  align-items: center;
  display: flex;
  justify-content: space-between;
  width: 100%;
  padding: 0 10px;
  border-bottom: 1px solid #D8E6F4;
}

.search-bar {
  width: 215px !important;
  align-items: center;
  display: flex;
  box-shadow: 0px 0px 10px 0px #71717126;
  height: 40px;
  padding: 0 14px;
  border-radius: 10px;
}
.inner-input::placeholder {
  color: #4682be;
}
.inner-input {
  background: transparent;
  border: none;
  width: 80%;
  margin-left: 10px;
  font-size: 14px;
  color: #3A84C6;

}

.right-section {
  display: flex;
  align-items: center;
}

.img-nav {
  height: 30px;
  width: 30px;
  object-fit: cover;
  border-radius: 50%;
}

.inner-page {
  height: calc(100vh - 65px);
  overflow-y: auto;
}

.hide-responsiveness {
  display: flex
}


/* Media Queries for Responsiveness */
@media (max-width: 768px) {

  .menu {
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    padding: 10px;
    height: auto;
  }

  .menu-item {
    margin: 10px;
  }

  .nav {
    flex-direction: column;
    padding: 10px;
  }

  .inner-page {
    height: auto;
  }

  .hide-responsiveness {
    display: none
  }

  .sidebar {
    width: 80px;
  }


}
/* Notification Panel Styles */
.notification-container {
  position: relative;
  cursor: pointer;
}

.notification-icon {
  cursor: pointer;
}

.notification-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: red;
  color: white;
  font-size: 12px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.notification-panel {
  position: absolute;
  top: 35px;
  right: 0;
  background: white;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  border-radius: 6px;
  width: 250px;
  padding: 10px;
  z-index: 100;
}

.notification-panel h4 {
  margin: 0;
  font-size: 16px;
}

.notification-panel ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.notification-panel li {
  padding: 8px;
  border-bottom: 1px solid #eee;
}

.notification-panel li:last-child {
  border-bottom: none;
}
</style>
