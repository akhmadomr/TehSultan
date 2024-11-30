<template>
  <div class="container mx-auto p-4">
    <!-- Button to show add user form -->
    <button @click="showAddUserForm = true" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 hover:bg-blue-600">Add User</button>

    <!-- Add User Form -->
    <div v-if="showAddUserForm" class="popup">
      <div class="popup-content">
        <h2 class="text-xl font-semibold mb-4">Add New User</h2>
        <form @submit.prevent="addUser">
          <div class="mb-4">
            <label for="nama" class="block text-sm font-medium">Nama</label>
            <input v-model="newUser.nama" type="text" id="nama" class="w-full p-2 border border-gray-300 rounded mt-1" required />
          </div>
          <div class="mb-4">
            <label for="username" class="block text-sm font-medium">Username</label>
            <input v-model="newUser.username" type="text" id="username" class="w-full p-2 border border-gray-300 rounded mt-1" required />
          </div>
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium">Email</label>
            <input v-model="newUser.email" type="email" id="email" class="w-full p-2 border border-gray-300 rounded mt-1" required />
          </div>
          <div class="mb-4">
            <label for="role" class="block text-sm font-medium">Role</label>
            <select v-model="newUser.role" id="role" class="w-full p-2 border border-gray-300 rounded mt-1" required>
              <option value="manager">Manager</option>
              <option value="crewoutlet">Crew Outlet</option>
              <option value="gudang">Gudang</option>
            </select>
          </div>
          <div class="mb-4">
            <label for="password" class="block text-sm font-medium">Password</label>
            <input v-model="newUser.password" type="password" id="password" class="w-full p-2 border border-gray-300 rounded mt-1" required />
          </div>
          <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
            <input v-model="newUser.password_confirmation" type="password" id="password_confirmation" class="w-full p-2 border border-gray-300 rounded mt-1" required />
          </div>
          <div class="flex justify-end">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
            <button @click="showAddUserForm = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit User Form -->
    <div v-if="showEditUserForm" class="popup">
      <div class="popup-content">
        <h2 class="text-xl font-semibold mb-4">Edit User</h2>
        <form @submit.prevent="updateUser">
          <div class="mb-4">
            <label for="nama" class="block text-sm font-medium">Name</label>
            <input v-model="editUser.nama" type="text" id="edit-nama" class="w-full p-2 border border-gray-300 rounded mt-1" required />
          </div>
          <div class="mb-4">
            <label for="username" class="block text-sm font-medium">Username</label>
            <input v-model="editUser.username" type="text" id="edit-username" class="w-full p-2 border border-gray-300 rounded mt-1" required />
          </div>
          <div class="mb-4">
            <label for="role" class="block text-sm font-medium">Role</label>
            <select v-model="editUser.role" id="edit-role" class="w-full p-2 border border-gray-300 rounded mt-1" required>
              <option value="manager">Manager</option>
              <option value="crewoutlet">Crew Outlet</option>
              <option value="gudang">Gudang</option>
            </select>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
            <button @click="showEditUserForm = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- User List Table -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-4">
      <table class="min-w-full text-sm text-left text-gray-500">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-6 py-3">Name</th>
            <th class="px-6 py-3">Username</th>
            <th class="px-6 py-3">Role</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
            <td class="px-6 py-4">{{ user.nama }}</td>
            <td class="px-6 py-4">{{ user.username }}</td>
            <td class="px-6 py-4">{{ user.role }}</td>
            <td class="px-6 py-4">
              <span :class="user.is_active ? 'bg-green-500' : 'bg-red-500'" class="text-white px-2 py-1 rounded-full">
                {{ user.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 space-x-2">
              <button @click="prepareEditUser(user)" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</button>
              <button @click="deleteUser(user.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
              <button @click="toggleStatus(user.id)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{ user.is_active ? 'Deactivate' : 'Activate' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  components: {

  },
  props: {
    auth: Object,
  },
  data() {
    return {
      authUser: this.auth,
      showAddUserForm: false,
      showEditUserForm: false,
      users: [],
      newUser: {
        nama: '',
        username: '',
        role: '',
        password: '',
        password_confirmation: '',
      },
      editUser: {
        id: '',
        nama: '',
        username: '',
        role: ''
      },
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    fetchUsers() {
      axios.get(route('manager.users.index'))
        .then(response => {
          this.users = response.data;
        })
        .catch(error => {
          console.error('Error fetching users:', error);
        });
    },
    addUser() {
      axios.post('/manager/users', this.newUser)
        .then(response => {
          this.users.push(response.data);
          this.showAddUserForm = false;
          this.resetNewUser();
        })
        .catch(error => {
          console.error('Error adding user:', error);
        });
    },
    prepareEditUser(user) {
      this.editUser = { ...user };
      this.showEditUserForm = true;
    },
    updateUser() {
      axios.put(`/manager/users/${this.editUser.id}`, this.editUser)
        .then(response => {
          const index = this.users.findIndex(u => u.id === this.editUser.id);
          if (index !== -1) {
            this.users[index] = response.data;
          }
          this.showEditUserForm = false;
        })
        .catch(error => {
          console.error('Error updating user:', error);
        });
    },
    deleteUser(id) {
      axios.delete(`/manager/users/${id}`)
        .then(() => {
          this.users = this.users.filter(user => user.id !== id);
        })
        .catch(error => {
          console.error('Error deleting user:', error);
        });
    },
    toggleStatus(id) {
      axios.patch(`/manager/users/${id}/toggle-status`)
        .then(response => {
          const index = this.users.findIndex(u => u.id === id);
          this.$set(this.users, index, response.data);
        })
        .catch(error => {
          console.error('Error toggling status:', error);
        });
    },
    resetNewUser() {
      this.newUser = {
        nama: '',
        username: '',
        role: '',
        password: '',
        password_confirmation: '',
      };
    },
  },
};
</script>

<style scoped>
.popup {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-content {
  background: white;
  padding: 20px;
  border-radius: 5px;
  width: 400px;
}

button {
  padding: 8px 16px;
  border-radius: 4px;
  transition: background-color 0.3s;
}

button:hover {
  background-color: #ddd;
}
</style>