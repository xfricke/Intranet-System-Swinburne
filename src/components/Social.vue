<template>
  <div class="container mt-5">
    <!-- is user logged in? if so show... -->
    <div v-if="currentUser">
      <h3>{{ currentUser.fname }}'s Tasks</h3>

      <!--task section-->
      <div class="mt-3">
        <div class="input-group mb-3">
        <input v-model="newTask" type="text" class="form-control" placeholder="Enter new task" />
        <button @click="addTask" class="btn btn-primary">Add</button>
      </div>
  
      <div class="row mb-1">
        <div class="col-6">
          <input
            v-model="filters.task_id"
            type="text"
            class="form-control"
            placeholder="Search by Task ID"
          />
        </div>
        <div class="col-6">
          <input
            v-model="filters.task_title"
            type="text"
            class="form-control"
            placeholder="Search by Task Title"
          />
        </div>
      </div>
  
      <ul class="list-group">
        <li
          v-for="(task, index) in filteredTasks"
          :key="index"
          class="list-group-item d-flex justify-content-between align-items-start"
        >
          <div>
            {{ task.task_id }} | {{ task.task_title }} |
            <span v-if="task.priority === 'high'" class="text-danger fw-bold">(High Priority)</span>
            <span v-else class="text-muted">(Low Priority)</span>
          </div>
          <div>
            <button
              @click="togglePriority(task)"
              class="btn btn-sm btn-outline-warning me-2"
            >
              {{ task.priority === 'low' ? 'High Priority' : 'Low Priority' }}
            </button>
            <button
              @click="deleteTask(index)"
              class="btn btn-sm btn-outline-danger"
            >
              Delete
            </button>
          </div>
        </li>
      </ul>
      </div>

      <!-- other users -->
      <div class="mt-5">
        <h4>Other Users</h4>
        <ul class="list-group">
          <li v-for="user in otherUsers" :key="user.email" class="list-group-item">
            👤 {{ user.fname }} — {{ user.email }}
          </li>
        </ul>
      </div>
    </div>

    <!-- if user isnt logged In -->
    <div v-else class="card p-4 text-center">
      <h2>Welcome to the Intranet's Social Page!</h2>
      <p>Unfortunately you are not signed in, therefore we cannot display any social content at this time.</p>
    </div>
  </div>
</template>
  
  <script>
  export default {
    name: "SocialPage",

    data() {
      return {
        userFirstName: localStorage.getItem('userFirstName') || 'User',
        count: 4,
        allUsers: [],
        currentUser: null,
        newTask: "",
        filters: {
          task_id: "",
          task_title: ""
        },
        tasks: [
          { task_id: "Task - 1", task_title: "Explore the Intranet space!", priority: "high" },
        ]
      };
    },
    computed: {
      otherUsers() {
        return this.allUsers.filter(user => user.email !== this.currentUser?.email);
      },
      filteredTasks() {
        return this.tasks.filter(task => {
          const idMatch = task.task_id.toLowerCase().includes(this.filters.task_id.toLowerCase());
          const nameMatch = task.task_title.toLowerCase().includes(this.filters.task_title.toLowerCase());
          return idMatch && nameMatch;
        });
      }
    },
    methods: {
      addTask() {
        if (this.newTask.trim() !== "") { // is input empty?
          const incrementID = "Task - " + String(this.count).padStart(1, ""); // increment task number
          this.tasks.push({
            task_id: incrementID, // increment
            task_title: this.newTask.trim(), // add input
            priority: "low" // default priority
          });
          alert("Task " + this.newTask + " has been added!"); // confirmation of task
          this.newTask = ""; // clear input box
          this.count++; // update count, keeps track of task number
        } else {
          alert("Task cannot be empty!"); // empty check
        }
      },
      deleteTask(index) {
        this.tasks.splice(index, 1); // deletion of task on line associated
      },
      togglePriority(task) {
        task.priority = task.priority === "low" ? "high" : "low"; // toggle flip of task, if 'high', toggle to 'low'
      }
    },
    mounted() {
    const stored = localStorage.getItem("currentUser"); 
    if (stored) {
      this.currentUser = JSON.parse(stored);
    }

    fetch("users.json") // fetch users file, used when displaying other users
      .then(res => res.json())
      .then(data => {
        this.allUsers = data;
      })
      .catch(err => {
        console.error("Error loading users:", err);
      });
  }
};
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
  