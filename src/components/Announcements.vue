<template>
  <div class="container mt-5">
    <!-- is user logged in? if so show... -->
    <div v-if="currentUser">
      <div class="row mb-3">
        <div class="col-md-6 mb-2">
          <label for="titleSearch" class="form-label">Search by title:</label>
          <input
            v-model="filters.announcement.title"
            type="text"
            class="form-control"
            placeholder="Search by Title"
          />
        </div>

        <div class="col-md-6 mb-2">
          <label for="categoryFilter" class="form-label">Filter by category:</label>
          <select v-model="selectedCategory" class="form-select" id="categoryFilter">
            <option value="">All</option>
            <option v-for="category in availableCategories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </div>
      </div>

      <div v-if="paginatedAnnouncements.length === 0" class="text-center text-muted mt-4">
        No announcements match your search.
      </div>
      <div class="text-muted small">Debug: {{ paginatedAnnouncements.length }} announcements showing</div>
      

      <div class="row mb-3">
        <div class="col mb-3" v-for="announcement in paginatedAnnouncements" :key="announcement.title">
          <div class="card h-100" style="min-height: 230px;">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title">{{ announcement.title }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ announcement.date }} — {{ announcement.category }}</h6>
                <p class="card-text">{{ announcement.content }}</p>
              </div>

              <div class="pt-2">
                <button
                  class="btn btn-outline-success btn-sm"
                  @click="acknowledge(announcement.title)"
                  :disabled="acknowledged.includes(announcement.title)"
                >
                  👍 Acknowledge
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- pagination -->
      <div class="mt-4 d-flex justify-content-center" style="min-height: 60px;">
        <nav v-if="pageCount > 1">
          <ul class="pagination">
            <li
              class="page-item"
              v-for="page in pageCount"
              :key="page"
              :class="{ active: page === currentPage }"
            >
              <button class="page-link" @click="currentPage = page">{{ page }}</button>
            </li>
          </ul>
        </nav>
      </div>

      <!-- acknowledged list -->
      <div v-if="acknowledged.length" class="col mt-4">
        <h4>Acknowledged</h4>
        <ul class="list-group">
          <li
            v-for="title in acknowledged"
            :key="title"
            class="list-group-item list-group-item-success"
          >
            ✅ {{ title }}
          </li>
        </ul>
      </div>
    </div>

    <!-- if user isnt logged In -->
    <div v-else class="card p-4 text-center">
      <h2>Welcome to the Intranet's Announcements Page!</h2>
      <p>
        Unfortunately, you are not signed in, so we cannot display any announcements to you at this time.
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AnnouncementsPage',
 
  data() {
    return {
        currentUser: null, // initialise currentUser, this value will be confirmed later so we assume they arent logged in initially
        announcements: [],
        acknowledged: [],
        userEmail: "",
        loading: true,
        
        selectedCategory: '',

        filters: {
          announcement: {
            title: '' // initialise blank value, keep input box clean so titles aren't filtered when page loads
          }
        },

        currentPage: 1, //pagination,
        perPage: 3 //how many items do we show maximum per page?
       
    };
  },

  methods: {
    acknowledge(title) {
      console.log("Clicked Acknowledge:", title); // ✅ should show up in console

      if (!this.userEmail || this.acknowledged.includes(title)) return;

      fetch("/cos30043/s102674782/Assignment3/acknowledged.php", { //locate acknowldgements
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          email: this.userEmail, // we add the associated user to the data structure
          announcement: title
        })
      })
        .then(res => res.json())
        .then(response => {
          console.log("Acknowledged:", response);
          this.acknowledged.push(title); // display the title of the announcement as what is acknowledged in the acknowledged list
        })
        .catch(err => {
          console.error("Failed to acknowledge announcement:", err);
        });
    }
  },

  computed: {
    filteredAnnouncements() {
      const title = this.filters?.announcement?.title?.toLowerCase() || "";
      const category = this.selectedCategory;

      return this.announcements.filter((announcement) => {
        const titleMatch = announcement.title.toLowerCase().includes(title);
        const categoryMatch = category === "" || announcement.category === category;
        return titleMatch && categoryMatch;
      });
    },
    availableCategories() {
      const categories = this.announcements.map(a => a.category); // extract category field
      return [...new Set(categories)]; // remove duplicates
    },
    pageCount() {
      return Math.ceil(this.filteredAnnouncements.length / this.perPage);
    },
    paginatedAnnouncements() {
      const start = (this.currentPage - 1) * this.perPage;
      return this.filteredAnnouncements.slice(start, start + this.perPage);
    }
  },

  mounted() {
  const stored = localStorage.getItem("currentUser");
  if (stored) {
    this.currentUser = JSON.parse(stored);
  }

  if (this.currentUser && this.currentUser.email) {
    this.userEmail = this.currentUser.email;
  }

  // Only fetch announcements if logged in
  if (this.currentUser) {
    fetch("/cos30043/s102674782/Assignment3/announcements.json")
      .then((res) => {
      if (!res.ok) throw new Error(`HTTP error ${res.status}`); // what is the http error?
      return res.json();
      })
      .then((data) => {
        console.log("✅ Announcements loaded:", data);
        this.announcements = data;
      })
      .catch((error) => { // debugging errors, allows me to see if the json has actually been located or not, helped with an issue where pagination wasnt displaying the announcements
        console.error("❌ Failed to load announcements:", error);
      });

    fetch("/cos30043/s102674782/Assignment3/acknowledged.json")
      .then((res) => res.json())
      .then((data) => {
        const email = this.currentUser.email;
        if (email && data[email]) {
          this.acknowledged = data[email];
        }
      })
      .catch((err) => {
        console.error("Error loading acknowledged list:", err);
      });
  }
}
}
</script>
<style scoped>
 .card {
  border-left: 5px solid #0d6efd;
}

.card-body {
  padding-bottom: 1rem;
}

.card-text {
  margin-bottom: 0.5rem;
}
</style>
  