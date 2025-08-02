<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4 shadow-sm">
      <div class="container-fluid">
        <router-link to="/home" class="navbar-brand fw-bold">Intranet</router-link> <!--company name is home button-->
        <div class="d-flex align-items-center">
          <router-link class="nav-link text-white me-3" to="/social">Social</router-link>
          <router-link class="nav-link text-white me-3" to="/about">About</router-link>
          <router-link class="nav-link text-white me-3" to="/announcements">Announcements</router-link>

          <router-link v-if="!isLoggedIn" class="nav-link text-white me-3" to="/signup">Signup</router-link>
          <router-link v-if="!isLoggedIn" class="nav-link text-white" to="/login">Login</router-link> <!--if user isnt logged in, display login button-->
          <button v-if="isLoggedIn" class="btn btn-danger btn-sm ms-2" @click="logout">Logout</button><!--if user is logged in, show the logout button-->
        </div>
      </div>
    </nav>

    <router-view />
  </div>
</template>

<script>
  export default {
    name: 'App',
    data() {
      return {
        isLoggedIn: false // assume we arent logged in on first visit
      };
    },
    mounted() {
      this.syncLoginState();
    },
    methods: {
      syncLoginState() {
        const user = JSON.parse(localStorage.getItem("currentUser"));
        this.isLoggedIn = !!user; // make sure we store currentUser locally. if we refresh or change page make sure the 'user' value is synced and still the same
      },
      logout() {//call this when logout button is pressed in nav
        localStorage.removeItem("currentUser"); //delete value stored in currentUser
        this.isLoggedIn = false; // we arent logged in anymore
        this.$router.push("/login");
      }
    },
    watch: {
      // re-check login if user navigates 
      $route() {
        this.syncLoginState();
      }
    }
  };
</script>

<style>
.nav-link:hover {
  color: #e0e0e0 !important;
  text-decoration: underline;
}

.navbar-brand {
  font-size: 1.4rem;
  letter-spacing: 0.5px;
}

.navbar-brand.router-link-exact-active {
  color: white;
  text-decoration: none;
}
</style>
