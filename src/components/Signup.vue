<template>
  <div class="container mt-5" style="max-width: 450px;">

    <form class="needs-validation" @submit.prevent="handleSubmit" novalidate>
      
      <h3 class="mb-3">Personal Info</h3>
        <div class="row">
          <div class="col-12 col-md-6 mb-3">
              <label>First Name: </label>
              <input v-model="fname" name="fname" type="text" required/>
              <div class="text-danger" v-if="fnameError">{{ fnameError }}</div>
          </div>

          <div class="col-12 col-md-6 mb-3">
              <label>Last Name: </label>
              <input v-model="lname" name="lname" type="text" required/>
              <div class="text-danger" v-if="lnameError">{{ lnameError }}</div>
          </div>

          <div class="col-12 col-md-6 mb-3">
              <label>Confrim Password: </label>
              <input v-model="confirmPassword" name="confirmPassword" type="text" required/>
              <div class="text-danger" v-if="confirmPasswordError">{{ confirmPasswordError }}</div>
          </div>
          
          <div class="col-12 col-md-6 mb-3">
              <label>Password: </label>
              <input v-model="password" name="password" type="text" required/>
              <div class="text-danger" v-if="passwordError">{{ passwordError }}</div>
          </div>
          <div class="col-12 col-md-6 mb-3">
                <label>Date of Birth: </label>
                <input v-model="birthDate" name="birthDate" type="date" required/>
            <div class="text-danger" v-if="birthDateError">{{ birthDateError }}</div>

        </div>
    </div>

      <br> <!-- Divider -->
      <hr class="my-5" />
      <h3 class="mb-3">Contact Info</h3>

      <div class="row">

      <div class="col-12 col-md-6 mb-3">
      <label>Email:</label>
      <input v-model="email" name="email" type="email" />
      <div class="text-danger" v-if="emailError">{{ emailError }}</div>
      </div>

      <div class="col-12 col-md-6 mb-3">
      <label>Mobile:</label>
      <input v-model="mobile" name="mobile" type="mobile" />
      <div class="text-danger" v-if="mobileError">{{ mobileError }}</div>
      </div>

      </div>

      <button type="submit" class="btn btn-primary" @click="console.log('Clicked')">Sign Up</button>

    </form>
  </div>
</template>

<script>
  export default {
    name: 'SignUp',
    data() {
      return {
        fname: '',
        lname: '',
        password: '',
        confirmPassword: '',
        birthDate: '',
        email: '',

        //ERRORS
        fnameError: '',
        lnameError: '',
        passwordError: '',
        confirmPasswordError: '',
        birthDateError: '',
        emailError: '',
        mobileError: '',

        submitted: false, // initialise submit variable
      };
    },
    methods: {
        async handleSubmit(event) {
          event.preventDefault(); 
          this.clearErrors(); // clear any previous errors to make sure they dont cause problems for new inputs which are now correct

          
            // error logic for form
          let valid = true;
    
          if (!this.fname.match(/^[A-Za-z\s]+$/)) {
            this.fnameError = 'First name must only contain letters';
            valid = false; //if the name contains any of the symbols, return error message, dont allow form to submit
          }

          if (!this.lname.match(/^[A-Za-z\s]+$/)) {
            this.lnameError = 'Last name must only contain letters';
            valid = false; //if the name contains any of the symbols, return error message, dont allow form to submit
          }
    
          if (this.password.length < 8) {
            this.passwordError = 'Password must be at least 8 characters';
            valid = false; // must reach a certain length
          }
    
          if (this.password !== this.confirmPassword) {
            this.confirmPasswordError = 'Passwords do not match';
            valid = false; // does it match?
          }


          if (!this.birthDate) {
            this.birthDateError = 'Date of birth is required';
            valid = false;
          } else {
            const dob = new Date(this.birthDate); // get birthdate from form
            const today = new Date(); // this is the logic. must be at least 16 years old.
            const minAgeDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate()); // create a new object which is the day, get the year, month and day and minus 16 from the year. 
          
            if (dob > minAgeDate) {
              this.birthDateError = 'Must be at least 16 years old'; // if we are within 16 years, return error message dob = input, if the input is greater (meaning closer to the date now) return error
              valid = false;
            }
          }

          if (!this.mobile.match(/^04\d{8}$/)) {
            this.mobileError = 'Mobile number must be 10 digits and start with 04';
            valid = false; // must be 10 digits starting with 04
          }
    
          if (!this.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            this.emailError = 'Invalid email format';
            valid = false; //must include a '.com' and '@'
          }

          
          if (!valid) {
          console.log("Validation failed");
          return;
        }

        try {
          const formData = new FormData();
          formData.append("fname", this.fname);
          formData.append("lname", this.lname);
          formData.append("email", this.email);
          formData.append("mobile", this.mobile);
          formData.append("birthDate", this.birthDate);
          formData.append("password", this.password);
          formData.append("confirmPassword", this.confirmPassword);

          const response = await fetch("/cos30043/s102674782/Assignment3/signup.php", {
            method: "POST",
            body: formData,
          });

        

          if (response.ok) { // if response isnt an error
            const result = await response.json();
            console.log("Success:", result.message); // was used to debug an issue when appending users to Json file
            this.$router.push("/login"); // once signup is valid, direct them to login page
          } else if (response.status === 409) {
            this.errors.email = "Email already exists."; // error if email exists
          } else if (response.status === 400) { 
            const result = await response.json();
            this.errors = result.errors;
          } else {
            alert("Something went wrong. Please try again."); // general umbrella of errors, connection, file not located...
          }
        } catch (err) {
          console.error("Fetch failed:", err);
        }
      },

        clearErrors() {
          this.fnameError = '';// re-initialise these variables
          this.lnameError = ''; // clear errors so they dont stack or if it prevents a future valid sign up
          this.passwordError = '';
          this.confirmPasswordError = '';
          this.mobileError = '';
          this.emailError = ''; 
          this.birthDateError = '';
          
        }
      },
      
    mounted() {
    
    }
  };
</script>

<style scoped>

</style>
