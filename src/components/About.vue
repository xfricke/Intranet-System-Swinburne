<template>
    <div class="container mt-5">
      <div class="d-flex justify-content-center mb-4">
        <h1 class="fw-bold text-primary">About Us</h1>
      </div>
    
      <div class="row mb-3">
        <div class="col-md-6 mb-2">
          <h3 class="fw-bold mb-3 text-primary">Who We Are</h3>
          <p>
            Our internal intranet system is designed to centralise communication, 
            streamline access to resources and to create a sense of connection across all departments. 
            Here you catch up on the latest announcements, acknowledge important updates or access shared documentation.
            Built with security, usability, and scalability in mind, this system is the foundation of our digital workspace — a tool to keep everyone informed, engaged, and aligned.
          </p>
        </div>
        
        <div class="col-md-6 mb-2">
          <h3 class="fw-bold mb-3 text-primary">Why is it so Effective?</h3>
          <p>
            The intranet isn't just another platform — it's an efficient workspace tailored to your productive needs. 
            We recognized the need for a centralised space where information is easy to find, messages aren't lost in email threads, and important updates reach everyone in real time.
            From tech updates to staff announcements and interactive features like acknowledgment tracking, our intranet brings structure and clarity to internal communications. 
         </p>
        </div>

      </div>  







      <div class="d-flex justify-content-center">
        <div class="card p-4" style="max-width: 500px; width: 100%;">
          <h4 class="text-center mb-4">Please fill in this form! It informs us you are not a bot!</h4>
          <form @submit.prevent="handleSubmit">
            
            <div class="mb-3">
              <label class="form-label">First Name:</label>
              <input v-model="firstName" type="text" required class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Last Name:</label>
              <input v-model="lastName" type="text" required class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Location:</label><br />
              <div class="form-check form-check-inline">
                <input
                  type="radio"
                  id="sea"
                  value="Sea"
                  v-model="location"
                  class="form-check-input"
                />
                <label class="form-check-label" for="sea">Sea</label>
              </div>
              <div class="form-check form-check-inline">
                <input
                  type="radio"
                  id="mountain"
                  value="Mountain"
                  v-model="location"
                  class="form-check-input"
                />
                <label class="form-check-label" for="mountain">Mountain</label>
              </div>

              <div v-if="seaChecked">Wecome {{firstName + '! '}} You chose 'Sea'! Interesting decision...   <img src="images/sea.png" alt="Sea" class="img-fluid rounded" style="max-width: 450px; "></div>
              <div v-if="mountainChecked">Wecome {{firstName + '! '}} You chose 'Mountain'! Interesting decision...     <img src="images/mountain.png" alt="Mountain" class="img-fluid rounded" style="max-width: 450px;"></div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>

          </form>
        </div>
      </div>

    </div>
</template>

<script>
export default {
  name: 'AboutPage',
  props: {
    msg: String
  },

  data() {
    return {
      firstName: '',
      lastName: '',
      location: '',
      mountainChecked: false,
      seaChecked: false
    };
  },

  methods: {
    handleSubmit() {
      if(this.location == 'Sea'){
        this.seaChecked = true;
        this.mountainChecked = false;
      }
      else if(this.location == 'Mountain'){
        this.mountainChecked = true;
        this.seaChecked = false;
      }
    }
  }
}
</script>
<style>
@media (max-width: 576px) {
  img {
    max-width: 400px !important;
  }
}
</style>
  