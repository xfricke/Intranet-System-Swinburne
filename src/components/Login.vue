<template>


  <div class="container mt-5">
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <input v-model="email" type="email" class="form-control mb-2" placeholder="Email" />
        <input v-model="password" type="password" class="form-control mb-3" placeholder="Password" />
        <div class="text-danger" v-if="error">{{ error }}</div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
  </div>
  
  
</template>
    
<script>
  export default {
    name: 'LoginPage',
      data() {
        return {
          email: '',
          password: '', // initialise data
          error: ''
        };
      },
    
    methods: {
      async handleLogin() {
      try {
        const response = await fetch("/cos30043/s102674782/Assignment3/users.json"); // load the user data
        if (!response.ok) {
          throw new Error("Failed to load users");
        }

        const users = await response.json(); // parse the json file

        const matchedUser = users.find(
          user =>
            user.email === this.email.trim() &&
            user.password === this.password.trim() // access users, set the email and password in this instance
      );


        if (matchedUser) {
          localStorage.setItem("currentUser", JSON.stringify(matchedUser)); // save user object
          this.error = ""; //make sure no error display occurs
          this.$router.push("/social"); // route to social page once successful login
        } else {
          this.error = "Invalid credentials";
        }
      } catch (err) {
        console.error("Login error:", err);
        this.error = "Something went wrong. Please try again.";
      }
    }
  }
  };
</script>
    
    