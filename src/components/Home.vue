<template>
  <div class="container mt-5">
  
    <div class="text-center mb-4">
      <h1 class="fw-bold text-primary">Welcome to the Intranet</h1>
    </div>

    
    <div class="mb-5 text-center">
      <p class="lead">
        This platform serves as your central hub for announcements, social engagement, and internal communication.
        Use the navigation above to explore features, connect with colleagues, and stay up to date with the latest from your team.
      </p>
    </div>

    <!--image section-->
    <div class="row text-center">
      <div class="col-md-6 mb-4">
        <img src="images/office.png" class="img-fluid" alt="office">
        <p class="mt-2 text-muted">Collaborate with your team effectively</p>
      </div>

      <div class="col-md-6 mb-4">
        <img src="images/intranet.png" class="img-fluid" alt="intranet">
        <p class="mt-2 text-muted">Stay informed with real-time updates</p>
      </div>
    </div>

    <!-- Tech News -->
    <div class="mt-5">
      <h4 class="text-primary">Latest Tech News</h4>
      <ul class="list-group">
        <li
          v-for="(news, index) in techNews"
          :key="index"
          class="list-group-item"
        >
          <h6 class="fw-bold mb-1">
            <a :href="news.url" target="_blank">{{ news.title }}</a>
          </h6>
          <p class="mb-0">{{ news.description }}</p>
        </li>
      </ul>
    </div>
  </div>

</template>
<script>
  export default {
    name: 'HomePage',
    props: {
    },

  data() {
    return {
        techNews: []
      };
    
  },

  mounted(){
    fetch("/cos30043/s102674782/Assignment3/techNews.json") // fetch api json
      .then((res) => res.json()) // response
      .then((data) => {
        this.techNews = data; //data
      })
      .catch((err) => {
        console.error("Error loading tech news:", err); //error message
    });
  },

  methods: {
  
  }
}
</script>
<style scoped>

img {
  max-height: 300px;
  object-fit: cover; /*make sure image isn't massive and doesnt overflow out of the wrapper*/
}
</style>